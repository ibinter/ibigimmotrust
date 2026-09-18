<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include __DIR__ . '/menu.php';

// Récupérer tous les admins
$admins = $pdo->query("SELECT * FROM admins ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des administrateurs</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family: system-ui, sans-serif;
    background:#f4f6fb;
    margin:0;
    padding:20px;
}

h1{
    font-size:24px;
    font-weight:700;
    color:#003c96;
    margin-bottom:18px;
}

.btn-add{
    display:inline-block;
    background:#003c96;
    color:white;
    padding:10px 18px;
    border-radius:8px;
    font-size:15px;
    font-weight:600;
    text-decoration:none;
    margin-bottom:20px;
}
.btn-add i{ margin-right:6px; }

.table-box{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.06);
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th, td{
    padding:12px 14px;
    border-bottom:1px solid #eee;
    font-size:14px;
}

th{
    background:#003c96;
    color:white;
    text-align:left;
}

tr:hover{
    background:#f0f5ff;
}

.badge{
    display:inline-block;
    padding:5px 10px;
    border-radius:8px;
    font-size:13px;
    color:white;
    font-weight:600;
}

.superadmin{ background:#e30613; }
.admin{ background:#003c96; }
.agent{ background:#10b981; }
.consultant{ background:#f59e0b; }

.action-links a{
    margin-right:10px;
    font-weight:600;
    text-decoration:none;
}

.edit{ color:#003c96; }
.delete{ color:#e30613; }

</style>
</head>

<body>

<h1><i class="fa-solid fa-users-gear"></i> Gestion des administrateurs</h1>

<a href="admin_add.php" class="btn-add">
    <i class="fa-solid fa-user-plus"></i> Ajouter un administrateur
</a>

<div class="table-box">
<table>
    <tr>
        <th>ID</th>
        <th>Nom complet</th>
        <th>Email</th>
        <th>Identifiant</th>
        <th>Rôle</th>
        <th>Date création</th>
        <th>Actions</th>
    </tr>

    <?php if (count($admins) === 0): ?>
        <tr>
            <td colspan="7" style="text-align:center; padding:20px;">
                Aucun administrateur enregistré.
            </td>
        </tr>
    <?php endif; ?>

    <?php foreach($admins as $a): ?>
    <tr>
        <td><?= $a['id'] ?></td>
        <td><?= htmlspecialchars($a['fullname']) ?></td>
        <td><?= htmlspecialchars($a['email']) ?></td>
        <td><?= htmlspecialchars($a['username']) ?></td>

        <td>
            <span class="badge <?= $a['role'] ?>">
                <?= ucfirst($a['role']) ?>
            </span>
        </td>

        <td><?= $a['created_at'] ?></td>

        <td class="action-links">
            <a href="admin_edit.php?id=<?= $a['id'] ?>" class="edit">
                <i class="fa-solid fa-pen"></i> Modifier
            </a>

            <?php if ($a['role'] !== 'superadmin'): ?>
            <a href="admin_delete.php?id=<?= $a['id'] ?>"
               class="delete"
               onclick="return confirm('Supprimer cet administrateur ?');">
               <i class="fa-solid fa-trash"></i> Supprimer
            </a>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>
</div>

</body>
</html>
