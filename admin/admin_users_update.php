<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if ($_SESSION['role'] !== 'superadmin') {
    die("Accès refusé");
}

$id       = (int)$_POST['id'];
$username = $_POST['username'];
$role     = $_POST['role'];
$password = $_POST['password'];

if ($password != "") {
    $sql = "UPDATE admins SET username=?, role=?, password=? WHERE id=?";
    $params = [$username, $role, md5($password), $id];
} else {
    $sql = "UPDATE admins SET username=?, role=? WHERE id=?";
    $params = [$username, $role, $id];
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

header("Location: admin_users.php");
