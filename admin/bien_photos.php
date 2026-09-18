<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) die("Accès refusé");
include __DIR__ . '/menu.php';

$id = (int)($_GET['id'] ?? 0);
if($id<=0) die("ID invalide");

$st = $pdo->prepare("SELECT * FROM immo_biens WHERE id=?");
$st->execute([$id]);
$bien = $st->fetch(PDO::FETCH_ASSOC);
if(!$bien) die("Bien introuvable");

$images = $bien['images'] ? json_decode($bien['images'], true) : [];
if(!is_array($images)) $images = [];

if($_SERVER['REQUEST_METHOD']==='POST'){
    $uploadPaths = [];

    if(isset($_FILES['photos'])){
        foreach($_FILES['photos']['tmp_name'] as $i => $tmp){
            if(!empty($_FILES['photos']['name'][$i])){
                $folder = '../uploads/';
                if(!is_dir($folder)) mkdir($folder,0777,true);
                $filename = time().'_'.$i.'_'.basename($_FILES['photos']['name'][$i]);
                $target = $folder.$filename;
                if(move_uploaded_file($tmp,$target)){
                    $uploadPaths[] = 'uploads/'.$filename;
                }
            }
        }
    }

    $newSet = array_merge($images, $uploadPaths);
    $st = $pdo->prepare("UPDATE immo_biens SET images=? WHERE id=?");
    $st->execute([json_encode($newSet),$id]);

    header("Location: bien_photos.php?id=".$id);
    exit;
}
?>

<div class="container" style="margin-top:20px;">
  <h1 style="font-size:22px;color:#003c96;font-weight:800;margin-bottom:10px;">
     Photos du bien #<?= $bien['id'] ?> – <?= htmlspecialchars($bien['titre']) ?>
  </h1>

  <form method="post" enctype="multipart/form-data" style="background:#fff;padding:18px 20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.06);margin-bottom:20px;">
    <label>Ajouter des photos (multiples)</label><br>
    <input type="file" name="photos[]" multiple accept="image/*">
    <button class="btn-main" type="submit" style="margin-top:10px;">Uploader</button>
  </form>

  <h3 style="margin-bottom:10px;">Photos existantes :</h3>

  <div style="display:flex;flex-wrap:wrap;gap:15px;">
    <?php foreach($images as $img): ?>
      <div>
        <img src="../<?= htmlspecialchars($img) ?>" width="150" style="border-radius:10px;box-shadow:0 3px 10px rgba(0,0,0,0.2);">
      </div>
    <?php endforeach; ?>
    <?php if(empty($images)): ?>
      <p style="color:#6b7280;">Aucune photo secondaire pour l’instant.</p>
    <?php endif; ?>
  </div>
</div>
