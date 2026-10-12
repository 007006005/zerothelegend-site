<?php
/**
 * api/game_api.php - Sistema B (Railway): sessione, profilo e progressi di ZeroAgar.
 * Azioni: me | game_progress | game_save | game_leaderboard
 */
declare(strict_types=1);
require_once __DIR__ . '/db.php';

$u = require_login_api();
$pdo = db();
$in = read_json_post();
$action = (string)($_GET['action'] ?? ($in['action'] ?? ''));
$game = 'zeroagar_classic';

if ($action === 'me') {
    $safe = $u;
    unset($safe['password_hash'], $safe['password'], $safe['pin']);
    $profile = json_decode((string)($u['profile_json'] ?? ''), true);
    json_response(['success' => true, 'user' => $safe, 'profile' => is_array($profile) ? $profile : []]);
}

if ($action === 'game_progress') {
    $st = $pdo->prepare('SELECT best_mass, games_played, total_time FROM game_progress WHERE game = ? AND user_id = ? LIMIT 1');
    $st->execute([$game, $u['id']]);
    $r = $st->fetch() ?: [];
    json_response(['ok' => true, 'progress' => [
        'best'  => (int)($r['best_mass'] ?? 0),
        'plays' => (int)($r['games_played'] ?? 0),
        'total' => (int)($r['total_time'] ?? 0),
    ]]);
}

if ($action === 'game_save') {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') json_response(['ok' => false, 'error' => 'Metodo non consentito'], 405);
    enforce_same_origin();
    $score     = max(0, min(600000, (int)($in['score'] ?? 0)));
    $data      = is_array($in['data'] ?? null) ? $in['data'] : [];
    $best      = max(0, min(600000, (int)($data['best'] ?? $score)));
    $plays     = max(1, min(1000000, (int)($data['plays'] ?? 1)));
    $total     = max(0, min(100000000, (int)($data['total'] ?? 0)));
    $kills     = max(0, min(2000000000, (int)($data['kills'] ?? 0)));
    $particles = max(0, min(2000000000, (int)($data['particles'] ?? 0)));

    $pdo->beginTransaction();
    try {
        $pdo->prepare('INSERT INTO arcade_scores (game, user_id, score, updated_at) VALUES (?, ?, ?, NOW())
            ON DUPLICATE KEY UPDATE score = GREATEST(score, VALUES(score)), updated_at = NOW()')
            ->execute([$game, $u['id'], $score]);

        $st = $pdo->prepare('SELECT best_mass, games_played, total_kills, total_particles, total_time FROM game_progress WHERE game = ? AND user_id = ? LIMIT 1');
        $st->execute([$game, $u['id']]);
        $old = $st->fetch();

        if ($old) {
            $pdo->prepare('UPDATE game_progress SET best_mass = ?, games_played = ?, total_kills = ?, total_particles = ?, total_time = ?, last_mass = ?, updated_at = NOW() WHERE game = ? AND user_id = ?')
                ->execute([
                    max((int)$old['best_mass'], $best, $score),
                    max((int)$old['games_played'], $plays),
                    max((int)$old['total_kills'], $kills),
                    max((int)$old['total_particles'], $particles),
                    max((int)$old['total_time'], $total),
                    $score, $game, $u['id'],
                ]);
        } else {
            $pdo->prepare('INSERT INTO game_progress (game, user_id, best_mass, games_played, total_kills, total_particles, total_time, last_mass, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())')
                ->execute([$game, $u['id'], max($best, $score), $plays, $kills, $particles, $total, $score]);
        }
        $pdo->commit();
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('game_save: ' . $e->getMessage());
        json_response(['ok' => false, 'error' => 'Salvataggio non riuscito'], 500);
    }
    json_response(['ok' => true]);
}

if ($action === 'game_leaderboard') {
    $limit = max(1, min(20, (int)($_GET['limit'] ?? ($in['limit'] ?? 10))));
    $st = $pdo->prepare('SELECT u.username, s.score FROM arcade_scores s JOIN users u ON u.id = s.user_id
        WHERE s.game = ? AND s.score > 0 AND u.is_banned = 0 ORDER BY s.score DESC LIMIT ' . $limit);
    $st->execute([$game]);
    json_response(['ok' => true, 'leaders' => $st->fetchAll()]);
}

json_response(['ok' => false, 'error' => 'Azione non valida'], 400);
