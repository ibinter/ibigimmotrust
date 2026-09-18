<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if ($_SESSION['admin_role'] !== 'superadmin') {
    die("Accès refusé");
}

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']); // 🔥 AJOUTÉ
    $email    = trim($_POST['email']);
    $password = md5($_POST['password']); // ⚠ À remplacer plus tard par password_hash()
    $role     = $_POST['role'];

    // INSERT complet incluant username
    $st = $pdo->prepare("INSERT INTO admins(fullname,username,email,password,role) VALUES(?,?,?,?,?)");

    try {
        $st->execute([$fullname,$username,$email,$password,$role]);
        $msg = "Administrateur ajouté avec succès !";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $msg = "❌ Le nom d’utilisateur est déjà utilisé.";
        } else {
            $msg = "❌ Erreur SQL : " . $e->getMessage();
        }
    }
}
?>

<?php include __DIR__ . '/menu.php'; ?>

<style>
/* =====================================
   STYLE PREMIUM AJOUT ADMIN – IBIG IMMO
   ===================================== */

.page-wrapper{
    padding:35px;
}

h1{
    font-size:26px;
    color:#003c96;
    font-weight:700;
    margin-bottom:25px;
}

/* Message succès / erreur */
.success{
    background:#d4f8df;
    color:#0a7a2e;
    padding:12px 18px;
    border-radius:8px;
    margin-bottom:20px;
    border-left:5px solid #0a7a2e;
    font-weight:600;
}

.error{
    background:#ffe0e0;
    color:#b30000;
    padding:12px 18px;
    border-radius:8px;
    margin-bottom:20px;
    border-left:5px solid #b30000;
    font-weight:600;
}

/* Form container */
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

/* Inputs premium */
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

<h1>➕ Ajouter un administrateur</h1>

<?php if($msg): ?>
<div class="<?= str_starts_with($msg,"❌") ? 'error' : 'success' ?>"><?= $msg ?></div>
<?php endif; ?>

<form method="post">
    <div>
        <label>Nom complet</label>
        <input type="text" name="fullname" required>
    </div>

    <div>
        <label>Nom d’utilisateur (username)</label> <!-- 🔥 AJOUTÉ -->
        <input type="text" name="username" required>
    </div>

    <div>
        <label>Email</label>
        <input type="email" name="email" required>
    </div>

    <div>
        <label>Mot de passe</label>
        <input type="password" name="password" required>
    </div>

    <div>
        <label>Rôle</label>
        <select name="role">
            <option value="admin">Admin</option>
            <option value="agent">Agent</option>
            <option value="consultant">Consultant</option>
            <option value="superadmin">Super Admin</option>
        </select>
    </div>

    <button class="btn-main">Créer</button>
</form>

</div>
