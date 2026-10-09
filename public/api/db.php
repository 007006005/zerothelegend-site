<?php
/**
 * Shared database, CORS, session, and authentication helpers for the API.
 */

$allowedOrigins = array_values(array_filter(array_map(
    'trim',
    explode(',', getenv('ALLOWED_ORIGINS') ?: 'https://zerothelegend.com,https://www.zerothelegend.com,https://games.zerothelegend.com')
)));
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if ($origin !== '' && in_array($origin, $allowedOrigins, true)) {
    header('Access-Control-Allow-Origin: ' . $origin);
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
    header('Vary: Origin');
}

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
    http_response_code($origin === '' || in_array($origin, $allowedOrigins, true) ? 204 : 403);
    exit;
}

if (!function_exists('env_or_default')) {
    function env_or_default(string $key, string $default): string
    {
        $value = getenv($key);
        if (is_string($value) && $value !== '') {
            return $value;
        }

        $value = $_ENV[$key] ?? null;
        return is_string($value) && $value !== '' ? $value : $default;
    }
}

if (!function_exists('request_is_secure')) {
    function request_is_secure(): bool
    {
        $https = $_SERVER['HTTPS'] ?? '';
        if (is_string($https) && strcasecmp($https, 'on') === 0) {
            return true;
        }

        if ((string)($_SERVER['SERVER_PORT'] ?? '') === '443') {
            return true;
        }

        $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? $_SERVER['HTTP_X_FORWARDED_SSL'] ?? null;
        if (is_string($forwardedProto)) {
            foreach (explode(',', $forwardedProto) as $candidate) {
                $value = strtolower(trim($candidate));
                if ($value === 'https' || $value === 'on') {
                    return true;
                }
                if ($value === 'http') {
                    return false;
                }
            }
        }

        $cfVisitor = $_SERVER['HTTP_CF_VISITOR'] ?? '';
        if (is_string($cfVisitor) && str_contains($cfVisitor, '"scheme":"https"')) {
            return true;
        }

        return false;
    }
}

if (!defined('DB_HOST')) {
    define('DB_HOST', env_or_default('DB_HOST', '127.0.0.1'));
    define('DB_PORT', (int)env_or_default('DB_PORT', '3306'));
    define('DB_NAME', env_or_default('DB_NAME', 'your_database_name'));
    define('DB_USER', env_or_default('DB_USER', 'your_db_user'));
    define('DB_PASS', env_or_default('DB_PASS', 'your_db_password'));
}

if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => request_is_secure(),
        'cookie_samesite' => 'Lax',
    ]);
}

try {
    if (str_starts_with(DB_NAME, 'your_') || str_starts_with(DB_USER, 'your_')) {
        throw new RuntimeException('Railway database environment variables are not configured.');
    }

    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (Throwable $error) {
    error_log('API database initialization failed: ' . $error->getMessage());
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'success' => false,
        'error' => 'Database non disponibile. Controlla le variabili DB_* del servizio Railway.',
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

function db(): PDO
{
    global $pdo;
    return $pdo;
}

function touch_presence(string $userId): void
{
    $statement = db()->prepare('UPDATE users SET last_seen = NOW() WHERE id = ?');
    $statement->execute([$userId]);
}

function read_json_post(): array
{
    $raw = file_get_contents('php://input');
    if (!is_string($raw) || trim($raw) === '') {
        return $_POST;
    }

    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function json_response(array $data, int $code = 200): void
{
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

function enforce_same_origin(): void
{
    global $allowedOrigins;
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if ($origin === '' || !in_array($origin, $allowedOrigins, true)) {
        json_response(['success' => false, 'error' => 'Origine della richiesta non consentita.'], 403);
    }
}

function client_ip(): string
{
    $address = (string)($_SERVER['REMOTE_ADDR'] ?? '');
    return filter_var($address, FILTER_VALIDATE_IP) ? $address : 'unknown';
}

function ensure_auth_attempts_table(): void
{
    static $initialized = false;
    if ($initialized) {
        return;
    }

    try {
        db()->exec(
            'CREATE TABLE IF NOT EXISTS auth_attempts (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                ip VARCHAR(45) NOT NULL,
                kind VARCHAR(20) NOT NULL,
                ident VARCHAR(190) NOT NULL DEFAULT \'\',
                attempted_at DATETIME NOT NULL,
                KEY idx_auth_attempts_lookup (ip, kind, ident, attempted_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    } catch (PDOException $error) {
        error_log('Unable to initialize auth_attempts: ' . $error->getMessage());
        json_response(['success' => false, 'error' => 'Database non pronto per il login: impossibile inizializzare il limite dei tentativi.'], 500);
    }
    $initialized = true;
}

function ensure_portal_auth_schema(): void
{
    $exists = db()->prepare(
        'SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?'
    );

    try {
        $exists->execute(['users']);
        if ((int)$exists->fetchColumn() === 0) {
            db()->exec(
                'CREATE TABLE IF NOT EXISTS users (
                    id VARCHAR(32) NOT NULL PRIMARY KEY,
                    username VARCHAR(20) NOT NULL,
                    email VARCHAR(190) NOT NULL,
                    password_hash VARCHAR(255) NOT NULL,
                    role VARCHAR(20) NOT NULL DEFAULT \'user\',
                    coins INT UNSIGNED NOT NULL DEFAULT 500,
                    gems INT UNSIGNED NOT NULL DEFAULT 15,
                    xp INT UNSIGNED NOT NULL DEFAULT 0,
                    level INT UNSIGNED NOT NULL DEFAULT 1,
                    clan VARCHAR(8) NOT NULL DEFAULT \'ZERO\',
                    profile_json MEDIUMTEXT NULL,
                    is_banned TINYINT(1) NOT NULL DEFAULT 0,
                    ban_reason VARCHAR(255) NULL,
                    daily_streak INT UNSIGNED NOT NULL DEFAULT 0,
                    last_login DATETIME NULL,
                    last_seen DATETIME NULL,
                    last_daily DATE NULL,
                    created_at DATETIME NOT NULL,
                    UNIQUE KEY uq_users_username (username),
                    UNIQUE KEY uq_users_email (email)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
            );
        }

        $exists->execute(['user_state']);
        if ((int)$exists->fetchColumn() === 0) {
            db()->exec(
                'CREATE TABLE IF NOT EXISTS user_state (
                    user_id VARCHAR(32) NOT NULL PRIMARY KEY,
                    state_json LONGTEXT NOT NULL,
                    updated_at DATETIME NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
            );
        }
    } catch (PDOException $error) {
        error_log('Unable to initialize portal auth schema: ' . $error->getMessage());
        json_response([
            'success' => false,
            'error' => 'Database non pronto: impossibile inizializzare le tabelle utenti. Verifica i permessi CREATE del database Railway.',
        ], 500);
    }
}

function rate_limited(string $kind, string $ident, int $limit, int $windowSeconds): bool
{
    ensure_auth_attempts_table();
    $cutoff = date('Y-m-d H:i:s', time() - max(1, $windowSeconds));
    $statement = db()->prepare(
        'SELECT COUNT(*) FROM auth_attempts
         WHERE ip = ? AND kind = ? AND ident = ? AND attempted_at >= ?'
    );
    $statement->execute([client_ip(), $kind, $ident, $cutoff]);

    return (int)$statement->fetchColumn() >= max(1, $limit);
}

function record_attempt(string $kind, string $ident): void
{
    ensure_auth_attempts_table();
    db()->prepare(
        'INSERT INTO auth_attempts (ip, kind, ident, attempted_at) VALUES (?, ?, ?, NOW())'
    )->execute([client_ip(), $kind, $ident]);
}

function table_columns(string $table): array
{
    if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $table)) {
        throw new InvalidArgumentException('Nome tabella non valido.');
    }

    $statement = db()->query('SHOW COLUMNS FROM `' . $table . '`');
    $columns = [];
    foreach ($statement->fetchAll() as $column) {
        if (isset($column['Field'])) {
            $columns[(string)$column['Field']] = true;
        }
    }

    return $columns;
}

function insert_row(string $table, array $values): void
{
    if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $table)) {
        throw new InvalidArgumentException('Nome tabella non valido.');
    }

    $availableColumns = table_columns($table);
    $values = array_intersect_key($values, $availableColumns);
    if ($values === []) {
        throw new InvalidArgumentException('Nessuna colonna valida per l’inserimento.');
    }

    $columns = array_keys($values);
    foreach ($columns as $column) {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', (string)$column)) {
            throw new InvalidArgumentException('Nome colonna non valido.');
        }
    }

    $quotedColumns = array_map(static fn(string $column): string => '`' . $column . '`', $columns);
    $placeholders = implode(',', array_fill(0, count($columns), '?'));
    $statement = db()->prepare(
        'INSERT INTO `' . $table . '` (' . implode(',', $quotedColumns) . ') VALUES (' . $placeholders . ')'
    );
    $statement->execute(array_values($values));
}

function user_hash(array $user): string
{
    $passwordHash = (string)($user['password_hash'] ?? '');
    return $passwordHash !== '' ? $passwordHash : (string)($user['password'] ?? '');
}

function login_session(string $userId): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $userId;
    unset($_SESSION['user']);
}

function logout_session(): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires' => time() - 42000,
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => $params['secure'],
            'httponly' => $params['httponly'],
            'samesite' => $params['samesite'] ?? 'Lax',
        ]);
    }
    session_destroy();
}

function current_user(bool $refresh = false): ?array
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $cachedUser = $_SESSION['user'] ?? null;
    $userId = (string)($_SESSION['user_id'] ?? (is_array($cachedUser) ? ($cachedUser['id'] ?? '') : ''));
    if ($userId === '') {
        return null;
    }

    if (!$refresh && is_array($cachedUser) && !empty($cachedUser['id']) && empty($cachedUser['is_banned'])) {
        return $cachedUser;
    }

    $statement = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
    $statement->execute([$userId]);
    $user = $statement->fetch();
    if (!is_array($user) || !empty($user['is_banned'])) {
        unset($_SESSION['user'], $_SESSION['user_id']);
        return null;
    }

    $profile = json_decode((string)($user['profile_json'] ?? ''), true);
    $user['profile'] = is_array($profile) ? $profile : [];
    $_SESSION['user'] = $user;
    $_SESSION['user_id'] = (string)$user['id'];

    return $user;
}

function user_public(array $user): array
{
    $profile = $user['profile'] ?? [];
    if (!is_array($profile)) {
        $profile = [];
    }

    foreach (['password', 'password_hash', 'pin', 'serverAuth', 'loggedIn', 'provider'] as $privateKey) {
        unset($profile[$privateKey]);
    }

    return [
        'id' => (string)($user['id'] ?? ''),
        'username' => (string)($user['username'] ?? ''),
        'email' => (string)($user['email'] ?? ''),
        'role' => (string)($user['role'] ?? 'user'),
        'coins' => (int)($user['coins'] ?? 0),
        'gems' => (int)($user['gems'] ?? 0),
        'xp' => (int)($user['xp'] ?? 0),
        'level' => (int)($user['level'] ?? 1),
        'clan' => (string)($user['clan'] ?? 'ZERO'),
        'profile' => $profile,
    ];
}

function require_login_api(): array
{
    $user = current_user(true);
    if ($user === null) {
        json_response(['success' => false, 'error' => 'Sessione non valida o scaduta. Effettua nuovamente l’accesso.'], 401);
    }

    return $user;
}

if (!defined('ARCADE_ROLES')) {
    define('ARCADE_ROLES', ['user', 'vip', 'helper', 'mod', 'admin', 'founder']);
}

function role_rank(string $role): int
{
    $normalized = strtolower(trim($role));
    if ($normalized === 'moderatore') {
        $normalized = 'mod';
    }

    $rank = array_search($normalized, ARCADE_ROLES, true);
    return $rank === false ? 0 : $rank;
}

function require_login_page(string $redirectTo = '/'): array
{
    $user = current_user(true);
    if ($user !== null) {
        return $user;
    }

    if (!preg_match('/^[A-Za-z0-9_./?=&%-]+$/', $redirectTo)) {
        $redirectTo = '/';
    }

    header('Location: ' . $redirectTo);
    exit;
}

function require_staff_api(string $minimumRole = 'mod'): array
{
    $user = require_login_api();
    if (role_rank((string)($user['role'] ?? 'user')) < role_rank($minimumRole)) {
        json_response(['success' => false, 'error' => 'Accesso riservato allo staff.'], 403);
    }

    return $user;
}

function esc(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function audit(array $admin, string $action, string $target = '', string $details = ''): void
{
    static $initialized = false;
    $pdo = db();

    if (!$initialized) {
        $pdo->exec(
            'CREATE TABLE IF NOT EXISTS admin_audit (
                id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                admin_id VARCHAR(32) NOT NULL,
                admin_name VARCHAR(80) NOT NULL,
                action VARCHAR(80) NOT NULL,
                target VARCHAR(190) NOT NULL DEFAULT \'\',
                details TEXT NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                KEY idx_admin_audit_created (created_at),
                KEY idx_admin_audit_admin (admin_id, created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
        );
        $initialized = true;
    }

    $pdo->prepare(
        'INSERT INTO admin_audit (admin_id, admin_name, action, target, details, created_at)
         VALUES (?, ?, ?, ?, ?, NOW())'
    )->execute([
        (string)($admin['id'] ?? ''),
        (string)($admin['username'] ?? 'staff'),
        $action,
        $target,
        $details,
    ]);
}