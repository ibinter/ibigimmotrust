<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
echo "<h1 style='color:red'>OK — functions.php chargé</h1>";
exit;

// Déjà connecté ?
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ==============================
       Step 1 : Anti-bruteforce
    =============================== */
    $ip         = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

    // Vérifie tentatives précédentes
    $check = $pdo->prepare("SELECT attempts, last_attempt FROM failed_logins WHERE ip = ?");
    $check->execute([$ip]);
    $failed = $check->fetch(PDO::FETCH_ASSOC);

    if ($failed) {
        // Si 5 tentatives échouées dans les 15 dernières minutes
        if ($failed['attempts'] >= 5 && strtotime($failed['last_attempt']) > time() - 900) {
            $error = "âÂÂ Trop de tentatives. Réessayez dans 15 minutes.";
            // On affiche le formulaire avec erreur
        }
    }

    /* ==============================
       Step 2 : Récupération inputs
    =============================== */
    $email    = secure_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    /* ==============================
       Step 3 : Vérification admin
    =============================== */
    $st = $pdo->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
    $st->execute([$email]);
    $admin = $st->fetch(PDO::FETCH_ASSOC);

    if ($admin && md5($password) === $admin['password']) {

        /* ==============================
           Step 4 : Connexion réussie
        =============================== */

        // Reset bruteforce
        $reset = $pdo->prepare("DELETE FROM failed_logins WHERE ip = ?");
        $reset->execute([$ip]);

        // Création de la session
        $_SESSION['admin']       = true;
        $_SESSION['admin_id']    = $admin['id'];
        $_SESSION['admin_role']  = $admin['role'];
        $_SESSION['admin_name']  = $admin['fullname'];

        // Journal admin
        log_admin("Login réussi");

        header("Location: dashboard.php");
        exit;
    } 
    else 
    {
        /* ==============================
           Step 5 : Échec de connexion
        =============================== */

        if ($failed) {
            // Mise à jour de la tentative
            $upd = $pdo->prepare("
                UPDATE failed_logins 
                SET attempts = attempts + 1, last_attempt = NOW()
                WHERE ip = ?
            ");
            $upd->execute([$ip]);
        } else {
            // Première erreur
            $ins = $pdo->prepare("
                INSERT INTO failed_logins(ip, user_agent, attempts, last_attempt)
                VALUES (?, ?, 1, NOW())
            ");
            $ins->execute([$ip, $user_agent]);
        }

        log_admin("Échec login", "Email utilisé : $email");
        $error = "Identifiants incorrects.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Connexion Admin</title>

<style>
body{
    font-family:Arial;
    background:#f3f4f6;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.login-box{
    background:white;
    padding:30px;
    width:350px;
    border-radius:12px;
    box-shadow:0 4px 18px rgba(0,0,0,0.1);
}
h2{
    margin-top:0;
    color:#003c96;
    text-align:center;
}
input{
    width:100%;
    padding:10px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:8px;
}
button{
    width:100%;
    padding:10px;
    background:#003c96;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
}
button:hover{
    background:#002a6a;
}
.error{
    background:#ffe5e5;
    padding:10px;
    color:#d60000;
    margin-bottom:10px;
    border-radius:6px;
    font-size:14px;
}
</style>

</head>
<body>

<div class="login-box">
    <h2>Connexion Admin</h2>

    <?php if($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="post">
        <input type="email" name="email" placeholder="Email admin" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <button type="submit">Se connecter</button>
    </form>
</div>

</body>
</html>
