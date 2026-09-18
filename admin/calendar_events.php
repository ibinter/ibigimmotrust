<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) { http_response_code(403); exit; }

$rows = $pdo->query("
    SELECT id, name, type, date_rdv, time_rdv 
    FROM rdv_requests 
    WHERE date_rdv IS NOT NULL
")->fetchAll(PDO::FETCH_ASSOC);

$events = [];
foreach($rows as $r){
    $events[] = [
        'id'    => $r['id'],
        'title' => $r['name'] . ' – ' . $r['type'],
        'start' => $r['date_rdv'],
        'allDay'=> true
    ];
}

header('Content-Type: application/json');
echo json_encode($events);
