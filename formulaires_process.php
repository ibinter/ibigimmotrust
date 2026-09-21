<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

// Vérifier méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: formulaires.php");
    exit;
}

// Sécurisation
function clean($v){
    return htmlspecialchars(trim($v ?? ''), ENT_QUOTES, 'UTF-8');
}

$name     = clean($_POST['name']);
$phone    = clean($_POST['phone']);
$email    = clean($_POST['email']);
$type     = clean($_POST['type']);
$budget   = clean($_POST['budget']);
$location = clean($_POST['location']);
$message  = clean($_POST['message']);
$ip       = $_SERVER['REMOTE_ADDR'];

// Champs obligatoires
if ($name === '' || $phone === '' || $type === '' || $message === '') {
    header("Location: formulaires.php?error=1");
    exit;
}

// Enregistrement DB
$stmt = $pdo->prepare("
    INSERT INTO demandes_clients (name, phone, email, type, budget, location, message, ip_address, created_at)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())
");
$stmt->execute([$name, $phone, $email, $type, $budget, $location, $message, $ip]);

$txt =
"🔥 *NOUVELLE DEMANDE CLIENT – IBIG IMMO TRUST*\n\n".
"👤 Nom : $name\n".
"📞 Téléphone : $phone\n".
"📧 Email : $email\n".
"📌 Type : $type\n".
"💰 Budget : $budget\n".
"📍 Localisation : $location\n".
"📝 Message : $message\n\n".
"🔒 IP : $ip\n";

// --------------------------------------
// 1️⃣ EMAIL – PHPMailer (LWS SMTP)
// --------------------------------------
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/SMTP.php';
require_once __DIR__ . '/../vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = "mail.ibigimmotrust.com";
    $mail->SMTPAuth   = true;
    $mail->Username   = "contact@ibigimmotrust.com";
    $mail->Password   = "pV9!FHrp39Dzc2q"; 
    $mail->SMTPSecure = "ssl";
    $mail->Port       = 465;
    $mail->CharSet    = "UTF-8";

    $mail->setFrom("contact@ibigimmotrust.com", "IBIG IMMO TRUST");
    $mail->addAddress("contact@ibigimmotrust.com");
    $mail->addAddress("immo@intermark-business.com");

    if ($email !== '') {
        $mail->addReplyTo($email, $name);
    }

    $mail->Subject = "🔥 Nouvelle demande client – IBIG IMMO TRUST";
    $mail->Body = nl2br($txt);
    $mail->isHTML(true);

    $mail->send();
} catch (Exception $e) {
    // On ne bloque rien en cas d'erreur
}


// --------------------------------------
// 2️⃣ NOTIFICATION WHATSAPP ADMIN (Silencieuse)
// --------------------------------------
$waNumber = "2250778882592";
$waUrl = "https://api.whatsapp.com/send?phone=$waNumber&text=".urlencode($txt);

// Appel silencieux (1 seconde max)
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $waUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 1);
curl_exec($ch);
curl_close($ch);

// --------------------------------------
// 3️⃣ REDIRECTION CLIENT VERS PAGE SUCCESS
// --------------------------------------
header("Location: formulaire-success.php");
exit;
?>
