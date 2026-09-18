<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin_role'] !== 'superadmin') {
    die("Accès refusé");
}

include __DIR__ . '/menu.php';

//
// ► RÉCUPÉRATION DES STATISTIQUES
//

$total_visits = $pdo->query("SELECT COUNT(*) FROM visits")->fetchColumn();
$unique_visitors = $pdo->query("SELECT COUNT(DISTINCT ip) FROM visits")->fetchColumn();
$today_visits = $pdo->query("SELECT COUNT(*) FROM visits WHERE DATE(created_at)=CURDATE()")->fetchColumn();

$total_events = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();

// CLICS par type
$click_whatsapp = $pdo->query("SELECT COUNT(*) FROM events WHERE event_name='click_whatsapp'")->fetchColumn();
$click_rdv      = $pdo->query("SELECT COUNT(*) FROM events WHERE event_name='click_rdv_home'")->fetchColumn();
$click_contact  = $pdo->query("SELECT COUNT(*) FROM events WHERE event_name='click_contact_home'")->fetchColumn();

// Pages les plus vues
$top_pages = $pdo->query("
    SELECT page, COUNT(*) as total 
    FROM visits 
    GROUP BY page 
    ORDER BY total DESC 
    LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

// Appareils
$devices = $pdo->query("
    SELECT device, COUNT(*) as total 
    FROM visits 
    GROUP BY device
")->fetchAll(PDO::FETCH_ASSOC);

// Navigateurs
$browsers = $pdo->query("
    SELECT browser, COUNT(*) as total 
    FROM visits 
    GROUP BY browser
")->fetchAll(PDO::FETCH_ASSOC);

// OS
$os_list = $pdo->query("
    SELECT os, COUNT(*) as total 
    FROM visits 
    GROUP BY os
")->fetchAll(PDO::FETCH_ASSOC);

// Derniers événements
$recent_events = $pdo->query("
    SELECT * FROM events ORDER BY id DESC LIMIT 20
")->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
/* ======================= STYLE PREMIUM DASHBOARD ======================= */

.page-wrapper {
    padding: 30px;
}

h1 {
    color: #003c96;
    font-size: 26px;
    margin-bottom: 25px;
    font-weight: 700;
}

/* Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(230px,1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.stat-card {
    background: #fff;
    padding: 20px;
    border-radius: 14px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
    border-left: 5px solid #003c96;
}

.stat-card h3 {
    font-size: 17px;
    margin: 0 0 10px;
    color: #6b7280;
}

.stat-card .stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #003c96;
}

/* Tables */
.table-box {
    margin-top: 35px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
    background: white;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

th {
    background: #003c96;
    color: white;
    padding: 12px;
    text-align: left;
    font-size: 14px;
}

td {
    padding: 10px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 14px;
}

.section-title {
    font-size: 20px;
    font-weight: 700;
    color: #001f54;
    margin-top: 40px;
}
</style>

<div class="page-wrapper">

<h1>📊 Statistiques des visites & interactions</h1>

<!-- 📌 STATISTIQUES GLOBALES -->
<div class="stats-grid">

    <div class="stat-card">
        <h3>Total visites</h3>
        <div class="stat-value"><?= $total_visits ?></div>
    </div>

    <div class="stat-card">
        <h3>Visiteurs uniques</h3>
        <div class="stat-value"><?= $unique_visitors ?></div>
    </div>

    <div class="stat-card">
        <h3>Visites aujourd’hui</h3>
        <div class="stat-value"><?= $today_visits ?></div>
    </div>

    <div class="stat-card">
        <h3>Total clics enregistrés</h3>
        <div class="stat-value"><?= $total_events ?></div>
    </div>

</div>

<!-- 📌 CLICS SPÉCIFIQUES -->
<h2 class="section-title">Interactions principales</h2>

<div class="stats-grid">

    <div class="stat-card">
        <h3>Clics WhatsApp</h3>
        <div class="stat-value"><?= $click_whatsapp ?></div>
    </div>

    <div class="stat-card">
        <h3>Clics Prendre RDV</h3>
        <div class="stat-value"><?= $click_rdv ?></div>
    </div>

    <div class="stat-card">
        <h3>Clics Contact</h3>
        <div class="stat-value"><?= $click_contact ?></div>
    </div>

</div>

<!-- 🗂️ TOP PAGES -->
<h2 class="section-title">Pages les plus consultées</h2>

<table>
<tr>
    <th>Page</th>
    <th>Visites</th>
</tr>
<?php foreach ($top_pages as $p): ?>
<tr>
    <td><?= htmlspecialchars($p['page']) ?></td>
    <td><?= $p['total'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<!-- 📱 APPAREILS -->
<h2 class="section-title">Appareils utilisés</h2>

<table>
<tr>
    <th>Appareil</th>
    <th>Visites</th>
</tr>
<?php foreach ($devices as $d): ?>
<tr>
    <td><?= $d['device'] ?></td>
    <td><?= $d['total'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<!-- 🌐 NAVIGATEURS -->
<h2 class="section-title">Navigateurs</h2>

<table>
<tr>
    <th>Navigateur</th>
    <th>Visites</th>
</tr>
<?php foreach ($browsers as $b): ?>
<tr>
    <td><?= $b['browser'] ?></td>
    <td><?= $b['total'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<!-- 🖥️ SYSTÈMES D’EXPLOITATION -->
<h2 class="section-title">Systèmes d’exploitation</h2>

<table>
<tr>
    <th>OS</th>
    <th>Visites</th>
</tr>
<?php foreach ($os_list as $o): ?>
<tr>
    <td><?= $o['os'] ?></td>
    <td><?= $o['total'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<!-- 📨 DERNIERS ÉVÉNEMENTS -->
<h2 class="section-title">Derniers clics & interactions</h2>

<table>
<tr>
    <th>Événement</th>
    <th>Valeur</th>
    <th>IP</th>
    <th>Page</th>
    <th>Date</th>
</tr>

<?php foreach ($recent_events as $e): ?>
<tr>
    <td><?= $e['event_name'] ?></td>
    <td><?= $e['event_value'] ?></td>
    <td><?= $e['ip'] ?></td>
    <td><?= $e['page'] ?></td>
    <td><?= $e['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>

</div>