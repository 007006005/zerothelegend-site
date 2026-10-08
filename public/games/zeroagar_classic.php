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
#mini{position:absolute;left:10px;bottom:max(10px,env(safe-area-inset-bottom));width:150px;height:150px;border-radius:8px;background:rgba(255,255,255,.92);border:1px solid #aaa;display:none;z-index:8}.mini-label{position:absolute;left:6px;top:4px;font-size:9px;color:#666;font-weight:700;z-index:1}
#menu{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:min(900px,95vw);display:grid;grid-template-columns:245px 1fr 245px;gap:8px;z-index:20}.box{padding:14px}.logo{text-align:center;font-size:46px;font-weight:800;letter-spacing:-3px;margin:6px 0 14px}.logo span{color:#19b5f1}
#nick{width:100%;padding:12px;border:1px solid #bbb;border-radius:5px;font-size:16px}button{border:0;border-radius:5px;padding:10px 13px;font-weight:700;cursor:pointer}button:active{transform:translateY(1px)}
#play{width:100%;margin-top:10px;background:#58d100;color:#fff;font-size:20px}.mode{display:grid;grid-template-columns:1fr 1fr;gap:7px;margin-top:10px}.mode button{background:#f2f3f4;border:1px solid #ddd}.mode button.on{background:#ff3f58;color:#fff}.help{text-align:center;color:#777;font-size:12px;line-height:1.5;margin-top:10px}.action{width:100%;background:#13b8ef;color:#fff;margin:4px 0}.action.gray{background:#707780}
#bottom{position:absolute;left:50%;bottom:15px;transform:translateX(-50%);display:none;gap:7px;z-index:10}.bottom-btn{min-width:90px;background:rgba(255,255,255,.96)}
#back{position:absolute;left:10px;bottom:15px;background:rgba(255,255,255,.96);display:none;z-index:10}.game-state{position:absolute;left:50%;bottom:70px;transform:translateX(-50%);background:rgba(0,0,0,.63);color:#fff;padding:7px 12px;border-radius:18px;font-size:12px;display:none;z-index:10}.toast{position:absolute;left:50%;top:16px;transform:translateX(-50%);background:rgba(0,0,0,.74);color:#fff;padding:8px 14px;border-radius:18px;font-size:12px;display:none;z-index:30}
#death{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:min(430px,90vw);padding:18px;display:none;z-index:25;text-align:center}.deathScore{font-size:32px;font-weight:800}.deathActions{display:flex;gap:8px;margin-top:12px}.deathActions button{flex:1}.green{background:#59d414;color:#fff}.blue{background:#16b7ef;color:#fff}.gray{background:#6c7178;color:#fff}
#touch{position:absolute;right:14px;bottom:72px;display:none;pointer-events:auto;z-index:12}.touchbtn{width:62px;height:62px;border-radius:50%;background:rgba(255,255,255,.88);margin:4px;font-size:10px}
#tips{position:absolute;left:50%;top:92px;transform:translateX(-50%);padding:5px 10px;background:rgba(255,255,255,.83);border:1px solid rgba(0,0,0,.08);border-radius:14px;font-size:10px;display:none;z-index:7}
@media(max-width:900px){#menu{grid-template-columns:1fr;max-width:430px}#menu .side{display:none}#leader{width:205px}#profile{width:235px}.logo{font-size:38px}.help{font-size:11px}}
#arenaChat{position:absolute;right:10px;bottom:15px;width:min(340px,92vw);z-index:14;pointer-events:auto}.chat-head{padding:8px 10px;background:#222d3b;color:#fff;border-radius:10px 10px 0 0;font-size:12px;font-weight:700;display:flex;justify-content:space-between}.chat-count{font-weight:500;opacity:.75}.chat-body{height:190px;overflow:auto;background:rgba(255,255,255,.95);border-left:1px solid rgba(0,0,0,.12);border-right:1px solid rgba(0,0,0,.12);padding:7px}.chat-msg{font-size:12px;line-height:1.35;margin:4px 0}.chat-msg b{color:#0876d1}.chat-form{display:flex;background:#fff;border:1px solid rgba(0,0,0,.12);border-radius:0 0 10px 10px;overflow:hidden}.chat-form input{flex:1;border:0;padding:9px;font-size:12px;outline:none}.chat-form button{border-radius:0;background:#16b7ef;color:#fff;padding:9px 12px;font-size:12px}
@media(pointer:coarse){#touch{display:block}}
</style>
</head>
<body>
<div id="game"><canvas id="canvas"></canvas></div>
<div id="hud">
  <div id="topbar">
    <div id="profile" class="panel">
      <div id="avatar" class="avatar"></div>
      <div id="playerName">Caricamento…</div><span id="role" class="badge">USER</span>
      <div class="xp"><i id="xpbar"></i></div>
      <div id="meta" class="meta">LV1 · 0 XP · Record 0</div>
    </div>
    <div id="leader" class="panel"><div class="leaderTitle">Classifica</div><div id="leaders"></div></div>
  </div>
  <div id="tips">Mouse / dito = movimento · SPACE = split · W = espelli massa · rotellina = zoom · M = minimappa</div>
  <canvas id="mini" width="150" height="150"></canvas>
  <div id="menu">
    <div class="box panel side">
      <h3>Account</h3>
      <p id="loginInfo" class="help" style="text-align:left">Controllo sessione…</p>
      <button class="action" id="refreshProfile">AGGIORNA</button>
      <button class="action" id="saveNow">SALVA PROGRESSI</button>
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
  </div>
  <div id="touch"><button class="touchbtn" id="tsplit">SPLIT</button><button class="touchbtn" id="teject">MASSA</button></div>
  <div id="gameState" class="game-state"></div><div id="toast" class="toast"></div>
  <div id="arenaChat" class="panel" style="display:none">
    <div class="chat-head"><span>💬 CHAT ARENA</span><span class="chat-count" id="arenaOnline">0 online</span></div>
    <div class="chat-body" id="arenaChatBody"></div>
    <form class="chat-form" id="arenaChatForm"><input id="arenaChatInput" maxlength="200" autocomplete="off" placeholder="Scrivi nella chat della stessa arena…"><button type="submit">INVIA</button></form>
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
const canvas=document.getElementById('canvas'),ctx=canvas.getContext('2d');
const mini=document.getElementById('mini'),mctx=mini.getContext('2d');
let W=innerWidth,H=innerHeight,D=Math.min(devicePixelRatio||1,2);
function resize(){W=innerWidth;H=innerHeight;canvas.width=Math.floor(W*D);canvas.height=Math.floor(H*D);ctx.setTransform(D,0,0,D,0,0)}addEventListener('resize',resize);resize();
const palette=['#ff3f58','#20b7ee','#f2c94c','#7ed321','#9b59ff','#ff7a18','#00c7a7','#e85dff'];
const TEAM_COLORS=['#2d7ff9','#e14cff','#ff9a2f'];
const names=['Vortex','Nucleus','Blobby','Helix','Nebula','Rogue','Photon','Mitosis','Quark','Zephyr','Titan','Krill','Hydra','Plasma','Onyx','Cygnus','Pyxis','Aphid','Spore','Flux'];
const state={
 mode:'ffa',playing:false,spectating:false,online:false,loggedIn:false,profile:null,nick:'ZeroPlayer',world:6000,
 foods:[],ejected:[],viruses:[],bots:[],cells:[],camera:{x:3000,y:3000,zoom:1},mouse:{x:W/2,y:H/2},
 score:0,best:0,plays:0,kills:0,foodEaten:0,time:0,lastFrame:0,lastSave:0,leaderTick:0,minimap:true,partyCode:'',
 remotePlayers:{},arenaJoined:false,arenaSyncBusy:false,arenaUpdateBusy:false,chatSince:0,arenaTimer:null,arenaUpdateTimer:null,
 spawn:{x:3000,y:3000},paused:false,gameOver:false,spectateIndex:0
};
function esc(s){return String(s??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m]))}
async function api(action,payload){const o={credentials:'same-origin',headers:{}};if(payload!==undefined){o.method='POST';o.headers['Content-Type']='application/json';o.body=JSON.stringify(payload)}const r=await fetch(API+'?action='+encodeURIComponent(action),o);if(!r.ok)throw Error('HTTP '+r.status);return r.json()}
function toast(s){const e=document.getElementById('toast');e.textContent=s;e.style.display='block';clearTimeout(toast.t);toast.t=setTimeout(()=>e.style.display='none',1800)}
function radToMass(r){return Math.max(10,r*r)}
function massToRadius(m){return Math.max(10,Math.sqrt(Math.max(10,m)))}
function rand(a,b){return a+Math.random()*(b-a)}
function d2(a,b){return Math.hypot(a.x-b.x,a.y-b.y)}
function clamp(v,a,b){return Math.max(a,Math.min(b,v))}
function now(){return performance.now()}
function playerSkinColor(){return state.profile?.profile?.skin_color||state.profile?.equipped?.cell_skin||'#1db7ef'}
function applyProfileUI(){const p=state.profile;if(!p)return;document.getElementById('playerName').textContent=state.nick;document.getElementById('role').textContent=(p.role||'user').toUpperCase();document.getElementById('meta').textContent=`LV${p.level||1} · ${p.xp||0} XP · Record ${Math.floor(state.best)}`;document.getElementById('xpbar').style.width=Math.min(100,(Number(p.xp||0)%1000)/10)+'%';const av=document.getElementById('avatar');av.classList.toggle('skin',!!p.equipped?.cell_skin);if(p.equipped?.cell_skin)av.style.background=p.equipped.cell_skin}
async function loadAccount(){try{const r=await api('me');if(!r?.profile)throw Error('login_required');state.online=true;state.loggedIn=true;state.profile=r.profile;state.nick=r.profile.name||r.profile.username||'ZeroPlayer';try{const g=await api('game_progress',{game:'zeroagar_classic'});if(g?.progress){state.best=+g.progress.best||0;state.plays=+g.progress.plays||0;state.time=+g.progress.total||0}}catch(_){ }document.getElementById('loginInfo').innerHTML='<b>Account collegato</b><br>'+esc(state.nick)+'<br>Progressi salvati nel database.';document.getElementById('nick').value=state.nick;applyProfileUI();await refreshLeaderboard()}catch(e){state.online=false;state.loggedIn=false;state.profile=null;document.getElementById('loginInfo').innerHTML='Sessione non disponibile.';document.getElementById('nick').value=state.nick}updateLeaders()}
async function refreshLeaderboard(){try{const r=await api('game_leaderboard',{game:'zeroagar_classic',limit:10});if(r?.leaders)state.serverLeaders=r.leaders}catch(_){}}
async function saveProgress(){if(!state.online||!state.loggedIn)return;try{await api('game_save',{game:'zeroagar_classic',score:Math.floor(state.score),data:{best:Math.floor(state.best),plays:state.plays,total:Math.floor(state.time),kills:state.kills,particles:state.foodEaten,mode:state.mode}});state.lastSave=Date.now()}catch(e){console.warn(e)}}
function updateLeaders(){const real=Array.isArray(state.serverLeaders)?state.serverLeaders:[];let vals=real.slice();const mine={username:state.nick||'Tu',score:Math.floor(state.score),me:true};vals=vals.filter(v=>String(v.username||'').toLowerCase()!==String(state.nick||'').toLowerCase());vals.push(mine);vals.sort((a,b)=>(+b.score||0)-(+a.score||0));document.getElementById('leaders').innerHTML=vals.slice(0,10).map((v,i)=>`<div class="row ${v.me?'me':''}"><span>${i+1}. ${esc(v.username||'')}</span><b>${Math.floor(+v.score||0)}</b></div>`).join('')}
function food(){return{x:rand(50,state.world-50),y:rand(50,state.world-50),r:rand(3,6),m:5,c:palette[(Math.random()*palette.length)|0]}}
function virus(){return{x:rand(500,state.world-500),y:rand(500,state.world-500),r:48,m:100,spin:rand(0,Math.PI*2)}}
function createBot(i){const team=state.mode==='ffa'?null:i%3;const m=rand(40,240);return{x:rand(300,state.world-300),y:rand(300,state.world-300),r:massToRadius(m),m,vx:rand(-35,35),vy:rand(-35,35),name:names[i%names.length],team,angle:rand(0,Math.PI*2),think:0,color:team==null?palette[i%palette.length]:TEAM_COLORS[team],alive:true}}
function resetWorld(){state.foods=[];state.ejected=[];state.viruses=[];state.bots=[];for(let i=0;i<700;i++)state.foods.push(food());const vc=state.mode==='experimental'?14:10;for(let i=0;i<vc;i++)state.viruses.push(virus());const bc=state.mode==='teams'?24:18;for(let i=0;i<bc;i++)state.bots.push(createBot(i));if(state.mode==='party'){state.partyCode='ZA-'+Math.random().toString(36).slice(2,7).toUpperCase();toast('Party code: '+state.partyCode)}}
function makeCell(x,y,m,color,name,team=null,player=false){return{x,y,m,r:massToRadius(m),vx:0,vy:0,merge:0,color,name,team,player}}
const ARENA_API='../api/arena_live.php';
async function arenaCall(action,payload={}){const r=await fetch(ARENA_API,{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json'},body:JSON.stringify(Object.assign({action,arena:'ZeroArcade:global',game:'zeroagar_classic'},payload))});if(!r.ok)throw Error('Arena HTTP '+r.status);return r.json()}
function arenaCellsPayload(){return state.cells.map(c=>({x:c.x,y:c.y,m:c.m,color:c.color,team:c.team}))}
function updateRemotePlayers(players){const map={};for(const p of (players||[])){if(!p.self&&p.alive)map[p.id]=p}state.remotePlayers=map;const el=document.getElementById('arenaOnline');if(el)el.textContent=((players||[]).length||0)+' online'}
function renderArenaChat(messages){const box=document.getElementById('arenaChatBody');if(!box||!Array.isArray(messages))return;for(const m of messages){if(document.getElementById('am-'+m.id))continue;const row=document.createElement('div');row.className='chat-msg';row.id='am-'+m.id;row.innerHTML='<b>'+esc(m.username)+'</b>: '+esc(m.text);box.appendChild(row)}while(box.children.length>100)box.removeChild(box.firstChild);box.scrollTop=box.scrollHeight}
async function arenaJoin(){if(!state.online||!state.loggedIn||state.arenaJoined)return;try{const r=await arenaCall('join',{color:playerSkinColor(),team:state.cells[0]?.team??null,score:Math.floor(state.score),cells:arenaCellsPayload()});if(r?.success){state.arenaJoined=true;updateRemotePlayers(r.players||[]);const chat=document.getElementById('arenaChat');if(chat)chat.style.display='block';await arenaSync(true)}}catch(e){console.warn('[ZeroAgar] arena join failed',e)}}
async function arenaUpdate(){if(!state.arenaJoined||!state.playing||state.arenaUpdateBusy)return;state.arenaUpdateBusy=true;try{const r=await arenaCall('update',{color:playerSkinColor(),team:state.cells[0]?.team??null,score:Math.floor(state.score),cells:arenaCellsPayload()});if(r?.dead){state.arenaJoined=false;die('Sei stato divorato da un giocatore online');return}if(Number.isFinite(Number(r?.server_score)))state.score=Math.max(state.score,Number(r.server_score));updateRemotePlayers(r.players||[])}catch(e){}finally{state.arenaUpdateBusy=false}}
async function arenaSync(force=false){if(!state.arenaJoined||state.arenaSyncBusy)return;state.arenaSyncBusy=true;try{const r=await arenaCall('sync',{since:force?0:state.chatSince});if(r?.success){updateRemotePlayers(r.players||[]);renderArenaChat(r.messages||[]);if(r.last_chat)state.chatSince=r.last_chat}}catch(e){}finally{state.arenaSyncBusy=false}}
async function arenaLeave(){if(!state.arenaJoined)return;state.arenaJoined=false;try{await arenaCall('leave',{})}catch(e){}const chat=document.getElementById('arenaChat');if(chat)chat.style.display='none'}
async function sendArenaChat(text){text=String(text||'').trim();if(!text||!state.arenaJoined)return;try{const r=await arenaCall('chat',{text});if(r?.success)await arenaSync(true)}catch(e){toast(e?.message||'Chat non disponibile')}}
function startArenaTimers(){clearInterval(state.arenaTimer);clearInterval(state.arenaUpdateTimer);state.arenaTimer=setInterval(()=>arenaSync(),350);state.arenaUpdateTimer=setInterval(()=>arenaUpdate(),120)}
function drawRemotePlayers(){const z=state.camera.zoom;for(const p of Object.values(state.remotePlayers)){for(const c of (p.cells||[])){const q=screenPoint(c.x,c.y);const r=Math.max(5,Number(c.r||massToRadius(c.m||10)))*z;ctx.beginPath();ctx.arc(q.x,q.y,r,0,Math.PI*2);ctx.fillStyle=c.color||p.color||'#ef476f';ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=3;ctx.stroke();if(r>22){ctx.fillStyle='#fff';ctx.font='bold 13px Arial';ctx.textAlign='center';ctx.fillText(p.username,q.x,q.y+4)}}}}
function startGame(){state.nick=(state.profile?.username||state.profile?.name||document.getElementById('nick').value||'ZeroPlayer').trim().slice(0,20)||'ZeroPlayer';state.playing=true;state.spectating=false;state.gameOver=false;state.paused=false;state.score=25;state.kills=0;state.foodEaten=0;state.spawn={x:rand(1300,4700),y:rand(1300,4700)};state.camera={x:state.spawn.x,y:state.spawn.y,zoom:1};state.cells=[makeCell(state.spawn.x,state.spawn.y,25,playerSkinColor(),state.nick,state.mode==='teams'?Math.floor(Math.random()*3):null,true)];resetWorld();document.getElementById('menu').style.display='none';document.getElementById('death').style.display='none';document.getElementById('bottom').style.display='flex';document.getElementById('back').style.display='block';document.getElementById('tips').style.display='block';state.lastFrame=now();state.lastSave=Date.now();requestAnimationFrame(loop);arenaJoin();startArenaTimers();toast('Partita iniziata · '+state.mode.toUpperCase())}
function respawn(){startGame()}
function die(reason='Divorato'){state.playing=false;state.gameOver=true;state.plays++;state.best=Math.max(state.best,state.score);state.time+=Math.max(0,Math.floor((now()-state.lastFrame)/1000));document.getElementById('deathScore').textContent=Math.floor(state.score);document.getElementById('deathStats').textContent=`Record ${Math.floor(state.best)} · Kill ${state.kills} · Cibo ${state.foodEaten} · ${reason}`;document.getElementById('death').style.display='block';document.getElementById('bottom').style.display='none';document.getElementById('tips').style.display='none';arenaLeave();saveProgress();refreshLeaderboard();updateLeaders()}
function eatCell(big,small){const gain=small.m;big.m+=gain;big.r=massToRadius(big.m);if(small.player){state.kills++;die('Sei stato divorato');return true}return false}
function canEat(a,b){return a.m>b.m*1.10 && a.r>b.r*1.08}
function split(){if(!state.playing||state.paused||state.cells.length>=16)return;const additions=[];for(const c of state.cells.slice()){if(c.m<36||state.cells.length+additions.length>=16)continue;const ang=Math.atan2(state.mouse.y-H/2,state.mouse.x-W/2);const half=c.m/2;c.m=half;c.r=massToRadius(half);c.merge=10;additions.push(Object.assign({},c,{x:c.x+Math.cos(ang)*(c.r+18),y:c.y+Math.sin(ang)*(c.r+18),m:half,r:massToRadius(half),vx:Math.cos(ang)*650,vy:Math.sin(ang)*650,merge:10,player:true}))}if(additions.length){state.cells.push(...additions);toast('SPLIT')}}
function eject(){if(!state.playing||state.paused)return;const ang=Math.atan2(state.mouse.y-H/2,state.mouse.x-W/2);for(const c of state.cells){if(c.m<36)continue;c.m-=12;c.r=massToRadius(c.m);state.ejected.push({x:c.x+Math.cos(ang)*(c.r+9),y:c.y+Math.sin(ang)*(c.r+9),vx:Math.cos(ang)*700+c.vx,vy:Math.sin(ang)*700+c.vy,m:12,r:massToRadius(12),life:8,color:c.color})} }
function handleVirusHit(c,v){if(c.m<100)return false;const n=Math.min(6,Math.floor(c.m/90));c.m*=0.9;c.r=massToRadius(c.m);const ang=Math.atan2(c.y-v.y,c.x-v.x);for(let i=0;i<n&&state.cells.length<16;i++){const a=ang+(i-(n-1)/2)*0.22;state.cells.push(makeCell(c.x+Math.cos(a)*45,c.y+Math.sin(a)*45,Math.max(20,c.m/n*0.85),c.color,c.name,c.team,true));state.cells[state.cells.length-1].vx=Math.cos(a)*420;state.cells[state.cells.length-1].vy=Math.sin(a)*420}v.x=rand(500,state.world-500);v.y=rand(500,state.world-500);toast('VIRUS!')}
function updateBots(dt){for(const b of state.bots){if(!b.alive)continue;b.think-=dt;if(b.think<=0){b.think=rand(.25,1.4);let target=null,best=1e9;
for(const f of state.foods){const d=(f.x-b.x)*(f.x-b.x)+(f.y-b.y)*(f.y-b.y);if(d<best){best=d;target=f}}for(const c of state.cells){const d=Math.hypot(c.x-b.x,c.y-b.y);if(canEat(b,c)&&d<800){target=c;break}if(canEat(c,b)&&d<700){b.angle+=Math.PI;break}}if(target){b.angle=Math.atan2(target.y-b.y,target.x-b.x)+(Math.random()-.5)*.5}}b.vx+=Math.cos(b.angle)*25*dt;b.vy+=Math.sin(b.angle)*25*dt;const s=Math.hypot(b.vx,b.vy)||1;const max=70/(1+Math.sqrt(b.m)/80);if(s>max){b.vx=b.vx/s*max;b.vy=b.vy/s*max}b.x=clamp(b.x+b.vx*dt,b.r,state.world-b.r);b.y=clamp(b.y+b.vy*dt,b.r,state.world-b.r)}}
function update(dt){if(!state.playing||state.paused)return;const first=state.cells[0]||state.bots[state.spectateIndex];if(!first)return;state.time+=dt/1000;const speed=320/Math.sqrt(Math.max(25,state.score))*32;const dx=state.mouse.x-W/2,dy=state.mouse.y-H/2,len=Math.hypot(dx,dy)||1;for(const c of state.cells){const localSpeed=clamp(520/Math.sqrt(c.m)*35,45,300);c.vx+=(dx/len*localSpeed-c.vx)*Math.min(1,dt*6);c.vy+=(dy/len*localSpeed-c.vy)*Math.min(1,dt*6);c.x=clamp(c.x+c.vx*dt,c.r,state.world-c.r);c.y=clamp(c.y+c.vy*dt,c.r,state.world-c.r);c.merge=Math.max(0,c.merge-dt)}
for(let i=state.ejected.length-1;i>=0;i--){const e=state.ejected[i];e.life-=dt;e.vx*=Math.pow(.03,dt);e.vy*=Math.pow(.03,dt);e.x=clamp(e.x+e.vx*dt,e.r,state.world-e.r);e.y=clamp(e.y+e.vy*dt,e.r,state.world-e.r);if(e.life<=0)state.ejected.splice(i,1)}
updateBots(dt);
for(const c of state.cells){for(let i=state.foods.length-1;i>=0;i--){const f=state.foods[i];if(d2(c,f)<c.r){c.m+=f.m;c.r=massToRadius(c.m);state.foods[i]=food();state.score=Math.max(state.score,Math.floor(c.m));state.foodEaten++}}}
for(const e of state.ejected){for(const c of state.cells){if(d2(e,c)<c.r){c.m+=e.m;c.r=massToRadius(c.m);e.life=0}}}
state.ejected=state.ejected.filter(e=>e.life>0);
for(const b of state.bots){for(const c of state.cells.slice()){if(state.mode==='teams'&&b.team===c.team)continue;const d=d2(b,c);if(canEat(c,b)&&d<c.r*.92){c.m+=b.m;c.r=massToRadius(c.m);state.kills++;b.x=rand(400,5600);b.y=rand(400,5600);b.m=rand(40,220);b.r=massToRadius(b.m)}else if(canEat(b,c)&&d<b.r*.92){die('Un rivale più grande ti ha divorato');return}}}
for(const v of state.viruses){for(const c of state.cells){if(d2(c,v)<c.r+v.r*.65){if(state.mode!=='ffa'||c.m>100)handleVirusHit(c,v)}}}
if(state.mode!=='ffa'){for(let i=state.cells.length-1;i>=0;i--){for(let j=i-1;j>=0;j--){const a=state.cells[i],b=state.cells[j];if(a.merge<=0&&b.merge<=0&&a.team===b.team&&d2(a,b)<Math.min(a.r,b.r)*.55){const big=a.m>=b.m?a:b,small=big===a?b:a;big.m+=small.m;big.r=massToRadius(big.m);state.cells.splice(state.cells.indexOf(small),1)}}}}
else{for(let i=state.cells.length-1;i>=0;i--){for(let j=i-1;j>=0;j--){const a=state.cells[i],b=state.cells[j];if(a.merge<=0&&b.merge<=0&&d2(a,b)<Math.min(a.r,b.r)*.55){const big=a.m>=b.m?a:b,small=big===a?b:a;if(canEat(big,small)){big.m+=small.m;big.r=massToRadius(big.m);if(!small.player)state.cells.splice(state.cells.indexOf(small),1)}}}}}
if(!state.cells.length){die('Partita terminata');return}state.score=state.cells.reduce((s,c)=>s+c.m,0);state.best=Math.max(state.best,state.score);state.camera.x=first.x;state.camera.y=first.y;state.camera.zoom=clamp(1/Math.pow(Math.max(25,state.score)/25,.22),.33,1.15);if(++state.leaderTick%20===0){updateLeaders();refreshLeaderboard()}if(Date.now()-state.lastSave>15000)saveProgress();applyProfileUI()}
function screenPoint(x,y){const z=state.camera.zoom;return{x:W/2+(x-state.camera.x)*z,y:H/2+(y-state.camera.y)*z}}
function draw(){ctx.clearRect(0,0,W,H);ctx.fillStyle='#dfe3e5';ctx.fillRect(0,0,W,H);if(!state.playing)return;const z=state.camera.zoom;const grid=50*z,ox=W/2-state.camera.x*z,oy=H/2-state.camera.y*z;ctx.strokeStyle='rgba(90,100,110,.16)';ctx.lineWidth=1;for(let x=((ox%grid)+grid)%grid;x<W;x+=grid){ctx.beginPath();ctx.moveTo(x,0);ctx.lineTo(x,H);ctx.stroke()}for(let y=((oy%grid)+grid)%grid;y<H;y+=grid){ctx.beginPath();ctx.moveTo(0,y);ctx.lineTo(W,y);ctx.stroke()}
drawRemotePlayers();
for(const f of state.foods){const p=screenPoint(f.x,f.y);ctx.beginPath();ctx.arc(p.x,p.y,f.r*z,0,Math.PI*2);ctx.fillStyle=f.c;ctx.fill()}
for(const e of state.ejected){const p=screenPoint(e.x,e.y);ctx.beginPath();ctx.arc(p.x,p.y,e.r*z,0,Math.PI*2);ctx.fillStyle=e.color;ctx.fill()}
for(const v of state.viruses){const p=screenPoint(v.x,v.y);ctx.save();ctx.translate(p.x,p.y);ctx.rotate(v.spin);ctx.beginPath();for(let i=0;i<40;i++){const a=i*Math.PI*2/40,r=(i%2?v.r*.84:v.r)*z;ctx.lineTo(Math.cos(a)*r,Math.sin(a)*r)}ctx.closePath();ctx.fillStyle='#54b948';ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=2;ctx.stroke();ctx.restore()}
for(const b of state.bots){const p=screenPoint(b.x,b.y);ctx.beginPath();ctx.arc(p.x,p.y,b.r*z,0,Math.PI*2);ctx.fillStyle=b.color;ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=2;ctx.stroke();if(b.r*z>20){ctx.fillStyle='#fff';ctx.font='bold 12px Arial';ctx.textAlign='center';ctx.fillText(b.name,p.x,p.y+4)}}
for(const c of state.cells){const p=screenPoint(c.x,c.y);ctx.beginPath();ctx.arc(p.x,p.y,c.r*z,0,Math.PI*2);ctx.fillStyle=c.color;ctx.fill();ctx.strokeStyle='#fff';ctx.lineWidth=3;ctx.stroke();if(c.r*z>22){ctx.fillStyle='#fff';ctx.font='bold 13px Arial';ctx.textAlign='center';ctx.fillText(state.nick,p.x,p.y+4)}}
if(state.minimap){mini.style.display='block';mctx.clearRect(0,0,150,150);mctx.fillStyle='#f2f2f2';mctx.fillRect(0,0,150,150);mctx.strokeStyle='#aaa';mctx.strokeRect(.5,.5,149,149);for(const b of state.bots){mctx.fillStyle=b.color;mctx.fillRect(b.x/state.world*150-1,b.y/state.world*150-1,2,2)}for(const c of state.cells){mctx.fillStyle=c.color;mctx.beginPath();mctx.arc(c.x/state.world*150,c.y/state.world*150,3,0,Math.PI*2);mctx.fill()}mctx.fillStyle='#111';mctx.font='9px Arial';mctx.fillText(state.mode.toUpperCase(),6,145)}
}
function loop(t){if(!state.playing)return;const dt=Math.min(.05,(t-state.lastFrame)/1000||.016);state.lastFrame=t;update(dt);draw();requestAnimationFrame(loop)}
function spectate(){state.spectating=true;document.getElementById('death').style.display='none';state.playing=true;state.cells=[];state.spectateIndex=Math.floor(Math.random()*Math.max(1,state.bots.length));state.lastFrame=now();function sl(t){if(!state.spectating)return;state.lastFrame=t;const b=state.bots[state.spectateIndex%Math.max(1,state.bots.length)];if(b){state.camera.x=b.x;state.camera.y=b.y;state.camera.zoom=.75}draw();requestAnimationFrame(sl)}requestAnimationFrame(sl)}
function help(){toast('SPACE split · W massa · rotella zoom · M mappa · ESC portale')}
addEventListener('mousemove',e=>{state.mouse.x=e.clientX;state.mouse.y=e.clientY});addEventListener('wheel',e=>{if(!state.playing)return;e.preventDefault();state.camera.zoom=clamp(state.camera.zoom*(e.deltaY<0?1.08:.92),.33,1.3)},{passive:false});addEventListener('keydown',e=>{if(e.code==='Space'){e.preventDefault();split()}else if(e.key.toLowerCase()==='w'){eject()}else if(e.key.toLowerCase()==='m'){state.minimap=!state.minimap}else if(e.key==='Escape'){location.href='https://www.zerothelegend.com/portal/index.php'}});
document.getElementById('play').onclick=startGame;document.getElementById('continueBtn').onclick=startGame;document.getElementById('respawnBtn').onclick=respawn;document.getElementById('spectateBtn').onclick=spectate;document.getElementById('back').onclick=()=>{saveProgress();location.href='https://www.zerothelegend.com/portal/index.php'};document.getElementById('portalBtn').onclick=()=>location.href='https://www.zerothelegend.com/portal/index.php';document.getElementById('deathPortalBtn').onclick=()=>location.href='https://www.zerothelegend.com/portal/index.php';document.getElementById('helpBtn').onclick=help;document.getElementById('refreshProfile').onclick=loadAccount;document.getElementById('saveNow').onclick=saveProgress;document.getElementById('splitBtn').onclick=split;document.getElementById('ejectBtn').onclick=eject;document.getElementById('miniBtn').onclick=()=>state.minimap=!state.minimap;document.getElementById('tsplit').onclick=split;document.getElementById('teject').onclick=eject;document.querySelectorAll('.mode button').forEach(b=>b.onclick=()=>{document.querySelectorAll('.mode button').forEach(x=>x.classList.remove('on'));b.classList.add('on');state.mode=b.dataset.mode});
const chatForm=document.getElementById('arenaChatForm');if(chatForm)chatForm.addEventListener('submit',e=>{e.preventDefault();const inp=document.getElementById('arenaChatInput');const t=inp?.value||'';if(inp)inp.value='';sendArenaChat(t)});addEventListener('pagehide',()=>{try{navigator.sendBeacon(ARENA_API,new Blob([JSON.stringify({action:'leave'})],{type:'application/json'}))}catch(e){}});
loadAccount();
})();
</script>
</body>
</html>
