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
// GESTION MESSAGES
// =========================
$msg = '';
$typeMsg = 'success';

// =========================
// AGENT EN ÉDITION (GET ?edit=ID)
// =========================
$editAgent = null;
$editId = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;

if ($editId > 0) {
    $st = $pdo->prepare("SELECT * FROM agents WHERE id=?");
    $st->execute([$editId]);
    $editAgent = $st->fetch(PDO::FETCH_ASSOC);

    if (!$editAgent) {
        $msg = "Agent introuvable pour l'ID ".$editId;
        $typeMsg = 'error';
    }
}

// =========================
// AJOUT / MODIFICATION (POST)
// =========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action  = $_POST['action'] ?? '';
    $name    = trim($_POST['name'] ?? '');
    $phone   = trim($_POST['phone'] ?? '');
    $whatsapp = trim($_POST['whatsapp'] ?? '');
    $idPost  = (int)($_POST['id'] ?? 0);

    if ($name === '') {
        $msg = "Le nom de l'agent est obligatoire.";
        $typeMsg = 'error';
    } else {
        if ($action === 'add') {
            $st = $pdo->prepare("INSERT INTO agents (name, phone, whatsapp) VALUES (?, ?, ?)");
            $st->execute([$name, $phone, $whatsapp]);
            $msg = "Agent ajouté avec succès.";
            $typeMsg = 'success';
        } elseif ($action === 'edit' && $idPost > 0) {
            $st = $pdo->prepare("UPDATE agents SET name=?, phone=?, whatsapp=? WHERE id=?");
            $st->execute([$name, $phone, $whatsapp, $idPost]);
            $msg = "Agent mis à jour avec succès.";
            $typeMsg = 'success';

            // On reste en mode édition sur cet agent
            header("Location: agents.php?edit=".$idPost."&updated=1");
            exit;
        }
    }
}

// =========================
// SUPPRESSION (GET ?delete=ID)
// =========================
if (isset($_GET['delete'])) {
    $idDel = (int) $_GET['delete'];
    if ($idDel > 0) {
        $del = $pdo->prepare("DELETE FROM agents WHERE id=?");
        $del->execute([$idDel]);
        $msg = "Agent supprimé avec succès.";
        $typeMsg = 'success';
    }
}

// =========================
// RÉCUP LISTE AGENTS
// =========================
$agents = $pdo->query("SELECT * FROM agents ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

// Si on vient de ?updated=1, renvoyer un message
if (!empty($_GET['updated']) && $msg === '') {
    $msg = "Agent mis à jour avec succès.";
    $typeMsg = 'success';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Commerciaux / Agents – IBIG IMMO TRUST</title>

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

    .container-box{
        display:grid;
        grid-template-columns: minmax(0, 380px) minmax(0, 1fr);
        gap:20px;
        align-items:flex-start;
    }

    .card{
        background:#ffffff;
        border-radius:12px;
        box-shadow:0 2px 6px rgba(0,0,0,0.06);
        padding:15px 18px;
    }

    .card h2{
        font-size:18px;
        margin:0 0 10px 0;
        color:#111827;
    }

    .form-group{
        margin-bottom:10px;
    }

    label{
        display:block;
        font-size:13px;
        margin-bottom:4px;
        color:#374151;
    }

    input[type="text"],
    input[type="tel"]{
        width:100%;
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

    .btn-secondary{
        background:#6b7280;
    }
    .btn-secondary:hover{
        background:#4b5563;
    }

    .btn-del{
        background:#e30613;
        padding:5px 9px;
        border-radius:6px;
        color:white;
        font-size:12px;
        text-decoration:none;
    }
    .btn-del:hover{ background:#b90410; }

    .btn-small{
        padding:5px 9px;
        font-size:12px;
        border-radius:6px;
    }

    table{
        width:100%;
        border-collapse:collapse;
        background:white;
        border-radius:12px;
        overflow:hidden;
    }

    th, td{
        padding:10px 12px;
        font-size:14px;
        border-bottom:1px solid #e5e7eb;
        text-align:left;
    }

    th{
        background:#003c96;
        color:white;
        font-weight:600;
    }

    tr:hover{
        background:#f9fafb;
    }

    .alert{
        padding:10px 12px;
        border-radius:8px;
        margin-bottom:15px;
        font-size:14px;
    }
    .alert.success{
        background:#d1fae5;
        border-left:5px solid #10b981;
        color:#064e3b;
    }
    .alert.error{
        background:#fee2e2;
        border-left:5px solid #b91c1c;
        color:#7f1d1d;
    }

    .muted{
        font-size:12px;
        color:#6b7280;
        margin-top:4px;
    }
</style>
</head>
<body>

<h1> Commerciaux / Agents – IBIG IMMO TRUST</h1>

<?php if ($msg): ?>
    <div class="alert <?= $typeMsg === 'error' ? 'error' : 'success' ?>">
        <?= htmlspecialchars($msg) ?>
    </div>
<?php endif; ?>

<div class="container-box">

    <!-- FORMULAIRE AJOUT / EDITION -->
    <div class="card">
        <?php if ($editAgent): ?>
            <h2> Modifier un agent</h2>
        <?php else: ?>
            <h2> Ajouter un nouvel agent</h2>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label>Nom complet de l’agent *</label>
                <input type="text" name="name" required
                       value="<?= $editAgent ? htmlspecialchars($editAgent['name']) : '' ?>">
            </div>

            <div class="form-group">
                <label>Téléphone (appel)</label>
                <input type="text" name="phone"
                       value="<?= $editAgent ? htmlspecialchars($editAgent['phone']) : '' ?>">
            </div>

            <div class="form-group">
                <label>Numéro WhatsApp (format international conseillé)</label>
                <input type="text" name="whatsapp"
                       value="<?= $editAgent ? htmlspecialchars($editAgent['whatsapp']) : '' ?>">
                <div class="muted">Ex : 2250700000000</div>
            </div>

            <?php if ($editAgent): ?>
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?= $editAgent['id'] ?>">
                <button type="submit" class="btn"> Mettre à jour</button>
                <a href="agents.php" class="btn btn-secondary"> Annuler</a>
            <?php else: ?>
                <input type="hidden" name="action" value="add">
                <button type="submit" class="btn"> Enregistrer l’agent</button>
            <?php endif; ?>
        </form>
    </div>

    <!-- LISTE DES AGENTS -->
    <div class="card">
        <h2> Liste des agents commerciaux</h2>

        <?php if (empty($agents)): ?>
            <p>Aucun agent enregistré pour le moment.</p>
        <?php else: ?>
            <table>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>WhatsApp</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
                <?php foreach($agents as $a): ?>
                    <tr>
                        <td><?= $a['id'] ?></td>
                        <td><?= htmlspecialchars($a['name']) ?></td>
                        <td><?= htmlspecialchars($a['phone']) ?></td>
                        <td><?= htmlspecialchars($a['whatsapp']) ?></td>
                        <td><?= $a['created_at'] ?></td>
                        <td>
                            <a href="agents.php?edit=<?= $a['id'] ?>" class="btn-small btn">Modifier</a>
                            <a href="agents.php?delete=<?= $a['id'] ?>"
                               class="btn-del"
                               onclick="return confirm('Supprimer cet agent ?');">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
