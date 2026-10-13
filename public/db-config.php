<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'zerolegend');
define('DB_USER', 'root');
define('DB_PASS', '');
define('API_SECRET', 'agar-zero-secret-2026');
date_default_timezone_set('Europe/Rome');
function db(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;
    if (!extension_loaded('pdo_mysql')) throw new RuntimeException('Estensione PHP pdo_mysql non disponibile sul server.');
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_TIMEOUT => 8,
    ]);
    return $pdo;
}
