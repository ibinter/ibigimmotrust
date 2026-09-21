<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/tracker.php';

$currentPage = 'biens';

if (!function_exists('e')) {
    function e(string $str): string
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

function biens_fix_text(?string $value): string
{
    $text = trim((string) $value);
    if ($text === '') {
        return '';
    }

    if (preg_match('/(?:Ãƒ.|Ã¢.|Ã‚)/u', $text)) {
        $converted = @iconv('Windows-1252', 'UTF-8//IGNORE', $text);
        if (is_string($converted) && $converted !== '') {
            $text = $converted;
        }
    }

    $text = strtr($text, [
        'â€™' => "'",
        'â€˜' => "'",
        'â€œ' => '"',
        'â€Â' => '"',
        'â€“' => '-',
        'â€”' => '-',
        'â€¦' => '...',
        "\xc2\xa0" => ' ',
    ]);

    $text = preg_replace('/\s+/u', ' ', $text);
    return trim((string) $text);
}

function biens_clean_int_money($value): int
{
    $digits = preg_replace('/\D+/', '', (string) $value);
    return $digits !== '' ? (int) $digits : 0;
}

function biens_is_new($createdAt, int $days = 10): bool
{
    if (empty($createdAt)) {
        return false;
    }

    $timestamp = strtotime((string) $createdAt);
    if (!$timestamp) {
        return false;
    }

    return (time() - $timestamp) <= ($days * 86400);
}

function biens_build_query(array $extra = [], array $source = null): string
{
    $base = $source ?? $_GET;

    foreach ($extra as $key => $value) {
        if ($value === null || $value === '') {
            unset($base[$key]);
            continue;
        }

        $base[$key] = $value;
    }

    unset($base['slug'], $base['id']);

    $query = http_build_query($base);
    return $query !== '' ? '?' . $query : '';
}

function biens_page_url(array $extra = [], array $source = null): string
{
    return url('tous_les_biens.php') . biens_build_query($extra, $source);
}

function biens_absolute_url(string $path): string
{
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }

    return rtrim((string) BASE_URL, '/') . '/' . ltrim($path, '/');
}

function biens_price_label($price): string
{
    $amount = (float) $price;
    if ($amount <= 0) {
        return 'Prix sur demande';
    }

    return number_format($amount, 0, ',', ' ') . ' FCFA';
}

function biens_transaction_filter(string $value): string
{
    $normalized = strtolower(trim(str_replace('_', '-', $value)));

    if ($normalized === '') {
        return '';
    }

    if (in_array($normalized, ['vente', 'a-vendre', 'a vendre', 'vendre', 'avendre'], true)) {
        return 'vente';
    }

    if (in_array($normalized, ['location', 'a-louer', 'a louer', 'louer', 'alouer'], true)) {
        return 'location';
    }

    return $normalized;
}

function biens_transaction_variants(string $normalized): array
{
    if ($normalized === 'vente') {
        return ['vente', 'a vendre', 'a-vendre', 'à vendre', 'avendre'];
    }

    if ($normalized === 'location') {
        return ['location', 'a louer', 'a-louer', 'à louer', 'alouer'];
    }

    return [str_replace('-', ' ', $normalized)];
}

function biens_transaction_label(?string $value): string
{
    $normalized = biens_transaction_filter((string) $value);

    if ($normalized === 'vente') {
        return 'À vendre';
    }

    if ($normalized === 'location') {
        return 'À louer';
    }

    $text = trim((string) $value);
    if ($text === '') {
        return 'Transaction';
    }

    return ucfirst($text);
}

function biens_type_filter(string $value): string
{
    $normalized = strtolower(trim(str_replace(['_', '/'], ['-', '-'], $value)));
    $normalized = str_replace(['&', '  '], ['et', ' '], $normalized);

    $aliases = [
        'villas' => 'villa',
        'villa' => 'villa',
        'appartements' => 'appartement',
        'appartement' => 'appartement',
        'terrains' => 'terrain',
        'terrain' => 'terrain',
        'maisons' => 'maison',
        'maison' => 'maison',
        'immeubles' => 'immeuble',
        'immeuble' => 'immeuble',
        'bureaux' => 'bureau',
        'bureau' => 'bureau',
        'duplex' => 'duplex',
        'locaux-commerciaux' => 'local commercial',
        'local-commercial' => 'local commercial',
        'local-commerciaux' => 'local commercial',
        'local commercial' => 'local commercial',
        'entrepots' => 'entrepot',
        'entrepot' => 'entrepot',
        'entrepots' => 'entrepot',
    ];

    if (isset($aliases[$normalized])) {
        return $aliases[$normalized];
    }

    return str_replace('-', ' ', $normalized);
}

function biens_type_label(string $value): string
{
    $normalized = biens_type_filter($value);

    $labels = [
        'terrain' => 'Terrain',
        'appartement' => 'Appartement',
        'maison' => 'Maison',
        'villa' => 'Villa',
        'duplex' => 'Duplex',
        'immeuble' => 'Immeuble',
        'bureau' => 'Bureau',
        'local commercial' => 'Local commercial',
        'entrepot' => 'Entrepôt',
    ];

    if (isset($labels[$normalized])) {
        return $labels[$normalized];
    }

    return ucwords($normalized);
}

function biens_type_variants(string $normalized): array
{
    $normalized = biens_type_filter($normalized);

    $variants = [
        'terrain' => ['terrain'],
        'appartement' => ['appartement'],
        'maison' => ['maison'],
        'villa' => ['villa'],
        'duplex' => ['duplex'],
        'immeuble' => ['immeuble'],
        'bureau' => ['bureau'],
        'local commercial' => ['local commercial'],
        'entrepot' => ['entrepot'],
    ];

    return $variants[$normalized] ?? [$normalized];
}

function biens_location_term(string $value): string
{
    $value = biens_fix_text($value);
    if ($value === '') {
        return '';
    }

    $parts = preg_split('/\\s+-\\s+|,\\s*/', $value);
    $city = trim((string) ($parts[0] ?? $value));

    if (strpos($city, '-') !== false && strpos($city, ' ') === false) {
        $city = str_replace('-', ' ', $city);
    }

    return trim($city);
}

function biens_visibility_value(array $bien): string
{
    $value = strtolower(trim((string) ($bien['niveau_visibilite'] ?? '')));
    return $value !== '' ? $value : 'standard';
}

function biens_visibility_label(string $value): string
{
    $labels = [
        'exclusif' => 'Exclusif',
        'vedette' => 'Vedette',
        'libre' => 'Libre',
        'standard' => 'Standard',
        'normal' => 'Standard & Libre',
    ];

    return $labels[$value] ?? ucfirst($value);
}

function biens_image_path(array $bien): string
{
    if (!empty($bien['image_principale'])) {
        return '/' . ltrim((string) $bien['image_principale'], '/');
    }

    return '/assets/img/no-image.jpg';
}

function biens_detail_path(array $bien): string
{
    if (!empty($bien['slug'])) {
        return '/bien/' . rawurlencode((string) $bien['slug']);
    }

    return '/bien/' . (int) $bien['id'];
}

function biens_location_label(array $bien): string
{
    $parts = [];

    $ville = biens_fix_text((string) ($bien['ville'] ?? ''));
    $quartier = biens_fix_text((string) ($bien['quartier'] ?? ''));

    if ($ville !== '') {
        $parts[] = $ville;
    }

    if ($quartier !== '') {
        $parts[] = $quartier;
    }

    return $parts ? implode(' - ', $parts) : 'Localisation à confirmer';
}

function biens_meta_tokens(array $bien): array
{
    $tokens = [];

    $surface = biens_fix_text((string) ($bien['superficie'] ?? ($bien['superficie_habitable'] ?? '')));
    if ($surface !== '') {
        $tokens[] = $surface;
    }

    if (!empty($bien['chambres'])) {
        $tokens[] = (int) $bien['chambres'] . ' ch.';
    }

    if (!empty($bien['piscine'])) {
        $tokens[] = 'Piscine';
    }

    if (!empty($bien['jardin'])) {
        $tokens[] = 'Jardin';
    }

    return $tokens;
}

function biens_excerpt(array $bien, int $limit = 120): string
{
    $text = biens_fix_text(strip_tags((string) ($bien['description_detaillee'] ?? ($bien['description'] ?? ''))));
    if ($text === '') {
        return 'Un bien sélectionné par IBIG IMMO TRUST pour un accompagnement rapide et sécurisé.';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $limit - 1)) . 'â€¦';
    }

    if (strlen($text) <= $limit) {
        return $text;
    }

    return rtrim(substr($text, 0, $limit - 1)) . '...';
}

function biens_pagination_items(int $currentPage, int $totalPages): array
{
    if ($totalPages <= 1) {
        return [1];
    }

    if ($totalPages <= 7) {
        return range(1, $totalPages);
    }

    $items = [1];
    $start = max(2, $currentPage - 1);
    $end = min($totalPages - 1, $currentPage + 1);

    if ($currentPage <= 4) {
        $start = 2;
        $end = 4;
    } elseif ($currentPage >= $totalPages - 3) {
        $start = $totalPages - 3;
        $end = $totalPages - 1;
    }

    if ($start > 2) {
        $items[] = '...';
    }

    for ($i = $start; $i <= $end; $i++) {
        $items[] = $i;
    }

    if ($end < $totalPages - 1) {
        $items[] = '...';
    }

    $items[] = $totalPages;
    return $items;
}

$allowedMise = ['exclusif', 'vedette', 'standard', 'libre', 'normal'];
$miseFilter = strtolower(trim((string) ($_GET['mise'] ?? '')));
if (!in_array($miseFilter, $allowedMise, true)) {
    $miseFilter = '';
}

$villeInput = trim((string) ($_GET['ville'] ?? ''));
$villeSearch = biens_location_term($villeInput);
$quartier = trim((string) ($_GET['quartier'] ?? ''));
$typeFilter = biens_type_filter((string) ($_GET['type'] ?? ''));
$transactionFilter = biens_transaction_filter((string) ($_GET['transaction'] ?? ''));
$prixMinInput = trim((string) ($_GET['prix_min'] ?? ($_GET['min_price'] ?? '')));
$prixMaxInput = trim((string) ($_GET['prix_max'] ?? ($_GET['max_price'] ?? '')));
$chambresMin = max(0, (int) ($_GET['chambres'] ?? 0));
$avecPiscine = isset($_GET['avec_piscine']) && $_GET['avec_piscine'] !== '0';
$avecJardin = isset($_GET['avec_jardin']) && $_GET['avec_jardin'] !== '0';
$sort = trim((string) ($_GET['sort'] ?? 'date_desc'));
$allowedSort = ['date_desc', 'date_asc', 'price_asc', 'price_desc'];
if (!in_array($sort, $allowedSort, true)) {
    $sort = 'date_desc';
}

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 12;
$waNumber = '2250584437474';
$pageBaseUrl = url('tous_les_biens.php');

$hasCatalogueFilters = (
    $miseFilter !== '' ||
    $villeSearch !== '' ||
    $quartier !== '' ||
    $typeFilter !== '' ||
    $transactionFilter !== '' ||
    $prixMinInput !== '' ||
    $prixMaxInput !== '' ||
    $chambresMin > 0 ||
    $avecPiscine ||
    $avecJardin
);

$catalogueStats = [
    'total' => 0,
    'exclusif_total' => 0,
    'vedette_total' => 0,
    'libre_total' => 0,
    'vente_total' => 0,
    'location_total' => 0,
    'villes_total' => 0,
];

$popularCities = [];
$typeOptions = [];
$biensExclusif = [];
$biensVedette = [];
$biens = [];
$total = 0;
$totalPages = 1;
$offset = 0;

try {
    $statsSql = "
        SELECT
            COUNT(*) AS total,
            SUM(CASE WHEN LOWER(TRIM(COALESCE(niveau_visibilite, 'standard'))) = 'exclusif' THEN 1 ELSE 0 END) AS exclusif_total,
            SUM(CASE WHEN LOWER(TRIM(COALESCE(niveau_visibilite, 'standard'))) = 'vedette' THEN 1 ELSE 0 END) AS vedette_total,
            SUM(CASE WHEN LOWER(TRIM(COALESCE(niveau_visibilite, 'standard'))) = 'libre' THEN 1 ELSE 0 END) AS libre_total,
            SUM(CASE WHEN LOWER(TRIM(COALESCE(transaction, ''))) IN ('vente', 'a vendre', 'a-vendre', 'à vendre', 'avendre') THEN 1 ELSE 0 END) AS vente_total,
            SUM(CASE WHEN LOWER(TRIM(COALESCE(transaction, ''))) IN ('location', 'a louer', 'a-louer', 'à louer', 'alouer') THEN 1 ELSE 0 END) AS location_total,
            COUNT(DISTINCT NULLIF(TRIM(ville), '')) AS villes_total
        FROM immo_biens
        WHERE visible = 1
          AND statut_publication = 'valide'
    ";
    $catalogueStatsStmt = $pdo->query($statsSql);
    $catalogueStatsData = $catalogueStatsStmt ? $catalogueStatsStmt->fetch(PDO::FETCH_ASSOC) : [];
    if (is_array($catalogueStatsData)) {
        foreach ($catalogueStats as $key => $value) {
            $catalogueStats[$key] = isset($catalogueStatsData[$key]) ? (int) $catalogueStatsData[$key] : 0;
        }
    }

    $popularCitiesStmt = $pdo->query("
        SELECT TRIM(ville) AS ville_name, COUNT(*) AS total
        FROM immo_biens
        WHERE visible = 1
          AND statut_publication = 'valide'
          AND ville IS NOT NULL
          AND TRIM(ville) <> ''
        GROUP BY TRIM(ville)
        ORDER BY total DESC, ville_name ASC
        LIMIT 8
    ");
    $popularCities = $popularCitiesStmt ? $popularCitiesStmt->fetchAll(PDO::FETCH_ASSOC) : [];

    $typesStmt = $pdo->query("
        SELECT DISTINCT TRIM(type) AS type_name
        FROM immo_biens
        WHERE visible = 1
          AND statut_publication = 'valide'
          AND type IS NOT NULL
          AND TRIM(type) <> ''
        ORDER BY type_name ASC
    ");
    $rawTypeOptions = $typesStmt ? $typesStmt->fetchAll(PDO::FETCH_COLUMN) : [];

    $typeMap = [];
    foreach ($rawTypeOptions as $rawType) {
        $key = biens_type_filter((string) $rawType);
        if ($key === '') {
            continue;
        }
        $typeMap[$key] = biens_type_label($key);
    }

    $preferredTypeOrder = [
        'terrain',
        'appartement',
        'maison',
        'villa',
        'duplex',
        'immeuble',
        'bureau',
        'local commercial',
        'entrepot',
    ];

    foreach ($preferredTypeOrder as $preferredType) {
        if (isset($typeMap[$preferredType])) {
            $typeOptions[$preferredType] = $typeMap[$preferredType];
            unset($typeMap[$preferredType]);
        }
    }

    if (!empty($typeMap)) {
        asort($typeMap);
        foreach ($typeMap as $key => $label) {
            $typeOptions[$key] = $label;
        }
    }

    $biensExclusifStmt = $pdo->query("
        SELECT *
        FROM immo_biens
        WHERE visible = 1
          AND statut_publication = 'valide'
          AND LOWER(TRIM(COALESCE(niveau_visibilite, 'standard'))) = 'exclusif'
        ORDER BY COALESCE(priorite, 0) DESC, COALESCE(created_at, '1970-01-01') DESC, id DESC
        LIMIT 5
    ");
    $biensExclusif = $biensExclusifStmt ? $biensExclusifStmt->fetchAll(PDO::FETCH_ASSOC) : [];

    $biensVedetteStmt = $pdo->query("
        SELECT *
        FROM immo_biens
        WHERE visible = 1
          AND statut_publication = 'valide'
          AND LOWER(TRIM(COALESCE(niveau_visibilite, 'standard'))) = 'vedette'
        ORDER BY COALESCE(priorite, 0) DESC, COALESCE(created_at, '1970-01-01') DESC, id DESC
        LIMIT 10
    ");
    $biensVedette = $biensVedetteStmt ? $biensVedetteStmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Throwable $exception) {
    error_log('Catalogue stats error: ' . $exception->getMessage());
}

$whereParts = [
    "visible = 1",
    "statut_publication = 'valide'",
];
$params = [];

if ($villeSearch !== '') {
    $whereParts[] = 'ville LIKE :ville';
    $params[':ville'] = '%' . $villeSearch . '%';
}

if ($quartier !== '') {
    $whereParts[] = 'quartier LIKE :quartier';
    $params[':quartier'] = '%' . $quartier . '%';
}

if ($typeFilter !== '') {
    $typeVariants = biens_type_variants($typeFilter);
    $typePlaceholders = [];
    foreach ($typeVariants as $index => $variant) {
        $placeholder = ':type_' . $index;
        $params[$placeholder] = strtolower($variant);
        $typePlaceholders[] = $placeholder;
    }
    $whereParts[] = 'LOWER(TRIM(COALESCE(type, ""))) IN (' . implode(', ', $typePlaceholders) . ')';
}

if ($transactionFilter !== '') {
    $transactionVariants = biens_transaction_variants($transactionFilter);
    $transactionPlaceholders = [];
    foreach ($transactionVariants as $index => $variant) {
        $placeholder = ':transaction_' . $index;
        $params[$placeholder] = strtolower($variant);
        $transactionPlaceholders[] = $placeholder;
    }
    $whereParts[] = 'LOWER(TRIM(COALESCE(transaction, ""))) IN (' . implode(', ', $transactionPlaceholders) . ')';
}

$prixMin = biens_clean_int_money($prixMinInput);
if ($prixMin > 0) {
    $whereParts[] = 'prix >= :prix_min';
    $params[':prix_min'] = $prixMin;
}

$prixMax = biens_clean_int_money($prixMaxInput);
if ($prixMax > 0) {
    $whereParts[] = 'prix <= :prix_max';
    $params[':prix_max'] = $prixMax;
}

if ($chambresMin > 0) {
    $whereParts[] = 'chambres >= :chambres_min';
    $params[':chambres_min'] = $chambresMin;
}

if ($avecPiscine) {
    $whereParts[] = 'piscine = 1';
}

if ($avecJardin) {
    $whereParts[] = 'jardin = 1';
}

if ($miseFilter !== '') {
    if ($miseFilter === 'normal') {
        $whereParts[] = "COALESCE(NULLIF(LOWER(TRIM(niveau_visibilite)), ''), 'standard') NOT IN ('exclusif', 'vedette')";
    } else {
        $whereParts[] = "COALESCE(NULLIF(LOWER(TRIM(niveau_visibilite)), ''), 'standard') = :mise";
        $params[':mise'] = $miseFilter;
    }
}

$whereSql = ' WHERE ' . implode(' AND ', $whereParts);

$priorityOrder = "
    CASE COALESCE(NULLIF(LOWER(TRIM(niveau_visibilite)), ''), 'standard')
        WHEN 'exclusif' THEN 1
        WHEN 'vedette' THEN 2
        WHEN 'libre' THEN 3
        ELSE 4
    END
";

switch ($sort) {
    case 'price_asc':
        $orderBy = $priorityOrder . ", CASE WHEN prix IS NULL OR prix = 0 THEN 1 ELSE 0 END, prix ASC, COALESCE(created_at, '1970-01-01') DESC, id DESC";
        break;
    case 'price_desc':
        $orderBy = $priorityOrder . ", CASE WHEN prix IS NULL OR prix = 0 THEN 1 ELSE 0 END, prix DESC, COALESCE(created_at, '1970-01-01') DESC, id DESC";
        break;
    case 'date_asc':
        $orderBy = $priorityOrder . ", COALESCE(created_at, '1970-01-01') ASC, id ASC";
        break;
    case 'date_desc':
    default:
        $orderBy = $priorityOrder . ", COALESCE(created_at, '1970-01-01') DESC, id DESC";
        break;
}

try {
    $countStmt = $pdo->prepare('SELECT COUNT(*) FROM immo_biens' . $whereSql);
    $countStmt->execute($params);
    $total = (int) $countStmt->fetchColumn();
    $totalPages = max(1, (int) ceil($total / $perPage));
    if ($page > $totalPages) {
        $page = $totalPages;
    }
    $offset = ($page - 1) * $perPage;

    $sql = 'SELECT * FROM immo_biens' . $whereSql . ' ORDER BY ' . $orderBy . ' LIMIT :limit OFFSET :offset';
    $stmt = $pdo->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $biens = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $exception) {
    error_log('Catalogue listing error: ' . $exception->getMessage());
    $biens = [];
    $total = 0;
    $totalPages = 1;
    $page = 1;
    $offset = 0;
}

$catalogueStats['standard_total'] = max(
    0,
    $catalogueStats['total'] - $catalogueStats['exclusif_total'] - $catalogueStats['vedette_total'] - $catalogueStats['libre_total']
);

$showDiscoverySections = !$hasCatalogueFilters && $page === 1;
$resultStart = $total > 0 ? ($offset + 1) : 0;
$resultEnd = $total > 0 ? min($total, $offset + count($biens)) : 0;

$titleParts = [];
if ($typeFilter !== '') {
    $titleParts[] = biens_type_label($typeFilter);
}
if ($transactionFilter !== '') {
    $titleParts[] = biens_transaction_label($transactionFilter);
}
if ($villeSearch !== '') {
    $titleParts[] = $villeSearch;
}
if ($miseFilter !== '') {
    $titleParts[] = biens_visibility_label($miseFilter);
}

$pageTitle = !empty($titleParts)
    ? implode(' | ', $titleParts) . ' | IBIG IMMO TRUST'
    : 'Tous nos biens disponibles | IBIG IMMO TRUST';

$metaDescription = $hasCatalogueFilters
    ? 'Parcourez notre sélection de biens immobiliers filtrés selon vos critères : type, ville, budget, chambres et niveau de mise en avant.'
    : 'Découvrez tous les biens immobiliers disponibles chez IBIG IMMO TRUST : exclusivités, vedettes, ventes, locations et opportunités à fort potentiel.';
$metaRobots = $hasCatalogueFilters ? 'noindex,follow' : 'index,follow';
$canonicalUrl = biens_absolute_url('biens');
$ogImage = !empty($biensExclusif) ? biens_absolute_url(biens_image_path($biensExclusif[0])) : biens_absolute_url('/assets/img/default.jpg');

include __DIR__ . '/includes/header.php';

$activeFilters = [];
if ($miseFilter !== '') {
    $activeFilters[] = [
        'label' => 'Visibilité : ' . biens_visibility_label($miseFilter),
        'url' => biens_page_url(['mise' => null, 'page' => 1]),
    ];
}
if ($villeInput !== '') {
    $activeFilters[] = [
        'label' => 'Ville : ' . $villeInput,
        'url' => biens_page_url(['ville' => null, 'page' => 1]),
    ];
}
if ($quartier !== '') {
    $activeFilters[] = [
        'label' => 'Quartier : ' . $quartier,
        'url' => biens_page_url(['quartier' => null, 'page' => 1]),
    ];
}
if ($typeFilter !== '') {
    $activeFilters[] = [
        'label' => 'Type : ' . biens_type_label($typeFilter),
        'url' => biens_page_url(['type' => null, 'page' => 1]),
    ];
}
if ($transactionFilter !== '') {
    $activeFilters[] = [
        'label' => 'Transaction : ' . biens_transaction_label($transactionFilter),
        'url' => biens_page_url(['transaction' => null, 'page' => 1]),
    ];
}
if ($prixMin > 0 || $prixMax > 0) {
    $priceLabel = 'Budget : ';
    if ($prixMin > 0 && $prixMax > 0) {
        $priceLabel .= number_format($prixMin, 0, ',', ' ') . ' - ' . number_format($prixMax, 0, ',', ' ') . ' FCFA';
    } elseif ($prixMin > 0) {
        $priceLabel .= 'à partir de ' . number_format($prixMin, 0, ',', ' ') . ' FCFA';
    } else {
        $priceLabel .= 'jusqu’à ' . number_format($prixMax, 0, ',', ' ') . ' FCFA';
    }
    $activeFilters[] = [
        'label' => $priceLabel,
        'url' => biens_page_url(['prix_min' => null, 'prix_max' => null, 'min_price' => null, 'max_price' => null, 'page' => 1]),
    ];
}
if ($chambresMin > 0) {
    $activeFilters[] = [
        'label' => 'Chambres : ' . $chambresMin . '+',
        'url' => biens_page_url(['chambres' => null, 'page' => 1]),
    ];
}
if ($avecPiscine) {
    $activeFilters[] = [
        'label' => 'Avec piscine',
        'url' => biens_page_url(['avec_piscine' => null, 'page' => 1]),
    ];
}
if ($avecJardin) {
    $activeFilters[] = [
        'label' => 'Avec jardin',
        'url' => biens_page_url(['avec_jardin' => null, 'page' => 1]),
    ];
}

$sortOptions = [
    'date_desc' => 'Plus récents',
    'date_asc' => 'Plus anciens',
    'price_asc' => 'Prix croissant',
    'price_desc' => 'Prix décroissant',
];

$transactionOptions = [
    '' => 'Toutes',
    'vente' => 'À vendre',
    'location' => 'À louer',
];

$miseOptions = [
    '' => 'Toutes les formules',
    'exclusif' => 'Exclusif',
    'vedette' => 'Vedette',
    'libre' => 'Libre',
    'standard' => 'Standard',
    'normal' => 'Standard & Libre',
];

$paginationItems = biens_pagination_items($page, $totalPages);
?>

<style>
.biens-page {
  --biens-blue: #0A1628;
  --biens-blue-dark: #041c4b;
  --biens-red:#D4AF37;
  --biens-gold: #d4af37;
  --biens-green: #25d366;
  --biens-text: #0f172a;
  --biens-muted: #64748b;
  --biens-bg: #f4f7fb;
  --biens-card: #ffffff;
  --biens-line: rgba(15, 23, 42, 0.1);
  --biens-shadow: 0 20px 55px rgba(2, 12, 27, 0.12);
  --biens-shadow-soft: 0 12px 28px rgba(15, 23, 42, 0.08);
  --biens-radius: 24px;
  --biens-radius-sm: 16px;
  color: var(--biens-text);
}

.biens-page *,
.biens-page *::before,
.biens-page *::after {
  box-sizing: border-box;
}

.biens-shell {
  width: min(1280px, calc(100% - 32px));
  margin: 0 auto;
}

.biens-hero {
  position: relative;
  overflow: hidden;
  padding: 28px 0 18px;
}

.biens-hero::before,
.biens-hero::after {
  content: "";
  position: absolute;
  border-radius: 999px;
  pointer-events: none;
  filter: blur(12px);
}

.biens-hero::before {
  width: 420px;
  height: 420px;
  top: -180px;
  left: -80px;
  background: radial-gradient(circle at center, rgba(212, 175, 55, 0.22), rgba(212, 175, 55, 0));
}

.biens-hero::after {
  width: 520px;
  height: 520px;
  top: -120px;
  right: -180px;
  background: radial-gradient(circle at center, rgba(0, 60, 150, 0.22), rgba(0, 60, 150, 0));
}

.biens-hero__panel {
  position: relative;
  display: grid;
  grid-template-columns: minmax(0, 1.02fr) minmax(360px, 0.98fr);
  gap: 28px;
  padding: 36px;
  border-radius: 32px;
  background:
    linear-gradient(135deg, rgba(4, 28, 75, 0.98), rgba(0, 60, 150, 0.94)),
    linear-gradient(160deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0));
  box-shadow: var(--biens-shadow);
  color: #ffffff;
}

.biens-hero__panel--solo {
  grid-template-columns: minmax(0, 1fr);
}

.biens-hero__copy {
  position: relative;
  z-index: 1;
}

.biens-kicker {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 8px 14px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  background: rgba(255, 255, 255, 0.12);
  border: 1px solid rgba(255, 255, 255, 0.16);
}

.biens-hero__title {
  margin: 16px 0 12px;
  font-size: clamp(1.9rem, 3.1vw, 3.05rem);
  line-height: 1.08;
  font-weight: 760;
  letter-spacing: -0.025em;
  max-width: 15ch;
  color: #ffffff;
}

.biens-hero__text {
  max-width: 60ch;
  margin: 0;
  color: rgba(255, 255, 255, 0.82);
  font-size: 1rem;
  line-height: 1.75;
}

.biens-hero__stats {
  display: grid;
  grid-template-columns: repeat(4, minmax(0, 1fr));
  gap: 12px;
  margin-top: 22px;
}

.biens-stat {
  padding: 16px 16px 14px;
  border-radius: 18px;
  background: rgba(255, 255, 255, 0.09);
  border: 1px solid rgba(255, 255, 255, 0.12);
}

.biens-stat strong {
  display: block;
  font-size: 1.45rem;
  line-height: 1;
  font-weight: 760;
  margin-bottom: 6px;
}

.biens-stat span {
  display: block;
  color: rgba(255, 255, 255, 0.76);
  font-size: 0.88rem;
}

.biens-hero__actions {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  margin-top: 24px;
}

.biens-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  min-height: 48px;
  padding: 12px 18px;
  border: 1px solid transparent;
  border-radius: 14px;
  font-weight: 700;
  text-decoration: none;
  transition: transform 0.22s ease, box-shadow 0.22s ease, background 0.22s ease, color 0.22s ease;
}

.biens-button:hover {
  transform: translateY(-1px);
}

.biens-button--light {
  color: #081225;
  background: linear-gradient(135deg, #f8d978, #d4af37);
  box-shadow: 0 12px 22px rgba(212, 175, 55, 0.24);
}

.biens-button--ghost {
  color: #ffffff;
  border-color: rgba(255, 255, 255, 0.18);
  background: rgba(255, 255, 255, 0.1);
}

.biens-button--primary {
  background: var(--biens-blue);
  color: #ffffff;
}

.biens-button--secondary {
  background: #eef3ff;
  border-color: rgba(0, 60, 150, 0.12);
  color: var(--biens-blue-dark);
}

.biens-button--whatsapp {
  background: var(--biens-green);
  color: #ffffff;
}

.biens-chip-list {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-top: 22px;
}

.biens-chip,
.biens-chip-list a {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  min-height: 38px;
  padding: 8px 14px;
  border-radius: 999px;
  border: 1px solid rgba(255, 255, 255, 0.14);
  background: rgba(255, 255, 255, 0.08);
  color: #ffffff;
  text-decoration: none;
  font-size: 0.92rem;
  font-weight: 600;
}

.biens-chip-list a:hover {
  background: rgba(255, 255, 255, 0.16);
}

.biens-hero__media {
  min-width: 0;
}

.biens-hero-slider {
  position: relative;
  height: 100%;
  min-height: 460px;
  border-radius: 28px;
  overflow: hidden;
  background: rgba(0, 0, 0, 0.18);
  border: 1px solid rgba(255, 255, 255, 0.12);
}

.biens-hero-slider__track {
  display: flex;
  height: 100%;
  transition: transform 0.55s ease;
  will-change: transform;
}

.biens-hero-slide {
  min-width: 100%;
  position: relative;
  isolation: isolate;
}

.biens-hero-slide img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}

.biens-hero-slide::after {
  content: "";
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(6, 13, 25, 0.1) 0%, rgba(6, 13, 25, 0.68) 100%);
}

.biens-hero-slide__content {
  position: absolute;
  inset: auto 18px 18px 18px;
  z-index: 1;
  display: grid;
  gap: 10px;
  padding: 20px;
  border-radius: 22px;
  background: linear-gradient(180deg, rgba(4, 28, 75, 0.02), rgba(4, 28, 75, 0.88));
  backdrop-filter: blur(4px);
}

.biens-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 32px;
  padding: 6px 12px;
  border-radius: 999px;
  font-size: 0.72rem;
  line-height: 1;
  font-weight: 700;
}

.biens-badge--gold {
  color: #17120a;
  background: linear-gradient(135deg, #f3d573, #d4af37);
}

.biens-badge--blue {
  color: #ffffff;
  background: linear-gradient(135deg, #2563eb, #0A1628);
}

.biens-badge--green {
  color: #ffffff;
  background: linear-gradient(135deg, #16a34a, #15803d);
}

.biens-badge--dark {
  color: #ffffff;
  background: linear-gradient(135deg, #0f172a, #334155);
}

.biens-badge--orange {
  color: #ffffff;
  background: linear-gradient(135deg, #f59e0b, #ea580c);
}

.biens-hero-slide__title {
  margin: 0;
  font-size: clamp(1.28rem, 1.95vw, 1.72rem);
  line-height: 1.16;
  font-weight: 760;
  color: #ffffff;
}

.biens-hero-slide__meta,
.biens-hero-slide__excerpt {
  margin: 0;
  color: rgba(255, 255, 255, 0.84);
}

.biens-hero-slide__price {
  font-size: 1.3rem;
  font-weight: 780;
  color: #ffffff;
}

.biens-token-row {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.biens-token {
  display: inline-flex;
  align-items: center;
  min-height: 30px;
  padding: 6px 10px;
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.12);
  color: #ffffff;
  font-size: 0.8rem;
  font-weight: 600;
}

.biens-hero-slider__controls {
  position: absolute;
  inset: 16px 16px auto 16px;
  z-index: 2;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
}

.biens-slider-nav {
  display: flex;
  align-items: center;
  gap: 8px;
}

.biens-slider-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 42px;
  height: 42px;
  border: 1px solid rgba(255, 255, 255, 0.16);
  border-radius: 999px;
  background: rgba(4, 28, 75, 0.4);
  color: #ffffff;
  cursor: pointer;
  font-size: 1.05rem;
  transition: background 0.2s ease, transform 0.2s ease;
}

.biens-slider-button:hover {
  background: rgba(4, 28, 75, 0.6);
  transform: translateY(-1px);
}

.biens-hero-slider__counter {
  font-size: 0.9rem;
  font-weight: 700;
  color: #ffffff;
  min-width: 54px;
  text-align: right;
}

.biens-slider-dots {
  display: inline-flex;
  align-items: center;
  gap: 8px;
}

.biens-slider-dot {
  width: 10px;
  height: 10px;
  border: none;
  border-radius: 999px;
  padding: 0;
  background: rgba(255, 255, 255, 0.34);
  cursor: pointer;
}

.biens-slider-dot.is-active {
  background: #ffffff;
}

.biens-featured {
  padding: 16px 0 10px;
}

.biens-section-head {
  display: flex;
  align-items: end;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 16px;
  flex-wrap: wrap;
}

.biens-section-head h2 {
  margin: 0;
  font-size: clamp(1.55rem, 3vw, 2.2rem);
  line-height: 1.05;
  color: var(--biens-blue-dark);
}

.biens-section-head p {
  margin: 8px 0 0;
  color: var(--biens-muted);
}

.biens-section-head__actions {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
}

.biens-inline-link {
  display: inline-flex;
  align-items: center;
  min-height: 42px;
  padding: 10px 14px;
  border: 1px solid var(--biens-line);
  border-radius: 12px;
  background: #ffffff;
  color: var(--biens-blue-dark);
  text-decoration: none;
  font-weight: 700;
}

.biens-inline-link:hover {
  background: #f8fafc;
}

.biens-carousel {
  display: grid;
  grid-template-columns: auto minmax(0, 1fr) auto;
  gap: 12px;
  align-items: center;
}

.biens-carousel__viewport {
  display: flex;
  gap: 18px;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  scroll-behavior: smooth;
  padding: 4px;
  scrollbar-width: none;
}

.biens-carousel__viewport::-webkit-scrollbar {
  display: none;
}

.biens-feature-card {
  flex: 0 0 min(340px, 86vw);
  display: flex;
  flex-direction: column;
  scroll-snap-align: start;
  border-radius: 22px;
  overflow: hidden;
  background: var(--biens-card);
  box-shadow: var(--biens-shadow-soft);
  border: 1px solid rgba(15, 23, 42, 0.06);
}

.biens-feature-card__media {
  position: relative;
  display: block;
  aspect-ratio: 16 / 10;
  overflow: hidden;
}

.biens-feature-card__media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.42s ease;
}

.biens-feature-card:hover .biens-feature-card__media img,
.biens-card:hover .biens-card__media img {
  transform: scale(1.035);
}

.biens-feature-card__badge {
  position: absolute;
  top: 14px;
  left: 14px;
  z-index: 1;
}

.biens-feature-card__body {
  display: grid;
  gap: 10px;
  padding: 18px;
}

.biens-feature-card__eyebrow {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  color: var(--biens-muted);
  font-size: 0.84rem;
  font-weight: 600;
}

.biens-feature-card__title {
  margin: 0;
  font-size: 1.08rem;
  line-height: 1.34;
  font-weight: 720;
  color: var(--biens-blue-dark);
}

.biens-feature-card__title a {
  text-decoration: none;
  color: inherit;
}

.biens-feature-card__price {
  color: var(--biens-red);
  font-size: 1.1rem;
  font-weight: 780;
}

.biens-catalogue {
  padding: 14px 0 28px;
}

.biens-results-head {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 18px;
  flex-wrap: wrap;
  margin-bottom: 18px;
}

.biens-results-head h2 {
  margin: 0;
  font-size: clamp(1.7rem, 2.9vw, 2.4rem);
  line-height: 1.05;
  color: var(--biens-blue-dark);
}

.biens-results-head p {
  margin: 8px 0 0;
  color: var(--biens-muted);
}

.biens-sort-form {
  display: flex;
  align-items: center;
  gap: 12px;
}

.biens-sort-form label {
  font-weight: 600;
  color: var(--biens-blue-dark);
}

.biens-select,
.biens-input {
  width: 100%;
  min-height: 48px;
  padding: 12px 14px;
  border: 1px solid rgba(15, 23, 42, 0.12);
  border-radius: 14px;
  background: #ffffff;
  color: var(--biens-text);
  font-size: 0.96rem;
  transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
}

.biens-select:focus,
.biens-input:focus {
  outline: none;
  border-color: rgba(0, 60, 150, 0.42);
  box-shadow: 0 0 0 4px rgba(0, 60, 150, 0.12);
}

.biens-filters-card {
  padding: 22px;
  margin-bottom: 22px;
  border-radius: 26px;
  background:
    linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(255, 255, 255, 0.92)),
    linear-gradient(135deg, rgba(212, 175, 55, 0.08), rgba(0, 60, 150, 0.04));
  box-shadow: var(--biens-shadow-soft);
  border: 1px solid rgba(15, 23, 42, 0.06);
}

.biens-filters {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
  gap: 14px;
}

.biens-field {
  display: grid;
  gap: 8px;
}

.biens-field label {
  font-size: 0.9rem;
  font-weight: 700;
  color: var(--biens-blue-dark);
}

.biens-field--full {
  grid-column: 1 / -1;
}

.biens-field--actions {
  align-self: end;
}

.biens-checkbox-row {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.biens-checkbox {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  min-height: 46px;
  padding: 10px 14px;
  border-radius: 14px;
  background: #f8fafc;
  border: 1px solid rgba(15, 23, 42, 0.08);
  color: var(--biens-text);
  font-weight: 600;
}

.biens-checkbox input {
  width: 18px;
  height: 18px;
  margin: 0;
}

.biens-actions-row {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}

.biens-active-filters {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 18px;
}

.biens-filter-pill {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  min-height: 38px;
  padding: 8px 12px;
  border-radius: 999px;
  background: #ffffff;
  border: 1px solid rgba(0, 60, 150, 0.16);
  color: var(--biens-blue-dark);
  text-decoration: none;
  font-size: 0.9rem;
  font-weight: 600;
}

.biens-filter-pill strong {
  display: inline-block;
  width: 18px;
  text-align: center;
  color: var(--biens-red);
}

.biens-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
  gap: 22px;
}

.biens-card {
  display: flex;
  flex-direction: column;
  min-width: 0;
  background: var(--biens-card);
  border-radius: 24px;
  overflow: hidden;
  border: 1px solid rgba(15, 23, 42, 0.07);
  box-shadow: var(--biens-shadow-soft);
  transition: transform 0.24s ease, box-shadow 0.24s ease;
}

.biens-card:hover {
  transform: translateY(-4px);
  box-shadow: var(--biens-shadow);
}

.biens-card__media {
  position: relative;
  display: block;
  aspect-ratio: 16 / 11;
  overflow: hidden;
  background: #e2e8f0;
}

.biens-card__media img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform 0.4s ease;
}

.biens-card__badges {
  position: absolute;
  top: 14px;
  left: 14px;
  right: 14px;
  z-index: 1;
  display: flex;
  justify-content: space-between;
  gap: 10px;
  pointer-events: none;
}

.biens-card__body {
  display: grid;
  gap: 8px;
  padding: 16px 16px 12px;
}

.biens-card__eyebrow {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  color: var(--biens-muted);
  font-size: 0.83rem;
  font-weight: 600;
}

.biens-card__title {
  margin: 0;
  font-size: 1.06rem;
  line-height: 1.36;
  font-weight: 720;
  color: var(--biens-blue-dark);
}

.biens-card__title a {
  color: inherit;
  text-decoration: none;
}

.biens-card__price {
  font-size: 1.1rem;
  font-weight: 780;
  color: var(--biens-red);
}

.biens-card__price--soft {
  color: var(--biens-blue-dark);
}

.biens-card__location {
  color: var(--biens-muted);
  font-weight: 500;
  font-size: 0.92rem;
}

.biens-card__excerpt {
  margin: 0;
  color: #334155;
  font-size: 0.92rem;
  line-height: 1.62;
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.biens-card__tokens {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.biens-card__token {
  display: inline-flex;
  align-items: center;
  min-height: 30px;
  padding: 6px 10px;
  border-radius: 999px;
  background: #f8fafc;
  color: #334155;
  font-size: 0.78rem;
  font-weight: 600;
}

.biens-card__footer {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  padding: 0 16px 12px;
}

.biens-card__share {
  display: flex;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  padding: 12px 16px 16px;
  border-top: 1px dashed rgba(15, 23, 42, 0.12);
}

.biens-card__share-label {
  color: var(--biens-muted);
  font-size: 0.85rem;
  font-weight: 700;
}

.biens-share-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 36px;
  padding: 8px 12px;
  border: 1px solid rgba(15, 23, 42, 0.12);
  border-radius: 999px;
  background: #ffffff;
  color: var(--biens-blue-dark);
  cursor: pointer;
  font-weight: 600;
}

.biens-share-button:hover {
  background: #f8fafc;
}

.biens-empty {
  padding: 40px 24px;
  border-radius: 24px;
  background: #ffffff;
  border: 1px solid rgba(15, 23, 42, 0.08);
  box-shadow: var(--biens-shadow-soft);
  text-align: center;
}

.biens-empty h3 {
  margin: 0 0 10px;
  font-size: 1.45rem;
  color: var(--biens-blue-dark);
}

.biens-empty p {
  margin: 0 auto 18px;
  max-width: 56ch;
  color: var(--biens-muted);
  line-height: 1.8;
}

.biens-pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  flex-wrap: wrap;
  margin-top: 28px;
}

.biens-pagination a,
.biens-pagination span {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 44px;
  min-height: 44px;
  padding: 0 14px;
  border-radius: 12px;
  border: 1px solid rgba(15, 23, 42, 0.12);
  background: #ffffff;
  color: var(--biens-blue-dark);
  text-decoration: none;
  font-weight: 800;
}

.biens-pagination .is-current {
  background: var(--biens-blue);
  color: #ffffff;
  border-color: var(--biens-blue);
}

.biens-pagination .is-disabled {
  opacity: 0.55;
  pointer-events: none;
}

.biens-pagination .is-dots {
  border-style: dashed;
}

.biens-location {
  position: relative;
}

.biens-location-results {
  position: absolute;
  top: calc(100% + 8px);
  left: 0;
  right: 0;
  z-index: 20;
  display: none;
  padding: 8px;
  border-radius: 18px;
  background: #ffffff;
  border: 1px solid rgba(15, 23, 42, 0.08);
  box-shadow: 0 20px 36px rgba(15, 23, 42, 0.18);
}

.biens-location-results.is-visible {
  display: block;
}

.biens-location-result {
  display: grid;
  gap: 2px;
  width: 100%;
  padding: 12px 14px;
  border: none;
  border-radius: 14px;
  background: transparent;
  text-align: left;
  cursor: pointer;
}

.biens-location-result:hover,
.biens-location-result:focus {
  outline: none;
  background: #f8fafc;
}

.biens-location-result strong {
  color: var(--biens-blue-dark);
  font-size: 0.95rem;
}

.biens-location-result span {
  color: var(--biens-muted);
  font-size: 0.82rem;
}

.biens-toast {
  position: fixed;
  left: 50%;
  bottom: 24px;
  z-index: 9999;
  transform: translate(-50%, 18px);
  opacity: 0;
  pointer-events: none;
  padding: 12px 16px;
  border-radius: 999px;
  background: rgba(4, 28, 75, 0.96);
  color: #ffffff;
  font-weight: 700;
  box-shadow: 0 16px 30px rgba(15, 23, 42, 0.26);
  transition: opacity 0.25s ease, transform 0.25s ease;
}

.biens-toast.is-visible {
  opacity: 1;
  transform: translate(-50%, 0);
}

.biens-hidden {
  position: absolute !important;
  width: 1px !important;
  height: 1px !important;
  padding: 0 !important;
  margin: -1px !important;
  overflow: hidden !important;
  clip: rect(0, 0, 0, 0) !important;
  white-space: nowrap !important;
  border: 0 !important;
}

@media (max-width: 1120px) {
  .biens-hero__panel {
    grid-template-columns: minmax(0, 1fr);
  }

  .biens-hero__stats {
    grid-template-columns: repeat(2, minmax(0, 1fr));
  }

  .biens-hero-slider {
    min-height: 420px;
  }
}

@media (max-width: 860px) {
  .biens-shell {
    width: min(1280px, calc(100% - 24px));
  }

  .biens-hero__panel {
    padding: 24px;
    border-radius: 26px;
  }

  .biens-carousel {
    grid-template-columns: 1fr;
  }

  .biens-carousel .biens-slider-button {
    display: none;
  }

  .biens-results-head,
  .biens-sort-form {
    width: 100%;
  }

  .biens-sort-form {
    flex-wrap: wrap;
  }
}

@media (max-width: 640px) {
  .biens-hero {
    padding-top: 18px;
  }

  .biens-hero__stats {
    grid-template-columns: 1fr 1fr;
  }

  .biens-hero-slider {
    min-height: 370px;
  }

  .biens-hero-slide__content {
    inset: auto 12px 12px 12px;
    padding: 16px;
  }

  .biens-grid {
    grid-template-columns: 1fr;
  }

  .biens-card__footer,
  .biens-actions-row {
    flex-direction: column;
  }

  .biens-button {
    width: 100%;
  }

  .biens-card__share {
    align-items: stretch;
  }

  .biens-share-button {
    flex: 1 1 auto;
  }
}
</style>

<main class="biens-page">
  <section class="biens-hero">
    <div class="biens-shell">
      <div class="biens-hero__panel<?= $showDiscoverySections && !empty($biensExclusif) ? '' : ' biens-hero__panel--solo' ?>">
        <div class="biens-hero__copy">
          <span class="biens-kicker">Catalogue immobilier premium</span>
          <h1 class="biens-hero__title">Trouvez un bien solide, visible et prêt à performer.</h1>
          <p class="biens-hero__text">
            Explorez notre sélection de biens à vendre et à louer, avec une mise en avant claire entre exclusivités, vedettes et opportunités standards. La page s’adapte à vos critères, reste fluide sur mobile et vous mène rapidement vers le bon bien.
          </p>

          <div class="biens-hero__stats">
            <div class="biens-stat">
              <strong><?= number_format($catalogueStats['total'], 0, ',', ' ') ?></strong>
              <span>biens actifs</span>
            </div>
            <div class="biens-stat">
              <strong><?= number_format($catalogueStats['exclusif_total'], 0, ',', ' ') ?></strong>
              <span>exclusifs</span>
            </div>
            <div class="biens-stat">
              <strong><?= number_format($catalogueStats['vedette_total'], 0, ',', ' ') ?></strong>
              <span>vedettes</span>
            </div>
            <div class="biens-stat">
              <strong><?= number_format($catalogueStats['villes_total'], 0, ',', ' ') ?></strong>
              <span>villes couvertes</span>
            </div>
          </div>

          <div class="biens-hero__actions">
            <a class="biens-button biens-button--light" href="#catalogue">Explorer le catalogue</a>
            <a class="biens-button biens-button--ghost" href="https://wa.me/<?= e($waNumber) ?>?text=<?= rawurlencode('Bonjour IBIG IMMO TRUST, je souhaite être accompagné pour choisir un bien.') ?>" target="_blank" rel="noopener">Parler à un conseiller</a>
          </div>

          <div class="biens-chip-list">
            <a href="<?= e(biens_page_url(['mise' => 'exclusif', 'page' => 1])) ?>">Exclusifs (<?= (int) $catalogueStats['exclusif_total'] ?>)</a>
            <a href="<?= e(biens_page_url(['mise' => 'vedette', 'page' => 1])) ?>">Vedettes (<?= (int) $catalogueStats['vedette_total'] ?>)</a>
            <a href="<?= e(biens_page_url(['transaction' => 'vente', 'page' => 1])) ?>">À vendre (<?= (int) $catalogueStats['vente_total'] ?>)</a>
            <a href="<?= e(biens_page_url(['transaction' => 'location', 'page' => 1])) ?>">À louer (<?= (int) $catalogueStats['location_total'] ?>)</a>
          </div>
        </div>

        <?php if ($showDiscoverySections && !empty($biensExclusif)): ?>
          <div class="biens-hero__media">
            <div class="biens-hero-slider" data-hero-slider>
              <div class="biens-hero-slider__controls">
                <div class="biens-slider-nav">
                  <button type="button" class="biens-slider-button" data-hero-prev aria-label="Bien précédent">&lsaquo;</button>
                  <button type="button" class="biens-slider-button" data-hero-next aria-label="Bien suivant">&rsaquo;</button>
                </div>
                <div class="biens-slider-nav">
                  <div class="biens-slider-dots" data-hero-dots></div>
                  <div class="biens-hero-slider__counter" data-hero-counter></div>
                </div>
              </div>

              <div class="biens-hero-slider__track" data-hero-track>
                <?php foreach ($biensExclusif as $index => $bien): ?>
                  <?php
                  $detailPath = biens_detail_path($bien);
                  $absoluteDetail = biens_absolute_url($detailPath);
                  $heroMeta = biens_meta_tokens($bien);
                  ?>
                  <article class="biens-hero-slide">
                    <img
                      src="<?= e(biens_image_path($bien)) ?>"
                      alt="<?= e((string) ($bien['titre'] ?? 'Bien immobilier')) ?>"
                      <?= $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"' ?>
                      decoding="async"
                    >

                    <div class="biens-hero-slide__content">
                      <span class="biens-badge biens-badge--gold">Exclusivité IBIG</span>
                      <h2 class="biens-hero-slide__title"><?= e(biens_fix_text((string) ($bien['titre'] ?? 'Bien exclusif'))) ?></h2>
                      <p class="biens-hero-slide__meta"><?= e(biens_location_label($bien)) ?></p>
                      <div class="biens-hero-slide__price"><?= e(biens_price_label($bien['prix'] ?? 0)) ?></div>
                      <?php if (!empty($heroMeta)): ?>
                        <div class="biens-token-row">
                          <?php foreach ($heroMeta as $token): ?>
                            <span class="biens-token"><?= e($token) ?></span>
                          <?php endforeach; ?>
                        </div>
                      <?php endif; ?>
                      <div class="biens-hero__actions">
                        <a class="biens-button biens-button--light" href="<?= e($detailPath) ?>">Voir le bien</a>
                        <a class="biens-button biens-button--ghost" href="https://wa.me/<?= e($waNumber) ?>?text=<?= rawurlencode('Bonjour IBIG IMMO TRUST, je suis intéressé par ce bien : ' . ((string) ($bien['titre'] ?? '')) . ' - ' . $absoluteDetail) ?>" target="_blank" rel="noopener">Demander une visite</a>
                      </div>
                    </div>
                  </article>
                <?php endforeach; ?>
              </div>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <?php if ($showDiscoverySections && !empty($popularCities)): ?>
    <section class="biens-featured">
      <div class="biens-shell">
        <div class="biens-section-head">
          <div>
            <h2>Zones les plus demandées</h2>
            <p>Des raccourcis utiles pour démarrer votre recherche plus vite.</p>
          </div>
        </div>

        <div class="biens-chip-list" style="margin-top:0;">
          <?php foreach ($popularCities as $city): ?>
            <?php
            $cityName = biens_fix_text((string) ($city['ville_name'] ?? ''));
            if ($cityName === '') {
                continue;
            }
            ?>
            <a href="<?= e(biens_page_url(['ville' => $cityName, 'page' => 1])) ?>">
              <?= e($cityName) ?> (<?= (int) ($city['total'] ?? 0) ?>)
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <?php if ($showDiscoverySections && !empty($biensVedette)): ?>
    <section class="biens-featured">
      <div class="biens-shell">
        <div class="biens-section-head">
          <div>
            <h2>Biens en vedette</h2>
            <p>Une sélection marquée pour accélérer la prise de contact et la visibilité.</p>
          </div>
          <div class="biens-section-head__actions">
            <a class="biens-inline-link" href="<?= e(biens_page_url(['mise' => 'vedette', 'page' => 1])) ?>">Voir toutes les vedettes</a>
          </div>
        </div>

        <div class="biens-carousel">
          <button type="button" class="biens-slider-button" data-carousel-prev="featured" aria-label="Faire défiler vers la gauche">&lsaquo;</button>

          <div class="biens-carousel__viewport" data-carousel-viewport="featured">
            <?php foreach ($biensVedette as $bien): ?>
              <?php
              $detailPath = biens_detail_path($bien);
              $featureMeta = biens_meta_tokens($bien);
              ?>
              <article class="biens-feature-card">
                <a class="biens-feature-card__media" href="<?= e($detailPath) ?>">
                  <span class="biens-feature-card__badge biens-badge biens-badge--blue">Vedette</span>
                  <img src="<?= e(biens_image_path($bien)) ?>" alt="<?= e(biens_fix_text((string) ($bien['titre'] ?? 'Bien en vedette'))) ?>" loading="lazy" decoding="async">
                </a>
                <div class="biens-feature-card__body">
                  <div class="biens-feature-card__eyebrow">
                    <span><?= e(biens_type_label((string) ($bien['type'] ?? 'Bien'))) ?></span>
                    <span><?= e(biens_transaction_label((string) ($bien['transaction'] ?? ''))) ?></span>
                  </div>
                  <h3 class="biens-feature-card__title"><a href="<?= e($detailPath) ?>"><?= e(biens_fix_text((string) ($bien['titre'] ?? 'Bien en vedette'))) ?></a></h3>
                  <div class="biens-card__location"><?= e(biens_location_label($bien)) ?></div>
                  <div class="biens-feature-card__price"><?= e(biens_price_label($bien['prix'] ?? 0)) ?></div>
                  <?php if (!empty($featureMeta)): ?>
                    <div class="biens-card__tokens">
                      <?php foreach ($featureMeta as $token): ?>
                        <span class="biens-card__token"><?= e($token) ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                </div>
              </article>
            <?php endforeach; ?>
          </div>

          <button type="button" class="biens-slider-button" data-carousel-next="featured" aria-label="Faire défiler vers la droite">&rsaquo;</button>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <section class="biens-catalogue" id="catalogue">
    <div class="biens-shell">
      <div class="biens-results-head">
        <div>
          <h2>Catalogue complet</h2>
          <p>
            <?= number_format($total, 0, ',', ' ') ?> bien(s) trouvé(s)
            <?php if ($total > 0): ?>
              <span>- affichage <?= (int) $resultStart ?>-<?= (int) $resultEnd ?></span>
            <?php endif; ?>
          </p>
        </div>

        <form class="biens-sort-form" method="get" action="<?= e($pageBaseUrl) ?>">
          <?php if ($miseFilter !== ''): ?><input type="hidden" name="mise" value="<?= e($miseFilter) ?>"><?php endif; ?>
          <?php if ($villeInput !== ''): ?><input type="hidden" name="ville" value="<?= e($villeInput) ?>"><?php endif; ?>
          <?php if ($quartier !== ''): ?><input type="hidden" name="quartier" value="<?= e($quartier) ?>"><?php endif; ?>
          <?php if ($typeFilter !== ''): ?><input type="hidden" name="type" value="<?= e($typeFilter) ?>"><?php endif; ?>
          <?php if ($transactionFilter !== ''): ?><input type="hidden" name="transaction" value="<?= e($transactionFilter) ?>"><?php endif; ?>
          <?php if ($prixMinInput !== ''): ?><input type="hidden" name="prix_min" value="<?= e($prixMinInput) ?>"><?php endif; ?>
          <?php if ($prixMaxInput !== ''): ?><input type="hidden" name="prix_max" value="<?= e($prixMaxInput) ?>"><?php endif; ?>
          <?php if ($chambresMin > 0): ?><input type="hidden" name="chambres" value="<?= (int) $chambresMin ?>"><?php endif; ?>
          <?php if ($avecPiscine): ?><input type="hidden" name="avec_piscine" value="1"><?php endif; ?>
          <?php if ($avecJardin): ?><input type="hidden" name="avec_jardin" value="1"><?php endif; ?>

          <label for="catalogue-sort">Trier</label>
          <select id="catalogue-sort" name="sort" class="biens-select" onchange="this.form.submit()">
            <?php foreach ($sortOptions as $sortValue => $sortLabel): ?>
              <option value="<?= e($sortValue) ?>" <?= $sort === $sortValue ? 'selected' : '' ?>><?= e($sortLabel) ?></option>
            <?php endforeach; ?>
          </select>
        </form>
      </div>

      <?php if (!empty($activeFilters)): ?>
        <div class="biens-active-filters" aria-label="Filtres actifs">
          <?php foreach ($activeFilters as $activeFilter): ?>
            <a class="biens-filter-pill" href="<?= e($activeFilter['url']) ?>">
              <?= e($activeFilter['label']) ?>
              <strong>&times;</strong>
            </a>
          <?php endforeach; ?>
          <a class="biens-filter-pill" href="<?= e($pageBaseUrl) ?>">
            Tout réinitialiser
            <strong>&times;</strong>
          </a>
        </div>
      <?php endif; ?>

      <div class="biens-filters-card">
        <form class="biens-filters" method="get" action="<?= e($pageBaseUrl) ?>">
          <div class="biens-field">
            <label for="mise">Formule</label>
            <select id="mise" name="mise" class="biens-select">
              <?php foreach ($miseOptions as $miseValue => $miseLabel): ?>
                <option value="<?= e($miseValue) ?>" <?= $miseFilter === $miseValue ? 'selected' : '' ?>><?= e($miseLabel) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="biens-field biens-location">
            <label for="location-input">Ville</label>
            <input
              type="text"
              id="location-input"
              name="ville"
              class="biens-input"
              placeholder="Abidjan, Yamoussoukro, Bouaké..."
              value="<?= e($villeInput) ?>"
              autocomplete="off"
              data-location-input
            >
            <div class="biens-location-results" data-location-results></div>
          </div>

          <div class="biens-field">
            <label for="quartier">Quartier</label>
            <input type="text" id="quartier" name="quartier" class="biens-input" placeholder="Cocody, Riviera, Marcory..." value="<?= e($quartier) ?>">
          </div>

          <div class="biens-field">
            <label for="type">Type de bien</label>
            <select id="type" name="type" class="biens-select">
              <option value="">Tous</option>
              <?php foreach ($typeOptions as $typeValue => $typeLabel): ?>
                <option value="<?= e($typeValue) ?>" <?= $typeFilter === $typeValue ? 'selected' : '' ?>><?= e($typeLabel) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="biens-field">
            <label for="transaction">Transaction</label>
            <select id="transaction" name="transaction" class="biens-select">
              <?php foreach ($transactionOptions as $transactionValue => $transactionLabel): ?>
                <option value="<?= e($transactionValue) ?>" <?= $transactionFilter === $transactionValue ? 'selected' : '' ?>><?= e($transactionLabel) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="biens-field">
            <label for="chambres">Chambres min.</label>
            <select id="chambres" name="chambres" class="biens-select">
              <option value="">Toutes</option>
              <?php for ($i = 1; $i <= 6; $i++): ?>
                <option value="<?= $i ?>" <?= $chambresMin === $i ? 'selected' : '' ?>><?= $i ?>+</option>
              <?php endfor; ?>
            </select>
          </div>

          <div class="biens-field">
            <label for="prix_min">Budget min.</label>
            <input type="text" id="prix_min" name="prix_min" class="biens-input" placeholder="Ex : 15 000 000" value="<?= e($prixMinInput) ?>" inputmode="numeric" data-price-input>
          </div>

          <div class="biens-field">
            <label for="prix_max">Budget max.</label>
            <input type="text" id="prix_max" name="prix_max" class="biens-input" placeholder="Ex : 150 000 000" value="<?= e($prixMaxInput) ?>" inputmode="numeric" data-price-input>
          </div>

          <div class="biens-field biens-field--full">
            <label>Préférences</label>
            <div class="biens-checkbox-row">
              <label class="biens-checkbox">
                <input type="checkbox" name="avec_piscine" value="1" <?= $avecPiscine ? 'checked' : '' ?>>
                Avec piscine
              </label>
              <label class="biens-checkbox">
                <input type="checkbox" name="avec_jardin" value="1" <?= $avecJardin ? 'checked' : '' ?>>
                Avec jardin
              </label>
            </div>
          </div>

          <div class="biens-field biens-field--full biens-field--actions">
            <div class="biens-actions-row">
              <button type="submit" class="biens-button biens-button--primary">Lancer la recherche</button>
              <a class="biens-button biens-button--secondary" href="<?= e($pageBaseUrl) ?>">Réinitialiser</a>
            </div>
          </div>
        </form>
      </div>

      <?php if (empty($biens)): ?>
        <div class="biens-empty">
          <h3>Aucun bien ne correspond à vos critères pour le moment.</h3>
          <p>Vous pouvez élargir la ville, desserrer le budget ou retirer certains filtres. Si vous préférez, notre équipe peut aussi vous proposer une short-list adaptée en direct.</p>
          <div class="biens-actions-row" style="justify-content:center;">
            <a class="biens-button biens-button--secondary" href="<?= e($pageBaseUrl) ?>">Voir tout le catalogue</a>
            <a class="biens-button biens-button--whatsapp" href="https://wa.me/<?= e($waNumber) ?>?text=<?= rawurlencode('Bonjour IBIG IMMO TRUST, je souhaite recevoir une sélection de biens adaptée à mes critères.') ?>" target="_blank" rel="noopener">Demander une sélection</a>
          </div>
        </div>
      <?php else: ?>
        <div class="biens-grid">
          <?php foreach ($biens as $bien): ?>
            <?php
            $detailPath = biens_detail_path($bien);
            $detailUrlAbsolute = biens_absolute_url($detailPath);
            $imagePath = biens_image_path($bien);
            $locationLabel = biens_location_label($bien);
            $metaTokens = biens_meta_tokens($bien);
            $visibilityValue = biens_visibility_value($bien);
            $leftBadge = null;
            $rightBadge = null;

            if ($visibilityValue === 'exclusif') {
                $leftBadge = ['class' => 'biens-badge biens-badge--gold', 'label' => 'Exclusivité'];
            } elseif ($visibilityValue === 'vedette') {
                $leftBadge = ['class' => 'biens-badge biens-badge--blue', 'label' => 'Vedette'];
            } elseif ($visibilityValue === 'libre') {
                $leftBadge = ['class' => 'biens-badge biens-badge--green', 'label' => 'Libre'];
            }

            if (biens_is_new($bien['created_at'] ?? null)) {
                $rightBadge = ['class' => 'biens-badge biens-badge--orange', 'label' => 'Nouveau'];
            } elseif (!empty($bien['badge'])) {
                $rightBadge = ['class' => 'biens-badge biens-badge--dark', 'label' => biens_fix_text(trim(strip_tags((string) $bien['badge'])))];
            }

            $waMessage = rawurlencode(
                'Bonjour IBIG IMMO TRUST, je suis intéressé par ce bien : ' .
                ((string) ($bien['titre'] ?? '')) .
                ' - ' .
                $detailUrlAbsolute
            );

              $shareTitle = biens_fix_text((string) ($bien['titre'] ?? 'Bien immobilier'));
            $shareText = trim($shareTitle . ' | ' . biens_price_label($bien['prix'] ?? 0) . ' | ' . $locationLabel);
            ?>
            <article class="biens-card">
              <a class="biens-card__media" href="<?= e($detailPath) ?>">
                <?php if ($leftBadge || $rightBadge): ?>
                  <div class="biens-card__badges">
                    <div><?= $leftBadge ? '<span class="' . e($leftBadge['class']) . '">' . e($leftBadge['label']) . '</span>' : '' ?></div>
                    <div><?= $rightBadge ? '<span class="' . e($rightBadge['class']) . '">' . e($rightBadge['label']) . '</span>' : '' ?></div>
                  </div>
                <?php endif; ?>

                <img src="<?= e($imagePath) ?>" alt="<?= e($shareTitle) ?>" loading="lazy" decoding="async">
              </a>

              <div class="biens-card__body">
                <div class="biens-card__eyebrow">
                  <span><?= e(biens_type_label((string) ($bien['type'] ?? 'Bien'))) ?></span>
                  <span><?= e(biens_transaction_label((string) ($bien['transaction'] ?? ''))) ?></span>
                </div>

                <h3 class="biens-card__title">
                  <a href="<?= e($detailPath) ?>"><?= e($shareTitle) ?></a>
                </h3>

                <?php $priceLabel = biens_price_label($bien['prix'] ?? 0); ?>
                <div class="biens-card__price<?= ($priceLabel === 'Prix sur demande') ? ' biens-card__price--soft' : '' ?>">
                  <?= e($priceLabel) ?>
                </div>

                <div class="biens-card__location"><?= e($locationLabel) ?></div>

                <?php if (!empty($metaTokens)): ?>
                  <div class="biens-card__tokens">
                    <?php foreach ($metaTokens as $token): ?>
                      <span class="biens-card__token"><?= e($token) ?></span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>
              </div>

              <div class="biens-card__footer">
                <a class="biens-button biens-button--whatsapp" href="https://wa.me/<?= e($waNumber) ?>?text=<?= $waMessage ?>" target="_blank" rel="noopener">WhatsApp</a>
                <a class="biens-button biens-button--secondary" href="<?= e($detailPath) ?>">Voir le bien</a>
              </div>

              <div
                class="biens-card__share"
                data-share-url="<?= e($detailUrlAbsolute) ?>"
                data-share-title="<?= e($shareTitle) ?>"
                data-share-text="<?= e($shareText) ?>"
              >
                <span class="biens-card__share-label">Partager :</span>
                <button type="button" class="biens-share-button" data-share-action="native">Partager</button>
                <button type="button" class="biens-share-button" data-share-action="copy">Copier le lien</button>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if ($totalPages > 1): ?>
        <nav class="biens-pagination" aria-label="Pagination du catalogue">
          <?php if ($page > 1): ?>
            <a href="<?= e(biens_page_url(['page' => $page - 1])) ?>" aria-label="Page precedente">&lsaquo;</a>
          <?php else: ?>
            <span class="is-disabled">&lsaquo;</span>
          <?php endif; ?>

          <?php foreach ($paginationItems as $item): ?>
            <?php if ($item === '...'): ?>
              <span class="is-dots">â€¦</span>
            <?php elseif ((int) $item === $page): ?>
              <span class="is-current"><?= (int) $item ?></span>
            <?php else: ?>
              <a href="<?= e(biens_page_url(['page' => (int) $item])) ?>"><?= (int) $item ?></a>
            <?php endif; ?>
          <?php endforeach; ?>

          <?php if ($page < $totalPages): ?>
            <a href="<?= e(biens_page_url(['page' => $page + 1])) ?>" aria-label="Page suivante">&rsaquo;</a>
          <?php else: ?>
            <span class="is-disabled">&rsaquo;</span>
          <?php endif; ?>
        </nav>
      <?php endif; ?>
    </div>
  </section>

  <div class="biens-toast" id="biens-toast" aria-live="polite"></div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
  setupHeroSlider();
  setupFeaturedCarousel();
  setupShareActions();
  setupLocationAutocomplete();
  setupPriceFormatting();
});

function setupHeroSlider() {
  const slider = document.querySelector('[data-hero-slider]');
  if (!slider) {
    return;
  }

  const track = slider.querySelector('[data-hero-track]');
  const slides = Array.from(slider.querySelectorAll('.biens-hero-slide'));
  const dotsBox = slider.querySelector('[data-hero-dots]');
  const counter = slider.querySelector('[data-hero-counter]');
  const prev = slider.querySelector('[data-hero-prev]');
  const next = slider.querySelector('[data-hero-next]');

  if (!track || slides.length <= 1) {
    if (counter && slides.length === 1) {
      counter.textContent = '1 / 1';
    }
    return;
  }

  let index = 0;
  let timer = null;
  let touchStartX = 0;
  let touchEndX = 0;

  slides.forEach(function (_, slideIndex) {
    const dot = document.createElement('button');
    dot.type = 'button';
    dot.className = 'biens-slider-dot' + (slideIndex === 0 ? ' is-active' : '');
    dot.setAttribute('aria-label', 'Aller au bien ' + (slideIndex + 1));
    dot.addEventListener('click', function () {
      goTo(slideIndex);
    });
    if (dotsBox) {
      dotsBox.appendChild(dot);
    }
  });

  const dots = dotsBox ? Array.from(dotsBox.querySelectorAll('.biens-slider-dot')) : [];

  function render() {
    track.style.transform = 'translateX(-' + (index * 100) + '%)';
    if (counter) {
      counter.textContent = (index + 1) + ' / ' + slides.length;
    }
    dots.forEach(function (dot, dotIndex) {
      dot.classList.toggle('is-active', dotIndex === index);
    });
  }

  function goTo(nextIndex) {
    index = (nextIndex + slides.length) % slides.length;
    render();
  }

  function startAutoPlay() {
    stopAutoPlay();
    timer = window.setInterval(function () {
      goTo(index + 1);
    }, 5200);
  }

  function stopAutoPlay() {
    if (timer) {
      window.clearInterval(timer);
      timer = null;
    }
  }

  if (prev) {
    prev.addEventListener('click', function () {
      goTo(index - 1);
    });
  }

  if (next) {
    next.addEventListener('click', function () {
      goTo(index + 1);
    });
  }

  slider.addEventListener('mouseenter', stopAutoPlay);
  slider.addEventListener('mouseleave', startAutoPlay);
  slider.addEventListener('touchstart', function (event) {
    touchStartX = event.changedTouches[0].clientX;
    stopAutoPlay();
  }, { passive: true });
  slider.addEventListener('touchend', function (event) {
    touchEndX = event.changedTouches[0].clientX;
    if (Math.abs(touchEndX - touchStartX) > 40) {
      if (touchEndX < touchStartX) {
        goTo(index + 1);
      } else {
        goTo(index - 1);
      }
    }
    startAutoPlay();
  }, { passive: true });

  document.addEventListener('visibilitychange', function () {
    if (document.hidden) {
      stopAutoPlay();
    } else {
      startAutoPlay();
    }
  });

  render();
  startAutoPlay();
}

function setupFeaturedCarousel() {
  const viewport = document.querySelector('[data-carousel-viewport="featured"]');
  if (!viewport) {
    return;
  }

  const prev = document.querySelector('[data-carousel-prev="featured"]');
  const next = document.querySelector('[data-carousel-next="featured"]');

  function scrollByCard(direction) {
    const firstCard = viewport.querySelector('.biens-feature-card');
    if (!firstCard) {
      return;
    }

    const gap = 18;
    const width = firstCard.getBoundingClientRect().width + gap;
    viewport.scrollBy({
      left: width * direction,
      behavior: 'smooth'
    });
  }

  if (prev) {
    prev.addEventListener('click', function () {
      scrollByCard(-1);
    });
  }

  if (next) {
    next.addEventListener('click', function () {
      scrollByCard(1);
    });
  }
}

function setupShareActions() {
  document.addEventListener('click', async function (event) {
    const button = event.target.closest('[data-share-action]');
    if (!button) {
      return;
    }

    const box = button.closest('[data-share-url]');
    if (!box) {
      return;
    }

    const shareUrl = box.getAttribute('data-share-url') || window.location.href;
    const shareTitle = box.getAttribute('data-share-title') || document.title;
    const shareText = box.getAttribute('data-share-text') || shareTitle;
    const action = button.getAttribute('data-share-action');

    if (action === 'native') {
      if (navigator.share) {
        try {
          await navigator.share({
            title: shareTitle,
            text: shareText,
            url: shareUrl
          });
          return;
        } catch (error) {
          if (error && error.name === 'AbortError') {
            return;
          }
        }
      }

      await copyText(shareUrl);
      showBiensToast('Lien copie. Vous pouvez maintenant le partager.');
      return;
    }

    if (action === 'copy') {
      await copyText(shareUrl);
      showBiensToast('Lien copie avec succes.');
    }
  });
}

function setupLocationAutocomplete() {
  const input = document.querySelector('[data-location-input]');
  const resultsBox = document.querySelector('[data-location-results]');

  if (!input || !resultsBox) {
    return;
  }

  let debounceTimer = null;
  let currentRequest = 0;

  function clearResults() {
    resultsBox.innerHTML = '';
    resultsBox.classList.remove('is-visible');
  }

  function renderResults(items) {
    if (!Array.isArray(items) || items.length === 0) {
      clearResults();
      return;
    }

    resultsBox.innerHTML = '';

    items.forEach(function (item) {
      const button = document.createElement('button');
      button.type = 'button';
      button.className = 'biens-location-result';
      button.innerHTML =
        '<strong>' + escapeHtml(item.value || item.label || '') + '</strong>' +
        '<span>' + escapeHtml(item.label || '') + '</span>';
      button.addEventListener('click', function () {
        input.value = item.value || item.label || '';
        clearResults();
      });
      resultsBox.appendChild(button);
    });

    resultsBox.classList.add('is-visible');
  }

  input.addEventListener('input', function () {
    const query = input.value.trim();
    window.clearTimeout(debounceTimer);

    if (query.length < 2) {
      clearResults();
      return;
    }

    debounceTimer = window.setTimeout(async function () {
      currentRequest += 1;
      const requestId = currentRequest;

      try {
        const response = await fetch('/api/search-location.php?q=' + encodeURIComponent(query), {
          headers: { 'Accept': 'application/json' }
        });

        if (!response.ok) {
          clearResults();
          return;
        }

        const data = await response.json();
        if (requestId !== currentRequest) {
          return;
        }

        renderResults(data);
      } catch (error) {
        clearResults();
      }
    }, 220);
  });

  document.addEventListener('click', function (event) {
    if (!event.target.closest('.biens-location')) {
      clearResults();
    }
  });

  input.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      clearResults();
    }
  });
}

function setupPriceFormatting() {
  const inputs = document.querySelectorAll('[data-price-input]');
  if (!inputs.length) {
    return;
  }

  function formatValue(input) {
    const digits = (input.value || '').replace(/\D+/g, '');
    if (!digits) {
      input.value = '';
      return;
    }

    input.value = Number(digits).toLocaleString('fr-FR');
  }

  inputs.forEach(function (input) {
    formatValue(input);

    input.addEventListener('focus', function () {
      input.value = (input.value || '').replace(/\s+/g, '');
    });

    input.addEventListener('blur', function () {
      formatValue(input);
    });
  });
}

function showBiensToast(message) {
  const toast = document.getElementById('biens-toast');
  if (!toast) {
    return;
  }

  toast.textContent = message;
  toast.classList.add('is-visible');
  window.clearTimeout(showBiensToast._timer);
  showBiensToast._timer = window.setTimeout(function () {
    toast.classList.remove('is-visible');
  }, 2200);
}

async function copyText(text) {
  if (navigator.clipboard && navigator.clipboard.writeText) {
    await navigator.clipboard.writeText(text);
    return;
  }

  const textarea = document.createElement('textarea');
  textarea.value = text;
  textarea.setAttribute('readonly', '');
  textarea.style.position = 'absolute';
  textarea.style.left = '-9999px';
  document.body.appendChild(textarea);
  textarea.select();
  document.execCommand('copy');
  document.body.removeChild(textarea);
}

function escapeHtml(value) {
  return String(value)
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>

