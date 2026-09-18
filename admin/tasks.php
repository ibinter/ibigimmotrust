<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) die("Accès refusé");

include __DIR__ . '/menu.php';

$msg = '';

// Traitement POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $title  = trim($_POST['title'] ?? '');
    $desc   = trim($_POST['description'] ?? '');
    $agent  = (int)($_POST['agent_id'] ?? 0);
    $status = $_POST['status'] ?? 'En attente';
    $deadline = $_POST['deadline'] ?: null;

    if ($action === 'add' && $title && $agent>0) {
        $st = $pdo->prepare("INSERT INTO agent_tasks (agent_id,title,description,status,deadline) VALUES (?,?,?,?,?)");
        $st->execute([$agent,$title,$desc,$status,$deadline]);
        $msg = "Tâche ajoutée.";
    }

    if ($action === 'update_status') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id>0) {
            $st = $pdo->prepare("UPDATE agent_tasks SET status=? WHERE id=?");
            $st->execute([$status,$id]);
            $msg = "Statut mis à jour.";
        }
    }
}

// Suppression
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM agent_tasks WHERE id=?")->execute([$id]);
    $msg = "Tâche supprimée.";
}

$agents = $pdo->query("SELECT * FROM agents ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);
$tasks  = $pdo->query("SELECT t.*, a.name AS agent_name
                       FROM agent_tasks t
                       JOIN agents a ON t.agent_id=a.id
                       ORDER BY t.status, t.deadline IS NULL, t.deadline ASC, t.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Tâches agents – IBIG IMMO TRUST</title>
<style>
body{font-family:system-ui,sans-serif;background:#f4f6fb;padding:20px;}
h1{color:#003c96;font-weight:800;}
.grid{display:grid;grid-template-columns:minmax(0,360px) minmax(0,1fr);gap:18px;align-items:flex-start;}
.card{background:white;border-radius:12px;padding:15px 18px;box-shadow:0 4px 10px rgba(0,0,0,0.08);}
label{font-size:13px;color:#374151;display:block;margin-bottom:3px;}
input[type="text"],textarea,select,input[type="date"]{
 width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;font-size:14px;margin-bottom:8px;
}
.btn{background:#003c96;color:white;border:none;border-radius:6px;padding:8px 14px;font-size:14px;cursor:pointer;}
.btn-small{padding:4px 8px;font-size:12px;}
.badge{padding:3px 7px;font-size:11px;border-radius:999px;color:#fff;}
.badge.wait{background:#f59e0b;} .badge.run{background:#2563eb;} .badge.done{background:#16a34a;}
.table{width:100%;border-collapse:collapse;font-size:13px;}
.table th,.table td{padding:8px;border-bottom:1px solid #e5e7eb;text-align:left;}
.alert{margin-bottom:10px;padding:8px 10px;border-radius:8px;background:#d1fae5;border-left:4px solid #10b981;font-size:13px;}
</style>
</head>
<body>

<h1> Tâches des agents</h1>

<?php if($msg): ?><div class="alert"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

<div class="grid">

<div class="card">
    <h2> Nouvelle tâche</h2>
    <form method="post">
        <label>Titre *</label>
        <input type="text" name="title" required>

        <label>Agent *</label>
        <select name="agent_id" required>
            <option value="">— Choisir un agent —</option>
            <?php foreach($agents as $ag): ?>
                <option value="<?= $ag['id'] ?>"><?= htmlspecialchars($ag['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Échéance (optionnel)</label>
        <input type="date" name="deadline">

        <label>Description</label>
        <textarea name="description" rows="3"></textarea>

        <label>Statut initial</label>
        <select name="status">
            <option>En attente</option>
            <option>En cours</option>
            <option>Terminé</option>
        </select>

        <input type="hidden" name="action" value="add">
        <button class="btn">Enregistrer</button>
    </form>
</div>

<div class="card">
    <h2> Liste des tâches</h2>
    <table class="table">
        <tr>
            <th>Titre</th><th>Agent</th><th>Échéance</th><th>Statut</th><th>Action</th>
        </tr>
        <?php foreach($tasks as $t): ?>
            <tr>
                <td><?= htmlspecialchars($t['title']) ?></td>
                <td><?= htmlspecialchars($t['agent_name']) ?></td>
                <td><?= $t['deadline'] ?: '—' ?></td>
                <td>
                    <form method="post" style="display:inline-flex;gap:4px;align-items:center;">
                        <input type="hidden" name="id" value="<?= $t['id'] ?>">
                        <input type="hidden" name="action" value="update_status">
                        <select name="status" onchange="this.form.submit()" style="font-size:11px;">
                            <option <?= $t['status']=='En attente'?'selected':'' ?>>En attente</option>
                            <option <?= $t['status']=='En cours'?'selected':'' ?>>En cours</option>
                            <option <?= $t['status']=='Terminé'?'selected':'' ?>>Terminé</option>
                        </select>
                    </form>
                </td>
                <td>
                    <a href="tasks.php?delete=<?= $t['id'] ?>" onclick="return confirm('Supprimer ?');" class="btn-small" style="background:#e11d48;color:#fff;border-radius:6px;text-decoration:none;">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

</div>
</body>
</html>
