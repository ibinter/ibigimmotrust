<?php
if (!isset($_SESSION)) { session_start(); }

$current = basename($_SERVER['PHP_SELF']); // page active
$role = $_SESSION['admin_role'] ?? 'agent';
?>

<style>
/* =====================================================
   IBIG IMMO TRUST – MENU ADMIN PREMIUM V2
   ===================================================== */

.admin-menu {
    background:#003c96;
    padding:15px 0;
    margin-bottom:25px;
    box-shadow:0 4px 10px rgba(0,0,0,0.20);
    position:sticky;
    top:0;
    z-index:9999;
}

.admin-menu .wrapper {
    max-width:1200px;
    margin:auto;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 20px;
}

.admin-logo {
    color:#fff;
    font-size:22px;
    font-weight:700;
    letter-spacing:1px;
    display:flex;
    align-items:center;
    gap:10px;
}

.admin-logo span {
    background:#ff9f1c;
    color:white;
    padding:4px 7px;
    border-radius:6px;
    font-size:14px;
}

.admin-nav {
    display:flex;
    gap:18px;
    align-items:center;
}

.admin-nav a {
    color:#e3ecff;
    text-decoration:none;
    padding:7px 14px;
    font-size:15px;
    border-radius:6px;
    transition:0.25s;
    font-weight:500;
    display:flex;
    align-items:center;
    gap:6px;
}

.admin-nav a:hover {
    background:rgba(255,255,255,0.18);
    color:#fff;
}

.admin-nav a.active {
    background:#e30613;
    color:#fff !important;
    font-weight:700;
    box-shadow:0 3px 6px rgba(0,0,0,0.2);
}

.logout-btn {
    background:#e30613;
    padding:7px 14px;
    border-radius:6px;
    color:#fff !important;
    font-weight:600;
    display:flex;
    align-items:center;
    gap:6px;
}

.logout-btn:hover{
    background:#b5050f;
}

/* BURGER ICON */
.admin-burger {
    font-size:28px;
    color:white;
    cursor:pointer;
    display:none;
}

/* MOBILE MENU */
#admin-mobile-menu {
    position:fixed;
    top:0;
    right:-100%;
    width:75%;
    height:100vh;
    background:#003c96;
    padding:40px 20px;
    display:flex;
    flex-direction:column;
    gap:22px;
    transition:0.35s ease-in-out;
    z-index:99999;
    box-shadow:-4px 0 10px rgba(0,0,0,0.25);
}

#admin-mobile-menu a {
    color:#fff;
    font-size:18px;
    text-decoration:none;
    padding:10px 0;
    border-bottom:1px solid rgba(255,255,255,0.15);
    display:flex;
    align-items:center;
    gap:10px;
}

/* RESPONSIVE */
@media(max-width:900px){
    .admin-nav { display:none; }
    .admin-burger { display:block; }
}
</style>

<!-- ====== BARRE HAUT ADMIN ====== -->
<div class="admin-menu">
    <div class="wrapper">

        <div class="admin-logo">
             IBIG IMMO <span>ADMIN</span>
        </div>

        <!-- MENU DESKTOP -->
        <nav class="admin-nav">

            <a href="dashboard.php" class="<?= ($current=='dashboard.php'?'active':'') ?>">
                 <span>Dashboard</span>
            </a>

            <a href="biens.php" class="<?= ($current=='biens.php'?'active':'') ?>">
                 <span>Biens</span>
            </a>

            <a href="demandes.php" class="<?= ($current=='demandes.php'?'active':'') ?>">
                 <span>Demandes</span>
            </a>

            <a href="rdv.php" class="<?= ($current=='rdv.php'?'active':'') ?>">
                 <span>RDV</span>
            </a>

            <a href="agents.php" class="<?= ($current=='agents.php'?'active':'') ?>">
                 <span>Agents</span>
            </a>
            
            <a href="analytics.php" class="<?= ($current=='analytics.php'?'active':'') ?>">
              📊 <span>Analytics</span>
            </a>

            <?php if ($role === 'superadmin'): ?>
            <a href="admins.php" class="<?= ($current=='admins.php'?'active':'') ?>">
                 <span>Admins</span>
            </a>
            <?php endif; ?>

            <a href="logout.php" class="logout-btn">
                 <span>Déconnexion</span>
            </a>
        </nav>

        <!-- BURGER -->
        <div class="admin-burger" onclick="toggleAdminMenu()"></div>

    </div>
</div>

<!-- MENU MOBILE -->
<div id="admin-mobile-menu">
    <a href="dashboard.php"> Dashboard</a>
    <a href="biens.php"> Biens</a>
    <a href="demandes.php"> Demandes</a>
    <a href="rdv.php"> RDV</a>
    <a href="agents.php"> Agents</a>
    <a href="visites.php">ð Statistiques visites</a>

    <?php if ($role === 'superadmin'): ?>
    <a href="admins.php"> Gestion Admins</a>
    <?php endif; ?>

    <a href="logout.php" style="color:#ffbbbb;"> Déconnexion</a>
</div>

<script>
function toggleAdminMenu(){
    let menu = document.getElementById("admin-mobile-menu");
    menu.style.right = (menu.style.right === "0%") ? "-100%" : "0%";
}
</script>
