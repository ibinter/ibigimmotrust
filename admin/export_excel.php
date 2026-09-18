<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin'])) { die("Accès refusé"); }

// Récupération des données
$stmt = $pdo->query("
    SELECT id, nom, telephone, type_demande, budget, ville, created_at
    FROM immo_leads
    ORDER BY id DESC
");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nom du fichier
$filename = "IBIG_Export_Leads_" . date("Y-m-d_His") . ".xls";

// Headers Excel
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Pragma: no-cache");
header("Expires: 0");

// UTF-8
echo "\xEF\xBB\xBF";

// =======================================
// STYLE CSS POUR LE FICHIER EXCEL
// =======================================
echo "
<style>
table { border-collapse: collapse; width: 100%; }
th {
    background: #003c96;
    color: #fff;
    padding: 8px;
    border: 1px solid #ccc;
    font-weight: bold;
}
td {
    padding: 7px;
    border: 1px solid #ccc;
}
.title {
    font-size: 20px;
    font-weight: bold;
    text-align: center;
}
.subtitle {
    font-size: 13px;
    text-align: center;
    margin-bottom: 10px;
}
</style>
";

// =======================================
// EN-TÊTE TITRE
// =======================================
echo "
<h2 class='title'>ð IBIG IMMO TRUST – Export des demandes clients</h2>
<p class='subtitle'>Date d’export : <strong>" . date('d/m/Y H:i') . "</strong></p>
";

// =======================================
// TABLEAU
// =======================================
echo "<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Téléphone</th>
    <th>Type de demande</th>
    <th>Budget (FCFA)</th>
    <th>Ville</th>
    <th>Date</th>
</tr>
</thead>
<tbody>
";

foreach($rows as $r){
    echo "<tr>
            <td>{$r['id']}</td>
            <td>{$r['nom']}</td>
            <td>{$r['telephone']}</td>
            <td>{$r['type_demande']}</td>
            <td>{$r['budget']}</td>
            <td>{$r['ville']}</td>
            <td>{$r['created_at']}</td>
         </tr>";
}

echo "</tbody></table>";
exit;
?>
