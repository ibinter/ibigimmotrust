<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

// Sécurisation
function clean($s){
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}

$name    = clean($_POST['name'] ?? '');
$phone   = clean($_POST['phone'] ?? '');
$email   = clean($_POST['email'] ?? '');
$subject = clean($_POST['subject'] ?? '');
$message = clean($_POST['message'] ?? '');

if ($name == '' || $phone == '' || $subject == '' || $message == '') {
    header("Location: contact.php?error=1");
    exit;
}

// ENREGISTREMENT EN BASE
$stmt = $pdo->prepare("
    INSERT INTO contact_messages (name, email, phone, subject, message)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->execute([$name, $email, $phone, $subject, $message]);

// ENVOI EMAIL
$to = "info@ibigimmotrust.com";
$from = "no-reply@ibigimmotrust.com";
$subjectMail = "Nouveau message – IBIG IMMO TRUST : $subject";

$body = "
Nouveau message reçu depuis le site IBIG IMMO TRUST :

Nom : $name
Téléphone : $phone
Email : $email
Objet : $subject

Message :
$message

Date : ".date('d-m-Y H:i')."
";

$headers = "From: IBIG IMMO TRUST <".$from.">\r\n";
$headers .= "Reply-To: ".$email."\r\n";

mail($to, $subjectMail, $body, $headers);

// REDIRECTION
header("Location: contact.php?success=1");
exit;
