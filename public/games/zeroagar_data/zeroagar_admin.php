<?php
declare(strict_types=1);
/* Pannello amministrazione ZeroAgar. Mettilo nella stessa cartella di zeroagar_classic.php e zeroagar_economy.php.
   L'accesso è deciso dal server (ruolo admin/mod letto da ../api/api.php?action=me): questa pagina è solo l'interfaccia. */
header('Cache-Control: no-store');
?>
<!doctype html>
<html lang="it">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>ZeroAgar · Amministrazione</title>
<style>
*{box-sizing:border-box}body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#10141a;color:#e8ecf1;font-size:14px}
header{display:flex;justify-content:space-between;align-items:center;gap:10px;padding:12px 18px;background:#171d26;border-bottom:1px solid #2a3340;position:sticky;top:0;z-index:5;flex-wrap:wrap}
h1{margin:0;font-size:18px}h2{margin:18px 0 8px;font-size:15px}
nav{display:flex;gap:6px;flex-wrap:wrap;padding:10px 18px}nav button,.btn{border:0;border-radius:6px;padding:8px 13px;font-weight:700;cursor:pointer;background:#262d38;color:#e8ecf1}
nav button.on,.btn.blue{background:#13b8ef;color:#fff}.btn.green{background:#3fa400;color:#fff}.btn.red{background:#d63a4f;color:#fff}.btn.gray{background:#4a5361;color:#fff}.btn.sm{padding:5px 9px;font-size:12px}
main{padding:0 18px 40px;max-width:1100px;margin:0 auto}
.card{background:#171d26;border:1px solid #2a3340;border-radius:10px;padding:14px;margin:10px 0}
.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:10px}
.stat{background:#1d2530;border-radius:8px;padding:12px;text-align:center}.stat b{display:block;font-size:22px}.stat span{font-size:12px;color:#9aa4b2}
table{width:100%;border-collapse:collapse}th,td{padding:8px 6px;border-bottom:1px solid #2a3340;text-align:left;vertical-align:middle}th{font-size:12px;color:#9aa4b2;text-transform:uppercase}
.tw{overflow-x:auto}
input,select,textarea{background:#1d232c;color:#e8ecf1;border:1px solid #3a4351;border-radius:6px;padding:8px;font-size:14px;max-width:100%}
label{display:block;font-size:12px;color:#9aa4b2;margin:8px 0 3px}
.row{display:flex;gap:8px;flex-wrap:wrap;align-items:flex-end}.row>div{flex:1;min-width:130px}.row input,.row select{width:100%}
.tag{display:inline-block;padding:2px 7px;border-radius:10px;font-size:11px;font-weight:700;background:#3a4351}.tag.red{background:#d63a4f}.tag.orange{background:#e8890c}.tag.green{background:#3fa400}
.skin{display:inline-block;width:180px;margin:6px;vertical-align:top;text-align:center}.skin img{width:140px;height:140px;object-fit:cover;border-radius:50%;background:#2a3340;border:2px solid #3a4351}
#toast{position:fixed;left:50%;bottom:20px;transform:translateX(-50%);background:#000c;padding:10px 16px;border-radius:20px;display:none;z-index:50}
.muted{color:#9aa4b2;font-size:12px}.hide{display:none}
.opt{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #2a3340}.opt input[type=text],.opt input[type=number],.opt select{width:240px}
code{background:#1d2530;padding:2px 6px;border-radius:4px;word-break:break-all}
</style>
</head>
<body>
<header><h1>🛠 ZeroAgar · Amministrazione</h1><div><span id="who" class="muted"></span> <a class="btn gray sm" href="zeroagar_classic.php" style="text-decoration:none">← Torna al gioco</a></div></header>
<nav id="tabs"></nav>
<main id="view"><div class="card">Caricamento…</div></main>
<div id="toast"></div>
<script>
(()=>{
'use strict';
const API='zeroagar_economy.php';
const $=(s,r=document)=>r.querySelector(s);
const esc=s=>String(s??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]));
const fmt=ts=>ts?new Date(ts*1000).toLocaleString('it-IT'):'—';
let ME=null,CAT=null,TAB='stats';
function toast(t){const e=$('#toast');e.textContent=t;e.style.display='block';clearTimeout(toast.t);toast.t=setTimeout(()=>e.style.display='none',2600)}
async function call(action,payload){
  const r=await fetch(API+'?action='+encodeURIComponent(action),{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','X-Requested-With':'ZeroAgar'},body:JSON.stringify(payload||{})});
  const d=await r.json().catch(()=>null);
  if(!d||d.success===false)throw Error((d&&d.error)||'HTTP '+r.status);
  return d;
}
const go=async(fn,ok)=>{try{await fn();if(ok)toast(ok)}catch(e){toast('Errore: '+e.message)}};
const TABS=[['stats','📊 Panoramica'],['users','👥 Utenti e premi'],['events','🎉 Eventi'],['skins','🎨 Skin'],['settings','⚙️ Impostazioni e chat',true],['logs','📜 Log']];
function drawTabs(){
  const n=$('#tabs');n.innerHTML='';
  for(const [id,l,adm] of TABS){
    if(adm&&!ME.is_admin)continue;
    const b=document.createElement('button');b.textContent=l;b.className=id===TAB?'on':'';b.onclick=()=>{TAB=id;drawTabs();render()};n.appendChild(b);
  }
}
function render(){({stats:vStats,users:vUsers,events:vEvents,skins:vSkins,settings:vSettings,logs:vLogs})[TAB]()}
const V=h=>{$('#view').innerHTML=h};

/* ---------- panoramica ---------- */
async function vStats(){
  V('<div class="card">Caricamento…</div>');
  await go(async()=>{
    const s=await call('admin_stats');
    const it=[['players','Giocatori'],['online','Online (10 min)'],['banned','Bannati'],['muted','Silenziati'],['coins','Zero Coin in circolo'],['pending_skins','Skin da approvare'],['events','Eventi attivi'],['orders','Ordini pagati']];
    V('<div class="grid">'+it.map(([k,l])=>'<div class="stat"><b>'+esc(s[k])+'</b><span>'+l+'</span></div>').join('')+'</div>'+
      (s.pending_skins>0?'<div class="card">🎨 Ci sono <b>'+s.pending_skins+'</b> skin in attesa di approvazione. <button class="btn blue sm" id="gs">Vai alle skin</button></div>':''));
    const g=$('#gs');if(g)g.onclick=()=>{TAB='skins';drawTabs();render()};
  });
}

/* ---------- utenti ---------- */
let UQ='',UF='all',UP=0;
async function vUsers(){
  V('<div class="card"><div class="row"><div><label>Cerca nome</label><input id="uq" value="'+esc(UQ)+'" placeholder="nome utente"></div><div><label>Filtro</label><select id="uf"><option value="all">Tutti</option><option value="banned">Bannati</option><option value="muted">Silenziati</option></select></div><div style="flex:0"><button class="btn blue" id="us">Cerca</button></div></div></div><div id="ul" class="card">Caricamento…</div><div id="up"></div>');
  $('#uf').value=UF;
  const load=()=>go(async()=>{
    UQ=$('#uq').value.trim();UF=$('#uf').value;
    const r=await call('admin_users',{q:UQ,filter:UF,page:UP});
    $('#ul').innerHTML='<div class="tw"><table><tr><th>Utente</th><th>Ruolo</th><th>Lv</th><th>Coin</th><th>Partite</th><th>Stato</th><th>Ultimo accesso</th><th></th></tr>'+
      r.users.map(u=>'<tr><td><b>'+esc(u.username)+'</b></td><td>'+esc(u.role)+'</td><td>'+u.level+'</td><td>🪙 '+u.coins+'</td><td>'+u.games+'</td><td>'+(u.banned?'<span class="tag red">BAN'+(u.ban_until>0?' fino '+esc(fmt(u.ban_until)):'')+'</span> ':'')+(u.muted_until>Date.now()/1000?'<span class="tag orange">MUTE</span>':'')+'</td><td class="muted">'+esc(fmt(u.last_seen))+'</td><td><button class="btn blue sm" data-u="'+esc(u.uid)+'">Gestisci</button></td></tr>').join('')+'</table></div>'+
      '<div class="row" style="margin-top:10px"><button class="btn gray sm" id="pp">← Precedenti</button><button class="btn gray sm" id="pn">Successivi →</button></div>';
    $('#pp').onclick=()=>{UP=Math.max(0,UP-1);load()};$('#pn').onclick=()=>{if(r.users.length===40){UP++;load()}};
    $('#ul').querySelectorAll('[data-u]').forEach(b=>b.onclick=()=>manage(r.users.find(x=>x.uid===b.dataset.u)));
  });
  $('#us').onclick=()=>{UP=0;load()};$('#uq').onkeydown=e=>{if(e.key==='Enter'){UP=0;load()}};load();
}
function itemOpts(){
  if(!CAT)return '';
  let h='<option value="">— nessun oggetto —</option>';
  for(const id in CAT.skins)h+='<option value="skin:'+id+'">Skin · '+esc(CAT.skins[id].name)+'</option>';
  for(const id in CAT.upgrades)h+='<option value="upgrade:'+id+'">Potenziamento · '+esc(CAT.upgrades[id].name)+'</option>';
  for(const id in CAT.bots)h+='<option value="bot:'+id+'">Bot · '+esc(CAT.bots[id].name)+'</option>';
  return h;
}
function manage(u){
  const box=$('#up');
  box.innerHTML='<div class="card"><h2 style="margin-top:0">'+esc(u.username)+' <span class="muted">(Lv '+u.level+' · 🪙 '+u.coins+' · '+esc(u.role)+')</span></h2>'+
   (ME.is_admin?'<h2>🎁 Premi</h2><div class="row"><div><label>Zero Coin (+/−)</label><input id="g_c" type="number" placeholder="0"></div><div><label>XP (+/−)</label><input id="g_x" type="number" placeholder="0"></div><div><label>Imposta livello</label><input id="g_l" type="number" min="1" max="999" placeholder="—"></div><div><label>Punti Zero Pass (+/−)</label><input id="g_p" type="number" placeholder="0"></div></div>'+
   '<div class="row"><div><label>Oggetto</label><select id="g_i">'+itemOpts()+'</select></div><div><label>Messaggio per il giocatore (opzionale)</label><input id="g_m" maxlength="120"></div><div style="flex:0"><button class="btn green" id="g_go">Assegna</button></div></div>':'<div class="muted">I premi possono essere assegnati solo dagli amministratori.</div>')+
   '<h2>🔨 Sanzioni</h2><div class="row"><div><label>Motivo ban</label><input id="b_r" maxlength="200"></div><div><label>Durata ban (ore, 0 = permanente)</label><input id="b_h" type="number" min="0" value="24"></div><div style="flex:0"><button class="btn red" id="b_go">Banna</button></div><div style="flex:0"><button class="btn gray" id="b_un">Rimuovi ban</button></div></div>'+
   '<div class="row"><div><label>Silenzia in chat (minuti)</label><input id="m_m" type="number" min="1" value="30"></div><div style="flex:0"><button class="btn red" id="m_go">Silenzia</button></div><div style="flex:0"><button class="btn gray" id="m_un">Rimuovi silenzio</button></div></div></div>';
  box.scrollIntoView({behavior:'smooth'});
  const done=()=>vUsers();
  if(ME.is_admin)$('#g_go').onclick=()=>go(async()=>{
    const it=$('#g_i').value.split(':');
    const r=await call('admin_grant',{uid:u.uid,coins:+$('#g_c').value||0,xp:+$('#g_x').value||0,level:+$('#g_l').value||0,pass:+$('#g_p').value||0,item_type:it[0]||'',item_id:it[1]||'',message:$('#g_m').value});
    toast('Assegnato: '+r.summary);done();
  });
  $('#b_go').onclick=()=>{if(!confirm('Bannare '+u.username+'?'))return;go(async()=>{await call('admin_ban',{uid:u.uid,hours:+$('#b_h').value||0,reason:$('#b_r').value});done()},'Utente bannato')};
  $('#b_un').onclick=()=>go(async()=>{await call('admin_unban',{uid:u.uid});done()},'Ban rimosso');
  $('#m_go').onclick=()=>go(async()=>{await call('admin_mute',{uid:u.uid,minutes:+$('#m_m').value||30});done()},'Utente silenziato');
  $('#m_un').onclick=()=>go(async()=>{await call('admin_unmute',{uid:u.uid});done()},'Silenzio rimosso');
}

/* ---------- eventi ---------- */
const ETYPES={xp:'XP x',coins:'Zero Coin x',pass:'Punti Pass x',food:'Più cibo x (1–3)',speed:'Velocità x (1–2)',announce:'Solo annuncio'};
async function vEvents(){
  V('<div id="el" class="card">Caricamento…</div>'+(ME.is_admin?'<div class="card"><h2 style="margin-top:0">➕ Nuovo evento</h2><div class="row"><div><label>Titolo</label><input id="e_t" maxlength="80"></div><div><label>Tipo</label><select id="e_y">'+Object.entries(ETYPES).map(([k,v])=>'<option value="'+k+'">'+v+'</option>').join('')+'</select></div><div><label>Moltiplicatore</label><input id="e_m" type="number" step="0.1" min="1" value="2"></div><div><label>Durata (ore)</label><input id="e_h" type="number" min="1" value="24"></div></div><div class="row"><div><label>Descrizione (opzionale)</label><input id="e_d" maxlength="200"></div><div><label>Inizio (vuoto = adesso)</label><input id="e_s" type="datetime-local"></div><div style="flex:0"><button class="btn green" id="e_go">Crea evento</button></div></div></div><div class="card"><h2 style="margin-top:0">📢 Annuncio rapido</h2><div class="row"><div><label>Messaggio</label><input id="a_t" maxlength="80"></div><div><label>Ore</label><input id="a_h" type="number" min="1" value="6"></div><div style="flex:0"><button class="btn blue" id="a_go">Pubblica</button></div></div></div>':''));
  const load=()=>go(async()=>{
    const r=await call('admin_event_list'),n=r.now;
    $('#el').innerHTML='<div class="tw"><table><tr><th>Evento</th><th>Tipo</th><th>Periodo</th><th>Stato</th><th></th></tr>'+(r.events.map(e=>{
      const live=+e.enabled&&e.start_ts<=n&&e.end_ts>=n;
      return '<tr><td><b>'+esc(e.title)+'</b><div class="muted">'+esc(e.descr||'')+'</div></td><td>'+esc(e.type)+(e.type==='announce'?'':' x'+e.mult)+'</td><td class="muted">'+esc(fmt(e.start_ts))+'<br>'+esc(fmt(e.end_ts))+'</td><td>'+(live?'<span class="tag green">ATTIVO</span>':+e.enabled?'<span class="tag">programmato/finito</span>':'<span class="tag orange">DISATTIVATO</span>')+'</td><td>'+(ME.is_admin?'<button class="btn gray sm" data-t="'+e.id+'">On/Off</button> <button class="btn red sm" data-d="'+e.id+'">Elimina</button>':'')+'</td></tr>'}).join('')||'<tr><td colspan="5" class="muted">Nessun evento</td></tr>')+'</table></div>';
    $('#el').querySelectorAll('[data-t]').forEach(b=>b.onclick=()=>go(async()=>{await call('admin_event_toggle',{id:+b.dataset.t});load()}));
    $('#el').querySelectorAll('[data-d]').forEach(b=>b.onclick=()=>{if(confirm('Eliminare questo evento?'))go(async()=>{await call('admin_event_delete',{id:+b.dataset.d});load()})});
  });
  load();
  if(ME.is_admin){
    $('#e_go').onclick=()=>go(async()=>{const s=$('#e_s').value;await call('admin_event_save',{title:$('#e_t').value,type:$('#e_y').value,mult:+$('#e_m').value||1,hours:+$('#e_h').value||24,descr:$('#e_d').value,start:s?s.replace('T',' ')+':00':''});$('#e_t').value='';load()},'Evento creato');
    $('#a_go').onclick=()=>go(async()=>{await call('admin_announce',{title:$('#a_t').value,hours:+$('#a_h').value||6});$('#a_t').value='';load()},'Annuncio pubblicato');
  }
}

/* ---------- skin ---------- */
let SF='pending';
async function vSkins(){
  V('<div class="card"><div class="row"><div style="flex:0"><label>Mostra</label><select id="sf"><option value="pending">In attesa</option><option value="approved">Approvate</option><option value="rejected">Rifiutate</option><option value="all">Tutte</option></select></div></div></div><div id="sl" class="card">Caricamento…</div>');
  $('#sf').value=SF;
  const load=()=>go(async()=>{
    SF=$('#sf').value;const r=await call('admin_skin_list',{status:SF});
    $('#sl').innerHTML=r.skins.length?r.skins.map(s=>'<div class="skin"><img src="'+API+'?action=skin_file&id='+s.id+'" alt=""><div><b>'+esc(s.name)+'</b></div><div class="muted">di '+esc(s.username||'?')+' · '+s.w+'×'+s.h+' · '+Math.round(s.bytes/1024)+' KB</div><div><span class="tag '+(s.status==='approved'?'green':s.status==='pending'?'orange':'red')+'">'+esc(s.status)+'</span></div><div style="margin-top:6px"><button class="btn green sm" data-a="approve" data-i="'+s.id+'">✔</button> <button class="btn gray sm" data-a="reject" data-i="'+s.id+'">✖</button> <button class="btn red sm" data-a="delete" data-i="'+s.id+'">🗑</button></div></div>').join(''):'<div class="muted">Nessuna skin.</div>';
    $('#sl').querySelectorAll('[data-a]').forEach(b=>b.onclick=()=>{if(b.dataset.a==='delete'&&!confirm('Eliminare definitivamente la skin?'))return;go(async()=>{await call('admin_skin_review',{id:+b.dataset.i,decision:b.dataset.a});load()},'Fatto')});
  });
  $('#sf').onchange=load;load();
}

/* ---------- impostazioni e chat (solo admin) ---------- */
const SET=[
 ['Chat',[['chat_enabled','Chat arena attiva','bool'],['chat_slowmode','Slowmode (secondi tra un messaggio e l’altro)','number'],['chat_blocked_words','Parole bloccate (separate da virgola)','text']]],
 ['Skin personalizzate',[['skin_upload_enabled','Upload skin attivo','bool'],['skin_auto_approve','Approva automaticamente le skin','bool'],['skin_max_kb','Peso massimo (KB)','number'],['skin_max_per_user','Skin per utente','number']]],
 ['Premi e moltiplicatori globali',[['coin_mult','Moltiplicatore Zero Coin','number','0.1'],['xp_mult','Moltiplicatore XP','number','0.1'],['pass_mult','Moltiplicatore punti Pass','number','0.1']]],
 ['Shop e Zero Pass',[['shop_discount','Sconto shop (%)','number'],['pass_premium_price','Prezzo Zero Pass Premium (coin)','number'],['pass_season','Nome stagione','text'],['pass_ends','Fine stagione (testo mostrato ai giocatori)','text']]],
 ['Pagamenti Zero Coin',[['payments_mode','Modalità','select',['off','demo','external']],['payment_url','URL pagamento ({order} {pack} {uid})','text']]]
];
async function vSettings(){
  V('<div class="card">Caricamento…</div>');
  await go(async()=>{
    const r=await call('admin_settings_get'),s=r.settings;
    let h='';
    for(const [grp,items] of SET){
      h+='<div class="card"><h2 style="margin-top:0">'+grp+'</h2>';
      for(const [k,l,t,x] of items){
        let inp;
        if(t==='bool')inp='<select data-k="'+k+'"><option value="1"'+(s[k]==='1'?' selected':'')+'>Sì</option><option value="0"'+(s[k]!=='1'?' selected':'')+'>No</option></select>';
        else if(t==='select')inp='<select data-k="'+k+'">'+x.map(o=>'<option'+(s[k]===o?' selected':'')+'>'+o+'</option>').join('')+'</select>';
        else inp='<input data-k="'+k+'" type="'+(t==='number'?'number':'text')+'"'+(x?' step="'+x+'"':'')+' value="'+esc(s[k])+'">';
        h+='<div class="opt"><div>'+esc(l)+'</div>'+inp+'</div>';
      }
      h+='</div>';
    }
    const wh=location.origin+location.pathname.replace(/[^/]*$/,'')+API+'?action=webhook_credit';
    h+='<div class="card"><h2 style="margin-top:0">🔗 Webhook pagamenti (modalità external)</h2><p class="muted">Dopo un pagamento riuscito il tuo provider deve fare una POST a:</p><p><code>'+esc(wh)+'</code></p><p class="muted">con header <code>X-ZA-Secret: '+esc(r.webhook_secret)+'</code> e corpo JSON <code>{"order":ID_ORDINE}</code>. Tieni segreto questo codice.</p></div>';
    h+='<button class="btn green" id="ss">💾 Salva impostazioni</button>';
    V(h);
    $('#ss').onclick=()=>go(async()=>{const v={};document.querySelectorAll('[data-k]').forEach(i=>v[i.dataset.k]=i.value);await call('admin_settings_set',{values:v})},'Impostazioni salvate');
  });
}

/* ---------- log ---------- */
async function vLogs(){
  V('<div class="card">Caricamento…</div>');
  await go(async()=>{
    const r=await call('admin_logs');
    V('<div class="card tw"><table><tr><th>Quando</th><th>Staff</th><th>Azione</th><th>Bersaglio</th><th>Dettaglio</th></tr>'+(r.logs.map(l=>'<tr><td class="muted">'+esc(fmt(l.ts))+'</td><td>'+esc(l.actor)+'</td><td><span class="tag">'+esc(l.action)+'</span></td><td>'+esc(l.target)+'</td><td class="muted">'+esc(l.detail)+'</td></tr>').join('')||'<tr><td colspan="5" class="muted">Nessuna attività</td></tr>')+'</table></div>');
  });
}

/* ---------- avvio ---------- */
(async()=>{
  try{
    const r=await call('state');
    ME=r.player;CAT=r.cat;
    if(!ME.is_mod){V('<div class="card">⛔ Non hai i permessi per usare questo pannello.</div>');return}
    $('#who').textContent=ME.username+' · '+ME.role;
    drawTabs();render();
  }catch(e){V('<div class="card">⚠️ '+esc(e.message==='Accesso richiesto'?'Accedi dal portale per usare il pannello.':'Impossibile contattare l’economia: '+e.message)+'</div>')}
})();
})();
</script>
</body>
</html>
