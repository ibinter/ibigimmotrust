<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

// Sécurité : accès admin uniquement
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Récupération des temps de visite
$visits = $pdo->query("
    SELECT * 
    FROM visit_time 
    ORDER BY created_at DESC
    LIMIT 200
")->fetchAll(PDO::FETCH_ASSOC);

// Statistiques : top des pages les plus visitées
$top_pages = $pdo->query("
    SELECT page, COUNT(*) AS vues, AVG(time_spent) AS moyenne
    FROM visit_time
    GROUP BY page
    ORDER BY vues DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>📊 Temps de visite – IBIG IMMO ADMIN</title>
<style>
body{
    font-family:system-ui;
    background:#f4f6fb;
    margin:0;padding:0;
}
.page-wrapper{ padding:25px; }

h1{
    font-size:24px;
    color:#003c96;
    font-weight:800;
}

.section-title{
    font-size:18px;
    font-weight:700;
    margin:20px 0 10px;
    color:#003c96;
}

/* TABLES */
.table-box{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 2px 6px rgba(0,0,0,0.08);
    margin-bottom:30px;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:14px;
}

th{
    background:#003c96;
    color:white;
    padding:10px;
    text-align:left;
}

td{
    padding:8px;
    border-bottom:1px solid #eee;
}

tr:hover td{
    background:#f4faff;
}
</style>
</head>

<body>

<?php include __DIR__ . '/menu.php'; ?>

<div class="page-wrapper">

<h1>📊 Analyse du temps de visite</h1>

<!-- TOP PAGES -->
<div class="table-box">
    <div class="section-title">🏆 Pages les plus visitées</div>
    <table>
        <tr>
            <th>Page</th>
            <th>Vues</th>
            <th>Temps moyen (sec)</th>
        </tr>

        <?php foreach($top_pages as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['page']) ?></td>
            <td><?= $p['vues'] ?></td>
            <td><?= number_format($p['moyenne'], 1) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<!-- LISTE COMPLETE -->
<div class="table-box">
    <div class="section-title">📄 Détails des visites récentes</div>
    <table>
        <tr>
            <th>Page</th>
            <th>Temps (sec)</th>
            <th>Temps (min)</th>
            <th>IP</th>
            <th>Date</th>
        </tr>

        <?php foreach($visits as $v): ?>
        <tr>
            <td><?= htmlspecialchars($v['page']) ?></td>
            <td><?= $v['time_spent'] ?>s</td>
            <td><?= number_format($v['time_spent']/60, 2) ?> min</td>
            <td><?= $v['ip'] ?></td>
            <td><?= $v['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>

    </table>
</div>

</div>

</body>
</html>
