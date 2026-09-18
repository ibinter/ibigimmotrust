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

/* ======================================================
   RECHERCHE + FILTRES + TRI + PAGINATION
====================================================== */

// Recherche
$search = trim($_GET['search'] ?? '');

// Filtres
$type     = trim($_GET['type'] ?? '');
$ville    = trim($_GET['ville'] ?? '');
$statut   = trim($_GET['statut'] ?? '');
$budgetMin = trim($_GET['budget_min'] ?? '');
$budgetMax = trim($_GET['budget_max'] ?? '');

// Tri
$orderBy = $_GET['sort'] ?? 'id';
$allowedSort = ['id','nom','created_at','budget'];
if (!in_array($orderBy, $allowedSort)) $orderBy = 'id';

$orderDir = ($_GET['dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';

// Pagination
$limitOptions = [10,20,50,100];
$limit = (int)($_GET['limit'] ?? 10);
if (!in_array($limit, $limitOptions)) $limit = 10;

$page = (int)($_GET['page'] ?? 1);
if ($page < 1) $page = 1;

/* ======================================================
   CONSTRUCTION DU WHERE DYNAMIQUE
====================================================== */
$where = "WHERE 1";
$params = [];

if ($search !== "") {
    $where .= " AND (nom LIKE :s OR telephone LIKE :s OR type_demande LIKE :s OR ville LIKE :s)";
    $params[':s'] = "%$search%";
}
if ($type !== "") {
    $where .= " AND type_demande = :type";
    $params[':type'] = $type;
}
if ($ville !== "") {
    $where .= " AND ville LIKE :ville";
    $params[':ville'] = "%$ville%";
}
if ($statut !== "") {
    $where .= " AND statut = :statut";
    $params[':statut'] = $statut;
}
if ($budgetMin !== "") {
    $where .= " AND budget >= :bmin";
    $params[':bmin'] = $budgetMin;
}
if ($budgetMax !== "") {
    $where .= " AND budget <= :bmax";
    $params[':bmax'] = $budgetMax;
}

/* ======================================================
   PAGINATION SQL
====================================================== */
$offset = ($page - 1) * $limit;

$total = $pdo->prepare("SELECT COUNT(*) FROM immo_leads $where");
$total->execute($params);
$totalRows = $total->fetchColumn();

$totalPages = max(1, ceil($totalRows / $limit));

/* ======================================================
   REQUÊTE PRINCIPALE
====================================================== */
$sql = "
    SELECT id, nom, telephone, type_demande, budget, ville, statut, created_at
    FROM immo_leads
    $where
    ORDER BY $orderBy $orderDir
    LIMIT $limit OFFSET $offset
";
$st = $pdo->prepare($sql);
$st->execute($params);
$demandes = $st->fetchAll(PDO::FETCH_ASSOC);

/* ======================================================
   LISTE DES VILLES & TYPES POUR FILTRES
====================================================== */
$listeVilles = $pdo->query("SELECT DISTINCT ville FROM immo_leads WHERE ville <> '' ORDER BY ville")->fetchAll(PDO::FETCH_COLUMN);
$listeTypes  = $pdo->query("SELECT DISTINCT type_demande FROM immo_leads WHERE type_demande <> '' ORDER BY type_demande")->fetchAll(PDO::FETCH_COLUMN);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Demandes Clients – IBIG IMMO TRUST</title>

<?php include __DIR__ . '/menu.php'; ?>

<style>
body { font-family: system-ui; background:#f4f6fb; padding:20px; }
h2 { color:#003c96; }

/* BOÎTE DES FILTRES */
.filters-box {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-end;
    gap: 10px;
    background: #fff;
    padding: 15px;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    margin-bottom: 18px;
}

/* Inputs & selects de la zone de filtres UNIQUEMENT */
.filters-box input,
.filters-box select {
    padding: 8px;
    border: 1px solid #bbb;
    border-radius: 6px;
    width: auto;              /* annule le 100% global */
    min-width: 140px;         /* largeur mini élégante */
    display: inline-block;    /* force l’horizontal */
    box-sizing: border-box;
}

/* Boutons dans la barre de filtres */
.filters-box .btn-blue,
.filters-box .btn-red {
    padding: 8px 14px;
    white-space: nowrap;
}

/* Sur mobile : on repasse en colonne pour respirer */
@media (max-width: 768px) {
    .filters-box {
        flex-direction: column;
        align-items: flex-start;
    }

    .filters-box input,
    .filters-box select,
    .filters-box .btn-blue,
    .filters-box .btn-red,
    .filters-box a.btn-blue,
    .filters-box a.btn-red {
        width: 100%;
    }
}

.btn-blue {
    background:#003c96; color:white; border:none;
    padding:8px 18px; border-radius:6px; cursor:pointer;
}
.btn-red {
    background:#e30613; color:white; border:none;
    padding:8px 18px; border-radius:6px; cursor:pointer;
}

table {
    width:100%; border-collapse:collapse; background:white;
    overflow:hidden; border-radius:10px;
    box-shadow:0 2px 6px rgba(0,0,0,0.07);
}
th, td {
    padding:12px; border-bottom:1px solid #eee;
}
th { background:#003c96; color:white; cursor:pointer; }

.badge {
    padding:4px 10px; border-radius:12px; font-size:12px; color:white;
}
.new { background:#10b981; }
.progress { background:#f59e0b; }
.done { background:#6b7280; }

.action-btns a {
    padding:6px 10px; background:#003c96; color:white;
    border-radius:6px; text-decoration:none; font-size:12px;
}
.whatsapp {
    background:#25D366 !important;
}
.call {
    background:#111 !important;
}

.pagination {
    margin-top:15px; display:flex; gap:6px;
}
.pagination a {
    padding:6px 12px; background:white; border-radius:6px;
    text-decoration:none; border:1px solid #ddd;
}
.pagination .active {
    background:#003c96; color:white; border:none;
}
</style>
</head>
<body>

<h2> Demandes Clients IMMO</h2>

<form method="get" class="filters-box">

    <input type="text" name="search" placeholder="Recherche..."
           value="<?= htmlspecialchars($search) ?>">

    <select name="type">
        <option value="">Type demande</option>
        <?php foreach($listeTypes as $t): ?>
            <option <?= $t==$type ? "selected":"" ?>><?= $t ?></option>
        <?php endforeach; ?>
    </select>

    <select name="ville">
        <option value="">Ville</option>
        <?php foreach($listeVilles as $v): ?>
            <option <?= $v==$ville ? "selected":"" ?>><?= $v ?></option>
        <?php endforeach; ?>
    </select>

    <select name="statut">
        <option value="">Statut</option>
        <option <?= $statut=="Nouveau"?"selected":"" ?>>Nouveau</option>
        <option <?= $statut=="En cours"?"selected":"" ?>>En cours</option>
        <option <?= $statut=="Traité"?"selected":"" ?>>Traité</option>
    </select>

    <input type="number" name="budget_min" placeholder="Budget min"
           value="<?= htmlspecialchars($budgetMin) ?>">

    <input type="number" name="budget_max" placeholder="Budget max"
           value="<?= htmlspecialchars($budgetMax) ?>">

    <select name="limit">
        <?php foreach($limitOptions as $l): ?>
            <option value="<?= $l ?>" <?= $l==$limit?"selected":"" ?>><?= $l ?>/page</option>
        <?php endforeach; ?>
    </select>

    <button class="btn-blue">Filtrer</button>

    <a href="export_excel.php" class="btn-blue"> Excel</a>
    <a href="export_pdf.php" class="btn-red"> PDF</a>

</form>


<table>
<tr>
    <th onclick="sort('id')">ID</th>
    <th onclick="sort('nom')">Nom</th>
    <th>Téléphone</th>
    <th onclick="sort('type_demande')">Type</th>
    <th onclick="sort('budget')">Budget</th>
    <th onclick="sort('ville')">Ville</th>
    <th onclick="sort('created_at')">Date</th>
    <th>Statut</th>
    <th>Actions</th>
</tr>

<?php foreach($demandes as $d): ?>
<tr>
    <td><?= $d['id'] ?></td>
    <td><?= htmlspecialchars($d['nom']) ?></td>
    <td><?= htmlspecialchars($d['telephone']) ?></td>
    <td><?= htmlspecialchars($d['type_demande']) ?></td>
    <td><?= htmlspecialchars($d['budget']) ?></td>
    <td><?= htmlspecialchars($d['ville']) ?></td>
    <td><?= $d['created_at'] ?></td>
    <td>
        <?php
        if ($d['statut']=="Traité") echo "<span class='badge done'>Traité</span>";
        elseif ($d['statut']=="En cours") echo "<span class='badge progress'>En cours</span>";
        else echo "<span class='badge new'>Nouveau</span>";
        ?>
    </td>
    <td class="action-btns">
        <a href="demande_view.php?id=<?= $d['id'] ?>"> Voir</a>
        <a href="https://wa.me/225<?= $d['telephone'] ?>" class="whatsapp" target="_blank">WhatsApp</a>
        <a href="tel:<?= $d['telephone'] ?>" class="call"> Appel</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<!-- PAGINATION -->
<div class="pagination">
<?php for($i=1; $i<=$totalPages; $i++): ?>
    <a href="?page=<?= $i ?>&<?= http_build_query($_GET) ?>" 
       class="<?= $i==$page?'active':'' ?>"><?= $i ?></a>
<?php endfor; ?>
</div>

<script>
function sort(col){
    let dir = "DESC";
    const url = new URL(window.location.href);

    if(url.searchParams.get("sort") === col 
       && url.searchParams.get("dir") === "DESC"){
        dir = "ASC";
    }

    url.searchParams.set("sort", col);
    url.searchParams.set("dir", dir);

    window.location = url.toString();
}
</script>

</body>
</html>
