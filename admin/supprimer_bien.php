<?php
session_start();
require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header("Location: biens.php?msg=ID bien invalide");
    exit;
}

// Supprimer les photos liées
$st = $pdo->prepare("DELETE FROM immo_bien_photos WHERE bien_id = ?");
$st->execute([$id]);

// Supprimer le bien
$st2 = $pdo->prepare("DELETE FROM immo_biens WHERE id = ?");
$st2->execute([$id]);

header("Location: biens.php?msg=Bien supprimé avec succès");
exit;
