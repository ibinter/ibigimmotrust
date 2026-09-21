<?php

/* ==========================================
   LOGS ADMIN
========================================== */
function log_admin($action, $details = ''){
    global $pdo;

    $admin_id = $_SESSION['admin'] ?? 0;
    $ip       = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $agent    = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
    $page     = $_SERVER['REQUEST_URI'] ?? 'unknown';

    $st = $pdo->prepare("
        INSERT INTO logs_admin(admin_id, action, details, ip, user_agent, page, created_at)
        VALUES (?,?,?,?,?,?, NOW())
    ");

    $st->execute([
        $admin_id,
        $action,
        $details,
        $ip,
        $agent,
        $page
    ]);
}

/* ==========================================
   LOG MESSAGES CLIENTS (CRM CENTRAL)
========================================== */
function log_message($pdo, $data = []) {

    $lead_id   = $data['lead_id']   ?? null;
    $nom       = $data['nom']       ?? null;
    $phone     = $data['phone']     ?? null;
    $email     = $data['email']     ?? null;
    $message   = $data['message']   ?? '';
    $canal     = $data['canal']     ?? 'formulaire';
    $direction = $data['direction'] ?? 'in';

    if (trim($message) === '') return false;

    $sql = "INSERT INTO immo_messages 
            (lead_id, nom, phone, email, message, canal, direction, created_at)
            VALUES (:lead_id, :nom, :phone, :email, :message, :canal, :direction, NOW())";

    $st = $pdo->prepare($sql);

    return $st->execute([
        ':lead_id'   => $lead_id ?: null,
        ':nom'       => $nom,
        ':phone'     => $phone,
        ':email'     => $email,
        ':message'   => $message,
        ':canal'     => $canal,
        ':direction' => $direction
    ]);
}

/* ==========================================
   SÉCURITÉ – SANITIZE
========================================== */

function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function secure_input($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

/* ==========================================
   CSRF TOKEN
========================================== */

function generate_csrf() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function is_valid_csrf($token) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

?>
