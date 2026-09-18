<?php
session_start();
require_once __DIR__ . '/../includes/config.php';
if (!isset($_SESSION['admin'])) die("Accès refusé");
include __DIR__ . '/menu.php';

function slugify($text){
    $text = iconv('UTF-8','ASCII//TRANSLIT',$text);
    $text = preg_replace('~[^\\pL\\d]+~u','-',$text);
    $text = trim($text,'-');
    $text = strtolower($text);
    $text = preg_replace('~[^-a-z0-9]+~','',$text);
    if (empty($text)) { return 'bien-'.time(); }
    return $text;
}

$msg = '';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $titre       = trim($_POST['titre'] ?? '');
    $type_bien   = $_POST['type_bien'] ?? '';
    $statut      = $_POST['statut'] ?? 'A vendre';
    $prix        = $_POST['prix'] ?? '';
    $ville       = $_POST['ville'] ?? '';
    $quartier    = $_POST['quartier'] ?? '';
    $surface     = $_POST['surface'] ?? '';
    $chambres    = (int)($_POST['chambres'] ?? 0);
    $description = $_POST['description'] ?? '';

    if($titre!==''){
        $slug = slugify($titre);

        // image principale
        $imgPath = null;
        if(!empty($_FILES['image_principale']['name'])){
            $folder = '../uploads/';
            if(!is_dir($folder)) mkdir($folder,0777,true);
            $filename = time().'_'.basename($_FILES['image_principale']['name']);
            $target = $folder.$filename;
            if(move_uploaded_file($_FILES['image_principale']['tmp_name'],$target)){
                $imgPath = 'uploads/'.$filename;
            }
        }

        $st = $pdo->prepare("INSERT INTO immo_biens
          (titre, slug, description, type_bien, statut, prix, ville, quartier, surface, chambres, image_principale)
          VALUES(?,?,?,?,?,?,?,?,?,?,?)");
        $st->execute([
          $titre,$slug,$description,$type_bien,$statut,$prix,$ville,$quartier,$surface,$chambres,$imgPath
        ]);

        header("Location: biens.php?msg=".urlencode("Bien ajouté avec succès."));
        exit;
    } else {
        $msg = "Le titre est obligatoire.";
    }
}
?>

<div class="container" style="margin-top:20px;max-width:900px;">
  <h1 style="font-size:22px;color:#003c96;font-weight:800;margin-bottom:10px;">
     Nouveau bien immobilier
  </h1>

  <?php if($msg): ?>
    <div style="background:#fef2f2;border:1px solid #ef4444;color:#991b1b;padding:8px 12px;border-radius:8px;font-size:13px;margin-bottom:10px;">
      <?= htmlspecialchars($msg) ?>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" style="background:#fff;padding:18px 20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.06);">

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
      <div>
        <label>Titre du bien *</label>
        <input type="text" name="titre" required style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Type de bien</label>
        <select name="type_bien" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
          <option value="">-- Choisir --</option>
          <option>Terrain</option>
          <option>Maison</option>
          <option>Appartement</option>
          <option>Villa</option>
          <option>Immeuble</option>
          <option>Bureau</option>
          <option>Local commercial</option>
        </select>
      </div>

      <div>
        <label>Statut</label>
        <select name="statut" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
          <option>A vendre</option>
          <option>A louer</option>
          <option>Réservé</option>
          <option>Vendu</option>
          <option>Loué</option>
          <option>Archivé</option>
        </select>
      </div>

      <div>
        <label>Prix</label>
        <input type="text" name="prix" placeholder="ex : 80 000 000 FCFA" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Ville</label>
        <input type="text" name="ville" placeholder="Abidjan" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Quartier</label>
        <input type="text" name="quartier" placeholder="Cocody, Riviera, etc." style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Surface</label>
        <input type="text" name="surface" placeholder="ex : 500 m²" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Chambres</label>
        <input type="number" name="chambres" min="0" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
    </div>

    <div style="margin-top:14px;">
      <label>Description</label>
      <textarea name="description" rows="5" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;"></textarea>
    </div>

    <div style="margin-top:14px;">
      <label>Image principale</label><br>
      <input type="file" name="image_principale" accept="image/*">
    </div>

    <div style="margin-top:18px;text-align:right;">
      <button class="btn-main" type="submit">Enregistrer le bien</button>
    </div>

  </form>
</div>
