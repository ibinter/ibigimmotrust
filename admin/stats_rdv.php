<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

include __DIR__ . '/menu.php';

// Calcul des stats globales
$total = $pdo->query("SELECT COUNT(*) FROM rdv_requests")->fetchColumn();
$pending = $pdo->query("SELECT COUNT(*) FROM rdv_requests WHERE status='En attente'")->fetchColumn();
$confirmed = $pdo->query("SELECT COUNT(*) FROM rdv_requests WHERE status='Confirmé'")->fetchColumn();
$done = $pdo->query("SELECT COUNT(*) FROM rdv_requests WHERE status='Traité'")->fetchColumn();

// Stats par mois
$monthly = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS m, COUNT(*) AS total
    FROM rdv_requests
    GROUP BY m
    ORDER BY m ASC
")->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$data = [];
foreach ($monthly as $m){
    $labels[] = $m['m'];
    $data[] = $m['total'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Statistiques RDV – IBIG IMMO TRUST</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    font-family:system-ui, sans-serif;
    background:#f4f6fb;
    padding:20px;
}
h1{
    color:#003c96;
    font-weight:800;
}
.stats{
    display:flex;
    gap:20px;
    margin-top:20px;
    flex-wrap:wrap;
}
.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    flex:1;
    min-width:200px;
}
.card h2{
    margin:0;
    font-size:18px;
    color:#003c96;
}
.card .value{
    font-size:32px;
    font-weight:700;
    margin-top:8px;
}
.chart-box{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
    margin-top:25px;
}
</style>

</head>
<body>

<h1> Statistiques des Rendez-vous</h1>

<div class="stats">

    <div class="card">
        <h2>Total RDV</h2>
        <div class="value"><?= $total ?></div>
    </div>

    <div class="card">
        <h2>En attente</h2>
        <div class="value"><?= $pending ?></div>
    </div>

    <div class="card">
        <h2>Confirmés</h2>
        <div class="value"><?= $confirmed ?></div>
    </div>

    <div class="card">
        <h2>Traités</h2>
        <div class="value"><?= $done ?></div>
    </div>

</div>

<div class="chart-box">
    <h2> Évolution mensuelle des RDV</h2>
    <canvas id="rdvChart" height="120"></canvas>
</div>

<script>
const ctx = document.getElementById('rdvChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'RDV par mois',
            data: <?= json_encode($data) ?>,
            borderWidth: 3,
            tension: 0.4,
            borderColor: '#003c96',
            backgroundColor: 'rgba(0,60,150,0.08)',
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

</body>
</html>
