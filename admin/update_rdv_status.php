<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/whatsapp.php';

if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

$id = (int)($_POST['id'] ?? 0);
$status = $_POST['status'] ?? '';

if ($id <= 0 || $status == '') {
    die("Paramètres invalides");
}

// Mise à jour du statut
$st = $pdo->prepare("UPDATE rdv_requests SET status=? WHERE id=?");
$st->execute([$status, $id]);

// Si le RDV est confirmé → envoi WhatsApp au client
if ($status === "Confirmé") {
    sendWhatsAppToClient($id, "Votre rendez-vous a été confirmé par IBIG IMMO TRUST. Merci.");
}

header("Location: rdv.php?status_updated=1");
exit;
