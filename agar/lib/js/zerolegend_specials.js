
(function(){
  'use strict';
  var KEY='zl_user_settings_v1';
  var defaults={
    fps:true, lowGraphics:false, highQuality60:true, smoothAnimations:true, depth3d:true,
    particles:true, dashTrail:true, dashGlow:true, screenShake:true, sound:true,
    music:true, chatSounds:true, autoZoom:false, showMass:true, showNames:true,
    darkMode:true, compactHud:false, disableSkins:false, disableBackground:false, colorBlind:false
  };
  var defs=[
    ['fps','Mostra FPS','Visualizza gli FPS nell’HUD','bool'],
    ['lowGraphics','Grafica ridotta','Riduce effetti per dispositivi lenti','bool'],
    ['highQuality60','60 FPS','Mantiene il rendering a 60 FPS quando possibile','bool'],
    ['smoothAnimations','Animazioni fluide','Transizioni più morbide nel menu','bool'],
    ['depth3d','Profondità 3D','Attiva la futura profondità/luce delle celle','bool'],
    ['particles','Particelle','Effetti particellari durante eventi e abilità','bool'],
    ['dashTrail','Scia Dash','Lascia una scia durante il Dash','bool'],
    ['dashGlow','Bagliore Dash','Aura luminosa intorno alla cella durante il Dash','bool'],
    ['screenShake','Camera dinamica','Piccolo shake sugli impatti e sul Dash','bool'],
    ['sound','Suoni gioco','Audio effetti del gioco','bool'],
    ['music','Musica','Musica di sottofondo','bool'],
    ['chatSounds','Suoni chat','Segnale audio sui messaggi chat','bool'],
    ['autoZoom','Auto zoom','Adatta automaticamente lo zoom all’azione','bool'],
    ['showMass','Mostra massa','Mostra la massa della tua cella','bool'],
    ['showNames','Mostra nomi','Mostra i nomi delle celle','bool'],
    ['darkMode','Tema scuro','Usa l’interfaccia scura','bool'],
    ['compactHud','HUD compatto','Riduce le dimensioni degli elementi informativi','bool'],
    ['disableSkins','Nascondi skin','Disattiva le texture delle skin','bool'],
    ['disableBackground','Sfondo minimo','Riduce decorazioni dello sfondo','bool'],
    ['colorBlind','Modalità daltonismo','Palette ad alto contrasto','bool']
  ];
  function load(){try{return Object.assign({},defaults,JSON.parse(localStorage.getItem(KEY)||'{}'))}catch(e){return Object.assign({},defaults)}}
  function save(s){try{localStorage.setItem(KEY,JSON.stringify(s))}catch(e){}; apply(s)}
  function apply(s){
    document.body.classList.toggle('zl-low-graphics',!!s.lowGraphics);
    document.body.classList.toggle('zl-compact-hud',!!s.compactHud);
    document.body.classList.toggle('zl-no-bg',!!s.disableBackground);
    document.body.classList.toggle('zl-colorblind',!!s.colorBlind);
    if(window.setSkins && s.disableSkins!==undefined){try{window.setSkins(!!s.disableSkins)}catch(e){}}
    if(window.setNames && s.showNames!==undefined){try{window.setNames(!s.showNames)}catch(e){}}
    if(window.setShowMass && s.showMass!==undefined){try{window.setShowMass(!!s.showMass)}catch(e){}}
  }
  function render(){
    var grid=document.getElementById('zlSettingsGrid'), state=load(); if(!grid)return;
    grid.innerHTML='';
    defs.forEach(function(d){
      var row=document.createElement('div'); row.className='zl-setting';
      var main=document.createElement('div'); main.className='zl-setting-main';
      main.innerHTML='<div class="zl-setting-title">'+d[1]+'</div><div class="zl-setting-desc">'+d[2]+'</div>';
      var control=document.createElement('label'); control.className='zl-switch'; control.innerHTML='<input type="checkbox" data-zl-key="'+d[0]+'" '+(state[d[0]]?'checked':'')+'><span class="zl-track"></span>';
      row.appendChild(main); row.appendChild(control); grid.appendChild(row);
    });
    grid.querySelectorAll('input[data-zl-key]').forEach(function(i){i.addEventListener('change',function(){var s=load();s[this.dataset.zlKey]=this.checked;save(s)})});
  }
  function open(){var m=document.getElementById('zlUserSettings'); if(!m)return; render(); m.classList.add('is-open'); m.setAttribute('aria-hidden','false')}
  function close(){var m=document.getElementById('zlUserSettings'); if(!m)return; m.classList.remove('is-open'); m.setAttribute('aria-hidden','true');}
  document.addEventListener('DOMContentLoaded',function(){
    apply(load());
    var b=document.getElementById('zlOpenUserSettings'), c=document.getElementById('zlSettingsClose'), d=document.getElementById('zlSettingsDone'), r=document.getElementById('zlSettingsReset');
    if(b)b.onclick=open;if(c)c.onclick=close;if(d)d.onclick=close;if(r)r.onclick=function(){save(Object.assign({},defaults));render()};
    document.addEventListener('keydown',function(e){if((e.key==='u'||e.key==='U')&&!['INPUT','TEXTAREA'].includes(document.activeElement.tagName)){open()} if(e.key==='Escape')close()});
  });
  window.ZLUserSettings={open:open,close:close,load:load,save:save,defaults:defaults};

  /* Dash controller. The physics boost is sent through the original mouse packet
     from datad23b.js, while this layer renders the HUD and faux-3D feedback. */
  var dash={duration:10000,cooldown:16000,active:false,until:0,next:0,multiplier:2.6,raf:0,particles:[]};
  function ensureFx(){
    var c=document.getElementById('zlFxCanvas'); if(!c)return null;
    if(!c.width || c.width!==window.innerWidth*devicePixelRatio){c.width=Math.floor(window.innerWidth*devicePixelRatio);c.height=Math.floor(window.innerHeight*devicePixelRatio);}
    return c;
  }
  function setDashUi(on){
    document.body.classList.toggle('zl-dash-on',on);
    var hud=document.getElementById('zlDashHud'), state=document.getElementById('zlDashState');
    if(hud)hud.setAttribute('aria-hidden',on?'false':'true');
    if(state)state.textContent=on?'Boost attivo':'Pronto';
  }
  function seedParticles(){
    dash.particles=[];
    for(var i=0;i<90;i++) dash.particles.push({a:Math.random()*Math.PI*2,r:40+Math.random()*Math.min(innerWidth,innerHeight)*.42,z:.2+Math.random()*.8,s:.3+Math.random()*1.9});
  }
  function paintFx(now){
    var c=ensureFx(); if(!c){return}
    var x=c.getContext('2d'); if(!x)return;
    var d=devicePixelRatio||1, w=innerWidth*d, h=innerHeight*d; x.clearRect(0,0,w,h);
    if(!dash.active)return;
    var st=load();
    if(!st.depth3d && !st.particles && !st.dashTrail)return;
    var left=Math.max(0,dash.until-now), t=1-left/dash.duration, cx=w/2, cy=h/2;
    x.save();
    if(st.screenShake){ x.translate((Math.random()-0.5)*3*d,(Math.random()-0.5)*3*d); }
    x.translate(cx,cy); x.globalCompositeOperation='lighter';
    if(st.particles){
    for(var k=0;k<dash.particles.length;k++){
      var p=dash.particles[k], depth=(p.z+.18*Math.sin(now*.004+p.a))%1, rr=p.r*(1+depth*.85)+t*240;
      var px=Math.cos(p.a+t*2.8)*rr, py=Math.sin(p.a+t*2.8)*rr*.64;
      if(st.lowGraphics && (k%2)){continue;}
      var size=(2+5*(1-depth))*d;
      x.fillStyle='rgba(112,248,255,'+(0.05+0.28*(1-depth))+')'; x.beginPath(); x.arc(px*d,py*d,size,0,Math.PI*2); x.fill();
    }}
    if(st.dashTrail){ for(var q=0;q<4;q++){var rr=(80+q*58+t*190)*d; x.beginPath(); x.arc(0,0,rr,.08+q*.35,Math.PI*2-.08); x.strokeStyle='rgba(157,123,255,'+(0.16-q*.025)+')'; x.lineWidth=(2+q)*d; x.stroke();}}
    x.restore();
  }
  function tick(){
    var now=performance.now();
    if(dash.active){
      var left=Math.max(0,dash.until-now), timer=document.getElementById('zlDashTimer');
      if(timer)timer.textContent=(left/1000).toFixed(1)+'s';
      paintFx(now);
      if(left<=0){dash.active=false;setDashUi(false);}
    }
    dash.raf=requestAnimationFrame(tick);
  }
  function startDash(){
    var now=performance.now();
    if(dash.active || now<dash.next)return false;
    dash.active=true;dash.until=now+dash.duration;dash.next=now+dash.cooldown;seedParticles();setDashUi(true);
    try{ if(navigator.vibrate)navigator.vibrate(25); }catch(e){}
    if(load().sound){try{var A=window.AudioContext||window.webkitAudioContext;if(A){var ac=new A(),o=ac.createOscillator(),g=ac.createGain();o.frequency.value=520;o.type='sine';g.gain.setValueAtTime(.035,ac.currentTime);g.gain.exponentialRampToValueAtTime(.001,ac.currentTime+.16);o.connect(g);g.connect(ac.destination);o.start();o.stop(ac.currentTime+.16);}}catch(e){}}
    window.dispatchEvent(new CustomEvent('zl-dash-request',{detail:{duration:dash.duration,multiplier:dash.multiplier}}));
    return true;
  }
  window.ZLDashBridge={request:startDash,isActive:function(){return dash.active},multiplier:function(){return dash.multiplier},until:function(){return dash.until}};
  document.addEventListener('DOMContentLoaded',function(){
    var c=document.getElementById('zlFxCanvas'); if(c){ensureFx();window.addEventListener('resize',ensureFx)}
    document.addEventListener('keydown',function(e){
      if((e.key==='Shift'||e.keyCode===16)&&!e.repeat&&!['INPUT','TEXTAREA'].includes(document.activeElement.tagName)){e.preventDefault();startDash();}
    },true);
    seedParticles();tick();
  });
})();
