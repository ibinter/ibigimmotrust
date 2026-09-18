<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/whatsapp.php';

if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

$id = (int)($_POST['id'] ?? 0);
$agent_id = $_POST['agent_id'] ?? '';

if ($id <= 0) {
    die("ID RDV invalide");
}

// Mise à jour
$st = $pdo->prepare("UPDATE rdv_requests SET assigned_to=? WHERE id=?");
$st->execute([$agent_id ?: NULL, $id]);

// 🔔 Notifier l’agent
if ($agent_id) {
    $ag = $pdo->prepare("SELECT * FROM agents WHERE id=? LIMIT 1");
    $ag->execute([$agent_id]);
    $agent = $ag->fetch(PDO::FETCH_ASSOC);

    if ($agent && $agent['whatsapp']) {
        sendWhatsAppToAgent(
            $agent['whatsapp'],
            "Nouveau RDV assigné : merci de traiter ce prospect."
        );
    }
}

header("Location: rdv.php?assigned=1");
exit;
