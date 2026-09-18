<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

// =======================
// STATS GLOBALES
// =======================
$total_rdv     = (int)$pdo->query("SELECT COUNT(*) FROM rdv_requests")->fetchColumn();
$total_leads   = (int)$pdo->query("SELECT COUNT(*) FROM immo_leads")->fetchColumn();
$total_msgs    = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$total_agents  = (int)$pdo->query("SELECT COUNT(*) FROM agents")->fetchColumn();

// RDV par statut
$pending   = (int)$pdo->query("SELECT COUNT(*) FROM rdv_requests WHERE status='En attente'")->fetchColumn();
$confirmed = (int)$pdo->query("SELECT COUNT(*) FROM rdv_requests WHERE status='Confirmé'")->fetchColumn();
$done      = (int)$pdo->query("SELECT COUNT(*) FROM rdv_requests WHERE status='Traité'")->fetchColumn();

// =======================
// RDV PAR MOIS (12 DERNIERS MOIS)
// =======================
$rdvLabels = [];
$rdvData   = [];

$sql = "
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS mois, COUNT(*) AS total
    FROM rdv_requests
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
    GROUP BY mois
    ORDER BY mois
";
$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as $r) {
    $rdvLabels[] = $r['mois'];   // ex: 2025-01
    $rdvData[]   = (int)$r['total'];
}

// Pour éviter les tableaux vides
if (empty($rdvLabels)) {
    $rdvLabels = [date('Y-m')];
    $rdvData   = [0];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard IMMO TRUST</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    body{
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        background:#f3f4f6;
        margin:0;
        padding:0;
    }

    .page-wrapper{
        padding:20px;
        max-width:1200px;
        margin:0 auto;
    }

    .page-header{
        display:flex;
        flex-wrap:wrap;
        align-items:flex-start;
        justify-content:space-between;
        gap:12px;
        margin-bottom:18px;
    }

    .page-title{
        font-size:24px;
        font-weight:700;
        color:#111827;
    }
    .page-subtitle{
        font-size:13px;
        color:#6b7280;
        margin-top:4px;
    }

    .kpi-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(210px,1fr));
        gap:14px;
        margin-bottom:18px;
    }
    .card{
        background:#ffffff;
        border-radius:12px;
        padding:14px 16px;
        box-shadow:0 4px 12px rgba(15,23,42,0.06);
    }
    .stat-label{
        font-size:13px;
        color:#6b7280;
        margin-bottom:4px;
    }
    .stat-number{
        font-size:26px;
        font-weight:700;
        color:#111827;
    }
    .stat-sub{
        margin-top:4px;
        font-size:12px;
        color:#9ca3af;
    }

    .charts-grid{
        display:grid;
        grid-template-columns: minmax(0,2fr) minmax(0,1.3fr);
        gap:16px;
        margin-bottom:18px;
    }
    @media(max-width:900px){
        .charts-grid{
            grid-template-columns:1fr;
        }
    }

    .card h3{
        margin:0 0 10px 0;
        font-size:16px;
        color:#111827;
    }

    .lists-grid{
        display:grid;
        grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
        gap:16px;
        margin-bottom:20px;
    }
    .list-item{
        display:flex;
        justify-content:space-between;
        font-size:13px;
        padding:4px 0;
        border-bottom:1px dashed #e5e7eb;
    }
    .list-item span:first-child{
        color:#4b5563;
    }
    .list-item span:last-child{
        color:#111827;
        font-weight:600;
    }

    .section-title{
        font-size:14px;
        font-weight:600;
        color:#111827;
        margin-bottom:8px;
    }

    @media(max-width:600px){
        .page-wrapper{ padding:12px; }
        .page-title{ font-size:20px; }
    }
</style>
</head>

<body>

<?php require_once __DIR__ . '/menu.php'; ?>

<div class="page-wrapper">

    <div class="page-header">
        <div>
            <div class="page-title">Dashboard IMMO TRUST</div>
            <div class="page-subtitle">
                Vue synthétique : rendez-vous, demandes, messages & agents commerciaux.
            </div>
        </div>
    </div>

    <!-- KPI CARDS -->
    <div class="kpi-grid">
        <div class="card">
            <div class="stat-label">Rendez-vous (total)</div>
            <div class="stat-number"><?= $total_rdv ?></div>
            <div class="stat-sub">Tous les RDV enregistrés.</div>
        </div>

        <div class="card">
            <div class="stat-label">Demandes (formulaire immo)</div>
            <div class="stat-number"><?= $total_leads ?></div>
            <div class="stat-sub">Leads issus du formulaire unique.</div>
        </div>

        <div class="card">
            <div class="stat-label">Messages reçus</div>
            <div class="stat-number"><?= $total_msgs ?></div>
            <div class="stat-sub">Formulaire de contact / site.</div>
        </div>

        <div class="card">
            <div class="stat-label">Agents commerciaux</div>
            <div class="stat-number"><?= $total_agents ?></div>
            <div class="stat-sub">Réseau actif IBIG IMMO TRUST.</div>
        </div>

        <div class="card">
            <div class="stat-label">RDV en attente</div>
            <div class="stat-number"><?= $pending ?></div>
            <div class="stat-sub">À traiter au plus vite.</div>
        </div>

        <div class="card">
            <div class="stat-label">RDV confirmés</div>
            <div class="stat-number"><?= $confirmed ?></div>
            <div class="stat-sub">En phase de rendez-vous.</div>
        </div>

        <div class="card">
            <div class="stat-label">RDV traités</div>
            <div class="stat-number"><?= $done ?></div>
            <div class="stat-sub">Clôturés / finalisés.</div>
        </div>
    </div>

    <!-- CHARTS -->
    <div class="charts-grid">
        <div class="card">
            <h3>Évolution mensuelle des RDV (12 derniers mois)</h3>
            <canvas id="rdvChart" height="140"></canvas>
        </div>
        <div class="card">
            <h3>Répartition des RDV par statut</h3>
            <canvas id="statusChart" height="140"></canvas>
        </div>
    </div>

</div>

<script>
const rdvLabels = <?= json_encode($rdvLabels) ?>;
const rdvData   = <?= json_encode($rdvData) ?>;
const pending   = <?= (int)$pending ?>;
const confirmed = <?= (int)$confirmed ?>;
const done      = <?= (int)$done ?>;

// Ligne RDV par mois
const ctxRdv = document.getElementById('rdvChart').getContext('2d');
new Chart(ctxRdv, {
    type: 'line',
    data: {
        labels: rdvLabels,
        datasets: [{
            label: 'RDV',
            data: rdvData,
            borderWidth: 2,
            borderColor: '#111827',
            backgroundColor: 'rgba(31,41,55,0.12)',
            fill: true,
            tension: 0.3,
            pointRadius: 3
        }]
    },
    options: {
        responsive:true,
        scales: {
            y: {
                beginAtZero:true,
                ticks:{ precision:0 }
            }
        }
    }
});

// Doughnut Statuts
const ctxStatus = document.getElementById('statusChart').getContext('2d');
new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        labels: ['En attente','Confirmé','Traité'],
        datasets: [{
            data: [pending, confirmed, done],
            backgroundColor: ['#f59e0b','#10b981','#6b7280']
        }]
    },
    options:{
        plugins:{
            legend:{ position:'bottom', labels:{ boxWidth:14 } }
        }
    }
});
</script>

</body>
</html>