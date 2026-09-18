<?php
require_once __DIR__ . '/../includes/config.php';

$data = json_decode(file_get_contents("php://input"), true);

$page       = $data['page'] ?? '';
$time_spent = intval($data['time_spent'] ?? 0);
$ip         = $_SERVER['REMOTE_ADDR'] ?? '';

if ($page && $time_spent > 0) {
    $st = $pdo->prepare("
        INSERT INTO visit_time(page, time_spent, ip)
        VALUES(?,?,?)
    ");
    $st->execute([$page, $time_spent, $ip]);
}

echo "OK";
