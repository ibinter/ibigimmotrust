<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/vendor/autoload.php';

// Autoload manuel PHPMailer
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php';
require_once __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function clean($str){
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Erreur : Méthode non autorisée.";
    exit;
}

$name    = clean($_POST['name'] ?? '');
$phone   = clean($_POST['phone'] ?? '');
$email   = clean($_POST['email'] ?? '');
$subject = clean($_POST['subject'] ?? '');
$message = clean($_POST['message'] ?? '');

if (!$name || !$phone || !$subject || !$message) {
    echo "Erreur : Champs manquants.";
    exit;
}

// ------------------------------
// EMAIL PRINCIPAL À IBIG
// ------------------------------
$mail = new PHPMailer(true);

try {

    // CONFIG SMTP LWS
    $mail->isSMTP();
    $mail->Host       = "mail.ibigimmotrust.com";
    $mail->SMTPAuth   = true;
    $mail->Username   = "contact@ibigimmotrust.com";
    $mail->Password   = "pV9!FHrp39Dzc2q"; // Mot de passe réel LWS
    $mail->SMTPSecure = 'ssl';
    $mail->Port       = 465;
    $mail->CharSet    = "UTF-8";

    // Expéditeur
    $mail->setFrom("contact@ibigimmotrust.com", "IBIG IMMO TRUST");

    // Destinataire IBIG
    $mail->addAddress("contact@ibigimmotrust.com");

    // Si le client a mis un email → Reply-To
    if (!empty($email)) {
        $mail->addReplyTo($email, $name);
    }

    // Contenu
    $mail->isHTML(true);
    $mail->Subject = "Nouveau message : " . $subject;
    $mail->Body = "
        <strong>Nouveau message reçu depuis le site IBIG IMMO TRUST :</strong><br><br>
        <strong>Nom :</strong> $name<br>
        <strong>Téléphone :</strong> $phone<br>
        <strong>Email :</strong> $email<br>
        <strong>Objet :</strong> $subject<br><br>
        <strong>Message :</strong><br>$message
    ";

    $mail->send();

    // ------------------------------
    // EMAIL DE CONFIRMATION AU CLIENT
    // ------------------------------
    if (!empty($email)) {

        $confirm = new PHPMailer(true);

        // Reprise SMTP
        $confirm->isSMTP();
        $confirm->Host       = "mail.ibigimmotrust.com";
        $confirm->SMTPAuth   = true;
        $confirm->Username   = "contact@ibigimmotrust.com";
        $confirm->Password   = "pV9!FHrp39Dzc2q";
        $confirm->SMTPSecure = 'ssl';
        $confirm->Port       = 465;
        $confirm->CharSet    = "UTF-8";

        // Expéditeur
        $confirm->setFrom("contact@ibigimmotrust.com", "IBIG IMMO TRUST");

        // Destinataire = l’utilisateur final
        $confirm->addAddress($email, $name);

        // Contenu email client
        $confirm->isHTML(true);
        $confirm->Subject = "Confirmation de réception – IBIG IMMO TRUST";

        $confirm->Body = "
            Bonjour <strong>$name</strong>,<br><br>
            Nous vous remercions pour votre message.<br>
            Notre équipe a bien reçu votre demande et vous contactera très rapidement.<br><br>

            <strong>Récapitulatif de votre demande :</strong><br>
            Objet : $subject<br>
            Message :<br>$message<br><br>

            Cordialement,<br>
            <strong>IBIG IMMO TRUST</strong><br>
            Immobilier • BTP • Gestion Locative • Financement<br>
            Tel : 07 78 88 25 92
        ";

        $confirm->send();
    }

    // REDIRECTION
    header("Location: contact.php?success=1");
    exit;

} catch (Exception $e) {
    echo "<pre>ERREUR SMTP : " . $e->getMessage() . "\n";
    echo "DETAIL : " . $mail->ErrorInfo . "</pre>";
    exit;
}
