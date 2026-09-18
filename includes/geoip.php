<?php
// =============================================
// LECTEUR GEOIP POUR IBIG IMMO TRUST
// =============================================

require_once __DIR__ . '/geoip2.phar';

use GeoIp2\Database\Reader;

function getGeoData($ip)
{
    $dbPath = __DIR__ . "/GeoLite2-City.mmdb";

    if (!file_exists($dbPath)) {
        return ['country' => 'Unknown', 'city' => 'Unknown'];
    }

    try {
        $reader = new Reader($dbPath);
        $record = $reader->city($ip);

        return [
            'country' => $record->country->name ?? 'Unknown',
            'city'    => $record->city->name ?? 'Unknown'
        ];
    } catch (Exception $e) {
        return ['country' => 'Unknown', 'city' => 'Unknown'];
    }
}
