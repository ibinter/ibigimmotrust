<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

// Sécurité
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* =========================================================
   1) KPIs GLOBAUX
   Tables utilisées :
   - visits      : ip, user_agent, page, referer, device, browser, os, country, city, created_at
   - visit_time  : page, time_spent, ip, created_at
   - events      : id, ip, event_name, event_value, page, created_at
========================================================= */

// Visites
$total_visits       = (int)$pdo->query("SELECT COUNT(*) FROM visits")->fetchColumn();
$unique_visitors    = (int)$pdo->query("SELECT COUNT(DISTINCT ip) FROM visits")->fetchColumn();
$distinct_pages     = (int)$pdo->query("SELECT COUNT(DISTINCT page) FROM visits")->fetchColumn();
$today_visits       = (int)$pdo->query("SELECT COUNT(*) FROM visits WHERE DATE(created_at)=CURDATE()")->fetchColumn();

// PAYS & VILLES
$total_countries    = (int)$pdo->query("SELECT COUNT(DISTINCT country) FROM visits")->fetchColumn();
$total_cities       = (int)$pdo->query("SELECT COUNT(DISTINCT city) FROM visits")->fetchColumn();

// Événements
$total_events       = (int)$pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();

// Temps de visite
$avg_time_per_page  = (float)$pdo->query("SELECT AVG(time_spent) FROM visit_time")->fetchColumn();
$total_time_spent   = (int)$pdo->query("SELECT SUM(time_spent) FROM visit_time")->fetchColumn();

/* =========================================================
   2) COURBE VISITES PAR JOUR (14 DERNIERS JOURS)
========================================================= */
$visits_days = $pdo->query("
    SELECT DATE(created_at) AS d, COUNT(*) AS total
    FROM visits
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
    GROUP BY d
    ORDER BY d ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   3) STATS DEVICE / BROWSER / OS
========================================================= */
$stats_device = $pdo->query("
    SELECT device, COUNT(*) AS total
    FROM visits
    GROUP BY device
")->fetchAll(PDO::FETCH_ASSOC);

$stats_browser = $pdo->query("
    SELECT browser, COUNT(*) AS total
    FROM visits
    GROUP BY browser
    ORDER BY total DESC
    LIMIT 7
")->fetchAll(PDO::FETCH_ASSOC);

$stats_os = $pdo->query("
    SELECT os, COUNT(*) AS total
    FROM visits
    GROUP BY os
    ORDER BY total DESC
    LIMIT 7
")->fetchAll(PDO::FETCH_ASSOC);

$stats_country = $pdo->query("
    SELECT country, COUNT(*) AS total
    FROM visits
    WHERE country <> ''
    GROUP BY country
    ORDER BY total DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

$stats_city = $pdo->query("
    SELECT city, COUNT(*) AS total
    FROM visits
    WHERE city <> ''
    GROUP BY city
    ORDER BY total DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   3B) COORDONNÉES GEO (pour Heatmap)
========================================================= */
$geo_points = $pdo->query("
    SELECT latitude, longitude 
    FROM visits 
    WHERE latitude IS NOT NULL 
      AND longitude IS NOT NULL
      AND latitude <> '' 
      AND longitude <> ''
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   4) TOP PAGES (par visites)
========================================================= */
$top_pages = $pdo->query("
    SELECT page, COUNT(*) AS vues, COUNT(DISTINCT ip) AS uniques
    FROM visits
    GROUP BY page
    ORDER BY vues DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   5) TOP PAGES PAR TEMPS (visit_time)
========================================================= */
$time_pages = $pdo->query("
    SELECT page,
           COUNT(*) AS vues,
           SUM(time_spent) AS total_sec,
           AVG(time_spent) AS avg_sec
    FROM visit_time
    GROUP BY page
    ORDER BY total_sec DESC
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   6) STATS ÉVÉNEMENTS
    Ta table a event_name / event_value, on les renomme en alias
========================================================= */
$stats_events_type = $pdo->query("
    SELECT event_name AS event, COUNT(*) AS total
    FROM events
    GROUP BY event_name
    ORDER BY total DESC
")->fetchAll(PDO::FETCH_ASSOC);

$stats_events_days = $pdo->query("
    SELECT DATE(created_at) AS d, COUNT(*) AS total
    FROM events
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
    GROUP BY d
    ORDER BY d ASC
")->fetchAll(PDO::FETCH_ASSOC);

/* =========================================================
   7) LISTES DÉTAILLÉES (pour les tableaux)
========================================================= */

// Dernières visites
$last_visits = $pdo->query("
    SELECT *
    FROM visits
    ORDER BY created_at DESC
    LIMIT 50
")->fetchAll(PDO::FETCH_ASSOC);

// Derniers temps de visite
$last_times = $pdo->query("
    SELECT *
    FROM visit_time
    ORDER BY created_at DESC
    LIMIT 50
")->fetchAll(PDO::FETCH_ASSOC);

// Derniers événements – alias pour coller avec le reste du code
$last_events = $pdo->query("
    SELECT event_name AS event, event_value AS value, ip, page, created_at
    FROM events
    ORDER BY created_at DESC
    LIMIT 50
")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
    
<!-- Leaflet MAP -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<!-- Leaflet Heatmap -->
<script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

<head>
<meta charset="UTF-8">
<title>Analytics – IBIG IMMO TRUST</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    font-family:system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
    background:#f4f6fb;
    margin:0;
}
.analytics-wrapper{
    padding:25px;
}

/* TITRES */
h1{
    font-size:24px;
    font-weight:800;
    color:#003c96;
    margin-bottom:5px;
}
.subtitle{
    color:#6b7280;
    font-size:13px;
    margin-bottom:25px;
}

/* KPIs */
.kpi-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:15px;
    margin-bottom:25px;
}
.kpi-card{
    background:white;
    padding:15px;
    border-radius:12px;
    box-shadow:0 2px 6px rgba(15,23,42,0.08);
}
.kpi-label{
    font-size:12px;
    color:#6b7280;
    text-transform:uppercase;
    letter-spacing:0.06em;
}
.kpi-value{
    font-size:24px;
    font-weight:800;
    color:#003c96;
    margin-top:5px;
}
.kpi-sub{
    font-size:11px;
    color:#9ca3af;
}

/* ONGLET */
.tabs{
    margin-top:15px;
    margin-bottom:10px;
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}
.tab-btn{
    border:none;
    background:#e5e7eb;
    color:#374151;
    padding:8px 14px;
    border-radius:999px;
    font-size:13px;
    cursor:pointer;
    font-weight:500;
    display:flex;
    align-items:center;
    gap:6px;
    transition:0.2s;
}
.tab-btn.active{
    background:#003c96;
    color:white;
    box-shadow:0 3px 8px rgba(0,60,150,0.35);
}
.tab-panel{
    display:none;
}
.tab-panel.active{
    display:block;
}

/* BLOCKS / BOX */
.box{
    background:white;
    padding:18px;
    border-radius:12px;
    box-shadow:0 2px 6px rgba(15,23,42,0.06);
    margin-bottom:20px;
}
.box-title{
    font-size:15px;
    font-weight:700;
    color:#003c96;
    margin-bottom:8px;
}

/* GRIDS INTERIEURS */
.grid-2{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
    gap:18px;
}
.grid-3{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:18px;
}

/* TABLES */
table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
    margin-top:8px;
}
th{
    background:#eef2ff;
    padding:8px;
    text-align:left;
    color:#4b5563;
}
td{
    padding:8px;
    border-bottom:1px solid #e5e7eb;
}
tr:hover td{
    background:#f9fafb;
}
.badge{
    display:inline-block;
    padding:2px 7px;
    border-radius:999px;
    font-size:11px;
    background:#e5e7eb;
    color:#374151;
}

/* RESPONSIVE */
@media(max-width:768px){
    .analytics-wrapper{ padding:15px; }
}
</style>
</head>
<body>

<?php include __DIR__ . '/menu.php'; ?>

<div class="analytics-wrapper">

    <h1> Analytics – IBIG IMMO TRUST</h1>
    <p class="subtitle">Vue consolidée : visites, temps passé, appareils & événements.</p>

    <!-- KPIs GLOBAUX -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">Visites totales</div>
            <div class="kpi-value"><?= $total_visits ?></div>
            <div class="kpi-sub"><?= $today_visits ?> aujourd’hui</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Visiteurs uniques</div>
            <div class="kpi-value"><?= $unique_visitors ?></div>
            <div class="kpi-sub">Basé sur l’IP</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Pages différentes</div>
            <div class="kpi-value"><?= $distinct_pages ?></div>
            <div class="kpi-sub">Routes / pages distinctes</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Événements trackés</div>
            <div class="kpi-value"><?= $total_events ?></div>
            <div class="kpi-sub">WhatsApp, téléphone, email, CTA…</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">Temps moyen enregistré</div>
            <div class="kpi-value"><?= number_format($avg_time_per_page,1) ?>s</div>
            <div class="kpi-sub">Temps total ≈ <?= number_format($total_time_spent/60,1) ?> min</div>
        </div>
            <!--  NOUVEAU KPI : Pays visiteurs -->
    <div class="kpi-card">
        <div class="kpi-label">Pays visiteurs</div>
        <div class="kpi-value"><?= $total_countries ?></div>
        <div class="kpi-sub">Localisation détectée</div>
    </div>

    <!--  NOUVEAU KPI : Villes visiteurs -->
    <div class="kpi-card">
        <div class="kpi-label">Villes visiteurs</div>
        <div class="kpi-value"><?= $total_cities ?></div>
        <div class="kpi-sub">Localisation détaillée</div>
    </div>

    </div>

    <!-- ONGLET NAVIGATION -->
    <div class="tabs">
        <button class="tab-btn active" data-tab="overview"> Vue d’ensemble</button>
        <button class="tab-btn" data-tab="pages"> Pages</button>
        <button class="tab-btn" data-tab="time"> Temps passé</button>
        <button class="tab-btn" data-tab="events">¯ Événements</button>
    </div>

    <!-- PANELS D’ONGLETS (le contenu arrive dans les blocs 2 et 3) -->
<!-- ============================
     ONGLET 1 – VUE D’ENSEMBLE 
=============================== -->
<div class="grid-3" style="margin-top:20px;">
    <div class="box">
        <div class="box-title">Top pays</div>
        <table>
            <tr><th>Pays</th><th>Visites</th></tr>
            <?php foreach ($stats_country as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['country']) ?></td>
                <td><?= $c['total'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="box">
        <div class="box-title">Top villes</div>
        <table>
            <tr><th>Ville</th><th>Visites</th></tr>
            <?php foreach ($stats_city as $c): ?>
            <tr>
                <td><?= htmlspecialchars($c['city']) ?></td>
                <td><?= $c['total'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <div class="box" style="height:380px;">
    <div class="box-title">Carte Heatmap des visiteurs</div>
    <div id="heatmap_overview" style="height:320px; border-radius:10px;"></div>
</div>
</div>

<div class="tab-panel active" id="overview">

    <div class="grid-2">

        <!-- VISITES 14 JOURS -->
        <div class="box">
            <div class="box-title">Évolution des visites (14 derniers jours)</div>
            <canvas id="chart_visits_14"></canvas>
        </div>

        <!-- ÉVÉNEMENTS 14 JOURS -->
        <div class="box">
            <div class="box-title">Évolution des événements (14 derniers jours)</div>
            <canvas id="chart_events_14"></canvas>
        </div>

    </div>

    <div class="grid-3" style="margin-top:20px;">

        <div class="box">
            <div class="box-title">Appareils utilisés</div>
            <canvas id="chart_device"></canvas>
        </div>

        <div class="box">
            <div class="box-title">Navigateurs</div>
            <canvas id="chart_browser"></canvas>
        </div>

        <div class="box">
            <div class="box-title">Systèmes d’exploitation</div>
            <canvas id="chart_os"></canvas>
        </div>

    </div>
</div>


<!-- ============================
     ONGLET 2 – PAGES
=============================== -->
<div class="tab-panel" id="pages">

    <!-- TOP PAGES PAR VISITES -->
    <div class="box">
        <div class="box-title">Pages les plus visitées</div>

        <table>
            <tr>
                <th>Page</th>
                <th>Vues</th>
                <th>Visiteurs uniques</th>
            </tr>

            <?php foreach($top_pages as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['page']) ?></td>
                <td><?= $p['vues'] ?></td>
                <td><?= $p['uniques'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- DERNIÈRES VISITES -->
    <div class="box">
        <div class="box-title">Dernières visites</div>

        <table>
            <tr>
                <th>IP</th>
                <th>Page</th>
                <th>Device</th>
                <th>Browser</th>
                <th>OS</th>
                <th>Date</th>
            </tr>

            <?php foreach($last_visits as $v): ?>
            <tr>
                <td><?= $v['ip'] ?></td>
                <td><?= htmlspecialchars($v['page']) ?></td>
                <td><?= $v['device'] ?></td>
                <td><?= $v['browser'] ?></td>
                <td><?= $v['os'] ?></td>
                <td><?= $v['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>


<!-- ============================
     ONGLET 3 – TEMPS PASSÉ
=============================== -->
<div class="tab-panel" id="time">

    <!-- PAGES LES PLUS LONGUES -->
    <div class="box">
        <div class="box-title">Pages où les visiteurs restent le plus longtemps</div>

        <table>
            <tr>
                <th>Page</th>
                <th>Vues</th>
                <th>Total (sec)</th>
                <th>Moyenne (sec)</th>
            </tr>

            <?php foreach($time_pages as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['page']) ?></td>
                <td><?= $t['vues'] ?></td>
                <td><?= $t['total_sec'] ?></td>
                <td><?= number_format($t['avg_sec'],1) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- DERNIERS TEMPS -->
    <div class="box">
        <div class="box-title">Derniers enregistrements de temps</div>

        <table>
            <tr>
                <th>Page</th>
                <th>Durée (sec)</th>
                <th>IP</th>
                <th>Date</th>
            </tr>

            <?php foreach($last_times as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['page']) ?></td>
                <td><?= $t['time_spent'] ?></td>
                <td><?= $t['ip'] ?></td>
                <td><?= $t['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>


<!-- ============================
     ONGLET 4 – ÉVÉNEMENTS
=============================== -->
<div class="tab-panel" id="events">

    <!-- TYPES D’ÉVÉNEMENTS -->
    <div class="box">
        <div class="box-title">Types d’événements enregistrés</div>

        <table>
            <tr>
                <th>Événement</th>
                <th>Nombre</th>
            </tr>

            <?php foreach($stats_events_type as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['event']) ?></td>
                <td><?= $e['total'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- DERNIERS ÉVÉNEMENTS -->
    <div class="box">
        <div class="box-title">Derniers événements</div>

        <table>
            <tr>
                <th>Événement</th>
                <th>Valeur</th>
                <th>Page</th>
                <th>IP</th>
                <th>Date</th>
            </tr>

            <?php foreach($last_events as $ev): ?>
            <tr>
                <td><?= htmlspecialchars($ev['event']) ?></td>
                <td><?= htmlspecialchars($ev['value']) ?></td>
                <td><?= htmlspecialchars($ev['page']) ?></td>
                <td><?= $ev['ip'] ?></td>
                <td><?= $ev['created_at'] ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</div>
</div> <!-- .analytics-wrapper -->

<script>
// ==========================
// GESTION DES ONGLETS
// ==========================
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // Boutons
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        // Panels
        const target = btn.getAttribute('data-tab'); // overview, pages, time, events
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.getElementById(target).classList.add('active');
    });
});

// ==========================
// DONNÉES PHP -> JS
// ==========================

// Visites 14 derniers jours
const visitsDaysLabels = [<?php foreach($visits_days as $v){ echo "'".$v['d']."',"; } ?>];
const visitsDaysData   = [<?php foreach($visits_days as $v){ echo $v['total'].","; } ?>];

// Events 14 derniers jours
const eventsDaysLabels = [<?php foreach($stats_events_days as $e){ echo "'".$e['d']."',"; } ?>];
const eventsDaysData   = [<?php foreach($stats_events_days as $e){ echo $e['total'].","; } ?>];

// Devices
const deviceLabels = [<?php foreach($stats_device as $d){ echo "'".$d['device']."',"; } ?>];
const deviceData   = [<?php foreach($stats_device as $d){ echo $d['total'].","; } ?>];

// Navigateurs
const browserLabels = [<?php foreach($stats_browser as $b){ echo "'".$b['browser']."',"; } ?>];
const browserData   = [<?php foreach($stats_browser as $b){ echo $b['total'].","; } ?>];

// OS
const osLabels = [<?php foreach($stats_os as $o){ echo "'".$o['os']."',"; } ?>];
const osData   = [<?php foreach($stats_os as $o){ echo $o['total'].","; } ?>];

// ==========================
// CHARTS
// ==========================

const ctxVisits = document.getElementById('chart_visits_14');
if(ctxVisits){
    new Chart(ctxVisits, {
        type:'line',
        data:{
            labels:visitsDaysLabels,
            datasets:[{
                label:'Visites',
                data:visitsDaysData,
                borderColor:'#003c96',
                backgroundColor:'rgba(0,60,150,0.15)',
                fill:true,
                tension:0.3
            }]
        },
        options:{
            plugins:{legend:{display:false}},
            scales:{
                y:{beginAtZero:true}
            }
        }
    });
}

const ctxEvents = document.getElementById('chart_events_14');
if(ctxEvents){
    new Chart(ctxEvents, {
        type:'line',
        data:{
            labels:eventsDaysLabels,
            datasets:[{
                label:'Événements',
                data:eventsDaysData,
                borderColor:'#e30613',
                backgroundColor:'rgba(227,6,19,0.12)',
                fill:true,
                tension:0.3
            }]
        },
        options:{
            plugins:{legend:{display:false}},
            scales:{ y:{beginAtZero:true} }
        }
    });
}

const ctxDevice = document.getElementById('chart_device');
if(ctxDevice){
    new Chart(ctxDevice, {
        type:'doughnut',
        data:{
            labels:deviceLabels,
            datasets:[{
                data:deviceData,
                backgroundColor:['#003c96','#ff9f1c','#e30613','#0ea5e9']
            }]
        },
        options:{
            plugins:{legend:{position:'bottom'}}
        }
    });
}

const ctxBrowser = document.getElementById('chart_browser');
if(ctxBrowser){
    new Chart(ctxBrowser, {
        type:'bar',
        data:{
            labels:browserLabels,
            datasets:[{
                data:browserData,
                backgroundColor:'#003c96'
            }]
        },
        options:{
            indexAxis:'y',
            plugins:{legend:{display:false}},
            scales:{ x:{beginAtZero:true} }
        }
    });
}

const ctxOS = document.getElementById('chart_os');
if(ctxOS){
    new Chart(ctxOS, {
        type:'bar',
        data:{
            labels:osLabels,
            datasets:[{
                data:osData,
                backgroundColor:'#0ea5e9'
            }]
        },
        options:{
            indexAxis:'y',
            plugins:{legend:{display:false}},
            scales:{ x:{beginAtZero:true} }
        }
    });
}

// ==========================
// HEATMAP GEO (carte Overview)
// ==========================

const geoPointsOverview = [
    <?php 
        foreach($geo_points as $g){
            if (is_numeric($g['latitude']) && is_numeric($g['longitude'])) {
                echo "[".$g['latitude'].", ".$g['longitude']."],";
            }
        }
    ?>
];

if (document.getElementById('heatmap_overview')) {

    var mapOverview = L.map('heatmap_overview').setView([5.35, -4.0], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap'
    }).addTo(mapOverview);

    L.heatLayer(geoPointsOverview, {
        radius: 30,
        blur: 20,
        maxZoom: 17,
    }).addTo(mapOverview);

    if (geoPointsOverview.length > 1) {
        const bounds = L.latLngBounds(geoPointsOverview);
        mapOverview.fitBounds(bounds.pad(0.2));
    }
}

</script>

</body>
</html>
