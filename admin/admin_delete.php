<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if ($_SESSION['admin_role'] !== 'superadmin') { die("Accès refusé"); }

$id = (int)$_GET['id'];

$pdo->prepare("DELETE FROM admins WHERE id=? LIMIT 1")->execute([$id]);

header("Location: admins.php");
exit;
