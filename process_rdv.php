<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

function clean($s){
    return htmlspecialchars(trim($s), ENT_QUOTES, 'UTF-8');
}

$name      = clean($_POST['name'] ?? '');
$phone     = clean($_POST['phone'] ?? '');
$email     = clean($_POST['email'] ?? '');
$type      = clean($_POST['type'] ?? '');
$date_rdv  = clean($_POST['date_rdv'] ?? '');
$time_rdv  = clean($_POST['time_rdv'] ?? '');
$message   = clean($_POST['message'] ?? '');

if ($name=='' || $phone=='' || $type=='' || $date_rdv=='' || $time_rdv=='') {
    header("Location: rdv.php?error=1");
    exit;
}

// ENREGISTREMENT EN BASE
$stmt = $pdo->prepare("
    INSERT INTO rdv_requests (name, phone, email, type, date_rdv, time_rdv, message)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->execute([$name, $phone, $email, $type, $date_rdv, $time_rdv, $message]);

// EMAIL
$to = "info@ibigimmotrust.com";
$from = "no-reply@ibigimmotrust.com";
$subject = "Nouvelle demande de rendez-vous : $name";

$body = "
Demande de rendez-vous :

Nom         : $name
Téléphone   : $phone
Email       : $email
Objet       : $type
Date        : $date_rdv
Heure       : $time_rdv

Message :
$message

Date de la demande : ".date('d-m-Y H:i')."
";

$headers = "From: IBIG IMMO TRUST <".$from.">\r\n";
$headers .= "Reply-To: ".$email."\r\n";

mail($to, $subject, $body, $headers);

header("Location: rdv.php?success=1");
exit;
