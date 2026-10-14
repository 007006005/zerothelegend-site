<?php
declare(strict_types=1);
/**
 * api/avatar.php - Immagine profilo unica, salvata nel database MySQL.
 *   GET  ?u=<user_id>        -> restituisce l'immagine (404 se non c'e')
 *   POST {image:"data:image/...;base64,..."}   (login richiesto) -> salva
 *   POST {action:"clear"}                       (login richiesto) -> elimina
 */
require_once __DIR__ . '/db.php';

function ensure_avatar_table(): void
{
    db()->exec(
        'CREATE TABLE IF NOT EXISTS user_avatars (
            user_id VARCHAR(32) NOT NULL PRIMARY KEY,
            mime VARCHAR(40) NOT NULL,
            data MEDIUMBLOB NOT NULL,
            updated_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
    );
}

try {
    ensure_avatar_table();
} catch (Throwable $e) {
    error_log('avatar table: ' . $e->getMessage());
    json_response(['success' => false, 'error' => 'Database non pronto per le immagini profilo.'], 500);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
    $id = (string)($_GET['u'] ?? '');
    if (!preg_match('/^[A-Za-z0-9_-]{1,32}$/', $id)) {
        http_response_code(404);
        exit;
    }
    $st = db()->prepare('SELECT mime, data FROM user_avatars WHERE user_id = ? LIMIT 1');
    $st->execute([$id]);
    $row = $st->fetch();
    if (!$row) {
        http_response_code(404);
        exit;
    }
    session_write_close();
    header('Content-Type: ' . $row['mime']);
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: public, max-age=86400');   // l'URL cambia (?v=) a ogni nuovo caricamento
    echo $row['data'];
    exit;
}

$u = require_login_api();
enforce_same_origin();
$in = read_json_post();

if (($in['action'] ?? '') === 'clear') {
    db()->prepare('DELETE FROM user_avatars WHERE user_id = ?')->execute([$u['id']]);
    json_response(['success' => true, 'avatar_url' => null]);
}

$image = (string)($in['image'] ?? '');
if (!preg_match('#^data:image/(png|jpeg|webp|gif);base64,([A-Za-z0-9+/=\s]+)$#', $image, $m)) {
    json_response(['success' => false, 'error' => 'Formato immagine non valido (PNG, JPG, WEBP o GIF).'], 400);
}
$bin = base64_decode($m[2], true);
if ($bin === false || $bin === '' || strlen($bin) > 700000) {
    json_response(['success' => false, 'error' => 'Immagine troppo grande (max 700 KB dopo la riduzione).'], 413);
}
$info = @getimagesizefromstring($bin);
$allowed = [IMAGETYPE_PNG => 'image/png', IMAGETYPE_JPEG => 'image/jpeg', IMAGETYPE_WEBP => 'image/webp', IMAGETYPE_GIF => 'image/gif'];
if (!$info || !isset($allowed[$info[2]]) || $info[0] > 1024 || $info[1] > 1024) {
    json_response(['success' => false, 'error' => 'Immagine non valida o oltre 1024x1024.'], 400);
}
try {
    db()->prepare(
        'INSERT INTO user_avatars (user_id, mime, data, updated_at) VALUES (?,?,?,NOW())
         ON DUPLICATE KEY UPDATE mime = VALUES(mime), data = VALUES(data), updated_at = NOW()'
    )->execute([$u['id'], $allowed[$info[2]], $bin]);
} catch (Throwable $e) {
    error_log('avatar save: ' . $e->getMessage());
    json_response(['success' => false, 'error' => 'Salvataggio immagine non riuscito (database).'], 500);
}
json_response(['success' => true, 'avatar_url' => avatar_url_for((string)$u['id'])]);
