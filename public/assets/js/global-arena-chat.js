(() => {
  'use strict';

  const apiUrl = '/api/arena_live.php';
  let since = 0;
  let busy = false;
  let open = false;
  let timer = 0;

  function createChat() {
    if (document.getElementById('za-global-chat')) return;

    const style = document.createElement('style');
    style.textContent = `
      #za-global-chat{position:fixed;left:12px;bottom:12px;z-index:20000;width:min(320px,calc(100vw - 24px));font:13px Arial,Helvetica,sans-serif;color:#eff8ff}
      #za-global-chat *{box-sizing:border-box}
      #za-global-chat-panel{display:none;margin-bottom:8px;overflow:hidden;border:1px solid rgba(0,240,255,.45);border-radius:10px;background:rgba(8,16,30,.96);box-shadow:0 8px 28px rgba(0,0,0,.45)}
      #za-global-chat.open #za-global-chat-panel{display:block}
      #za-global-chat-head{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:9px 11px;background:#14253a;font-weight:700}
      #za-global-chat-head small{font-weight:400;opacity:.75}
      #za-global-chat-close{padding:1px 6px;border:0;background:transparent;color:#fff;font-size:20px;cursor:pointer}
      #za-global-chat-status{padding:6px 9px;color:#b5c6d9;font-size:11px}
      #za-global-chat-messages{height:180px;overflow-y:auto;padding:8px}
      #za-global-chat-messages div{margin:4px 0;overflow-wrap:anywhere}
      #za-global-chat-messages b{color:#54dcff}
      #za-global-chat-form{display:flex;border-top:1px solid #304156}
      #za-global-chat-input{flex:1;min-width:0;padding:9px;border:0;background:#101b2a;color:#fff;outline:none}
      #za-global-chat-form button{padding:8px 11px;border:0;background:#08bde8;color:#06131b;font-weight:700;cursor:pointer}
      #za-global-chat-toggle{min-width:108px;padding:11px 15px;border:1px solid rgba(0,240,255,.7);border-radius:10px;background:rgba(8,16,30,.94);color:#8ff9ff;font-weight:700;cursor:pointer;box-shadow:0 4px 16px rgba(0,0,0,.35)}
      #za-global-chat-toggle:hover,#za-global-chat-close:hover{filter:brightness(1.2)}
      @media(max-width:520px){#za-global-chat{left:8px;bottom:8px;width:min(290px,calc(100vw - 16px))}#za-global-chat-messages{height:140px}}
      #arenaChat,#go-live-chat,#chat-panel{display:none!important}
    `;
    document.head.appendChild(style);

    const root = document.createElement('section');
    root.id = 'za-global-chat';
    root.innerHTML = `
      <div id="za-global-chat-panel" aria-label="Chat globale">
        <div id="za-global-chat-head"><span>CHAT GLOBALE</span><small id="za-global-chat-online">— online</small><button id="za-global-chat-close" type="button" aria-label="Chiudi chat">×</button></div>
        <div id="za-global-chat-messages" aria-live="polite"></div>
        <div id="za-global-chat-status" role="status">Connessione alla chat…</div>
        <form id="za-global-chat-form"><input id="za-global-chat-input" maxlength="200" autocomplete="off" placeholder="Scrivi a tutti i giocatori" aria-label="Messaggio chat"><button type="submit">INVIA</button></form>
      </div>
      <button id="za-global-chat-toggle" type="button" aria-expanded="false">💬 CHAT</button>
    `;
    document.body.appendChild(root);

    const toggle = root.querySelector('#za-global-chat-toggle');
    const input = root.querySelector('#za-global-chat-input');
    const status = root.querySelector('#za-global-chat-status');
    const messages = root.querySelector('#za-global-chat-messages');
    const online = root.querySelector('#za-global-chat-online');

    function setOpen(value) {
      open = value;
      root.classList.toggle('open', open);
      toggle.setAttribute('aria-expanded', String(open));
      toggle.textContent = open ? '💬 CHIUDI CHAT' : '💬 CHAT';
      if (open) input.focus();
    }

    async function call(action, payload = {}) {
      const response = await fetch(apiUrl, {
        method: 'POST',
        credentials: 'include',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
        body: JSON.stringify({ action, ...payload }),
        cache: 'no-store'
      });
      const text = await response.text();
      let data;
      try {
        data = JSON.parse(text);
      } catch {
        throw new Error('Risposta del server non valida.');
      }
      if (!response.ok || data.success !== true) {
        throw new Error(data.error || `Errore chat (HTTP ${response.status}).`);
      }
      return data;
    }

    function render(rows) {
      for (const message of rows) {
        const id = `za-global-msg-${message.id}`;
        if (document.getElementById(id)) continue;
        const row = document.createElement('div');
        row.id = id;
        const username = document.createElement('b');
        username.textContent = String(message.username || 'Giocatore');
        row.append(username, document.createTextNode(`: ${String(message.text || '')}`));
        messages.appendChild(row);
      }
      while (messages.children.length > 100) messages.firstElementChild.remove();
      messages.scrollTop = messages.scrollHeight;
    }

    async function sync() {
      if (busy) return;
      busy = true;
      try {
        const data = await call('sync', { since });
        render(data.messages || []);
        if (Number.isFinite(Number(data.last_chat))) since = Number(data.last_chat);
        online.textContent = `${Number(data.online_count) || 0} online`;
        status.textContent = 'Chat condivisa tra i giochi Zero World.';
      } catch (error) {
        status.textContent = error.message || 'Chat non disponibile.';
      } finally {
        busy = false;
      }
    }

    toggle.addEventListener('click', () => setOpen(!open));
    root.querySelector('#za-global-chat-close').addEventListener('click', () => setOpen(false));
    input.addEventListener('keydown', (event) => {
      event.stopPropagation();
      if (event.key === 'Escape') setOpen(false);
    });
    root.querySelector('#za-global-chat-form').addEventListener('submit', async (event) => {
      event.preventDefault();
      const text = input.value.trim();
      if (!text) return;
      try {
        await call('chat', { text });
        input.value = '';
        await sync();
      } catch (error) {
        status.textContent = error.message || 'Invio del messaggio non riuscito.';
      }
    });

    sync();
    timer = window.setInterval(sync, 1800);
    window.addEventListener('pagehide', () => window.clearInterval(timer), { once: true });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', createChat, { once: true });
  } else {
    createChat();
  }
})();
