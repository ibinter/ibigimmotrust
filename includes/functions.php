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
   SÉCURITÉ GLOBALE – Helpers
========================================== */

/**
 * Nettoie une chaîne ou un tableau (anti XSS basique)
 */
function sanitize($data) {
    if (is_array($data)) {
        return array_map('sanitize', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Version simple pour une seule valeur
 */
function secure_input($value) {
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

/**
 * Génère un token CSRF pour les formulaires
 */
function generate_csrf() {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Vérifie le token CSRF reçu
 */
function is_valid_csrf($token) {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
