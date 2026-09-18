<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if ($_SESSION['role'] !== 'superadmin') {
    die("Accès refusé");
}

$id = (int)$_GET['id'];

$admin = $pdo->prepare("SELECT * FROM admins WHERE id=?");
$admin->execute([$id]);
$a = $admin->fetch();

if (!$a) die("Admin introuvable");
?>

<!DOCTYPE html>
<html>
<head>
<title>Modifier admin</title>
<link rel="stylesheet" href="admin.css">
</head>
<body>

<?php include 'menu.php'; ?>

<div class="container">
<h2>✏ Modifier administrateur</h2>

<form method="post" action="admin_users_update.php">
    <input type="hidden" name="id" value="<?= $a['id'] ?>">

    <label>Nom d'utilisateur</label>
    <input type="text" name="username" value="<?= $a['username'] ?>" required>

    <label>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
    <input type="password" name="password">

    <label>Rôle</label>
    <select name="role">
        <option <?= $a['role']=='admin'?'selected':'' ?> value="admin">Admin</option>
        <option <?= $a['role']=='agent'?'selected':'' ?> value="agent">Agent</option>
        <option <?= $a['role']=='superadmin'?'selected':'' ?> value="superadmin">Superadmin</option>
    </select>

    <button class="btn" style="margin-top:10px;">Mettre à jour</button>
</form>
</div>

</body>
</html>
