<?php
require_once __DIR__.'/includes/config.php';

$pageTitle="Recherche de biens – IBIG IMMO TRUST";
include __DIR__.'/includes/header.php';

function e($v){
return htmlspecialchars((string)$v, ENT_QUOTES,'UTF-8');
}

/* =========================
RECUPERATION FILTRES
========================= */

$transaction = $_GET['transaction'] ?? '';
$ville       = $_GET['ville'] ?? '';
$type        = $_GET['type'] ?? '';
$budget      = (int)($_GET['budget'] ?? 0);

/* =========================
REQUETE
========================= */

$sql = "
SELECT b.*,
(
SELECT p.fichier
FROM immo_bien_photos p
WHERE p.bien_id = b.id
ORDER BY p.is_principale DESC, p.sort_order ASC
LIMIT 1
) AS photo
FROM immo_biens b
WHERE b.visible = 1
";

$params=[];

if($transaction){
$sql.=" AND b.transaction=?";
$params[]=$transaction;
}

if($ville){
$sql.=" AND b.ville=?";
$params[]=$ville;
}

if($type){
$sql.=" AND b.type=?";
$params[]=$type;
}

if($budget){
$sql.=" AND b.prix <= ?";
$params[]=$budget;
}

$sql.=" ORDER BY b.created_at DESC";

$stmt=$pdo->prepare($sql);
$stmt->execute($params);

$biens=$stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>

/* =========================
PAGE RECHERCHE
========================= */

.search-container{

max-width:1200px;
margin:auto;
padding:40px 20px;

}

.search-title{

font-size:36px;
font-weight:900;
margin-bottom:30px;
color:#0f2044;

}

/* =========================
GRID BIENS
========================= */

.biens-grid{

display:grid;

grid-template-columns:repeat(auto-fit,minmax(280px,1fr));

gap:25px;

}

/* =========================
CARTE BIEN
========================= */

.card-bien{

background:#fff;

border-radius:14px;

overflow:hidden;

box-shadow:0 8px 20px rgba(0,0,0,0.08);

transition:0.25s;

}

.card-bien:hover{

transform:translateY(-5px);

box-shadow:0 12px 25px rgba(0,0,0,0.12);

}

/* IMAGE */

.bien-image{

position:relative;

height:200px;

overflow:hidden;

}

.bien-image img{

width:100%;

height:100%;

object-fit:cover;

}

/* BADGE */

.badge-transaction{

position:absolute;

top:12px;

left:12px;

background:#D4AF37;

color:#fff;

padding:4px 10px;

font-size:12px;

font-weight:800;

border-radius:6px;

}

/* CONTENU */

.bien-content{

padding:18px;

}

.bien-title{

font-size:18px;

font-weight:800;

margin-bottom:8px;

color:#0d3f91;

}

.bien-ville{

font-size:14px;

color:#6b7280;

margin-bottom:8px;

}

.bien-prix{

font-size:22px;

font-weight:900;

color:#0d3f91;

margin-bottom:14px;

}

.btn-voir{

display:inline-block;

background:#D4AF37;

color:#fff;

padding:9px 16px;

border-radius:8px;

font-weight:700;

font-size:14px;

}

.btn-voir:hover{

background:#c4040f;

}

/* NO RESULT */

.no-result{

font-size:18px;

color:#6b7280;

}

</style>

<div class="search-container">

<h1 class="search-title">

Résultats de recherche

</h1>

<?php if(!$biens): ?>

<p class="no-result">

Aucun bien trouvé avec ces critères.

</p>

<?php else: ?>

<div class="biens-grid">

<?php foreach($biens as $b): ?>

<div class="card-bien">

<div class="bien-image">

<?php if(!empty($b['photo'])): ?>

<img 
src="<?= BASE_URL ?>/<?= e($b['photo']) ?>" 
alt="<?= e($b['titre']) ?>" 
loading="lazy"
>

<?php else: ?>

<img 
src="<?= BASE_URL ?>/assets/img/no-image.jpg" 
alt="Image non disponible"
loading="lazy"
>

<?php endif; ?>

<span class="badge-transaction">

<?= ucfirst(e($b['transaction'] ?? '')) ?>

</span>

</div>

<div class="bien-content">

<div class="bien-title">

<?= e($b['titre']) ?>

</div>

<div class="bien-ville">

&#128205; <?= e($b['ville']) ?>

</div>

<div class="bien-prix">

<?= number_format($b['prix'],0,',',' ') ?> FCFA

</div>

<a href="<?= BASE_URL ?>/bien/<?= e($b['slug']) ?>" class="btn-voir">

Voir le bien

</a>

</div>

</div>

<?php endforeach ?>

</div>

<?php endif ?>

</div>

<?php include __DIR__.'/includes/footer.php'; ?>