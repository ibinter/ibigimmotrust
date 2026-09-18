<?php
session_start();
if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/menu.php';

$st = $pdo->query("
    SELECT l.*, a.name AS agent_name
    FROM immo_leads l
    LEFT JOIN agents a ON l.assigned_to = a.id
    ORDER BY l.created_at DESC
    LIMIT 200
");
$leads = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Leads BOT – IBIG IMMO TRUST</title>
<style>
body{
    font-family:system-ui,sans-serif;
    background:#f4f6fb;
    padding:20px;
}
h1{ color:#003c96; margin-bottom:16px; }
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 3px 10px rgba(0,0,0,0.06);
}
th{
    background:#003c96;
    color:#fff;
    padding:10px;
    font-size:13px;
}
td{
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:13px;
}
.badge{
    padding:3px 8px;
    border-radius:999px;
    font-size:11px;
    color:#fff;
}
.badge-achat{ background:#2563eb; }
.badge-location{ background:#16a34a; }
.badge-construction,
.badge-btp{ background:#ea580c; }
.badge-gestion_locative{ background:#0f766e; }
.badge-investissement{ background:#7c3aed; }
.badge-general{ background:#6b7280; }
</style>
</head>
<body>

<h1>📋 Leads issus du BOT IBIG IMMO</h1>

<table>
<tr>
    <th>#</th>
    <th>Nom</th>
    <th>Téléphone</th>
    <th>Besoin</th>
    <th>Intent</th>
    <th>Agent assigné</th>
    <th>Canal</th>
    <th>Date</th>
</tr>
<?php foreach($leads as $l): 
    $cls = 'badge-'.($l['intent'] ?: 'general');
?>
<tr>
    <td><?= (int)$l['id'] ?></td>
    <td><?= htmlspecialchars($l['name']) ?></td>
    <td><?= htmlspecialchars($l['phone']) ?></td>
    <td><?= nl2br(htmlspecialchars($l['need'])) ?></td>
    <td><span class="badge <?= $cls ?>"><?= htmlspecialchars($l['intent'] ?: 'general') ?></span></td>
    <td><?= htmlspecialchars($l['agent_name'] ?: '—') ?></td>
    <td><?= htmlspecialchars($l['canal'] ?? '') ?></td>
    <td><?= htmlspecialchars($l['created_at']) ?></td>
</tr>
<?php endforeach; ?>
</table>

</body>
</html>
