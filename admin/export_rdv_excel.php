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

// Récupération des RDV
$stmt = $pdo->query("
    SELECT id, name, phone, type, date_rdv, time_rdv, status, created_at
    FROM rdv_requests
    ORDER BY id DESC
");

$rdv = $stmt->fetchAll(PDO::FETCH_ASSOC);

// === ENTÊTES POUR FICHIER EXCEL ===
header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=IBIG_RDV_".date('Y-m-d_H-i').".xls");
header("Pragma: no-cache");
header("Expires: 0");

echo "<html><meta charset='UTF-8'><body>";

echo "<h2>IBIG IMMO TRUST – Export des RDV</h2>";
echo "<p>Date d'export : <strong>".date("d/m/Y H:i")."</strong></p>";

echo "<table border='1' cellspacing='0' cellpadding='6'>";
echo "
<tr style='background:#003c96;color:#fff;font-weight:bold;'>
    <th>ID</th>
    <th>Nom</th>
    <th>Téléphone</th>
    <th>Type</th>
    <th>Date RDV</th>
    <th>Heure</th>
    <th>Statut</th>
    <th>Date création</th>
</tr>
";

foreach ($rdv as $r) {
    echo "<tr>
        <td>{$r['id']}</td>
        <td>".htmlspecialchars($r['name'])."</td>
        <td>".htmlspecialchars($r['phone'])."</td>
        <td>".htmlspecialchars($r['type'])."</td>
        <td>{$r['date_rdv']}</td>
        <td>{$r['time_rdv']}</td>
        <td>{$r['status']}</td>
        <td>{$r['created_at']}</td>
    </tr>";
}

echo "</table>";
echo "</body></html>";
exit;
?>
