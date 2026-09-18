<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

// Stats
$total_rdv   = $pdo->query("SELECT COUNT(*) FROM rdv_requests")->fetchColumn();
$total_leads = $pdo->query("SELECT COUNT(*) FROM immo_leads")->fetchColumn();
$total_msgs  = $pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
$total_agents= $pdo->query("SELECT COUNT(*) FROM agents")->fetchColumn();

// Derniers leads
$leads = $pdo->query("
    SELECT nom, telephone, type_demande, created_at
    FROM immo_leads ORDER BY id DESC LIMIT 20
")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Rapport IMMO TRUST</title>

<style>
body{ font-family:Arial; padding:20px; }
h1{ text-align:center; }
.section{ margin-top:30px; }
table{ width:100%; border-collapse:collapse; margin-top:10px; }
th, td{ border:1px solid #ccc; padding:8px; font-size:14px; }
th{ background:#003c96; color:white; }
</style>

</head>
<body>

<h1>RAPPORT IMMO TRUST</h1>

<p>Date : <strong><?= date('d/m/Y H:i') ?></strong></p>

<div class="section">
<h2>Statistiques générales</h2>
<ul>
    <li>Total RDV : <strong><?= $total_rdv ?></strong></li>
    <li>Total Leads IMMO : <strong><?= $total_leads ?></strong></li>
    <li>Total messages : <strong><?= $total_msgs ?></strong></li>
    <li>Total agents : <strong><?= $total_agents ?></strong></li>
</ul>
</div>

<div class="section">
<h2>20 derniers leads</h2>

<table>
<tr>
    <th>Nom</th>
    <th>Téléphone</th>
    <th>Demande</th>
    <th>Date</th>
</tr>

<?php foreach($leads as $l): ?>
<tr>
    <td><?= htmlspecialchars($l['nom']) ?></td>
    <td><?= htmlspecialchars($l['telephone']) ?></td>
    <td><?= htmlspecialchars($l['type_demande']) ?></td>
    <td><?= $l['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>
</div>

<br><br>
<p>Imprimez → Enregistrer en PDF.</p>

</body>
</html>
