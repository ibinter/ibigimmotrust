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

// Récupération des données
$stmt = $pdo->query("
    SELECT id, nom, telephone, type_demande, budget, ville, created_at
    FROM immo_leads
    ORDER BY id DESC
");

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// On envoie du HTML NORMAL
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Export PDF – IBIG IMMO TRUST</title>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 40px;
    font-size: 13px;
}
h1 {
    text-align: center;
    color:#003c96;
    margin-bottom: 5px;
}
.subtitle {
    text-align: center;
    margin-bottom: 25px;
    color:#444;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th {
    background: #003c96;
    color: white;
    padding: 8px;
    font-size: 12px;
    border: 1px solid #ccc;
}
td {
    padding: 7px;
    font-size: 12px;
    border: 1px solid #ccc;
}
tr:nth-child(even) {
    background: #f4f6fb;
}
.print-note {
    margin-top: 15px;
    font-size: 11px;
    color:#666;
    text-align:center;
}
</style>
</head>

<body>

<h1>IBIG IMMO TRUST – Export PDF</h1>
<div class="subtitle">Date d'export : <strong><?= date("d/m/Y H:i") ?></strong></div>

<table>
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Téléphone</th>
    <th>Type de demande</th>
    <th>Budget</th>
    <th>Ville</th>
    <th>Date</th>
</tr>

<?php foreach ($data as $d): ?>
<tr>
    <td><?= $d['id'] ?></td>
    <td><?= htmlspecialchars($d['nom']) ?></td>
    <td><?= htmlspecialchars($d['telephone']) ?></td>
    <td><?= htmlspecialchars($d['type_demande']) ?></td>
    <td><?= htmlspecialchars($d['budget']) ?></td>
    <td><?= htmlspecialchars($d['ville']) ?></td>
    <td><?= $d['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>

<p class="print-note">
â¡ Pour enregistrer en PDF : CTRL+P → Destination → “Enregistrer en PDF”
</p>

</body>
</html>
