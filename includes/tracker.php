<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use MaxMind\Db\Reader;

// =============================
// 1) INFORMATIONS DE BASE
// =============================
$ip        = $_SERVER['REMOTE_ADDR'] ?? '';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$page      = $_SERVER['REQUEST_URI'] ?? '';
$referer   = $_SERVER['HTTP_REFERER'] ?? '';

// =============================
// 2) DEVICE / BROWSER / OS
// =============================
function detectDevice($ua){
    if (preg_match('/mobile|android|iphone|ipad/i', $ua)) return "Mobile";
    return "Desktop";
}

function detectBrowser($ua){
    if (stripos($ua, 'Chrome') !== false) return "Chrome";
    if (stripos($ua, 'Firefox') !== false) return "Firefox";
    if (stripos($ua, 'Safari') !== false) return "Safari";
    if (stripos($ua, 'Edge') !== false) return "Edge";
    return "Autre";
}

function detectOS($ua){
    if (stripos($ua, 'Windows') !== false) return "Windows";
    if (stripos($ua, 'Mac') !== false) return "MacOS";
    if (stripos($ua, 'Linux') !== false) return "Linux";
    if (stripos($ua, 'Android') !== false) return "Android";
    if (stripos($ua, 'iPhone') !== false) return "iOS";
    return "Autre";
}

$device  = detectDevice($userAgent);
$browser = detectBrowser($userAgent);
$os      = detectOS($userAgent);

// =============================
// 3) GEOLOCALISATION MaxMind
// =============================
$continent = "";
$country   = "";
$city      = "";
$lat       = "";
$lon       = "";

try {
    $reader = new Reader(__DIR__ . "/GeoLite2-City.mmdb");
    $geo = $reader->get($ip);

    if ($geo) {
        $continent = $geo['continent']['names']['fr'] ?? '';
        $country   = $geo['country']['names']['fr'] ?? '';
        $city      = $geo['city']['names']['fr'] ?? '';
        $lat       = $geo['location']['latitude'] ?? '';
        $lon       = $geo['location']['longitude'] ?? '';
    }

    $reader->close();
} catch (Exception $e) {
    // En cas d’erreur MaxMind, on n’arrête pas le site
}

// =============================
// 4) INSERT BDD
// ⚠️ COLONNES EXACTES DE TA TABLE
// =============================
$insert = $pdo->prepare("
    INSERT INTO visits (
        ip, user_agent, page, referer, device, browser, os,
        country, city, latitude, longitude, continent
    )
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
");

$insert->execute([
    $ip,
    $userAgent,
    $page,
    $referer,
    $device,
    $browser,
    $os,
    $country,
    $city,
    $lat,
    $lon,
    $continent
]);

?>
