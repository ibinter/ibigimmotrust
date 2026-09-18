<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) die("ID RDV invalide");

$st = $pdo->prepare("SELECT r.*, a.name AS agent_name 
                     FROM rdv_requests r 
                     LEFT JOIN agents a ON r.assigned_to = a.id
                     WHERE r.id=? LIMIT 1");
$st->execute([$id]);
$rdv = $st->fetch(PDO::FETCH_ASSOC);

if (!$rdv) die("RDV introuvable");

include __DIR__ . '/menu.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Fiche RDV – IBIG IMMO TRUST</title>

<style>
body{
    font-family:system-ui, sans-serif;
    background:#f4f6fb;
    padding:20px;
}
.card{
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
    max-width:900px;
    margin:auto;
}
h1{
    color:#003c96;
    font-weight:800;
    margin-bottom:20px;
}
.info{
    margin-bottom:15px;
}
.info label{
    font-weight:600;
    display:block;
    margin-bottom:3px;
    color:#003c96;
}
.info .value{
    font-size:15px;
    background:#f9fafb;
    padding:10px;
    border-radius:6px;
}
.btn{
    background:#003c96;
    color:white;
    padding:8px 15px;
    border-radius:6px;
    text-decoration:none;
}
.back{
    margin-bottom:20px;
    display:inline-block;
}
.badge{
    padding:5px 10px;
    font-size:13px;
    border-radius:6px;
    color:white;
}
.badge.wait{ background:#ff9f1c; }
.badge.ok{ background:#10b981; }
.badge.done{ background:#4b5563; }
</style>

</head>
<body>

<a href="rdv.php" class="btn back">← Retour à la liste</a>

<a href="export_rdv_pdf.php?id=<?= $rdv['id'] ?>" class="btn" style="background:#10b981; margin-left:10px;">
     Télécharger PDF
</a>

<div class="card">

<h1> Fiche détaillée du rendez-vous #<?= $rdv['id'] ?></h1>

<div class="info">
    <label>Nom complet :</label>
    <div class="value"><?= htmlspecialchars($rdv['name']) ?></div>
</div>

<div class="info">
    <label>Téléphone :</label>
    <div class="value"><?= htmlspecialchars($rdv['phone']) ?></div>
</div>

<div class="info">
    <label>Email :</label>
    <div class="value"><?= htmlspecialchars($rdv['email']) ?></div>
</div>

<div class="info">
    <label>Objet / Type :</label>
    <div class="value"><?= htmlspecialchars($rdv['type']) ?></div>
</div>

<div class="info">
    <label>Date & Heure du RDV :</label>
    <div class="value"><?= $rdv['date_rdv'] ?> à <?= $rdv['time_rdv'] ?></div>
</div>

<div class="info">
    <label>Message :</label>
    <div class="value" style="white-space:pre-line;"><?= htmlspecialchars($rdv['message']) ?></div>
</div>

<div class="info">
    <label>Statut :</label>
    <div class="value">
        <?php if ($rdv['status'] == "En attente"): ?>
            <span class="badge wait">En attente</span>
        <?php elseif ($rdv['status'] == "Confirmé"): ?>
            <span class="badge ok">Confirmé</span>
        <?php else: ?>
            <span class="badge done">Traité</span>
        <?php endif; ?>
    </div>
</div>

<div class="info">
    <label>Assigné à :</label>
    <div class="value"><?= $rdv['agent_name'] ?: "— Aucun —" ?></div>
</div>

<div class="info">
    <label>Créé le :</label>
    <div class="value"><?= $rdv['created_at'] ?></div>
</div>

</div>

</body>
</html>
