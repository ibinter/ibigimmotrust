<?php
// role_protect.php
// Sécurisation par rôles pour les pages admin

if (!isset($_SESSION)) { session_start(); }

function require_role($roles) {

    if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
        header("Location: login.php");
        exit;
    }

    $userRole = $_SESSION['admin_role'] ?? 'agent'; // par défaut agent

    // Toujours autoriser SUPERADMIN
    if ($userRole === 'superadmin') return true;

    // Convertir rôle unique → tableau
    if (!is_array($roles)) {
        $roles = [$roles];
    }

    if (!in_array($userRole, $roles)) {
        die("
            <h2 style='margin:50px;text-align:center;color:#b80606;font-size:22px;'>
                🚫 Accès interdit  
                <br> Vous n’avez pas les permissions nécessaires pour cette page.
            </h2>
        ");
    }

    return true;
}
