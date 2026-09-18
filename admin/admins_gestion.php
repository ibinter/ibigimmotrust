<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

// Option : seuls les rôles "admin" peuvent gérer les comptes
// if ($_SESSION['admin_role'] !== 'admin') { die("Accès refusé"); }

$msg = "";
$err = "";

// AJOUT D'UN ADMIN
if (isset($_POST['action']) && $_POST['action'] === 'add') {
    $username = trim($_POST['username_add'] ?? '');
    $password = trim($_POST['password_add'] ?? '');
    $role     = trim($_POST['role_add'] ?? 'admin');

    if ($username === '' || $password === '') {
        $err = "Nom d'utilisateur et mot de passe obligatoires.";
    } else {
        // Vérifier doublon
        $st = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ?");
        $st->execute([$username]);
        if ($st->fetchColumn() > 0) {
            $err = "Ce nom d'utilisateur existe déjà.";
        } else {
            $st = $pdo->prepare("INSERT INTO admins (username, password, role, created_at) VALUES (?,?,?,NOW())");
            $st->execute([$username, md5($password), $role]);
            $msg = "Nouvel admin ajouté avec succès.";
        }
    }
}

// CHANGEMENT DE MOT DE PASSE
if (isset($_POST['action']) && $_POST['action'] === 'pass') {
    $id       = (int)($_POST['id_pass'] ?? 0);
    $password = trim($_POST['password_new'] ?? '');

    if ($id <= 0 || $password === '') {
        $err = "Mot de passe obligatoire.";
    } else {
        $st = $pdo->prepare("UPDATE admins SET password = ? WHERE id = ?");
        $st->execute([md5($password), $id]);
        $msg = "Mot de passe mis à jour.";
    }
}

// SUPPRESSION D'UN ADMIN
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    // On évite de supprimer le seul admin existant
    $count = (int)$pdo->query("SELECT COUNT(*) FROM admins")->fetchColumn();
    if ($count <= 1) {
        $err = "Impossible de supprimer le dernier admin.";
    } else {
        $st = $pdo->prepare("DELETE FROM admins WHERE id = ?");
        $st->execute([$id]);
        $msg = "Admin supprimé.";
    }
}

// LISTE DES ADMINS
$admins = $pdo->query("SELECT id, username, role, created_at FROM admins ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des admins – IBIG IMMO TRUST</title>
<style>
body{
    font-family:system-ui, sans-serif;
    background:#f3f4f6;
    margin:0;
}
.page{
    max-width:900px;
    margin:20px auto;
    padding:0 15px;
}
h1{
    font-size:22px;
    color:#111827;
    margin-bottom:12px;
}
.alert-success{
    background:#ecfdf3;
    color:#166534;
    border:1px solid #bbf7d0;
    padding:8px 10px;
    border-radius:8px;
    margin-bottom:10px;
    font-size:13px;
}
.alert-error{
    background:#fef2f2;
    color:#b91c1c;
    border:1px solid #fecaca;
    padding:8px 10px;
    border-radius:8px;
    margin-bottom:10px;
    font-size:13px;
}
table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:10px;
    overflow:hidden;
    box-shadow:0 2px 6px rgba(15,23,42,0.08);
    margin-top:15px;
}
th,td{
    padding:10px 12px;
    font-size:13px;
    border-bottom:1px solid #e5e7eb;
}
th{
    background:#003c96;
    color:#fff;
    text-align:left;
}
tr:hover{
    background:#f9fafb;
}
form.inline{
    display:inline-block;
}
input, select{
    padding:7px 9px;
    border-radius:6px;
    border:1px solid #d1d5db;
    font-size:13px;
}
button{
    padding:7px 12px;
    border:none;
    border-radius:6px;
    background:#003c96;
    color:#fff;
    font-size:13px;
    cursor:pointer;
}
button:hover{ background:#002a6a; }
.small{
    font-size:12px;
    color:#6b7280;
}
.block{
    background:#fff;
    padding:12px;
    border-radius:10px;
    box-shadow:0 2px 6px rgba(15,23,42,0.08);
    margin-top:15px;
}
</style>
</head>
<body>

<?php include __DIR__ . '/menu.php'; ?>

<div class="page">

    <h1>Gestion des administrateurs</h1>

    <?php if($msg): ?><div class="alert-success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <?php if($err): ?><div class="alert-error"><?= htmlspecialchars($err) ?></div><?php endif; ?>

    <div class="block">
        <h3 style="margin:0 0 8px;font-size:15px;">Ajouter un nouvel admin</h3>
        <form method="post">
            <input type="hidden" name="action" value="add">
            <input type="text" name="username_add" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password_add" placeholder="Mot de passe" required>
            <select name="role_add">
                <option value="admin">admin</option>
                <option value="agent">agent</option>
                <option value="consultant">consultant</option>
            </select>
            <button type="submit">Ajouter</button>
        </form>
        <div class="small">Le nouveau mot de passe sera enregistré en MD5 (comme les autres).</div>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Rôle</th>
            <th>Créé le</th>
            <th>Actions</th>
        </tr>

        <?php foreach($admins as $a): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= htmlspecialchars($a['username']) ?></td>
            <td><?= htmlspecialchars($a['role']) ?></td>
            <td><?= $a['created_at'] ?></td>
            <td>
                <!-- Formulaire changement mot de passe -->
                <form method="post" class="inline" style="margin-right:6px;">
                    <input type="hidden" name="action" value="pass">
                    <input type="hidden" name="id_pass" value="<?= $a['id'] ?>">
                    <input type="password" name="password_new" placeholder="Nouveau mot de passe" required>
                    <button type="submit">MAJ MDP</button>
                </form>

                <!-- Supprimer -->
                <a href="admins_gestion.php?delete=<?= $a['id'] ?>"
                   onclick="return confirm('Supprimer cet admin ?');"
                   style="color:#b91c1c;font-size:12px;margin-left:4px;">
                   Supprimer
                </a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>

</body>
</html>
