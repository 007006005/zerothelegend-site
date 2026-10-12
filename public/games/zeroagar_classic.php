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
#mini{position:absolute;right:10px;bottom:68px;width:150px;height:150px;border-radius:8px;background:rgba(255,255,255,.92);border:1px solid #aaa;display:none;z-index:8}#mini.left{left:10px;right:auto;bottom:max(10px,env(safe-area-inset-bottom))}
#menu{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:min(900px,95vw);display:grid;grid-template-columns:245px 1fr 245px;gap:8px;z-index:20}.box{padding:14px}.logo{text-align:center;font-size:46px;font-weight:800;letter-spacing:-3px;margin:6px 0 14px}.logo span{color:#19b5f1}
#nick{width:100%;padding:12px;border:1px solid #bbb;border-radius:5px;font-size:16px}button{border:0;border-radius:5px;padding:10px 13px;font-weight:700;cursor:pointer}button:active{transform:translateY(1px)}
#play{width:100%;margin-top:10px;background:#58d100;color:#fff;font-size:20px}.mode{display:grid;grid-template-columns:1fr 1fr;gap:7px;margin-top:10px}.mode button{background:#f2f3f4;border:1px solid #ddd}.mode button.on{background:#ff3f58;color:#fff}.help{text-align:center;color:#777;font-size:12px;line-height:1.5;margin-top:10px}.action{width:100%;background:#13b8ef;color:#fff;margin:4px 0}.action.gray{background:#707780}
#bottom{position:absolute;left:50%;bottom:15px;transform:translateX(-50%);display:none;gap:7px;z-index:10}.bottom-btn{min-width:90px;background:rgba(255,255,255,.96)}
#back{position:absolute;left:10px;bottom:15px;background:rgba(255,255,255,.96);display:none;z-index:10}.toast{position:absolute;left:50%;top:16px;transform:translateX(-50%);background:rgba(0,0,0,.74);color:#fff;padding:8px 14px;border-radius:18px;font-size:12px;display:none;z-index:30}
#death{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:min(430px,90vw);padding:18px;display:none;z-index:25;text-align:center}.deathScore{font-size:32px;font-weight:800}.deathActions{display:flex;gap:8px;margin-top:12px}.deathActions button{flex:1}.green{background:#59d414;color:#fff}.blue{background:#16b7ef;color:#fff}.gray{background:#6c7178;color:#fff}
#touch{position:absolute;right:14px;bottom:var(--touchb,72px);display:none;pointer-events:auto;z-index:12}.touchbtn{width:62px;height:62px;border-radius:50%;background:rgba(255,255,255,.88);margin:4px;font-size:10px}
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
#overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);display:none;align-items:center;justify-content:center;z-index:30000;pointer-events:auto;padding:12px}
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
#ecoBanner{position:absolute;left:50%;top:160px;transform:translateX(-50%);padding:6px 14px;font-size:12px;font-weight:700;max-width:90vw;text-align:center;pointer-events:none;z-index:9;zoom:var(--ui,1)}
.role-tag{display:inline-block;font-size:9px;font-weight:800;color:#fff;border-radius:4px;padding:1px 5px;margin-right:3px;text-transform:uppercase;vertical-align:1px}
.chat-gif{display:block;max-width:150px;max-height:110px;border-radius:8px;margin:4px 0}
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
        <button data-um="shop">🛒 Shop Zero Coin</button>
        <button data-um="pass">🎟️ Zero Pass</button>
        <button data-um="skins">🎨 Collezione e skin</button>
        <button data-um="admin" id="umAdmin" style="display:none">🛠 Amministrazione</button>
        <button data-um="portal">🏠 Portale</button>
      </div>
    </div>
    <div id="leader" class="panel"><div class="leaderTitle">Classifica</div><div id="leaders"></div></div>
  </div>
  <div id="tips">Mouse / dito = movimento · tasti personalizzabili in Impostazioni › Controlli · raccogli i bonus ⚡🛡️🧲✨</div>
  <div id="ecoBanner" class="panel" style="display:none"></div>
  <div id="powerups"></div>
  <div id="feed"></div>
  <div id="pauseBox" class="panel">⏸ PAUSA<div class="help" id="pauseHelp">premi P per riprendere</div></div>
  <canvas id="mini" width="150" height="150"></canvas>
  <div id="menu">
    <div class="box panel side">
      <h3>Account</h3>
      <p id="loginInfo" class="help" style="text-align:left">Controllo sessione…</p>
      <button class="action" id="refreshProfile">AGGIORNA</button>
      <button class="action" id="saveNow">SALVA PROGRESSI</button>
      <button class="action" id="profileBtn">PROFILO</button>
      <div id="coinInfo" class="help" style="margin:6px 0"></div>
      <button class="action" id="shopBtn">🛒 SHOP</button>
      <button class="action" id="passBtn">🎟️ ZERO PASS</button>
      <button class="action" id="skinsBtn">🎨 COLLEZIONE</button>
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
        <button data-mode="survivor">SURVIVOR</button><button data-mode="zombie">ZOMBIE</button>
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
  <div id="touch"><button class="touchbtn" id="tsplit">SPLIT</button><button class="touchbtn" id="teject">MASSA</button><button class="touchbtn" id="tdash">DASH</button></div>
  <div id="toast" class="toast"></div>
  <div id="fps" style="display:none">0 FPS</div>
  <div id="quick"><button id="gearBtn" title="Menu e impostazioni">⚙️</button><button id="fsBtn" title="Schermo intero">⛶</button></div>
  <div id="arenaChat" class="panel">
    <div class="chat-head"><span>💬 CHAT ARENA</span><span class="chat-count" id="arenaOnline">0 online</span></div>
    <div class="chat-body" id="arenaChatBody"></div>
    <form class="chat-form" id="arenaChatForm"><input id="arenaChatInput" maxlength="200" autocomplete="off" placeholder="Scrivi nella chat globale…"><button type="submit">INVIA</button></form>
    <div class="chat-emotes" aria-label="Emoticon chat"><button type="button" data-emote="😀">😀</button><button type="button" data-emote="😂">😂</button><button type="button" data-emote="😎">😎</button><button type="button" data-emote="😮">😮</button><button type="button" data-emote="❤️">❤️</button><button type="button" data-emote="👋">👋</button><button type="button" data-emote="🔥">🔥</button><button type="button" data-gif="1" title="Inserisci GIF da URL">GIF</button></div>
  </div>
  <div id="death" class="panel">
    <h2>SEI STATO DIVORATO</h2><div class="deathScore" id="deathScore">0</div><div id="deathStats" class="help"></div>
    <div class="deathActions"><button class="green" id="respawnBtn">RESPAWN</button><button class="blue" id="spectateBtn">SPETTATORE</button><button class="gray" id="deathPortalBtn">PORTALE</button></div>
  </div>
</div>
<div id="overlay"><div id="sheet"></div></div>
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
const DEFAULTS={names:true,mass:false,autoZoom:true,botCount:'normal',botSkill:'normal',chat:true,theme:'light',quality:'high',grid:true,particles:true,minimap:true,fps:false,sound:true,volume:60,uiScale:100,touch:'auto',keepAwake:false,feedRate:'normal',deadZone:'normal',autoRespawn:false,vibration:false,minimapSize:'medium',minimapPos:'right',borderWidth:3,mergeTimer:false,worldBorder:true,cursorLine:false,foodSize:'normal',bgColor:'default',showScore:true,showLeaderboard:true,showFeed:true,mouseLeft:'none',mouseRight:'none'};
const DEFAULT_KEYS={moveUp:'ArrowUp',moveDown:'ArrowDown',moveLeft:'ArrowLeft',moveRight:'ArrowRight',stop:'KeyS',split:'Space',doubleSplit:'KeyQ',tripleSplit:'KeyT',maxSplit:'KeyX',eject:'KeyW',holdFeed:'KeyF',dash:'ShiftLeft',blink:'KeyB',barrier:'KeyV',shock:'KeyG',shadow:'KeyZ',pause:'KeyP',respawn:'KeyR',menu:'Escape',screenshot:'F9',focusChat:'Enter',minimap:'KeyM',zoomIn:'Equal',zoomOut:'Minus',zoomReset:'Digit0',toggleNames:'KeyN',toggleMass:'KeyK',toggleGrid:'KeyH',toggleTheme:'KeyJ'};
const S=Object.assign({},DEFAULTS);
try{Object.assign(S,JSON.parse(localStorage.getItem(SKEY)||'{}'))}catch(_){}
S.keys=Object.assign({},DEFAULT_KEYS,(S.keys&&typeof S.keys==='object')?S.keys:{});
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
  serverLeaders:[],saving:false,rafId:0,lastUpd:0,
  pickups:[],fx:{speed:0,shield:0,magnet:0,double:0},combo:0,comboT:0,bestCombo:0,particles:[],feed:[],runTime:0
};
state.zoomMul=1;state.feedAcc=0;state.dash={t:0,cd:0};state.dashAcc=0;state.flash=0;
state.cd={blink:0,barrier:0,shock:0,shadow:0};state.inv=0;state.shadow=0;state.revives=0;state.won=false;state.myTeam=null;
state.mm={t:0,ev:30,wave:1,surge:0,zx:3000,zy:3000,zr:4200,tm:[0,0,0]};
state.eco=null;state.allies=[];state.remoteSkins={};state.rewarded=false;state.skinTimer=null;
state.rIv=120;state.arenaGen=0;
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
  if(!S.particles)return;
  for(let i=0;i<n&&state.particles.length<(S.quality==='low'?80:300);i++){
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
  const dsh=state.dash;
  if(dsh.t>0)h+='<div class="pu" style="border-color:#7cffcb">⚡ Dash '+dsh.t.toFixed(1)+'s</div>';
  else if(dsh.cd>0)h+='<div class="pu">⚡ Dash '+Math.ceil(dsh.cd)+'s</div>';
  else if(state.playing)h+='<div class="pu" style="border-color:#7cffcb">⚡ Dash pronto · '+keyLabel(S.keys.dash)+'</div>';
  if(state.playing)for(const k in ABIL){
    const ab=ABIL[k],cd=state.cd[k];
    if(k==='barrier'&&state.inv>0)h+='<div class="pu" style="border-color:#ffd166">'+ab.icon+' '+state.inv.toFixed(1)+'s</div>';
    else if(k==='shadow'&&state.shadow>0)h+='<div class="pu" style="border-color:#b388ff">'+ab.icon+' '+state.shadow.toFixed(1)+'s</div>';
    else if(cd>0)h+='<div class="pu">'+ab.icon+' '+Math.ceil(cd)+'s</div>';
    else h+='<div class="pu" style="border-color:#7cffcb">'+ab.icon+' '+keyLabel(S.keys[k])+'</div>';
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
    toast('🔥 COMBO x'+state.combo+' · +'+bonus+' massa');beep('combo');
    addFeed('Combo x'+state.combo+' · +'+bonus+' massa');
    burst(c.x,c.y,'#ffb703',16,260);
  }
}
function updateExtras(dt){
  const dsh=state.dash,prevCd=dsh.cd;
  dsh.t=Math.max(0,dsh.t-dt);dsh.cd=Math.max(0,dsh.cd-dt);state.flash=Math.max(0,state.flash-dt);
  for(const k in state.cd)state.cd[k]=Math.max(0,state.cd[k]-dt);
  state.inv=Math.max(0,state.inv-dt);state.shadow=Math.max(0,state.shadow-dt);
  if(prevCd>0&&dsh.cd===0&&state.playing){toast('⚡ Dash pronto!');beep('pickup')}
  if(dsh.t>0&&S.particles){
    state.dashAcc+=dt;
    if(state.dashAcc>=.03){
      state.dashAcc=0;
      for(const c of state.cells.slice(0,4)){
        if(state.particles.length>=(S.quality==='low'?80:300))break;
        state.particles.push({x:c.x,y:c.y,vx:-c.vx*.15,vy:-c.vy*.15,life:.45,color:Math.random()<.5?'#7cffcb':'#0aa2ff',r:Math.max(3,c.r*.28)});
      }
    }
  }
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
        state.fx[k.type]=def.dur*durMul();
        toast(def.icon+' '+def.name+'!');
        addFeed(def.icon+' '+def.name+' attivo ('+Math.round(def.dur*durMul())+'s)');
        burst(k.x,k.y,def.color,14,220);beep('pickup');
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
function drawVirusShape(x,y,r){
  ctx.save();ctx.translate(x,y);ctx.beginPath();
  for(let i=0;i<24;i++){const a=i*Math.PI*2/24,rr=(i%2?r*.7:r);ctx.lineTo(Math.cos(a)*rr,Math.sin(a)*rr)}
  ctx.closePath();ctx.fillStyle='#54b948';ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=2;ctx.stroke();ctx.restore();
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
/* ===== Abilità del player (Blink, Barriera, Onda d'urto, Ombra) + potenziamenti passivi ===== */
const ABIL={
  blink:{cd:20,icon:'🌀',name:'Blink'},
  barrier:{cd:40,dur:3,icon:'🔰',name:'Barriera'},
  shock:{cd:35,icon:'💥',name:'Onda d’urto'},
  shadow:{cd:45,dur:5,icon:'👤',name:'Ombra'}
};
function cdMul(){return Math.max(.4,1-.06*upLvl('cdr'))}
function modeSpeed(){const m=state.mm||{};return(state.mode==='experimental'?1.1:1)*(m.surge>0?1.3:1)}
function pushAway(x,y,R,force,stun){
  for(const b of state.bots){
    if(b.dead)continue;
    const dx=b.x-x,dy=b.y-y,d=Math.hypot(dx,dy)||1;
    if(d<R+b.r){
      b.x=clamp(b.x+dx/d*force,b.r,state.world-b.r);b.y=clamp(b.y+dy/d*force,b.r,state.world-b.r);
      if(b.zombie)b.stun=Math.max(b.stun||0,stun);
    }
  }
}
function useAbility(k){
  if(!state.playing||state.paused||state.gameOver||!state.cells.length)return;
  const a=ABIL[k];if(!a)return;
  if(state.cd[k]>0){toast(a.icon+' '+a.name+' in ricarica: '+Math.ceil(state.cd[k])+'s');return}
  state.cd[k]=a.cd*cdMul();
  const lead=state.cells.reduce((m,c)=>c.m>m.m?c:m,state.cells[0]);
  if(k==='blink'){
    const wm=worldMouse();
    for(const c of state.cells){
      const dx=wm.x-c.x,dy=wm.y-c.y,d=Math.hypot(dx,dy)||1,st=Math.min(480,d);
      burst(c.x,c.y,'#b388ff',14,260);
      c.x=clamp(c.x+dx/d*st,c.r,state.world-c.r);c.y=clamp(c.y+dy/d*st,c.r,state.world-c.r);
      burst(c.x,c.y,'#7cffcb',18,300);
    }
    state.flash=.15;
  }else if(k==='barrier'){
    state.inv=Math.max(state.inv,a.dur);burst(lead.x,lead.y,'#ffd166',24,320);
  }else if(k==='shock'){
    pushAway(lead.x,lead.y,520+lead.r,420,1.6);
    burst(lead.x,lead.y,'#4dabf7',30,520);state.flash=.12;
  }else if(k==='shadow'){
    state.shadow=a.dur;burst(lead.x,lead.y,'#b388ff',20,240);
  }
  beep('dash');addFeed(a.icon+' '+a.name+' attivata');toast(a.icon+' '+a.name+'!');renderPowerups();
}
/* Seconda vita (potenziamento): rinasci con metà massa */
function reviveCheck(reason){
  if(!state.playing||state.revives<=0)return false;
  state.revives--;
  const o=state.cells[0]||state.camera;
  const m=Math.max(startMass(),state.score*.5);
  state.cells=[makeCell(clamp(o.x,300,state.world-300),clamp(o.y,300,state.world-300),m,playerSkinColor(),state.nick,state.myTeam,true)];
  state.inv=3.5;state.flash=.4;
  pushAway(state.cells[0].x,state.cells[0].y,600,420,2);
  burst(state.cells[0].x,state.cells[0].y,'#ff5cd6',30,420);beep('pickup');
  toast('💖 Seconda vita! ('+state.revives+' rimaste)');addFeed('💖 Sei rinato con metà massa');
  return true;
}
/* nelle modalità Survivor/Zombie i bot divorati escono dal gioco, altrove rinascono */
function reviveBot(b){
  if(state.mode==='survivor'||state.mode==='zombie'){b.dead=true;return}
  b.x=rand(400,5600);b.y=rand(400,5600);b.m=rand(25,150);b.r=massToRadius(b.m);
}

/* ===== Modalità di gioco ===== */
function initMode(){
  const mode=state.mode;
  state.mm={t:0,ev:mode==='party'?15:rand(25,40),wave:1,surge:0,zx:rand(2400,3600),zy:rand(2400,3600),zr:4200,tm:[0,0,0]};
  state.myTeam=state.cells[0]?state.cells[0].team:null;
  const tips={
    teams:'Squadra '+['Blu','Viola','Arancio'][state.myTeam]+': i compagni non ti attaccano',
    experimental:'Virus mobili, pioggia di cibo e surge di velocità',
    party:'Party: i bot non ti attaccano, bonus casuali ogni 25s, dura 5 minuti',
    survivor:'Survivor: resta dentro la zona, elimina tutti i rivali',
    zombie:'Zombie: resisti 4 minuti, ondate sempre più numerose'
  };
  if(tips[mode])addFeed(tips[mode]);
}
function createZombie(i,boss){
  const c=state.cells[0],wave=state.mm.wave;
  const m=boss?rand(380,520):rand(14,34)+wave*5;
  let x,y,tries=0;
  do{x=rand(200,state.world-200);y=rand(200,state.world-200);tries++}while(c&&Math.hypot(x-c.x,y-c.y)<900&&tries<20);
  return{x,y,r:massToRadius(m),m,vx:0,vy:0,name:boss?'Zombie Boss':'Zombie',team:'z',angle:rand(0,Math.PI*2),think:0,color:boss?'#2f7a2f':'#6abf4b',alive:true,zombie:true,boss:!!boss,stun:0};
}
function spawnWave(){
  const m=state.mm,n=Math.max(0,Math.min(4+2*m.wave,70-state.bots.length));
  for(let i=0;i<n;i++)state.bots.push(createZombie(i,false));
  const boss=m.wave%3===0&&state.bots.length<70;
  if(boss)state.bots.push(createZombie(0,true));
  toast('🧟 Ondata '+m.wave+(boss?' · BOSS!':''));addFeed('🧟 Ondata '+m.wave+': +'+n+' zombie'+(boss?' e un Boss':''));beep('death');
}
function modeUpdate(dt){
  const m=state.mm,mode=state.mode,WW=state.world;
  m.t+=dt;m.surge=Math.max(0,m.surge-dt);
  if(state.bots.some(b=>b.dead))state.bots=state.bots.filter(b=>!b.dead);
  const lead=state.cells[0];if(!lead)return false;
  if(mode==='experimental'){
    for(const v of state.viruses){
      if(v.dir===undefined)v.dir=rand(0,Math.PI*2);
      v.x+=Math.cos(v.dir)*28*dt;v.y+=Math.sin(v.dir)*28*dt;
      if(v.x<80||v.x>WW-80||v.y<80||v.y>WW-80){v.dir+=Math.PI*.8;v.x=clamp(v.x,80,WW-80);v.y=clamp(v.y,80,WW-80)}
    }
    m.ev-=dt;
    if(m.ev<=0){
      m.ev=rand(35,50);
      if(Math.random()<.5){
        for(let i=0;i<110;i++){const f=food();f.x=clamp(lead.x+rand(-900,900),50,WW-50);f.y=clamp(lead.y+rand(-900,900),50,WW-50);f.m=14;f.r=rand(5,8);f.tmp=true;state.foods.push(f)}
        toast('🌧️ Pioggia di cibo!');addFeed('🌧️ Pioggia di cibo vicino a te');
      }else{m.surge=10;toast('⚡ Surge! Tutti più veloci');addFeed('⚡ Surge: tutti più veloci per 10s')}
    }
  }else if(mode==='teams'){
    const tm=[0,0,0];
    for(const b of state.bots)if(typeof b.team==='number')tm[b.team]+=b.m;
    if(state.myTeam!=null)tm[state.myTeam]+=state.cells.reduce((a,c)=>a+c.m,0);
    m.tm=tm;
  }else if(mode==='party'){
    m.ev-=dt;
    if(m.ev<=0){
      m.ev=25;
      const e=[['🎈 Festa di velocità','speed'],['✨ Massa doppia','double'],['🧲 Magnete party','magnet']][Math.floor(Math.random()*3)];
      state.fx[e[1]]=10;toast(e[0]+'!');addFeed(e[0]+' (10s)');
      for(const c of state.cells)burst(c.x,c.y,'#ff5cd6',26,320);
      beep('pickup');
    }
    if(S.particles&&Math.random()<dt*8&&state.particles.length<(S.quality==='low'?80:300)){
      const z=state.camera.zoom;
      state.particles.push({x:state.camera.x+rand(-W/2,W/2)/z,y:state.camera.y+rand(-H/2,H/2)/z,vx:rand(-20,20),vy:rand(30,90),life:rand(.9,1.6),color:palette[(Math.random()*palette.length)|0],r:rand(3,6)});
    }
    if(m.t>=300){die('🎉 Festa finita! Hai resistito 5 minuti',true,true);return true}
  }else if(mode==='survivor'){
    m.zr=Math.max(450,4200-m.t*(4200-450)/240);
    const dmg=m.t>240?.14:.06;
    for(const c of state.cells){if(Math.hypot(c.x-m.zx,c.y-m.zy)>m.zr+c.r*.3){c.m*=1-dmg*dt;c.r=massToRadius(c.m)}}
    if(state.cells.reduce((a,c)=>a+c.m,0)<14){die('☠️ La zona ti ha eliminato');return true}
    for(const b of state.bots){
      if(Math.hypot(b.x-m.zx,b.y-m.zy)>m.zr+b.r*.3){
        b.m*=1-dmg*dt;b.r=massToRadius(b.m);
        if(b.m<14){b.dead=true;addFeed('☠️ '+b.name+' eliminato dalla zona')}
      }
    }
    if(!state.bots.some(b=>!b.dead)){die('🏆 Sei l’ultimo sopravvissuto!',true,true);return true}
  }else if(mode==='zombie'){
    while(m.t>=m.wave*30&&m.wave<8){m.wave++;spawnWave()}
    for(const z of state.bots){
      if(z.dead||(z.stun||0)>0)continue;
      for(const c of state.cells){
        if(z.dead)break;
        const d=Math.hypot(z.x-c.x,z.y-c.y);
        if(d<z.r+c.r*.55&&!canEat(c,z)){
          const ang=Math.atan2(z.y-c.y,z.x-c.x);
          z.x=clamp(z.x+Math.cos(ang)*(z.r+c.r)*.8,z.r,WW-z.r);z.y=clamp(z.y+Math.sin(ang)*(z.r+c.r)*.8,z.r,WW-z.r);z.stun=.9;
          if(state.inv>0||state.fx.shield>0){burst(z.x,z.y,'#7cffcb',8,200);continue}
          c.m-=c.m*(z.boss?.3:.18);c.r=massToRadius(Math.max(10,c.m));state.inv=1.2;
          toast('🧟 Infetto! -'+(z.boss?30:18)+'% massa');vib([40,30,40]);burst(c.x,c.y,'#6abf4b',16,260);beep('death');
        }
      }
    }
    if(state.cells.reduce((a,c)=>a+c.m,0)<22){die('🧟 Sei stato infettato');return true}
    if(m.t>=240){die('🏆 Hai resistito a tutte le ondate!',true,true);return true}
  }
  return false;
}
function drawZone(){
  if(state.mode!=='survivor'||!state.mm)return;
  const z=state.camera.zoom,m=state.mm,p=screenPoint(m.zx,m.zy),r=m.zr*z;
  ctx.save();ctx.beginPath();ctx.rect(-5,-5,W+10,H+10);ctx.moveTo(p.x+r,p.y);ctx.arc(p.x,p.y,r,0,Math.PI*2);
  ctx.fillStyle='rgba(255,40,60,.16)';ctx.fill('evenodd');
  ctx.beginPath();ctx.arc(p.x,p.y,r,0,Math.PI*2);ctx.strokeStyle='rgba(255,63,88,.9)';ctx.lineWidth=Math.max(3,5*z);ctx.stroke();ctx.restore();
}
function drawRadar(){
  const l=upLvl('radar');if(!l||!state.playing||!state.cells.length)return;
  const lead=state.cells.reduce((a,c)=>c.m>a.m?c:a,state.cells[0]);
  const R=500+350*l,rad=Math.min(W,H)*.4;let n=0;
  for(const b of state.bots){
    if(b.dead)continue;
    const dx=b.x-lead.x,dy=b.y-lead.y,d=Math.hypot(dx,dy);
    if(d<lead.r+120||d>R)continue;
    const threat=b.zombie||canEat(b,lead),prey=canEat(lead,b);
    if(!threat&&!prey)continue;
    const a=Math.atan2(dy,dx);
    ctx.save();ctx.translate(W/2+Math.cos(a)*rad,H/2+Math.sin(a)*rad);ctx.rotate(a);
    ctx.beginPath();ctx.moveTo(10,0);ctx.lineTo(-7,-7);ctx.lineTo(-7,7);ctx.closePath();
    ctx.globalAlpha=Math.max(.35,1-d/R);ctx.fillStyle=threat?'#ff3f58':'#58d100';ctx.fill();ctx.restore();
    if(++n>=10)break;
  }
}
function drawModeHud(){
  if(!state.playing||!state.mm)return;
  const m=state.mm,mode=state.mode;let t='';
  if(mode==='survivor')t='SURVIVOR · Sopravvissuti '+state.bots.filter(b=>!b.dead).length+' · Zona '+Math.round(m.zr)+' px';
  else if(mode==='zombie')t='ZOMBIE · Ondata '+m.wave+' · Resisti '+Math.max(0,Math.ceil(240-m.t))+'s · Zombie '+state.bots.filter(b=>!b.dead).length;
  else if(mode==='party')t='PARTY '+state.partyCode+' · Fine tra '+Math.max(0,Math.ceil(300-m.t))+'s';
  else if(mode==='experimental')t='EXPERIMENTAL'+(m.surge>0?' · ⚡ Surge '+Math.ceil(m.surge)+'s':'');
  ctx.font='bold 14px Arial';ctx.textAlign='center';ctx.lineWidth=4;
  const stroke=S.theme==='dark'?'rgba(0,0,0,.6)':'rgba(255,255,255,.85)',fill=S.theme==='dark'?'#e8ecf1':'#222';
  if(t){ctx.strokeStyle=stroke;ctx.strokeText(t,W/2,96);ctx.fillStyle=fill;ctx.fillText(t,W/2,96)}
  if(mode==='teams'){
    const nm=['Blu','Viola','Arancio'];
    for(let i=0;i<3;i++){
      const tx=nm[i]+' '+Math.floor(m.tm[i]||0)+(i===state.myTeam?' ★':''),x=W/2+(i-1)*140;
      ctx.strokeStyle=stroke;ctx.strokeText(tx,x,96);ctx.fillStyle=TEAM_COLORS[i];ctx.fillText(tx,x,96);
    }
  }
}
function startDash(){
  if(!state.playing||state.paused||state.gameOver||!state.cells.length)return;
  const d=state.dash;
  if(d.t>0)return;
  if(d.cd>0){toast('⚡ Dash in ricarica: '+Math.ceil(d.cd)+'s');return}
  d.t=7;d.cd=30*cdMul();state.flash=.3;
  for(const c of state.cells.slice(0,4))burst(c.x,c.y,'#7cffcb',24,420);
  addFeed('⚡ Dash attivo (7s)');toast('⚡ DASH!');beep('dash');renderPowerups();
}
function drawDashFx(){
  if(state.flash>0){ctx.fillStyle='rgba(255,255,255,'+Math.min(.35,state.flash)+')';ctx.fillRect(0,0,W,H)}
  if(state.dash.t<=0||!S.particles)return;
  const g=ctx.createRadialGradient(W/2,H/2,Math.min(W,H)*.35,W/2,H/2,Math.max(W,H)*.75);
  g.addColorStop(0,'rgba(124,255,203,0)');g.addColorStop(1,'rgba(10,162,255,'+(.22+.08*Math.sin(state.time*10))+')');
  ctx.fillStyle=g;ctx.fillRect(0,0,W,H);
  if(S.quality==='low')return;
  ctx.save();ctx.strokeStyle='rgba(255,255,255,.28)';ctx.lineWidth=2;
  for(let i=0;i<16;i++){
    const a=i/16*Math.PI*2,r0=Math.min(W,H)*(.3+.5*((i*.37+state.time*2.2)%1)),len=40+(i%4)*14;
    ctx.beginPath();ctx.moveTo(W/2+Math.cos(a)*r0,H/2+Math.sin(a)*r0);ctx.lineTo(W/2+Math.cos(a)*(r0+len),H/2+Math.sin(a)*(r0+len));ctx.stroke();
  }
  ctx.restore();
}
function drawCellFx(c,p,z){
  if(state.inv>0){
    ctx.beginPath();ctx.arc(p.x,p.y,c.r*z+13,0,Math.PI*2);
    ctx.strokeStyle='rgba(255,209,102,'+(state.inv<1?.4:.9)+')';ctx.lineWidth=6;ctx.stroke();
  }
  if(state.mode==='teams'&&c.team!=null){
    ctx.beginPath();ctx.arc(p.x,p.y,c.r*z+4,0,Math.PI*2);ctx.strokeStyle=TEAM_COLORS[c.team];ctx.lineWidth=4;ctx.stroke();
  }
  if(state.dash.t>0){
    const pulse=.6+.4*Math.sin(state.time*18);
    ctx.save();ctx.shadowColor='#7cffcb';ctx.shadowBlur=S.quality==='low'?0:24;
    ctx.beginPath();ctx.arc(p.x,p.y,c.r*z+9,0,Math.PI*2);
    ctx.strokeStyle='rgba(124,255,203,'+(.55+.35*pulse)+')';ctx.lineWidth=5;ctx.stroke();ctx.restore();
  }
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
  if(!state.playing||sheetMode)return;
  state.paused=!state.paused;
  document.getElementById('pauseBox').style.display=state.paused?'block':'none';
}

/* ----- Impostazioni, menu utente, schermo intero, audio ----- */
function saveSettings(){try{localStorage.setItem(SKEY,JSON.stringify(S))}catch(_){}}
function botSkill(){return({easy:.75,normal:1,hard:1.3}[S.botSkill]||1)}
function toggleMinimap(){
  state.minimap=!state.minimap;S.minimap=state.minimap;saveSettings();
  if(!state.minimap)mini.style.display='none';
  applyExtra();
}
const arrows={};
let actx=null,lastBeep=0;
function beep(kind){
  if(!S.sound||S.volume<=0)return;
  const t=performance.now();
  if(kind==='eat'){if(t-lastBeep<70)return;lastBeep=t}
  try{
    actx=actx||new (window.AudioContext||window.webkitAudioContext)();
    if(actx.state==='suspended')actx.resume();
    const presets={eat:[520,620,.05],combo:[700,1000,.18],pickup:[600,950,.2],dash:[300,1100,.3],kill:[240,480,.16],death:[260,70,.5]};
    const p=presets[kind];if(!p)return;
    const o=actx.createOscillator(),g=actx.createGain(),n=actx.currentTime,vol=S.volume/100*.12;
    o.type=kind==='death'?'sawtooth':'sine';
    o.frequency.setValueAtTime(p[0],n);o.frequency.linearRampToValueAtTime(p[1],n+p[2]);
    g.gain.setValueAtTime(vol,n);g.gain.exponentialRampToValueAtTime(.0001,n+p[2]);
    o.connect(g);g.connect(actx.destination);o.start(n);o.stop(n+p[2]+.02);
  }catch(_){}
}
function isFullscreen(){return !!(document.fullscreenElement||document.webkitFullscreenElement)}
function toggleFullscreen(){
  const el0=document.documentElement;
  try{
    if(isFullscreen()){(document.exitFullscreen||document.webkitExitFullscreen).call(document)}
    else if(el0.requestFullscreen||el0.webkitRequestFullscreen){
      const p=(el0.requestFullscreen||el0.webkitRequestFullscreen).call(el0);
      if(p&&p.catch)p.catch(()=>toast('Schermo intero non disponibile'));
    }else toast('Schermo intero non supportato da questo browser');
  }catch(_){toast('Schermo intero non disponibile')}
}
function syncFullscreenUI(){
  const b=document.getElementById('fsBtn');if(b)b.textContent=isFullscreen()?'🗗':'⛶';
  if(sheetMode==='settings'||sheetMode==='pause')renderSheet();
}
let wakeLock=null;
async function applyWakeLock(){
  try{
    if(S.keepAwake&&'wakeLock' in navigator&&document.visibilityState==='visible'){
      if(!wakeLock){wakeLock=await navigator.wakeLock.request('screen');wakeLock.addEventListener('release',()=>{wakeLock=null})}
    }else if(wakeLock){await wakeLock.release();wakeLock=null}
  }catch(_){}
}
function applySettings(){
  document.body.classList.toggle('dark',S.theme==='dark');
  document.documentElement.style.setProperty('--ui',String(S.uiScale/100));
  D=qualityDPR();resize();
  state.minimap=!!S.minimap;
  if(!S.minimap)mini.style.display='none';
  const t=document.getElementById('touch');t.style.display=S.touch==='on'?'block':S.touch==='off'?'none':'';
  document.getElementById('fps').style.display=S.fps?'block':'none';
  applyExtra();
  const chat=document.getElementById('arenaChat');if(chat)chat.style.display=(state.arenaJoined&&S.chat)?'block':'none';
  applyWakeLock();
}
let fpsFrames=0,fpsLast=0;
function fpsTick(t){
  if(!S.fps)return;
  fpsFrames++;
  if(!fpsLast)fpsLast=t;
  if(t-fpsLast>=500){document.getElementById('fps').textContent=Math.round(fpsFrames*1000/(t-fpsLast))+' FPS';fpsFrames=0;fpsLast=t}
}

/* ----- Controlli personalizzabili e funzioni extra ----- */
const ACTIONS=[
  {id:'moveUp',l:'Muovi su',g:'Movimento'},
  {id:'moveDown',l:'Muovi giù',g:'Movimento'},
  {id:'moveLeft',l:'Muovi a sinistra',g:'Movimento'},
  {id:'moveRight',l:'Muovi a destra',g:'Movimento'},
  {id:'stop',l:'Ferma le cellule (tieni premuto)',g:'Movimento'},
  {id:'split',l:'Split',g:'Azioni'},
  {id:'doubleSplit',l:'Doppio split',g:'Azioni'},
  {id:'tripleSplit',l:'Triplo split',g:'Azioni'},
  {id:'maxSplit',l:'Split massimo (fino a 16)',g:'Azioni'},
  {id:'eject',l:'Espelli massa',g:'Azioni'},
  {id:'holdFeed',l:'Espelli massa continuo (tieni premuto)',g:'Azioni'},
  {id:'dash',l:'Dash velocità (7s, ricarica 30s)',g:'Azioni'},
  {id:'blink',l:'Blink (teletrasporto, ricarica 20s)',g:'Azioni'},
  {id:'barrier',l:'Barriera (invulnerabile 3s, ricarica 40s)',g:'Azioni'},
  {id:'shock',l:'Onda d’urto (respinge i nemici, ricarica 35s)',g:'Azioni'},
  {id:'shadow',l:'Ombra (invisibile ai nemici 5s, ricarica 45s)',g:'Azioni'},
  {id:'pause',l:'Pausa',g:'Gioco'},
  {id:'respawn',l:'Rigioca dopo la morte',g:'Gioco'},
  {id:'menu',l:'Menu di gioco',g:'Gioco'},
  {id:'screenshot',l:'Screenshot',g:'Gioco'},
  {id:'focusChat',l:'Scrivi in chat',g:'Gioco'},
  {id:'minimap',l:'Minimappa on/off',g:'Visuale'},
  {id:'zoomIn',l:'Zoom avanti',g:'Visuale'},
  {id:'zoomOut',l:'Zoom indietro',g:'Visuale'},
  {id:'zoomReset',l:'Reimposta zoom',g:'Visuale'},
  {id:'toggleNames',l:'Mostra/nascondi nomi',g:'Visuale'},
  {id:'toggleMass',l:'Mostra/nascondi massa',g:'Visuale'},
  {id:'toggleGrid',l:'Mostra/nascondi griglia',g:'Visuale'},
  {id:'toggleTheme',l:'Cambia tema chiaro/scuro',g:'Visuale'}
];
const HELD=new Set(['moveUp','moveDown','moveLeft','moveRight','stop','holdFeed']);
const MINI_SIZES={small:120,medium:150,large:200};
const DEAD_ZONE={small:20,normal:40,large:90};
const FOOD_SCALE={small:.75,normal:1,large:1.4};
const BG_COLORS={default:['#dfe3e5','#151a20'],blue:['#d6e6f5','#0f1a2a'],green:['#dcebd8','#122017'],sand:['#efe6d2','#241f14'],white:['#ffffff','#000000']};
let rebinding=null;
function bgColor(){const m=BG_COLORS[S.bgColor]||BG_COLORS.default;return S.theme==='dark'?m[1]:m[0]}
function actionForCode(code){if(!code)return null;for(const a of ACTIONS)if(S.keys[a.id]===code)return a.id;if(code==='ShiftRight'&&S.keys.dash==='ShiftLeft')return 'dash';return null}
function keyLabel(c){
  if(!c)return '—';
  const m={Space:'SPAZIO',Escape:'ESC',Enter:'INVIO',Equal:'+',Minus:'-',Backspace:'⌫',Tab:'TAB',ShiftLeft:'SHIFT',ShiftRight:'SHIFT',ControlLeft:'CTRL',ControlRight:'CTRL',ArrowUp:'↑',ArrowDown:'↓',ArrowLeft:'←',ArrowRight:'→',Backquote:'`',Comma:',',Period:'.',Slash:'/',Semicolon:';',Quote:"'",BracketLeft:'[',BracketRight:']',Backslash:'\\'};
  if(m[c])return m[c];
  if(c.startsWith('Key'))return c.slice(3);
  if(c.startsWith('Digit'))return c.slice(5);
  if(c.startsWith('Numpad'))return 'NUM '+c.slice(6);
  return c.toUpperCase();
}
function vib(p){if(S.vibration&&navigator.vibrate){try{navigator.vibrate(p)}catch(_){}}}
function toggleSetting(k,label){S[k]=!S[k];saveSettings();applySettings();toast(label+(S[k]?' attivi':' disattivati'))}
function zoomBy(f){if(!state.playing)return;state.zoomMul=clamp(state.zoomMul*f,.4,2.5)}
function multiSplit(n){for(let i=0;i<n;i++)setTimeout(()=>{if(state.playing&&!state.paused)split()},i*110)}
function screenshot(){
  if(!state.playing&&!state.spectating){toast('Avvia una partita per fare uno screenshot');return}
  draw();
  canvas.toBlob(b=>{
    if(!b)return;
    const a=document.createElement('a');a.href=URL.createObjectURL(b);a.download='zeroagar-'+Date.now()+'.png';
    document.body.appendChild(a);a.click();a.remove();
    setTimeout(()=>URL.revokeObjectURL(a.href),2000);toast('📸 Screenshot salvato');
  },'image/png');
}
function focusChat(){
  const i=document.getElementById('arenaChatInput');
  if(state.arenaJoined&&S.chat&&i)i.focus({preventScroll:true});else toast('Chat non disponibile');
}
function runAction(a){
  switch(a){
    case 'split':split();break;
    case 'doubleSplit':multiSplit(2);break;
    case 'tripleSplit':multiSplit(3);break;
    case 'maxSplit':multiSplit(4);break;
    case 'eject':eject();break;
    case 'dash':startDash();break;
    case 'blink':case 'barrier':case 'shock':case 'shadow':useAbility(a);break;
    case 'pause':togglePause();break;
    case 'respawn':if(state.gameOver&&!state.playing)respawn();break;
    case 'menu':openSheet(state.playing?'pause':'settings');break;
    case 'screenshot':screenshot();break;
    case 'focusChat':focusChat();break;
    case 'minimap':toggleMinimap();break;
    case 'zoomIn':zoomBy(1.12);break;
    case 'zoomOut':zoomBy(1/1.12);break;
    case 'zoomReset':state.zoomMul=1;toast('Zoom reimpostato');break;
    case 'toggleNames':toggleSetting('names','Nomi');break;
    case 'toggleMass':toggleSetting('mass','Massa');break;
    case 'toggleGrid':toggleSetting('grid','Griglia');break;
    case 'toggleTheme':S.theme=S.theme==='dark'?'light':'dark';saveSettings();applySettings();break;
  }
}
function buildKeys(){
  const wrap=el('div','keys');
  let grp='';
  for(const a of ACTIONS){
    if(a.g!==grp){
      grp=a.g;
      const h=el('div','',grp);
      h.style.cssText='font-weight:800;font-size:12px;margin:14px 0 2px;text-transform:uppercase;color:#13b8ef';
      wrap.appendChild(h);
    }
    const row=el('div','opt');row.appendChild(el('div','',a.l));
    const on=rebinding===a.id;
    const b=el('button',on?'blue':'gray',on?'Premi un tasto…':keyLabel(S.keys[a.id]));
    b.style.minWidth='120px';
    b.onclick=()=>{rebinding=on?null:a.id;renderSettings()};
    row.appendChild(b);wrap.appendChild(row);
  }
  const r=el('button','gray','Ripristina tasti');
  r.style.cssText='width:100%;margin-top:14px';
  r.onclick=()=>{S.keys=Object.assign({},DEFAULT_KEYS);rebinding=null;saveSettings();applyExtra();renderSettings();toast('Tasti ripristinati')};
  wrap.appendChild(r);
  wrap.appendChild(el('div','help','Clicca un tasto e premi quello nuovo · ESC annulla · Backspace rimuove il tasto'));
  return wrap;
}
function applyExtra(){
  const ms=MINI_SIZES[S.minimapSize]||150;
  if(mini.width!==ms){mini.width=ms;mini.height=ms}
  mini.style.width=ms+'px';mini.style.height=ms+'px';
  mini.classList.toggle('left',S.minimapPos==='left');
  const tb=(S.minimap&&S.minimapPos!=='left')?68+ms+12:72;
  document.documentElement.style.setProperty('--touchb',tb+'px');
  const lb=document.getElementById('leader');if(lb)lb.style.display=S.showLeaderboard?'':'none';
  const fe=document.getElementById('feed');if(fe)fe.style.display=S.showFeed?'':'none';
  const setT=(id,t)=>{const x=document.getElementById(id);if(x)x.textContent=t};
  setT('splitBtn','SPLIT ['+keyLabel(S.keys.split)+']');
  setT('ejectBtn','MASSA ['+keyLabel(S.keys.eject)+']');
  setT('miniBtn','MAPPA ['+keyLabel(S.keys.minimap)+']');
  setT('pauseBtn','PAUSA ['+keyLabel(S.keys.pause)+']');
  setT('pauseHelp','premi '+keyLabel(S.keys.pause)+' per riprendere');
}
function drawWorldExtras(){
  if(!S.worldBorder)return;
  const z=state.camera.zoom,p=screenPoint(0,0),sz=state.world*z;
  ctx.strokeStyle='rgba(255,63,88,.75)';ctx.lineWidth=Math.max(3,6*z);ctx.strokeRect(p.x,p.y,sz,sz);
}
function drawCellExtras(c,p,z){
  if(!S.mergeTimer||state.cells.length<2)return;
  const r=c.r*z;if(r<16)return;
  if(c.merge>0){
    ctx.fillStyle='#fff';ctx.font='bold '+Math.max(10,Math.min(16,r*.28))+'px Arial';ctx.textAlign='center';
    ctx.fillText(Math.ceil(c.merge)+'s',p.x,p.y+r*.55);
  }else{
    ctx.beginPath();ctx.arc(p.x,p.y,r+4,0,Math.PI*2);ctx.strokeStyle='rgba(88,209,0,.9)';ctx.lineWidth=3;ctx.stroke();
  }
}
function drawCursorLine(){
  if(!S.cursorLine||!state.playing)return;
  const wm=worldMouse(),q=screenPoint(wm.x,wm.y);
  ctx.save();ctx.setLineDash([6,6]);ctx.lineWidth=1.5;ctx.strokeStyle=S.theme==='dark'?'rgba(255,255,255,.35)':'rgba(0,0,0,.3)';
  for(const c of state.cells){const p=screenPoint(c.x,c.y);ctx.beginPath();ctx.moveTo(p.x,p.y);ctx.lineTo(q.x,q.y);ctx.stroke()}
  ctx.restore();
}
function drawScoreHud(){
  if(!S.showScore||!state.playing)return;
  const t0='Massa '+Math.floor(state.score)+' · Cellule '+state.cells.length+'/'+MAX_CELLS;const t=window.ZA_T?ZA_T(t0):t0;
  ctx.font='bold 18px Arial';ctx.textAlign='center';ctx.lineWidth=4;
  ctx.strokeStyle=S.theme==='dark'?'rgba(0,0,0,.6)':'rgba(255,255,255,.85)';ctx.strokeText(t,W/2,72);
  ctx.fillStyle=S.theme==='dark'?'#e8ecf1':'#222';ctx.fillText(t,W/2,72);
}
/* Minimappa: i giocatori online sono disegnati con un punto ben visibile (raggio minimo 4px) e anello bianco */
function drawMinimap(){
  if(!state.minimap)return;
  const MS=mini.width,k=MS/state.world;
  mini.style.display='block';mctx.clearRect(0,0,MS,MS);
  mctx.fillStyle=S.theme==='dark'?'#1d232c':'#f2f2f2';mctx.fillRect(0,0,MS,MS);
  mctx.strokeStyle='#aaa';mctx.lineWidth=1;mctx.strokeRect(.5,.5,MS-1,MS-1);
  const z=state.camera.zoom,vw=W/z*k,vh=H/z*k;
  mctx.strokeStyle='rgba(19,184,239,.85)';mctx.strokeRect(state.camera.x*k-vw/2,state.camera.y*k-vh/2,vw,vh);
  for(const b of state.bots){mctx.fillStyle=b.color;mctx.fillRect(b.x*k-1,b.y*k-1,2,2)}
  for(const p of Object.values(state.remotePlayers)){
    const cs=p.cells||[];
    for(const c of cs){
      const rr=Math.max(4,(Number(c.r)||massToRadius(c.m||10))*k);
      mctx.beginPath();mctx.arc(c.x*k,c.y*k,rr,0,Math.PI*2);
      mctx.fillStyle='#ff3f58';mctx.fill();
      mctx.lineWidth=1.5;mctx.strokeStyle='#fff';mctx.stroke();
    }
    if(cs.length){
      let best=cs[0];for(const c of cs)if((c.m||0)>(best.m||0))best=c;
      mctx.font='bold 9px Arial';mctx.textAlign='center';mctx.lineWidth=3;
      mctx.strokeStyle=S.theme==='dark'?'rgba(0,0,0,.8)':'rgba(255,255,255,.9)';
      mctx.strokeText(p.username||'',best.x*k,best.y*k-7);
      mctx.fillStyle=S.theme==='dark'?'#e8ecf1':'#111';mctx.fillText(p.username||'',best.x*k,best.y*k-7);
    }
  }
  for(const c of state.cells){mctx.fillStyle=c.color;mctx.beginPath();mctx.arc(c.x*k,c.y*k,Math.max(3,c.r*k),0,Math.PI*2);mctx.fill();mctx.strokeStyle='#0876d1';mctx.lineWidth=1;mctx.stroke()}
  mctx.fillStyle=S.theme==='dark'?'#e8ecf1':'#111';mctx.font='9px Arial';mctx.textAlign='left';mctx.fillText(state.mode.toUpperCase(),6,MS-5);
}
const SCHEMA=[
  {name:'Gioco',items:[
    {k:'names',t:'toggle',l:'Mostra i nomi'},
    {k:'mass',t:'toggle',l:'Mostra la massa',h:'Il valore numerico sulle palline'},
    {k:'autoZoom',t:'toggle',l:'Zoom automatico',h:'La visuale si allarga quando cresci'},
    {k:'botCount',t:'select',l:'Numero di bot',h:'Vale dalla prossima partita',o:[['low','Pochi'],['normal','Normale'],['high','Tanti']]},
    {k:'botSkill',t:'select',l:'Difficoltà dei bot',o:[['easy','Facile'],['normal','Normale'],['hard','Difficile']]},
    {k:'chat',t:'toggle',l:'Chat arena online'},
    {k:'feedRate',t:'select',l:'Velocità espulsione massa',h:'Vale quando tieni premuto il tasto di espulsione continua',o:[['slow','Lenta'],['normal','Normale'],['fast','Veloce']]},
    {k:'deadZone',t:'select',l:'Zona morta del cursore',h:'Più è grande, più le cellule rallentano vicino al cursore',o:[['small','Piccola'],['normal','Normale'],['large','Grande']]},
    {k:'autoRespawn',t:'toggle',l:'Respawn automatico',h:'Rientri in partita 2,5 secondi dopo la morte'},
    {k:'vibration',t:'toggle',l:'Vibrazione',h:'Su telefoni compatibili: split, kill e morte'}
  ]},
  {name:'Grafica',items:[
    {k:'theme',t:'select',l:'Tema',o:[['light','Chiaro'],['dark','Scuro']]},
    {k:'quality',t:'select',l:'Qualità',h:'Bassa = più fluido sui telefoni',o:[['low','Bassa'],['medium','Media'],['high','Alta']]},
    {k:'grid',t:'toggle',l:'Griglia di sfondo'},
    {k:'particles',t:'toggle',l:'Effetti particellari'},
    {k:'minimap',t:'toggle',l:'Minimappa'},
    {k:'fps',t:'toggle',l:'Mostra FPS'},
    {k:'minimapSize',t:'select',l:'Dimensione minimappa',o:[['small','Piccola'],['medium','Media'],['large','Grande']]},
    {k:'minimapPos',t:'select',l:'Posizione minimappa',o:[['right','Destra'],['left','Sinistra']]},
    {k:'borderWidth',t:'range',l:'Spessore bordo cellule',min:1,max:8,step:1,u:' px'},
    {k:'mergeTimer',t:'toggle',l:'Timer di riunione sulle cellule',h:'Conto alla rovescia e anello verde quando puoi fonderti'},
    {k:'worldBorder',t:'toggle',l:'Bordo della mappa'},
    {k:'cursorLine',t:'toggle',l:'Linea verso il cursore'},
    {k:'foodSize',t:'select',l:'Dimensione del cibo',o:[['small','Piccolo'],['normal','Normale'],['large','Grande']]},
    {k:'bgColor',t:'select',l:'Colore di sfondo',o:[['default','Classico'],['blue','Blu'],['green','Verde'],['sand','Sabbia'],['white','Bianco/Nero']]}
  ]},
  {name:'Audio',items:[
    {k:'sound',t:'toggle',l:'Effetti sonori'},
    {k:'volume',t:'range',l:'Volume',min:0,max:100,step:5,u:'%'}
  ]},
  {name:'Schermo',items:[
    {t:'fullscreen',l:'Schermo intero'},
    {k:'uiScale',t:'range',l:'Dimensione interfaccia',min:80,max:130,step:5,u:'%'},
    {k:'touch',t:'select',l:'Pulsanti touch',o:[['auto','Automatico'],['on','Sempre visibili'],['off','Nascosti']]},
    {k:'keepAwake',t:'toggle',l:'Tieni lo schermo acceso',h:'Se il browser lo supporta'}
  ]},
  {name:'Interfaccia',items:[
    {k:'showScore',t:'toggle',l:'Mostra massa e numero di cellule'},
    {k:'showLeaderboard',t:'toggle',l:'Mostra la classifica'},
    {k:'showFeed',t:'toggle',l:'Mostra il kill feed'}
  ]},
  {name:'Controlli',items:[
    {k:'mouseLeft',t:'select',l:'Tasto sinistro del mouse',o:[['none','Nessuna azione'],['split','Split'],['doubleSplit','Doppio split'],['maxSplit','Split massimo'],['eject','Espelli massa']]},
    {k:'mouseRight',t:'select',l:'Tasto destro del mouse',o:[['none','Nessuna azione'],['split','Split'],['doubleSplit','Doppio split'],['maxSplit','Split massimo'],['eject','Espelli massa']]},
    {t:'keys'}
  ]}
];
const overlay=document.getElementById('overlay'),sheet=document.getElementById('sheet');
let sheetMode=null,sheetTab=0,autoPaused=false;
function el(tag,cls,text){const e0=document.createElement(tag);if(cls)e0.className=cls;if(text!=null)e0.textContent=text;return e0}
function fmtTime(s){s=Math.floor(s||0);const h=Math.floor(s/3600),m=Math.floor(s%3600/60);return h?h+'h '+m+'m':m+'m '+(s%60)+'s'}
function openSheet(mode){
  if(state.playing&&!state.paused){state.paused=true;autoPaused=true}
  sheetMode=mode;overlay.style.display='flex';
  document.getElementById('userMenu').style.display='none';
  renderSheet();
}
function closeSheet(){
  overlay.style.display='none';sheetMode=null;rebinding=null;
  if(autoPaused){state.paused=false;autoPaused=false}
}
function renderSheet(){
  if(sheetMode==='settings')renderSettings();
  else if(sheetMode==='profile')renderProfile();
  else if(sheetMode==='pause')renderPauseMenu();
  else if(sheetMode==='shop')renderShop();
  else if(sheetMode==='pass')renderPass();
  else if(sheetMode==='skins')renderCollection();
}
function sheetHead(title){
  sheet.innerHTML='';
  const head=el('div','sh-head');head.appendChild(el('span','',title));
  const x=el('button','gray','✕');x.onclick=closeSheet;head.appendChild(x);
  sheet.appendChild(head);
}
function buildOption(it){
  const row=el('div','opt');
  if(it.t==='info'){
    row.className='keys';
    row.innerHTML='<div style="width:100%;font-size:13px;line-height:1.9"><b>Mouse / dito</b> muovi · <b>Frecce</b> muovi con la tastiera<br><b>SPACE</b> split · <b>W</b> espelli massa<br><b>P</b> pausa · <b>M</b> minimappa · <b>R</b> rigioca dopo la morte<br><b>ESC</b> menu di gioco · <b>rotellina</b> zoom</div>';
    return row;
  }
  if(it.t==='keys')return buildKeys();
  const lab=el('div');lab.appendChild(document.createTextNode(it.l||''));
  if(it.h)lab.appendChild(el('small','',it.h));
  row.appendChild(lab);
  if(it.t==='toggle'){
    const sw=el('div','sw'+(S[it.k]?' on':''));sw.setAttribute('role','switch');sw.tabIndex=0;
    const flip=()=>{S[it.k]=!S[it.k];sw.classList.toggle('on',!!S[it.k]);saveSettings();applySettings()};
    sw.onclick=flip;
    sw.onkeydown=e=>{if(e.key===' '||e.key==='Enter'){e.preventDefault();flip()}};
    row.appendChild(sw);
  }else if(it.t==='select'){
    const sel=document.createElement('select');
    for(const [v,n] of it.o){const o=document.createElement('option');o.value=v;o.textContent=n;if(S[it.k]===v)o.selected=true;sel.appendChild(o)}
    sel.onchange=()=>{S[it.k]=sel.value;saveSettings();applySettings()};
    row.appendChild(sel);
  }else if(it.t==='range'){
    const wrap=el('div','rng'),val=el('span','',S[it.k]+(it.u||''));
    const r=document.createElement('input');r.type='range';r.min=it.min;r.max=it.max;r.step=it.step;r.value=S[it.k];
    r.oninput=()=>{val.textContent=r.value+(it.u||'');if(it.k!=='uiScale'){S[it.k]=+r.value;applySettings()}};
    r.onchange=()=>{S[it.k]=+r.value;saveSettings();applySettings();if(it.k==='volume')beep('pickup')};
    wrap.appendChild(r);wrap.appendChild(val);row.appendChild(wrap);
  }else if(it.t==='fullscreen'){
    const b=el('button','blue',isFullscreen()?'Esci da schermo intero':'Attiva');
    b.onclick=toggleFullscreen;row.appendChild(b);
  }
  return row;
}
function renderSettings(){
  const tab=SCHEMA[sheetTab]||SCHEMA[0];
  sheetHead('⚙️ Impostazioni');
  const tabs=el('div','sh-tabs');
  SCHEMA.forEach((t,i)=>{const b=el('button',i===sheetTab?'on':'',t.name);b.onclick=()=>{sheetTab=i;renderSettings()};tabs.appendChild(b)});
  sheet.appendChild(tabs);
  const body=el('div','sh-body');
  for(const it of tab.items)body.appendChild(buildOption(it));
  sheet.appendChild(body);
  const foot=el('div','sh-foot');
  const rst=el('button','gray','Ripristina');
  rst.onclick=()=>{Object.assign(S,DEFAULTS);S.keys=Object.assign({},DEFAULT_KEYS);saveSettings();applySettings();renderSettings();toast('Impostazioni ripristinate')};
  const ok=el('button','green','Chiudi');ok.onclick=closeSheet;
  foot.appendChild(rst);foot.appendChild(ok);sheet.appendChild(foot);
}
function renderProfile(){
  const p=state.profile||{};
  const xp=+p.xp||0,skin=p.equipped?.cell_skin;
  sheetHead('👤 Profilo');
  const body=el('div','sh-body');
  body.innerHTML=
    '<div style="display:flex;gap:12px;align-items:center;margin:8px 0">'+
      '<div class="avatar" style="float:none;margin:0;'+(skin?'background:'+esc(skin):'')+'"></div>'+
      '<div><div style="font-size:18px;font-weight:800">'+esc(state.nick)+'</div><span class="badge">'+esc((p.role||'user').toUpperCase())+'</span></div>'+
    '</div>'+
    '<div class="xp"><i style="width:'+Math.min(100,(xp%1000)/10)+'%"></i></div>'+
    '<div class="stat-grid">'+
      '<div class="stat"><b>'+(p.level||1)+'</b><span>Livello</span></div>'+
      '<div class="stat"><b>'+xp+'</b><span>XP</span></div>'+
      '<div class="stat"><b>'+Math.floor(state.best)+'</b><span>Record</span></div>'+
      '<div class="stat"><b>'+state.plays+'</b><span>Partite</span></div>'+
      '<div class="stat"><b>'+fmtTime(state.time)+'</b><span>Tempo totale</span></div>'+
      '<div class="stat"><b>'+state.kills+'</b><span>Kill (ultima partita)</span></div>'+
      '<div class="stat"><b>'+state.foodEaten+'</b><span>Cibo (ultima partita)</span></div>'+
      '<div class="stat"><b>'+state.bestCombo+'</b><span>Combo max (ultima partita)</span></div>'+
    '</div>'+
    '<div class="help">'+(state.loggedIn?'Account collegato · i progressi vengono salvati nel database.':'Sessione non disponibile · accedi dal portale per salvare i progressi.')+'</div>';
  sheet.appendChild(body);
  const foot=el('div','sh-foot');
  const st=el('button','blue','Impostazioni');st.onclick=()=>{sheetMode='settings';renderSheet()};
  const ok=el('button','green','Chiudi');ok.onclick=closeSheet;
  foot.appendChild(st);foot.appendChild(ok);sheet.appendChild(foot);
}
function renderPauseMenu(){
  sheetHead('⏸ Menu di gioco');
  const body=el('div','sh-body sh-menu');
  const add=(label,cls,fn)=>{const b=el('button',cls,label);b.onclick=fn;body.appendChild(b)};
  add('▶ Riprendi','green',closeSheet);
  add('⚙️ Impostazioni','blue',()=>{sheetMode='settings';renderSheet()});
  add('👤 Profilo e statistiche','blue',()=>{sheetMode='profile';renderSheet()});
  add(isFullscreen()?'🗗 Esci da schermo intero':'⛶ Schermo intero','gray',toggleFullscreen);
  add('💾 Salva progressi','gray',()=>{saveProgress(true);toast('Salvataggio in corso…')});
  add('🏠 Salva ed esci al portale','gray',goPortal);
  sheet.appendChild(body);
}
function userAction(a){
  if(a==='profile')openSheet('profile');
  else if(a==='settings')openSheet('settings');
  else if(a==='fullscreen')toggleFullscreen();
  else if(a==='save'){saveProgress(true);toast('Salvataggio in corso…')}
  else if(a==='refresh')loadAccount();
  else if(a==='shop')openSheet('shop');
  else if(a==='pass')openSheet('pass');
  else if(a==='skins')openSheet('skins');
  else if(a==='admin'){endGameReward(true);saveProgress(true);location.href='zeroagar_admin.php'}
  else if(a==='portal')goPortal();
}
document.getElementById('profile').addEventListener('click',e=>{
  const um=document.getElementById('userMenu');
  const it=e.target.closest('[data-um]');
  if(it){um.style.display='none';userAction(it.dataset.um);return}
  um.style.display=um.style.display==='none'?'block':'none';
});
document.addEventListener('click',e=>{if(!e.target.closest('#profile'))document.getElementById('userMenu').style.display='none'});
overlay.addEventListener('mousedown',e=>{if(e.target===overlay)closeSheet()});
document.getElementById('gearBtn').onclick=()=>{if(sheetMode){closeSheet();return}openSheet(state.playing?'pause':'settings')};
document.getElementById('fsBtn').onclick=toggleFullscreen;
document.getElementById('settingsBtn').onclick=()=>openSheet('settings');
document.getElementById('profileBtn').onclick=()=>openSheet('profile');
document.addEventListener('fullscreenchange',syncFullscreenUI);
document.addEventListener('webkitfullscreenchange',syncFullscreenUI);
document.addEventListener('visibilitychange',applyWakeLock);
addEventListener('keyup',e=>{const a=actionForCode(e.code);if(a&&HELD.has(a))arrows[a]=false});
addEventListener('blur',()=>{for(const k in arrows)arrows[k]=false});

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
    await loadEco();
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
  if(!state.online||!state.loggedIn){if(force)toast('Non collegato: accedi dal portale per salvare i progressi');return}
  if(state.saving)return;
  if(!force&&Date.now()-state.lastSave<15000)return;
  state.saving=true;
  state.lastSave=Date.now();
  try{
    await api('game_save',{game:'zeroagar_classic',score:Math.floor(state.score),data:{best:Math.floor(state.best),plays:state.plays,total:Math.floor(state.time),kills:state.kills,particles:state.foodEaten,mode:state.mode}});
    if(force)toast('Progressi salvati ✔');
  }catch(e){
    console.warn('[ZeroAgar] salvataggio fallito',e);
    toast('Salvataggio non riuscito: '+e.message);
  }finally{state.saving=false}
}

/* Classifica: record salvati nel database + giocatori ONLINE adesso (con il loro punteggio live) + tu.
   Un giocatore live sostituisce il suo record salvato; i live sono marcati con un pallino verde. */
function updateLeaders(){
  const real=Array.isArray(state.serverLeaders)?state.serverLeaders:[];
  const meName=String(state.nick||'').toLowerCase();
  const live=Object.values(state.remotePlayers||{}).map(p=>({username:String(p.username||'?'),score:Math.floor(+p.score||0),live:true}));
  const liveNames=new Set(live.map(v=>v.username.toLowerCase()));
  let vals=real.filter(v=>{const n=String(v.username||'').toLowerCase();return n!==meName&&!liveNames.has(n)});
  vals=vals.concat(live);
  vals.push({username:state.nick||'Tu',score:Math.floor(state.score),me:true,live:state.playing});
  vals.sort((a,b)=>(+b.score||0)-(+a.score||0));
  document.getElementById('leaders').innerHTML=vals.slice(0,10).map((v,i)=>`<div class="row ${v.me?'me':''}"><span>${i+1}. ${v.live&&!v.me?'<span style="color:#58d100">●</span> ':''}${esc(v.username||'')}</span><b>${Math.floor(+v.score||0)}</b></div>`).join('');
}

function food(){return{x:rand(50,state.world-50),y:rand(50,state.world-50),r:rand(3,6),m:8,c:palette[(Math.random()*palette.length)|0]}}
function virus(){return{x:rand(500,state.world-500),y:rand(500,state.world-500),r:48,m:100,spin:rand(0,Math.PI*2)}}
function createBot(i){if(state.mode==='zombie')return createZombie(i,false);const team=state.mode==='ffa'?null:i%3;const m=rand(25,150);return{x:rand(300,state.world-300),y:rand(300,state.world-300),r:massToRadius(m),m,vx:rand(-35,35),vy:rand(-35,35),name:names[i%names.length],team,angle:rand(0,Math.PI*2),think:0,color:team==null?palette[i%palette.length]:TEAM_COLORS[team],alive:true}}
/* bx/by = spinta dello split (decade nel tempo), vx/vy = movimento verso il cursore */
function makeCell(x,y,m,color,name,team=null,player=false){return{x,y,m,r:massToRadius(m),vx:0,vy:0,bx:0,by:0,merge:0,color,name,team,player}}
function resetWorld(){
  state.foods=[];state.ejected=[];state.viruses=[];state.bots=[];
  initMode();
  const mode=state.mode;
  state.pickups=[];for(let i=0;i<10;i++)state.pickups.push(spawnPickup());
  const ft=Math.round(foodTarget()*({experimental:1.5,survivor:.7,party:1.2,zombie:.8}[mode]||1));
  for(let i=0;i<ft;i++)state.foods.push(food());
  const vc=({experimental:16,survivor:6,zombie:4,party:0}[mode]??10);for(let i=0;i<vc;i++)state.viruses.push(virus());
  const base=({low:10,normal:18,high:30}[S.botCount]||18);
  const bc=mode==='zombie'?8:mode==='survivor'?Math.max(14,base):base+(mode==='teams'?6:0)+(mode==='experimental'?4:0);
  for(let i=0;i<bc;i++)state.bots.push(createBot(i));
  if(mode==='party'){state.partyCode='ZA-'+Math.random().toString(36).slice(2,7).toUpperCase();toast('Party code: '+state.partyCode)}
}

/* ----- Arena multiplayer ----- */
function arenaCellsPayload(){return state.cells.map(c=>({x:c.x,y:c.y,m:c.m,r:c.r,color:c.color,team:c.team}))}
/* Giocatori remoti: per ognuno si conserva una breve storia di "istantanee" (posizione + istante di arrivo).
   Il disegno li mostra con un piccolo ritardo, interpolando tra due istantanee: così il movimento è fluido
   anche se il server risponde a intervalli irregolari (prima si usava un inseguimento esponenziale che andava a scatti). */
function updateRemotePlayers(players){
  const old=state.remotePlayers,map={},t=now();
  for(const p of (players||[])){
    if(p.self||!p.alive)continue;
    const prev=old[p.id];
    p.snaps=(prev&&prev.snaps)?prev.snaps:[];
    const cells=(p.cells||[]).map(c=>({x:+c.x||0,y:+c.y||0,r:Number(c.r)||massToRadius(c.m||10)}));
    const last=p.snaps[p.snaps.length-1];
    const same=last&&last.c.length===cells.length&&cells.every((c,i)=>c.x===last.c[i].x&&c.y===last.c[i].y&&c.r===last.c[i].r);
    if(!same){
      if(last){
        const iv=t-last.t;
        /* dopo una pausa (giocatore fermo) si inserisce un punto "fermo" appena prima, così non c'è uno scatto */
        if(iv>state.rIv*2.2)p.snaps.push({t:t-state.rIv,c:last.c});
        else state.rIv=state.rIv*.85+clamp(iv,30,400)*.15;
      }
      p.snaps.push({t,c:cells});
      while(p.snaps.length>8)p.snaps.shift();
    }
    map[p.id]=p;
  }
  state.remotePlayers=map;
  const e=document.getElementById('arenaOnline');if(e)e.textContent=((players||[]).length||0)+' online';
}
const CHAT_EMOJI={':fire:':'🔥',':heart:':'❤️',':lol:':'😂',':cool:':'😎',':wow:':'😮',':gg:':'🏆',':ok:':'👌',':skull:':'💀',':gg2:':'🎉'};
function chatBody(text){
  let out=esc(text);
  for(const k in CHAT_EMOJI)out=out.split(k).join(CHAT_EMOJI[k]);
  return out.replace(/https:\/\/[^\s<>]+?\.(gif|png|jpe?g|webp)(\?[^\s<>]*)?/gi,u=>'<img class="chat-gif" src="'+u+'" alt="gif" loading="lazy" referrerpolicy="no-referrer">');
}
function renderArenaChat(messages){
  const box=document.getElementById('arenaChatBody');if(!box||!Array.isArray(messages))return;
  for(const m of messages){
    const id='am-'+m.id;if(document.getElementById(id))continue;
    const row=document.createElement('div');row.className='chat-msg';row.id=id;
    const tag=m.role_label?'<span class="role-tag" style="background:'+esc(m.role_color)+'">'+esc(m.role_label)+'</span> ':'';
    const nameStyle=m.role_color?' style="color:'+esc(m.role_color)+'"':'';
    row.innerHTML=tag+'<b'+nameStyle+'>'+esc(m.username)+'</b>: '+chatBody(m.text);
    box.appendChild(row);
  }
  while(box.children.length>100)box.removeChild(box.firstChild);
  box.scrollTop=box.scrollHeight;
}
function showArenaChat(show){const c=document.getElementById('arenaChat');if(c)c.style.display=(show&&S.chat)?'block':'none'}
async function arenaJoin(){
  if(!state.online||!state.loggedIn||state.arenaJoined||!state.playing)return;
  try{
    const r=await arenaCall('join',{color:playerSkinColor(),team:state.cells[0]?.team??null,score:Math.floor(state.score),cells:arenaCellsPayload()});
    if(r?.success){state.arenaJoined=true;updateRemotePlayers(r.players||[]);showArenaChat(true);await arenaSync(true)}
  }catch(e){console.warn('[ZeroAgar] arena join fallito',e)}
}
function applyLost(list){
  for(const L of list){
    let best=-1,bd=1e9;
    state.cells.forEach((c,i)=>{const d=Math.abs(c.m-L.m)+Math.hypot(c.x-L.x,c.y-L.y)*.2;if(d<bd){bd=d;best=i}});
    if(best>=0)state.cells.splice(best,1);
  }
  if(!state.cells.length&&state.playing){state.arenaJoined=false;die('Un giocatore online ti ha divorato')}
}
async function arenaUpdate(){
  if(!state.arenaJoined||!state.playing||state.arenaUpdateBusy)return;
  state.arenaUpdateBusy=true;
  try{
    const r=await arenaCall('update',{color:playerSkinColor(),team:state.cells[0]?.team??null,score:Math.floor(state.score),cells:arenaCellsPayload()});
    if(r?.dead){state.arenaJoined=false;die('Sei stato divorato da un giocatore online');return}
    if(Number.isFinite(Number(r?.server_score)))state.score=Math.max(state.score,Number(r.server_score));
    if(Array.isArray(r?.lost)&&r.lost.length)applyLost(r.lost);
    if(Number(r?.gained)>0&&state.cells.length){const lead=state.cells.reduce((a,c)=>c.m>a.m?c:a,state.cells[0]);lead.m+=Number(r.gained);lead.r=massToRadius(lead.m)}
    if(!state.playing)return;
    updateRemotePlayers(r.players||[]);
    state.lastUpd=now();
  }catch(e){
    if(/arena|join/i.test(String(e.message))){state.arenaJoined=false;arenaJoin()}
  }finally{state.arenaUpdateBusy=false}
}
async function arenaSync(force=false){
  if(!state.arenaJoined||state.arenaSyncBusy)return;
  state.arenaSyncBusy=true;
  try{
    const r=await arenaCall('sync',{since:force?0:state.chatSince});
    if(r?.success){
      /* le posizioni arrivano da update (più frequente): sync le usa solo se update è fermo da più di 1s */
      if(force||!state.lastUpd||now()-state.lastUpd>1000)updateRemotePlayers(r.players||[]);
      renderArenaChat(r.messages||[]);if(r.last_chat)state.chatSince=r.last_chat;
    }
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
  if(!chatAllowed(text))return;
  try{const r=await arenaCall('chat',{text});if(r?.success)await arenaSync(true)}
  catch(e){toast(e.message||'Chat non disponibile')}
}
function stopArenaTimers(){state.arenaGen++;clearInterval(state.arenaTimer);clearTimeout(state.arenaUpdateTimer);clearInterval(state.skinTimer);state.arenaTimer=null;state.arenaUpdateTimer=null;state.skinTimer=null}
function startArenaTimers(){
  stopArenaTimers();
  const gen=state.arenaGen;
  state.arenaTimer=setInterval(()=>{if(!state.arenaJoined&&state.playing)arenaJoin();else arenaSync()},350);
  /* ciclo di aggiornamento posizione: una nuova richiesta parte ~100ms dopo l'inizio della precedente,
     senza "saltare" cicli come faceva il vecchio setInterval quando la risposta tardava */
  const loop=async()=>{
    if(gen!==state.arenaGen)return;
    const t0=now();
    await arenaUpdate();
    if(gen!==state.arenaGen)return;
    state.arenaUpdateTimer=setTimeout(loop,Math.max(0,100-(now()-t0)));
  };
  loop();
  state.skinTimer=setInterval(fetchRemoteSkins,6000);
}
/* Posizione di un giocatore remoto all'istante rt: interpolazione tra istantanee (con breve estrapolazione se i dati sono in ritardo) */
function remotePose(p,rt){
  const s=p.snaps;if(!s||!s.length)return null;
  const last=s[s.length-1];
  if(rt>=last.t){
    const prev=s[s.length-2];
    if(!prev||prev.c.length!==last.c.length||last.t-prev.t<20)return last.c;
    const span=last.t-prev.t,k=Math.min(rt-last.t,120)/span;
    return last.c.map((c,i)=>{
      const q=prev.c[i],dx=c.x-q.x,dy=c.y-q.y;
      if(Math.hypot(dx,dy)>900)return c;
      return{x:c.x+dx*k,y:c.y+dy*k,r:c.r};
    });
  }
  if(rt<=s[0].t)return s[0].c;
  let i=s.length-2;
  while(i>0&&s[i].t>rt)i--;
  const a=s[i],b=s[i+1];
  if(a.c.length!==b.c.length)return b.c;
  const f=clamp((rt-a.t)/Math.max(1,b.t-a.t),0,1);
  return b.c.map((c,j)=>{
    const q=a.c[j];
    if(Math.hypot(c.x-q.x,c.y-q.y)>900)return c;
    return{x:q.x+(c.x-q.x)*f,y:q.y+(c.y-q.y)*f,r:q.r+(c.r-q.r)*f};
  });
}
/* Disegno dei giocatori remoti, mostrati con un ritardo di ~1,6 intervalli di aggiornamento (min 110ms, max 350ms) */
function drawRemotePlayers(){
  const z=state.camera.zoom,rt=now()-clamp(state.rIv*1.6,110,350);
  for(const p of Object.values(state.remotePlayers)){
    const pose=remotePose(p,rt);if(!pose)continue;
    const raw=p.cells||[];
    for(let i=0;i<pose.length;i++){
      const v=pose[i],q=screenPoint(v.x,v.y),r=Math.max(5,v.r)*z;
      const col=(raw[i]&&raw[i].color)||p.color||'#ef476f';
      drawSkinCell(q,r,col,remoteSkin(p));
      if(S.names&&r>22){ctx.fillStyle='#fff';ctx.font='bold 13px Arial';ctx.textAlign='center';ctx.fillText(p.username,q.x,q.y+4)}
    }
  }
}

/* ----- Partita ----- */
function startGame(){
  if(ecoP()&&ecoP().banned){toast('Account sospeso'+(ecoP().ban_reason?': '+ecoP().ban_reason:''));return}
  state.nick=(state.profile?.username||state.profile?.name||document.getElementById('nick').value||'ZeroPlayer').trim().slice(0,20)||'ZeroPlayer';
  state.playing=true;state.spectating=false;state.gameOver=false;state.paused=false;state.rewarded=false;
  state.score=startMass();state.kills=0;state.foodEaten=0;
  state.fx={speed:0,shield:2*upLvl('shield'),magnet:0,double:0};state.dash={t:0,cd:0};state.dashAcc=0;state.flash=0;state.cd={blink:0,barrier:0,shock:0,shadow:0};state.inv=0;state.shadow=0;state.revives=upLvl('revive');state.won=false;state.combo=0;state.comboT=0;state.bestCombo=0;state.runTime=0;state.particles=[];state.feed=[];
  renderFeed();renderPowerups();
  document.getElementById('pauseBox').style.display='none';
  const spawn={x:rand(1300,4700),y:rand(1300,4700)};
  state.camera={x:spawn.x,y:spawn.y,zoom:1};state.zoomMul=1;
  state.cells=[makeCell(spawn.x,spawn.y,startMass(),playerSkinColor(),state.nick,state.mode==='teams'?Math.floor(Math.random()*3):null,true)];
  resetWorld();
  document.getElementById('menu').style.display='none';
  document.getElementById('death').style.display='none';
  document.getElementById('bottom').style.display='flex';
  document.getElementById('back').style.display='block';
  document.getElementById('tips').style.display='block';
  state.lastFrame=now();state.lastSave=Date.now();
  cancelAnimationFrame(state.rafId);
  state.rafId=requestAnimationFrame(loop);
  arenaJoin();startArenaTimers();spawnAllies();
  toast('Partita iniziata · '+state.mode.toUpperCase());
}
function respawn(){startGame()}
function die(reason='Divorato',final=false,won=false){
  if(!state.playing&&state.gameOver)return;
  if(!final&&reviveCheck(reason))return;
  state.won=won;
  state.playing=false;state.gameOver=true;state.plays++;
  state.best=Math.max(state.best,state.score);
  document.getElementById('deathScore').textContent=Math.floor(state.score);
  const mins=Math.floor(state.runTime/60),secs=Math.floor(state.runTime%60);
  document.getElementById('deathStats').textContent=`Record ${Math.floor(state.best)} · Kill ${state.kills} · Cibo ${state.foodEaten} · Combo max ${state.bestCombo} · Tempo ${mins}:${String(secs).padStart(2,'0')} · ${reason} · premi ${keyLabel(S.keys.respawn)} per rigiocare`;
  document.getElementById('pauseBox').style.display='none';
  document.getElementById('powerups').innerHTML='';
  document.getElementById('death').style.display='block';
  document.getElementById('bottom').style.display='none';
  document.getElementById('tips').style.display='none';
  stopArenaTimers();arenaLeave();beep('death');vib([60,40,90]);
  if(S.autoRespawn)setTimeout(()=>{if(state.gameOver&&!state.playing&&!state.spectating)respawn()},2500);
  saveProgress(true);refreshLeaderboard().then(updateLeaders);updateLeaders();endGameReward(false);
}
function canEat(a,b){return a.m>b.m*1.10&&a.r>b.r*1.08}

/* ----- Split in stile agar.io ----- */
const MAX_CELLS=16,MIN_SPLIT_MASS=36,SPLIT_SPEED=460,SPLIT_DECAY=4.5;
/* Timer di riunione: 30s base + 2,33% della massa della cellula (ridotto dal potenziamento "Fusione rapida") */
function mergeTime(m){return(30+m*0.0233)*(1-.04*upLvl('merge'))}
function worldMouse(){const z=state.camera.zoom;return{x:state.camera.x+(state.mouse.x-W/2)/z,y:state.camera.y+(state.mouse.y-H/2)/z}}
function split(){
  if(!state.playing||state.paused)return;
  const wm=worldMouse();
  /* si dividono prima le cellule più grandi; il limite è 16 cellule totali */
  const list=state.cells.filter(c=>c.m>=MIN_SPLIT_MASS).sort((a,b)=>b.m-a.m);
  let n=state.cells.length,did=false;
  for(const c of list){
    if(n>=MAX_CELLS)break;
    let dx=wm.x-c.x,dy=wm.y-c.y,l=Math.hypot(dx,dy);
    if(l<1){dx=1;dy=0;l=1}
    dx/=l;dy/=l;
    const half=c.m/2;
    c.m=half;c.r=massToRadius(half);
    const t=mergeTime(half);c.merge=t;
    const nc=makeCell(c.x+dx*c.r*.5,c.y+dy*c.r*.5,half,c.color,c.name,c.team,true);
    nc.vx=c.vx;nc.vy=c.vy;
    nc.bx=dx*SPLIT_SPEED;nc.by=dy*SPLIT_SPEED;
    nc.merge=t;
    state.cells.push(nc);n++;did=true;
  }
  if(did){toast('SPLIT');beep('pickup');vib(25)}
}
/* Le cellule dello stesso giocatore si respingono finché il timer non è scaduto */
function separateCells(dt){
  const cs=state.cells;
  for(let i=0;i<cs.length;i++)for(let j=i+1;j<cs.length;j++){
    const a=cs[i],b=cs[j];
    if(a.merge<=0&&b.merge<=0)continue;
    let dx=b.x-a.x,dy=b.y-a.y,d=Math.hypot(dx,dy);
    const min=a.r+b.r;
    if(d>=min)continue;
    if(d<.01){const g=Math.random()*6.28;dx=Math.cos(g);dy=Math.sin(g);d=1}
    const push=(min-d)*Math.min(1,dt*12),nx=dx/d,ny=dy/d,tm=a.m+b.m;
    a.x=clamp(a.x-nx*push*(b.m/tm),a.r,state.world-a.r);a.y=clamp(a.y-ny*push*(b.m/tm),a.r,state.world-a.r);
    b.x=clamp(b.x+nx*push*(a.m/tm),b.r,state.world-b.r);b.y=clamp(b.y+ny*push*(a.m/tm),b.r,state.world-b.r);
  }
}
function eject(){
  if(!state.playing||state.paused)return;
  const ang=Math.atan2(state.mouse.y-H/2,state.mouse.x-W/2);
  for(const c of state.cells){
    if(c.m<36)continue;
    c.m-=12;c.r=massToRadius(c.m);
    state.ejected.push({x:c.x+Math.cos(ang)*(c.r+9),y:c.y+Math.sin(ang)*(c.r+9),vx:Math.cos(ang)*700+c.vx,vy:Math.sin(ang)*700+c.vy,m:12,r:14,life:6,color:'#54b948',virus:true});
  }
}
function handleVirusHit(c,v){
  if(c.m<100)return;
  const n=Math.min(6,Math.floor(c.m/90));
  c.m*=0.9;c.r=massToRadius(c.m);
  c.merge=Math.max(c.merge,mergeTime(c.m));
  const ang=Math.atan2(c.y-v.y,c.x-v.x);
  for(let i=0;i<n&&state.cells.length<MAX_CELLS;i++){
    const a=ang+(i-(n-1)/2)*0.22;
    const nc=makeCell(c.x+Math.cos(a)*45,c.y+Math.sin(a)*45,Math.max(20,c.m/n*0.85),c.color,c.name,c.team,true);
    nc.bx=Math.cos(a)*420;nc.by=Math.sin(a)*420;nc.merge=mergeTime(nc.m);state.cells.push(nc);
  }
  v.x=rand(500,state.world-500);v.y=rand(500,state.world-500);
  toast('VIRUS!');
}
function updateBots(dt){
  for(const b of state.bots){
    if(!b.alive||b.dead)continue;
    if(b.stun>0){b.stun-=dt;continue}
    const mm=state.mm,zf=b.zombie?2.2*(1+.06*mm.wave):1,mf=(state.mode==='experimental'?1.2:1)*(mm.surge>0?1.3:1);
    b.think-=dt;
    if(b.think<=0){
      b.think=b.zombie?rand(.12,.35):rand(.25,1.4);
      let target=null,best=1e9;
      if(b.zombie){
        if(state.shadow<=0)for(const c of state.cells){const d=(c.x-b.x)**2+(c.y-b.y)**2;if(d<best&&d<1700*1700){best=d;target=c}}
      }else{
        for(const f of state.foods){const d=(f.x-b.x)**2+(f.y-b.y)**2;if(d<best){best=d;target=f}}
        if(state.shadow<=0)for(const c of state.cells){
          if(state.mode==='teams'&&b.team===c.team)continue;
          const d=Math.hypot(c.x-b.x,c.y-b.y);
          if(state.mode!=='party'&&canEat(b,c)&&d<(state.mode==='teams'?1100:800)){target=c;break}
          if(canEat(c,b)&&d<700){b.angle+=Math.PI;break}
        }
      }
      if(target)b.angle=Math.atan2(target.y-b.y,target.x-b.x)+(Math.random()-.5)*(b.zombie?.15:.5);
      else if(b.zombie)b.angle+=rand(-.8,.8);
      if(state.mode==='survivor'&&Math.hypot(b.x-mm.zx,b.y-mm.zy)>mm.zr-200)b.angle=Math.atan2(mm.zy-b.y,mm.zx-b.x);
    }
    b.vx+=Math.cos(b.angle)*25*botSkill()*zf*mf*dt;b.vy+=Math.sin(b.angle)*25*botSkill()*zf*mf*dt;
    const s=Math.hypot(b.vx,b.vy)||1;const max=70*botSkill()*zf*mf/(1+Math.sqrt(b.m)/80);
    if(s>max){b.vx=b.vx/s*max;b.vy=b.vy/s*max}
    b.x=clamp(b.x+b.vx*dt,b.r,state.world-b.r);b.y=clamp(b.y+b.vy*dt,b.r,state.world-b.r);
  }
}
function update(dt){
  if(!state.playing||state.paused)return;
  const first=state.cells[0];if(!first)return;
  const ax=(arrows.moveRight?1:0)-(arrows.moveLeft?1:0),ay=(arrows.moveDown?1:0)-(arrows.moveUp?1:0);
  if(ax||ay){state.mouse.x=W/2+ax*300;state.mouse.y=H/2+ay*300}
  const wm=worldMouse();
  const dec=Math.exp(-SPLIT_DECAY*dt);
  for(const c of state.cells){
    /* ogni cellula punta al cursore, rallentando quando ci arriva sopra */
    const dx=wm.x-c.x,dy=wm.y-c.y,dist=Math.hypot(dx,dy)||1;
    const k=Math.min(1,dist/Math.max(DEAD_ZONE[S.deadZone]||40,c.r));
    const localSpeed=clamp(520/Math.sqrt(c.m)*35,45,300)*(state.fx.speed>0?1.6:1)*(state.dash.t>0?1.8:1)*modeSpeed()*speedMul()*k*(arrows.stop?0:1);
    c.vx+=(dx/dist*localSpeed-c.vx)*Math.min(1,dt*6);
    c.vy+=(dy/dist*localSpeed-c.vy)*Math.min(1,dt*6);
    /* la spinta dello split decade in modo esponenziale, indipendente dallo sterzo */
    c.bx*=dec;c.by*=dec;
    if(Math.abs(c.bx)<2)c.bx=0;
    if(Math.abs(c.by)<2)c.by=0;
    c.x=clamp(c.x+(c.vx+c.bx)*dt,c.r,state.world-c.r);
    c.y=clamp(c.y+(c.vy+c.by)*dt,c.r,state.world-c.r);
    c.merge=Math.max(0,c.merge-dt);
  }
  separateCells(dt);
  if(arrows.holdFeed){
    state.feedAcc+=dt;const iv=({slow:.18,normal:.09,fast:.045}[S.feedRate])||.09;
    while(state.feedAcc>=iv){state.feedAcc-=iv;eject()}
  }else state.feedAcc=0;
  for(let i=state.ejected.length-1;i>=0;i--){
    const e=state.ejected[i];e.life-=dt;
    e.vx*=Math.pow(.03,dt);e.vy*=Math.pow(.03,dt);
    e.x=clamp(e.x+e.vx*dt,e.r,state.world-e.r);e.y=clamp(e.y+e.vy*dt,e.r,state.world-e.r);
    if(e.life<=0)state.ejected.splice(i,1);
  }
  updateBots(dt);updateAllies(dt);passiveMagnet(dt);
  for(const c of state.cells){
    for(let i=state.foods.length-1;i>=0;i--){
      const f=state.foods[i];
      if(d2(c,f)<c.r){
        c.m+=f.m*(state.fx.double>0?2:1)*(1+.08*upLvl('feast'));c.r=massToRadius(c.m);
        burst(f.x,f.y,f.c,3,110);beep('eat');
        if(f.tmp)state.foods.splice(i,1);else state.foods[i]=food();
        state.foodEaten++;
        registerCombo(c);
      }
    }
  }
  for(const e of state.ejected){if(e.virus)continue;for(const c of state.cells){if(d2(e,c)<c.r){c.m+=e.m;c.r=massToRadius(c.m);e.life=0}}}
  for(const e of state.ejected){
    if(!e.virus||e.life<=0)continue;
    for(const b of state.bots){
      if(b.dead)continue;
      if(Math.hypot(b.x-e.x,b.y-e.y)<b.r+e.r*.6){
        if(b.m>=60){
          /* come un virus di agar.io: il bersaglio si spacca in due */
          const half=b.m/2,a=Math.atan2(b.y-e.y,b.x-e.x);
          b.m=half;b.r=massToRadius(half);
          state.bots.push(Object.assign({},b,{m:half,r:massToRadius(half),x:clamp(b.x+Math.cos(a)*b.r*1.5,b.r,state.world-b.r),y:clamp(b.y+Math.sin(a)*b.r*1.5,b.r,state.world-b.r),vx:Math.cos(a)*180,vy:Math.sin(a)*180,think:0}));
          burst(b.x,b.y,'#54b948',22,300);addFeed('🦠 Virus su '+b.name+': si è spaccato!');
        }else continue;
        e.life=0;break;
      }
    }
  }
  state.ejected=state.ejected.filter(e=>e.life>0);
  for(const b of state.bots){
    if(b.dead)continue;
    for(const c of state.cells.slice()){
      if(b.dead)break;
      if(state.mode==='teams'&&b.team===c.team)continue;
      const d=d2(b,c);
      if(canEat(c,b)&&d<c.r*.92){
        c.m+=b.m*(1+.06*upLvl('vampire'));c.r=massToRadius(c.m);state.kills++;
        burst(b.x,b.y,b.color,18,240);beep('kill');vib(35);addFeed('Hai divorato '+b.name+' (+'+Math.floor(b.m)+')');
        reviveBot(b);
      }else if(state.mode!=='zombie'&&state.mode!=='party'&&canEat(b,c)&&d<b.r*.92&&state.fx.shield<=0&&state.inv<=0){
        /* perdi solo la cellula mangiata: la partita finisce quando non ne resta nessuna */
        const idx=state.cells.indexOf(c);
        if(idx>=0)state.cells.splice(idx,1);
        burst(c.x,c.y,c.color,18,240);beep('death');vib([60,40,90]);
        addFeed('💥 '+b.name+' ha divorato una tua parte');
        if(!state.cells.length){die('Un rivale più grande ti ha divorato');return}
      }
    }
  }
  if(modeUpdate(dt))return;
  for(const v of state.viruses){for(const c of state.cells){if(d2(c,v)<c.r+v.r*.65){if(state.mode!=='ffa'||c.m>100)handleVirusHit(c,v)}}}
  updateExtras(dt);
  mergeCells();
  if(!state.cells.length){die('Partita terminata');return}
  {const rg=upLvl('regen');if(rg)for(const c of state.cells){c.m+=Math.min(c.m*.0025*rg,4*rg)*dt;c.r=massToRadius(c.m)}}
  state.score=state.cells.reduce((s,c)=>s+c.m,0);
  state.best=Math.max(state.best,state.score);
  state.time+=dt;
  /* la camera segue il centro di tutte le cellule */
  let cx=0,cy=0;for(const c of state.cells){cx+=c.x;cy+=c.y}
  state.camera.x+=(cx/state.cells.length-state.camera.x)*Math.min(1,dt*10);
  state.camera.y+=(cy/state.cells.length-state.camera.y)*Math.min(1,dt*10);
  {const base=S.autoZoom?clamp(1/Math.pow(Math.max(25,state.score)/25,.15),.28,1.15):1;
  const target=clamp(base*state.zoomMul,.2,2);
  state.camera.zoom+=(target-state.camera.zoom)*Math.min(1,dt*8)}
  if(++state.leaderTick%20===0){updateLeaders()}
  if(state.leaderTick%6===0)renderPowerups();
  if(state.leaderTick%300===0)refreshLeaderboard().then(updateLeaders);
  saveProgress();
  applyProfileUI();
}
/* Le cellule dello stesso giocatore si fondono quando il timer è scaduto e si toccano */
function mergeCells(){
  for(let i=state.cells.length-1;i>=0;i--){
    for(let j=i-1;j>=0;j--){
      const a=state.cells[i],b=state.cells[j];
      if(!a||!b||a.merge>0||b.merge>0)continue;
      const big=a.m>=b.m?a:b,small=big===a?b:a;
      if(d2(a,b)<big.r*.65){
        big.m+=small.m;big.r=massToRadius(big.m);
        big.bx+=small.bx;big.by+=small.by;
        state.cells.splice(state.cells.indexOf(small),1);
        break;
      }
    }
  }
}
function screenPoint(x,y){const z=state.camera.zoom;return{x:W/2+(x-state.camera.x)*z,y:H/2+(y-state.camera.y)*z}}
function draw(){
  ctx.clearRect(0,0,W,H);ctx.fillStyle=bgColor();ctx.fillRect(0,0,W,H);
  if(!state.playing&&!state.spectating)return;
  const z=state.camera.zoom;
  const grid=50*z,ox=W/2-state.camera.x*z,oy=H/2-state.camera.y*z;
  ctx.strokeStyle=S.theme==='dark'?'rgba(200,210,225,.08)':'rgba(90,100,110,.16)';ctx.lineWidth=1;
  if(S.grid)for(let x=((ox%grid)+grid)%grid;x<W;x+=grid){ctx.beginPath();ctx.moveTo(x,0);ctx.lineTo(x,H);ctx.stroke()}
  if(S.grid)for(let y=((oy%grid)+grid)%grid;y<H;y+=grid){ctx.beginPath();ctx.moveTo(0,y);ctx.lineTo(W,y);ctx.stroke()}
  drawWorldExtras();
  drawZone();
  drawRemotePlayers();
  drawPickups();
  for(const f of state.foods){const p=screenPoint(f.x,f.y);ctx.beginPath();ctx.arc(p.x,p.y,f.r*z*(FOOD_SCALE[S.foodSize]||1),0,Math.PI*2);ctx.fillStyle=f.c;ctx.fill()}
  drawParticles();
  for(const e of state.ejected){const p=screenPoint(e.x,e.y);if(e.virus){drawVirusShape(p.x,p.y,e.r*z);continue}ctx.beginPath();ctx.arc(p.x,p.y,e.r*z,0,Math.PI*2);ctx.fillStyle=e.color;ctx.fill()}
  for(const v of state.viruses){
    const p=screenPoint(v.x,v.y);ctx.save();ctx.translate(p.x,p.y);ctx.rotate(v.spin);ctx.beginPath();
    for(let i=0;i<40;i++){const a=i*Math.PI*2/40,r=(i%2?v.r*.84:v.r)*z;ctx.lineTo(Math.cos(a)*r,Math.sin(a)*r)}
    ctx.closePath();ctx.fillStyle='#54b948';ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=2;ctx.stroke();ctx.restore();
  }
  for(const b of state.bots){
    const p=screenPoint(b.x,b.y);ctx.beginPath();ctx.arc(p.x,p.y,b.r*z,0,Math.PI*2);ctx.fillStyle=b.color;ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=2;ctx.stroke();
    if(b.r*z>20){ctx.fillStyle='#fff';ctx.font='bold 12px Arial';ctx.textAlign='center';if(S.names)ctx.fillText(b.name,p.x,p.y+(S.mass?-1:4));if(S.mass)ctx.fillText(Math.floor(b.m),p.x,p.y+(S.names?13:4))}
  }
  drawAllies();
  /* le cellule più piccole vengono disegnate sotto quelle grandi */
  const drawCells=state.cells.slice().sort((a,b)=>a.m-b.m);
  for(const c of drawCells){
    const p=screenPoint(c.x,c.y);if(state.shadow>0)ctx.globalAlpha=.4;drawSkinCell(p,c.r*z,c.color,mySkin());
    if(c.r*z>22){ctx.fillStyle='#fff';ctx.font='bold 13px Arial';ctx.textAlign='center';if(S.names)ctx.fillText(state.nick,p.x,p.y+(S.mass?-1:4));if(S.mass)ctx.fillText(Math.floor(c.m),p.x,p.y+(S.names?14:4))}
    drawCellFx(c,p,z);drawCellExtras(c,p,z);ctx.globalAlpha=1;
  }
  drawDashFx();drawRadar();
  drawCursorLine();
  drawScoreHud();
  drawModeHud();
  drawMinimap();
}
let lastT=0;
function loop(t){
  if(!state.playing)return;
  const dt=Math.min(.05,(t-(lastT||t))/1000||.016);lastT=t;
  fpsTick(t);
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
function help(){toast('Split '+keyLabel(S.keys.split)+' · Massa '+keyLabel(S.keys.eject)+' · Pausa '+keyLabel(S.keys.pause)+' · Mappa '+keyLabel(S.keys.minimap)+' · Menu '+keyLabel(S.keys.menu)+' · tasti modificabili in Impostazioni')}
function goPortal(){if(state.playing)endGameReward(true);saveProgress(true);location.href=PORTAL_URL}

addEventListener('mousemove',e=>{state.mouse.x=e.clientX;state.mouse.y=e.clientY});
addEventListener('wheel',e=>{
  if(!state.playing||sheetMode)return;e.preventDefault();
  zoomBy(e.deltaY<0?1.08:.92);
},{passive:false});
addEventListener('keydown',e=>{
  if(rebinding){
    e.preventDefault();e.stopPropagation();
    if(e.code==='Escape'){rebinding=null;renderSettings();return}
    const code=(e.code==='Backspace'||e.code==='Delete')?'':e.code;
    if(code)for(const a of ACTIONS)if(a.id!==rebinding&&S.keys[a.id]===code)S.keys[a.id]='';
    S.keys[rebinding]=code;rebinding=null;saveSettings();applyExtra();renderSettings();
    return;
  }
  if(sheetMode){if(e.key==='Escape'||e.code===S.keys.menu)closeSheet();return}
  if(e.target&&(e.target.tagName==='INPUT'||e.target.tagName==='TEXTAREA')){if(e.key==='Escape')e.target.blur();return}
  if(e.ctrlKey||e.metaKey||e.altKey)return;
  const a=actionForCode(e.code);if(!a)return;
  e.preventDefault();
  if(HELD.has(a)){arrows[a]=true;return}
  if(e.repeat&&a!=='zoomIn'&&a!=='zoomOut')return;
  runAction(a);
});
addEventListener('mousedown',e=>{
  if(e.target!==canvas||!state.playing||state.paused)return;
  const a=e.button===0?S.mouseLeft:e.button===2?S.mouseRight:'none';
  if(a&&a!=='none')runAction(a);
});
addEventListener('contextmenu',e=>{if(e.target===canvas&&S.mouseRight!=='none')e.preventDefault()});

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
document.getElementById('miniBtn').onclick=toggleMinimap;
document.getElementById('pauseBtn').onclick=togglePause;
document.getElementById('tsplit').onclick=split;
document.getElementById('teject').onclick=eject;
document.getElementById('tdash').onclick=startDash;
document.querySelectorAll('.mode button').forEach(b=>b.onclick=()=>{
  document.querySelectorAll('.mode button').forEach(x=>x.classList.remove('on'));
  b.classList.add('on');state.mode=b.dataset.mode;
});
document.getElementById('arenaChatForm').addEventListener('submit',e=>{
  e.preventDefault();
  const inp=document.getElementById('arenaChatInput');
  const t=inp.value;inp.value='';
  if(t.trim())sendArenaChat(t);
  inp.blur(); /* Invio apre la chat, Invio di nuovo invia e torna subito al gioco */
});
document.querySelectorAll('.chat-emotes [data-emote]').forEach(b=>b.addEventListener('click',()=>{
  const inp=document.getElementById('arenaChatInput');
  const start=inp.selectionStart??inp.value.length,end=inp.selectionEnd??start;
  inp.setRangeText(b.dataset.emote||'',start,end,'end');inp.focus();
}));
document.querySelectorAll('.chat-emotes [data-gif]').forEach(b=>b.addEventListener('click',()=>{
  const inp=document.getElementById('arenaChatInput');
  const u=(prompt('Incolla l\'URL https di una GIF o immagine:')||'').trim();
  if(!/^https:\/\/\S+\.(gif|png|jpe?g|webp)(\?\S*)?$/i.test(u)){toast('URL non valido: serve un link https a una GIF o immagine');return}
  inp.value=(inp.value?inp.value+' ':'')+u;inp.focus();
}));
addEventListener('pagehide',()=>{
  try{navigator.sendBeacon(ARENA_API,new Blob([JSON.stringify({action:'leave'})],{type:'application/json'}))}catch(e){}
});

/* ===== Economia ZeroAgar: Zero Coin, shop, potenziamenti, skin, bot, Zero Pass ===== */
const ECON='zeroagar_economy.php';
async function econ(action,payload,form){
  const o={method:'POST',credentials:'same-origin',headers:{'X-Requested-With':'ZeroAgar'}};
  if(form)o.body=form;else{o.headers['Content-Type']='application/json';o.body=JSON.stringify(payload||{})}
  const ctrl=new AbortController(),tm=setTimeout(()=>ctrl.abort(),12000);o.signal=ctrl.signal;
  try{
    const r=await fetch(ECON+'?action='+encodeURIComponent(action),o);
    const d=await r.json().catch(()=>null);
    if(!d||d.success===false)throw Error((d&&d.error)||('HTTP '+r.status));
    return d;
  }finally{clearTimeout(tm)}
}
const eco=()=>state.eco||{};
const ecoP=()=>eco().player||null;
function upLvl(id){const p=ecoP();return p&&p.upgrades?(+p.upgrades[id]||0):0}
function evMult(type){let m=1;for(const e of eco().events||[])if(e.type===type)m*=Math.max(.1,+e.mult||1);return m}
function speedMul(){return(1+.04*upLvl('speed'))*evMult('speed')}
function durMul(){return 1+.12*upLvl('dur')}
function startMass(){return 25+10*upLvl('start')}
function foodTarget(){return Math.round(700*evMult('food'))}
const SKINS={neon:['#7cffcb','#0aa2ff'],gold:['#fff3b0','#d4a017'],rainbow:['#ff3f58','#f2c94c','#7ed321','#20b7ee','#9b59ff'],lava:['#ffd166','#e63946'],ice:['#e0fbfc','#5fa8d3'],toxic:['#d9ed92','#2d6a4f'],sunset:['#ffd166','#ef476f','#7b2cbf'],galaxy:['#c77dff','#10002b'],aurora:['#a8ff78','#78ffd6','#7f7fd5'],ocean:['#e0f7ff','#0077b6','#03045e'],candy:['#ffafcc','#bde0fe','#ffc8dd'],shadow:['#6c757d','#0b0c10'],fire:['#fff3b0','#ff9f1c','#d00000'],matrix:['#b7ffb7','#00a000','#002b00']};
async function loadEco(){
  try{
    state.eco=await econ('state');
    const inbox=state.eco.inbox||[];if(inbox.length)toast(inbox.join(' · '));
  }catch(e){state.eco=null;state.ecoErr=e.message;console.warn('[ZeroAgar] economia non disponibile',e.message)}
  renderEcoUI();
}
function renderEcoUI(){
  const e=state.eco,p=e&&e.player;
  const ci=document.getElementById('coinInfo');
  if(ci)ci.innerHTML=p?'🪙 <b>'+p.coins+'</b> Zero Coin · 🎟️ Pass '+p.pass.tier+'/30':'';
  const am=document.getElementById('umAdmin');if(am)am.style.display=((p&&p.is_mod)||(!p&&state.loggedIn))?'block':'none';
  const b=document.getElementById('ecoBanner'),h=[];
  for(const ev of (e&&e.events)||[]){
    const x=ev.type==='announce'?'':' · x'+ev.mult+' '+({xp:'XP',coins:'Zero Coin',pass:'punti Pass',food:'cibo',speed:'velocità'}[ev.type]||'');
    h.push('🎉 '+esc(ev.title)+x+(ev.descr?' — '+esc(ev.descr):''));
  }
  if(p&&p.banned)h.push('⛔ Account sospeso'+(p.ban_reason?': '+esc(p.ban_reason):''));
  else if(p&&p.muted_until>Date.now()/1000)h.push('🔇 Sei silenziato in chat');
  if(b){b.style.display=h.length?'block':'none';b.innerHTML=h.join(' &nbsp;|&nbsp; ')}
}
/* ----- skin: gradienti esclusivi o immagini/GIF caricate ----- */
const skinCache={};
/* Skin animate (GIF/WEBP): i frame vengono decodificati una volta e disegnati in base al tempo,
   così l'animazione gira nel canvas in qualsiasi browser che supporti ImageDecoder. */
const animSkins={};
function loadAnimSkin(id){
  const a=animSkins[id]={frames:null,total:0,t0:performance.now(),failed:false};
  (async()=>{
    try{
      if(!('ImageDecoder' in window)||Object.keys(animSkins).length>8)throw 0;
      const r=await fetch(ECON+'?action=skin_file&id='+id,{credentials:'same-origin'});
      const type=(r.headers.get('content-type')||'').split(';')[0].trim();
      if(!r.ok||!/^image\/(gif|webp|png)$/.test(type))throw 0;
      const dec=new ImageDecoder({data:await r.arrayBuffer(),type});
      await dec.tracks.ready;
      const tr=dec.tracks.selectedTrack;
      if(!tr||!tr.animated||tr.frameCount<2)throw 0;
      const n=tr.frameCount,step=Math.max(1,Math.ceil(n/100)),frames=[];
      for(let i=0;i<n;i+=step){
        const {image}=await dec.decode({frameIndex:i});
        const sc=Math.min(1,192/Math.max(image.displayWidth,image.displayHeight));
        const cv=document.createElement('canvas');
        cv.width=Math.max(1,Math.round(image.displayWidth*sc));cv.height=Math.max(1,Math.round(image.displayHeight*sc));
        cv.getContext('2d').drawImage(image,0,0,cv.width,cv.height);
        frames.push({cv,dur:Math.max(20,(image.duration||100000)/1000*step)});
        image.close();
      }
      a.frames=frames;a.total=frames.reduce((x,f)=>x+f.dur,0);a.t0=performance.now();
    }catch(_){a.failed=true}
  })();
  return a;
}
function skinFrame(id){
  if(!/^\d+$/.test(id))return null;
  const a=animSkins[id]||loadAnimSkin(id);
  if(!a.frames)return null;
  const t=(performance.now()-a.t0)%a.total;let acc=0;
  for(const f of a.frames){acc+=f.dur;if(t<acc)return f.cv}
  return a.frames[a.frames.length-1].cv;
}
function skinImg(id){
  if(!/^\d+$/.test(id))return null;
  let i=skinCache[id];
  if(!i){
    i=new Image();i.style.cssText='width:96px;height:96px';i.src=ECON+'?action=skin_file&id='+id;
    let st=document.getElementById('skinStore');
    if(!st){st=document.createElement('div');st.id='skinStore';st.style.cssText='position:fixed;left:0;top:0;width:96px;height:96px;opacity:.02;overflow:hidden;pointer-events:none;z-index:-1';document.body.appendChild(st)}
    st.appendChild(i);skinCache[id]=i;
  }
  return i.complete&&i.naturalWidth?i:null;
}
function drawSkinCell(p,r,color,sk){
  ctx.beginPath();ctx.arc(p.x,p.y,r,0,Math.PI*2);
  const st=sk&&SKINS[sk];
  if(st){const g=ctx.createRadialGradient(p.x-r*.3,p.y-r*.3,r*.1,p.x,p.y,r);st.forEach((c,i)=>g.addColorStop(i/(st.length-1),c));ctx.fillStyle=g}
  else ctx.fillStyle=color;
  ctx.fill();
  if(sk&&sk.indexOf('custom:')===0){
    const im=skinFrame(sk.slice(7))||skinImg(sk.slice(7));
    if(im){ctx.save();ctx.beginPath();ctx.arc(p.x,p.y,r,0,Math.PI*2);ctx.clip();ctx.drawImage(im,p.x-r,p.y-r,r*2,r*2);ctx.restore()}
  }
  ctx.beginPath();ctx.arc(p.x,p.y,r,0,Math.PI*2);ctx.strokeStyle='#fff';ctx.lineWidth=S.borderWidth||3;ctx.stroke();
}
function mySkin(){const p=ecoP();return p?p.equipped:'default'}
async function fetchRemoteSkins(){
  if(!state.eco)return;
  const names=[...new Set(Object.values(state.remotePlayers).map(p=>String(p.username||'')).filter(Boolean))].slice(0,40);
  if(!names.length)return;
  try{const r=await econ('equipped_bulk',{usernames:names});for(const k in r.skins)state.remoteSkins[k]=String(r.skins[k])}catch(_){}
}
function remoteSkin(p){return state.remoteSkins[String(p.username||'').toLowerCase()]||'default'}
/* ----- bot alleati: Raccoglitore e Cacciatore (tutti quelli posseduti entrano in partita) ----- */
function spawnAllies(){
  state.allies=[];const p=ecoP(),c=state.cells[0];if(!p||!c)return;
  const has=id=>(p.owned.bots||[]).includes(id);
  if(has('collector'))state.allies.push({kind:'collector',x:c.x+rand(-80,80),y:c.y+rand(-80,80),r:15,sp:150,a:0,color:'#16b7ef'});
  if(has('hunter'))state.allies.push({kind:'hunter',x:c.x+rand(-80,80),y:c.y+rand(-80,80),r:20,sp:140,a:3,m:60,color:'#ff7a18'});
  if(has('guardian'))state.allies.push({kind:'guardian',x:c.x+rand(-80,80),y:c.y+rand(-80,80),r:22,sp:200,a:0,color:'#9b59ff'});
}
function updateAllies(dt){
  if(!state.allies.length||!state.cells.length)return;
  const lead=state.cells.reduce((a,c)=>c.m>a.m?c:a,state.cells[0]);
  const near=(x,y,R)=>(x-lead.x)**2+(y-lead.y)**2<R*R;
  for(const a of state.allies){
    if(a.kind==='guardian'){
      a.a+=dt*2.4;const gx=lead.x+Math.cos(a.a)*(lead.r+55),gy=lead.y+Math.sin(a.a)*(lead.r+55);
      a.x+=(gx-a.x)*Math.min(1,dt*8);a.y+=(gy-a.y)*Math.min(1,dt*8);
      for(const b of state.bots){if(b.dead)continue;const dd=Math.hypot(b.x-a.x,b.y-a.y);
        if(dd<a.r+b.r+40&&dd>1){b.x=clamp(b.x+(b.x-a.x)/dd*260*dt,b.r,state.world-b.r);b.y=clamp(b.y+(b.y-a.y)/dd*260*dt,b.r,state.world-b.r);if(b.zombie)b.stun=Math.max(b.stun||0,.25)}}
      continue;
    }
    let tx,ty,best=600*600;
    if(a.kind==='hunter'){
      for(const b of state.bots){if(!b.dead&&b.m<a.m*.8&&near(b.x,b.y,1100)){const d=(b.x-a.x)**2+(b.y-a.y)**2;if(d<best){best=d;tx=b.x;ty=b.y}}}
    }else{
      for(const f of state.foods){if(near(f.x,f.y,900)){const d=(f.x-a.x)**2+(f.y-a.y)**2;if(d<best){best=d;tx=f.x;ty=f.y}}}
    }
    if(tx===undefined){a.a+=dt*1.2;tx=lead.x+Math.cos(a.a)*(lead.r+70);ty=lead.y+Math.sin(a.a)*(lead.r+70)}
    const dx=tx-a.x,dy=ty-a.y,d=Math.hypot(dx,dy)||1,s=Math.min(a.sp*1.6,d*4+40);
    a.x=clamp(a.x+dx/d*s*dt,0,state.world);a.y=clamp(a.y+dy/d*s*dt,0,state.world);
    if(a.kind==='collector'){
      for(let i=0;i<state.foods.length;i++){const f=state.foods[i];if(Math.hypot(f.x-a.x,f.y-a.y)<a.r+f.r){lead.m+=f.m*.6;lead.r=massToRadius(lead.m);burst(f.x,f.y,f.c,2,90);state.foods[i]=food()}}
    }else{
      for(const b of state.bots){if(!b.dead&&b.m<a.m*.8&&Math.hypot(b.x-a.x,b.y-a.y)<a.r+b.r){
        lead.m+=b.m*.5;lead.r=massToRadius(lead.m);burst(b.x,b.y,b.color,14,220);addFeed('🤖 Il tuo Cacciatore ha divorato '+b.name);
        reviveBot(b);}}
    }
  }
}
function drawAllies(){
  const z=state.camera.zoom;
  for(const a of state.allies){
    const p=screenPoint(a.x,a.y);ctx.beginPath();ctx.arc(p.x,p.y,a.r*z,0,Math.PI*2);
    ctx.fillStyle=a.color;ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=2;ctx.stroke();
    ctx.fillStyle='#fff';ctx.font=Math.max(9,a.r*z)+'px Arial';ctx.textAlign='center';ctx.fillText(a.kind==='hunter'?'🎯':a.kind==='guardian'?'🛡️':'🤖',p.x,p.y+a.r*z*.35);
  }
}
/* potenziamento "Magnete passivo": attira il cibo vicino anche senza il bonus */
function passiveMagnet(dt){
  const l=upLvl('magnet');if(!l||state.fx.magnet>0)return;
  const R=60+45*l;
  for(const f of state.foods)for(const c of state.cells){
    const dx=c.x-f.x,dy=c.y-f.y,d=Math.hypot(dx,dy);
    if(d<c.r+R&&d>1){const s=Math.min(d,140*dt);f.x+=dx/d*s;f.y+=dy/d*s;break}
  }
}
/* ricompensa a fine partita (il server decide le quantità) */
async function endGameReward(leaving){
  if(state.rewarded||!state.eco||(ecoP()&&ecoP().banned))return;
  state.rewarded=true;
  const body={score:Math.floor(state.score),kills:state.kills,food:state.foodEaten,seconds:Math.floor(state.runTime),won:state.won?1:0};
  if(leaving){try{fetch(ECON+'?action=game_end',{method:'POST',keepalive:true,credentials:'same-origin',headers:{'X-Requested-With':'ZeroAgar','Content-Type':'application/json'},body:JSON.stringify(body)})}catch(_){}return}
  try{
    const r=await econ('game_end',body),w=r.reward||{};
    state.eco.player=r.player;renderEcoUI();
    const ds=document.getElementById('deathStats');
    if(ds&&(w.coins||w.xp))ds.textContent+=' · 🪙 +'+w.coins+' Zero Coin · ⭐ +'+w.xp+' XP · 🎟️ +'+w.pass+' Pass'+(w.levelUp?' · 🆙 LIVELLO SU!':'');
  }catch(e){console.warn('[ZeroAgar] ricompensa non assegnata',e.message)}
}
/* chat: controlli lato client (la moderazione vera va applicata anche dal server della chat) */
function chatAllowed(text){
  const p=ecoP(),cfg=eco().cfg||{};
  if(cfg.chat_enabled==='0'){toast('La chat è stata disattivata dagli admin');return false}
  if(p&&p.banned){toast('Account sospeso');return false}
  if(p&&p.muted_until>Date.now()/1000){toast('Sei silenziato: non puoi scrivere in chat');return false}
  const slow=(+cfg.chat_slowmode||0)*1000,t=Date.now();
  if(slow&&t-(chatAllowed.last||0)<slow){toast('Slowmode: aspetta '+Math.ceil((slow-(t-chatAllowed.last))/1000)+'s');return false}
  const bad=String(cfg.chat_blocked_words||'').split(',').map(s=>s.trim().toLowerCase()).filter(Boolean);
  if(bad.some(w=>text.toLowerCase().includes(w))){toast('Messaggio bloccato dal filtro');return false}
  chatAllowed.last=t;return true;
}
/* ----- finestre: shop, collezione, pass ----- */
let shopTab=0;
const refreshSheet=()=>loadEco().then(renderSheet);
function footClose(){const f=el('div','sh-foot'),ok=el('button','green','Chiudi');ok.onclick=closeSheet;f.appendChild(ok);sheet.appendChild(f)}
function needLogin(body){
  if(state.loggedIn){
    body.appendChild(el('div','help','Economia non raggiungibile: '+(state.ecoErr||'?')));
    const b=el('button','blue','Riprova');b.style.cssText='width:100%;margin:8px 0';b.onclick=refreshSheet;body.appendChild(b);
  }else body.appendChild(el('div','help','Accedi dal portale per usare shop, skin e Zero Pass.'));
}
function skinDot(id){
  const st=SKINS[id],s=el('span');
  s.style.cssText='display:inline-block;width:20px;height:20px;border-radius:50%;margin-right:8px;vertical-align:middle;background:'+(st?'radial-gradient(circle at 30% 30%,'+st.join(',')+')':'#1db7ef');return s;
}
function buyRow(type,id,name,desc,price,done,doneLabel,extra){
  const row=el('div','opt'),lab=el('div');
  if(type==='skin')lab.appendChild(skinDot(id));
  lab.appendChild(document.createTextNode(name+(extra||'')));if(desc)lab.appendChild(el('small','',desc));row.appendChild(lab);
  const b=el('button',done?'gray':'blue',done?doneLabel:'🪙 '+price);b.disabled=done;
  b.onclick=async()=>{b.disabled=true;try{const r=await econ('buy',{type,id});state.eco.player=r.player;state.eco.cat=r.cat;beep('pickup');toast('Acquisto riuscito');renderEcoUI();renderSheet()}catch(err){toast(err.message);b.disabled=false}};
  row.appendChild(b);return row;
}
function buildPacks(body){
  const e=eco(),mode=e.cfg&&e.cfg.payments_mode;
  if(mode==='off')body.appendChild(el('div','help','Gli acquisti di Zero Coin non sono ancora attivi: intanto guadagnali giocando!'));
  for(const id in (e.cat&&e.cat.packs)||{}){
    const k=e.cat.packs[id],row=el('div','opt'),lab=el('div');
    lab.appendChild(document.createTextNode('🪙 '+k.coins+' Zero Coin · '+k.label));lab.appendChild(el('small','','€ '+(k.cents/100).toFixed(2)+(mode==='demo'?' (modalità demo: gratis)':'')));row.appendChild(lab);
    const b=el('button','green','Acquista');b.disabled=mode==='off';
    b.onclick=async()=>{b.disabled=true;try{const r=await econ('buy_coins',{pack:id});
      if(r.url){window.open(r.url,'_blank','noopener');toast('Completa il pagamento nella nuova scheda')}
      else{state.eco.player=r.player;renderEcoUI();toast('Zero Coin aggiunti!');beep('pickup');renderSheet()}}catch(err){toast(err.message)}b.disabled=false};
    row.appendChild(b);body.appendChild(row);
  }
  if(mode==='external')body.appendChild(el('div','help','Dopo il pagamento i coin arrivano in pochi secondi: usa "Aggiorna account" dal menu.'));
}
function renderShop(){
  const e=eco(),p=ecoP();sheetHead('🛒 Shop · 🪙 '+(p?p.coins:0));
  const tabs=el('div','sh-tabs');
  ['Potenziamenti','Skin','Bot','Zero Coin'].forEach((n,i)=>{const b=el('button',i===shopTab?'on':'',n);b.onclick=()=>{shopTab=i;renderShop()};tabs.appendChild(b)});
  sheet.appendChild(tabs);
  const body=el('div','sh-body');
  if(!p)needLogin(body);
  else{
    if(+((e.cfg||{}).shop_discount)>0)body.appendChild(el('div','help','🏷️ Sconto attivo: -'+e.cfg.shop_discount+'%'));
    const c=e.cat||{};
    if(shopTab===0)for(const id in c.upgrades){const u=c.upgrades[id];body.appendChild(buyRow('upgrade',id,u.name,u.desc,u.next,u.next===null,'MAX',' · Lv '+u.level+'/'+u.max))}
    else if(shopTab===1)for(const id in c.skins){const s=c.skins[id],own=p.owned.skins.includes(id);
      body.appendChild(s.pass&&!own?(()=>{const r=el('div','opt'),l=el('div');l.appendChild(skinDot(id));l.appendChild(document.createTextNode(s.name));l.appendChild(el('small','','Esclusiva Zero Pass (livello 30 Premium)'));r.appendChild(l);r.appendChild(el('span','badge','PASS'));return r})():buyRow('skin',id,s.name,'',s.price,own,'Posseduta'))}
    else if(shopTab===2)for(const id in c.bots){const b=c.bots[id];body.appendChild(buyRow('bot',id,b.name,b.desc,b.price,p.owned.bots.includes(id),'Posseduto'))}
    else buildPacks(body);
  }
  sheet.appendChild(body);footClose();
}
function renderCollection(){
  const e=eco(),p=ecoP();sheetHead('🎨 Collezione');const body=el('div','sh-body');
  if(!p){needLogin(body);sheet.appendChild(body);footClose();return}
  const h=t=>{const d=el('div','',t);d.style.cssText='font-weight:800;font-size:12px;margin:14px 0 2px;text-transform:uppercase;color:#13b8ef';return d};
  const equip=async(skin)=>{try{const r=await econ('equip',{skin});state.eco.player=r.player;renderEcoUI();renderSheet()}catch(err){toast(err.message)}};
  const row=(label,id,dot)=>{
    const r=el('div','opt'),l=el('div');if(dot)l.appendChild(dot);l.appendChild(document.createTextNode(label));r.appendChild(l);
    const cur=p.equipped===id,b=el('button',cur?'gray':'blue',cur?'In uso':'Equipaggia');b.disabled=cur;b.onclick=()=>equip(id);r.appendChild(b);return r;
  };
  body.appendChild(h('Skin'));
  body.appendChild(row('Predefinita (colore profilo)','default'));
  for(const id of p.owned.skins)body.appendChild(row((e.cat.skins[id]||{}).name||id,id,skinDot(id)));
  for(const s of e.custom||[]){
    const r=el('div','opt'),l=el('div');
    const img=document.createElement('img');img.src=ECON+'?action=skin_file&id='+s.id;img.style.cssText='width:34px;height:34px;border-radius:50%;object-fit:cover;vertical-align:middle;margin-right:8px;background:#ddd';l.appendChild(img);
    l.appendChild(document.createTextNode(s.name));l.appendChild(el('small','',({approved:'Approvata',pending:'In attesa di approvazione (visibile solo a te)',rejected:'Rifiutata'}[s.status]||s.status)+' · '+s.w+'×'+s.h));r.appendChild(l);
    const box=el('div');box.style.cssText='display:flex;gap:6px';
    if(s.status!=='rejected'){const id='custom:'+s.id,cur=p.equipped===id,b=el('button',cur?'gray':'blue',cur?'In uso':'Usa');b.disabled=cur;b.onclick=()=>equip(id);box.appendChild(b)}
    const d=el('button','gray','🗑');d.onclick=async()=>{if(!confirm('Eliminare questa skin?'))return;try{const x=await econ('delete_skin',{id:s.id});state.eco.player=x.player;state.eco.custom=x.custom;renderSheet()}catch(err){toast(err.message)}};
    box.appendChild(d);r.appendChild(box);body.appendChild(r);
  }
  const cfg=e.cfg||{};
  if(cfg.skin_upload_enabled==='1'){
    const up=el('div','opt'),col=el('div');col.style.width='100%';
    col.appendChild(el('div','','⬆️ Carica una skin personalizzata'));
    col.appendChild(el('small','','PNG, JPG, GIF animata o WEBP · qualsiasi dimensione · max '+(+cfg.skin_max_kb>=1024?Math.round(cfg.skin_max_kb/1024)+' MB':cfg.skin_max_kb+' KB')+' · fino a '+cfg.skin_max_per_user+' skin'+(cfg.skin_auto_approve==='1'?'':' · approvata da un moderatore')));
    const fi=document.createElement('input');fi.type='file';fi.accept='image/png,image/jpeg,image/gif,image/webp';fi.style.cssText='display:block;margin:8px 0;max-width:100%';
    const nm=document.createElement('input');nm.maxLength=24;nm.placeholder='Nome skin';nm.style.cssText='width:100%;padding:8px;border:1px solid #bbb;border-radius:6px;margin-bottom:8px';
    const go=el('button','green','Carica');go.style.width='100%';
    go.onclick=async()=>{
      if(!fi.files[0]){toast('Scegli un file');return}
      const fd=new FormData();fd.append('file',fi.files[0]);fd.append('name',nm.value);go.disabled=true;
      try{const r=await econ('upload_skin',undefined,fd);state.eco.custom=r.custom;await loadEco();const want=(r.custom||[]).length,have=(state.eco.custom||[]).length;if(have<want)toast('⚠️ La skin non risulta salvata sul server: serve controllare economia');else toast(r.status==='approved'?'Skin caricata e approvata':'Skin caricata: in attesa di approvazione');renderSheet()}
      catch(err){toast(err.message);go.disabled=false}
    };
    col.appendChild(fi);col.appendChild(nm);col.appendChild(go);up.appendChild(col);body.appendChild(up);
  }else body.appendChild(el('div','help','Gli upload di skin sono momentaneamente disattivati.'));
  body.appendChild(h('Bot alleati'));
  const c=e.cat.bots;let any=false;
  for(const id of p.owned.bots){any=true;body.appendChild(el('div','opt',(c[id]||{}).name+' — '+((c[id]||{}).desc||'')+' (entra in ogni partita)'))}
  if(!any)body.appendChild(el('div','help','Compra un bot nello Shop: scenderà in campo con te a ogni partita.'));
  body.appendChild(el('div','help','Le modifiche si vedono subito; i bot entrano dalla prossima partita.'));
  sheet.appendChild(body);footClose();
}
function renderPass(){
  const e=eco(),p=ecoP(),pc=e.cat&&e.cat.pass;sheetHead('🎟️ Zero Pass'+(pc?' · '+pc.season:''));const body=el('div','sh-body');
  if(!p||!pc){needLogin(body);sheet.appendChild(body);footClose();return}
  const inT=p.pass.tier>=30?100:Math.floor((p.pass.points%pc.step)/pc.step*100);
  body.innerHTML='<div style="margin:8px 0;font-weight:800">Livello '+p.pass.tier+' / 30 · '+p.pass.points+' punti · 🪙 '+p.coins+'</div><div class="xp"><i style="width:'+inT+'%"></i></div><div class="help">Ogni partita (almeno 15 secondi) assegna punti Pass.'+(pc.ends?' Fine stagione: '+esc(pc.ends):'')+'</div>';
  if(!p.pass.premium){
    const b=el('button','green','👑 Sblocca Premium · 🪙 '+pc.premium_price);b.style.cssText='width:100%;margin:8px 0';
    b.onclick=async()=>{try{const r=await econ('pass_buy',{});state.eco.player=r.player;renderEcoUI();toast('Zero Pass Premium sbloccato!');beep('pickup');renderSheet()}catch(err){toast(err.message)}};body.appendChild(b);
  }else body.appendChild(el('div','help','👑 Zero Pass Premium attivo'));
  for(const t in pc.tiers){
    const T=+t,r=pc.tiers[t],open=p.pass.tier>=T,row=el('div','opt');row.appendChild(el('div','','Lv '+T));
    const mk=(track,label)=>{
      const got=(track==='prem'?p.pass.prem:p.pass.free).includes(T),ok=open&&(track==='free'||p.pass.premium)&&!got;
      const b=el('button',got?'gray':ok?'blue':'gray',(got?'✔ ':track==='prem'?'👑 ':'')+label);b.disabled=!ok;b.style.fontSize='12px';
      b.onclick=async()=>{try{const x=await econ('pass_claim',{tier:T,track});state.eco.player=x.player;state.eco.cat=x.cat;renderEcoUI();toast('Ricevuto: '+x.label);beep('pickup');renderSheet()}catch(err){toast(err.message)}};return b;
    };
    const box=el('div');box.style.cssText='display:flex;gap:6px;flex-wrap:wrap;justify-content:flex-end';box.appendChild(mk('free',r.free.label));box.appendChild(mk('prem',r.prem.label));
    row.appendChild(box);body.appendChild(row);
  }
  sheet.appendChild(body);footClose();
}
document.getElementById('shopBtn').onclick=()=>openSheet('shop');
document.getElementById('passBtn').onclick=()=>openSheet('pass');
document.getElementById('skinsBtn').onclick=()=>openSheet('skins');

applySettings();
loadAccount();
})();
</script>
<script src="zeroagar_i18n.js" defer></script>
<script src="/assets/js/global-arena-chat.js" defer></script>
</body>
</html>
