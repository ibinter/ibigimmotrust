<?php
require_once __DIR__ . '/../includes/config.php';
session_start();

if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

/* ============================================================
   RÉCUPÉRATION DES DONNÉES STATISTIQUES
============================================================ */

// Leads IMMO
$stats_leads = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS mois, COUNT(*) AS total
    FROM immo_leads
    GROUP BY mois ORDER BY mois
")->fetchAll(PDO::FETCH_ASSOC);

// RDV clients
$stats_rdv = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS mois, COUNT(*) AS total
    FROM rdv_requests
    GROUP BY mois ORDER BY mois
")->fetchAll(PDO::FETCH_ASSOC);

// Messages
$stats_messages = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS mois, COUNT(*) AS total
    FROM contact_messages
    GROUP BY mois ORDER BY mois
")->fetchAll(PDO::FETCH_ASSOC);

// Répartition des statuts de biens
$statut_biens = $pdo->query("
    SELECT statut, COUNT(*) AS total
    FROM immo_biens
    GROUP BY statut
")->fetchAll(PDO::FETCH_ASSOC);

// Nombre de biens par type
$types_biens = $pdo->query("
    SELECT type, COUNT(*) AS total
    FROM immo_biens
    GROUP BY type
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Statistiques – IBIG IMMO TRUST</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body{
        font-family:system-ui, sans-serif;
        background:#f4f6fb;
        padding:30px;
        color:#003c96;
    }
    h1{
        text-align:center;
        font-size:30px;
        margin-bottom:15px;
        font-weight:800;
    }
    .charts-container{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(420px,1fr));
        gap:30px;
        margin-top:30px;
    }
    .chart-box{
        background:white;
        padding:20px;
        border-radius:12px;
        box-shadow:0 4px 18px rgba(0,0,0,0.08);
    }
    .chart-box h3{
        font-size:18px;
        margin-bottom:15px;
        text-align:center;
        color:#003c96;
        font-weight:700;
    }
</style>

</head>
<body>

<h1> Tableau des Statistiques – IBIG IMMO TRUST</h1>

<div class="charts-container">

    <!-- Leads -->
    <div class="chart-box">
        <h3> Évolution des Leads IMMO</h3>
        <canvas id="chartLeads"></canvas>
    </div>

    <!-- RDV -->
    <div class="chart-box">
        <h3> RDV Clients (6 derniers mois)</h3>
        <canvas id="chartRDV"></canvas>
    </div>

    <!--  Messages -->
    <div class="chart-box">
        <h3> Messages reçus (6 derniers mois)</h3>
        <canvas id="chartMessages"></canvas>
    </div>

    <!-- Répartition statuts -->
    <div class="chart-box">
        <h3> Répartition des Statuts des Biens</h3>
        <canvas id="chartStatuts"></canvas>
    </div>

    <!--  Types biens -->
    <div class="chart-box">
        <h3> Répartition des Types de Biens</h3>
        <canvas id="chartTypes"></canvas>
    </div>

</div>

<script>
/* ============================================================
   GRAPHIQUE LEADS
============================================================ */
new Chart(document.getElementById('chartLeads'), {
    type:'line',
    data:{
        labels:[<?php foreach($stats_leads as $s){ echo "'".$s['mois']."',"; } ?>],
        datasets:[{
            label:'Leads IMMO',
            data:[<?php foreach($stats_leads as $s){ echo $s['total'].","; } ?>],
            borderColor:'#003c96',
            backgroundColor:'rgba(0,60,150,0.25)',
            borderWidth:3,
            tension:0.35,
            fill:true
        }]
    },
    options:{ responsive:true }
});

/* ============================================================
   GRAPHIQUE RDV
============================================================ */
new Chart(document.getElementById('chartRDV'), {
    type:'line',
    data:{
        labels:[<?php foreach($stats_rdv as $s){ echo "'".$s['mois']."',"; } ?>],
        datasets:[{
            label:'RDV Clients',
            data:[<?php foreach($stats_rdv as $s){ echo $s['total'].","; } ?>],
            borderColor:'#ff6a00',
            backgroundColor:'rgba(255,100,0,0.25)',
            borderWidth:3,
            tension:0.35,
            fill:true
        }]
    }
});

/* ============================================================
   GRAPHIQUE MESSAGES
============================================================ */
new Chart(document.getElementById('chartMessages'), {
    type:'line',
    data:{
        labels:[<?php foreach($stats_messages as $s){ echo "'".$s['mois']."',"; } ?>],
        datasets:[{
            label:'Messages',
            data:[<?php foreach($stats_messages as $s){ echo $s['total'].","; } ?>],
            borderColor:'#e30613',
            backgroundColor:'rgba(227,6,19,0.25)',
            borderWidth:3,
            tension:0.35,
            fill:true
        }]
    }
});

/* ============================================================
   STATUTS DES BIENS
============================================================ */
new Chart(document.getElementById('chartStatuts'), {
    type:'doughnut',
    data:{
        labels:[<?php foreach($statut_biens as $b){ echo "'".$b['statut']."',"; } ?>],
        datasets:[{
            data:[<?php foreach($statut_biens as $b){ echo $b['total'].","; } ?>],
            backgroundColor:['#003c96','#ff9f1c','#10b981','#e30613','#6b7280','#6366f1']
        }]
    }
});

/* ============================================================
   TYPES DE BIENS
============================================================ */
new Chart(document.getElementById('chartTypes'), {
    type:'bar',
    data:{
        labels:[<?php foreach($types_biens as $b){ echo "'".$b['type']."',"; } ?>],
        datasets:[{
            label:'Biens',
            data:[<?php foreach($types_biens as $b){ echo $b['total'].","; } ?>],
            backgroundColor:'#003c96'
        }]
    },
    options:{ scales:{ y:{ beginAtZero:true } } }
});
</script>

</body>
</html>