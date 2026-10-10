/* Account Agar: usa l'API a token di api/ (login.php, register.php, me.php).
   Il salvataggio delle partite è fatto solo dal server di gioco (save.php, firmato). */
(function () {
  'use strict';
  var TOKEN_KEY = 'zl_agar_token';
  var BASE = 'api/';
  var account = { ready: false, user: null, token: null };
  window.ZLAgarAccount = account;

  function post(path, body) {
    return fetch(BASE + path, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(body)
    }).then(function (r) {
      return r.json().catch(function () { throw new Error('Risposta non valida (HTTP ' + r.status + ')'); })
        .then(function (d) { if (!r.ok || !d.ok) throw new Error(d.error || 'Richiesta non riuscita'); return d; });
    });
  }
  function me(token) {
    return fetch(BASE + 'me.php', { headers: { Authorization: 'Bearer ' + token } }).then(function (r) {
      return r.json().then(function (d) { if (!r.ok || !d.ok) throw new Error(d.error || 'Sessione scaduta'); return d.user; });
    });
  }
  function readToken() { try { return localStorage.getItem(TOKEN_KEY); } catch (e) { return null; } }
  function writeToken(t) { try { t ? localStorage.setItem(TOKEN_KEY, t) : localStorage.removeItem(TOKEN_KEY); } catch (e) {} }

  var panel, status;
  function buildPanel() {
    var anchor = document.getElementById('nick');
    if (!anchor) return;
    panel = document.createElement('div');
    panel.id = 'zlAgarAccount';
    panel.style.cssText = 'margin:8px 0;font:13px/1.4 Arial,sans-serif';
    panel.innerHTML =
      '<input id="zlAgarUser" maxlength="16" placeholder="Username" autocomplete="username" style="width:100%;padding:6px;margin-bottom:4px">' +
      '<input id="zlAgarPass" type="password" maxlength="72" placeholder="Password (min 8)" autocomplete="current-password" style="width:100%;padding:6px;margin-bottom:4px">' +
      '<div style="display:flex;gap:6px"><button type="button" id="zlAgarLogin">Accedi</button><button type="button" id="zlAgarRegister">Registrati</button><button type="button" id="zlAgarLogout" style="display:none">Esci</button></div>' +
      '<div id="zlAgarStatus" role="status" style="margin-top:6px;color:#168c4a"></div>';
    anchor.parentNode.insertBefore(panel, anchor.nextSibling);
    status = document.getElementById('zlAgarStatus');
    document.getElementById('zlAgarLogin').onclick = function () { auth('login'); };
    document.getElementById('zlAgarRegister').onclick = function () { auth('register'); };
    document.getElementById('zlAgarLogout').onclick = logout;
  }

  function setSignedIn(user) {
    account.user = user;
    var nick = document.getElementById('nick');
    if (nick) { nick.value = user.name; nick.readOnly = true; }
    document.getElementById('zlAgarLogin').style.display = 'none';
    document.getElementById('zlAgarRegister').style.display = 'none';
    document.getElementById('zlAgarUser').style.display = 'none';
    document.getElementById('zlAgarPass').style.display = 'none';
    document.getElementById('zlAgarLogout').style.display = '';
    status.textContent = 'Connesso come ' + user.name + ' · Livello ' + user.level + ' · ' + user.xp + ' XP';
    window.dispatchEvent(new CustomEvent('zl-agar-account-ready', { detail: account }));
  }
  function setSignedOut(msg) {
    account.user = null; account.token = null;
    var nick = document.getElementById('nick');
    if (nick) nick.readOnly = false;
    ['zlAgarLogin', 'zlAgarRegister', 'zlAgarUser', 'zlAgarPass'].forEach(function (id) {
      document.getElementById(id).style.display = '';
    });
    document.getElementById('zlAgarLogout').style.display = 'none';
    status.textContent = msg || '';
    status.style.color = msg ? '#b00020' : '#168c4a';
  }

  function auth(kind) {
    var u = document.getElementById('zlAgarUser').value.trim();
    var p = document.getElementById('zlAgarPass').value;
    status.style.color = '#168c4a';
    status.textContent = 'Attendere…';
    post(kind === 'login' ? 'login.php' : 'register.php', { username: u, password: p })
      .then(function (d) {
        account.token = d.token; writeToken(d.token);
        setSignedIn(d.user);
      })
      .catch(function (e) { setSignedOut(e.message); });
  }

  function logout() { writeToken(null); setSignedOut('Disconnesso.'); }

  function init() {
    buildPanel();
    var t = readToken();
    if (!t) { account.ready = true; return; }
    me(t)
      .then(function (user) { account.token = t; setSignedIn(user); })
      .catch(function () { writeToken(null); setSignedOut(''); })
      .finally(function () { account.ready = true; });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
