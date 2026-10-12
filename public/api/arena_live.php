<?php
/**
 * Zero World — ZeroAgar Classic LIVE Arena
 * Multiplayer reale sulla stessa arena + chat della stanza globale.
 *
 * Novità:
 *  - Mangiata parziale: quando un giocatore mangia UNA cella di un altro, si perde solo quella cella.
 *    Il giocatore muore solo quando non gli resta nessuna cella.
 *    La cella persa viene comunicata al giocatore colpito tramite il campo "lost" della risposta di update.
 *  - Ruoli staff nella chat: admin (rosso), mod (verde), altri ruoli con colori propri.
 *  - Colonne aggiunte automaticamente se mancano (pending_json, role).
 */
declare(strict_types=1);
require_once __DIR__ . '/db.php';

$user = require_login_api();
$pdo = db();
$ARENA = 'ZeroArcade:global';
try {
    $pdo->exec("UPDATE zeroagar_arena_players SET arena_key='ZeroArcade:global' WHERE arena_key='zeroagar_classic:global'");
} catch (Throwable $e) {}

$input = ($_SERVER['REQUEST_METHOD'] ?? '') === 'GET' ? $_GET : read_json_post();
$action = (string)($input['action'] ?? 'sync');

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'GET') enforce_same_origin();

$pdo->exec("CREATE TABLE IF NOT EXISTS zeroagar_arena_players (
    user_id VARCHAR(24) NOT NULL PRIMARY KEY,
    arena_key VARCHAR(48) NOT NULL DEFAULT 'ZeroArcade:global',
    username VARCHAR(30) NOT NULL,
    color VARCHAR(32) NOT NULL DEFAULT '#1db7ef',
    team TINYINT NULL,
    cells_json MEDIUMTEXT NOT NULL,
    score INT NOT NULL DEFAULT 0,
    alive TINYINT(1) NOT NULL DEFAULT 1,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_arena_seen (arena_key, updated_at),
    KEY idx_arena_alive (arena_key, alive)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$pdo->exec("CREATE TABLE IF NOT EXISTS zeroagar_arena_chat (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    arena_key VARCHAR(48) NOT NULL,
    user_id VARCHAR(24) NOT NULL,
    username VARCHAR(30) NOT NULL,
    text VARCHAR(200) NOT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_arena_chat (arena_key, id),
    KEY idx_chat_user (user_id, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

/* Aggiunge una colonna solo se manca (una volta per processo). */
function ensure_column(PDO $pdo, string $table, string $col, string $def): void {
    static $done = [];
    $key = $table . '.' . $col;
    if (isset($done[$key])) return;
    $st = $pdo->query("SHOW COLUMNS FROM `$table` LIKE " . $pdo->quote($col));
    if (!$st->fetch()) {
        $pdo->exec("ALTER TABLE `$table` ADD COLUMN `$col` $def");
    }
    $done[$key] = true;
}
ensure_column($pdo, 'zeroagar_arena_players', 'pending_json', 'MEDIUMTEXT NULL');
ensure_column($pdo, 'zeroagar_arena_players', 'role', "VARCHAR(16) NOT NULL DEFAULT 'user'");
ensure_column($pdo, 'zeroagar_arena_chat', 'role', "VARCHAR(16) NOT NULL DEFAULT 'user'");

/* Ruoli staff: etichetta e colore. Gli altri utenti non hanno etichetta. */
function role_meta(string $role): array {
    $map = [
        'admin'   => ['ADMIN',   '#ff3f58'],
        'mod'     => ['MOD',     '#58d100'],
        'vip'     => ['VIP',     '#f2c94c'],
        'helper'  => ['HELPER',  '#20b7ee'],
        'creator' => ['CREATOR', '#b197fc'],
    ];
    return $map[$role] ?? ['', ''];
}

function clean_cells($raw): array {
    $cells = is_array($raw) ? $raw : [];
    $out = [];
    foreach ($cells as $c) {
        if (!is_array($c)) continue;
        $x = (float)($c['x'] ?? 0);
        $y = (float)($c['y'] ?? 0);
        $m = (float)($c['m'] ?? 0);
        if (!is_finite($x) || !is_finite($y) || !is_finite($m) || $m < 1) continue;
        $out[] = [
            'x' => max(0, min(6000, $x)),
            'y' => max(0, min(6000, $y)),
            'm' => max(1, min(2000000, $m)),
            'r' => max(5, min(1600, isset($c['r']) && is_numeric($c['r']) ? (float)$c['r'] : sqrt(max(10, $m)))),
            'color' => substr((string)($c['color'] ?? '#1db7ef'), 0, 32),
            'team' => isset($c['team']) ? (int)$c['team'] : null,
        ];
        if (count($out) >= 16) break;
    }
    return $out;
}

/* Toglie dalla lista la cella più vicina a quella persa (stessa massa, posizione simile). */
function drop_nearest(array $cells, array $L): array {
    $best = -1;
    $bd = INF;
    foreach ($cells as $i => $c) {
        $d = hypot((float)$c['x'] - (float)$L['x'], (float)$c['y'] - (float)$L['y'])
           + abs((float)$c['m'] - (float)$L['m']) * 0.5;
        if ($d < $bd) { $bd = $d; $best = $i; }
    }
    if ($best >= 0 && $bd < 150) unset($cells[$best]);
    return array_values($cells);
}

function fetch_players(PDO $pdo, string $arena, string $me): array {
    $st = $pdo->prepare("SELECT user_id, username, color, team, cells_json, score, alive, role, updated_at
        FROM zeroagar_arena_players
        WHERE arena_key = ? AND updated_at >= (NOW() - INTERVAL 8 SECOND)
        ORDER BY score DESC LIMIT 80");
    $st->execute([$arena]);
    $out = [];
    foreach ($st->fetchAll() as $r) {
        $cells = json_decode((string)$r['cells_json'], true);
        if (!is_array($cells)) $cells = [];
        $rm = role_meta((string)($r['role'] ?? 'user'));
        $out[] = [
            'id' => (string)$r['user_id'],
            'username' => (string)$r['username'],
            'color' => (string)$r['color'],
            'team' => $r['team'] === null ? null : (int)$r['team'],
            'cells' => $cells,
            'score' => (int)$r['score'],
            'alive' => (bool)$r['alive'],
            'role_label' => $rm[0],
            'role_color' => $rm[1],
            'self' => (string)$r['user_id'] === $me,
        ];
    }
    return $out;
}

function clean_old(PDO $pdo, string $arena): void {
    $pdo->prepare('DELETE FROM zeroagar_arena_players WHERE arena_key = ? AND updated_at < (NOW() - INTERVAL 30 SECOND)')->execute([$arena]);
    $pdo->prepare('DELETE FROM zeroagar_arena_chat WHERE arena_key = ? AND id < (SELECT x.m FROM (SELECT COALESCE(MAX(id),0) - 1000 AS m FROM zeroagar_arena_chat WHERE arena_key = ?) x)')->execute([$arena, $arena]);
}

function push_chat(PDO $pdo, string $arena, array $user, string $text): void {
    $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');
    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $text) ?? '';
    $text = mb_substr($text, 0, 200);
    if ($text === '') json_response(['success' => false, 'error' => 'Messaggio vuoto.'], 400);
    $last = $pdo->prepare('SELECT created_at FROM zeroagar_arena_chat WHERE arena_key = ? AND user_id = ? ORDER BY id DESC LIMIT 1');
    $last->execute([$arena, $user['id']]);
    $lt = $last->fetchColumn();
    if ($lt && (time() - strtotime((string)$lt)) < 1) json_response(['success' => false, 'error' => 'Attendi un secondo tra i messaggi.'], 429);
    $role = (string)($user['role'] ?? 'user');
    $pdo->prepare('INSERT INTO zeroagar_arena_chat (arena_key, user_id, username, text, role) VALUES (?,?,?,?,?)')
        ->execute([$arena, $user['id'], $user['username'], $text, $role]);
}

/* Inserisce o riattiva la riga del giocatore (usata da join e dal rientro automatico in update). */
function upsert_player(PDO $pdo, string $arena, array $user, string $color, ?int $team, array $cells, int $score): void {
    $role = (string)($user['role'] ?? 'user');
    $pdo->prepare("INSERT INTO zeroagar_arena_players (user_id, arena_key, username, color, team, cells_json, score, alive, role, updated_at)
        VALUES (?,?,?,?,?,?,?,1,?,NOW())
        ON DUPLICATE KEY UPDATE arena_key=VALUES(arena_key), username=VALUES(username), color=VALUES(color), team=VALUES(team), cells_json=VALUES(cells_json), score=VALUES(score), alive=1, role=VALUES(role), pending_json=NULL, updated_at=NOW()")->execute([
        $user['id'], $arena, $user['username'], $color, $team, json_encode($cells, JSON_UNESCAPED_UNICODE), $score, $role
    ]);
}

if ($action === 'join') {
    clean_old($pdo, $ARENA);
    $color = substr((string)($input['color'] ?? ($user['profile']['skin_color'] ?? '#1db7ef')), 0, 32);
    $team = isset($input['team']) && $input['team'] !== '' ? max(0, min(2, (int)$input['team'])) : null;
    $cells = clean_cells($input['cells'] ?? []);
    $score = max(0, min(2000000, (int)($input['score'] ?? 25)));
    if (!$cells) $cells = [['x'=>3000,'y'=>3000,'m'=>25,'r'=>5,'color'=>$color,'team'=>$team]];
    upsert_player($pdo, $ARENA, $user, $color, $team, $cells, $score);
    touch_presence($user['id']);
    json_response(['success'=>true,'arena'=>$ARENA,'players'=>fetch_players($pdo,$ARENA,$user['id'])]);
}

if ($action === 'leave') {
    $pdo->prepare('DELETE FROM zeroagar_arena_players WHERE user_id = ? AND arena_key = ?')->execute([$user['id'], $ARENA]);
    json_response(['success'=>true]);
}

if ($action === 'chat') {
    push_chat($pdo, $ARENA, $user, (string)($input['text'] ?? ''));
    json_response(['success'=>true]);
}

if ($action === 'update') {
    $cells = clean_cells($input['cells'] ?? []);
    $score = max(0, min(2000000, (int)($input['score'] ?? 0)));
    $color = substr((string)($input['color'] ?? '#1db7ef'), 0, 32);
    $team = isset($input['team']) && $input['team'] !== '' ? max(0, min(2, (int)$input['team'])) : null;
    $role = (string)($user['role'] ?? 'user');

    $st = $pdo->prepare('SELECT alive FROM zeroagar_arena_players WHERE user_id = ? AND arena_key = ? LIMIT 1');
    $st->execute([$user['id'], $ARENA]);
    $alive = $st->fetchColumn();

    if ($alive === false) {
        if (!$cells) json_response(['success'=>false,'error'=>'join','need_join'=>true], 409);
        upsert_player($pdo, $ARENA, $user, $color, $team, $cells, max(1, $score));
        $alive = 1;
    }
    if (!(bool)$alive) json_response(['success'=>true,'dead'=>true]);

    $lost = [];
    $gained = 0;
    $serverScore = $score;

    $pdo->beginTransaction();
    try {
        $stMine = $pdo->prepare('SELECT score, pending_json FROM zeroagar_arena_players WHERE user_id=? AND arena_key=? FOR UPDATE');
        $stMine->execute([$user['id'], $ARENA]);
        $mine = $stMine->fetch() ?: [];
        $serverScore = max((int)($mine['score'] ?? 0), $score);

        /* Celle che un altro giocatore ci ha mangiato: le togliamo dall'invio e le comunichiamo al client */
        $pending = json_decode((string)($mine['pending_json'] ?? '[]'), true);
        if (!is_array($pending)) $pending = [];
        foreach ($pending as $L) {
            if (!is_array($L)) continue;
            $cells = drop_nearest($cells, $L);
            $lost[] = ['x' => (float)$L['x'], 'y' => (float)$L['y'], 'm' => (float)$L['m']];
        }

        $pdo->prepare('UPDATE zeroagar_arena_players SET username=?, color=?, team=?, role=?, cells_json=?, score=?, pending_json=NULL, alive=1, updated_at=NOW() WHERE user_id=? AND arena_key=?')
            ->execute([$user['username'], $color, $team, $role, json_encode($cells, JSON_UNESCAPED_UNICODE), $serverScore, $user['id'], $ARENA]);

        /* Mangiata parziale: il giocatore più grande mangia una cella, la vittima perde solo quella cella */
        $others = $pdo->prepare('SELECT user_id, team, cells_json, score, pending_json FROM zeroagar_arena_players WHERE arena_key=? AND user_id<>? AND alive=1 AND updated_at >= (NOW() - INTERVAL 8 SECOND) FOR UPDATE');
        $others->execute([$ARENA, $user['id']]);
        foreach ($others->fetchAll() as $o) {
            if ($team !== null && $o['team'] !== null && (int)$team === (int)$o['team']) continue;
            $oc = json_decode((string)$o['cells_json'], true);
            if (!is_array($oc) || !$oc) continue;

            $eatenList = [];
            $gainHere = 0;
            foreach ($cells as $mc) {
                foreach ($oc as $idx => $ocell) {
                    if (!is_array($ocell)) continue;
                    $dx = (float)$mc['x'] - (float)($ocell['x'] ?? 0);
                    $dy = (float)$mc['y'] - (float)($ocell['y'] ?? 0);
                    $d  = sqrt($dx * $dx + $dy * $dy);
                    $mr = (float)($mc['r'] ?? sqrt(max(10, (float)$mc['m'])));
                    $or = (float)($ocell['r'] ?? sqrt(max(10, (float)($ocell['m'] ?? 10))));
                    $mm = (float)($mc['m'] ?? 0);
                    $om = (float)($ocell['m'] ?? 0);
                    if ($d < max(1, $mr * .92) && $mm > $om * 1.10 && $mr > $or * 1.05) {
                        $eatenList[] = ['x' => (float)($ocell['x'] ?? 0), 'y' => (float)($ocell['y'] ?? 0), 'm' => $om];
                        $gainHere += (int)round($om);
                        unset($oc[$idx]);
                        break;
                    }
                }
            }
            if (!$eatenList) continue;

            $serverScore += $gainHere;
            $gained += $gainHere;

            $oc = array_values($oc);
            $oScore = max(0, (int)$o['score'] - $gainHere);
            $oPending = json_decode((string)($o['pending_json'] ?? '[]'), true);
            if (!is_array($oPending)) $oPending = [];
            foreach ($eatenList as $e) $oPending[] = $e;

            if (!$oc) {
                /* non gli resta nessuna cella: è davvero morto */
                $pdo->prepare("UPDATE zeroagar_arena_players SET alive=0, cells_json='[]', score=0, pending_json=NULL WHERE user_id=? AND arena_key=?")
                    ->execute([$o['user_id'], $ARENA]);
            } else {
                $pdo->prepare('UPDATE zeroagar_arena_players SET cells_json=?, score=?, pending_json=? WHERE user_id=? AND arena_key=?')
                    ->execute([json_encode($oc, JSON_UNESCAPED_UNICODE), $oScore, json_encode($oPending, JSON_UNESCAPED_UNICODE), $o['user_id'], $ARENA]);
            }
        }

        $pdo->prepare('UPDATE zeroagar_arena_players SET score=? WHERE user_id=? AND arena_key=?')->execute([$serverScore, $user['id'], $ARENA]);
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        throw $e;
    }
    touch_presence($user['id']);
    clean_old($pdo, $ARENA);
    json_response([
        'success' => true,
        'server_score' => $serverScore,
        'gained' => $gained,
        'lost' => $lost,
        'players' => fetch_players($pdo, $ARENA, $user['id']),
    ]);
}

if ($action === 'sync') {
    clean_old($pdo, $ARENA);
    touch_presence($user['id']);
    $since = max(0, (int)($input['since'] ?? ($_GET['since'] ?? 0)));
    $players = fetch_players($pdo, $ARENA, $user['id']);
    if ($since > 0) {
        $st = $pdo->prepare('SELECT id, username, text, role, created_at FROM zeroagar_arena_chat WHERE arena_key=? AND id>? ORDER BY id ASC LIMIT 100');
        $st->execute([$ARENA, $since]);
        $rows = $st->fetchAll();
    } else {
        $st = $pdo->prepare('SELECT id, username, text, role, created_at FROM zeroagar_arena_chat WHERE arena_key=? ORDER BY id DESC LIMIT 60');
        $st->execute([$ARENA]);
        $rows = array_reverse($st->fetchAll());
    }
    $messages = [];
    foreach ($rows as $r) {
        $rm = role_meta((string)($r['role'] ?? 'user'));
        $messages[] = [
            'id' => (int)$r['id'],
            'username' => $r['username'],
            'text' => $r['text'],
            'role_label' => $rm[0],
            'role_color' => $rm[1],
            'created_at' => date('c', strtotime((string)$r['created_at'])),
        ];
    }
    $lastChat = $messages ? (int)$messages[count($messages)-1]['id'] : $since;
    json_response(['success'=>true,'players'=>$players,'messages'=>$messages,'last_chat'=>$lastChat,'online_count'=>count($players)]);
}

json_response(['success'=>false,'error'=>'Azione arena non valida.'], 400);
