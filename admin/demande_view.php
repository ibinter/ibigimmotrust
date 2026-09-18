<?php
require_once __DIR__ . '/../includes/config.php';
session_start();

if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

$id = (int)($_GET['id'] ?? 0);

$st = $pdo->prepare("SELECT * FROM immo_leads WHERE id=?");
$st->execute([$id]);
$d = $st->fetch(PDO::FETCH_ASSOC);

if (!$d) die("Demande introuvable");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Détail demande #<?= $id ?></title>

<?php include __DIR__ . '/menu.php'; ?>

<style>
body{ font-family:Arial; padding:20px; background:#f4f6fb; }
.box{
  background:white;
  padding:25px;
  border-radius:12px;
  max-width:700px;
  margin:auto;
  box-shadow:0 2px 10px rgba(0,0,0,0.1);
}
p{ margin-bottom:10px; font-size:15px; }
h2{ color:#003c96; margin-bottom:20px; text-align:center; }
.label{ font-weight:bold; color:#003c96; }
.msg{
    background:#f8f9ff;
    padding:12px;
    border-radius:8px;
    white-space:pre-line;
    border:1px solid #e5e7eb;
}
</style>
</head>
<body>

<h2> Détail de la demande #<?= $id ?></h2>

<div class="box">

  <p><span class="label">Nom :</span> <?= htmlspecialchars($d['nom']) ?></p>
  <p><span class="label">Téléphone :</span> <?= htmlspecialchars($d['telephone']) ?></p>
  <p><span class="label">Email :</span> <?= htmlspecialchars($d['email']) ?></p>
  <p><span class="label">Ville :</span> <?= htmlspecialchars($d['ville']) ?></p>
  <p><span class="label">Type de demande :</span> <?= htmlspecialchars($d['type_demande']) ?></p>
  <p><span class="label">Budget :</span> <?= htmlspecialchars($d['budget']) ?></p>

  <p><span class="label">Service :</span> <?= htmlspecialchars($d['service']) ?></p>
  <p><span class="label">Surface :</span> <?= htmlspecialchars($d['surface']) ?></p>

  <p><span class="label">Message :</span><br>
     <div class="msg"><?= nl2br(htmlspecialchars($d['details'])) ?></div>
  </p>

  <p><span class="label">IP :</span> <?= $d['ip_address'] ?></p>
  <p><span class="label">Origine :</span> <?= $d['origin_page'] ?></p>
  <p><span class="label">Date :</span> <?= $d['created_at'] ?></p>

</div>

</body>
</html>