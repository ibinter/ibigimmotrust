<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';

/* ============================================================
   1) KPIs PRINCIPAUX IMMO
============================================================ */

// Messages contact
$total_messages = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();

// RDV
$total_rdv = (int)$pdo->query("SELECT COUNT(*) FROM rdv_requests")->fetchColumn();

// Leads IMMO
$total_leads = (int)$pdo->query("SELECT COUNT(*) FROM immo_leads")->fetchColumn();

// Biens totaux
$total_biens = (int)$pdo->query("SELECT COUNT(*) FROM immo_biens")->fetchColumn();

// Biens visibles (ta table a la colonne 'visible')
$biens_visibles = (int)$pdo->query("
    SELECT COUNT(*) FROM immo_biens
    WHERE visible = 1
")->fetchColumn();

// Biens non visibles
$biens_non_visibles = (int)$pdo->query("
    SELECT COUNT(*) FROM immo_biens
    WHERE visible = 0 OR visible IS NULL
")->fetchColumn();

// Biens avec image manquante
$alert_sans_image = (int)$pdo->query("
    SELECT COUNT(*) FROM immo_biens
    WHERE image_principale IS NULL OR image_principale = ''
")->fetchColumn();

// Biens en ligne depuis +60 jours
$alert_trop_anciens = (int)$pdo->query("
    SELECT COUNT(*) FROM immo_biens
    WHERE visible = 1 
      AND created_at IS NOT NULL
      AND created_at < DATE_SUB(CURDATE(), INTERVAL 60 DAY)
")->fetchColumn();

// Leads aujourd’hui
$leads_today = (int)$pdo->query("
    SELECT COUNT(*) FROM immo_leads
    WHERE DATE(created_at) = CURDATE()
")->fetchColumn();

// Leads des 30 derniers jours
$leads_30 = (int)$pdo->query("
    SELECT COUNT(*) FROM immo_leads
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
")->fetchColumn();


/* ============================================================
   2) STATS VISITES & EVENTS
============================================================ */

// Visites
$total_visits      = (int)$pdo->query("SELECT COUNT(*) FROM visits")->fetchColumn();
$unique_visitors   = (int)$pdo->query("SELECT COUNT(DISTINCT ip) FROM visits")->fetchColumn();
$today_visits      = (int)$pdo->query("SELECT COUNT(*) FROM visits WHERE DATE(created_at)=CURDATE()")->fetchColumn();

// Events (clics)
$total_events      = (int)$pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$click_whatsapp    = (int)$pdo->query("SELECT COUNT(*) FROM events WHERE event_name='click_whatsapp'")->fetchColumn();
$click_telephone   = (int)$pdo->query("SELECT COUNT(*) FROM events WHERE event_name='click_telephone'")->fetchColumn();
$click_email       = (int)$pdo->query("SELECT COUNT(*) FROM events WHERE event_name='click_email'")->fetchColumn();
$click_bouton      = (int)$pdo->query("SELECT COUNT(*) FROM events WHERE event_name='click_bouton'")->fetchColumn();

// Temps par page (TOP)
$time_top_pages = $pdo->query("
    SELECT page, SUM(time_spent) AS total_sec
    FROM visit_time
    GROUP BY page
    ORDER BY total_sec DESC
    LIMIT 7
")->fetchAll(PDO::FETCH_ASSOC);

// Temps total dernières 7 jours
$time_last7 = $pdo->query("
    SELECT DATE(created_at) AS d, SUM(time_spent) AS total_sec
    FROM visit_time
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY d
    ORDER BY d ASC
")->fetchAll(PDO::FETCH_ASSOC);


/* ============================================================
   3) DERNIERS ENREGISTREMENTS
============================================================ */

$last_messages = $pdo->query("
    SELECT name, phone, subject, created_at 
    FROM contact_messages
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$last_rdv = $pdo->query("
    SELECT name, phone, type, date_rdv, time_rdv, created_at
    FROM rdv_requests
    ORDER BY created_at DESC 
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$last_leads = $pdo->query("
    SELECT nom, telephone, type_demande, created_at
    FROM immo_leads
    ORDER BY created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);


/* ============================================================
   4) STATS LEADS – 6 derniers mois
============================================================ */

$stats_leads = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS mois,
           COUNT(*) AS total
    FROM immo_leads
    GROUP BY mois
    ORDER BY mois ASC
    LIMIT 6
")->fetchAll(PDO::FETCH_ASSOC);


/* ============================================================
   5) RÉPARTITION DES TYPES DE BIENS
============================================================ */

$stats_types = $pdo->query("
    SELECT type, COUNT(*) AS total
    FROM immo_biens
    WHERE type IS NOT NULL AND type <> ''
    GROUP BY type
    ORDER BY total DESC
")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard IMMO TRUST</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    font-family:system-ui;
    background:#f4f6fb;
    margin:0;
}
.admin-main{
    padding:25px;
}
h1{
    font-size:24px;
    font-weight:800;
    color:#003c96;
    margin:10px 0 5px;
}
.subtitle{
    color:#555;
    margin-bottom:20px;
    font-size:13px;
}

.kpi-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:15px;
}
.kpi-card{
    padding:15px;
    background:white;
    border-radius:10px;
    box-shadow:0 2px 6px rgba(0,0,0,0.06);
}
.kpi-label{ font-size:13px;color:#666; }
.kpi-value{ font-size:26px;font-weight:700;color:#003c96; }
.kpi-sub{ font-size:11px;color:#999; }

.alerts-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:12px;
    margin:20px 0;
}
.alert-card{
    padding:12px;
    border-radius:10px;
    background:white;
    box-shadow:0 2px 6px rgba(0,0,0,0.05);
    border-left:4px solid #ff9f1c;
}
.alert-critical{ border-left-color:#e30613; }
.alert-info{ border-left-color:#0ea5e9; }
.alert-warning{ border-left-color:#ff9f1c; }

.chart-box{
    margin-top:25px;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 2px 6px rgba(0,0,0,0.05);
}
.chart-title{
    font-weight:700;
    color:#003c96;
    margin-bottom:8px;
}

.lists-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(350px,1fr));
    gap:20px;
    margin-top:25px;
}
.list-box{
    background:white;
    padding:15px;
    border-radius:12px;
    box-shadow:0 2px 6px rgba(0,0,0,0.05);
}
table{
    width:100%;
    font-size:13px;
    border-collapse:collapse;
}
th{
    background:#f3f4f6;
    color:#555;
    text-align:left;
    padding:8px;
}
td{
    padding:8px;
    border-bottom:1px solid #eee;
}
tr:hover td{
    background:#f4faff;
}

/* Section stats visites */
.visits-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
    margin-top:25px;
}
.visit-card{
    background:white;
    padding:15px;
    border-radius:10px;
    box-shadow:0 2px 6px rgba(0,0,0,0.06);
}
</style>
</head>

<body>

<?php include __DIR__ . '/menu.php'; ?>

<div class="admin-main">

<h1>Tableau de bord – IBIG IMMO TRUST</h1>
<p class="subtitle">Vue globale : biens, leads, messages, RDV & trafic site</p>

<!-- ================== KPIs BIENS / LEADS ================== -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-label">Biens visibles</div>
        <div class="kpi-value"><?= $biens_visibles ?></div>
        <div class="kpi-sub">sur <?= $total_biens ?> biens</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Biens non visibles</div>
        <div class="kpi-value"><?= $biens_non_visibles ?></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Leads (30 derniers jours)</div>
        <div class="kpi-value"><?= $leads_30 ?></div>
        <div class="kpi-sub"><?= $leads_today ?> aujourd’hui</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Messages reçus</div>
        <div class="kpi-value"><?= $total_messages ?></div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Rendez-vous</div>
        <div class="kpi-value"><?= $total_rdv ?></div>
    </div>
</div>

<!-- ================== KPIs VISITES / CLICS ================== -->
<h2 class="subtitle" style="margin-top:25px;">Trafic & interactions digitales</h2>

<div class="visits-grid">
    <div class="visit-card">
        <div class="kpi-label">Visites totales</div>
        <div class="kpi-value"><?= $total_visits ?></div>
        <div class="kpi-sub"><?= $today_visits ?> aujourd’hui</div>
    </div>

    <div class="visit-card">
        <div class="kpi-label">Visiteurs uniques</div>
        <div class="kpi-value"><?= $unique_visitors ?></div>
        <div class="kpi-sub">basé sur IP</div>
    </div>

    <div class="visit-card">
        <div class="kpi-label">Total clics (WhatsApp, tél., email, boutons)</div>
        <div class="kpi-value"><?= $total_events ?></div>
        <div class="kpi-sub">
            WA: <?= $click_whatsapp ?> · Tél: <?= $click_telephone ?> · Mail: <?= $click_email ?>
        </div>
    </div>
</div>

<!-- ALERTES -->
<h2 class="subtitle" style="margin-top:25px;">Alertes importantes</h2>
<div class="alerts-grid">
    <div class="alert-card alert-critical">
        <strong>Biens sans image principale</strong><br>
        <?= $alert_sans_image ?> bien(s)
    </div>

    <div class="alert-card alert-warning">
        <strong>Biens en ligne depuis +60 jours</strong><br>
        <?= $alert_trop_anciens ?> bien(s)
    </div>

    <div class="alert-card alert-info">
        <strong>Biens non visibles</strong><br>
        <?= $biens_non_visibles ?> bien(s)
    </div>
</div>

<!-- ========= GRAPHIQUE LEADS ========= -->
<div class="chart-box">
    <div class="chart-title">Évolution des leads (6 derniers mois)</div>
    <canvas id="leadsChart"></canvas>
</div>

<!-- ========= GRAPHIQUE TYPES ========= -->
<div class="chart-box">
    <div class="chart-title">Répartition des types de biens</div>
    <canvas id="typesChart"></canvas>
</div>

<!-- ========= GRAPHIQUE TEMPS – 7 DERNIERS JOURS ========= -->
<div class="chart-box">
    <div class="chart-title">Temps total passé sur le site (7 derniers jours)</div>
    <canvas id="time7Chart"></canvas>
</div>

<!-- ========= GRAPHIQUE TEMPS PAR PAGE ========= -->
<div class="chart-box">
    <div class="chart-title">Pages les plus “consommatrices” en temps</div>
    <canvas id="timePagesChart"></canvas>
</div>

<!-- ========= LISTES ========= -->
<div class="lists-grid">

    <div class="list-box">
        <h3 class="chart-title">Derniers leads IMMO</h3>
        <table>
            <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Demande</th>
                <th>Date</th>
            </tr>
            <?php foreach($last_leads as $l): ?>
            <tr>
                <td><?= htmlspecialchars($l['nom']) ?></td>
                <td><?= htmlspecialchars($l['telephone']) ?></td>
                <td><?= htmlspecialchars($l['type_demande']) ?></td>
                <td><?= $l['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="list-box">
        <h3 class="chart-title">Derniers messages</h3>
        <table>
            <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Objet</th>
                <th>Date</th>
            </tr>
            <?php foreach($last_messages as $m): ?>
            <tr>
                <td><?= htmlspecialchars($m['name']) ?></td>
                <td><?= htmlspecialchars($m['phone']) ?></td>
                <td><?= htmlspecialchars($m['subject']) ?></td>
                <td><?= $m['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="list-box">
        <h3 class="chart-title">Derniers rendez-vous</h3>
        <table>
            <tr>
                <th>Nom</th>
                <th>Téléphone</th>
                <th>Type</th>
                <th>Date</th>
                <th>Heure</th>
            </tr>
            <?php foreach($last_rdv as $r): ?>
            <tr>
                <td><?= htmlspecialchars($r['name']) ?></td>
                <td><?= htmlspecialchars($r['phone']) ?></td>
                <td><?= htmlspecialchars($r['type']) ?></td>
                <td><?= $r['date_rdv'] ?></td>
                <td><?= $r['time_rdv'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>

</div>

<script>
// LEADS CHART
new Chart(document.getElementById('leadsChart'), {
    type: 'line',
    data: {
        labels: [<?php foreach($stats_leads as $s){ echo "'".$s['mois']."',"; } ?>],
        datasets: [{
            label: 'Leads',
            data: [<?php foreach($stats_leads as $s){ echo $s['total'].","; } ?>],
            borderColor:'#003c96',
            backgroundColor:'rgba(0,60,150,0.15)',
            tension:0.3,
            fill:true
        }]
    }
});

// TYPES DE BIENS CHART
new Chart(document.getElementById('typesChart'), {
    type: 'bar',
    data: {
        labels: [<?php foreach($stats_types as $t){ echo "'".$t['type']."',"; } ?>],
        datasets: [{
            label:'Biens',
            data: [<?php foreach($stats_types as $t){ echo $t['total'].","; } ?>],
            backgroundColor:'#e30613'
        }]
    },
    options: { indexAxis:'y' }
});

// TEMPS TOTAL 7 DERNIERS JOURS
new Chart(document.getElementById('time7Chart'), {
    type: 'line',
    data: {
        labels: [<?php foreach($time_last7 as $t){ echo "'".$t['d']."',"; } ?>],
        datasets: [{
            label:'Temps total (sec)',
            data: [<?php foreach($time_last7 as $t){ echo $t['total_sec'].","; } ?>],
            borderColor:'#0f766e',
            backgroundColor:'rgba(15,118,110,0.15)',
            tension:0.3,
            fill:true
        }]
    }
});

// TEMPS PAR PAGE (TOP 7)
new Chart(document.getElementById('timePagesChart'), {
    type: 'bar',
    data: {
        labels: [<?php foreach($time_top_pages as $p){ echo "'".$p['page']."',"; } ?>],
        datasets: [{
            label:'Temps total (sec)',
            data: [<?php foreach($time_top_pages as $p){ echo $p['total_sec'].","; } ?>],
            backgroundColor:'#f97316'
        }]
    },
    options:{
        indexAxis:'y',
        plugins:{ legend:{ display:false } }
    }
});
</script>

</body>
</html>