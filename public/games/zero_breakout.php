<?php
$u = ['username' => 'Giocatore'];
function esc(string $value): string { return htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html><html lang="it"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Zero Breakout · Zero World</title><style>@import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700;800&display=swap');body{margin:0;background:#050814;color:#fff;font-family:Inter,system-ui,sans-serif;overflow:hidden}#app{display:grid;grid-template-columns:1fr 320px;height:100vh}canvas{width:100%;height:100%;display:block;background:#07101f}.side{padding:18px;background:rgba(8,14,31,.96);border-left:1px solid rgba(0,240,255,.25);display:flex;flex-direction:column;gap:10px}.title{font:800 22px Orbitron,sans-serif;color:#00f0ff}.stat{padding:10px;border:1px solid rgba(0,240,255,.2);border-radius:12px;background:#0b1430}.btn{display:block;width:100%;padding:11px;border:0;border-radius:10px;background:#00f0ff;color:#04101b;font-weight:800;cursor:pointer}.btn.alt{background:#1c2b55;color:#fff}.hint{color:#9cb2d9;font-size:13px;line-height:1.5}.lb{max-height:240px;overflow:auto;border:1px solid rgba(255,255,255,.08);border-radius:10px}.row{display:flex;justify-content:space-between;padding:8px 10px;border-bottom:1px solid rgba(255,255,255,.06)}@media(max-width:800px){#app{grid-template-columns:1fr}.side{position:absolute;right:0;top:0;height:100%;width:min(320px,85vw);z-index:5;transform:translateX(100%);transition:.2s}.side.open{transform:translateX(0)}}</style></head>
<body><div id="app"><canvas id="c"></canvas><aside class="side"><div class="title">🧱 Zero Breakout</div><div class="hint">Distruggi i mattoni neon prima di perdere la palla.</div><div class="stat">Giocatore: <b><span id="name"><?php echo esc($u['username']); ?></span></b></div><div class="stat">Punteggio <b id="score">0</b></div><div class="stat">Record database <b id="best">0</b></div><button class="btn" id="start">GIOCA</button><button class="btn alt" id="back">PORTALE</button><div class="lb" id="lb"></div><div class="hint">Tutti i punteggi vengono salvati su MySQL. Nessun salvataggio locale.</div></aside></div>
<script>
const GAME={slug:"zero_breakout",name:"Zero Breakout"}; const c=document.getElementById('c'),x=c.getContext('2d'); const scoreEl=document.getElementById('score'),bestEl=document.getElementById('best'),lb=document.getElementById('lb');
function size(){c.width=Math.max(640,innerWidth-(innerWidth>800?320:0))*devicePixelRatio;c.height=innerHeight*devicePixelRatio;x.setTransform(devicePixelRatio,0,0,devicePixelRatio,0,0);} addEventListener('resize',size);size();
async function api(action,payload){const r=await fetch('https://games.zerothelegend.com/api/mini_games.php?action='+encodeURIComponent(action),{method:payload?'POST':'GET',credentials:'include',headers:payload?{'Content-Type':'application/json'}:{},body:payload?JSON.stringify(payload):undefined,cache:'no-store'});const d=await r.json();if(!r.ok||!d.success)throw new Error(d.error||('HTTP '+r.status));return d;}
async function loadBoard(){try{const d=await api('leaderboard',null);bestEl.textContent=d.mine||0;lb.innerHTML=(d.top||[]).map(r=>`<div class="row"><span>#${r.rank} ${r.username}</span><b>${r.score}</b></div>`).join('')||'<div class="row">Nessun record</div>';}catch(e){lb.innerHTML='<div class="row">Classifica non disponibile</div>';}}
async function submit(score,stats={}){score=Math.max(0,Math.floor(score));scoreEl.textContent=score;try{const d=await api('submit',{game:GAME.slug,score,stats});bestEl.textContent=d.best||score;}catch(e){console.warn(e);}loadBoard();}
document.getElementById('back').onclick=()=>location.href='https://www.zerothelegend.com/portal/index.php';
let run=false,p={x:0,y:0,vx:5,vy:-5},bar=0,blocks=[],score=0;
function start(){run=true;score=0;bar=innerWidth/2;p={x:innerWidth/2,y:innerHeight-80,vx:5,vy:-5};blocks=[];for(let r=0;r<5;r++)for(let k=0;k<10;k++)blocks.push({x:40+k*75,y:60+r*26,on:1});}
addEventListener('mousemove',e=>bar=e.clientX);addEventListener('touchmove',e=>bar=e.touches[0].clientX,{passive:true});
function f(){
  if(run){
    p.x+=p.vx;p.y+=p.vy;
    if(p.x<8||p.x>innerWidth-8)p.vx*=-1;
    if(p.y<8)p.vy*=-1;
    if(p.y>innerHeight-50&&Math.abs(p.x-bar)<70)p.vy=-Math.abs(p.vy);
    for(const b of blocks){if(b.on&&p.x>b.x&&p.x<b.x+65&&p.y>b.y&&p.y<b.y+18){b.on=0;p.vy*=-1;score+=5;}}
    if(p.y>innerHeight+20){run=false;submit(score,{blocks:blocks.filter(b=>!b.on).length});}
    if(!blocks.some(b=>b.on)){run=false;submit(score+100,{win:1});}
  }
  x.fillStyle='#07101f';x.fillRect(0,0,innerWidth,innerHeight);
  blocks.forEach(b=>{if(b.on){x.fillStyle='#ff2d6b';x.fillRect(b.x,b.y,65,18);}});
  x.fillStyle='#00f0ff';x.fillRect(bar-60,innerHeight-35,120,14);
  x.fillStyle='#ffe600';x.beginPath();x.arc(p.x,p.y,8,0,7);x.fill();
  requestAnimationFrame(f);
}
document.getElementById('start').onclick=start;requestAnimationFrame(f);

loadBoard();
</script></body></html>