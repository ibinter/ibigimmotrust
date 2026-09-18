<?php
session_start();
if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/menu.php';

// Si on veut voir le détail d'une session
$sessionFilter = $_GET['session'] ?? null;

if ($sessionFilter) {
    $st = $pdo->prepare("SELECT * FROM bot_history WHERE session_id = ? ORDER BY created_at ASC");
    $st->execute([$sessionFilter]);
    $messages = $st->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Liste des sessions
    $st = $pdo->query("
        SELECT session_id,
               MIN(created_at) AS started_at,
               MAX(created_at) AS last_at,
               COUNT(*) AS total
        FROM bot_history
        GROUP BY session_id
        ORDER BY last_at DESC
        LIMIT 100
    ");
    $sessions = $st->fetchAll(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Conversations BOT – IBIG IMMO TRUST</title>
<style>
body{
    font-family:system-ui,sans-serif;
    background:#f4f6fb;
    padding:20px;
}
h1{ color:#003c96; margin-bottom:16px; }
.table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 3px 10px rgba(0,0,0,0.06);
}
.table th{
    background:#003c96;
    color:#fff;
    padding:10px;
    font-size:14px;
}
.table td{
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:14px;
}
.badge{
    padding:4px 8px;
    border-radius:6px;
    font-size:12px;
}
.msg-user{ background:#eff6ff; }
.msg-bot{ background:#f9fafb; }
</style>
</head>
<body>

<h1>🤖 Conversations BOT</h1>

<?php if (!$sessionFilter): ?>

<table class="table">
<tr>
    <th>Session</th>
    <th>Début</th>
    <th>Dernier message</th>
    <th>Messages</th>
    <th>Action</th>
</tr>
<?php foreach($sessions as $s): ?>
<tr>
    <td><?= htmlspecialchars(substr($s['session_id'],0,12)) ?>…</td>
    <td><?= htmlspecialchars($s['started_at']) ?></td>
    <td><?= htmlspecialchars($s['last_at']) ?></td>
    <td><?= (int)$s['total'] ?></td>
    <td><a href="bot_conversations.php?session=<?= urlencode($s['session_id']) ?>">Voir détails</a></td>
</tr>
<?php endforeach; ?>
</table>

<?php else: ?>

<p><a href="bot_conversations.php">&larr; Retour à la liste</a></p>

<table class="table">
<tr>
    <th>Heure</th>
    <th>Émetteur</th>
    <th>Message</th>
</tr>
<?php foreach($messages as $m): ?>
<tr class="msg-<?= $m['sender'] === 'user' ? 'user' : 'bot' ?>">
    <td><?= htmlspecialchars($m['created_at']) ?></td>
    <td><?= $m['sender']==='user' ? 'Client' : 'BOT' ?></td>
    <td style="white-space:pre-line;"><?= nl2br(htmlspecialchars($m['message'])) ?></td>
</tr>
<?php endforeach; ?>
</table>

<?php endif; ?>

</body>
</html>
