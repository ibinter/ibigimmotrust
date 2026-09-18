<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

// Protection admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* ========================================================
   1) KPIs
======================================================= */

$total_events = (int)$pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$events_today = (int)$pdo->query("
    SELECT COUNT(*) FROM events 
    WHERE DATE(created_at)=CURDATE()
")->fetchColumn();
$unique_events = (int)$pdo->query("
    SELECT COUNT(DISTINCT event) FROM events
")->fetchColumn();

/* ========================================================
   2) Types d’événements
======================================================= */
$stats_types = $pdo->query("
    SELECT event, COUNT(*) AS total
    FROM events
    GROUP BY event
    ORDER BY total DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* ========================================================
   3) Événements des 7 derniers jours
======================================================= */
$stats_jours = $pdo->query("
    SELECT DATE(created_at) AS jour, COUNT(*) AS total
    FROM events
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY jour
    ORDER BY jour ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* ========================================================
   4) Derniers événements détaillés
======================================================= */
$events = $pdo->query("
    SELECT *
    FROM events
    ORDER BY created_at DESC
    LIMIT 150
")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Événements – IBIG IMMO TRUST</title>
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

<h1>📈 Suivi des événements – IBIG IMMO TRUST</h1>

<!-- ===================== KPIs ===================== -->
<div class="kpi-grid">
    <div class="kpi">
        <div class="label">Événements enregistrés</div>
        <div class="value"><?= $total_events ?></div>
    </div>

    <div class="kpi">
        <div class="label">Aujourd’hui</div>
        <div class="value"><?= $events_today ?></div>
    </div>

    <div class="kpi">
        <div class="label">Types d’événements</div>
        <div class="value"><?= $unique_events ?></div>
    </div>
</div>

<!-- ===================== GRAPHIQUE 7 JOURS ===================== -->
<div class="box">
    <h3 style="color:#003c96;margin-bottom:10px;">📅 Activité des 7 derniers jours</h3>
    <canvas id="chart_jours"></canvas>
</div>

<!-- ===================== GRAPHIQUE TYPES ===================== -->
<div class="box">
    <h3 style="color:#003c96;margin-bottom:10px;">📌 Répartition des types d'événements</h3>
    <canvas id="chart_types"></canvas>
</div>

<!-- ===================== TABLEAU DES ÉVÉNEMENTS ===================== -->
<div class="box">
    <h3 style="color:#003c96;margin-bottom:10px;">📋 Derniers événements enregistrés</h3>

<table>
<tr>
    <th>Date</th>
    <th>Événement</th>
    <th>Valeur</th>
    <th>IP</th>
    <th>Page</th>
</tr>

<?php foreach($events as $e): ?>
<tr>
    <td><?= $e['created_at'] ?></td>
    <td><strong><?= htmlspecialchars($e['event']) ?></strong></td>
    <td><?= htmlspecialchars($e['value']) ?></td>
    <td><?= $e['ip'] ?></td>
    <td><?= $e['page'] ?></td>
</tr>
<?php endforeach; ?>

</table>
</div>

</div>

<!-- ===================== GRAPHIQUES JS ===================== -->
<script>
// ------- GRAPH 7 JOURS -------
new Chart(document.getElementById('chart_jours'), {
    type: 'line',
    data: {
        labels: [<?php foreach($stats_jours as $s) echo "'".$s['jour']."',"; ?>],
        datasets: [{
            label: 'Événements',
            data: [<?php foreach($stats_jours as $s) echo $s['total'].","; ?>],
            borderColor:'#e30613',
            backgroundColor:'rgba(227, 6, 19, 0.15)',
            tension:0.3,
            fill:true
        }]
    }
});

// ------- GRAPH TYPES -------
new Chart(document.getElementById('chart_types'), {
    type: 'bar',
    data: {
        labels: [<?php foreach($stats_types as $t) echo "'".$t['event']."',"; ?>],
        datasets: [{
            label:'Total',
            data: [<?php foreach($stats_types as $t) echo $t['total'].","; ?>],
            backgroundColor:'#003c96'
        }]
    },
    options:{indexAxis:'y'}
});
</script>

</body>
</html>
