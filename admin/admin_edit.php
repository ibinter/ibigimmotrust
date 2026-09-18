<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if ($_SESSION['admin_role'] !== 'superadmin') {
    die("Accès refusé");
}

$id = (int)$_GET['id'];
$st = $pdo->prepare("SELECT * FROM admins WHERE id=?");
$st->execute([$id]);
$admin = $st->fetch();

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $role     = $_POST['role'];

    // update
    $pdo->prepare("UPDATE admins SET fullname=?, email=?, role=? WHERE id=?")
        ->execute([$fullname,$email,$role,$id]);

    if (!empty($_POST['new_password'])) {
        $newpass = md5($_POST['new_password']); // ⚠ À remplacer par password_hash plus tard
        $pdo->prepare("UPDATE admins SET password=? WHERE id=?")
            ->execute([$newpass,$id]);
    }

    $msg = "Mise à jour réussie.";
}
?>

<?php include __DIR__ . '/menu.php'; ?>

<style>
/* =============================================
   STYLE PREMIUM – MODIFIER ADMIN – IBIG IMMO
   ============================================= */

.page-wrapper{
    padding:35px;
}

h1{
    font-size:26px;
    color:#003c96;
    font-weight:700;
    margin-bottom:25px;
}

/* Message succès */
.success{
    background:#d4f8df;
    color:#0a7a2e;
    padding:12px 18px;
    border-radius:8px;
    margin-bottom:20px;
    border-left:5px solid #0a7a2e;
    font-weight:600;
}

/* Form card */
form{
    max-width:550px;
    background:white;
    padding:25px;
    border-radius:14px;
    box-shadow:0 4px 18px rgba(0,0,0,0.08);
    display:flex;
    flex-direction:column;
    gap:15px;
}

/* Labels */
label{
    font-weight:600;
    margin-bottom:3px;
    color:#001f54;
}

/* Inputs & selects */
input, select{
    padding:12px;
    border:1px solid #d0d7e2;
    border-radius:10px;
    font-size:15px;
    transition:0.3s;
}

input:focus, select:focus{
    border-color:#003c96;
    box-shadow:0 0 0 3px rgba(0,60,150,0.15);
    outline:none;
}

/* Button */
.btn-main{
    background:#003c96;
    color:white;
    padding:13px;
    border-radius:10px;
    border:none;
    font-size:16px;
    font-weight:600;
    margin-top:10px;
    cursor:pointer;
    transition:0.3s;
}
.btn-main:hover{
    background:#001f54;
}
</style>

<div class="page-wrapper">

<h1>✏ Modifier l’administrateur</h1>

<?php if($msg): ?>
<div class="success"><?= $msg ?></div>
<?php endif; ?>

<form method="post">

    <div>
        <label>Nom complet</label>
        <input type="text" name="fullname" value="<?= htmlspecialchars($admin['fullname']) ?>" required>
    </div>

    <div>
        <label>Email</label>
        <input type="email" name="email" value="<?= htmlspecialchars($admin['email']) ?>" required>
    </div>

    <div>
        <label>Rôle</label>
        <select name="role">
            <option value="admin"      <?= $admin['role']=='admin'?'selected':'' ?>>Admin</option>
            <option value="agent"      <?= $admin['role']=='agent'?'selected':'' ?>>Agent</option>
            <option value="consultant" <?= $admin['role']=='consultant'?'selected':'' ?>>Consultant</option>
            <option value="superadmin" <?= $admin['role']=='superadmin'?'selected':'' ?>>Super Admin</option>
        </select>
    </div>

    <div>
        <label>Nouveau mot de passe (optionnel)</label>
        <input type="password" name="new_password">
    </div>

    <button class="btn-main">Modifier</button>
</form>

</div>
