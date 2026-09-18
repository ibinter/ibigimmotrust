<?php
session_start();
if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

require_once __DIR__ . '/../includes/config.php';
include __DIR__ . '/menu.php';

// Filtre
$search = trim($_GET['s'] ?? '');
$from   = $_GET['from'] ?? '';
$to     = $_GET['to'] ?? '';

$where  = " WHERE 1 ";
$params = [];

// Recherche générale
if ($search !== '') {
    $where .= " AND (user_message LIKE :s OR bot_message LIKE :s OR intent LIKE :s OR session_id LIKE :s)";
    $params[':s'] = "%$search%";
}

// Période
if ($from !== '' && $to !== '') {
    $where .= " AND DATE(created_at) BETWEEN :df AND :dt";
    $params[':df'] = $from;
    $params[':dt'] = $to;
}

$sql = "SELECT * FROM bot_history $where ORDER BY created_at DESC";
$st = $pdo->prepare($sql);
$st->execute($params);
$rows = $st->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Conversations BOT – IBIG IMMO TRUST</title>
<style>
body{
    background:#f4f6fb;
    font-family:system-ui, sans-serif;
    padding:20px;
}
h1{
    color:#003c96;
    font-size:24px;
    font-weight:700;
}
.filters{
    background:#fff;
    padding:15px;
    border-radius:10px;
    margin-bottom:20px;
    box-shadow:0 3px 10px rgba(0,0,0,0.06);
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}
.filters input{
    padding:8px 10px;
    border-radius:6px;
    border:1px solid #d1d5db;
}
.btn{
    background:#003c96;
    color:#fff;
    padding:8px 14px;
    border-radius:6px;
    border:none;
    cursor:pointer;
}
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
    font-size:14px;
}
td{
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:14px;
}
.intent{
    padding:4px 8px;
    border-radius:6px;
    color:#fff;
    font-size:12px;
    display:inline-block;
    text-transform:capitalize;
}
.intent.achat{background:#0ea5e9;}
.intent.location{background:#10b981;}
.intent.construction{background:#8b5cf6;}
.intent.btp{background:#dc2626;}
.intent.gestion{background:#f59e0b;}
.intent.investissement{background:#2563eb;}
.intent.rdv{background:#9333ea;}
.intent.prix{background:#6b7280;}
.intent.agent{background:#3b82f6;}
.intent.bien{background:#22c55e;}
.intent.general{background:#475569;}
</style>
</head>
<body>

<h1>🤖 Historique des conversations BOT</h1>

<form method="get" class="filters">
    <input type="text" name="s" placeholder="Recherche..." value="<?=htmlspecialchars($search)?>">
    <input type="date" name="from" value="<?=$from?>">
    <input type="date" name="to" value="<?=$to?>">
    <button class="btn">Filtrer</button>
    <a class="btn" href="bot_history.php" style="background:#10b981;">Réinitialiser</a>
</form>

<table>
<tr>
    <th>Session</th>
    <th>Message client</th>
    <th>Réponse bot</th>
    <th>Intent</th>
    <th>Date</th>
</tr>

<?php foreach ($rows as $r): ?>
<tr>
    <td><?= substr($r['session_id'], 0, 10) ?>…</td>
    <td style="white-space:pre-line;"><?= nl2br(htmlspecialchars($r['user_message'])) ?></td>
    <td style="white-space:pre-line;background:#f9fafc;"><?= nl2br(htmlspecialchars($r['bot_message'])) ?></td>
    <td><span class="intent <?=$r['intent']?>"><?=$r['intent']?></span></td>
    <td><?= $r['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
