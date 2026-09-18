<?php
header("Content-Type: application/json");
require_once __DIR__ . '/../includes/config.php';

// MODE : period (en jours) ou custom (from/to)
$mode   = $_GET['mode']   ?? 'period';
$period = $_GET['period'] ?? '30';
$from   = $_GET['from']   ?? null;
$to     = $_GET['to']     ?? null;

$where = " WHERE 1=1 ";
$params = [];

// Libellé du filtre
$filter_label = "";

// Gestion période
if ($mode === 'custom' && $from && $to) {
    $where .= " AND DATE(created_at) BETWEEN :from AND :to ";
    $params[':from'] = $from;
    $params[':to']   = $to;
    $filter_label = "Période du {$from} au {$to}";
} else {
    if ($period === 'all') {
        $filter_label = "Tout l'historique";
    } else {
        $days = (int)$period;
        if ($days <= 0) $days = 30;
        $startDate = date('Y-m-d', strtotime("-{$days} days"));
        $where .= " AND DATE(created_at) >= :startDate ";
        $params[':startDate'] = $startDate;
        $filter_label = "Derniers {$days} jours (depuis le {$startDate})";
    }
}

// Helper
function fetchValue($pdo, $sql, $params = []){
    $st = $pdo->prepare($sql);
    $st->execute($params);
    return (int)$st->fetchColumn();
}

// ===== STATS DE BASE =====

// Total leads sur la période
$sqlTotal = "SELECT COUNT(*) FROM immo_leads " . $where;
$total = fetchValue($pdo, $sqlTotal, $params);

// Local vs diaspora sur la période
$sqlLocal = "SELECT COUNT(*) FROM immo_leads {$where} AND is_local='oui'";
$local = fetchValue($pdo, $sqlLocal, $params);

$sqlDiaspora = "SELECT COUNT(*) FROM immo_leads {$where} AND is_local='non'";
$diaspora = fetchValue($pdo, $sqlDiaspora, $params);

// Leads du jour (global)
$today = fetchValue(
    $pdo,
    "SELECT COUNT(*) FROM immo_leads WHERE DATE(created_at) = CURDATE()"
);

// Répartition des besoins (sur la période)
$sqlNeeds = "
    SELECT main_need, COUNT(*) AS total
    FROM immo_leads
    {$where}
    GROUP BY main_need
    ORDER BY total DESC
";
$st = $pdo->prepare($sqlNeeds);
$st->execute($params);
$needsRows = $st->fetchAll(PDO::FETCH_ASSOC);

$needs_labels = [];
$needs_values = [];
foreach($needsRows as $row){
    $label = $row['main_need'] ?: 'Non précisé';
    $needs_labels[] = $label;
    $needs_values[] = (int)$row['total'];
}

// Top besoin principal (libellé)
$top_need = $needsRows[0]['main_need'] ?? null;

// Top 5 besoins pour liste
$top_needs_labels = [];
$top_needs_values = [];
foreach(array_slice($needsRows, 0, 5) as $row){
    $label = $row['main_need'] ?: 'Non précisé';
    $top_needs_labels[] = $label;
    $top_needs_values[] = (int)$row['total'];
}

// Top 5 localisations
$sqlLoc = "
    SELECT location, COUNT(*) AS total
    FROM immo_leads
    {$where}
    GROUP BY location
    HAVING location IS NOT NULL AND location <> ''
    ORDER BY total DESC
    LIMIT 5
";
$st = $pdo->prepare($sqlLoc);
$st->execute($params);
$locRows = $st->fetchAll(PDO::FETCH_ASSOC);

$top_locations_labels = [];
$top_locations_values = [];
foreach($locRows as $row){
    $top_locations_labels[] = $row['location'];
    $top_locations_values[] = (int)$row['total'];
}

// Activité journalière
$sqlDaily = "
    SELECT DATE(created_at) AS d, COUNT(*) AS t
    FROM immo_leads
    {$where}
    GROUP BY DATE(created_at)
    ORDER BY DATE(created_at) ASC
";
$st = $pdo->prepare($sqlDaily);
$st->execute($params);
$dailyRows = $st->fetchAll(PDO::FETCH_ASSOC);

$days = [];
$day_values = [];
foreach($dailyRows as $row){
    $days[] = $row['d'];
    $day_values[] = (int)$row['t'];
}

// Moyenne par jour sur la période affichée
$nbDays = count($days);
$avg_per_day = ($nbDays > 0) ? round($total / $nbDays, 1) : 0;

// Réponse JSON
echo json_encode([
    "total"                => $total,
    "local"                => $local,
    "diaspora"             => $diaspora,
    "today"                => $today,
    "needs_labels"         => $needs_labels,
    "needs_values"         => $needs_values,
    "days"                 => $days,
    "day_values"           => $day_values,
    "avg_per_day"          => $avg_per_day,
    "filter_label"         => $filter_label,
    "top_need"             => $top_need,
    "top_needs_labels"     => $top_needs_labels,
    "top_needs_values"     => $top_needs_values,
    "top_locations_labels" => $top_locations_labels,
    "top_locations_values" => $top_locations_values,
]);
