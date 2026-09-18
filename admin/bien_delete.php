<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) die("Accès refusé");

$id = (int)($_GET['id'] ?? 0);
if($id<=0) die("ID invalide");

$st = $pdo->prepare("DELETE FROM immo_biens WHERE id=?");
$st->execute([$id]);

header("Location: biens.php?msg=".urlencode("Bien supprimé."));
exit;
