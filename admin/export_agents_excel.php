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

header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=agents_".date('Y-m-d_His').".csv");
header("Pragma: no-cache");
header("Expires: 0");

// BOM UTF-8 (important pour Excel)
echo "\xEF\xBB\xBF";

// Colonnes
$columns = [
    "ID",
    "Nom complet",
    "Téléphone",
    "Email",
    "Statut",
    "Date d'inscription"
];

echo implode(";", $columns)."\n";

// Récupération données
$sql = "SELECT id, nom, telephone, email, statut, created_at FROM agents ORDER BY id DESC";
$q = $pdo->query($sql);

while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
    echo implode(";", array_map(function($v){
        return str_replace(["\n","\r",";"], " ", $v);
    }, $row))."\n";
}
exit;
