<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

// Protection admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// ===================== STATISTIQUES =====================
$total = $pdo->query("SELECT COUNT(*) FROM visits")->fetchColumn();
$total_today = $pdo->query("SELECT COUNT(*) FROM visits WHERE DATE(created_at)=CURDATE()")->fetchColumn();
$total_unique = $pdo->query("SELECT COUNT(DISTINCT ip) FROM visits")->fetchColumn();

// visites par device
$stats_device = $pdo->query("
    SELECT device, COUNT(*) AS total
    FROM visits
    GROUP BY device
")->fetchAll(PDO::FETCH_ASSOC);

// visites des 7 derniers jours
$stats_jours = $pdo->query("
    SELECT DATE(created_at) AS jour, COUNT(*) AS total
    FROM visits
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY jour
    ORDER BY jour ASC
")->fetchAll(PDO::FETCH_ASSOC);

// dernières visites
$visites = $pdo->query("
    SELECT *
    FROM visits
    ORDER BY created_at DESC
    LIMIT 100
")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Statistiques des Visites – IBIG IMMO TRUST</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{font-family:system-ui;background:#f4f6fb;margin:0;}
.page{padding:25px;}
h1{font-size:24px;font-weight:800;color:#003c96;margin-bottom:10px;}
.kpi-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:15px;margin-bottom:25px;}
.kpi{background:white;padding:15px;border-radius:10px;box-shadow:0 2px 6px rgba(0,0,0,0.08);}
.kpi .label{font-size:13px;color:#777;}
.kpi .value{font-size:26px;font-weight:700;color:#003c96;}

.box{background:white;padding:20px;border-radius:12px;box-shadow:0 2px 6px rgba(0,0,0,0.08);margin-top:25px;}
table{width:100%;border-collapse:collapse;font-size:13px;margin-top:10px;}
th,td{padding:8px;border-bottom:1px solid #eee;}
th{background:#f3f4f6;text-align:left;color:#555;}
tr:hover td{background:#f8fbff;}
</style>
</head>

<body>

<?php include __DIR__ . '/menu.php'; ?>

<div class="page">

<h1>📊 Statistiques des visites</h1>

<div class="kpi-grid">
    <div class="kpi">
        <div class="label">Visites totales</div>
        <div class="value"><?= $total ?></div>
    </div>

    <div class="kpi">
        <div class="label">Visites aujourd’hui</div>
        <div class="value"><?= $total_today ?></div>
    </div>

    <div class="kpi">
        <div class="label">Visiteurs uniques</div>
        <div class="value"><?= $total_unique ?></div>
    </div>
</div>

<!-- =================== GRAPHIQUE VISITES 7 JOURS =================== -->
<div class="box">
    <h3 style="color:#003c96;margin-bottom:10px;">📅 Visites des 7 derniers jours</h3>
    <canvas id="chart_jours"></canvas>
</div>

<!-- =================== GRAPHIQUE PAR DEVICE =================== -->
<div class="box">
    <h3 style="color:#003c96;margin-bottom:10px;">📱 Répartition par device</h3>
    <canvas id="chart_device"></canvas>
</div>

<!-- =================== TABLEAU DES VISITES =================== -->
<div class="box">
    <h3 style="color:#003c96;margin-bottom:10px;">📋 Dernières visites</h3>

<table>
<tr>
    <th>Date</th>
    <th>IP</th>
    <th>Page</th>
    <th>Device</th>
    <th>Browser</th>
    <th>OS</th>
</tr>

<?php foreach($visites as $v): ?>
<tr>
    <td><?= $v['created_at'] ?></td>
    <td><?= $v['ip'] ?></td>
    <td><?= $v['page'] ?></td>
    <td><?= $v['device'] ?></td>
    <td><?= $v['browser'] ?></td>
    <td><?= $v['os'] ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>

</div>

<script>
// ========= VISITES PAR JOUR ==========
new Chart(document.getElementById('chart_jours'), {
    type: 'line',
    data: {
        labels: [<?php foreach($stats_jours as $s) echo "'".$s['jour']."',"; ?>],
        datasets: [{
            label: 'Visites',
            data: [<?php foreach($stats_jours as $s) echo $s['total'].","; ?>],
            borderColor:'#003c96',
            backgroundColor:'rgba(0,60,150,0.15)',
            fill:true,
            tension:0.3
        }]
    }
});

// ========= REPARTITION DEVICE ==========
new Chart(document.getElementById('chart_device'), {
    type: 'doughnut',
    data: {
        labels: [<?php foreach($stats_device as $s) echo "'".$s['device']."',"; ?>],
        datasets: [{
            data: [<?php foreach($stats_device as $s) echo $s['total'].","; ?>],
            backgroundColor:['#003c96','#ff9f1c','#e30613','#0ea5e9']
        }]
    },
    options:{legend:{position:'bottom'}}
});
</script>

</body>
</html>
