/* Reuses the signed-in Zero World session and Agar profile stored in the portal DB. */
(function () {
  var account = { ready: false, user: null, profile: null };
  var accountStatus;
  window.ZLAgarAccount = account;
  window.__agarToken = function () { return null; };

  function api(path, body) {
    var url = window.zeroBackendUrl ? window.zeroBackendUrl(path) : path;
    var options = { method: body ? 'POST' : 'GET', credentials: 'include', headers: {} };
    var controller = new AbortController();
    var timeout = setTimeout(function () { controller.abort(); }, 8000);
    options.signal = controller.signal;
    if (body) {
      options.headers['Content-Type'] = 'application/json';
      options.body = JSON.stringify(body);
    }
    return fetch(url, options).then(function (response) {
      return response.json().catch(function () {
        throw new Error('Risposta API non valida (HTTP ' + response.status + ').');
      }).then(function (data) {
        if (!response.ok || data.success === false) throw new Error(data.error || 'Richiesta API non riuscita.');
        return data;
      });
    }).finally(function () { clearTimeout(timeout); });
  }

  function renderAccountStatus(text) {
    if (!accountStatus) {
      accountStatus = document.createElement('div');
      accountStatus.id = 'zlAgarAccountStatus';
      accountStatus.setAttribute('role', 'status');
      accountStatus.style.cssText = 'margin:5px 0;color:#168c4a;font:12px/1.4 Arial,sans-serif';
      var input = document.querySelector('.u-input');
      if (input) input.appendChild(accountStatus);
    }
    accountStatus.textContent = text;
  }

  function setAccount(user, profile) {
    account.user = user;
    account.profile = profile || null;
    var nickname = document.getElementById('nick');
    if (nickname) {
      nickname.value = user.username;
      nickname.readOnly = true;
      nickname.setAttribute('aria-label', 'Account Zero World: ' + user.username);
    }
    var level = profile && profile.level ? ' · Livello ' + profile.level : '';
    renderAccountStatus('Connesso con Zero World: ' + user.username + level);
    window.dispatchEvent(new CustomEvent('zl-agar-account-ready', { detail: account }));
  }

  window.addEventListener('zl-agar-game-over', function (event) {
    if (!account.user || account.submitting) return;
    var score = Math.max(0, Math.min(600000, Math.floor(Number(event.detail && event.detail.score) || 0)));
    if (!score) return;
    account.submitting = true;
    api('/api/agar_io.php?action=submit', { action: 'submit', score: score })
      .then(function (result) {
        if (account.profile) {
          account.profile.xp = Number(result.earned_xp || account.profile.xp || 0);
          account.profile.level = Number(result.level || account.profile.level || 1);
        }
        renderAccountStatus('Partita salvata per ' + account.user.username + ' · record ' + Number(result.new_high || 0));
      })
      .catch(function (error) {
        console.error('[ZeroLegend Agar] Salvataggio partita non riuscito:', error);
        renderAccountStatus('Account ' + account.user.username + ' · salvataggio non riuscito');
      })
      .finally(function () { account.submitting = false; });
  });

  var playButton = document.getElementById('playBtn');
  if (playButton) playButton.disabled = true;

  api('/api/auth.php?action=me')
    .then(function (session) {
      if (!session.user) return;
      var user = session.user;
      return api('/api/agar_io.php?action=load')
        .then(function (result) { setAccount(user, result.state); })
        .catch(function (error) {
          console.error('[ZeroLegend Agar] Caricamento profilo non riuscito:', error);
          setAccount(user, null);
          renderAccountStatus('Account ' + user.username + ' · profilo non disponibile');
        });
    })
    .catch(function (error) {
      console.error('[ZeroLegend Agar] Verifica sessione non riuscita:', error);
    })
    .finally(function () {
      account.ready = true;
      if (playButton) playButton.disabled = false;
    });
})();
