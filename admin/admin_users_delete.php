<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if ($_SESSION['role'] !== 'superadmin') {
    die("Accès refusé");
}

$id = (int)$_GET['id'];

$pdo->prepare("DELETE FROM admins WHERE id=?")->execute([$id]);

header("Location: admin_users.php");
