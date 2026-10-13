<?php
/**
 * db.php — Connessione al database MySQL (IONOS)
 *
 * IMPORTANTE:
 * - Riempi le costanti qui sotto con i TUOI dati reali.
 * - Non committare mai questo file in un repository pubblico.
 * - Assicurati che la password del database sia stata rigenerata
 *   (se l'hai mai condivisa altrove, es. in chat, email, screenshot).
 */

define('DB_HOST', 'db5021535656.hosting-data.io');
define('DB_PORT', 3306);
define('DB_NAME', 'dbs16175408');
define('DB_USER', 'dbu700360');
define('DB_PASS', 'Elpro112!Adriano'); // <-- da riempire lato server

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    error_log('DB connection failed: ' . $e->getMessage());
    http_response_code(500);
    die('Errore di connessione al database');
}
