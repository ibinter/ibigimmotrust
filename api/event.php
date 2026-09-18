<?php
require_once __DIR__ . '/../includes/config.php';

$data = json_decode(file_get_contents("php://input"), true);

$event = $data['event'] ?? '';
$value = $data['value'] ?? '';

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$page = $_SERVER['HTTP_REFERER'] ?? '';
$browser = $_SERVER['HTTP_USER_AGENT'] ?? '';

$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
$device = preg_match('/mobile|android|iphone|ipad/i', $ua) ? 'Mobile' : 'Desktop';

$geo = @json_decode(file_get_contents("http://ip-api.com/json/$ip"), true);

$st = $pdo->prepare("
INSERT INTO events(ip,event_name,event_value,page,browser,device,country,city)
VALUES(?,?,?,?,?,?,?,?)
");
$st->execute([
    $ip,
    $event,
    $value,
    $page,
    $browser,
    $device,
    $geo['country'] ?? '',
    $geo['city'] ?? ''
]);

echo "OK";