<?php
/**
 * Database (MySQL via PDO) + sessione + helper comuni.
 * Sostituisce il vecchio archivio JSON: tutti i dati sono ora nel database XAMPP.
 */
require_once __DIR__ . '/../config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name('arcade_sid');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    ]);
    session_start();
}

const ARCADE_ROLES   = ['user', 'premium', 'helper', 'moderatore', 'admin', 'founder'];
const ARCADE_GAMES   = ['zero_agar', 'space_defender', 'cyber_runner', 'neon_breaker'];
const ARCADE_AVATARS = ['🚀', '👾', '🦠', '⚡', '🧱', '🤖', '🔥', '🛸', '🎮', '🐉'];

const USER_COLS = [
    'username', 'email', 'role', 'avatar', 'coins', 'gems', 'dust', 'xp', 'level', 'prestige',
    'rank_title', 'streak', 'clan', 'referral_code', 'referred_by', 'referral_count',
    'referral_coins_earned', 'is_banned', 'ban_reason', 'is_premium_trial', 'trial_expires_at',
    'has_pro_license', 'license_expires_at', 'last_login', 'last_daily_claim', 'last_spin', 'last_seen',
];
const USER_DT_COLS   = ['trial_expires_at', 'license_expires_at', 'last_login', 'last_spin', 'last_seen', 'created_at'];
const USER_BOOL_COLS = ['is_banned', 'is_premium_trial', 'has_pro_license'];
const USER_INT_COLS  = ['coins', 'gems', 'dust', 'xp', 'level', 'prestige', 'streak', 'referral_count', 'referral_coins_earned'];

/* ------------------------------------------------------------------ */
/* Risposte JSON                                                       */
/* ------------------------------------------------------------------ */
function json_response($data, int $status = 200): void {
    if (isset($GLOBALS['ARCADE_PDO']) && $GLOBALS['ARCADE_PDO'] instanceof PDO && $GLOBALS['ARCADE_PDO']->inTransaction()) {
        // Una risposta anticipata (errore) non deve lasciare modifiche a metà
        if (empty($data['success'])) {
            $GLOBALS['ARCADE_PDO']->rollBack();
        }
    }
    header('Content-Type: application/json; charset=UTF-8');
    header('Cache-Control: no-store');
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    exit;
}

/** Legge il body JSON di una POST. Richiedere application/json blocca le POST cross-site (CSRF). */
function read_json_post(): array {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        json_response(['success' => false, 'error' => 'Metodo non consentito (usa POST)'], 405);
    }
    $ct = $_SERVER['CONTENT_TYPE'] ?? ($_SERVER['HTTP_CONTENT_TYPE'] ?? '');
    if (stripos($ct, 'application/json') === false) {
        json_response(['success' => false, 'error' => 'Content-Type deve essere application/json'], 415);
    }
    $data = json_decode(file_get_contents('php://input'), true);
    return is_array($data) ? $data : [];
}

function client_ip(): string {
    return substr($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0', 0, 45);
}

/* ------------------------------------------------------------------ */
/* Connessione PDO                                                     */
/* ------------------------------------------------------------------ */
function db_fatal(PDOException $e): void {
    $hint = 'Verifica che MySQL sia avviato in XAMPP, che i dati in config.php siano corretti e di aver eseguito install.php.';
    $isApi = strpos($_SERVER['SCRIPT_NAME'] ?? '', '/api/') !== false;
    if ($isApi) {
        json_response(['success' => false, 'error' => 'Database non raggiungibile. ' . $hint], 500);
    }
    http_response_code(500);
    header('Content-Type: text/html; charset=UTF-8');
    echo '<!DOCTYPE html><meta charset="utf-8"><body style="font-family:sans-serif;background:#0b1020;color:#e2e8f0;padding:40px">'
       . '<h2>Database non raggiungibile</h2><p>' . htmlspecialchars($hint) . '</p>'
       . '<p><a style="color:#22d3ee" href="install.php">Apri install.php</a></p></body>';
    exit;
}

function db(): PDO {
    if (isset($GLOBALS['ARCADE_PDO']) && $GLOBALS['ARCADE_PDO'] instanceof PDO) {
        return $GLOBALS['ARCADE_PDO'];
    }
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    } catch (PDOException $e) {
        db_fatal($e);
    }
    $GLOBALS['ARCADE_PDO'] = $pdo;
    return $pdo;
}

/* ------------------------------------------------------------------ */
/* Profilo utente (campi flessibili salvati come JSON)                 */
/* ------------------------------------------------------------------ */
function default_profile(): array {
    return [
        'unlocked_items' => ['skin_neon_blue', 'skin_gold'],
        'equipped' => [
            'ship_skin'  => 'skin_neon_blue',
            'cell_skin'  => '#00f0ff',
            'trail'      => 'default',
            'sound_pack' => 'retro_synth',
        ],
        'stats' => [
            'games_played'       => 0,
            'highscores'         => ['space_defender' => 0, 'cyber_runner' => 0, 'neon_breaker' => 0, 'zero_agar' => 0],
            'total_kills'        => 0,
            'total_distance'     => 0,
            'bosses_defeated'    => 0,
            'total_coins_earned' => 0,
            'items_purchased'    => 0,
            'zero_agar'          => ['peak_mass' => 0, 'rivals_eaten' => 0, 'food_eaten' => 0, 'splits_count' => 0, 'time_survived_sec' => 0],
        ],
        'achievements'      => [],
        'favorite_games'    => ['zero_agar'],
        'battle_pass'       => ['current_tier' => 1, 'claimed_free_tiers' => [], 'claimed_vip_tiers' => [], 'is_vip' => false],
        'referrals_history' => [],
        'telemetry'         => [],
        'speedruns'         => [],
        'clan_info'         => null,
        'cloud_checkpoint'  => null,
        'last_submit'       => [],
        'premium_source'    => null,
    ];
}

function is_list_array(array $a): bool {
    return $a === [] || array_keys($a) === range(0, count($a) - 1);
}

/** Unisce i default con quanto salvato: le liste salvate vincono, gli oggetti si completano. */
function profile_merge(array $def, array $stored): array {
    $out = $stored;
    foreach ($def as $k => $v) {
        if (!array_key_exists($k, $stored) || ($stored[$k] === null && $v !== null)) {
            $out[$k] = $v;
            continue;
        }
        if (is_array($v) && is_array($stored[$k]) && !is_list_array($v)) {
            $out[$k] = profile_merge($v, $stored[$k]);
        }
    }
    return $out;
}

function user_from_row(array $r): array {
    $profile = json_decode($r['profile_json'] ?? '', true);
    $u = profile_merge(default_profile(), is_array($profile) ? $profile : []);
    foreach ($r as $k => $v) {
        if ($k === 'profile_json' || $k === 'password_hash') continue;   // mai esposti
        $u[$k] = $v;
    }
    foreach (USER_INT_COLS as $k) $u[$k] = (int)($u[$k] ?? 0);
    foreach (USER_BOOL_COLS as $k) $u[$k] = !empty($u[$k]);
    foreach (USER_DT_COLS as $k) {
        if (!empty($u[$k])) {
            $ts = strtotime((string)$u[$k]);
            $u[$k] = $ts ? date('c', $ts) : null;
        } else {
            $u[$k] = null;
        }
    }
    $u['last_daily_claim'] = !empty($u['last_daily_claim']) ? (string)$u['last_daily_claim'] : null;
    return $u;
}

function sql_datetime($v): ?string {
    if ($v === null || $v === '' || $v === false) return null;
    $ts = is_numeric($v) ? (int)$v : strtotime((string)$v);
    return $ts ? date('Y-m-d H:i:s', $ts) : null;
}

function save_user(array $u): void {
    $sets = [];
    $params = [];
    foreach (USER_COLS as $c) {
        if (!array_key_exists($c, $u)) continue;
        $v = $u[$c];
        if (in_array($c, USER_DT_COLS, true)) {
            $v = sql_datetime($v);
        } elseif ($c === 'last_daily_claim') {
            $ts = $v ? strtotime((string)$v) : false;
            $v = $ts ? date('Y-m-d', $ts) : null;
        } elseif (in_array($c, USER_BOOL_COLS, true)) {
            $v = $v ? 1 : 0;
        } elseif ($c === 'email' || $c === 'ban_reason' || $c === 'referred_by') {
            $v = ($v === '' || $v === null) ? null : (string)$v;
        }
        $sets[] = "`$c` = :$c";
        $params[$c] = $v;
    }
    $profile = [];
    foreach (array_keys(default_profile()) as $k) {
        if (array_key_exists($k, $u)) $profile[$k] = $u[$k];
    }
    $sets[] = 'profile_json = :pj';
    $params['pj'] = json_encode($profile, JSON_UNESCAPED_UNICODE);
    $params['id'] = $u['id'];
    db()->prepare('UPDATE users SET ' . implode(', ', $sets) . ' WHERE id = :id')->execute($params);
}

/** Carica l'utente bloccando la riga: evita doppi accrediti con richieste simultanee. */
function user_lock(string $id): ?array {
    $pdo = db();
    if (!$pdo->inTransaction()) $pdo->beginTransaction();
    $st = $pdo->prepare('SELECT * FROM users WHERE id = ? FOR UPDATE');
    $st->execute([$id]);
    $r = $st->fetch();
    return $r ? user_from_row($r) : null;
}

function user_commit(array $u): void {
    save_user($u);
    if (db()->inTransaction()) db()->commit();
}

/** Scade automaticamente prova Premium e licenza. Ritorna l'utente aggiornato. */
function refresh_entitlements(array $u): array {
    $changed = false;
    $now = time();
    if ($u['is_premium_trial'] && $u['trial_expires_at'] && strtotime($u['trial_expires_at']) < $now) {
        $u['is_premium_trial'] = false;
        if ($u['role'] === 'premium' && ($u['premium_source'] ?? null) === 'trial') $u['role'] = 'user';
        $changed = true;
    }
    if ($u['has_pro_license'] && $u['license_expires_at'] && strtotime($u['license_expires_at']) < $now) {
        $u['has_pro_license'] = false;
        if ($u['role'] === 'premium' && ($u['premium_source'] ?? null) === 'license') $u['role'] = 'user';
        $changed = true;
    }
    if ($changed) save_user($u);
    return $u;
}

/* ------------------------------------------------------------------ */
/* Autenticazione                                                      */
/* ------------------------------------------------------------------ */
function current_user(): ?array {
    static $cache = false;
    if ($cache !== false) return $cache;
    $cache = null;
    if (empty($_SESSION['uid'])) return null;
    $st = db()->prepare('SELECT * FROM users WHERE id = ?');
    $st->execute([$_SESSION['uid']]);
    $r = $st->fetch();
    if (!$r || !empty($r['is_banned'])) {
        unset($_SESSION['uid']);
        return null;
    }
    $cache = refresh_entitlements(user_from_row($r));
    return $cache;
}

function require_login_api(): array {
    $u = current_user();
    if (!$u) {
        json_response(['success' => false, 'error' => 'Sessione non valida: effettua il login', 'auth_required' => true], 401);
    }
    return $u;
}

function require_login_page(): array {
    $u = current_user();
    if (!$u) {
        $next = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
        header('Location: login.php?next=' . urlencode($next));
        exit;
    }
    return $u;
}

function start_user_session(string $id): void {
    session_regenerate_id(true);
    $_SESSION['uid'] = $id;
}

/* ------------------------------------------------------------------ */
/* Cataloghi e classifiche (stessa forma del vecchio $db)              */
/* ------------------------------------------------------------------ */
function load_leaderboards(): array {
    $out = [];
    foreach (ARCADE_GAMES as $g) $out[$g] = [];
    $rows = db()->query('SELECT game_id, username, avatar, score, score_date FROM leaderboards ORDER BY score DESC, id ASC')->fetchAll();
    foreach ($rows as $r) {
        $g = $r['game_id'];
        if (!isset($out[$g])) $out[$g] = [];
        if (count($out[$g]) >= 20) continue;
        $out[$g][] = [
            'username' => $r['username'],
            'avatar'   => $r['avatar'],
            'score'    => (int)$r['score'],
            'date'     => $r['score_date'],
        ];
    }
    return $out;
}

function load_db_store(?array $viewer = null): array {
    $pdo = db();
    $shop = $pdo->query('SELECT id, type, name, price, icon, description FROM shop_items ORDER BY sort_order, id')->fetchAll();
    foreach ($shop as &$s) $s['price'] = (int)$s['price'];
    unset($s);
    $ach = $pdo->query('SELECT id, title, description, reward_coins, reward_xp, icon FROM achievements ORDER BY sort_order, id')->fetchAll();
    foreach ($ach as &$a) { $a['reward_coins'] = (int)$a['reward_coins']; $a['reward_xp'] = (int)$a['reward_xp']; }
    unset($a);
    $dc = $pdo->query('SELECT id, title, task, target, reward_coins FROM daily_challenges ORDER BY id')->fetchAll();

    $users = [];
    if ($viewer && ($viewer['role'] ?? '') === 'founder') {
        $users = $pdo->query('SELECT id, username, role, avatar, coins, level, is_banned FROM users ORDER BY created_at, username')->fetchAll();
        foreach ($users as &$u) { $u['coins'] = (int)$u['coins']; $u['level'] = (int)$u['level']; }
        unset($u);
    }
    return [
        'shop_items'       => $shop,
        'achievements'     => $ach,
        'daily_challenges' => $dc,
        'leaderboards'     => load_leaderboards(),
        'users'            => $users,
    ];
}
