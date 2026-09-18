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

// Récupérer les RDV
$stmt = $pdo->query("
    SELECT id, name, phone, type, date_rdv, time_rdv, status, created_at
    FROM rdv_requests
    ORDER BY id DESC
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Forcer le navigateur à ouvrir une fenêtre d'impression PDF
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Export PDF – RDV IBIG IMMO TRUST</title>

<style>
body{
    font-family: Arial, sans-serif;
    margin: 20px;
    font-size: 14px;
}
h2{
    text-align: center;
    margin-bottom: 10px;
}
table{
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td{
    border: 1px solid #333;
    padding: 8px;
    text-align: left;
}
th{
    background: #003c96;
    color: white;
}
</style>

<script>
// Ouvrir la fenêtre d'impression automatiquement
window.onload = function(){
    window.print();
};
</script>

</head>
<body>

<h2>IBIG IMMO TRUST – Export PDF des Rendez-vous</h2>
<p>Date d’export : <strong><?= date("d/m/Y H:i") ?></strong></p>

<table>
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Téléphone</th>
    <th>Type</th>
    <th>Date RDV</th>
    <th>Heure</th>
    <th>Statut</th>
    <th>Créé le</th>
</tr>

<?php foreach($rows as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['name']) ?></td>
    <td><?= htmlspecialchars($r['phone']) ?></td>
    <td><?= htmlspecialchars($r['type']) ?></td>
    <td><?= $r['date_rdv'] ?></td>
    <td><?= $r['time_rdv'] ?></td>
    <td><?= $r['status'] ?></td>
    <td><?= $r['created_at'] ?></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
