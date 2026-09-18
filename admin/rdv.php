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

// =========================
// SUPPRESSION RDV
// =========================
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $del = $pdo->prepare("DELETE FROM rdv_requests WHERE id=?");
    $del->execute([$id]);
    header("Location: rdv.php?deleted=1");
    exit;
}

// =========================
// RECHERCHE + FILTRES
// =========================
$search = trim($_GET['search'] ?? '');
$from   = $_GET['from'] ?? '';
$to     = $_GET['to'] ?? '';
$type   = $_GET['type'] ?? '';

$where = " WHERE 1 ";
$params = [];

// Recherche générale
if ($search !== '') {
    $where .= " AND (name LIKE :s OR phone LIKE :s OR email LIKE :s OR message LIKE :s) ";
    $params[':s'] = "%$search%";
}

// Filtre type RDV
if ($type !== '' && $type !== 'all') {
    $where .= " AND type = :t ";
    $params[':t'] = $type;
}

// Filtre dates
if ($from !== '' && $to !== '') {
    $where .= " AND DATE(created_at) BETWEEN :df AND :dt ";
    $params[':df'] = $from;
    $params[':dt'] = $to;
}

$sql = "SELECT * FROM rdv_requests $where ORDER BY created_at DESC";
$st = $pdo->prepare($sql);
$st->execute($params);
$rdvs = $st->fetchAll(PDO::FETCH_ASSOC);

// Charger les agents
$agents = $pdo->query("SELECT * FROM agents ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Rendez-vous – IBIG IMMO TRUST</title>

<?php include __DIR__ . '/menu.php'; ?>

<style>
    body{
        font-family:system-ui, sans-serif;
        background:#f4f6fb;
        padding:20px;
    }

    h1{
        margin:20px 0;
        color:#003c96;
        font-weight:800;
    }

    .filters{
        background:white;
        padding:15px;
        border-radius:12px;
        box-shadow:0 2px 6px rgba(0,0,0,0.06);
        display:flex;
        flex-wrap:wrap;
        gap:12px;
        margin-bottom:20px;
        align-items:center;
    }
    .filters input,
    .filters select {
        padding:8px 10px;
        border-radius:6px;
        border:1px solid #d1d5db;
        font-size:14px;
    }
    .btn{
        background:#003c96;
        color:white;
        padding:8px 15px;
        border-radius:6px;
        text-decoration:none;
        border:none;
        cursor:pointer;
        font-size:14px;
    }
    .btn:hover{
        background:#002a6a;
    }

    table{
        width:100%;
        border-collapse:collapse;
        background:white;
        border-radius:12px;
        overflow:hidden;
        box-shadow:0 2px 6px rgba(0,0,0,0.06);
    }
    th{
        background:#003c96;
        color:white;
        padding:12px;
        font-size:14px;
        font-weight:600;
        text-align:left;
    }
    td{
        padding:12px;
        border-bottom:1px solid #eee;
        font-size:14px;
    }
    tr:hover{
        background:#f0f4ff;
    }
    .msg{
        white-space:pre-line;
        background:#fafafa;
        padding:6px;
        border-radius:6px;
        font-size:13px;
    }

    .alert{
        background:#d1fae5;
        padding:10px;
        border-radius:8px;
        border-left:5px solid #10b981;
        margin-bottom:20px;
    }

    .btn-del{
        background:#e30613;
        padding:6px 10px;
        border-radius:6px;
        color:white;
        font-size:12px;
        text-decoration:none;
    }
    .btn-del:hover{ background:#b90410; }

    .btn-view{
        background:#ff9f1c;
        padding:6px 10px;
        border-radius:6px;
        color:white;
        font-size:12px;
        text-decoration:none;
    }

    select.status-select{
        padding:5px 8px;
        border-radius:6px;
        border:1px solid #ccc;
        font-size:12px;
    }
</style>

</head>
<body>

<h1> Liste des rendez-vous</h1>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert">Rendez-vous supprimé avec succès.</div>
<?php endif; ?>

<form method="get" class="filters">

    <input type="text" name="search" placeholder=" Recherche..." value="<?= htmlspecialchars($search) ?>">

    <select name="type">
        <option value="all">Tous les types</option>
        <option value="Achat"     <?= ($type=="Achat"?"selected":"") ?>>Achat</option>
        <option value="Location"  <?= ($type=="Location"?"selected":"") ?>>Location</option>
        <option value="Investissement" <?= ($type=="Investissement"?"selected":"") ?>>Investissement</option>
        <option value="Construction" <?= ($type=="Construction"?"selected":"") ?>>Construction</option>
        <option value="Autre" <?= ($type=="Autre"?"selected":"") ?>>Autre</option>
    </select>

    <label>Du :</label>
    <input type="date" name="from" value="<?= $from ?>">

    <label>Au :</label>
    <input type="date" name="to" value="<?= $to ?>">

    <button class="btn">Filtrer</button>

    <a href="export_rdv_excel.php" class="btn" style="background:#10b981;"> Excel</a>
    <a href="export_rdv_pdf.php" class="btn" style="background:#e30613;"> PDF</a>
</form>

<table>
<tr>
    <th>#</th>
    <th>Nom</th>
    <th>Téléphone</th>
    <th>Email</th>
    <th>Objet</th>
    <th>Date RDV</th>
    <th>Heure</th>
    <th>Message</th>
    <th>Statut</th>
    <th>Assignation</th>
    <th>Créé le</th>
    <th>Action</th>
</tr>

<?php foreach ($rdvs as $r): ?>
<tr>
    <td><?= $r['id'] ?></td>
    <td><?= htmlspecialchars($r['name']) ?></td>
    <td><?= htmlspecialchars($r['phone']) ?></td>
    <td><?= htmlspecialchars($r['email']) ?></td>
    <td><?= htmlspecialchars($r['type']) ?></td>
    <td><?= $r['date_rdv'] ?></td>
    <td><?= $r['time_rdv'] ?></td>
    <td class="msg"><?= nl2br(htmlspecialchars($r['message'])) ?></td>

    <!-- STATUT -->
    <td>
        <form method="post" action="update_rdv_status.php">
            <input type="hidden" name="id" value="<?= $r['id'] ?>">
            <select name="status" class="status-select" onchange="this.form.submit()">
                <option value="En attente"  <?= ($r['status']=="En attente" ? "selected" : "") ?>>En attente</option>
                <option value="Confirmé"    <?= ($r['status']=="Confirmé" ? "selected" : "") ?>>Confirmé</option>
                <option value="Traité"      <?= ($r['status']=="Traité" ? "selected" : "") ?>>Traité</option>
            </select>
        </form>
    </td>

    <!-- ASSIGNATION -->
    <td>
        <form method="post" action="assign_rdv.php">
            <input type="hidden" name="id" value="<?= $r['id'] ?>">
            <select name="agent_id" class="status-select" onchange="this.form.submit()">
                <option value="">— Aucun —</option>
                <?php foreach($agents as $ag): ?>
                    <option value="<?= $ag['id'] ?>" <?= ($r['assigned_to']==$ag['id']?"selected":"") ?>>
                        <?= $ag['name'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </td>

    <td><?= $r['created_at'] ?></td>

    <td>
        <a href="rdv_view.php?id=<?= $r['id'] ?>" class="btn-view">Voir</a>
        <a href="rdv.php?delete=<?= $r['id'] ?>" class="btn-del"
           onclick="return confirm('Supprimer ce rendez-vous ?');">Supprimer</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>