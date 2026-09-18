<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) { http_response_code(403); exit; }

$id   = (int)($_POST['id'] ?? 0);
$date = $_POST['date'] ?? '';

if ($id > 0 && $date !== '') {
    $st = $pdo->prepare("UPDATE rdv_requests SET date_rdv=? WHERE id=?");
    $st->execute([$date, $id]);
}

echo "OK";
