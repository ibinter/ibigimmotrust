<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if ($_SESSION['role'] !== 'superadmin') {
    die("Accès refusé");
}

$username = $_POST['username'];
$password = md5($_POST['password']);
$role     = $_POST['role'];

$stmt = $pdo->prepare("INSERT INTO admins (username, password, role) VALUES (?,?,?)");
$stmt->execute([$username, $password, $role]);

header("Location: admin_users.php");
