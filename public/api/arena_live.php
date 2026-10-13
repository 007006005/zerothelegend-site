<?php
/**
 * api/arena_live.php - Arena globale unica per tutti i giocatori autenticati.
 * POST JSON: {action: "join"|"update"|"sync"|"chat"|"leave", ...}
 * Il parametro "arena" inviato dal client viene ignorato: la room è sempre fissa.
 */
require_once __DIR__ . '/db.php';

const ARENA_ROOM = 'ZeroArcade:global';
const ARENA_TIMEOUT_S = 10;      // giocatore considerato offline dopo 10 s
const ARENA_CHAT_KEEP = 100;     // messaggi conservati
const ARENA_MAX_CELLS = 12;
const ARENA_MAX_MASS = 600000;

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    json_response(['success' => false, 'error' => 'Metodo non consentito'], 405);
}
enforce_same_origin();

$in = read_json_post();
$action = (string)($in['action'] ?? '');

$user = current_user();
if (!$user) {
    json_response(['success' => false, 'error' => 'Accedi per entrare nell\'arena.'], 401);
}
if (!empty($user['is_banned'])) {
    json_response(['success' => false, 'error' => 'Account bannato.'], 403);
}

$pdo = db();
$uid = (string)$user['id'];
$uname = (string)$user['username'];
$now = date('Y-m-d H:i:s');

/** Sanifica le celle inviate dal client (non fidarsi dei valori). */
function arena_clean_cells($raw): array {
    $out = [];
    if (!is_array($raw)) return $out;
    foreach (array_slice($raw, 0, ARENA_MAX_CELLS) as $c) {
        if (!is_array($c)) continue;
        $m = max(1, min(ARENA_MAX_MASS, (float)($c['m'] ?? 1)));
        $color = (string)($c['color'] ?? '#00ffff');
        if (!preg_match('/^#[0-9a-fA-F]{3,8}$/', $color)) $color = '#00ffff';
        $out[] = [
            'x' => max(0, min(6000, (float)($c['x'] ?? 3000))),
            'y' => max(0, min(6000, (float)($c['y'] ?? 3000))),
            'm' => $m,
            'r' => max(1, min(2000, (float)($c['r'] ?? sqrt($m)))),
            'color' => $color,
        ];
    }
    return $out;
}

/** Rimuove chi non ha mandato segnali di vita e restituisce i giocatori attivi. */
function arena_active_players(PDO $pdo): array {
    $cut = date('Y-m-d H:i:s', time() - ARENA_TIMEOUT_S);
    $pdo->prepare('DELETE FROM arena_players WHERE arena = ? AND last_seen < ?')
        ->execute([ARENA_ROOM, $cut]);
    $st = $pdo->prepare('SELECT user_id, username, score, cells_json, color FROM arena_players WHERE arena = ?');
    $st->execute([ARENA_ROOM]);
    $players = [];
    foreach ($st->fetchAll() as $r) {
        $players[] = [
            'id' => $r['user_id'],
            'username' => $r['username'],
            'score' => (int)$r['score'],
            'color' => $r['color'],
            'cells' => json_decode((string)$r['cells_json'], true) ?: [],
            'alive' => true,
        ];
    }
    return $players;
}

function arena_chat_since(PDO $pdo, int $since): array {
    $st = $pdo->prepare('SELECT id, username, text FROM arena_chat WHERE arena = ? AND id > ? ORDER BY id ASC LIMIT 50');
    $st->execute([ARENA_ROOM, $since]);
    return $st->fetchAll();
}

function arena_last_chat_id(PDO $pdo): int {
    $st = $pdo->prepare('SELECT COALESCE(MAX(id), 0) FROM arena_chat WHERE arena = ?');
    $st->execute([ARENA_ROOM]);
    return (int)$st->fetchColumn();
}

function arena_upsert_player(PDO $pdo, string $uid, string $uname, array $in, string $now): void {
    $cells = arena_clean_cells($in['cells'] ?? []);
    $score = max(0, min(ARENA_MAX_MASS, (int)($in['score'] ?? 0)));
    $color = (string)($in['cells'][0]['color'] ?? '#00ffff');
    if (!preg_match('/^#[0-9a-fA-F]{3,8}$/', $color)) $color = '#00ffff';
    $pdo->prepare(
        'INSERT INTO arena_players (arena, user_id, username, score, cells_json, color, last_seen)
         VALUES (?, ?, ?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE username = VALUES(username), score = VALUES(score),
           cells_json = VALUES(cells_json), color = VALUES(color), last_seen = VALUES(last_seen)'
    )->execute([ARENA_ROOM, $uid, $uname, $score, json_encode($cells), $color, $now]);
}

function arena_is_member(PDO $pdo, string $uid): bool {
    $st = $pdo->prepare('SELECT 1 FROM arena_players WHERE arena = ? AND user_id = ?');
    $st->execute([ARENA_ROOM, $uid]);
    return (bool)$st->fetchColumn();
}

switch ($action) {
    case 'join':
        arena_upsert_player($pdo, $uid, $uname, $in, $now);
        json_response([
            'success' => true,
            'arena' => ARENA_ROOM,
            'players' => arena_active_players($pdo),
            'last_chat' => arena_last_chat_id($pdo),
        ]);

    case 'update':
        if (!arena_is_member($pdo, $uid)) {
            json_response(['success' => false, 'error' => 'Non sei nell\'arena: esegui join.'], 409);
        }
        arena_upsert_player($pdo, $uid, $uname, $in, $now);
        $serverScore = (int)$pdo->query('SELECT score FROM arena_players WHERE arena = \'' . ARENA_ROOM . '\' AND user_id = \'' . $uid . '\'')->fetchColumn();
        json_response([
            'success' => true,
            'dead' => false,
            'server_score' => $serverScore,
            'players' => arena_active_players($pdo),
        ]);

    case 'sync':
        $since = max(0, (int)($in['since'] ?? 0));
        json_response([
            'success' => true,
            'players' => arena_active_players($pdo),
            'messages' => arena_chat_since($pdo, $since),
            'last_chat' => arena_last_chat_id($pdo),
        ]);

    case 'chat':
        if (!arena_is_member($pdo, $uid)) {
            json_response(['success' => false, 'error' => 'Non sei nell\'arena.'], 409);
        }
        $text = trim(preg_replace('/[\x00-\x1F\x7F]/u', '', (string)($in['text'] ?? '')));
        if ($text === '' || mb_strlen($text) > 200) {
            json_response(['success' => false, 'error' => 'Messaggio non valido.'], 400);
        }
        $ins = $pdo->prepare('INSERT INTO arena_chat (arena, user_id, username, text, created_at) VALUES (?, ?, ?, ?, ?)');
        $ins->execute([ARENA_ROOM, $uid, $uname, $text, $now]);
        // Mantiene solo gli ultimi ARENA_CHAT_KEEP messaggi
        $pdo->prepare('DELETE FROM arena_chat WHERE arena = ? AND id <= ?')
            ->execute([ARENA_ROOM, arena_last_chat_id($pdo) - ARENA_CHAT_KEEP]);
        json_response(['success' => true]);

    case 'leave':
        $pdo->prepare('DELETE FROM arena_players WHERE arena = ? AND user_id = ?')
            ->execute([ARENA_ROOM, $uid]);
        json_response(['success' => true]);

    default:
        json_response(['success' => false, 'error' => 'Azione non valida.'], 400);
}
