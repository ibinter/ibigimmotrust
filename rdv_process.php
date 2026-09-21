<?php
require_once __DIR__ . '/includes/config.php';

// Simple protection : uniquement POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: rdv.php");
    exit;
}

// Récupération des champs
$name      = trim($_POST['name'] ?? '');
phone: 
$phone     = trim($_POST['phone'] ?? '');
$email     = trim($_POST['email'] ?? '');
$type      = trim($_POST['type'] ?? '');
$finalite  = trim($_POST['finalite'] ?? '');
$budget    = trim($_POST['budget'] ?? '');
$ville     = trim($_POST['ville'] ?? '');
$quartier  = trim($_POST['quartier'] ?? '');
$date_rdv  = trim($_POST['date_rdv'] ?? '');
$time_rdv  = trim($_POST['time_rdv'] ?? '');
$message   = trim($_POST['message'] ?? '');
$pref      = $_POST['preference_contact'] ?? 'appel';
$urgence   = $_POST['urgence'] ?? 'normal';
$provenance= trim($_POST['provenance'] ?? 'site_rdv');
$bien_id   = isset($_POST['bien_id']) && $_POST['bien_id'] !== '' ? (int)$_POST['bien_id'] : null;

// Validation minimum
if ($name === '' || $phone === '' || $type === '' || $finalite === '' || $ville === '' || $date_rdv === '' || $time_rdv === '') {
    header("Location: rdv.php?error=1");
    exit;
}

try {
    $sql = "
        INSERT INTO rdv_requests
        (name, phone, email, type, finalite, budget, ville, quartier,
         preference_contact, urgence, provenance, bien_id,
         date_rdv, time_rdv, message, status, created_at)
        VALUES
        (:name, :phone, :email, :type, :finalite, :budget, :ville, :quartier,
         :pref, :urgence, :provenance, :bien_id,
         :date_rdv, :time_rdv, :message, 'en_attente', NOW())
    ";

    $st = $pdo->prepare($sql);
    $st->execute([
        ':name'       => $name,
        ':phone'      => $phone,
        ':email'      => ($email !== '' ? $email : null),
        ':type'       => $type,
        ':finalite'   => $finalite,
        ':budget'     => ($budget !== '' ? $budget : null),
        ':ville'      => $ville,
        ':quartier'   => ($quartier !== '' ? $quartier : null),
        ':pref'       => $pref,
        ':urgence'    => $urgence,
        ':provenance' => $provenance,
        ':bien_id'    => $bien_id,
        ':date_rdv'   => $date_rdv,
        ':time_rdv'   => $time_rdv,
        ':message'    => ($message !== '' ? $message : null),
    ]);

    // Optionnel : petit email de notification (à adapter avec ton système)
    /*
    $to      = "contact@ibigimmotrust.com";
    $subject = "Nouvelle demande de rendez-vous – IBIG IMMO TRUST";
    $body    = "Nom : $name\nTéléphone : $phone\nEmail : $email\n".
               "Type : $type\nFinalité : $finalite\nBudget : $budget\n".
               "Ville : $ville\nQuartier : $quartier\n".
               "Date / Heure : $date_rdv $time_rdv\n".
               "Préférence : $pref\nUrgence : $urgence\n".
               "Provenance : $provenance\nBien ID : ".($bien_id ?? "N/A")."\n\n".
               "Message :\n$message";
    @mail($to, $subject, $body, "From: no-reply@ibigimmotrust.com");
    */

    header("Location: rdv.php?success=1");
    exit;

} catch (Exception $e) {
    // Tu peux loguer l’erreur si besoin
    // error_log("Erreur RDV: " . $e->getMessage());
    header("Location: rdv.php?error=1");
    exit;
}