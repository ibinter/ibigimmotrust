<?php
// =====================================
// PROCESS_CONTACT.PHP – IBIG IMMO TRUST
// Traitement du formulaire de contact
// =====================================

require_once __DIR__ . '/includes/config.php';        // Connexion PDO + sécurité publique
require_once __DIR__ . '/includes/email_config.php';  // PHPMailer centralisé

// Optionnel : si tu veux utiliser secure_input() de functions.php
if (file_exists(__DIR__ . '/includes/functions.php')) {
    require_once __DIR__ . '/includes/functions.php';
}

// -------------------------------------
// 1) Vérifier méthode
// -------------------------------------
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: contact.php");
    exit;
}

// -------------------------------------
// 2) Récupération + nettoyage
// -------------------------------------
$name    = trim($_POST['name']    ?? '');
$phone   = trim($_POST['phone']   ?? '');
$email   = trim($_POST['email']   ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (function_exists('secure_input')) {
    $name    = secure_input($name);
    $phone   = secure_input($phone);
    $email   = secure_input($email);
    $subject = secure_input($subject);
    // pour le message on garde un minimum de mise en forme
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
}

// Validation basique
if ($name === '' || $phone === '' || $subject === '' || $message === '') {
    header("Location: contact.php?error=1");
    exit;
}

// IP & navigateur
$ip         = $_SERVER['REMOTE_ADDR']      ?? 'unknown';
$user_agent = $_SERVER['HTTP_USER_AGENT']  ?? 'unknown';

// -------------------------------------
// 3) Enregistrement SQL (log des messages)
// -------------------------------------
try {
    $sql = "
        INSERT INTO contact_messages
        (name, phone, email, subject, message, ip, user_agent, created_at)
        VALUES (:name, :phone, :email, :subject, :message, :ip, :user_agent, NOW())
    ";
    $st = $pdo->prepare($sql);
    $st->execute([
        ':name'       => $name,
        ':phone'      => $phone,
        ':email'      => $email,
        ':subject'    => $subject,
        ':message'    => $message,
        ':ip'         => $ip,
        ':user_agent' => $user_agent,
    ]);
} catch (Exception $e) {
    // Si plantage SQL, on ne bloque pas l'utilisateur
    // error_log("Erreur contact_messages: " . $e->getMessage());
}

// -------------------------------------
// 4) Envoi email à IBIG IMMO TRUST
// -------------------------------------
$bodyAdmin = "
    <h2> Nouveau message depuis le site IBIG IMMO TRUST</h2>
    <p><b>Nom :</b> {$name}</p>
    <p><b>Téléphone :</b> {$phone}</p>
    <p><b>Email :</b> {$email}</p>
    <p><b>Objet :</b> {$subject}</p>
    <p><b>Message :</b><br>" . nl2br($message) . "</p>
    <hr>
    <p><small>IP : {$ip}<br>User-agent : {$user_agent}</small></p>
";

ibig_send_mail(
    "contact@ibigimmotrust.com",
    "Service Commercial",
    "Nouveau message du site – {$name}",
    $bodyAdmin,
    $email ?: null,
    $name   ?: null
);

// -------------------------------------
// 5) Email de confirmation au client (si email saisi)
// -------------------------------------
if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $bodyClient = "
        Bonjour {$name},<br><br>
        Merci de nous avoir contactés via le site <b>IBIG IMMO TRUST</b>.<br>
        Votre message a bien été reçu et un conseiller vous contactera très rapidement.<br><br>
        <b>Rappel de votre demande :</b><br>
        Objet : {$subject}<br>
        Message :<br>" . nl2br($message) . "<br><br>
        Cordialement,<br>
        <b>IBIG IMMO TRUST</b><br>
        Immobilier & BTP Premium en Côte d’Ivoire.
    ";

    ibig_send_mail(
        $email,
        $name,
        "Votre message a bien été reçu ",
        $bodyClient,
        "contact@ibigimmotrust.com",
        "IBIG IMMO TRUST"
    );
}

// -------------------------------------
// 6) Préparation du lien WhatsApp (INTÉGRATION SIMPLE)
// -------------------------------------
// → tu peux l'afficher sur la page contact.php (bouton) 
//   ou plus tard faire une redirection automatique si tu veux.

$whatsappNumber = "2250778882592"; //  ton numéro pro
$wa_text = urlencode("
NOUVEAU CONTACT IBIG IMMO TRUST 

Nom : {$name}
Téléphone : {$phone}
Email : {$email}
Objet : {$subject}

Message :
{$message}
");
$wa_link = "https://wa.me/{$whatsappNumber}?text={$wa_text}";

// Pour l'instant on ne redirige pas vers WhatsApp automatiquement,
// mais on pourrait plus tard faire : header('Location: ' . $wa_link);

// -------------------------------------
// 7) Redirection finale vers la page contact
// -------------------------------------
header("Location: contact.php?success=1");
exit;
