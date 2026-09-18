<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'] ?? 'admin';

// SEULEMENT SUPERADMIN
if ($role !== 'superadmin') {
    die("<h3 style='color:red;'>Accès refusé : réservé au SUPERADMIN</h3>");
}

// Récupération des admins
$admins = $pdo->query("SELECT * FROM admins ORDER BY id DESC")
             ->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des Administrateurs</title>
<link rel="stylesheet" href="admin.css">
<style>
table{width:100%;border-collapse:collapse;margin-top:20px;background:#fff;}
th,td{padding:10px;border-bottom:1px solid #eee;font-size:14px;}
th{background:#003c96;color:#fff;text-align:left;}
.btn{padding:7px 12px;background:#003c96;color:#fff;border-radius:6px;text-decoration:none;}
.btn-danger{background:#e30613;}
form{margin-bottom:20px;background:#fff;padding:15px;border-radius:8px;}
input,select{padding:7px;width:100%;margin-top:5px;}
</style>
</head>

<body>

<?php include 'menu.php'; ?>

<div class="container">
<h1>👤 Gestion des administrateurs</h1>

<!-- Formulaire ajout -->
<h3>➕ Ajouter un administrateur</h3>
<form method="post" action="admin_users_add.php">
    <label>Nom d'utilisateur</label>
    <input type="text" name="username" required>

    <label>Mot de passe</label>
    <input type="password" name="password" required>

    <label>Rôle</label>
    <select name="role">
        <option value="admin">Admin</option>
        <option value="agent">Agent</option>
        <option value="superadmin">Superadmin</option>
    </select>

    <button class="btn" style="margin-top:10px;">Ajouter</button>
</form>

<!-- Tableau admins -->
<h3>📋 Liste des administrateurs</h3>

<table>
<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Rôle</th>
    <th>Date création</th>
    <th>Actions</th>
</tr>

<?php foreach($admins as $a): ?>
<tr>
    <td><?= $a['id'] ?></td>
    <td><?= htmlspecialchars($a['username']) ?></td>
    <td><?= htmlspecialchars($a['role']) ?></td>
    <td><?= $a['created_at'] ?></td>
    <td>
        <a class="btn" href="admin_users_edit.php?id=<?= $a['id'] ?>">Modifier</a>
        <?php if($a['role'] != 'superadmin'): ?>
        <a class="btn-danger" 
           href="admin_users_delete.php?id=<?= $a['id'] ?>"
           onclick="return confirm('Supprimer cet admin ?');">
           Supprimer
        </a>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

</table>

</div>

</body>
</html>
