<?php
declare(strict_types=1);

/* ================================
   CONNEXION BASE DE DONNÉES
================================ */

$db_host = '127.0.0.1';
$db_name = 'ibigs2689720_45vjj';
$db_user = 'ibigs2689720_45vjj';
$db_pass = 'eP2-Q9Rt2D-RF9n';

try {

$pdo = new PDO(
"mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
$db_user,
$db_pass,
[
PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]
);

} catch (PDOException $e) {

http_response_code(500);
exit;

}

/* ================================
   HEADERS XML
================================ */

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: index, follow');

/* ================================
   BASE URL
================================ */

$base = 'https://ibigimmotrust.com';

/* ================================
   FONCTION XML SAFE
================================ */

function exml(string $s): string
{
return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
}

/* ================================
   DÉBUT XML
================================ */

echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

<!-- ACCUEIL -->
<url>
<loc><?= exml($base) ?>/</loc>
<changefreq>daily</changefreq>
<priority>1.0</priority>
</url>

<!-- CATALOGUE -->
<url>
<loc><?= exml($base) ?>/tous-les-biens</loc>
<changefreq>daily</changefreq>
<priority>0.9</priority>
</url>

<!-- CONTACT -->
<url>
<loc><?= exml($base) ?>/contact</loc>
<changefreq>monthly</changefreq>
<priority>0.7</priority>
</url>

<!-- SERVICES -->
<url>
<loc><?= exml($base) ?>/services</loc>
<changefreq>monthly</changefreq>
<priority>0.7</priority>
</url>

<!-- ESTIMATION -->
<url>
<loc><?= exml($base) ?>/estimation</loc>
<changefreq>monthly</changefreq>
<priority>0.7</priority>
</url>

<?php

/* ================================
   BIENS IMMOBILIERS DYNAMIQUES
================================ */

$sql = "
SELECT DISTINCT slug, updated_at
FROM immo_biens
WHERE visible = 1
AND statut_publication = 'valide'
AND slug IS NOT NULL
AND slug <> ''
ORDER BY updated_at DESC
";

$stmt = $pdo->query($sql);

while ($b = $stmt->fetch()) {

$loc = $base . '/bien/' . $b['slug'];

$lastmod = !empty($b['updated_at'])
? date('Y-m-d', strtotime($b['updated_at']))
: date('Y-m-d');

?>

<url>
<loc><?= exml($loc) ?></loc>
<lastmod><?= $lastmod ?></lastmod>
<changefreq>weekly</changefreq>
<priority>0.8</priority>
</url>

<?php } ?>

</urlset>