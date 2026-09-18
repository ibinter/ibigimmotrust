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

$sql = "SELECT id, nom, telephone, email, statut, created_at 
        FROM agents ORDER BY id DESC";
$data = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Export PDF – Agents IMMO</title>

<style>
body {
    font-family: Arial, sans-serif;
    padding: 20px;
}
h2 {
    text-align: center;
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}
th, td {
    padding: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
}
th {
    background: #003c96;
    color: #fff;
}
tr:nth-child(even){
    background:#f7f7f7;
}
.note {
    margin-top: 25px;
    font-size: 12px;
    color: #666;
}
</style>
</head>
<body>

<h2>IMMO TRUST – Liste des agents</h2>

<table>
<tr>
    <th>ID</th>
    <th>Nom complet</th>
    <th>Téléphone</th>
    <th>Email</th>
    <th>Statut</th>
    <th>Date inscription</th>
</tr>

<?php foreach($data as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['nom']) ?></td>
    <td><?= htmlspecialchars($r['telephone']) ?></td>
    <td><?= htmlspecialchars($r['email']) ?></td>
    <td><?= htmlspecialchars($r['statut']) ?></td>
    <td><?= $r['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>

<p class="note">
PDF généré avec succès via votre navigateur (Imprimer → Enregistrer PDF).
</p>

</body>
</html>
