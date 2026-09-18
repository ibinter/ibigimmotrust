<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

$admins = $pdo->query("SELECT * FROM admins ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/menu.php';
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Admins – IBIG IMMO TRUST</title>
<link rel="stylesheet" href="admin.css">
<style>
.container{ padding:20px; }
a.btn{ padding:8px 14px; background:#003c96; color:#fff; border-radius:6px; text-decoration:none; }
table{
  width:100%; border-collapse:collapse; background:white; border-radius:10px; overflow:hidden;
}
th,td{ padding:12px; border-bottom:1px solid #eee; font-size:14px; }
th{ background:#003c96; color:white; }
tr:hover{ background:#eef3ff; }
.actions a{ margin-right:10px; font-weight:600; }
.actions .del{ color:red; }
</style>
</head>
<body>

<div class="container">
<h1>👤 Gestion des administrateurs</h1>

<a href="admin_add.php" class="btn">➕ Ajouter un administrateur</a>

<table>
<tr>
  <th>ID</th>
  <th>Nom complet</th>
  <th>Email</th>
  <th>Username</th>
  <th>Rôle</th>
  <th>Action</th>
</tr>

<?php foreach($admins as $a): ?>
<tr>
  <td><?= $a['id'] ?></td>
  <td><?= htmlspecialchars($a['fullname']) ?></td>
  <td><?= htmlspecialchars($a['email']) ?></td>
  <td><?= htmlspecialchars($a['username']) ?></td>
  <td><?= htmlspecialchars($a['role']) ?></td>
  <td class="actions">
      <a href="admin_edit.php?id=<?= $a['id'] ?>">✏ Modifier</a>
      <a href="admin_delete.php?id=<?= $a['id'] ?>" class="del"
         onclick="return confirm('Supprimer définitivement cet admin ?');">🗑 Supprimer</a>
  </td>
</tr>
<?php endforeach; ?>
</table>
</div>

</body>
</html>
