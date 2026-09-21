<?php
session_start();

require_once __DIR__ . '/includes/config.php';

/* =============================
VERIFIER UTILISATEUR CONNECTE
============================= */

if(!isset($_SESSION['user_id'])){
header("Location: /compte/connexion.php?redirect=publier-bien");
exit;
}

$user_id = (int) $_SESSION['user_id'];

$pageTitle = "Publier un bien – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';

function e($v){
return htmlspecialchars((string)$v, ENT_QUOTES,'UTF-8');
}

$message = "";

/* =============================
TRAITEMENT FORMULAIRE
============================= */

if(isset($_POST['submit_bien'])){

$titre = trim($_POST['titre'] ?? '');
$type = trim($_POST['type'] ?? '');
$transaction = trim($_POST['transaction'] ?? '');
$ville = trim($_POST['ville'] ?? '');
$quartier = trim($_POST['quartier'] ?? '');
$prix = (int)($_POST['prix'] ?? 0);
$description = trim($_POST['description'] ?? '');
$telephone = trim($_POST['telephone'] ?? '');

if($titre && $type){

$slug = strtolower(preg_replace('/[^a-z0-9]+/','-', $titre))."-".time();

/* =============================
INSERT BIEN
============================= */

$sql="INSERT INTO immo_biens
(
user_id,
titre,
slug,
type,
transaction,
ville,
quartier,
prix,
description,
telephone_contact,
niveau_visibilite,
statut_publication,
visible,
created_at
)
VALUES
(
:user_id,
:titre,
:slug,
:type,
:transaction,
:ville,
:quartier,
:prix,
:description,
:telephone,
'libre',
'attente',
0,
NOW()
)";

$stmt=$pdo->prepare($sql);

$stmt->execute([
':user_id'=>$user_id,
':titre'=>$titre,
':slug'=>$slug,
':type'=>$type,
':transaction'=>$transaction,
':ville'=>$ville,
':quartier'=>$quartier,
':prix'=>$prix,
':description'=>$description,
':telephone'=>$telephone
]);

$bien_id = $pdo->lastInsertId();

/* =============================
UPLOAD PHOTOS
============================= */

if(!empty($_FILES['photos']['name'][0])){

$allowed = ['jpg','jpeg','png','webp'];

$dir = __DIR__."/uploads/biens/";

if(!is_dir($dir)){
mkdir($dir,0777,true);
}

$total = count($_FILES['photos']['name']);

if($total > 10){
$total = 10;
}

for($i=0;$i<$total;$i++){

$tmp = $_FILES['photos']['tmp_name'][$i];

if(!$tmp) continue;

$ext = strtolower(pathinfo($_FILES['photos']['name'][$i],PATHINFO_EXTENSION));

if(!in_array($ext,$allowed)) continue;

$filename = uniqid().".".$ext;

$path = $dir.$filename;

if(move_uploaded_file($tmp,$path)){

$pdo->prepare("
INSERT INTO immo_bien_photos
(bien_id,fichier)
VALUES (?,?)
")->execute([$bien_id,"uploads/biens/".$filename]);

}

}

}

$message="Votre bien a été envoyé. Validation en cours.";

}

}
?>

<style>

.publish-wrapper{
max-width:950px;
margin:auto;
padding:40px 20px;
}

.publish-title{
font-size:32px;
font-weight:900;
margin-bottom:10px;
color:#0f2044;
}

.publish-desc{
color:#6b7280;
margin-bottom:30px;
}

.publish-form{
background:#fff;
padding:30px;
border-radius:14px;
box-shadow:0 10px 35px rgba(0,0,0,.08);
}

.publish-grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:18px;
}

.publish-grid-full{
grid-column:1/3;
}

.publish-form label{
font-weight:700;
font-size:14px;
display:block;
margin-bottom:6px;
}

.publish-form input,
.publish-form select,
.publish-form textarea{
width:100%;
padding:12px;
border-radius:8px;
border:1px solid #d1d5db;
background:#f9fafb;
}

.publish-form textarea{
min-height:120px;
resize:vertical;
}

.publish-btn{
margin-top:25px;
background:#0A1628;
color:#fff;
border:none;
padding:14px 26px;
border-radius:10px;
font-weight:900;
cursor:pointer;
font-size:16px;
}

.publish-btn:hover{
background:#0f2044;
}

.publish-success{
background:#d1fae5;
color:#065f46;
padding:14px;
border-radius:8px;
margin-bottom:20px;
font-weight:700;
}

.photo-help{
display:block;
font-size:12px;
color:#6b7280;
margin-top:6px;
}

.photo-preview{
display:grid;
grid-template-columns:repeat(auto-fill,minmax(100px,1fr));
gap:10px;
margin-top:12px;
}

.photo-preview img{
width:100%;
height:90px;
object-fit:cover;
border-radius:6px;
}

@media(max-width:700px){

.publish-grid{
grid-template-columns:1fr;
}

.publish-grid-full{
grid-column:auto;
}

}

</style>

<section class="publish-wrapper">

<h1 class="publish-title">
Publier un bien
</h1>

<p class="publish-desc">
Ajoutez votre bien immobilier sur IBIG IMMO TRUST.
</p>

<?php if($message): ?>
<div class="publish-success">
<?= e($message) ?>
</div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data" class="publish-form">

<div class="publish-grid">

<div>
<label>Titre du bien</label>
<input type="text" name="titre" required>
</div>

<div>
<label>Type de bien</label>
<select name="type" required>
<option value="">Choisir</option>
<option value="villa">Villa</option>
<option value="maison">Maison</option>
<option value="appartement">Appartement</option>
<option value="terrain">Terrain</option>
<option value="bureau">Bureau</option>
</select>
</div>

<div>
<label>Transaction</label>
<select name="transaction">
<option value="location">Location</option>
<option value="vente">Vente</option>
</select>
</div>

<div>
<label>Prix (FCFA)</label>
<input type="number" name="prix">
</div>

<div>
<label>Ville</label>
<input type="text" name="ville">
</div>

<div>
<label>Quartier</label>
<input type="text" name="quartier">
</div>

<div class="publish-grid-full">
<label>Description</label>
<textarea name="description"></textarea>
</div>

<div>
<label>Téléphone</label>
<input type="text" name="telephone" required>
</div>

<div class="publish-grid-full">

<label>Photos du bien</label>

<input
type="file"
name="photos[]"
multiple
accept="image/jpeg,image/png,image/webp"
id="photosInput"
>

<small class="photo-help">
Maximum 10 photos
</small>

<div id="previewPhotos" class="photo-preview"></div>

</div>

</div>

<button type="submit" name="submit_bien" class="publish-btn">
Publier mon bien
</button>

</form>

</section>

<script>

const input = document.getElementById("photosInput");
const preview = document.getElementById("previewPhotos");

input.addEventListener("change", function(){

preview.innerHTML="";

const files = this.files;

if(files.length > 10){
alert("Maximum 10 photos autorisées");
input.value="";
return;
}

for(let i=0;i<files.length;i++){

const reader = new FileReader();

reader.onload = function(e){

const img = document.createElement("img");
img.src = e.target.result;

preview.appendChild(img);

};

reader.readAsDataURL(files[i]);

}

});

</script>

<?php include __DIR__ . '/includes/footer.php'; ?>