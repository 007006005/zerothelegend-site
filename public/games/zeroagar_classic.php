<?php
declare(strict_types=1);
$user = null;
?>
<!doctype html>
<html lang="it">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no,viewport-fit=cover">
<script src="/assets/js/deployment.js"></script>
<title>ZeroAgar Classic</title>
<style>
*{box-sizing:border-box}html,body{margin:0;width:100%;height:100%;overflow:hidden;font-family:Arial,Helvetica,sans-serif;background:#e9ecef;color:#222}body{touch-action:none}
#game{position:fixed;inset:0;background:#dfe3e5}canvas{display:block;width:100%;height:100%}
#hud{position:fixed;inset:0;pointer-events:none}.panel{pointer-events:auto;background:rgba(255,255,255,.96);border:1px solid rgba(0,0,0,.1);box-shadow:0 2px 14px rgba(0,0,0,.18);border-radius:10px}
#topbar{position:absolute;top:max(10px,env(safe-area-inset-top));left:10px;right:10px;display:flex;justify-content:space-between;align-items:flex-start;gap:10px;z-index:10}
#profile{width:280px;padding:10px}.avatar{width:48px;height:48px;border-radius:50%;float:left;margin-right:9px;background:linear-gradient(145deg,#28c6ff,#0876d1);border:2px solid #fff;box-shadow:0 1px 5px #888}.avatar.skin{background-size:cover;background-position:center}
#playerName{font-size:17px;font-weight:700;line-height:22px}.badge{display:inline-block;font-size:10px;background:#596579;color:#fff;border-radius:4px;padding:3px 7px;text-transform:uppercase}.meta{clear:both;font-size:11px;color:#555;margin-top:6px}.xp{height:8px;border:1px solid #55bd2a;border-radius:5px;margin-top:8px;overflow:hidden}.xp i{display:block;height:100%;width:20%;background:#56d21c}
#leader{width:250px;padding:10px}.leaderTitle{font-size:13px;font-weight:700;margin-bottom:7px;text-transform:uppercase}.row{display:flex;justify-content:space-between;gap:10px;font-size:12px;margin:4px 0}.row.me{color:#0876d1;font-weight:700}.row.team{font-weight:700}
#mini{position:absolute;left:10px;bottom:max(10px,env(safe-area-inset-bottom));width:150px;height:150px;border-radius:8px;background:rgba(255,255,255,.92);border:1px solid #aaa;display:none;z-index:8}
#menu{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:min(900px,95vw);display:grid;grid-template-columns:245px 1fr 245px;gap:8px;z-index:20}.box{padding:14px}.logo{text-align:center;font-size:46px;font-weight:800;letter-spacing:-3px;margin:6px 0 14px}.logo span{color:#19b5f1}
#nick{width:100%;padding:12px;border:1px solid #bbb;border-radius:5px;font-size:16px}button{border:0;border-radius:5px;padding:10px 13px;font-weight:700;cursor:pointer}button:active{transform:translateY(1px)}
#play{width:100%;margin-top:10px;background:#58d100;color:#fff;font-size:20px}.mode{display:grid;grid-template-columns:1fr 1fr;gap:7px;margin-top:10px}.mode button{background:#f2f3f4;border:1px solid #ddd}.mode button.on{background:#ff3f58;color:#fff}.help{text-align:center;color:#777;font-size:12px;line-height:1.5;margin-top:10px}.action{width:100%;background:#13b8ef;color:#fff;margin:4px 0}.action.gray{background:#707780}
#bottom{position:absolute;left:50%;bottom:15px;transform:translateX(-50%);display:none;gap:7px;z-index:10}.bottom-btn{min-width:90px;background:rgba(255,255,255,.96)}
#back{position:absolute;left:10px;bottom:15px;background:rgba(255,255,255,.96);display:none;z-index:10}.toast{position:absolute;left:50%;top:16px;transform:translateX(-50%);background:rgba(0,0,0,.74);color:#fff;padding:8px 14px;border-radius:18px;font-size:12px;display:none;z-index:30}
#death{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:min(430px,90vw);padding:18px;display:none;z-index:25;text-align:center}.deathScore{font-size:32px;font-weight:800}.deathActions{display:flex;gap:8px;margin-top:12px}.deathActions button{flex:1}.green{background:#59d414;color:#fff}.blue{background:#16b7ef;color:#fff}.gray{background:#6c7178;color:#fff}
#touch{position:absolute;right:14px;bottom:72px;display:none;pointer-events:auto;z-index:12}.touchbtn{width:62px;height:62px;border-radius:50%;background:rgba(255,255,255,.88);margin:4px;font-size:10px}
#tips{position:absolute;left:50%;top:92px;transform:translateX(-50%);padding:5px 10px;background:rgba(255,255,255,.83);border:1px solid rgba(0,0,0,.08);border-radius:14px;font-size:10px;display:none;z-index:7}
@media(max-width:900px){#menu{grid-template-columns:1fr;max-width:430px}#menu .side{display:none}#leader{width:205px}#profile{width:235px}.logo{font-size:38px}.help{font-size:11px}}
#arenaChat{position:absolute;left:10px;right:auto;bottom:15px;width:min(340px,92vw);z-index:14;pointer-events:auto;display:none}.chat-head{padding:8px 10px;background:#222d3b;color:#fff;border-radius:10px 10px 0 0;font-size:12px;font-weight:700;display:flex;justify-content:space-between}.chat-count{font-weight:500;opacity:.75}.chat-body{height:190px;overflow:auto;background:rgba(255,255,255,.95);border-left:1px solid rgba(0,0,0,.12);border-right:1px solid rgba(0,0,0,.12);padding:7px}.chat-msg{font-size:12px;line-height:1.35;margin:4px 0}.chat-msg b{color:#0876d1}.chat-form{display:flex;background:#fff;border:1px solid rgba(0,0,0,.12);border-radius:0 0 10px 10px;overflow:hidden}.chat-form input{flex:1;min-width:0;border:0;padding:9px;font-size:12px;outline:none}.chat-form button{border-radius:0;background:#16b7ef;color:#fff;padding:9px 12px;font-size:12px}.chat-emotes{display:flex;justify-content:space-around;gap:4px;padding:4px;background:rgba(255,255,255,.95);border-left:1px solid rgba(0,0,0,.12);border-right:1px solid rgba(0,0,0,.12)}.chat-emotes button{padding:4px 7px;background:#edf4fa;font-size:17px}
#powerups{position:absolute;left:50%;top:124px;transform:translateX(-50%);display:flex;flex-wrap:wrap;justify-content:center;gap:6px;z-index:9;pointer-events:none;max-width:90vw}
.pu{background:rgba(255,255,255,.95);border:2px solid rgba(0,0,0,.12);border-radius:14px;padding:4px 10px;font-size:12px;font-weight:700;box-shadow:0 1px 6px rgba(0,0,0,.15)}
#feed{position:absolute;right:10px;top:310px;width:230px;z-index:9;pointer-events:none}
.feed-item{background:rgba(0,0,0,.68);color:#fff;font-size:12px;padding:5px 9px;border-radius:12px;margin-bottom:4px}
#pauseBox{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);display:none;padding:18px 28px;font-size:20px;font-weight:800;z-index:26;text-align:center}
.panel,#bottom,#touch,#powerups,#feed,#tips,#quick,.toast{zoom:var(--ui,1)}
#profile{position:relative;cursor:pointer}.caret{float:right;font-size:10px;font-weight:700;color:#0876d1;margin-top:4px}
.um{position:absolute;left:0;top:calc(100% + 6px);width:100%;background:#fff;border-radius:10px;box-shadow:0 6px 22px rgba(0,0,0,.28);overflow:hidden;z-index:30;cursor:default}
.um button{display:block;width:100%;text-align:left;background:transparent;color:inherit;border-radius:0;padding:11px 14px;font-size:13px}.um button:hover{background:rgba(19,184,239,.14)}
#quick{position:absolute;right:14px;bottom:14px;display:flex;gap:8px;z-index:15;pointer-events:auto}
#quick button{width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,.96);box-shadow:0 2px 10px rgba(0,0,0,.2);font-size:20px;padding:0}
#fps{position:absolute;left:10px;top:136px;background:rgba(0,0,0,.65);color:#fff;font:700 11px/1 monospace;padding:5px 8px;border-radius:8px;z-index:9;pointer-events:none}
#overlay{position:absolute;inset:0;background:rgba(0,0,0,.45);display:none;align-items:center;justify-content:center;z-index:40;pointer-events:auto;padding:12px}
#sheet{background:#fff;color:#222;border-radius:14px;width:min(560px,100%);max-height:88vh;overflow:auto;box-shadow:0 10px 40px rgba(0,0,0,.35);zoom:var(--ui,1)}
.sh-head{display:flex;justify-content:space-between;align-items:center;padding:14px 16px;border-bottom:1px solid rgba(0,0,0,.1);font-weight:800;font-size:17px}.sh-head button{padding:6px 11px}
.sh-tabs{display:flex;gap:6px;padding:10px 12px;flex-wrap:wrap;border-bottom:1px solid rgba(0,0,0,.08)}
.sh-tabs button{background:#eef1f4;color:#333;padding:7px 12px;font-size:12px}.sh-tabs button.on{background:#13b8ef;color:#fff}
.sh-body{padding:8px 16px 16px}.sh-menu button{width:100%;margin:5px 0;padding:13px;font-size:15px}
.opt{display:flex;justify-content:space-between;align-items:center;gap:12px;padding:11px 0;border-bottom:1px solid rgba(0,0,0,.06);font-size:14px}
.opt small{display:block;color:#777;font-size:11px;margin-top:2px}
.sw{position:relative;width:44px;height:24px;background:#c7ccd1;border-radius:12px;cursor:pointer;flex:none;transition:.15s}.sw:after{content:'';position:absolute;top:3px;left:3px;width:18px;height:18px;background:#fff;border-radius:50%;transition:.15s}.sw.on{background:#58d100}.sw.on:after{left:23px}
.opt select{padding:7px 9px;border:1px solid #bbb;border-radius:6px;font-size:13px;background:#fff;color:#222}
.rng{display:flex;align-items:center;gap:8px}.rng input{width:150px}.rng span{min-width:42px;text-align:right;font-size:12px}
.keys{padding:10px 0}
.sh-foot{display:flex;gap:8px;padding:12px 16px;border-top:1px solid rgba(0,0,0,.1)}.sh-foot button{flex:1}
.stat-grid{display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:12px}.stat{background:#f2f4f6;border-radius:8px;padding:10px;text-align:center}.stat b{display:block;font-size:20px}.stat span{font-size:11px;color:#666}
body.dark{background:#10141a;color:#e8ecf1}body.dark #game{background:#151a20}
body.dark .panel,body.dark .um,body.dark #sheet{background:rgba(28,33,41,.97);color:#e8ecf1;border-color:rgba(255,255,255,.1)}
body.dark .help,body.dark .meta,body.dark .opt small,body.dark .stat span{color:#9aa4b2}
body.dark .mode button,body.dark .bottom-btn,body.dark #quick button,body.dark .sh-tabs button,body.dark .stat{background:#262d38;color:#e8ecf1;border-color:#333b48}
body.dark .sh-tabs button.on{background:#13b8ef;color:#fff}
body.dark .mode button.on{background:#ff3f58;color:#fff}
body.dark #nick,body.dark .opt select{background:#1d232c;color:#e8ecf1;border-color:#3a4351}
body.dark .chat-body,body.dark .chat-emotes,body.dark .chat-form,body.dark .chat-form input{background:#1d232c;color:#e8ecf1}
body.dark .chat-msg b,body.dark .row.me{color:#5cc8ff}
body.dark .pu{background:rgba(28,33,41,.95);color:#e8ecf1}
body.dark .sh-head,body.dark .sh-tabs,body.dark .opt,body.dark .sh-foot{border-color:rgba(255,255,255,.1)}
body.dark .sw{background:#4a5361}body.dark .sw.on{background:#58d100}
@media(pointer:coarse){#touch{display:block}}
</style>
</head>
<body>
<div id="game"><canvas id="canvas"></canvas></div>
<div id="hud">
  <div id="topbar">
    <div id="profile" class="panel">
      <div id="avatar" class="avatar"></div>
      <div id="playerName">Caricamento…</div><span id="role" class="badge">USER</span> <span class="caret">▾ MENU</span>
      <div class="xp"><i id="xpbar"></i></div>
      <div id="meta" class="meta">LV1 · 0 XP · Record 0</div>
      <div id="userMenu" class="um" style="display:none">
        <button data-um="profile">👤 Profilo e statistiche</button>
        <button data-um="settings">⚙️ Impostazioni</button>
        <button data-um="fullscreen">⛶ Schermo intero</button>
        <button data-um="save">💾 Salva progressi</button>
        <button data-um="refresh">🔄 Aggiorna account</button>
        <button data-um="portal">🏠 Portale</button>
      </div>
    </div>
    <div id="leader" class="panel"><div class="leaderTitle">Classifica</div><div id="leaders"></div></div>
  </div>
  <div id="tips">Mouse / dito = movimento · SPACE = split · W = espelli massa · P = pausa · ESC = menu · M = minimappa · raccogli i bonus ⚡🛡️🧲✨</div>
  <div id="powerups"></div>
  <div id="feed"></div>
  <div id="pauseBox" class="panel">⏸ PAUSA<div class="help">premi P per riprendere</div></div>
  <canvas id="mini" width="150" height="150"></canvas>
  <div id="menu">
    <div class="box panel side">
      <h3>Account</h3>
      <p id="loginInfo" class="help" style="text-align:left">Controllo sessione…</p>
      <button class="action" id="refreshProfile">AGGIORNA</button>
      <button class="action" id="saveNow">SALVA PROGRESSI</button>
      <button class="action" id="profileBtn">PROFILO</button>
      <button class="action" id="settingsBtn">IMPOSTAZIONI</button>
      <button class="action gray" id="portalBtn">PORTALE</button>
    </div>
    <div id="center" class="box panel">
      <div class="logo">Zero<span>Agar</span></div>
      <input id="nick" maxlength="20" placeholder="Nickname">
      <button id="play">PLAY</button>
      <div class="help">Mangia palline e avversari più piccoli, evita i più grandi.</div>
      <div class="mode">
        <button class="on" data-mode="ffa">FFA</button><button data-mode="teams">TEAMS</button>
        <button data-mode="experimental">EXPERIMENTAL</button><button data-mode="party">PARTY</button>
      </div>
    </div>
    <div class="box panel side">
      <h3>Funzioni Classic</h3>
      <p class="help" style="text-align:left">Split, feed/eject, virus, zoom, minimappa, leaderboard, spectate, respawn e modalità di gioco.</p>
      <button class="action" id="continueBtn">CONTINUA</button>
      <button class="action gray" id="helpBtn">COMANDI</button>
    </div>
  </div>
  <button id="back" class="panel">← PORTALE Zero World</button>
  <div id="bottom">
    <button class="bottom-btn" id="splitBtn">SPLIT [SPACE]</button>
    <button class="bottom-btn" id="ejectBtn">MASSA [W]</button>
    <button class="bottom-btn" id="miniBtn">MAPPA [M]</button>
    <button class="bottom-btn" id="pauseBtn">PAUSA [P]</button>
  </div>
  <div id="touch"><button class="touchbtn" id="tsplit">SPLIT</button><button class="touchbtn" id="teject">MASSA</button></div>
  <div id="toast" class="toast"></div>
  <div id="fps" style="display:none">0 FPS</div>
  <div id="quick"><button id="gearBtn" title="Menu e impostazioni">⚙️</button><button id="fsBtn" title="Schermo intero">⛶</button></div>
  <div id="overlay"><div id="sheet"></div></div>
  <div id="arenaChat" class="panel">
    <div class="chat-head"><span>💬 CHAT ARENA</span><span class="chat-count" id="arenaOnline">0 online</span></div>
    <div class="chat-body" id="arenaChatBody"></div>
    <form class="chat-form" id="arenaChatForm"><input id="arenaChatInput" maxlength="200" autocomplete="off" placeholder="Scrivi nella chat globale…"><button type="submit">INVIA</button></form>
    <div class="chat-emotes" aria-label="Emoticon chat"><button type="button" data-emote="😀">😀</button><button type="button" data-emote="😂">😂</button><button type="button" data-emote="😎">😎</button><button type="button" data-emote="😮">😮</button><button type="button" data-emote="❤️">❤️</button><button type="button" data-emote="👋">👋</button></div>
  </div>
  <div id="death" class="panel">
    <h2>SEI STATO DIVORATO</h2><div class="deathScore" id="deathScore">0</div><div id="deathStats" class="help"></div>
    <div class="deathActions"><button class="green" id="respawnBtn">RESPAWN</button><button class="blue" id="spectateBtn">SPETTATORE</button><button class="gray" id="deathPortalBtn">PORTALE</button></div>
  </div>
</div>
<script>
(() => {
'use strict';
const API='../api/api.php';
const ARENA_API='../api/arena_live.php';
const PORTAL_URL='https://www.zerothelegend.com/portal/index.php';
const canvas=document.getElementById('canvas'),ctx=canvas.getContext('2d');
const mini=document.getElementById('mini'),mctx=mini.getContext('2d');
/* Impostazioni salvate nel browser */
const SKEY='zeroagar_settings_v1';
const DEFAULTS={names:true,mass:false,autoZoom:true,botCount:'normal',botSkill:'normal',chat:true,theme:'light',quality:'high',grid:true,particles:true,minimap:true,fps:false,sound:true,volume:60,uiScale:100,touch:'auto',keepAwake:false};
const S=Object.assign({},DEFAULTS);
try{Object.assign(S,JSON.parse(localStorage.getItem(SKEY)||'{}'))}catch(_){}
S.uiScale=isFinite(+S.uiScale)?Math.max(80,Math.min(130,+S.uiScale)):100;
S.volume=isFinite(+S.volume)?Math.max(0,Math.min(100,+S.volume)):60;
function qualityDPR(){const d=devicePixelRatio||1;return S.quality==='low'?1:S.quality==='medium'?Math.min(d,1.5):Math.min(d,2)}
let W=innerWidth,H=innerHeight,D=qualityDPR();
function resize(){W=innerWidth;H=innerHeight;canvas.width=Math.floor(W*D);canvas.height=Math.floor(H*D);ctx.setTransform(D,0,0,D,0,0)}
addEventListener('resize',resize);resize();
const palette=['#ff3f58','#20b7ee','#f2c94c','#7ed321','#9b59ff','#ff7a18','#00c7a7','#e85dff'];
const TEAM_COLORS=['#2d7ff9','#e14cff','#ff9a2f'];
const names=['Vortex','Nucleus','Blobby','Helix','Nebula','Rogue','Photon','Mitosis','Quark','Zephyr','Titan','Krill','Hydra','Plasma','Onyx','Cygnus','Pyxis','Aphid','Spore','Flux'];
const state={
  mode:'ffa',playing:false,spectating:false,online:false,loggedIn:false,profile:null,nick:'ZeroPlayer',world:6000,
  foods:[],ejected:[],viruses:[],bots:[],cells:[],camera:{x:3000,y:3000,zoom:1},mouse:{x:W/2,y:H/2},
  score:0,best:0,plays:0,kills:0,foodEaten:0,time:0,lastFrame:0,lastSave:0,leaderTick:0,minimap:true,partyCode:'',
  remotePlayers:{},arenaJoined:false,arenaSyncBusy:false,arenaUpdateBusy:false,chatSince:0,arenaTimer:null,arenaUpdateTimer:null,
  serverLeaders:[],saving:false,rafId:0,
  pickups:[],fx:{speed:0,shield:0,magnet:0,double:0},combo:0,comboT:0,bestCombo:0,particles:[],feed:[],runTime:0
};
function esc(s){return String(s??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]))}
function rand(a,b){return a+Math.random()*(b-a)}
function clamp(v,a,b){return Math.max(a,Math.min(b,v))}
function d2(a,b){return Math.hypot(a.x-b.x,a.y-b.y)}
function now(){return performance.now()}
function toast(s){const e=document.getElementById('toast');e.textContent=s;e.style.display='block';clearTimeout(toast.t);toast.t=setTimeout(()=>e.style.display='none',1800)}
/* Raggio visibile: cresce a ogni boccone di cibo (niente più minimo fisso che bloccava la crescita) */
function massToRadius(m){return 12+Math.sqrt(Math.max(10,m))*2.2}

/* ----- Extra: power-up, combo, particelle, kill feed, pausa ----- */
const PICKUPS={
  speed:{icon:'⚡',name:'Velocità',color:'#ffd43b',dur:8},
  shield:{icon:'🛡️',name:'Scudo',color:'#4dabf7',dur:6},
  magnet:{icon:'🧲',name:'Magnete',color:'#ff6b6b',dur:8},
  double:{icon:'✨',name:'Crescita x2',color:'#b197fc',dur:10}
};
const PICKUP_KEYS=Object.keys(PICKUPS);
function spawnPickup(){
  const k=PICKUP_KEYS[(Math.random()*PICKUP_KEYS.length)|0];
  return{type:k,x:rand(200,state.world-200),y:rand(200,state.world-200),r:16,t:rand(0,6.28)};
}
function burst(x,y,color,n=8,speed=160){
  for(let i=0;i<n&&state.particles.length<300;i++){
    const a=rand(0,Math.PI*2),s=rand(speed*.4,speed);
    state.particles.push({x,y,vx:Math.cos(a)*s,vy:Math.sin(a)*s,life:rand(.35,.7),color,r:rand(2,4)});
  }
}
function renderFeed(){
  const b=document.getElementById('feed');if(!b)return;
  b.innerHTML=state.feed.map(f=>'<div class="feed-item">'+esc(f.text)+'</div>').join('');
}
function addFeed(text){
  state.feed.push({text,life:5});
  if(state.feed.length>5)state.feed.shift();
  renderFeed();
}
function renderPowerups(){
  const box=document.getElementById('powerups');if(!box)return;
  let h='';
  for(const k of PICKUP_KEYS){
    const t=state.fx[k];
    if(t>0)h+='<div class="pu" style="border-color:'+PICKUPS[k].color+'">'+PICKUPS[k].icon+' '+PICKUPS[k].name+' '+t.toFixed(1)+'s</div>';
  }
  if(state.combo>=3)h+='<div class="pu">🔥 Combo x'+state.combo+'</div>';
  box.innerHTML=h;
}
function registerCombo(c){
  state.combo++;state.comboT=2.5;
  state.bestCombo=Math.max(state.bestCombo,state.combo);
  if(state.combo%10===0){
    const bonus=Math.min(50,state.combo);
    c.m+=bonus;c.r=massToRadius(c.m);
    toast('🔥 COMBO x'+state.combo+' · +'+bonus+' massa');
    addFeed('Combo x'+state.combo+' · +'+bonus+' massa');
    burst(c.x,c.y,'#ffb703',16,260);
  }
}
function updateExtras(dt){
  for(const k of PICKUP_KEYS)state.fx[k]=Math.max(0,state.fx[k]-dt);
  if(state.fx.magnet>0){
    for(const f of state.foods){
      for(const c of state.cells){
        const dx=c.x-f.x,dy=c.y-f.y,d=Math.hypot(dx,dy);
        if(d<c.r+320&&d>1){const s=Math.min(d,420*dt);f.x+=dx/d*s;f.y+=dy/d*s;break}
      }
    }
  }
  for(let i=0;i<state.pickups.length;i++){
    const k=state.pickups[i];
    for(const c of state.cells){
      if(d2(c,k)<c.r+k.r){
        const def=PICKUPS[k.type];
        state.fx[k.type]=def.dur;
        toast(def.icon+' '+def.name+'!');
        addFeed(def.icon+' '+def.name+' attivo ('+def.dur+'s)');
        burst(k.x,k.y,def.color,14,220);
        state.pickups[i]=spawnPickup();
        break;
      }
    }
  }
  if(state.comboT>0){state.comboT-=dt;if(state.comboT<=0)state.combo=0}
  for(let i=state.particles.length-1;i>=0;i--){
    const p=state.particles[i];
    p.life-=dt;p.x+=p.vx*dt;p.y+=p.vy*dt;
    p.vx*=Math.pow(.05,dt);p.vy*=Math.pow(.05,dt);
    if(p.life<=0)state.particles.splice(i,1);
  }
  if(state.feed.length){
    for(const f of state.feed)f.life-=dt;
    const n=state.feed.filter(f=>f.life>0);
    if(n.length!==state.feed.length){state.feed=n;renderFeed()}
  }
  state.runTime+=dt;
}
function drawPickups(){
  const z=state.camera.zoom;
  for(const k of state.pickups){
    const p=screenPoint(k.x,k.y),def=PICKUPS[k.type];
    const pulse=1+Math.sin(state.time*4+k.t)*.12;
    ctx.beginPath();ctx.arc(p.x,p.y,k.r*1.5*z*pulse,0,Math.PI*2);ctx.fillStyle=def.color+'55';ctx.fill();
    ctx.beginPath();ctx.arc(p.x,p.y,k.r*z,0,Math.PI*2);ctx.fillStyle=def.color;ctx.fill();
    ctx.strokeStyle='#fff';ctx.lineWidth=2;ctx.stroke();
    ctx.font=Math.max(10,18*z)+'px Arial';ctx.textAlign='center';ctx.fillText(def.icon,p.x,p.y+6*z);
  }
}
function drawParticles(){
  const z=state.camera.zoom;
  for(const pt of state.particles){
    const p=screenPoint(pt.x,pt.y);
    ctx.globalAlpha=Math.max(0,Math.min(1,pt.life/.5));
    ctx.beginPath();ctx.arc(p.x,p.y,pt.r*z,0,Math.PI*2);ctx.fillStyle=pt.color;ctx.fill();
  }
  ctx.globalAlpha=1;
}
function drawCellFx(c,p,z){
  if(state.fx.shield>0){
    const blink=state.fx.shield<2&&Math.floor(state.time*8)%2;
    ctx.beginPath();ctx.arc(p.x,p.y,c.r*z+7,0,Math.PI*2);
    ctx.strokeStyle='rgba(77,171,247,'+(blink?.3:.85)+')';ctx.lineWidth=5;ctx.stroke();
  }
  if(state.fx.magnet>0){
    ctx.beginPath();ctx.arc(p.x,p.y,(c.r+320)*z,0,Math.PI*2);
    ctx.strokeStyle='rgba(255,107,107,.25)';ctx.lineWidth=2;ctx.setLineDash([8,8]);ctx.stroke();ctx.setLineDash([]);
  }
}
function togglePause(){
  if(!state.playing)return;
  state.paused=!state.paused;
  document.getElementById('pauseBox').style.display=state.paused?'block':'none';
}

/* Richiesta con timeout: evita che una chiamata bloccata tenga occupato il client */
async function fetchJSON(url,options={},timeoutMs=8000){
  const ctrl=new AbortController();
  const timer=setTimeout(()=>ctrl.abort(),timeoutMs);
  try{
    const r=await fetch(url,Object.assign({credentials:'same-origin',signal:ctrl.signal},options));
    const data=await r.json().catch(()=>null);
    if(!r.ok||!data||data.success===false&&data.error){throw Error((data&&data.error)||('HTTP '+r.status))}
    return data;
  }finally{clearTimeout(timer)}
}
function api(action,payload){
  const o={};
  if(payload!==undefined){o.method='POST';o.headers={'Content-Type':'application/json'};o.body=JSON.stringify(payload)}
  return fetchJSON(API+'?action='+encodeURIComponent(action),o);
}
function arenaCall(action,payload={}){
  return fetchJSON(ARENA_API,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(Object.assign({action,arena:'ZeroArcade:global',game:'zeroagar_classic'},payload))});
}

function playerSkinColor(){return state.profile?.profile?.skin_color||state.profile?.equipped?.cell_skin||'#1db7ef'}
function applyProfileUI(){
  const p=state.profile;if(!p)return;
  document.getElementById('playerName').textContent=state.nick;
  document.getElementById('role').textContent=(p.role||'user').toUpperCase();
  document.getElementById('meta').textContent=`LV${p.level||1} · ${p.xp||0} XP · Record ${Math.floor(state.best)}`;
  document.getElementById('xpbar').style.width=Math.min(100,(Number(p.xp||0)%1000)/10)+'%';
  const av=document.getElementById('avatar');av.classList.toggle('skin',!!p.equipped?.cell_skin);
  if(p.equipped?.cell_skin)av.style.background=p.equipped.cell_skin;
}
async function loadAccount(){
  try{
    const r=await api('me');
    if(!r?.user&&!r?.profile)throw Error('login_required');
    const p=r.profile||r.user.profile||{};
    state.online=true;state.loggedIn=true;
    state.profile=Object.assign({},r.user||{},r.profile||{});
    state.nick=(state.profile.name||state.profile.username||'ZeroPlayer').slice(0,20);
    try{
      const g=await api('game_progress',{game:'zeroagar_classic'});
      if(g?.progress){state.best=+g.progress.best||0;state.plays=+g.progress.plays||0;state.time=+g.progress.total||0}
    }catch(_){}
    document.getElementById('loginInfo').innerHTML='<b>Account collegato</b><br>'+esc(state.nick)+'<br>Progressi salvati nel database.';
    document.getElementById('nick').value=state.nick;
    applyProfileUI();
    await refreshLeaderboard();
    if(state.playing)arenaJoin();
  }catch(e){
    state.online=false;state.loggedIn=false;state.profile=null;
    document.getElementById('loginInfo').innerHTML='Sessione non disponibile. Accedi dal portale.';
  }
  updateLeaders();
}
async function refreshLeaderboard(){
  try{const r=await api('game_leaderboard',{game:'zeroagar_classic',limit:10});if(r?.leaders)state.serverLeaders=r.leaders}catch(_){}
}

/* Salvataggio: un solo invio alla volta, massimo ogni 15 secondi, errori visibili */
async function saveProgress(force=false){
  if(!state.online||!state.loggedIn||state.saving)return;
  if(!force&&Date.now()-state.lastSave<15000)return;
  state.saving=true;
  state.lastSave=Date.now();
  try{
    await api('game_save',{game:'zeroagar_classic',score:Math.floor(state.score),data:{best:Math.floor(state.best),plays:state.plays,total:Math.floor(state.time),kills:state.kills,particles:state.foodEaten,mode:state.mode}});
  }catch(e){
    console.warn('[ZeroAgar] salvataggio fallito',e);
    toast('Salvataggio non riuscito: '+e.message);
  }finally{state.saving=false}
}

function updateLeaders(){
  const real=Array.isArray(state.serverLeaders)?state.serverLeaders:[];
  let vals=real.filter(v=>String(v.username||'').toLowerCase()!==String(state.nick||'').toLowerCase());
  vals.push({username:state.nick||'Tu',score:Math.floor(state.score),me:true});
  vals.sort((a,b)=>(+b.score||0)-(+a.score||0));
  document.getElementById('leaders').innerHTML=vals.slice(0,10).map((v,i)=>`<div class="row ${v.me?'me':''}"><span>${i+1}. ${esc(v.username||'')}</span><b>${Math.floor(+v.score||0)}</b></div>`).join('');
}

function food(){return{x:rand(50,state.world-50),y:rand(50,state.world-50),r:rand(3,6),m:8,c:palette[(Math.random()*palette.length)|0]}}
function virus(){return{x:rand(500,state.world-500),y:rand(500,state.world-500),r:48,m:100,spin:rand(0,Math.PI*2)}}
function createBot(i){const team=state.mode==='ffa'?null:i%3;const m=rand(25,150);return{x:rand(300,state.world-300),y:rand(300,state.world-300),r:massToRadius(m),m,vx:rand(-35,35),vy:rand(-35,35),name:names[i%names.length],team,angle:rand(0,Math.PI*2),think:0,color:team==null?palette[i%palette.length]:TEAM_COLORS[team],alive:true}}
function makeCell(x,y,m,color,name,team=null,player=false){return{x,y,m,r:massToRadius(m),vx:0,vy:0,merge:0,color,name,team,player}}
function resetWorld(){
  state.foods=[];state.ejected=[];state.viruses=[];state.bots=[];
  state.pickups=[];for(let i=0;i<10;i++)state.pickups.push(spawnPickup());
  for(let i=0;i<700;i++)state.foods.push(food());
  const vc=state.mode==='experimental'?14:10;for(let i=0;i<vc;i++)state.viruses.push(virus());
  const bc=state.mode==='teams'?24:18;for(let i=0;i<bc;i++)state.bots.push(createBot(i));
  if(state.mode==='party'){state.partyCode='ZA-'+Math.random().toString(36).slice(2,7).toUpperCase();toast('Party code: '+state.partyCode)}
}

/* ----- Arena multiplayer ----- */
function arenaCellsPayload(){return state.cells.map(c=>({x:c.x,y:c.y,m:c.m,r:c.r,color:c.color,team:c.team}))}
function updateRemotePlayers(players){
  const map={};
  for(const p of (players||[])){if(!p.self&&p.alive)map[p.id]=p}
  state.remotePlayers=map;
  const el=document.getElementById('arenaOnline');if(el)el.textContent=((players||[]).length||0)+' online';
}
function renderArenaChat(messages){
  const box=document.getElementById('arenaChatBody');if(!box||!Array.isArray(messages))return;
  for(const m of messages){
    const id='am-'+m.id;if(document.getElementById(id))continue;
    const row=document.createElement('div');row.className='chat-msg';row.id=id;
    row.innerHTML='<b>'+esc(m.username)+'</b>: '+esc(m.text);
    box.appendChild(row);
  }
  while(box.children.length>100)box.removeChild(box.firstChild);
  box.scrollTop=box.scrollHeight;
}
function showArenaChat(show){const c=document.getElementById('arenaChat');if(c)c.style.display=show?'block':'none'}
async function arenaJoin(){
  if(!state.online||!state.loggedIn||state.arenaJoined||!state.playing)return;
  try{
    const r=await arenaCall('join',{color:playerSkinColor(),team:state.cells[0]?.team??null,score:Math.floor(state.score),cells:arenaCellsPayload()});
    if(r?.success){state.arenaJoined=true;updateRemotePlayers(r.players||[]);showArenaChat(true);await arenaSync(true)}
  }catch(e){console.warn('[ZeroAgar] arena join fallito',e)}
}
async function arenaUpdate(){
  if(!state.arenaJoined||!state.playing||state.arenaUpdateBusy)return;
  state.arenaUpdateBusy=true;
  try{
    const r=await arenaCall('update',{color:playerSkinColor(),team:state.cells[0]?.team??null,score:Math.floor(state.score),cells:arenaCellsPayload()});
    if(r?.dead){state.arenaJoined=false;die('Sei stato divorato da un giocatore online');return}
    if(Number.isFinite(Number(r?.server_score)))state.score=Math.max(state.score,Number(r.server_score));
    updateRemotePlayers(r.players||[]);
  }catch(e){
    if(String(e.message).includes('join')){state.arenaJoined=false;arenaJoin()}
  }finally{state.arenaUpdateBusy=false}
}
async function arenaSync(force=false){
  if(!state.arenaJoined||state.arenaSyncBusy)return;
  state.arenaSyncBusy=true;
  try{
    const r=await arenaCall('sync',{since:force?0:state.chatSince});
    if(r?.success){updateRemotePlayers(r.players||[]);renderArenaChat(r.messages||[]);if(r.last_chat)state.chatSince=r.last_chat}
  }catch(e){}finally{state.arenaSyncBusy=false}
}
async function arenaLeave(){
  if(!state.arenaJoined)return;
  state.arenaJoined=false;
  try{await arenaCall('leave',{})}catch(e){}
  showArenaChat(false);
}
async function sendArenaChat(text){
  text=String(text||'').trim();if(!text||!state.arenaJoined)return;
  try{const r=await arenaCall('chat',{text});if(r?.success)await arenaSync(true)}
  catch(e){toast(e.message||'Chat non disponibile')}
}
function stopArenaTimers(){clearInterval(state.arenaTimer);clearInterval(state.arenaUpdateTimer);state.arenaTimer=null;state.arenaUpdateTimer=null}
function startArenaTimers(){
  stopArenaTimers();
  state.arenaTimer=setInterval(()=>arenaSync(),350);
  state.arenaUpdateTimer=setInterval(()=>arenaUpdate(),120);
}
function drawRemotePlayers(){
  const z=state.camera.zoom;
  for(const p of Object.values(state.remotePlayers)){
    for(const c of (p.cells||[])){
      const q=screenPoint(c.x,c.y);
      const r=Math.max(5,Number(c.r||massToRadius(c.m||10)))*z;
      ctx.beginPath();ctx.arc(q.x,q.y,r,0,Math.PI*2);ctx.fillStyle=c.color||p.color||'#ef476f';ctx.fill();
      ctx.strokeStyle='#fff';ctx.lineWidth=3;ctx.stroke();
      if(r>22){ctx.fillStyle='#fff';ctx.font='bold 13px Arial';ctx.textAlign='center';ctx.fillText(p.username,q.x,q.y+4)}
    }
  }
}

/* ----- Partita ----- */
function startGame(){
  state.nick=(state.profile?.username||state.profile?.name||document.getElementById('nick').value||'ZeroPlayer').trim().slice(0,20)||'ZeroPlayer';
  state.playing=true;state.spectating=false;state.gameOver=false;state.paused=false;
  state.score=25;state.kills=0;state.foodEaten=0;
  state.fx={speed:0,shield:0,magnet:0,double:0};state.combo=0;state.comboT=0;state.bestCombo=0;state.runTime=0;state.particles=[];state.feed=[];
  renderFeed();renderPowerups();
  document.getElementById('pauseBox').style.display='none';
  const spawn={x:rand(1300,4700),y:rand(1300,4700)};
  state.camera={x:spawn.x,y:spawn.y,zoom:1};
  state.cells=[makeCell(spawn.x,spawn.y,25,playerSkinColor(),state.nick,state.mode==='teams'?Math.floor(Math.random()*3):null,true)];
  resetWorld();
  document.getElementById('menu').style.display='none';
  document.getElementById('death').style.display='none';
  document.getElementById('bottom').style.display='flex';
  document.getElementById('back').style.display='block';
  document.getElementById('tips').style.display='block';
  state.lastFrame=now();state.lastSave=Date.now();
  cancelAnimationFrame(state.rafId);
  state.rafId=requestAnimationFrame(loop);
  arenaJoin();startArenaTimers();
  toast('Partita iniziata · '+state.mode.toUpperCase());
}
function respawn(){startGame()}
function die(reason='Divorato'){
  if(!state.playing&&state.gameOver)return;
  state.playing=false;state.gameOver=true;state.plays++;
  state.best=Math.max(state.best,state.score);
  document.getElementById('deathScore').textContent=Math.floor(state.score);
  const mins=Math.floor(state.runTime/60),secs=Math.floor(state.runTime%60);
  document.getElementById('deathStats').textContent=`Record ${Math.floor(state.best)} · Kill ${state.kills} · Cibo ${state.foodEaten} · Combo max ${state.bestCombo} · Tempo ${mins}:${String(secs).padStart(2,'0')} · ${reason} · premi R per rigiocare`;
  document.getElementById('pauseBox').style.display='none';
  document.getElementById('powerups').innerHTML='';
  document.getElementById('death').style.display='block';
  document.getElementById('bottom').style.display='none';
  document.getElementById('tips').style.display='none';
  stopArenaTimers();arenaLeave();
  saveProgress(true);refreshLeaderboard().then(updateLeaders);updateLeaders();
}
function canEat(a,b){return a.m>b.m*1.10&&a.r>b.r*1.08}
function split(){
  if(!state.playing||state.paused||state.cells.length>=16)return;
  const additions=[];
  const ang=Math.atan2(state.mouse.y-H/2,state.mouse.x-W/2);
  for(const c of state.cells.slice()){
    if(c.m<36||state.cells.length+additions.length>=16)continue;
    const half=c.m/2;c.m=half;c.r=massToRadius(half);c.merge=10;
    additions.push(Object.assign({},c,{x:c.x+Math.cos(ang)*(c.r+18),y:c.y+Math.sin(ang)*(c.r+18),m:half,r:massToRadius(half),vx:Math.cos(ang)*650,vy:Math.sin(ang)*650,merge:10,player:true}));
  }
  if(additions.length){state.cells.push(...additions);toast('SPLIT')}
}
function eject(){
  if(!state.playing||state.paused)return;
  const ang=Math.atan2(state.mouse.y-H/2,state.mouse.x-W/2);
  for(const c of state.cells){
    if(c.m<36)continue;
    c.m-=12;c.r=massToRadius(c.m);
    state.ejected.push({x:c.x+Math.cos(ang)*(c.r+9),y:c.y+Math.sin(ang)*(c.r+9),vx:Math.cos(ang)*700+c.vx,vy:Math.sin(ang)*700+c.vy,m:12,r:massToRadius(12),life:8,color:c.color});
  }
}
function handleVirusHit(c,v){
  if(c.m<100)return;
  const n=Math.min(6,Math.floor(c.m/90));
  c.m*=0.9;c.r=massToRadius(c.m);
  const ang=Math.atan2(c.y-v.y,c.x-v.x);
  for(let i=0;i<n&&state.cells.length<16;i++){
    const a=ang+(i-(n-1)/2)*0.22;
    const nc=makeCell(c.x+Math.cos(a)*45,c.y+Math.sin(a)*45,Math.max(20,c.m/n*0.85),c.color,c.name,c.team,true);
    nc.vx=Math.cos(a)*420;nc.vy=Math.sin(a)*420;state.cells.push(nc);
  }
  v.x=rand(500,state.world-500);v.y=rand(500,state.world-500);
  toast('VIRUS!');
}
function updateBots(dt){
  for(const b of state.bots){
    if(!b.alive)continue;
    b.think-=dt;
    if(b.think<=0){
      b.think=rand(.25,1.4);
      let target=null,best=1e9;
      for(const f of state.foods){const d=(f.x-b.x)**2+(f.y-b.y)**2;if(d<best){best=d;target=f}}
      for(const c of state.cells){
        const d=Math.hypot(c.x-b.x,c.y-b.y);
        if(canEat(b,c)&&d<800){target=c;break}
        if(canEat(c,b)&&d<700){b.angle+=Math.PI;break}
      }
      if(target)b.angle=Math.atan2(target.y-b.y,target.x-b.x)+(Math.random()-.5)*.5;
    }
    b.vx+=Math.cos(b.angle)*25*dt;b.vy+=Math.sin(b.angle)*25*dt;
    const s=Math.hypot(b.vx,b.vy)||1;const max=70/(1+Math.sqrt(b.m)/80);
    if(s>max){b.vx=b.vx/s*max;b.vy=b.vy/s*max}
    b.x=clamp(b.x+b.vx*dt,b.r,state.world-b.r);b.y=clamp(b.y+b.vy*dt,b.r,state.world-b.r);
  }
}
function update(dt){
  if(!state.playing||state.paused)return;
  const first=state.cells[0];if(!first)return;
  const dx=state.mouse.x-W/2,dy=state.mouse.y-H/2,len=Math.hypot(dx,dy)||1;
  for(const c of state.cells){
    const localSpeed=clamp(520/Math.sqrt(c.m)*35,45,300)*(state.fx.speed>0?1.6:1);
    c.vx+=(dx/len*localSpeed-c.vx)*Math.min(1,dt*6);
    c.vy+=(dy/len*localSpeed-c.vy)*Math.min(1,dt*6);
    c.x=clamp(c.x+c.vx*dt,c.r,state.world-c.r);c.y=clamp(c.y+c.vy*dt,c.r,state.world-c.r);
    c.merge=Math.max(0,c.merge-dt);
  }
  for(let i=state.ejected.length-1;i>=0;i--){
    const e=state.ejected[i];e.life-=dt;
    e.vx*=Math.pow(.03,dt);e.vy*=Math.pow(.03,dt);
    e.x=clamp(e.x+e.vx*dt,e.r,state.world-e.r);e.y=clamp(e.y+e.vy*dt,e.r,state.world-e.r);
    if(e.life<=0)state.ejected.splice(i,1);
  }
  updateBots(dt);
  for(const c of state.cells){
    for(let i=state.foods.length-1;i>=0;i--){
      const f=state.foods[i];
      if(d2(c,f)<c.r){
        c.m+=f.m*(state.fx.double>0?2:1);c.r=massToRadius(c.m);
        burst(f.x,f.y,f.c,3,110);
        state.foods[i]=food();state.foodEaten++;
        registerCombo(c);
      }
    }
  }
  for(const e of state.ejected){for(const c of state.cells){if(d2(e,c)<c.r){c.m+=e.m;c.r=massToRadius(c.m);e.life=0}}}
  state.ejected=state.ejected.filter(e=>e.life>0);
  for(const b of state.bots){
    for(const c of state.cells.slice()){
      if(state.mode==='teams'&&b.team===c.team)continue;
      const d=d2(b,c);
      if(canEat(c,b)&&d<c.r*.92){
        c.m+=b.m;c.r=massToRadius(c.m);state.kills++;
        burst(b.x,b.y,b.color,18,240);addFeed('Hai divorato '+b.name+' (+'+Math.floor(b.m)+')');
        b.x=rand(400,5600);b.y=rand(400,5600);b.m=rand(25,150);b.r=massToRadius(b.m);
      }else if(canEat(b,c)&&d<b.r*.92&&state.fx.shield<=0){die('Un rivale più grande ti ha divorato');return}
    }
  }
  for(const v of state.viruses){for(const c of state.cells){if(d2(c,v)<c.r+v.r*.65){if(state.mode!=='ffa'||c.m>100)handleVirusHit(c,v)}}}
  updateExtras(dt);
  mergeCells();
  if(!state.cells.length){die('Partita terminata');return}
  state.score=state.cells.reduce((s,c)=>s+c.m,0);
  state.best=Math.max(state.best,state.score);
  state.time+=dt;
  state.camera.x=first.x;state.camera.y=first.y;
  state.camera.zoom=clamp(1/Math.pow(Math.max(25,state.score)/25,.15),.28,1.15);
  if(++state.leaderTick%20===0){updateLeaders()}
  if(state.leaderTick%6===0)renderPowerups();
  if(state.leaderTick%300===0)refreshLeaderboard().then(updateLeaders);
  saveProgress();
  applyProfileUI();
}
function mergeCells(){
  for(let i=state.cells.length-1;i>=0;i--){
    for(let j=i-1;j>=0;j--){
      const a=state.cells[i],b=state.cells[j];
      if(!a||!b)continue;
      if(a.merge>0||b.merge>0)continue;
      if(state.mode!=='ffa'&&a.team!==b.team)continue;
      if(d2(a,b)<Math.min(a.r,b.r)*.55){
        const big=a.m>=b.m?a:b,small=big===a?b:a;
        if(state.mode==='ffa'&&!canEat(big,small))continue;
        big.m+=small.m;big.r=massToRadius(big.m);
        state.cells.splice(state.cells.indexOf(small),1);
      }
    }
  }
}
function screenPoint(x,y){const z=state.camera.zoom;return{x:W/2+(x-state.camera.x)*z,y:H/2+(y-state.camera.y)*z}}
function draw(){
  ctx.clearRect(0,0,W,H);ctx.fillStyle='#dfe3e5';ctx.fillRect(0,0,W,H);
  if(!state.playing&&!state.spectating)return;
  const z=state.camera.zoom;
  const grid=50*z,ox=W/2-state.camera.x*z,oy=H/2-state.camera.y*z;
  ctx.strokeStyle='rgba(90,100,110,.16)';ctx.lineWidth=1;
  for(let x=((ox%grid)+grid)%grid;x<W;x+=grid){ctx.beginPath();ctx.moveTo(x,0);ctx.lineTo(x,H);ctx.stroke()}
  for(let y=((oy%grid)+grid)%grid;y<H;y+=grid){ctx.beginPath();ctx.moveTo(0,y);ctx.lineTo(W,y);ctx.stroke()}
  drawRemotePlayers();
  drawPickups();
  for(const f of state.foods){const p=screenPoint(f.x,f.y);ctx.beginPath();ctx.arc(p.x,p.y,f.r*z,0,Math.PI*2);ctx.fillStyle=f.c;ctx.fill()}
  drawParticles();
  for(const e of state.ejected){const p=screenPoint(e.x,e.y);ctx.beginPath();ctx.arc(p.x,p.y,e.r*z,0,Math.PI*2);ctx.fillStyle=e.color;ctx.fill()}
  for(const v of state.viruses){
    const p=screenPoint(v.x,v.y);ctx.save();ctx.translate(p.x,p.y);ctx.rotate(v.spin);ctx.beginPath();
    for(let i=0;i<40;i++){const a=i*Math.PI*2/40,r=(i%2?v.r*.84:v.r)*z;ctx.lineTo(Math.cos(a)*r,Math.sin(a)*r)}
    ctx.closePath();ctx.fillStyle='#54b948';ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=2;ctx.stroke();ctx.restore();
  }
  for(const b of state.bots){
    const p=screenPoint(b.x,b.y);ctx.beginPath();ctx.arc(p.x,p.y,b.r*z,0,Math.PI*2);ctx.fillStyle=b.color;ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=2;ctx.stroke();
    if(b.r*z>20){ctx.fillStyle='#fff';ctx.font='bold 12px Arial';ctx.textAlign='center';ctx.fillText(b.name,p.x,p.y+4)}
  }
  for(const c of state.cells){
    const p=screenPoint(c.x,c.y);ctx.beginPath();ctx.arc(p.x,p.y,c.r*z,0,Math.PI*2);ctx.fillStyle=c.color;ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=3;ctx.stroke();
    if(c.r*z>22){ctx.fillStyle='#fff';ctx.font='bold 13px Arial';ctx.textAlign='center';ctx.fillText(state.nick,p.x,p.y+4)}
    drawCellFx(c,p,z);
  }
  if(state.minimap){
    mini.style.display='block';mctx.clearRect(0,0,150,150);
    mctx.fillStyle='#f2f2f2';mctx.fillRect(0,0,150,150);mctx.strokeStyle='#aaa';mctx.strokeRect(.5,.5,149,149);
    for(const b of state.bots){mctx.fillStyle=b.color;mctx.fillRect(b.x/state.world*150-1,b.y/state.world*150-1,2,2)}
    for(const c of state.cells){mctx.fillStyle=c.color;mctx.beginPath();mctx.arc(c.x/state.world*150,c.y/state.world*150,3,0,Math.PI*2);mctx.fill()}
    mctx.fillStyle='#111';mctx.font='9px Arial';mctx.fillText(state.mode.toUpperCase(),6,145);
  }
}
let lastT=0;
function loop(t){
  if(!state.playing)return;
  const dt=Math.min(.05,(t-(lastT||t))/1000||.016);lastT=t;
  update(dt);draw();
  state.rafId=requestAnimationFrame(loop);
}
function spectate(){
  state.spectating=true;state.playing=false;
  document.getElementById('death').style.display='none';
  const bots=state.bots.length?state.bots:[{x:3000,y:3000}];
  let idx=Math.floor(Math.random()*bots.length);
  const tick=()=>{
    if(!state.spectating)return;
    const b=bots[idx%bots.length];
    state.camera.x=b.x;state.camera.y=b.y;state.camera.zoom=.75;
    draw();requestAnimationFrame(tick);
  };
  requestAnimationFrame(tick);
}
function help(){toast('SPACE split · W massa · P pausa · R rigioca · M mappa · rotella zoom · ESC portale')}
function goPortal(){saveProgress(true);location.href=PORTAL_URL}

addEventListener('mousemove',e=>{state.mouse.x=e.clientX;state.mouse.y=e.clientY});
addEventListener('wheel',e=>{
  if(!state.playing)return;e.preventDefault();
  state.camera.zoom=clamp(state.camera.zoom*(e.deltaY<0?1.08:.92),.28,1.3);
},{passive:false});
addEventListener('keydown',e=>{
  if(e.target&&(e.target.tagName==='INPUT'||e.target.tagName==='TEXTAREA'))return;
  if(e.code==='Space'){e.preventDefault();split()}
  else if(e.key.toLowerCase()==='p'){togglePause()}
  else if(e.key.toLowerCase()==='r'&&state.gameOver&&!state.playing){respawn()}
  else if(e.key.toLowerCase()==='w'){eject()}
  else if(e.key.toLowerCase()==='m'){state.minimap=!state.minimap}
  else if(e.key==='Escape'){goPortal()}
});

document.getElementById('play').onclick=startGame;
document.getElementById('continueBtn').onclick=startGame;
document.getElementById('respawnBtn').onclick=respawn;
document.getElementById('spectateBtn').onclick=spectate;
document.getElementById('back').onclick=goPortal;
document.getElementById('portalBtn').onclick=goPortal;
document.getElementById('deathPortalBtn').onclick=goPortal;
document.getElementById('helpBtn').onclick=help;
document.getElementById('refreshProfile').onclick=loadAccount;
document.getElementById('saveNow').onclick=()=>saveProgress(true);
document.getElementById('splitBtn').onclick=split;
document.getElementById('ejectBtn').onclick=eject;
document.getElementById('miniBtn').onclick=()=>state.minimap=!state.minimap;
document.getElementById('pauseBtn').onclick=togglePause;
document.getElementById('tsplit').onclick=split;
document.getElementById('teject').onclick=eject;
document.querySelectorAll('.mode button').forEach(b=>b.onclick=()=>{
  document.querySelectorAll('.mode button').forEach(x=>x.classList.remove('on'));
  b.classList.add('on');state.mode=b.dataset.mode;
});
document.getElementById('arenaChatForm').addEventListener('submit',e=>{
  e.preventDefault();
  const inp=document.getElementById('arenaChatInput');
  const t=inp.value;inp.value='';
  sendArenaChat(t);
});
document.querySelectorAll('.chat-emotes [data-emote]').forEach(b=>b.addEventListener('click',()=>{
  const inp=document.getElementById('arenaChatInput');
  const start=inp.selectionStart??inp.value.length,end=inp.selectionEnd??start;
  inp.setRangeText(b.dataset.emote||'',start,end,'end');inp.focus();
}));
addEventListener('pagehide',()=>{
  try{navigator.sendBeacon(ARENA_API,new Blob([JSON.stringify({action:'leave'})],{type:'application/json'}))}catch(e){}
});

loadAccount();
})();
</script>
<script src="/assets/js/global-arena-chat.js" defer></script>
</body>
</html>
