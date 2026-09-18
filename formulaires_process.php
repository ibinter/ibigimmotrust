<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: formulaires.php");
    exit;
}

$name     = trim($_POST['name'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$email    = trim($_POST['email'] ?? '');
$type     = trim($_POST['type'] ?? '');
$budget   = trim($_POST['budget'] ?? '');
$location = trim($_POST['location'] ?? '');
$message  = trim($_POST['message'] ?? '');
$ip       = $_SERVER['REMOTE_ADDR'];

if ($name === '' || $phone === '' || $type === '' || $message === '') {
    header("Location: formulaires.php?error=1");
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO demandes_clients (name, phone, email, type, budget, location, message, ip_address)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$name, $phone, $email, $type, $budget, $location, $message, $ip]);

$txt =
"ð *NOUVELLE DEMANDE CLIENT – IBIG IMMO TRUST*\n\n".
"ð¤ Nom : $name\n".
"ð Téléphone : $phone\n".
"ð§ Email : $email\n".
"ð· Type : $type\n".
"ð° Budget : $budget\n".
"ð Localisation : $location\n".
"ð Message : $message\n";

mail(
    "info@ibigimmotrust.com, immo@intermark-business.com",
    "Nouvelle demande client – IBIG IMMO TRUST",
    $txt,
    "From: IBIG IMMO TRUST <no-reply@ibigimmotrust.com>\r\nContent-Type: text/plain; charset=UTF-8\r\n"
);

$wa = "https://api.whatsapp.com/send?phone=2250778882592&text=" . urlencode($txt);
header("Location: $wa");
exit;
