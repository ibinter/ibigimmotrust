<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/includes/config.php';

// Sécurité basique
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.php');
    exit;
}

$name    = trim($_POST['name']    ?? '');
$phone   = trim($_POST['phone']   ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if ($name === '' || $phone === '' || $subject === '' || $message === '') {
    header('Location: contact.php?error=1');
    exit;
}

// Source (au cas où plus tard tu réutilises le même process sur une autre page)
$source_page = 'contact';

// Enregistrement en base
$st = $pdo->prepare("
    INSERT INTO contact_messages (name, phone, email, subject, message, source_page)
    VALUES (:name, :phone, :email, :subject, :message, :source_page)
");

$ok = $st->execute([
    ':name'        => $name,
    ':phone'       => $phone,
    ':email'       => ($email !== '' ? $email : null),
    ':subject'     => $subject,
    ':message'     => $message,
    ':source_page' => $source_page,
]);

// (Optionnel) : envoyer un email interne en plus
/*
@mail(
    'contact@ibigimmotrust.com',
    '[IBIG IMMO TRUST] Nouveau message de contact',
    "Nom : $name\nTéléphone : $phone\nEmail : $email\nObjet : $subject\n\nMessage :\n$message"
);
*/

if ($ok) {
    header('Location: contact.php?success=1');
} else {
    header('Location: contact.php?error=1');
}
exit;
