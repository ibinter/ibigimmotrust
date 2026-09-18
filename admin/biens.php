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

include __DIR__ . '/menu.php';

/* ============================
   FILTRES
============================ */
$search       = trim($_GET['search'] ?? '');
$type_filter  = trim($_GET['type'] ?? '');
$trans_filter = trim($_GET['transaction'] ?? '');
$statut_filter= trim($_GET['statut'] ?? '');
$min_price    = trim($_GET['min_price'] ?? '');
$max_price    = trim($_GET['max_price'] ?? '');

$where = "WHERE 1";
$params = [];

// Recherche
if ($search !== "") {
    $where .= " AND (titre LIKE :s OR type LIKE :s OR ville LIKE :s)";
    $params[':s'] = "%$search%";
}
// Type
if ($type_filter !== "") {
    $where .= " AND type = :t";
    $params[':t'] = $type_filter;
}
// Transaction
if ($trans_filter !== "") {
    $where .= " AND transaction = :tr";
    $params[':tr'] = $trans_filter;
}
// Statut
if ($statut_filter !== "") {
    $where .= " AND statut = :st";
    $params[':st'] = $statut_filter;
}
// Prix min
if ($min_price !== "") {
    $where .= " AND prix >= :pmin";
    $params[':pmin'] = $min_price;
}
// Prix max
if ($max_price !== "") {
    $where .= " AND prix <= :pmax";
    $params[':pmax'] = $max_price;
}

$sql = "
    SELECT *
    FROM immo_biens
    $where
    ORDER BY created_at DESC
";

$st = $pdo->prepare($sql);
$st->execute($params);
$biens = $st->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Biens immobiliers – IBIG IMMO TRUST</title>

<style>
body{ font-family:Arial; background:#f4f6fb; padding:20px; }
h1{ margin-bottom:20px; color:#003c96; }

.filters{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:18px;
    background:white;
    padding:12px 15px;
    border-radius:10px;
    box-shadow:0 2px 6px rgba(0,0,0,0.1);
}
.filters input, .filters select{
    padding:8px 10px;
    border:1px solid #ccc;
    border-radius:6px;
}
.btn{
    padding:9px 15px;
    background:#003c96;
    color:white;
    border-radius:6px;
    cursor:pointer;
    border:none;
}
.btn:hover{ background:#002a70; }

table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 2px 6px rgba(0,0,0,0.08);
}
th, td{
    padding:12px;
    border-bottom:1px solid #eee;
}
th{
    background:#003c96;
    color:white;
    text-align:left;
}
tr:hover{ background:#eef3ff; }

.action-btn{
    margin-right:10px;
    font-weight:600;
}
.export-btns{
    margin-top:10px;
    display:flex;
    gap:10px;
}
</style>
</head>

<body>

<h1> Gestion des biens immobiliers</h1>

<a href="bien_edit.php" class="btn" style="margin-bottom:15px; display:inline-block;"> Ajouter un bien</a>

<!-- ======================
     FILTRES
======================= -->
<form method="GET" class="filters">

    <input type="text" name="search" placeholder="Recherche..." 
           value="<?= htmlspecialchars($search) ?>">

    <select name="type">
        <option value="">Type</option>
        <?php
        $types = ["Appartement","Studio","Maison","Villa","Terrain","Bureau","Magasin"];
        foreach($types as $t){
            $sel = ($type_filter === $t) ? "selected" : "";
            echo "<option $sel>$t</option>";
        }
        ?>
    </select>

    <select name="transaction">
        <option value="">Transaction</option>
        <?php
        $trTypes = ["A louer","A vendre","Réservé","Loué","Vendu"];
        foreach($trTypes as $tr){
            $sel = ($trans_filter === $tr) ? "selected" : "";
            echo "<option $sel>$tr</option>";
        }
        ?>
    </select>

    <select name="statut">
        <option value="">Statut</option>
        <?php
        $stats = ["Disponible","En négociation","Résérvé","Loué","Vendu","Archivé"];
        foreach($stats as $s){
            $sel = ($statut_filter === $s) ? "selected" : "";
            echo "<option $sel>$s</option>";
        }
        ?>
    </select>

    <input type="number" name="min_price" placeholder="Prix min"
           value="<?= htmlspecialchars($min_price) ?>">

    <input type="number" name="max_price" placeholder="Prix max"
           value="<?= htmlspecialchars($max_price) ?>">

    <button class="btn">Filtrer</button>
</form>

<div class="export-btns">
    <a href="export_biens_excel.php" class="btn"> Export Excel</a>
    <a href="export_biens_pdf.php" class="btn" style="background:#e30613;"> Export PDF</a>
</div>

<!-- ======================
     TABLEAU
======================= -->
<table>
<tr>
    <th>ID</th>
    <th>Titre</th>
    <th>Type</th>
    <th>Transaction</th>
    <th>Prix</th>
    <th>Statut</th>
    <th>Actions</th>
</tr>

<?php if (count($biens) === 0): ?>
<tr><td colspan="7" style="text-align:center; padding:15px;">Aucun bien trouvé.</td></tr>
<?php endif; ?>

<?php foreach($biens as $b): ?>
<tr>
    <td><?= $b['id'] ?></td>
    <td><?= htmlspecialchars($b['titre']) ?></td>
    <td><?= htmlspecialchars($b['type']) ?></td>
    <td><?= htmlspecialchars($b['transaction']) ?></td>
    <td><?= number_format($b['prix'],0,',',' ') ?> FCFA</td>
    <td><?= htmlspecialchars($b['statut']) ?></td>
    <td>
        <a class="action-btn" href="bien_edit.php?id=<?= $b['id'] ?>"> Modifier</a>
        <a class="action-btn" href="supprimer_bien.php?id=<?= $b['id'] ?>"
           onclick="return confirm('Supprimer définitivement ?');"
           style="color:red;"> Supprimer</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
