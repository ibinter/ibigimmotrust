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

// Récupération des colonnes existantes
$sql = "
    SELECT 
        id, 
        titre, 
        type,
        transaction,
        prix,
        ville,
        quartier,
        statut,
        created_at
    FROM immo_biens
    ORDER BY created_at DESC
";

$rows = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Export PDF – Biens immobiliers</title>

<style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
}
h1 {
    text-align: center;
    margin-bottom: 10px;
}
.small {
    text-align:center;
    margin-bottom:20px;
    font-size:13px;
    color:#555;
}
table {
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
    font-size:12px;
}
th, td {
    border:1px solid #444;
    padding:6px;
}
th {
    background:#003c96;
    color:white;
}
@media print {
    .no-print { display:none; }
}
</style>

</head>
<body>

<div class="no-print" style="margin-bottom:20px;">
    <button onclick="window.print()" 
            style="padding:10px 18px;background:#003c96;color:white;border:none;border-radius:6px;font-size:14px;cursor:pointer;">
        ð¨ï¸ Imprimer / Exporter en PDF
    </button>
</div>

<h1>IBIG IMMO TRUST</h1>
<div class="small">
    Export des biens immobiliers<br>
    Date : <?= date("d/m/Y H:i") ?>
</div>

<table>
<tr>
    <th>ID</th>
    <th>Titre</th>
    <th>Type</th>
    <th>Transaction</th>
    <th>Prix</th>
    <th>Ville</th>
    <th>Quartier</th>
    <th>Statut</th>
    <th>Date ajout</th>
</tr>

<?php foreach ($rows as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['titre']) ?></td>
    <td><?= htmlspecialchars($r['type']) ?></td>
    <td><?= htmlspecialchars($r['transaction']) ?></td>
    <td><?= number_format($r['prix'],0,',',' ') ?></td>
    <td><?= htmlspecialchars($r['ville']) ?></td>
    <td><?= htmlspecialchars($r['quartier']) ?></td>
    <td><?= htmlspecialchars($r['statut']) ?></td>
    <td><?= $r['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
