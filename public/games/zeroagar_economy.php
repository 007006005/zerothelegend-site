<?php
declare(strict_types=1);

/*
 * ZeroAgar Economy API
 * Zero Coin, negozio, potenziamenti, bot, skin (anche custom), Zero Pass, eventi,
 * pannello amministrazione, ban/mute, premi.
 *
 * Mettilo nella stessa cartella di zeroagar_classic.php.
 * Dati in SQLite (zeroagar_data/economy.sqlite), creati in automatico (serve PDO SQLite + GD non richiesto).
 * L'utente viene riconosciuto chiamando il tuo ../api/api.php?action=me con i cookie della sessione.
 */

/* Cartella dati: usa la prima posizione scrivibile tra quelle elencate */
function za_pick_dir(): string
{
    $cands = [
        __DIR__ . '/zeroagar_data',
        dirname(__DIR__) . '/uploads/zeroagar_data',
        sys_get_temp_dir() . '/zeroagar_data',
    ];
    foreach ($cands as $d) {
        if (!is_dir($d)) {
            @mkdir($d, 0775, true);
        }
        if (is_dir($d) && is_writable($d)) {
            return $d;
        }
    }
    return $cands[0];
}
define('ZA_DATA_DIR', za_pick_dir());
const ZA_ME_URL = '';   // lascia vuoto per usare automaticamente ../api/api.php?action=me
const ZA_ADMIN_ROLES = ['admin', 'administrator', 'owner', 'superadmin', 'founder'];
const ZA_MOD_ROLES = ['mod', 'moderator', 'staff'];
const ZA_DIAG = true;   // metti false dopo aver risolto
const ZA_PASS_TIERS = 30;
const ZA_PASS_STEP = 100;

/* ------------------------------------------------------------------ utilità */
function out(array $d): void
{
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode(['success' => true] + $d, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function fail(string $m, int $code = 400): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode(['success' => false, 'error' => $m], JSON_UNESCAPED_UNICODE);
    exit;
}

function za_boot(): void
{
    if (!is_dir(ZA_DATA_DIR)) {
        @mkdir(ZA_DATA_DIR, 0750, true);
    }
    if (!is_dir(ZA_DATA_DIR . '/skins')) {
        @mkdir(ZA_DATA_DIR . '/skins', 0750, true);
    }
    $ht = ZA_DATA_DIR . '/.htaccess';
    if (!is_file($ht)) {
        @file_put_contents($ht, "<IfModule mod_authz_core.c>\nRequire all denied\n</IfModule>\n<IfModule !mod_authz_core.c>\nDeny from all\n</IfModule>\n");
    }
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    $pdo = new PDO('sqlite:' . ZA_DATA_DIR . '/economy.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA journal_mode=WAL');
    $pdo->exec('PRAGMA busy_timeout=5000');
    $pdo->exec(<<<'SQL'
CREATE TABLE IF NOT EXISTS players(
  uid TEXT PRIMARY KEY, username TEXT NOT NULL DEFAULT '', role TEXT NOT NULL DEFAULT 'user',
  coins INTEGER NOT NULL DEFAULT 0, xp INTEGER NOT NULL DEFAULT 0,
  upgrades TEXT NOT NULL DEFAULT '{}', owned TEXT NOT NULL DEFAULT '{"skins":[],"bots":[]}',
  equipped TEXT NOT NULL DEFAULT 'default',
  pass_points INTEGER NOT NULL DEFAULT 0, pass_premium INTEGER NOT NULL DEFAULT 0,
  pass_free TEXT NOT NULL DEFAULT '[]', pass_prem TEXT NOT NULL DEFAULT '[]',
  muted_until INTEGER NOT NULL DEFAULT 0, banned_until INTEGER NOT NULL DEFAULT 0, ban_reason TEXT NOT NULL DEFAULT '',
  last_game INTEGER NOT NULL DEFAULT 0, games INTEGER NOT NULL DEFAULT 0,
  created INTEGER NOT NULL DEFAULT 0, last_seen INTEGER NOT NULL DEFAULT 0
);
CREATE INDEX IF NOT EXISTS idx_players_name ON players(username);
CREATE TABLE IF NOT EXISTS inbox(id INTEGER PRIMARY KEY AUTOINCREMENT, uid TEXT, message TEXT, created INTEGER, is_read INTEGER DEFAULT 0);
CREATE TABLE IF NOT EXISTS events(
  id INTEGER PRIMARY KEY AUTOINCREMENT, title TEXT, descr TEXT, type TEXT, mult REAL DEFAULT 1,
  start_ts INTEGER, end_ts INTEGER, enabled INTEGER DEFAULT 1
);
CREATE TABLE IF NOT EXISTS skins(
  id INTEGER PRIMARY KEY AUTOINCREMENT, uid TEXT, name TEXT, file TEXT, mime TEXT,
  status TEXT DEFAULT 'pending', w INTEGER, h INTEGER, bytes INTEGER, created INTEGER
);
CREATE TABLE IF NOT EXISTS settings(k TEXT PRIMARY KEY, v TEXT);
CREATE TABLE IF NOT EXISTS logs(id INTEGER PRIMARY KEY AUTOINCREMENT, ts INTEGER, actor TEXT, action TEXT, target TEXT, detail TEXT);
CREATE TABLE IF NOT EXISTS orders(
  id INTEGER PRIMARY KEY AUTOINCREMENT, uid TEXT, pack TEXT, coins INTEGER, cents INTEGER,
  status TEXT DEFAULT 'pending', created INTEGER, paid_at INTEGER DEFAULT 0
);
CREATE TABLE IF NOT EXISTS txns(id INTEGER PRIMARY KEY AUTOINCREMENT, uid TEXT, ts INTEGER, kind TEXT, amount INTEGER, detail TEXT);
CREATE TABLE IF NOT EXISTS sess_cache(h TEXT PRIMARY KEY, j TEXT, exp INTEGER);
SQL);
    $pdo->prepare('INSERT OR IGNORE INTO settings(k,v) VALUES(?,?)')->execute(['webhook_secret', bin2hex(random_bytes(16))]);
    return $pdo;
}

/* ------------------------------------------------------------------ impostazioni */
function setting_defaults(): array
{
    return [
        'skin_upload_enabled' => '1',
        'skin_auto_approve' => '0',
        'skin_max_kb' => '1024',
        'skin_max_per_user' => '5',
        'payments_mode' => 'off',      // off | demo | external
        'payment_url' => '',
        'coin_mult' => '1',
        'xp_mult' => '1',
        'pass_mult' => '1',
        'pass_premium_price' => '1200',
        'pass_season' => 'Stagione 1',
        'pass_ends' => '',
        'shop_discount' => '0',
        'chat_enabled' => '1',
        'chat_slowmode' => '2',
        'chat_blocked_words' => '',
    ];
}

function settings_all(bool $reset = false): array
{
    static $cache = null;
    if ($reset || $cache === null) {
        $cache = setting_defaults();
        foreach (db()->query('SELECT k,v FROM settings') as $r) {
            $cache[$r['k']] = (string)$r['v'];
        }
    }
    return $cache;
}

function setting(string $k): string
{
    $a = settings_all();
    return (string)($a[$k] ?? '');
}

function public_cfg(): array
{
    $keys = ['skin_upload_enabled', 'skin_max_kb', 'skin_max_per_user', 'skin_auto_approve', 'payments_mode',
        'chat_enabled', 'chat_slowmode', 'chat_blocked_words', 'shop_discount'];
    $o = [];
    foreach ($keys as $k) {
        $o[$k] = setting($k);
    }
    return $o;
}

/* ------------------------------------------------------------------ identità utente */
function me_url(): string
{
    if (ZA_ME_URL !== '') {
        return ZA_ME_URL;
    }
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    $dir = rtrim(str_replace('\\', '/', dirname(dirname((string)($_SERVER['SCRIPT_NAME'] ?? '/')))), '/');
    return ($https ? 'https' : 'http') . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . $dir . '/api/api.php?action=me';
}

/* Chiamata HTTP verso api.php?action=me con i cookie dell'utente. Ritorna [codice HTTP, corpo] */
function me_request(string $url, string $cookie): array
{
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 6,
            CURLOPT_CONNECTTIMEOUT => 3,
            CURLOPT_HTTPHEADER => ['Cookie: ' . $cookie, 'Accept: application/json'],
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_FOLLOWLOCATION => true,   // molti host forzano http -> https
            CURLOPT_MAXREDIRS => 3,
        ]);
        $raw = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        return [$code, is_string($raw) ? $raw : ''];
    }
    $ctx = stream_context_create(['http' => ['header' => "Cookie: $cookie\r\nAccept: application/json\r\n", 'timeout' => 6, 'follow_location' => 1]]);
    $raw = @file_get_contents($url, false, $ctx);
    return [is_string($raw) ? 200 : 0, is_string($raw) ? $raw : ''];
}

function fetch_me(string $cookie): ?array
{
    $url = me_url();
    $alt = strpos($url, 'https://') === 0 ? 'http://' . substr($url, 8) : 'https://' . substr($url, 7);
    $j = null;
    foreach ([$url, $alt] as $u) {
        [, $raw] = me_request($u, $cookie);
        $j = json_decode($raw, true);
        if (is_array($j)) {
            break;
        }
    }
    if (!is_array($j)) {
        return null;
    }
    $u = is_array($j['user'] ?? null) ? $j['user'] : [];
    $p = is_array($j['profile'] ?? null) ? $j['profile'] : [];
    $m = array_merge($u, $p);
    if (!$m) {
        return null;
    }
    $name = trim((string)($m['username'] ?? $m['name'] ?? ''));
    $id = $m['id'] ?? $m['user_id'] ?? $m['uid'] ?? null;
    if ($name === '' && ($id === null || $id === '')) {
        return null;
    }
    $uid = ($id !== null && $id !== '') ? (string)$id : 'n:' . strtolower($name);
    return ['uid' => $uid, 'name' => mb_substr($name !== '' ? $name : $uid, 0, 40), 'role' => strtolower((string)($m['role'] ?? 'user'))];
}

/* Diagnostica: apri zeroagar_economy.php?action=diag nel browser (da loggato). Poi puoi rimettere ZA_DIAG a false. */
function diag(): void
{
    $cookie = (string)($_SERVER['HTTP_COOKIE'] ?? '');
    $url = me_url();
    [$code, $raw] = $cookie !== '' ? me_request($url, $cookie) : [0, ''];
    $j = json_decode($raw, true);
    $dbOk = true;
    $dbErr = '';
    try {
        db();
    } catch (Throwable $e) {
        $dbOk = false;
        $dbErr = $e->getMessage();
    }
    out(['diag' => [
        'php' => PHP_VERSION,
        'me_url' => $url,
        'cookie_ricevuto' => $cookie !== '',
        'me_http_code' => $code,
        'me_json_valido' => is_array($j),
        'me_ruolo' => is_array($j) ? (string)(($j['profile']['role'] ?? $j['user']['role'] ?? '(nessun ruolo)')) : null,
        'curl' => function_exists('curl_init'),
        'pdo_sqlite' => in_array('sqlite', PDO::getAvailableDrivers(), true),
        'cartella_dati' => ZA_DATA_DIR,
        'cartella_dati_scrivibile' => is_dir(ZA_DATA_DIR) && is_writable(ZA_DATA_DIR),
        'db_ok' => $dbOk,
        'db_errore' => $dbErr,
        'ruoli_admin' => ZA_ADMIN_ROLES,
        'ruoli_mod' => ZA_MOD_ROLES,
    ]]);
}

function resolve_user(): ?array
{
    $cookie = (string)($_SERVER['HTTP_COOKIE'] ?? '');
    if ($cookie === '') {
        return null;
    }
    $db = db();
    $h = hash('sha256', $cookie);
    $st = $db->prepare('SELECT j FROM sess_cache WHERE h=? AND exp>?');
    $st->execute([$h, time()]);
    $j = $st->fetchColumn();
    if ($j === false) {
        $me = fetch_me($cookie);
        if ($me) {
            $db->prepare('INSERT OR REPLACE INTO sess_cache(h,j,exp) VALUES(?,?,?)')->execute([$h, json_encode($me), time() + 45]);
        }
        if (random_int(1, 50) === 1) {
            $db->exec('DELETE FROM sess_cache WHERE exp<' . time());
        }
    } else {
        $me = json_decode((string)$j, true);
    }
    if (!is_array($me) || empty($me['uid'])) {
        return null;
    }
    $me['admin'] = in_array($me['role'], ZA_ADMIN_ROLES, true);
    $me['mod'] = in_array($me['role'], ZA_MOD_ROLES, true);
    return $me;
}

/* ------------------------------------------------------------------ giocatori */
function level_from_xp(int $xp): int
{
    return max(1, min(999, 1 + intdiv(max(0, $xp), 1000)));
}

function get_player(array $me): array
{
    $db = db();
    $st = $db->prepare('SELECT * FROM players WHERE uid=?');
    $st->execute([$me['uid']]);
    $p = $st->fetch();
    $now = time();
    if (!$p) {
        $db->prepare('INSERT INTO players(uid,username,role,created,last_seen) VALUES(?,?,?,?,?)')
            ->execute([$me['uid'], $me['name'], $me['role'], $now, $now]);
        $st->execute([$me['uid']]);
        $p = $st->fetch();
    } elseif ($p['username'] !== $me['name'] || $p['role'] !== $me['role'] || $now - (int)$p['last_seen'] > 60) {
        $db->prepare('UPDATE players SET username=?, role=?, last_seen=? WHERE uid=?')->execute([$me['name'], $me['role'], $now, $me['uid']]);
        $p['username'] = $me['name'];
        $p['role'] = $me['role'];
        $p['last_seen'] = $now;
    }
    return $p;
}

function target(string $uid): array
{
    $st = db()->prepare('SELECT * FROM players WHERE uid=?');
    $st->execute([$uid]);
    $r = $st->fetch();
    if (!$r) {
        fail('Utente non trovato', 404);
    }
    return $r;
}

function savep(array $p): void
{
    $cols = ['username', 'role', 'coins', 'xp', 'upgrades', 'owned', 'equipped', 'pass_points', 'pass_premium', 'pass_free',
        'pass_prem', 'muted_until', 'banned_until', 'ban_reason', 'last_game', 'last_seen', 'games'];
    $set = implode(',', array_map(fn($c) => "$c=?", $cols));
    $vals = array_map(fn($c) => $p[$c], $cols);
    $vals[] = $p['uid'];
    db()->prepare("UPDATE players SET $set WHERE uid=?")->execute($vals);
}

function jget(array $p, string $k, array $def = []): array
{
    $v = json_decode((string)($p[$k] ?? ''), true);
    return is_array($v) ? $v : $def;
}

function setj(array &$p, string $k, array $v): void
{
    $p[$k] = json_encode($v, JSON_UNESCAPED_UNICODE);
}

function owned_of(array $p): array
{
    $o = jget($p, 'owned');
    $o['skins'] = array_values(array_filter((array)($o['skins'] ?? []), 'is_string'));
    $o['bots'] = array_values(array_filter((array)($o['bots'] ?? []), 'is_string'));
    return $o;
}

function is_banned(array $p): bool
{
    $b = (int)$p['banned_until'];
    return $b === -1 || $b > time();
}

function need_ok(array $p): void
{
    if (is_banned($p)) {
        fail('Sei bannato' . ($p['ban_reason'] !== '' ? ': ' . $p['ban_reason'] : ''), 403);
    }
}

function txn(string $uid, string $kind, int $amount, string $detail = ''): void
{
    db()->prepare('INSERT INTO txns(uid,ts,kind,amount,detail) VALUES(?,?,?,?,?)')->execute([$uid, time(), $kind, $amount, mb_substr($detail, 0, 200)]);
}

function msg(string $uid, string $text): void
{
    db()->prepare('INSERT INTO inbox(uid,message,created) VALUES(?,?,?)')->execute([$uid, mb_substr($text, 0, 300), time()]);
}

function alog(array $me, string $action, string $target, string $detail = ''): void
{
    db()->prepare('INSERT INTO logs(ts,actor,action,target,detail) VALUES(?,?,?,?,?)')
        ->execute([time(), $me['name'], $action, $target, mb_substr($detail, 0, 300)]);
}

/* ------------------------------------------------------------------ catalogo */
function catalog_base(): array
{
    return [
        'skins' => [
            'neon' => ['name' => 'Neon', 'price' => 400],
            'gold' => ['name' => 'Oro', 'price' => 800],
            'rainbow' => ['name' => 'Arcobaleno', 'price' => 1500],
            'lava' => ['name' => 'Lava', 'price' => 600],
            'ice' => ['name' => 'Ghiaccio', 'price' => 600],
            'toxic' => ['name' => 'Tossico', 'price' => 500],
            'sunset' => ['name' => 'Tramonto', 'price' => 450],
            'galaxy' => ['name' => 'Galassia', 'price' => 0, 'pass' => true],
        ],
        'upgrades' => [
            'start' => ['name' => 'Massa iniziale', 'desc' => '+10 massa alla nascita per livello', 'max' => 5, 'base' => 300],
            'speed' => ['name' => 'Velocità', 'desc' => '+4% velocità per livello', 'max' => 5, 'base' => 400],
            'magnet' => ['name' => 'Magnete passivo', 'desc' => 'Attira il cibo vicino', 'max' => 5, 'base' => 350],
            'dur' => ['name' => 'Durata bonus', 'desc' => '+12% durata dei power-up', 'max' => 5, 'base' => 450],
            'shield' => ['name' => 'Scudo iniziale', 'desc' => '+2s di scudo alla nascita', 'max' => 5, 'base' => 500],
            'merge' => ['name' => 'Fusione rapida', 'desc' => '-4% timer di riunione', 'max' => 5, 'base' => 600],
            'xp' => ['name' => 'XP extra', 'desc' => '+10% XP a fine partita', 'max' => 5, 'base' => 700],
            'coin' => ['name' => 'Coin extra', 'desc' => '+10% Zero Coin a fine partita', 'max' => 5, 'base' => 900],
        ],
        'bots' => [
            'collector' => ['name' => 'Bot Raccoglitore', 'desc' => 'Raccoglie cibo e ti dona massa', 'price' => 900],
            'hunter' => ['name' => 'Bot Cacciatore', 'desc' => 'Caccia i bot nemici e ti dona massa', 'price' => 1400],
        ],
        'packs' => [
            's' => ['label' => 'Manciata', 'coins' => 500, 'cents' => 99],
            'm' => ['label' => 'Sacchetto', 'coins' => 1400, 'cents' => 199],
            'l' => ['label' => 'Cassa', 'coins' => 3800, 'cents' => 499],
            'xl' => ['label' => 'Forziere', 'coins' => 9000, 'cents' => 999],
        ],
    ];
}

function disc_factor(): float
{
    return max(0.0, 1 - (float)setting('shop_discount') / 100);
}

function catalog_for(array $p): array
{
    $c = catalog_base();
    $f = disc_factor();
    $up = jget($p, 'upgrades');
    foreach ($c['skins'] as $id => $s) {
        $c['skins'][$id]['price'] = (int)round($s['price'] * $f);
    }
    foreach ($c['upgrades'] as $id => $u) {
        $l = (int)($up[$id] ?? 0);
        $c['upgrades'][$id]['level'] = $l;
        $c['upgrades'][$id]['next'] = $l >= $u['max'] ? null : (int)round($u['base'] * ($l + 1) * $f);
    }
    foreach ($c['bots'] as $id => $b) {
        $c['bots'][$id]['price'] = (int)round($b['price'] * $f);
    }
    $c['pass'] = [
        'tiers' => pass_rewards(),
        'premium_price' => (int)setting('pass_premium_price'),
        'step' => ZA_PASS_STEP,
        'season' => setting('pass_season'),
        'ends' => setting('pass_ends'),
    ];
    return $c;
}

function reward_item(string $type, string $id): array
{
    $c = catalog_base();
    $grp = $type === 'skin' ? 'skins' : ($type === 'bot' ? 'bots' : 'upgrades');
    $pre = $type === 'skin' ? 'Skin ' : ($type === 'upgrade' ? 'Potenziamento ' : '');
    return ['type' => $type, 'id' => $id, 'label' => $pre . ($c[$grp][$id]['name'] ?? $id)];
}

function pass_rewards(): array
{
    static $r = null;
    if ($r !== null) {
        return $r;
    }
    $free = [10 => ['skin', 'ice'], 20 => ['skin', 'toxic']];
    $prem = [5 => ['skin', 'neon'], 8 => ['upgrade', 'start'], 10 => ['bot', 'collector'], 12 => ['upgrade', 'shield'],
        15 => ['skin', 'gold'], 18 => ['upgrade', 'merge'], 20 => ['bot', 'hunter'], 25 => ['skin', 'rainbow'], 30 => ['skin', 'galaxy']];
    $r = [];
    for ($t = 1; $t <= ZA_PASS_TIERS; $t++) {
        $fa = 15 + $t * 3;
        $pa = 40 + $t * 6;
        $r[$t] = [
            'free' => isset($free[$t]) ? reward_item($free[$t][0], $free[$t][1]) : ['type' => 'coins', 'amount' => $fa, 'label' => '+' . $fa . ' 🪙'],
            'prem' => isset($prem[$t]) ? reward_item($prem[$t][0], $prem[$t][1]) : ['type' => 'coins', 'amount' => $pa, 'label' => '+' . $pa . ' 🪙'],
        ];
    }
    return $r;
}

/* assegna un oggetto; ritorna una descrizione */
function grant_item(array &$p, string $type, string $id): string
{
    $c = catalog_base();
    if ($type === 'skin' && isset($c['skins'][$id])) {
        $o = owned_of($p);
        if (in_array($id, $o['skins'], true)) {
            $p['coins'] = (int)$p['coins'] + 250;
            return 'Skin ' . $c['skins'][$id]['name'] . ' già posseduta: +250 coin';
        }
        $o['skins'][] = $id;
        setj($p, 'owned', $o);
        return 'Skin ' . $c['skins'][$id]['name'];
    }
    if ($type === 'bot' && isset($c['bots'][$id])) {
        $o = owned_of($p);
        if (in_array($id, $o['bots'], true)) {
            $p['coins'] = (int)$p['coins'] + 300;
            return $c['bots'][$id]['name'] . ' già posseduto: +300 coin';
        }
        $o['bots'][] = $id;
        setj($p, 'owned', $o);
        return $c['bots'][$id]['name'];
    }
    if ($type === 'upgrade' && isset($c['upgrades'][$id])) {
        $u = jget($p, 'upgrades');
        $l = (int)($u[$id] ?? 0);
        if ($l >= $c['upgrades'][$id]['max']) {
            $p['coins'] = (int)$p['coins'] + 300;
            return $c['upgrades'][$id]['name'] . ' già al massimo: +300 coin';
        }
        $u[$id] = $l + 1;
        setj($p, 'upgrades', $u);
        return 'Potenziamento ' . $c['upgrades'][$id]['name'] . ' LV' . ($l + 1);
    }
    return '';
}

function grant_reward(array &$p, array $r): string
{
    if (($r['type'] ?? '') === 'coins') {
        $p['coins'] = (int)$p['coins'] + (int)$r['amount'];
        return '+' . (int)$r['amount'] . ' Zero Coin';
    }
    return grant_item($p, (string)$r['type'], (string)$r['id']);
}

function validate_equipped(array $p): string
{
    $e = (string)$p['equipped'];
    if ($e === 'default') {
        return 'default';
    }
    if (strpos($e, 'custom:') === 0) {
        $id = (int)substr($e, 7);
        $st = db()->prepare("SELECT status FROM skins WHERE id=? AND uid=?");
        $st->execute([$id, $p['uid']]);
        $s = $st->fetchColumn();
        return ($s === 'approved' || $s === 'pending') ? $e : 'default';
    }
    return in_array($e, owned_of($p)['skins'], true) ? $e : 'default';
}

function pub(array $p, array $me): array
{
    $pts = (int)$p['pass_points'];
    return [
        'username' => $p['username'],
        'coins' => (int)$p['coins'],
        'xp' => (int)$p['xp'],
        'level' => level_from_xp((int)$p['xp']),
        'upgrades' => (object)jget($p, 'upgrades'),
        'owned' => owned_of($p),
        'equipped' => validate_equipped($p),
        'pass' => [
            'points' => $pts,
            'tier' => min(ZA_PASS_TIERS, intdiv($pts, ZA_PASS_STEP)),
            'premium' => (bool)$p['pass_premium'],
            'free' => jget($p, 'pass_free'),
            'prem' => jget($p, 'pass_prem'),
        ],
        'muted_until' => (int)$p['muted_until'],
        'banned' => is_banned($p),
        'ban_reason' => (string)$p['ban_reason'],
        'ban_until' => (int)$p['banned_until'],
        'is_admin' => $me['admin'],
        'is_mod' => $me['mod'] || $me['admin'],
        'role' => $me['role'],
    ];
}

function my_skins(string $uid): array
{
    $st = db()->prepare('SELECT id,name,status,w,h,bytes FROM skins WHERE uid=? ORDER BY id DESC');
    $st->execute([$uid]);
    return $st->fetchAll();
}

function active_events(): array
{
    $n = time();
    $st = db()->prepare('SELECT id,title,descr,type,mult,start_ts,end_ts FROM events WHERE enabled=1 AND start_ts<=? AND end_ts>=? ORDER BY id DESC');
    $st->execute([$n, $n]);
    $o = [];
    foreach ($st->fetchAll() as $r) {
        $o[] = ['id' => (int)$r['id'], 'title' => $r['title'], 'descr' => $r['descr'], 'type' => $r['type'],
            'mult' => (float)$r['mult'], 'start' => (int)$r['start_ts'], 'end' => (int)$r['end_ts']];
    }
    return $o;
}

function ev_mult(string $type): float
{
    $m = 1.0;
    foreach (active_events() as $e) {
        if ($e['type'] === $type) {
            $m *= max(0.1, $e['mult']);
        }
    }
    return $m;
}

function price_of(array $p, string $type, string $id): int
{
    $c = catalog_base();
    $f = disc_factor();
    if ($type === 'skin') {
        if (!isset($c['skins'][$id])) {
            fail('Skin inesistente');
        }
        if (!empty($c['skins'][$id]['pass'])) {
            fail('Skin esclusiva dello Zero Pass');
        }
        if (in_array($id, owned_of($p)['skins'], true)) {
            fail('Hai già questa skin');
        }
        return (int)round($c['skins'][$id]['price'] * $f);
    }
    if ($type === 'bot') {
        if (!isset($c['bots'][$id])) {
            fail('Bot inesistente');
        }
        if (in_array($id, owned_of($p)['bots'], true)) {
            fail('Hai già questo bot');
        }
        return (int)round($c['bots'][$id]['price'] * $f);
    }
    if ($type === 'upgrade') {
        if (!isset($c['upgrades'][$id])) {
            fail('Potenziamento inesistente');
        }
        $l = (int)(jget($p, 'upgrades')[$id] ?? 0);
        if ($l >= $c['upgrades'][$id]['max']) {
            fail('Potenziamento già al massimo');
        }
        return (int)round($c['upgrades'][$id]['base'] * ($l + 1) * $f);
    }
    fail('Tipo non valido');
    return 0;
}

/* ------------------------------------------------------------------ skin file */
function serve_skin(): void
{
    $id = (int)($_GET['id'] ?? 0);
    $st = db()->prepare('SELECT * FROM skins WHERE id=?');
    $st->execute([$id]);
    $row = $st->fetch();
    if (!$row) {
        http_response_code(404);
        exit;
    }
    if ($row['status'] !== 'approved') {
        $me = resolve_user();
        if (!$me || ($me['uid'] !== $row['uid'] && !$me['admin'] && !$me['mod'])) {
            http_response_code(404);
            exit;
        }
    }
    $path = ZA_DATA_DIR . '/skins/' . basename((string)$row['file']);
    if (!is_file($path)) {
        http_response_code(404);
        exit;
    }
    header('Content-Type: ' . $row['mime']);
    header('X-Content-Type-Options: nosniff');
    header("Content-Security-Policy: default-src 'none'; sandbox");
    header('Cache-Control: ' . ($row['status'] === 'approved' ? 'public, max-age=3600' : 'private, no-store'));
    header('Content-Length: ' . filesize($path));
    readfile($path);
    exit;
}

/* ------------------------------------------------------------------ webhook pagamenti */
function webhook(): void
{
    $secret = setting('webhook_secret');
    $given = (string)($_SERVER['HTTP_X_ZA_SECRET'] ?? '');
    if ($secret === '' || !hash_equals($secret, $given)) {
        fail('Non autorizzato', 403);
    }
    $b = json_decode((string)file_get_contents('php://input'), true);
    $oid = is_array($b) ? (int)($b['order'] ?? 0) : 0;
    $db = db();
    $db->beginTransaction();
    $st = $db->prepare('SELECT * FROM orders WHERE id=?');
    $st->execute([$oid]);
    $o = $st->fetch();
    if (!$o || $o['status'] !== 'pending') {
        $db->rollBack();
        fail('Ordine non valido o già pagato', 409);
    }
    $db->prepare('UPDATE players SET coins=coins+? WHERE uid=?')->execute([(int)$o['coins'], $o['uid']]);
    $db->prepare("UPDATE orders SET status='paid', paid_at=? WHERE id=?")->execute([time(), $oid]);
    $db->commit();
    txn((string)$o['uid'], 'purchase', (int)$o['coins'], 'ordine #' . $oid);
    msg((string)$o['uid'], 'Acquisto completato: +' . (int)$o['coins'] . ' Zero Coin');
    out(['order' => $oid]);
}

/* ------------------------------------------------------------------ main */
function main(): void
{
    za_boot();
    $action = (string)($_GET['action'] ?? '');
    if ($action === 'skin_file') {
        serve_skin();
    }
    if ($action === 'diag' && ZA_DIAG) {
        diag();
    }
    if ($action === 'webhook_credit') {
        webhook();
    }
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        fail('Metodo non consentito', 405);
    }
    if (($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') !== 'ZeroAgar') {
        fail('Richiesta non valida', 403);
    }
    $me = resolve_user();
    if (!$me) {
        fail('Accesso richiesto', 401);
    }
    $p = get_player($me);
    $b = json_decode((string)file_get_contents('php://input'), true);
    if (!is_array($b)) {
        $b = [];
    }
    if (strpos($action, 'admin_') === 0) {
        admin($action, $me, $b);
    }
    $db = db();
    $now = time();

    switch ($action) {
        case 'state':
            $st = $db->prepare('SELECT id,message FROM inbox WHERE uid=? AND is_read=0 ORDER BY id LIMIT 20');
            $st->execute([$p['uid']]);
            $rows = $st->fetchAll();
            $inbox = array_map(fn($r) => $r['message'], $rows);
            if ($rows) {
                $db->prepare('UPDATE inbox SET is_read=1 WHERE uid=? AND is_read=0')->execute([$p['uid']]);
            }
            out([
                'player' => pub($p, $me),
                'cat' => catalog_for($p),
                'cfg' => public_cfg(),
                'events' => active_events(),
                'custom' => my_skins($p['uid']),
                'inbox' => $inbox,
                'server_time' => $now,
            ]);

        case 'buy':
            need_ok($p);
            $type = (string)($b['type'] ?? '');
            $id = (string)($b['id'] ?? '');
            $price = price_of($p, $type, $id);
            if ((int)$p['coins'] < $price) {
                fail('Zero Coin insufficienti');
            }
            $p['coins'] = (int)$p['coins'] - $price;
            grant_item($p, $type, $id);
            savep($p);
            txn($p['uid'], 'buy', -$price, "$type:$id");
            out(['player' => pub($p, $me), 'cat' => catalog_for($p)]);

        case 'equip':
            need_ok($p);
            $s = (string)($b['skin'] ?? 'default');
            if ($s !== 'default') {
                if (strpos($s, 'custom:') === 0) {
                    $st = $db->prepare("SELECT status FROM skins WHERE id=? AND uid=?");
                    $st->execute([(int)substr($s, 7), $p['uid']]);
                    $stt = $st->fetchColumn();
                    if ($stt !== 'approved' && $stt !== 'pending') {
                        fail('Skin non disponibile');
                    }
                } elseif (!in_array($s, owned_of($p)['skins'], true)) {
                    fail('Non possiedi questa skin');
                }
            }
            $p['equipped'] = $s;
            savep($p);
            out(['player' => pub($p, $me)]);

        case 'upload_skin':
            need_ok($p);
            if (setting('skin_upload_enabled') !== '1') {
                fail('Gli upload di skin sono disattivati');
            }
            $f = $_FILES['file'] ?? null;
            if (!$f || (int)$f['error'] !== UPLOAD_ERR_OK || !is_uploaded_file((string)$f['tmp_name'])) {
                fail('Upload non riuscito');
            }
            $maxKb = max(32, (int)setting('skin_max_kb'));
            if ((int)$f['size'] > $maxKb * 1024) {
                fail("File troppo pesante (massimo $maxKb KB)");
            }
            $cnt = $db->prepare("SELECT COUNT(*) FROM skins WHERE uid=?");
            $cnt->execute([$p['uid']]);
            if ((int)$cnt->fetchColumn() >= max(1, (int)setting('skin_max_per_user'))) {
                fail('Hai raggiunto il numero massimo di skin personalizzate');
            }
            $info = @getimagesize((string)$f['tmp_name']);
            if (!$info) {
                fail('Immagine non valida');
            }
            $w = (int)$info[0];
            $h = (int)$info[1];
            if ($w < 16 || $h < 16 || $w > 1024 || $h > 1024) {
                fail('Dimensioni consentite: da 16x16 a 1024x1024 pixel');
            }
            $ext = ['image/png' => 'png', 'image/jpeg' => 'jpg', 'image/gif' => 'gif', 'image/webp' => 'webp'][$info['mime']] ?? null;
            if (!$ext) {
                fail('Formati consentiti: PNG, JPG, GIF, WEBP');
            }
            $name = trim(strip_tags((string)($_POST['name'] ?? 'Skin')));
            $name = mb_substr($name !== '' ? $name : 'Skin', 0, 24);
            $file = bin2hex(random_bytes(16)) . '.' . $ext;
            if (!move_uploaded_file((string)$f['tmp_name'], ZA_DATA_DIR . '/skins/' . $file)) {
                fail('Impossibile salvare il file', 500);
            }
            $status = setting('skin_auto_approve') === '1' ? 'approved' : 'pending';
            $db->prepare('INSERT INTO skins(uid,name,file,mime,status,w,h,bytes,created) VALUES(?,?,?,?,?,?,?,?,?)')
                ->execute([$p['uid'], $name, $file, $info['mime'], $status, $w, $h, (int)$f['size'], $now]);
            out(['custom' => my_skins($p['uid']), 'status' => $status]);

        case 'delete_skin':
            $id = (int)($b['id'] ?? 0);
            $st = $db->prepare('SELECT * FROM skins WHERE id=? AND uid=?');
            $st->execute([$id, $p['uid']]);
            $row = $st->fetch();
            if (!$row) {
                fail('Skin non trovata', 404);
            }
            @unlink(ZA_DATA_DIR . '/skins/' . basename((string)$row['file']));
            $db->prepare('DELETE FROM skins WHERE id=?')->execute([$id]);
            if ($p['equipped'] === 'custom:' . $id) {
                $p['equipped'] = 'default';
                savep($p);
            }
            out(['player' => pub($p, $me), 'custom' => my_skins($p['uid'])]);

        case 'equipped_bulk':
            $names = array_slice(array_values(array_filter((array)($b['usernames'] ?? []), 'is_string')), 0, 40);
            $res = [];
            foreach ($names as $n) {
                $k = strtolower(mb_substr($n, 0, 40));
                $st = $db->prepare('SELECT * FROM players WHERE lower(username)=?');
                $st->execute([$k]);
                $pl = $st->fetch();
                $e = 'default';
                if ($pl && !is_banned($pl)) {
                    $e = (string)$pl['equipped'];
                    if (strpos($e, 'custom:') === 0) {
                        $q = $db->prepare("SELECT status FROM skins WHERE id=? AND uid=?");
                        $q->execute([(int)substr($e, 7), $pl['uid']]);
                        if ($q->fetchColumn() !== 'approved') {
                            $e = 'default';
                        }
                    } elseif ($e !== 'default' && !in_array($e, owned_of($pl)['skins'], true)) {
                        $e = 'default';
                    }
                }
                $res[$k] = $e;
            }
            out(['skins' => (object)$res]);

        case 'game_end':
            need_ok($p);
            $score = max(0, min(200000, (int)($b['score'] ?? 0)));
            $kills = max(0, min(200, (int)($b['kills'] ?? 0)));
            $food = max(0, min(3000, (int)($b['food'] ?? 0)));
            $secs = max(0, min(3600, (int)($b['seconds'] ?? 0)));
            $res = ['coins' => 0, 'xp' => 0, 'pass' => 0, 'levelUp' => false];
            if ($secs >= 15 && $now - (int)$p['last_game'] >= 15) {
                $um = jget($p, 'upgrades');
                $coins = min(300, intdiv($score, 40) + $kills * 4 + intdiv($food, 15));
                $coins = (int)floor($coins * (float)setting('coin_mult') * ev_mult('coins') * (1 + 0.1 * (int)($um['coin'] ?? 0)));
                $xp = min(400, intdiv($score, 10) + $kills * 10 + intdiv($secs, 6));
                $xp = (int)floor($xp * (float)setting('xp_mult') * ev_mult('xp') * (1 + 0.1 * (int)($um['xp'] ?? 0)));
                $pp = min(60, intdiv($score, 60) + $kills * 2 + 5);
                $pp = (int)floor($pp * (float)setting('pass_mult') * ev_mult('pass'));
                $old = level_from_xp((int)$p['xp']);
                $p['xp'] = (int)$p['xp'] + $xp;
                $new = level_from_xp((int)$p['xp']);
                if ($new > $old) {
                    $coins += 50 * ($new - $old);
                    $res['levelUp'] = true;
                }
                $p['coins'] = (int)$p['coins'] + $coins;
                $p['pass_points'] = (int)$p['pass_points'] + $pp;
                $p['games'] = (int)$p['games'] + 1;
                $p['last_game'] = $now;
                savep($p);
                txn($p['uid'], 'game', $coins, "score=$score kills=$kills");
                $res['coins'] = $coins;
                $res['xp'] = $xp;
                $res['pass'] = $pp;
            }
            out(['player' => pub($p, $me), 'reward' => $res]);

        case 'pass_buy':
            need_ok($p);
            if ((int)$p['pass_premium']) {
                fail('Hai già lo Zero Pass Premium');
            }
            $price = (int)setting('pass_premium_price');
            if ((int)$p['coins'] < $price) {
                fail('Zero Coin insufficienti');
            }
            $p['coins'] = (int)$p['coins'] - $price;
            $p['pass_premium'] = 1;
            savep($p);
            txn($p['uid'], 'pass', -$price, 'premium');
            out(['player' => pub($p, $me)]);

        case 'pass_claim':
            need_ok($p);
            $t = (int)($b['tier'] ?? 0);
            $track = ($b['track'] ?? '') === 'prem' ? 'prem' : 'free';
            $tier = min(ZA_PASS_TIERS, intdiv((int)$p['pass_points'], ZA_PASS_STEP));
            if ($t < 1 || $t > ZA_PASS_TIERS || $t > $tier) {
                fail('Livello del Pass non sbloccato');
            }
            if ($track === 'prem' && !(int)$p['pass_premium']) {
                fail('Serve lo Zero Pass Premium');
            }
            $col = $track === 'prem' ? 'pass_prem' : 'pass_free';
            $claimed = jget($p, $col);
            if (in_array($t, $claimed, true)) {
                fail('Ricompensa già riscossa');
            }
            $label = grant_reward($p, pass_rewards()[$t][$track]);
            $claimed[] = $t;
            setj($p, $col, $claimed);
            savep($p);
            out(['player' => pub($p, $me), 'cat' => catalog_for($p), 'label' => $label]);

        case 'buy_coins':
            need_ok($p);
            $packs = catalog_base()['packs'];
            $pk = (string)($b['pack'] ?? '');
            if (!isset($packs[$pk])) {
                fail('Pacchetto inesistente');
            }
            $mode = setting('payments_mode');
            if ($mode === 'off') {
                fail('I pagamenti non sono ancora attivi: intanto guadagna Zero Coin giocando!');
            }
            if ($mode === 'demo') {
                $p['coins'] = (int)$p['coins'] + (int)$packs[$pk]['coins'];
                savep($p);
                txn($p['uid'], 'demo', (int)$packs[$pk]['coins'], "pack $pk (demo)");
                out(['player' => pub($p, $me)]);
            }
            $tpl = setting('payment_url');
            if ($tpl === '') {
                fail('Pagamenti non configurati');
            }
            $db->prepare('INSERT INTO orders(uid,pack,coins,cents,created) VALUES(?,?,?,?,?)')
                ->execute([$p['uid'], $pk, (int)$packs[$pk]['coins'], (int)$packs[$pk]['cents'], $now]);
            $oid = (int)$db->lastInsertId();
            $url = str_replace(['{order}', '{pack}', '{uid}'], [(string)$oid, $pk, rawurlencode((string)$p['uid'])], $tpl);
            out(['url' => $url, 'order' => $oid]);

        default:
            fail('Azione sconosciuta', 404);
    }
}

/* ------------------------------------------------------------------ amministrazione */
function admin(string $a, array $me, array $b): void
{
    if (!($me['admin'] || $me['mod'])) {
        fail('Non autorizzato', 403);
    }
    $adminOnly = ['admin_grant', 'admin_event_list', 'admin_event_save', 'admin_event_delete', 'admin_event_toggle',
        'admin_settings_get', 'admin_settings_set', 'admin_announce'];
    if (in_array($a, $adminOnly, true) && !$me['admin']) {
        fail('Serve il ruolo di amministratore', 403);
    }
    $db = db();
    $now = time();

    switch ($a) {
        case 'admin_stats':
            $q = fn(string $sql, array $args = []) => (function () use ($db, $sql, $args) {
                $s = $db->prepare($sql);
                $s->execute($args);
                return (int)$s->fetchColumn();
            })();
            out([
                'players' => $q('SELECT COUNT(*) FROM players'),
                'online' => $q('SELECT COUNT(*) FROM players WHERE last_seen>?', [$now - 600]),
                'banned' => $q('SELECT COUNT(*) FROM players WHERE banned_until=-1 OR banned_until>?', [$now]),
                'muted' => $q('SELECT COUNT(*) FROM players WHERE muted_until>?', [$now]),
                'coins' => $q('SELECT COALESCE(SUM(coins),0) FROM players'),
                'pending_skins' => $q("SELECT COUNT(*) FROM skins WHERE status='pending'"),
                'events' => count(active_events()),
                'orders' => $q("SELECT COUNT(*) FROM orders WHERE status='paid'"),
            ]);

        case 'admin_users':
            $qs = trim((string)($b['q'] ?? ''));
            $f = (string)($b['filter'] ?? 'all');
            $page = max(0, (int)($b['page'] ?? 0));
            $where = [];
            $args = [];
            if ($qs !== '') {
                $where[] = "username LIKE ? ESCAPE '\\'";
                $args[] = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $qs) . '%';
            }
            if ($f === 'banned') {
                $where[] = '(banned_until=-1 OR banned_until>' . $now . ')';
            } elseif ($f === 'muted') {
                $where[] = 'muted_until>' . $now;
            }
            $sql = 'SELECT uid,username,role,coins,xp,banned_until,muted_until,last_seen,games FROM players'
                . ($where ? ' WHERE ' . implode(' AND ', $where) : '') . ' ORDER BY last_seen DESC LIMIT 40 OFFSET ' . ($page * 40);
            $st = $db->prepare($sql);
            $st->execute($args);
            $rows = [];
            foreach ($st->fetchAll() as $r) {
                $rows[] = [
                    'uid' => $r['uid'], 'username' => $r['username'], 'role' => $r['role'], 'coins' => (int)$r['coins'],
                    'xp' => (int)$r['xp'], 'level' => level_from_xp((int)$r['xp']), 'games' => (int)$r['games'],
                    'banned' => ((int)$r['banned_until'] === -1 || (int)$r['banned_until'] > $now),
                    'ban_until' => (int)$r['banned_until'], 'muted_until' => (int)$r['muted_until'], 'last_seen' => (int)$r['last_seen'],
                ];
            }
            out(['users' => $rows]);

        case 'admin_ban':
            $t = target((string)($b['uid'] ?? ''));
            if (in_array($t['role'], ZA_ADMIN_ROLES, true) || $t['uid'] === $me['uid']) {
                fail('Non puoi bannare questo utente');
            }
            $hours = max(0, (int)($b['hours'] ?? 0));
            $t['banned_until'] = $hours > 0 ? $now + $hours * 3600 : -1;
            $t['ban_reason'] = mb_substr(trim((string)($b['reason'] ?? '')), 0, 200);
            savep($t);
            alog($me, 'ban', $t['username'], ($hours > 0 ? "$hours ore" : 'permanente') . ' - ' . $t['ban_reason']);
            out(['done' => true]);

        case 'admin_unban':
            $t = target((string)($b['uid'] ?? ''));
            $t['banned_until'] = 0;
            $t['ban_reason'] = '';
            savep($t);
            alog($me, 'unban', $t['username']);
            out(['done' => true]);

        case 'admin_mute':
            $t = target((string)($b['uid'] ?? ''));
            if (in_array($t['role'], ZA_ADMIN_ROLES, true)) {
                fail('Non puoi silenziare un amministratore');
            }
            $min = max(1, (int)($b['minutes'] ?? 30));
            $t['muted_until'] = $now + $min * 60;
            savep($t);
            alog($me, 'mute', $t['username'], "$min minuti");
            out(['done' => true]);

        case 'admin_unmute':
            $t = target((string)($b['uid'] ?? ''));
            $t['muted_until'] = 0;
            savep($t);
            alog($me, 'unmute', $t['username']);
            out(['done' => true]);

        case 'admin_grant':
            $t = target((string)($b['uid'] ?? ''));
            $parts = [];
            $coins = (int)($b['coins'] ?? 0);
            if ($coins !== 0) {
                $t['coins'] = max(0, (int)$t['coins'] + $coins);
                $parts[] = ($coins > 0 ? '+' : '') . $coins . ' Zero Coin';
                txn($t['uid'], 'admin', $coins, 'da ' . $me['name']);
            }
            $xp = (int)($b['xp'] ?? 0);
            if ($xp !== 0) {
                $t['xp'] = max(0, (int)$t['xp'] + $xp);
                $parts[] = ($xp > 0 ? '+' : '') . $xp . ' XP';
            }
            $lvl = (int)($b['level'] ?? 0);
            if ($lvl > 0) {
                $t['xp'] = (min(999, $lvl) - 1) * 1000;
                $parts[] = 'livello ' . min(999, $lvl);
            }
            $pp = (int)($b['pass'] ?? 0);
            if ($pp !== 0) {
                $t['pass_points'] = max(0, (int)$t['pass_points'] + $pp);
                $parts[] = ($pp > 0 ? '+' : '') . $pp . ' punti Pass';
            }
            $it = (string)($b['item_type'] ?? '');
            $iid = (string)($b['item_id'] ?? '');
            if ($it !== '' && $iid !== '') {
                $d = grant_item($t, $it, $iid);
                if ($d !== '') {
                    $parts[] = $d;
                }
            }
            if (!$parts) {
                fail('Nessun premio specificato');
            }
            savep($t);
            $text = trim((string)($b['message'] ?? ''));
            msg($t['uid'], '🎁 ' . ($text !== '' ? $text . ' — ' : 'Premio dagli admin: ') . implode(', ', $parts));
            alog($me, 'grant', $t['username'], implode(', ', $parts));
            out(['done' => true, 'summary' => implode(', ', $parts)]);

        case 'admin_event_list':
            $rows = $db->query('SELECT * FROM events ORDER BY id DESC LIMIT 60')->fetchAll();
            out(['events' => $rows, 'now' => $now]);

        case 'admin_event_save':
            $title = mb_substr(trim((string)($b['title'] ?? '')), 0, 80);
            if ($title === '') {
                fail('Inserisci un titolo');
            }
            $type = (string)($b['type'] ?? 'announce');
            if (!in_array($type, ['xp', 'coins', 'pass', 'food', 'speed', 'announce'], true)) {
                fail('Tipo evento non valido');
            }
            $mult = (float)($b['mult'] ?? 1);
            $mult = $type === 'speed' ? max(1, min(2, $mult)) : ($type === 'food' ? max(1, min(3, $mult)) : max(1, min(10, $mult)));
            $hours = max(1, min(24 * 90, (int)($b['hours'] ?? 24)));
            $startS = trim((string)($b['start'] ?? ''));
            $start = $startS !== '' ? (int)strtotime($startS) : $now;
            if ($start <= 0) {
                $start = $now;
            }
            $db->prepare('INSERT INTO events(title,descr,type,mult,start_ts,end_ts,enabled) VALUES(?,?,?,?,?,?,1)')
                ->execute([$title, mb_substr(trim((string)($b['descr'] ?? '')), 0, 200), $type, $mult, $start, $start + $hours * 3600]);
            alog($me, 'event_create', $title, "$type x$mult {$hours}h");
            out(['done' => true]);

        case 'admin_event_delete':
            $db->prepare('DELETE FROM events WHERE id=?')->execute([(int)($b['id'] ?? 0)]);
            alog($me, 'event_delete', (string)(int)($b['id'] ?? 0));
            out(['done' => true]);

        case 'admin_event_toggle':
            $db->prepare('UPDATE events SET enabled=1-enabled WHERE id=?')->execute([(int)($b['id'] ?? 0)]);
            out(['done' => true]);

        case 'admin_announce':
            $title = mb_substr(trim((string)($b['title'] ?? '')), 0, 80);
            if ($title === '') {
                fail('Scrivi il messaggio');
            }
            $hours = max(1, min(168, (int)($b['hours'] ?? 6)));
            $db->prepare("INSERT INTO events(title,descr,type,mult,start_ts,end_ts,enabled) VALUES(?,?, 'announce',1,?,?,1)")
                ->execute([$title, mb_substr(trim((string)($b['descr'] ?? '')), 0, 200), $now, $now + $hours * 3600]);
            alog($me, 'announce', $title);
            out(['done' => true]);

        case 'admin_skin_list':
            $f = (string)($b['status'] ?? 'pending');
            $sql = 'SELECT s.id,s.name,s.status,s.w,s.h,s.bytes,s.created,p.username FROM skins s LEFT JOIN players p ON p.uid=s.uid'
                . ($f !== 'all' ? ' WHERE s.status=?' : '') . ' ORDER BY s.id DESC LIMIT 60';
            $st = $db->prepare($sql);
            $st->execute($f !== 'all' ? [$f] : []);
            out(['skins' => $st->fetchAll()]);

        case 'admin_skin_review':
            $id = (int)($b['id'] ?? 0);
            $dec = (string)($b['decision'] ?? '');
            $st = $db->prepare('SELECT * FROM skins WHERE id=?');
            $st->execute([$id]);
            $row = $st->fetch();
            if (!$row) {
                fail('Skin non trovata', 404);
            }
            if ($dec === 'delete') {
                @unlink(ZA_DATA_DIR . '/skins/' . basename((string)$row['file']));
                $db->prepare('DELETE FROM skins WHERE id=?')->execute([$id]);
            } elseif ($dec === 'approve' || $dec === 'reject') {
                $db->prepare('UPDATE skins SET status=? WHERE id=?')->execute([$dec === 'approve' ? 'approved' : 'rejected', $id]);
                if ($dec === 'reject') {
                    $db->prepare("UPDATE players SET equipped='default' WHERE equipped=?")->execute(['custom:' . $id]);
                }
                msg((string)$row['uid'], $dec === 'approve' ? '✅ La tua skin "' . $row['name'] . '" è stata approvata' : '❌ La tua skin "' . $row['name'] . '" è stata rifiutata');
            } else {
                fail('Decisione non valida');
            }
            alog($me, 'skin_' . $dec, '#' . $id, (string)$row['name']);
            out(['done' => true]);

        case 'admin_settings_get':
            out(['settings' => settings_all(true), 'webhook_secret' => setting('webhook_secret')]);

        case 'admin_settings_set':
            $vals = is_array($b['values'] ?? null) ? $b['values'] : [];
            $allowed = array_keys(setting_defaults());
            $st = $db->prepare('INSERT OR REPLACE INTO settings(k,v) VALUES(?,?)');
            $changed = [];
            foreach ($vals as $k => $v) {
                if (in_array($k, $allowed, true) && (is_string($v) || is_numeric($v))) {
                    $v = mb_substr((string)$v, 0, 500);
                    if ($k === 'payments_mode' && !in_array($v, ['off', 'demo', 'external'], true)) {
                        continue;
                    }
                    $st->execute([$k, $v]);
                    $changed[] = $k;
                }
            }
            settings_all(true);
            alog($me, 'settings', '', implode(',', $changed));
            out(['saved' => $changed]);

        case 'admin_logs':
            out(['logs' => $db->query('SELECT ts,actor,action,target,detail FROM logs ORDER BY id DESC LIMIT 100')->fetchAll()]);

        default:
            fail('Azione admin sconosciuta', 404);
    }
}

try {
    main();
} catch (Throwable $e) {
    error_log('[ZeroAgar economy] ' . $e->getMessage() . ' @' . $e->getFile() . ':' . $e->getLine());
    fail('Errore del server', 500);
}
