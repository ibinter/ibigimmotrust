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

// Colonnes existantes dans ta base immo_biens
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

// Nom du fichier
$filename = "IBIG_Biens_" . date("Y-m-d_His") . ".csv";

// Headers pour télécharger comme Excel
header("Content-Type: text/csv; charset=UTF-8");
header("Content-Disposition: attachment; filename=\"$filename\"");

// BOM UTF-8 pour que Excel lise correctement
echo "\xEF\xBB\xBF";

// Ouvre un flux de sortie
$output = fopen("php://output", "w");

// Ligne d’en-tête
fputcsv($output, [
    "ID", "Titre", "Type", "Transaction",
    "Prix (FCFA)", "Ville", "Quartier",
    "Statut", "Date d’ajout"
], ";");

// Contenu
foreach ($rows as $r) {
    fputcsv($output, [
        $r['id'],
        $r['titre'],
        $r['type'],
        $r['transaction'],
        number_format($r['prix'], 0, ',', ' '),
        $r['ville'],
        $r['quartier'],
        $r['statut'],
        $r['created_at']
    ], ";");
}

fclose($output);
exit;
?>
