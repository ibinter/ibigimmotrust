<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use MaxMind\Db\Reader;

$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$page = $_SERVER['REQUEST_URI'] ?? '';
$referer = $_SERVER['HTTP_REFERER'] ?? '';
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Device simple
$device = (preg_match('/mobile/i', $ua) ? 'Mobile' : 'Desktop');

// Browser
$browser = 'Unknown';
if (preg_match('/Chrome/i', $ua)) $browser = 'Chrome';
elseif (preg_match('/Firefox/i', $ua)) $browser = 'Firefox';
elseif (preg_match('/Safari/i', $ua)) $browser = 'Safari';
elseif (preg_match('/Edge/i', $ua)) $browser = 'Edge';

// OS
$os = 'Unknown';
if (preg_match('/Windows/i', $ua)) $os = 'Windows';
elseif (preg_match('/Macintosh/i', $ua)) $os = 'MacOS';
elseif (preg_match('/Android/i', $ua)) $os = 'Android';
elseif (preg_match('/iPhone|iPad/i', $ua)) $os = 'iOS';
elseif (preg_match('/Linux/i', $ua)) $os = 'Linux';

// GEO-IP
$country = '';
$city = '';
$continent = '';
$lat = null;
$lon = null;

try {
    $reader = new Reader(__DIR__ . '/GeoLite2-City.mmdb');
    $record = $reader->get($ip);

    if ($record) {
        $country = $record['country']['names']['fr'] ?? '';
        $city = $record['city']['names']['fr'] ?? '';
        $continent = $record['continent']['names']['fr'] ?? '';
        $lat = $record['location']['latitude'] ?? null;
        $lon = $record['location']['longitude'] ?? null;
    }
    $reader->close();
} catch (Exception $e) {
    // Silent mode (pas d'erreur affichée)
}

// INSERT
$st = $pdo->prepare("
    INSERT INTO visits(ip, user_agent, page, referer, device, browser, os, country, city, continent, lat, lon)
    VALUES(?,?,?,?,?,?,?,?,?,?,?,?)
");

$st->execute([$ip, $ua, $page, $referer, $device, $browser, $os, $country, $city, $continent, $lat, $lon]);
