/* ZeroAgar · traduzioni (10 lingue) con menu a bandierine.
   Si carica con UNA riga in zeroagar_classic.php:  <script src="zeroagar_i18n.js" defer></script>
   Il gioco resta scritto in italiano: questo file traduce testi, bottoni e messaggi al volo
   (anche quelli creati dopo) e ricorda la lingua scelta. Le frasi non presenti restano in italiano. */
(() => {
'use strict';
const KEY = 'zeroagar_lang';
/* codice, nome, paese per la bandiera */
const LANGS = [['it','Italiano','it'],['en','English','gb'],['es','Español','es'],['fr','Français','fr'],['de','Deutsch','de'],['pt','Português','br'],['ru','Русский','ru'],['zh','中文','cn'],['ja','日本語','jp'],['ar','العربية','sa']];
/* ordine traduzioni: en, es, fr, de, pt, ru, zh, ja, ar */
const D = [];
const d = (it, ...t) => D.push([it, t]);

/* ---- menu e interfaccia ---- */
d('Caricamento…','Loading…','Cargando…','Chargement…','Laden…','Carregando…','Загрузка…','加载中…','読み込み中…','جارٍ التحميل…');
d('Classifica','Leaderboard','Clasificación','Classement','Rangliste','Classificação','Рейтинг','排行榜','ランキング','الترتيب');
d('Account','Account','Cuenta','Compte','Konto','Conta','Аккаунт','账号','アカウント','الحساب');
d('Controllo sessione…','Checking session…','Comprobando sesión…','Vérification de la session…','Sitzung wird geprüft…','Verificando sessão…','Проверка сессии…','正在检查会话…','セッション確認中…','جارٍ التحقق من الجلسة…');
d('AGGIORNA','REFRESH','ACTUALIZAR','ACTUALISER','AKTUALISIEREN','ATUALIZAR','ОБНОВИТЬ','刷新','更新','تحديث');
d('SALVA PROGRESSI','SAVE PROGRESS','GUARDAR PROGRESO','SAUVEGARDER','FORTSCHRITT SPEICHERN','SALVAR PROGRESSO','СОХРАНИТЬ','保存进度','進行状況を保存','حفظ التقدم');
d('PROFILO','PROFILE','PERFIL','PROFIL','PROFIL','PERFIL','ПРОФИЛЬ','个人资料','プロフィール','الملف الشخصي');
d('SHOP','SHOP','TIENDA','BOUTIQUE','SHOP','LOJA','МАГАЗИН','商店','ショップ','المتجر');
d('COLLEZIONE','COLLECTION','COLECCIÓN','COLLECTION','SAMMLUNG','COLEÇÃO','КОЛЛЕКЦИЯ','收藏','コレクション','المجموعة');
d('IMPOSTAZIONI','SETTINGS','AJUSTES','PARAMÈTRES','EINSTELLUNGEN','CONFIGURAÇÕES','НАСТРОЙКИ','设置','設定','الإعدادات');
d('PORTALE','PORTAL','PORTAL','PORTAIL','PORTAL','PORTAL','ПОРТАЛ','门户','ポータル','البوابة');
d('PORTALE Zero World','PORTAL Zero World','PORTAL Zero World','PORTAIL Zero World','PORTAL Zero World','PORTAL Zero World','ПОРТАЛ Zero World','门户 Zero World','ポータル Zero World','بوابة Zero World');
d('Mangia palline e avversari più piccoli, evita i più grandi.','Eat pellets and smaller opponents, avoid the bigger ones.','Come bolitas y rivales más pequeños, evita a los más grandes.','Mange les billes et les adversaires plus petits, évite les plus gros.','Friss Kugeln und kleinere Gegner, meide die größeren.','Coma bolinhas e rivais menores, evite os maiores.','Поглощай шарики и меньших соперников, избегай больших.','吃掉小球和比你小的对手，躲开比你大的。','小さい玉と小さい相手を食べ、大きい相手は避けよう。','التهم الكرات والخصوم الأصغر وتجنّب الأكبر.');
d('Funzioni Classic','Classic features','Funciones Classic','Fonctions Classic','Classic-Funktionen','Recursos Classic','Функции Classic','经典功能','クラシック機能','ميزات كلاسيك');
d('Split, feed/eject, virus, zoom, minimappa, leaderboard, spectate, respawn e modalità di gioco.','Split, feed/eject, viruses, zoom, minimap, leaderboard, spectate, respawn and game modes.','Split, expulsar masa, virus, zoom, minimapa, clasificación, espectador, reaparecer y modos de juego.','Split, éjection de masse, virus, zoom, minicarte, classement, spectateur, réapparition et modes de jeu.','Split, Masse auswerfen, Viren, Zoom, Minikarte, Rangliste, Zuschauen, Respawn und Spielmodi.','Split, ejetar massa, vírus, zoom, minimapa, classificação, espectador, renascer e modos de jogo.','Разделение, выброс массы, вирусы, зум, миникарта, рейтинг, наблюдение, возрождение и режимы игры.','分裂、喷射质量、病毒、缩放、小地图、排行榜、观战、重生和游戏模式。','分裂、質量放出、ウイルス、ズーム、ミニマップ、ランキング、観戦、リスポーン、ゲームモード。','الانقسام، قذف الكتلة، الفيروسات، التكبير، الخريطة المصغرة، الترتيب، المشاهدة، إعادة الظهور وأنماط اللعب.');
d('CONTINUA','CONTINUE','CONTINUAR','CONTINUER','WEITER','CONTINUAR','ПРОДОЛЖИТЬ','继续','続ける','متابعة');
d('COMANDI','CONTROLS','CONTROLES','COMMANDES','STEUERUNG','CONTROLES','УПРАВЛЕНИЕ','操作','操作方法','التحكم');
d('SPLIT','SPLIT','DIVIDIR','DIVISER','TEILEN','DIVIDIR','РАЗДЕЛИТЬ','分裂','分裂','انقسام');
d('SPLIT [{0}]','SPLIT [{0}]','DIVIDIR [{0}]','DIVISER [{0}]','TEILEN [{0}]','DIVIDIR [{0}]','РАЗДЕЛИТЬ [{0}]','分裂 [{0}]','分裂 [{0}]','انقسام [{0}]');
d('MASSA','MASS','MASA','MASSE','MASSE','MASSA','МАССА','质量','質量','الكتلة');
d('MASSA [{0}]','MASS [{0}]','MASA [{0}]','MASSE [{0}]','MASSE [{0}]','MASSA [{0}]','МАССА [{0}]','质量 [{0}]','質量 [{0}]','الكتلة [{0}]');
d('MAPPA [{0}]','MAP [{0}]','MAPA [{0}]','CARTE [{0}]','KARTE [{0}]','MAPA [{0}]','КАРТА [{0}]','地图 [{0}]','マップ [{0}]','الخريطة [{0}]');
d('PAUSA [{0}]','PAUSE [{0}]','PAUSA [{0}]','PAUSE [{0}]','PAUSE [{0}]','PAUSA [{0}]','ПАУЗА [{0}]','暂停 [{0}]','ポーズ [{0}]','إيقاف [{0}]');
d('PAUSA','PAUSE','PAUSA','PAUSE','PAUSE','PAUSA','ПАУЗА','暂停','ポーズ','إيقاف مؤقت');
d('premi {0} per riprendere','press {0} to resume','pulsa {0} para reanudar','appuie sur {0} pour reprendre','{0} drücken zum Fortsetzen','pressione {0} para continuar','нажмите {0}, чтобы продолжить','按 {0} 继续','{0} で再開','اضغط {0} للمتابعة');
d('SEI STATO DIVORATO','YOU WERE EATEN','FUISTE DEVORADO','TU AS ÉTÉ DÉVORÉ','DU WURDEST GEFRESSEN','VOCÊ FOI DEVORADO','ТЕБЯ СЪЕЛИ','你被吃掉了','食べられました','لقد تم التهامك');
d('SPETTATORE','SPECTATE','ESPECTADOR','SPECTATEUR','ZUSCHAUEN','ESPECTADOR','НАБЛЮДАТЬ','观战','観戦','مشاهدة');
d('Account collegato','Account connected','Cuenta conectada','Compte connecté','Konto verbunden','Conta conectada','Аккаунт подключён','账号已连接','アカウント連携済み','الحساب متصل');
d('Progressi salvati nel database.','Progress saved in the database.','Progreso guardado en la base de datos.','Progression enregistrée dans la base de données.','Fortschritt in der Datenbank gespeichert.','Progresso salvo no banco de dados.','Прогресс сохранён в базе данных.','进度已保存到数据库。','進行状況はデータベースに保存されます。','يتم حفظ التقدم في قاعدة البيانات.');
d('i progressi vengono salvati nel database.','progress is saved in the database.','el progreso se guarda en la base de datos.','la progression est enregistrée dans la base de données.','der Fortschritt wird in der Datenbank gespeichert.','o progresso é salvo no banco de dados.','прогресс сохраняется в базе данных.','进度会保存到数据库。','進行状況はデータベースに保存されます。','يتم حفظ التقدم في قاعدة البيانات.');
d('Sessione non disponibile. Accedi dal portale.','Session unavailable. Log in from the portal.','Sesión no disponible. Inicia sesión desde el portal.','Session indisponible. Connecte-toi depuis le portail.','Sitzung nicht verfügbar. Melde dich über das Portal an.','Sessão indisponível. Entre pelo portal.','Сессия недоступна. Войдите через портал.','会话不可用，请从门户登录。','セッションが利用できません。ポータルからログインしてください。','الجلسة غير متاحة. سجّل الدخول من البوابة.');
d('Sessione non disponibile','Session unavailable','Sesión no disponible','Session indisponible','Sitzung nicht verfügbar','Sessão indisponível','Сессия недоступна','会话不可用','セッションが利用できません','الجلسة غير متاحة');
d('accedi dal portale per salvare i progressi.','log in from the portal to save your progress.','inicia sesión desde el portal para guardar el progreso.','connecte-toi depuis le portail pour sauvegarder.','melde dich über das Portal an, um zu speichern.','entre pelo portal para salvar o progresso.','войдите через портал, чтобы сохранять прогресс.','请从门户登录以保存进度。','ポータルからログインして進行状況を保存してください。','سجّل الدخول من البوابة لحفظ التقدم.');
d('CHAT ARENA','ARENA CHAT','CHAT DE LA ARENA','CHAT DE L’ARÈNE','ARENA-CHAT','CHAT DA ARENA','ЧАТ АРЕНЫ','竞技场聊天','アリーナチャット','دردشة الساحة');
d('Scrivi nella chat globale…','Write in the global chat…','Escribe en el chat global…','Écris dans le chat global…','Schreibe im globalen Chat…','Escreva no chat global…','Напишите в общий чат…','在全局聊天中输入…','全体チャットに書き込む…','اكتب في الدردشة العامة…');
d('INVIA','SEND','ENVIAR','ENVOYER','SENDEN','ENVIAR','ОТПРАВИТЬ','发送','送信','إرسال');
d('Mouse / dito = movimento','Mouse / finger = move','Ratón / dedo = movimiento','Souris / doigt = déplacement','Maus / Finger = Bewegung','Mouse / dedo = movimento','Мышь / палец = движение','鼠标/手指 = 移动','マウス/指 = 移動','الفأرة / الإصبع = حركة');
d('tasti personalizzabili in Impostazioni › Controlli','keys customizable in Settings › Controls','teclas personalizables en Ajustes › Controles','touches personnalisables dans Paramètres › Commandes','Tasten anpassbar unter Einstellungen › Steuerung','teclas personalizáveis em Configurações › Controles','клавиши настраиваются в Настройки › Управление','可在 设置 › 操作 中自定义按键','キーは 設定 › 操作 で変更可能','يمكن تخصيص المفاتيح من الإعدادات › التحكم');
d('raccogli i bonus','collect the bonuses','recoge los bonus','ramasse les bonus','sammle die Boni','colete os bônus','собирайте бонусы','收集奖励','ボーナスを集めよう','اجمع المكافآت');

/* ---- menu utente ---- */
d('Profilo e statistiche','Profile and stats','Perfil y estadísticas','Profil et statistiques','Profil und Statistiken','Perfil e estatísticas','Профиль и статистика','资料与统计','プロフィールと統計','الملف الشخصي والإحصاءات');
d('Impostazioni','Settings','Ajustes','Paramètres','Einstellungen','Configurações','Настройки','设置','設定','الإعدادات');
d('Schermo intero','Fullscreen','Pantalla completa','Plein écran','Vollbild','Tela cheia','Во весь экран','全屏','全画面','ملء الشاشة');
d('Esci da schermo intero','Exit fullscreen','Salir de pantalla completa','Quitter le plein écran','Vollbild beenden','Sair da tela cheia','Выйти из полноэкранного режима','退出全屏','全画面を終了','الخروج من ملء الشاشة');
d('Salva progressi','Save progress','Guardar progreso','Sauvegarder','Fortschritt speichern','Salvar progresso','Сохранить прогресс','保存进度','進行状況を保存','حفظ التقدم');
d('Aggiorna account','Refresh account','Actualizar cuenta','Actualiser le compte','Konto aktualisieren','Atualizar conta','Обновить аккаунт','刷新账号','アカウントを更新','تحديث الحساب');
d('Shop Zero Coin','Zero Coin Shop','Tienda Zero Coin','Boutique Zero Coin','Zero-Coin-Shop','Loja Zero Coin','Магазин Zero Coin','Zero Coin 商店','Zero Coin ショップ','متجر Zero Coin');
d('Collezione e skin','Collection and skins','Colección y skins','Collection et skins','Sammlung und Skins','Coleção e skins','Коллекция и скины','收藏与皮肤','コレクションとスキン','المجموعة والأشكال');
d('Amministrazione','Administration','Administración','Administration','Verwaltung','Administração','Администрирование','管理','管理','الإدارة');
d('Portale','Portal','Portal','Portail','Portal','Portal','Портал','门户','ポータル','البوابة');

/* ---- impostazioni: schede ---- */
d('Gioco','Game','Juego','Jeu','Spiel','Jogo','Игра','游戏','ゲーム','اللعبة');
d('Grafica','Graphics','Gráficos','Graphismes','Grafik','Gráficos','Графика','画面','グラフィック','الرسوميات');
d('Audio','Audio','Audio','Audio','Audio','Áudio','Звук','音频','サウンド','الصوت');
d('Schermo','Screen','Pantalla','Écran','Bildschirm','Tela','Экран','屏幕','画面','الشاشة');
d('Interfaccia','Interface','Interfaz','Interface','Oberfläche','Interface','Интерфейс','界面','インターフェース','الواجهة');
d('Controlli','Controls','Controles','Commandes','Steuerung','Controles','Управление','操作','操作','التحكم');

/* ---- impostazioni: voci ---- */
d('Mostra i nomi','Show names','Mostrar nombres','Afficher les noms','Namen anzeigen','Mostrar nomes','Показывать имена','显示名字','名前を表示','إظهار الأسماء');
d('Mostra la massa','Show mass','Mostrar masa','Afficher la masse','Masse anzeigen','Mostrar massa','Показывать массу','显示质量','質量を表示','إظهار الكتلة');
d('Zoom automatico','Auto zoom','Zoom automático','Zoom automatique','Auto-Zoom','Zoom automático','Автозум','自动缩放','自動ズーム','تكبير تلقائي');
d('Numero di bot','Number of bots','Número de bots','Nombre de bots','Anzahl der Bots','Número de bots','Количество ботов','机器人数量','ボットの数','عدد الروبوتات');
d('Difficoltà dei bot','Bot difficulty','Dificultad de los bots','Difficulté des bots','Bot-Schwierigkeit','Dificuldade dos bots','Сложность ботов','机器人难度','ボットの難易度','صعوبة الروبوتات');
d('Chat arena online','Online arena chat','Chat de arena online','Chat d’arène en ligne','Online-Arena-Chat','Chat da arena online','Онлайн-чат арены','在线竞技场聊天','オンラインアリーナチャット','دردشة الساحة عبر الإنترنت');
d('Velocità espulsione massa','Mass eject speed','Velocidad de expulsión de masa','Vitesse d’éjection de masse','Auswurfgeschwindigkeit','Velocidade de ejeção de massa','Скорость выброса массы','喷射质量速度','質量放出速度','سرعة قذف الكتلة');
d('Zona morta del cursore','Cursor dead zone','Zona muerta del cursor','Zone morte du curseur','Cursor-Totzone','Zona morta do cursor','Мёртвая зона курсора','光标死区','カーソルのデッドゾーン','المنطقة الميتة للمؤشر');
d('Respawn automatico','Auto respawn','Reaparición automática','Réapparition auto','Automatischer Respawn','Renascimento automático','Автовозрождение','自动重生','自動リスポーン','إعادة الظهور تلقائياً');
d('Vibrazione','Vibration','Vibración','Vibration','Vibration','Vibração','Вибрация','振动','バイブレーション','الاهتزاز');
d('Tema','Theme','Tema','Thème','Design','Tema','Тема','主题','テーマ','السمة');
d('Qualità','Quality','Calidad','Qualité','Qualität','Qualidade','Качество','画质','画質','الجودة');
d('Griglia di sfondo','Background grid','Cuadrícula de fondo','Grille d’arrière-plan','Hintergrundraster','Grade de fundo','Сетка фона','背景网格','背景グリッド','شبكة الخلفية');
d('Effetti particellari','Particle effects','Efectos de partículas','Effets de particules','Partikeleffekte','Efeitos de partículas','Эффекты частиц','粒子特效','パーティクル効果','تأثيرات الجسيمات');
d('Minimappa','Minimap','Minimapa','Minicarte','Minikarte','Minimapa','Миникарта','小地图','ミニマップ','الخريطة المصغرة');
d('Mostra FPS','Show FPS','Mostrar FPS','Afficher les FPS','FPS anzeigen','Mostrar FPS','Показывать FPS','显示 FPS','FPSを表示','إظهار FPS');
d('Dimensione minimappa','Minimap size','Tamaño del minimapa','Taille de la minicarte','Minikarten-Größe','Tamanho do minimapa','Размер миникарты','小地图大小','ミニマップのサイズ','حجم الخريطة المصغرة');
d('Posizione minimappa','Minimap position','Posición del minimapa','Position de la minicarte','Minikarten-Position','Posição do minimapa','Положение миникарты','小地图位置','ミニマップの位置','موضع الخريطة المصغرة');
d('Spessore bordo cellule','Cell border width','Grosor del borde de las células','Épaisseur de bordure des cellules','Zellrandbreite','Espessura da borda das células','Толщина границы клеток','细胞边框粗细','セルの枠の太さ','سمك حدود الخلايا');
d('Timer di riunione sulle cellule','Merge timer on cells','Temporizador de fusión en las células','Minuteur de fusion sur les cellules','Verschmelz-Timer auf Zellen','Cronômetro de fusão nas células','Таймер слияния на клетках','细胞合并计时器','セルの合体タイマー','مؤقت الدمج على الخلايا');
d('Bordo della mappa','Map border','Borde del mapa','Bordure de la carte','Kartenrand','Borda do mapa','Граница карты','地图边界','マップの境界','حدود الخريطة');
d('Linea verso il cursore','Line to cursor','Línea al cursor','Ligne vers le curseur','Linie zum Cursor','Linha até o cursor','Линия к курсору','指向光标的线','カーソルへの線','خط نحو المؤشر');
d('Dimensione del cibo','Food size','Tamaño de la comida','Taille de la nourriture','Futtergröße','Tamanho da comida','Размер еды','食物大小','エサのサイズ','حجم الطعام');
d('Colore di sfondo','Background color','Color de fondo','Couleur d’arrière-plan','Hintergrundfarbe','Cor de fundo','Цвет фона','背景颜色','背景色','لون الخلفية');
d('Effetti sonori','Sound effects','Efectos de sonido','Effets sonores','Soundeffekte','Efeitos sonoros','Звуковые эффекты','音效','効果音','المؤثرات الصوتية');
d('Volume','Volume','Volumen','Volume','Lautstärke','Volume','Громкость','音量','音量','مستوى الصوت');
d('Dimensione interfaccia','Interface size','Tamaño de la interfaz','Taille de l’interface','Oberflächengröße','Tamanho da interface','Размер интерфейса','界面大小','UIのサイズ','حجم الواجهة');
d('Pulsanti touch','Touch buttons','Botones táctiles','Boutons tactiles','Touch-Tasten','Botões de toque','Сенсорные кнопки','触控按钮','タッチボタン','أزرار اللمس');
d('Tieni lo schermo acceso','Keep screen on','Mantener pantalla encendida','Garder l’écran allumé','Bildschirm anlassen','Manter tela ligada','Не гасить экран','保持屏幕常亮','画面をオンのままにする','إبقاء الشاشة مضاءة');
d('Mostra massa e numero di cellule','Show mass and cell count','Mostrar masa y número de células','Afficher masse et nombre de cellules','Masse und Zellenzahl anzeigen','Mostrar massa e nº de células','Показывать массу и число клеток','显示质量和细胞数','質量とセル数を表示','إظهار الكتلة وعدد الخلايا');
d('Mostra la classifica','Show leaderboard','Mostrar clasificación','Afficher le classement','Rangliste anzeigen','Mostrar classificação','Показывать рейтинг','显示排行榜','ランキングを表示','إظهار الترتيب');
d('Mostra il kill feed','Show kill feed','Mostrar feed de eliminaciones','Afficher le kill feed','Kill-Feed anzeigen','Mostrar feed de abates','Показывать ленту убийств','显示击杀信息','キルフィードを表示','إظهار سجل القتل');
d('Tasto sinistro del mouse','Left mouse button','Botón izquierdo del ratón','Clic gauche de la souris','Linke Maustaste','Botão esquerdo do mouse','Левая кнопка мыши','鼠标左键','マウス左ボタン','زر الفأرة الأيسر');
d('Tasto destro del mouse','Right mouse button','Botón derecho del ratón','Clic droit de la souris','Rechte Maustaste','Botão direito do mouse','Правая кнопка мыши','鼠标右键','マウス右ボタン','زر الفأرة الأيمن');
d('Vale dalla prossima partita','Applies from the next game','Se aplica desde la próxima partida','S’applique dès la prochaine partie','Gilt ab der nächsten Partie','Vale a partir da próxima partida','Со следующей игры','从下一局开始生效','次のゲームから有効','يسري من المباراة القادمة');
d('Attiva','Enable','Activar','Activer','Aktivieren','Ativar','Включить','开启','オン','تفعيل');

/* ---- opzioni dei menu a tendina ---- */
d('Pochi','Few','Pocos','Peu','Wenige','Poucos','Мало','少','少ない','قليل');
d('Normale','Normal','Normal','Normal','Normal','Normal','Обычно','正常','標準','عادي');
d('Tanti','Many','Muchos','Beaucoup','Viele','Muitos','Много','多','多い','كثير');
d('Facile','Easy','Fácil','Facile','Leicht','Fácil','Легко','简单','簡単','سهل');
d('Difficile','Hard','Difícil','Difficile','Schwer','Difícil','Сложно','困难','難しい','صعب');
d('Chiaro','Light','Claro','Clair','Hell','Claro','Светлая','浅色','ライト','فاتح');
d('Scuro','Dark','Oscuro','Sombre','Dunkel','Escuro','Тёмная','深色','ダーク','داكن');
d('Bassa','Low','Baja','Basse','Niedrig','Baixa','Низкое','低','低','منخفضة');
d('Media','Medium','Media','Moyenne','Mittel','Média','Среднее','中','中','متوسطة');
d('Alta','High','Alta','Haute','Hoch','Alta','Высокое','高','高','عالية');
d('Piccola','Small','Pequeña','Petite','Klein','Pequena','Маленький','小','小','صغيرة');
d('Grande','Large','Grande','Grande','Groß','Grande','Большой','大','大','كبيرة');
d('Destra','Right','Derecha','Droite','Rechts','Direita','Справа','右','右','يمين');
d('Sinistra','Left','Izquierda','Gauche','Links','Esquerda','Слева','左','左','يسار');
d('Classico','Classic','Clásico','Classique','Klassisch','Clássico','Классический','经典','クラシック','كلاسيكي');
d('Blu','Blue','Azul','Bleu','Blau','Azul','Синий','蓝色','青','أزرق');
d('Verde','Green','Verde','Vert','Grün','Verde','Зелёный','绿色','緑','أخضر');
d('Sabbia','Sand','Arena','Sable','Sand','Areia','Песок','沙色','サンド','رملي');
d('Bianco/Nero','White/Black','Blanco/Negro','Blanc/Noir','Weiß/Schwarz','Branco/Preto','Белый/Чёрный','白/黑','白/黒','أبيض/أسود');
d('Lenta','Slow','Lenta','Lente','Langsam','Lenta','Медленно','慢','遅い','بطيئة');
d('Veloce','Fast','Rápida','Rapide','Schnell','Rápida','Быстро','快','速い','سريعة');
d('Automatico','Automatic','Automático','Automatique','Automatisch','Automático','Авто','自动','自動','تلقائي');
d('Sempre visibili','Always visible','Siempre visibles','Toujours visibles','Immer sichtbar','Sempre visíveis','Всегда видны','始终显示','常に表示','مرئية دائماً');
d('Nascosti','Hidden','Ocultos','Masqués','Versteckt','Ocultos','Скрыты','隐藏','非表示','مخفية');
d('Nessuna azione','No action','Ninguna acción','Aucune action','Keine Aktion','Nenhuma ação','Нет действия','无操作','なし','بدون إجراء');
d('Split','Split','Dividir','Diviser','Teilen','Dividir','Разделение','分裂','分裂','انقسام');
d('Doppio split','Double split','Doble split','Double split','Doppel-Split','Split duplo','Двойное разделение','双重分裂','ダブル分裂','انقسام مزدوج');
d('Triplo split','Triple split','Triple split','Triple split','Dreifach-Split','Split triplo','Тройное разделение','三重分裂','トリプル分裂','انقسام ثلاثي');
d('Split massimo','Max split','Split máximo','Split maximum','Maximaler Split','Split máximo','Макс. разделение','最大分裂','最大分裂','أقصى انقسام');
d('Split massimo (fino a 16)','Max split (up to 16)','Split máximo (hasta 16)','Split maximum (jusqu’à 16)','Maximaler Split (bis 16)','Split máximo (até 16)','Макс. разделение (до 16)','最大分裂（最多16个）','最大分裂（最大16）','أقصى انقسام (حتى 16)');
d('Espelli massa','Eject mass','Expulsar masa','Éjecter de la masse','Masse auswerfen','Ejetar massa','Выбросить массу','喷射质量','質量を放出','قذف الكتلة');

/* ---- tasti ---- */
d('Movimento','Movement','Movimiento','Mouvement','Bewegung','Movimento','Движение','移动','移動','الحركة');
d('Azioni','Actions','Acciones','Actions','Aktionen','Ações','Действия','动作','アクション','الإجراءات');
d('Visuale','View','Vista','Vue','Ansicht','Visão','Вид','视图','表示','العرض');
d('Muovi su','Move up','Mover arriba','Aller en haut','Nach oben','Mover para cima','Вверх','向上移动','上へ移動','تحرك لأعلى');
d('Muovi giù','Move down','Mover abajo','Aller en bas','Nach unten','Mover para baixo','Вниз','向下移动','下へ移動','تحرك لأسفل');
d('Muovi a sinistra','Move left','Mover a la izquierda','Aller à gauche','Nach links','Mover para a esquerda','Влево','向左移动','左へ移動','تحرك لليسار');
d('Muovi a destra','Move right','Mover a la derecha','Aller à droite','Nach rechts','Mover para a direita','Вправо','向右移动','右へ移動','تحرك لليمين');
d('Ferma le cellule (tieni premuto)','Stop cells (hold)','Detener células (mantener)','Arrêter les cellules (maintenir)','Zellen stoppen (halten)','Parar células (segurar)','Остановить клетки (удерживать)','停止细胞（按住）','セルを止める（長押し）','إيقاف الخلايا (اضغط مطولاً)');
d('Espelli massa continuo (tieni premuto)','Continuous eject (hold)','Expulsión continua (mantener)','Éjection continue (maintenir)','Dauerauswurf (halten)','Ejeção contínua (segurar)','Непрерывный выброс (удерживать)','连续喷射（按住）','連続放出（長押し）','قذف مستمر (اضغط مطولاً)');
d('Dash velocità (7s, ricarica 30s)','Speed dash (7s, 30s recharge)','Dash de velocidad (7 s, recarga 30 s)','Dash de vitesse (7 s, recharge 30 s)','Speed-Dash (7 s, 30 s Aufladung)','Dash de velocidade (7 s, recarga 30 s)','Рывок (7 с, перезарядка 30 с)','加速冲刺（7秒，冷却30秒）','スピードダッシュ（7秒、リチャージ30秒）','اندفاع سريع (7 ثوانٍ، إعادة شحن 30 ثانية)');
d('Blink (teletrasporto, ricarica 20s)','Blink (teleport, 20s cooldown)','Blink (teletransporte, recarga 20 s)','Blink (téléportation, recharge 20 s)','Blink (Teleport, 20 s Aufladung)','Blink (teletransporte, recarga 20 s)','Блинк (телепорт, перезарядка 20 с)','闪现（传送，冷却20秒）','ブリンク（テレポート、リチャージ20秒）','وميض (انتقال فوري، إعادة شحن 20 ثانية)');
d('Barriera (invulnerabile 3s, ricarica 40s)','Barrier (invulnerable 3s, 40s cooldown)','Barrera (invulnerable 3 s, recarga 40 s)','Barrière (invulnérable 3 s, recharge 40 s)','Barriere (unverwundbar 3 s, 40 s Aufladung)','Barreira (invulnerável 3 s, recarga 40 s)','Барьер (неуязвимость 3 с, перезарядка 40 с)','屏障（无敌3秒，冷却40秒）','バリア（無敵3秒、リチャージ40秒）','حاجز (منيع 3 ثوانٍ، إعادة شحن 40 ثانية)');
d('Onda d’urto (respinge i nemici, ricarica 35s)','Shockwave (pushes enemies away, 35s cooldown)','Onda expansiva (aleja enemigos, recarga 35 s)','Onde de choc (repousse les ennemis, recharge 35 s)','Schockwelle (stößt Gegner weg, 35 s Aufladung)','Onda de choque (afasta inimigos, recarga 35 s)','Ударная волна (отталкивает врагов, перезарядка 35 с)','冲击波（击退敌人，冷却35秒）','衝撃波（敵を押し返す、リチャージ35秒）','موجة صدمة (تدفع الأعداء، إعادة شحن 35 ثانية)');
d('Ombra (invisibile ai nemici 5s, ricarica 45s)','Shadow (invisible to enemies 5s, 45s cooldown)','Sombra (invisible a enemigos 5 s, recarga 45 s)','Ombre (invisible aux ennemis 5 s, recharge 45 s)','Schatten (unsichtbar für Gegner 5 s, 45 s Aufladung)','Sombra (invisível a inimigos 5 s, recarga 45 s)','Тень (невидимость для врагов 5 с, перезарядка 45 с)','暗影（对敌人隐形5秒，冷却45秒）','シャドウ（敵に見えない5秒、リチャージ45秒）','ظل (غير مرئي للأعداء 5 ثوانٍ، إعادة شحن 45 ثانية)');
d('Pausa','Pause','Pausa','Pause','Pause','Pausa','Пауза','暂停','ポーズ','إيقاف مؤقت');
d('Rigioca dopo la morte','Play again after death','Jugar de nuevo tras morir','Rejouer après la mort','Nach dem Tod neu starten','Jogar de novo após morrer','Играть снова после смерти','死亡后重新开始','死亡後に再プレイ','العب مجدداً بعد الموت');
d('Menu di gioco','Game menu','Menú del juego','Menu du jeu','Spielmenü','Menu do jogo','Меню игры','游戏菜单','ゲームメニュー','قائمة اللعبة');
d('Screenshot','Screenshot','Captura de pantalla','Capture d’écran','Screenshot','Captura de tela','Скриншот','截图','スクリーンショット','لقطة شاشة');
d('Scrivi in chat','Write in chat','Escribir en el chat','Écrire dans le chat','In den Chat schreiben','Escrever no chat','Писать в чат','聊天输入','チャットに書く','اكتب في الدردشة');
d('Minimappa on/off','Minimap on/off','Minimapa sí/no','Minicarte on/off','Minikarte an/aus','Minimapa on/off','Миникарта вкл/выкл','小地图 开/关','ミニマップ オン/オフ','الخريطة المصغرة تشغيل/إيقاف');
d('Zoom avanti','Zoom in','Acercar','Zoom avant','Hineinzoomen','Aproximar','Приблизить','放大','ズームイン','تكبير');
d('Zoom indietro','Zoom out','Alejar','Zoom arrière','Herauszoomen','Afastar','Отдалить','缩小','ズームアウト','تصغير');
d('Reimposta zoom','Reset zoom','Restablecer zoom','Réinitialiser le zoom','Zoom zurücksetzen','Redefinir zoom','Сбросить зум','重置缩放','ズームをリセット','إعادة ضبط التكبير');
d('Mostra/nascondi nomi','Show/hide names','Mostrar/ocultar nombres','Afficher/masquer les noms','Namen ein/aus','Mostrar/ocultar nomes','Показать/скрыть имена','显示/隐藏名字','名前の表示切替','إظهار/إخفاء الأسماء');
d('Mostra/nascondi massa','Show/hide mass','Mostrar/ocultar masa','Afficher/masquer la masse','Masse ein/aus','Mostrar/ocultar massa','Показать/скрыть массу','显示/隐藏质量','質量の表示切替','إظهار/إخفاء الكتلة');
d('Mostra/nascondi griglia','Show/hide grid','Mostrar/ocultar cuadrícula','Afficher/masquer la grille','Raster ein/aus','Mostrar/ocultar grade','Показать/скрыть сетку','显示/隐藏网格','グリッドの表示切替','إظهار/إخفاء الشبكة');
d('Cambia tema chiaro/scuro','Toggle light/dark theme','Cambiar tema claro/oscuro','Changer de thème clair/sombre','Hell/Dunkel wechseln','Alternar tema claro/escuro','Сменить светлую/тёмную тему','切换浅色/深色主题','ライト/ダーク切替','تبديل السمة فاتح/داكن');
d('Ripristina tasti','Reset keys','Restablecer teclas','Réinitialiser les touches','Tasten zurücksetzen','Redefinir teclas','Сбросить клавиши','重置按键','キーをリセット','إعادة ضبط المفاتيح');
d('Premi un tasto…','Press a key…','Pulsa una tecla…','Appuie sur une touche…','Taste drücken…','Pressione uma tecla…','Нажмите клавишу…','请按一个键…','キーを押してください…','اضغط على مفتاح…');
d('Ripristina','Reset','Restablecer','Réinitialiser','Zurücksetzen','Redefinir','Сбросить','重置','リセット','إعادة ضبط');
d('Chiudi','Close','Cerrar','Fermer','Schließen','Fechar','Закрыть','关闭','閉じる','إغلاق');
d('Clicca un tasto e premi quello nuovo','Click a key and press the new one','Haz clic en una tecla y pulsa la nueva','Clique sur une touche puis appuie sur la nouvelle','Taste anklicken und neue drücken','Clique em uma tecla e pressione a nova','Нажмите на клавишу и затем на новую','点击按键后按下新的按键','キーをクリックして新しいキーを押す','انقر على مفتاح ثم اضغط المفتاح الجديد');
d('ESC annulla','ESC cancels','ESC cancela','ÉCHAP annule','ESC bricht ab','ESC cancela','ESC — отмена','ESC 取消','ESC でキャンセル','ESC للإلغاء');
d('Backspace rimuove il tasto','Backspace removes the key','Retroceso quita la tecla','Retour arrière supprime la touche','Rücktaste entfernt die Taste','Backspace remove a tecla','Backspace удаляет клавишу','Backspace 删除按键','Backspace でキーを解除','Backspace لإزالة المفتاح');

/* ---- profilo e menu di pausa ---- */
d('Profilo','Profile','Perfil','Profil','Profil','Perfil','Профиль','资料','プロフィール','الملف الشخصي');
d('Livello','Level','Nivel','Niveau','Level','Nível','Уровень','等级','レベル','المستوى');
d('Record','Record','Récord','Record','Rekord','Recorde','Рекорд','纪录','記録','الرقم القياسي');
d('Partite','Games','Partidas','Parties','Spiele','Partidas','Игры','场次','試合数','المباريات');
d('Tempo totale','Total time','Tiempo total','Temps total','Gesamtzeit','Tempo total','Всего времени','总时间','合計時間','الوقت الإجمالي');
d('Kill (ultima partita)','Kills (last game)','Eliminaciones (última partida)','Éliminations (dernière partie)','Kills (letztes Spiel)','Abates (última partida)','Убийства (последняя игра)','击杀（上一局）','キル（直前の試合）','القتلات (آخر مباراة)');
d('Cibo (ultima partita)','Food (last game)','Comida (última partida)','Nourriture (dernière partie)','Futter (letztes Spiel)','Comida (última partida)','Еда (последняя игра)','食物（上一局）','エサ（直前の試合）','الطعام (آخر مباراة)');
d('Combo max (ultima partita)','Max combo (last game)','Combo máx. (última partida)','Combo max (dernière partie)','Max. Combo (letztes Spiel)','Combo máx. (última partida)','Макс. комбо (последняя игра)','最大连击（上一局）','最大コンボ（直前の試合）','أقصى كومبو (آخر مباراة)');
d('Riprendi','Resume','Reanudar','Reprendre','Fortsetzen','Continuar','Продолжить','继续','再開','استئناف');
d('Salva ed esci al portale','Save and exit to portal','Guardar y salir al portal','Sauvegarder et retourner au portail','Speichern und zum Portal','Salvar e sair para o portal','Сохранить и выйти в портал','保存并返回门户','保存してポータルへ','حفظ والخروج إلى البوابة');

/* ---- bonus, kill feed, messaggi ---- */
d('Velocità','Speed','Velocidad','Vitesse','Tempo','Velocidade','Скорость','速度','スピード','السرعة');
d('Scudo','Shield','Escudo','Bouclier','Schild','Escudo','Щит','护盾','シールド','الدرع');
d('Magnete','Magnet','Imán','Aimant','Magnet','Ímã','Магнит','磁铁','マグネット','المغناطيس');
d('Crescita x2','Growth x2','Crecimiento x2','Croissance x2','Wachstum x2','Crescimento x2','Рост x2','成长 x2','成長 x2','نمو x2');
d('{0} attivo ({1}s)','{0} active ({1}s)','{0} activo ({1}s)','{0} actif ({1}s)','{0} aktiv ({1}s)','{0} ativo ({1}s)','{0} активен ({1}s)','{0} 已激活（{1}秒）','{0} 発動（{1}秒）','{0} نشط ({1}ث)');
d('Hai divorato {0} (+{1})','You ate {0} (+{1})','Devoraste a {0} (+{1})','Tu as dévoré {0} (+{1})','Du hast {0} gefressen (+{1})','Você devorou {0} (+{1})','Вы съели {0} (+{1})','你吃掉了 {0}（+{1}）','{0} を食べた (+{1})','التهمت {0} (+{1})');
d('+{0} massa','+{0} mass','+{0} de masa','+{0} de masse','+{0} Masse','+{0} de massa','+{0} массы','+{0} 质量','+{0} 質量','+{0} كتلة');
d('Partita iniziata','Game started','Partida iniciada','Partie lancée','Spiel gestartet','Partida iniciada','Игра началась','游戏开始','ゲーム開始','بدأت المباراة');
d('VIRUS!','VIRUS!','¡VIRUS!','VIRUS !','VIRUS!','VÍRUS!','ВИРУС!','病毒！','ウイルス！','فيروس!');
d('Zoom reimpostato','Zoom reset','Zoom restablecido','Zoom réinitialisé','Zoom zurückgesetzt','Zoom redefinido','Зум сброшен','缩放已重置','ズームをリセットしました','تمت إعادة ضبط التكبير');
d('Salvataggio in corso…','Saving…','Guardando…','Sauvegarde…','Speichern…','Salvando…','Сохранение…','正在保存…','保存中…','جارٍ الحفظ…');
d('Account sospeso','Account suspended','Cuenta suspendida','Compte suspendu','Konto gesperrt','Conta suspensa','Аккаунт заблокирован','账号已被封停','アカウント停止中','تم تعليق الحساب');
d('Sei silenziato in chat','You are muted in chat','Estás silenciado en el chat','Tu es muet dans le chat','Du bist im Chat stummgeschaltet','Você está silenciado no chat','Вы заглушены в чате','你已被禁言','チャットでミュート中','تم كتم صوتك في الدردشة');
d('Chat non disponibile','Chat unavailable','Chat no disponible','Chat indisponible','Chat nicht verfügbar','Chat indisponível','Чат недоступен','聊天不可用','チャットは利用できません','الدردشة غير متاحة');
d('Tasti ripristinati','Keys reset','Teclas restablecidas','Touches réinitialisées','Tasten zurückgesetzt','Teclas redefinidas','Клавиши сброшены','按键已重置','キーをリセットしました','تمت إعادة ضبط المفاتيح');
d('Impostazioni ripristinate','Settings reset','Ajustes restablecidos','Paramètres réinitialisés','Einstellungen zurückgesetzt','Configurações redefinidas','Настройки сброшены','设置已重置','設定をリセットしました','تمت إعادة ضبط الإعدادات');
d('Nomi','Names','Nombres','Noms','Namen','Nomes','Имена','名字','名前','الأسماء');
d('Massa','Mass','Masa','Masse','Masse','Massa','Масса','质量','質量','الكتلة');
d('Griglia','Grid','Cuadrícula','Grille','Raster','Grade','Сетка','网格','グリッド','الشبكة');
d('{0} attivi','{0} on','{0} activados','{0} activés','{0} an','{0} ativados','{0}: вкл','{0}已开启','{0}オン','{0} مفعّل');
d('{0} disattivati','{0} off','{0} desactivados','{0} désactivés','{0} aus','{0} desativados','{0}: выкл','{0}已关闭','{0}オフ','{0} معطّل');
d('Massa {0}','Mass {0}','Masa {0}','Masse {0}','Masse {0}','Massa {0}','Масса {0}','质量 {0}','質量 {0}','الكتلة {0}');
d('Cellule {0}','Cells {0}','Células {0}','Cellules {0}','Zellen {0}','Células {0}','Клетки {0}','细胞 {0}','セル {0}','الخلايا {0}');
d('Un rivale più grande ti ha divorato','A bigger rival ate you','Un rival más grande te devoró','Un rival plus gros t’a dévoré','Ein größerer Gegner hat dich gefressen','Um rival maior devorou você','Тебя съел соперник покрупнее','被更大的对手吃掉了','より大きな相手に食べられた','التهمك خصم أكبر');
d('Sei stato divorato da un giocatore online','You were eaten by an online player','Te devoró un jugador online','Tu as été dévoré par un joueur en ligne','Du wurdest von einem Online-Spieler gefressen','Você foi devorado por um jogador online','Тебя съел онлайн-игрок','被在线玩家吃掉了','オンラインプレイヤーに食べられた','التهمك لاعب عبر الإنترنت');
d('Partita terminata','Game over','Partida terminada','Partie terminée','Spiel beendet','Partida encerrada','Игра окончена','游戏结束','ゲーム終了','انتهت المباراة');
d('Divorato','Eaten','Devorado','Dévoré','Gefressen','Devorado','Съеден','被吃','食べられた','تم التهامك');
d('premi {0} per rigiocare','press {0} to play again','pulsa {0} para jugar de nuevo','appuie sur {0} pour rejouer','{0} drücken für neues Spiel','pressione {0} para jogar de novo','нажмите {0}, чтобы сыграть снова','按 {0} 再玩一次','{0} でもう一度','اضغط {0} للعب مجدداً');
d('Record {0}','Record {0}','Récord {0}','Record {0}','Rekord {0}','Recorde {0}','Рекорд {0}','纪录 {0}','記録 {0}','الرقم القياسي {0}');
d('Kill {0}','Kills {0}','Eliminaciones {0}','Éliminations {0}','Kills {0}','Abates {0}','Убийства {0}','击杀 {0}','キル {0}','القتلات {0}');
d('Cibo {0}','Food {0}','Comida {0}','Nourriture {0}','Futter {0}','Comida {0}','Еда {0}','食物 {0}','エサ {0}','الطعام {0}');
d('Combo max {0}','Max combo {0}','Combo máx. {0}','Combo max {0}','Max. Combo {0}','Combo máx. {0}','Макс. комбо {0}','最大连击 {0}','最大コンボ {0}','أقصى كومبو {0}');
d('Tempo {0}','Time {0}','Tiempo {0}','Temps {0}','Zeit {0}','Tempo {0}','Время {0}','时间 {0}','時間 {0}','الوقت {0}');
d('LIVELLO SU!','LEVEL UP!','¡SUBES DE NIVEL!','NIVEAU SUPÉRIEUR !','LEVEL UP!','SUBIU DE NÍVEL!','НОВЫЙ УРОВЕНЬ!','升级！','レベルアップ！','ارتقاء المستوى!');

/* ---- shop, collezione, zero pass ---- */
d('Shop','Shop','Tienda','Boutique','Shop','Loja','Магазин','商店','ショップ','المتجر');
d('Potenziamenti','Upgrades','Mejoras','Améliorations','Verbesserungen','Melhorias','Улучшения','强化','アップグレード','الترقيات');
d('Skin','Skins','Skins','Skins','Skins','Skins','Скины','皮肤','スキン','الأشكال');
d('Bot','Bots','Bots','Bots','Bots','Bots','Боты','机器人','ボット','الروبوتات');
d('Accedi dal portale per usare shop, skin e Zero Pass.','Log in from the portal to use the shop, skins and Zero Pass.','Inicia sesión desde el portal para usar la tienda, las skins y el Zero Pass.','Connecte-toi depuis le portail pour utiliser la boutique, les skins et le Zero Pass.','Melde dich über das Portal an, um Shop, Skins und Zero Pass zu nutzen.','Entre pelo portal para usar a loja, as skins e o Zero Pass.','Войдите через портал, чтобы пользоваться магазином, скинами и Zero Pass.','请从门户登录以使用商店、皮肤和 Zero Pass。','ポータルからログインすると、ショップ、スキン、Zero Passを利用できます。','سجّل الدخول من البوابة لاستخدام المتجر والأشكال وZero Pass.');
d('Economia non raggiungibile: {0}','Economy unavailable: {0}','Economía no disponible: {0}','Économie indisponible : {0}','Wirtschaft nicht erreichbar: {0}','Economia indisponível: {0}','Экономика недоступна: {0}','经济系统无法连接：{0}','エコノミーに接続できません: {0}','الاقتصاد غير متاح: {0}');
d('Riprova','Retry','Reintentar','Réessayer','Erneut versuchen','Tentar de novo','Повторить','重试','再試行','إعادة المحاولة');
d('Posseduta','Owned','Poseída','Possédée','Im Besitz','Possuída','Куплено','已拥有','所持済み','مملوكة');
d('Posseduto','Owned','Poseído','Possédé','Im Besitz','Possuído','Куплено','已拥有','所持済み','مملوك');
d('Acquista','Buy','Comprar','Acheter','Kaufen','Comprar','Купить','购买','購入','شراء');
d('Acquisto riuscito','Purchase successful','Compra realizada','Achat réussi','Kauf erfolgreich','Compra concluída','Покупка выполнена','购买成功','購入しました','تمت عملية الشراء');
d('Esclusiva Zero Pass (livello 30 Premium)','Zero Pass exclusive (Premium level 30)','Exclusiva del Zero Pass (nivel 30 Premium)','Exclusivité Zero Pass (niveau 30 Premium)','Zero-Pass-exklusiv (Premium Level 30)','Exclusiva do Zero Pass (nível 30 Premium)','Эксклюзив Zero Pass (30 уровень Premium)','Zero Pass 专属（Premium 30 级）','Zero Pass限定（プレミアムLv30）','حصري لـ Zero Pass (المستوى 30 بريميوم)');
d('Gli acquisti di Zero Coin non sono ancora attivi: intanto guadagnali giocando!','Zero Coin purchases are not active yet: earn them by playing in the meantime!','Las compras de Zero Coin aún no están activas: ¡mientras tanto, gánalas jugando!','Les achats de Zero Coin ne sont pas encore actifs : gagne-en en jouant !','Zero-Coin-Käufe sind noch nicht aktiv – verdiene sie in der Zwischenzeit durch Spielen!','As compras de Zero Coin ainda não estão ativas: por enquanto, ganhe jogando!','Покупка Zero Coin пока недоступна: зарабатывайте их в игре!','Zero Coin 购买暂未开放，先通过游戏赚取吧！','Zero Coinの購入はまだ利用できません。今はプレイして稼ぎましょう！','شراء Zero Coin غير متاح بعد: اكسبها باللعب في الوقت الحالي!');
d('Livello {0} / 30','Level {0} / 30','Nivel {0} / 30','Niveau {0} / 30','Level {0} / 30','Nível {0} / 30','Уровень {0} / 30','等级 {0} / 30','レベル {0} / 30','المستوى {0} / 30');
d('{0} punti','{0} points','{0} puntos','{0} points','{0} Punkte','{0} pontos','{0} очков','{0} 点','{0} ポイント','{0} نقطة');
d('Sblocca Premium','Unlock Premium','Desbloquear Premium','Débloquer Premium','Premium freischalten','Desbloquear Premium','Открыть Premium','解锁 Premium','プレミアムを解放','فتح بريميوم');
d('Zero Pass Premium attivo','Zero Pass Premium active','Zero Pass Premium activo','Zero Pass Premium actif','Zero Pass Premium aktiv','Zero Pass Premium ativo','Zero Pass Premium активен','Zero Pass Premium 已激活','Zero Pass プレミアム有効','Zero Pass بريميوم مفعّل');
d('Zero Pass Premium sbloccato!','Zero Pass Premium unlocked!','¡Zero Pass Premium desbloqueado!','Zero Pass Premium débloqué !','Zero Pass Premium freigeschaltet!','Zero Pass Premium desbloqueado!','Zero Pass Premium открыт!','Zero Pass Premium 已解锁！','Zero Pass プレミアムを解放しました！','تم فتح Zero Pass بريميوم!');
d('Ricevuto: {0}','Received: {0}','Recibido: {0}','Reçu : {0}','Erhalten: {0}','Recebido: {0}','Получено: {0}','已获得：{0}','獲得: {0}','تم الاستلام: {0}');
d('Collezione','Collection','Colección','Collection','Sammlung','Coleção','Коллекция','收藏','コレクション','المجموعة');
d('Predefinita (colore profilo)','Default (profile color)','Predeterminada (color del perfil)','Par défaut (couleur du profil)','Standard (Profilfarbe)','Padrão (cor do perfil)','По умолчанию (цвет профиля)','默认（资料颜色）','デフォルト（プロフィールの色）','الافتراضي (لون الملف الشخصي)');
d('In uso','In use','En uso','Utilisée','In Benutzung','Em uso','Используется','使用中','使用中','قيد الاستخدام');
d('Equipaggia','Equip','Equipar','Équiper','Ausrüsten','Equipar','Надеть','装备','装備','تجهيز');
d('Usa','Use','Usar','Utiliser','Benutzen','Usar','Использовать','使用','使う','استخدام');
d('Approvata','Approved','Aprobada','Approuvée','Genehmigt','Aprovada','Одобрено','已通过','承認済み','تمت الموافقة');
d('In attesa di approvazione (visibile solo a te)','Pending approval (visible only to you)','Pendiente de aprobación (solo visible para ti)','En attente d’approbation (visible uniquement par toi)','Wartet auf Genehmigung (nur für dich sichtbar)','Aguardando aprovação (visível só para você)','Ожидает одобрения (видно только вам)','等待审核（仅你可见）','承認待ち（自分のみ表示）','بانتظار الموافقة (مرئي لك فقط)');
d('Rifiutata','Rejected','Rechazada','Refusée','Abgelehnt','Rejeitada','Отклонено','已拒绝','却下','مرفوضة');
d('Carica una skin personalizzata','Upload a custom skin','Sube una skin personalizada','Importer une skin personnalisée','Eigene Skin hochladen','Envie uma skin personalizada','Загрузить свой скин','上传自定义皮肤','カスタムスキンをアップロード','ارفع شكلاً مخصصاً');
d('Nome skin','Skin name','Nombre de la skin','Nom de la skin','Skin-Name','Nome da skin','Название скина','皮肤名称','スキン名','اسم الشكل');
d('Carica','Upload','Subir','Envoyer','Hochladen','Enviar','Загрузить','上传','アップロード','رفع');
d('Scegli un file','Choose a file','Elige un archivo','Choisis un fichier','Datei auswählen','Escolha um arquivo','Выберите файл','请选择文件','ファイルを選択','اختر ملفاً');
d('Bot alleati','Ally bots','Bots aliados','Bots alliés','Verbündete Bots','Bots aliados','Боты-союзники','盟友机器人','味方ボット','الروبوتات الحليفة');
d('Compra un bot nello Shop: scenderà in campo con te a ogni partita.','Buy a bot in the Shop: it will join you in every game.','Compra un bot en la Tienda: te acompañará en cada partida.','Achète un bot dans la boutique : il t’accompagnera à chaque partie.','Kaufe einen Bot im Shop: Er begleitet dich in jedem Spiel.','Compre um bot na Loja: ele vai com você em toda partida.','Купите бота в магазине: он будет играть с вами в каждой игре.','在商店购买机器人，每局都会与你并肩作战。','ショップでボットを購入すると、毎試合一緒に戦います。','اشترِ روبوتاً من المتجر: سينضم إليك في كل مباراة.');
d('Le modifiche si vedono subito; i bot entrano dalla prossima partita.','Changes apply immediately; bots join from the next game.','Los cambios se ven al instante; los bots entran desde la próxima partida.','Les changements sont immédiats ; les bots arrivent dès la prochaine partie.','Änderungen gelten sofort; Bots kommen ab der nächsten Partie dazu.','As mudanças valem na hora; os bots entram a partir da próxima partida.','Изменения применяются сразу; боты появятся со следующей игры.','更改立即生效；机器人将在下一局加入。','変更はすぐに反映され、ボットは次の試合から参加します。','تظهر التغييرات فوراً؛ وينضم الروبوتات من المباراة القادمة.');
d('Gli upload di skin sono momentaneamente disattivati.','Skin uploads are temporarily disabled.','La subida de skins está desactivada temporalmente.','L’envoi de skins est temporairement désactivé.','Skin-Uploads sind vorübergehend deaktiviert.','O envio de skins está temporariamente desativado.','Загрузка скинов временно отключена.','皮肤上传暂时关闭。','スキンのアップロードは一時的に無効です。','رفع الأشكال معطّل مؤقتاً.');

/* ---- motore ---- */
let lang = 'it';
try { const s = localStorage.getItem(KEY); if (LANGS.some(l => l[0] === s)) lang = s; } catch (_) {}
let ti = Math.max(0, LANGS.findIndex(l => l[0] === lang) - 1);   /* indice nell'array delle traduzioni */

const EXACT = new Map(), TPL = [];
const esc = s => s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
function addEntry(it, tr) {
  if (/\{\d\}/.test(it)) {
    const re = new RegExp('^' + it.split(/\{\d\}/).map(esc).join('(.+?)') + '$', 's');
    TPL.push([re, tr]);
  } else EXACT.set(it, tr);
}
for (const [it, tr] of D) addEntry(it, tr);
/* nome del bonus + tempo residuo, es. "Velocità 7.9s" */
for (const k of ['Velocità', 'Scudo', 'Magnete', 'Crescita x2']) addEntry(k + ' {0}', EXACT.get(k).map(x => x + ' {0}'));

const fill = (t, m) => { let o = t; for (let i = 1; i < m.length; i++) o = o.replace('{' + (i - 1) + '}', () => core(m[i])); return o; };
function one(t) {
  const e = EXACT.get(t); if (e) return e[ti];
  for (const [re, tr] of TPL) { const m = re.exec(t); if (m) return fill(tr[ti], m); }
  const m = t.match(/^([^\p{L}\p{N}{]*)(.*?)([^\p{L}\p{N}}]*)$/su);   /* emoji o simboli ai bordi */
  if (m && m[2] && (m[1] || m[3])) {
    const e2 = EXACT.get(m[2]); if (e2) return m[1] + e2[ti] + m[3];
    for (const [re, tr] of TPL) { const mm = re.exec(m[2]); if (mm) return m[1] + fill(tr[ti], mm) + m[3]; }
  }
  return null;
}
function core(t) {
  const r = one(t); if (r != null) return r;
  if (t.includes(' · ')) return t.split(' · ').map(p => one(p) ?? p).join(' · ');
  return t;
}
function tr(s) {
  if (lang === 'it' || !s) return s;
  const t = s.trim(); if (!t) return s;
  const o = core(t);
  return o === t ? s : s.slice(0, s.indexOf(t)) + o + s.slice(s.indexOf(t) + t.length);
}
window.ZA_T = tr;
window.ZA_LANG = () => lang;

/* ---- traduzione del DOM (testi e attributi), anche per i contenuti creati dopo ---- */
const SKIP = '#playerName,#leaders,#arenaChatBody,#skinStore,#zaLang,script,style,textarea';
const ATTRS = ['placeholder', 'title', 'aria-label'];
const TS = new WeakMap(), AS = new WeakMap();
const skip = n => { const e = n.nodeType === 1 ? n : n.parentElement; return !e || !!e.closest(SKIP); };
function doText(n) {
  if (skip(n)) return;
  const v = n.nodeValue, rec = TS.get(n);
  if (rec && v === rec.o) {                       /* è già una nostra traduzione */
    if (lang === 'it') { n.nodeValue = rec.s; TS.delete(n); return; }
    const o = tr(rec.s); if (o !== rec.o) { rec.o = o; n.nodeValue = o; }
    return;
  }
  const o = tr(v);                                 /* testo nuovo scritto dal gioco (in italiano) */
  if (o !== v) { TS.set(n, { s: v, o }); n.nodeValue = o; } else TS.delete(n);
}
function doAttr(e, a) {
  if (skip(e) || !e.hasAttribute(a)) return;
  const v = e.getAttribute(a); let m = AS.get(e); if (!m) AS.set(e, m = {});
  const rec = m[a];
  if (rec && v === rec.o) {
    if (lang === 'it') { e.setAttribute(a, rec.s); delete m[a]; return; }
    const o = tr(rec.s); if (o !== rec.o) { rec.o = o; e.setAttribute(a, o); }
    return;
  }
  const o = tr(v);
  if (o !== v) { m[a] = { s: v, o }; e.setAttribute(a, o); } else delete m[a];
}
function walk(root) {
  if (root.nodeType === 3) { doText(root); return; }
  if (root.nodeType !== 1) return;
  const w = document.createTreeWalker(root, NodeFilter.SHOW_TEXT);
  let n; while ((n = w.nextNode())) doText(n);
  const sel = ATTRS.map(a => '[' + a + ']').join(',');
  if (root.matches(sel)) ATTRS.forEach(a => doAttr(root, a));
  root.querySelectorAll(sel).forEach(e => ATTRS.forEach(a => doAttr(e, a)));
}
const mo = new MutationObserver(ms => {
  for (const m of ms) {
    if (m.type === 'characterData') doText(m.target);
    else if (m.type === 'attributes') doAttr(m.target, m.attributeName);
    else m.addedNodes.forEach(walk);
  }
});

/* ---- menu a bandierine ---- */
const emoji = cc => String.fromCodePoint(...[...cc.toUpperCase()].map(c => 127397 + c.charCodeAt(0)));
function flag(cc, w) {
  const i = document.createElement('img');
  i.src = 'https://flagcdn.com/w40/' + cc + '.png'; i.alt = ''; i.width = w; i.height = Math.round(w * 0.75); i.loading = 'lazy';
  i.style.cssText = 'border-radius:3px;object-fit:cover;box-shadow:0 0 0 1px rgba(0,0,0,.2)';
  i.onerror = () => { const s = document.createElement('span'); s.textContent = emoji(cc); s.style.fontSize = (w * 0.9) + 'px'; i.replaceWith(s); };
  return i;
}
let btn, list;
function paintBtn() {
  const L = LANGS.find(l => l[0] === lang);
  btn.textContent = ''; btn.appendChild(flag(L[2], 26)); btn.title = L[1];
  list.querySelectorAll('.zl-i').forEach(x => x.classList.toggle('on', x.dataset.c === lang));
}
function setLang(c) {
  if (!LANGS.some(l => l[0] === c)) return;
  lang = c; ti = Math.max(0, LANGS.findIndex(l => l[0] === c) - 1);
  try { localStorage.setItem(KEY, c); } catch (_) {}
  document.documentElement.lang = c;
  walk(document.body);
  paintBtn();
  window.dispatchEvent(new CustomEvent('zeroagar:lang', { detail: c }));
}
function buildMenu() {
  const css = document.createElement('style');
  css.textContent = '#zaLang{position:relative;display:inline-block}#zaLang #zaLangBtn{display:flex;align-items:center;justify-content:center}' +
    '#zaLangList{position:absolute;right:0;bottom:54px;min-width:170px;background:#fff;color:#222;border-radius:12px;box-shadow:0 6px 24px rgba(0,0,0,.3);padding:6px;display:none;z-index:40}' +
    '#zaLangList.open{display:block}.zl-i{display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:8px;cursor:pointer;font:600 14px Arial,sans-serif}' +
    '.zl-i:hover{background:rgba(19,184,239,.15)}.zl-i.on{background:#13b8ef;color:#fff}' +
    'body.dark #zaLangList{background:#1c2129;color:#e8ecf1}';
  document.head.appendChild(css);
  const wrap = document.createElement('div'); wrap.id = 'zaLang';
  btn = document.createElement('button'); btn.id = 'zaLangBtn'; btn.type = 'button';
  list = document.createElement('div'); list.id = 'zaLangList';
  for (const [c, name, cc] of LANGS) {
    const it = document.createElement('div'); it.className = 'zl-i'; it.dataset.c = c;
    it.appendChild(flag(cc, 24)); it.appendChild(document.createTextNode(name));
    it.onclick = () => { setLang(c); list.classList.remove('open'); };
    list.appendChild(it);
  }
  btn.onclick = e => { e.stopPropagation(); list.classList.toggle('open'); };
  document.addEventListener('click', e => { if (!e.target.closest('#zaLang')) list.classList.remove('open'); });
  addEventListener('keydown', e => { if (e.key === 'Escape') list.classList.remove('open'); }, true);
  wrap.appendChild(btn); wrap.appendChild(list);
  const q = document.getElementById('quick');
  if (q) q.insertBefore(wrap, q.firstChild);
  else { wrap.style.cssText = 'position:fixed;right:14px;bottom:14px;z-index:40'; document.body.appendChild(wrap); }
}

function init() {
  buildMenu();
  document.documentElement.lang = lang;
  paintBtn();
  walk(document.body);
  mo.observe(document.body, { subtree: true, childList: true, characterData: true, attributes: true, attributeFilter: ATTRS });
}
if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
