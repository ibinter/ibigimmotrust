<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

include __DIR__ . '/menu.php';
?>
<link rel="stylesheet" href="admin.css">
<?php

// -----------------------------------------------------------
// 1) Fonction utilitaire : génération du slug
// -----------------------------------------------------------
function slugify($text){
    $text = iconv('UTF-8','ASCII//TRANSLIT',$text);
    $text = preg_replace('~[^\\pL\\d]+~u','-',$text);
    $text = trim($text,'-');
    $text = strtolower($text);
    $text = preg_replace('~[^-a-z0-9]+~','',$text);
    return $text ?: 'bien-'.time();
}

/* -----------------------------------------------------------
   2) Détection si on modifie un bien ou si on en crée un
----------------------------------------------------------- */
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$editMode = $id > 0;

// Valeurs par défaut
$bien = [
    'titre' => '',
    'type' => '',
    'transaction' => 'A vendre',
    'ville' => '',
    'quartier' => '',
    'quartier_type' => '',
    'prix' => '',
    'prix_euro' => '',
    'prix_usd' => '',
    'superficie' => '',
    'superficie_terrain' => '',
    'superficie_habitable' => '',
    'chambres' => '',
    'salles_bain' => '',
    'salons' => '',
    'cuisines' => '',
    'niveaux' => '',
    'annee_construction' => '',
    'etat_general' => '',
    'titre_foncier' => '',
    'disponibilite' => '',
    'description' => '',
    'conditions_paiement' => '',
    'url_video' => '',
    'nom_proprietaire' => '',
    'tel_proprietaire' => '',
    'mandat_signee' => 0,
    'visible' => 1,
    'en_vedette' => 0,
    'badge' => '',
    'latitude' => '',
    'longitude' => '',
    'distance_route' => '',
    'distance_ecole' => '',
    'distance_commerces' => '',
    'distance_transport' => '',
    'image_principale' => '',
];

/* Caractéristiques booléennes */
$caracs = [
    'climatisation','chauffe_eau','placards','dressing','cuisine_equipee',
    'fibre','securite','buanderie','balcon','piscine','jardin','parking',
    'garage','dependance','cour','forage','groupe_electrogene','cloture_securisee'
];

foreach($caracs as $c){
    $bien[$c] = 0;
}

/* -----------------------------------------------------------
   3) Chargement d’un bien existant si editMode = true
----------------------------------------------------------- */
if ($editMode) {
    $st = $pdo->prepare("SELECT * FROM immo_biens WHERE id = ?");
    $st->execute([$id]);
    $row = $st->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        die("Bien introuvable.");
    }
    foreach ($row as $k => $v) {
        $bien[$k] = $v;
    }
}

/* -----------------------------------------------------------
   4) Détection INTELLIGENTE du type de bien
----------------------------------------------------------- */
/*
Types supportés :
- Terrain
- Maison
- Appartement
- Villa
- Immeuble
- Bureau
- Local commercial
*/
$isTerrain = strtolower($bien['type']) === 'terrain';

?>
<?php
// Message éventuel d’erreur (titre vide, etc.)
$msg = $msg ?? '';
?>

<div class="container" style="margin-top:20px;max-width:1100px;">
  <h1 style="font-size:22px;color:#003c96;font-weight:800;margin-bottom:10px;">
    <?= $editMode ? "Modifier un bien" : "Nouveau bien immobilier" ?>
  </h1>

  <?php if($msg): ?>
    <div style="background:#fef2f2;border:1px solid #ef4444;color:#991b1b;padding:8px 12px;border-radius:8px;font-size:13px;margin-bottom:10px;">
      <?= htmlspecialchars($msg) ?>
    </div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" style="background:#fff;padding:18px 20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,0.06);">

    <!-- SECTION 1 : Infos générales -->
    <h2 style="font-size:16px;font-weight:700;margin-bottom:10px;">Informations générales</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-bottom:18px;">
      <div>
        <label>Titre du bien *</label>
        <input type="text" name="titre" value="<?= htmlspecialchars($bien['titre']) ?>" required style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Type de bien</label>
        <select name="type" id="type_bien" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
          <?php
          $types = ["","Terrain","Maison","Appartement","Villa","Immeuble","Bureau","Local commercial"];
          foreach($types as $t){
              $sel = ($bien['type'] === $t) ? 'selected' : '';
              $label = $t === "" ? "-- Choisir --" : $t;
              echo "<option value=\"$t\" $sel>$label</option>";
          }
          ?>
        </select>
      </div>

      <!-- TYPE DE TRANSACTION -->
<div>
    <label>Transaction</label>
    <select name="transaction" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
        <?php
        $transactions = ["A vendre","A louer"];
        foreach($transactions as $tr){
            $selected = ($bien['transaction'] ?? '') == $tr ? 'selected' : '';
            echo "<option value='$tr' $selected>$tr</option>";
        }
        ?>
    </select>
</div>

<!-- STATUT DU BIEN -->
<div>
    <label>Statut du bien</label>
    <select name="statut" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
        <?php 
        $statuts = ["Disponible","Réservé","En négociation","Loué","Vendu","Archivé"];
        foreach($statuts as $s){
            $selected = ($bien['statut'] ?? '') == $s ? "selected" : "";
            echo "<option value='$s' $selected>$s</option>";
        }
        ?>
    </select>
</div>

      <div>
        <label>Prix (FCFA)</label>
        <input type="text" name="prix" value="<?= htmlspecialchars($bien['prix']) ?>" placeholder="ex : 80 000 000" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Prix approximatif (€)</label>
        <input type="text" name="prix_euro" value="<?= htmlspecialchars($bien['prix_euro']) ?>" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Prix approximatif ($)</label>
        <input type="text" name="prix_usd" value="<?= htmlspecialchars($bien['prix_usd']) ?>" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Ville</label>
        <input type="text" name="ville" value="<?= htmlspecialchars($bien['ville']) ?>" placeholder="Abidjan" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Quartier</label>
        <input type="text" name="quartier" value="<?= htmlspecialchars($bien['quartier']) ?>" placeholder="Cocody, Riviera..." style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>

      <div>
        <label>Type de zone</label>
        <input type="text" name="quartier_type" value="<?= htmlspecialchars($bien['quartier_type']) ?>" placeholder="Résidentiel, commercial..." style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
    </div>

    <!-- SECTION 2 : Surfaces & pièces -->
    <h2 style="font-size:16px;font-weight:700;margin-bottom:10px;">Surfaces & pièces</h2>
    <div id="section_surfaces" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:18px;">
      <div>
        <label>Surface bâtie</label>
        <input type="text" name="superficie" value="<?= htmlspecialchars($bien['superficie']) ?>" placeholder="ex : 200 m²" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div>
        <label>Superficie terrain</label>
        <input type="text" name="superficie_terrain" value="<?= htmlspecialchars($bien['superficie_terrain']) ?>" placeholder="ex : 500 m²" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div class="bloc_habitation">
        <label>Superficie habitable</label>
        <input type="text" name="superficie_habitable" value="<?= htmlspecialchars($bien['superficie_habitable']) ?>" placeholder="ex : 180 m²" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div class="bloc_habitation">
        <label>Chambres</label>
        <input type="number" name="chambres" value="<?= htmlspecialchars($bien['chambres']) ?>" min="0" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div class="bloc_habitation">
        <label>Salles de bain</label>
        <input type="number" name="salles_bain" value="<?= htmlspecialchars($bien['salles_bain']) ?>" min="0" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div class="bloc_habitation">
        <label>Salons</label>
        <input type="number" name="salons" value="<?= htmlspecialchars($bien['salons']) ?>" min="0" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div class="bloc_habitation">
        <label>Cuisines</label>
        <input type="number" name="cuisines" value="<?= htmlspecialchars($bien['cuisines']) ?>" min="0" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div class="bloc_habitation">
        <label>Niveaux (R+1, R+2...)</label>
        <input type="text" name="niveaux" value="<?= htmlspecialchars($bien['niveaux']) ?>" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div>
        <label>Année de construction</label>
        <input type="text" name="annee_construction" value="<?= htmlspecialchars($bien['annee_construction']) ?>" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div>
        <label>État général</label>
        <select name="etat_general" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
          <option value="">-- Choisir --</option>
          <?php
          $etats = ["Neuf","Bon état","À rénover","Inachevé"];
          foreach($etats as $e){
            $sel = ($bien['etat_general'] === $e) ? 'selected' : '';
            echo "<option $sel>$e</option>";
          }
          ?>
        </select>
      </div>
      <div>
        <label>Documents fonciers</label>
        <input type="text" name="titre_foncier" value="<?= htmlspecialchars($bien['titre_foncier']) ?>" placeholder="ACD, attestation villageoise..." style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div>
        <label>Disponibilité</label>
        <input type="text" name="disponibilite" value="<?= htmlspecialchars($bien['disponibilite']) ?>" placeholder="Immédiate, sous 3 mois..." style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
    </div>

    <!-- SECTION 3 : Caractéristiques -->
    <h2 style="font-size:16px;font-weight:700;margin-bottom:10px;">Caractéristiques</h2>
    <div id="section_caracteristiques" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:6px;margin-bottom:18px;font-size:13px;">
      <?php
      $labelsCaracs = [
        'climatisation'       => "Climatisation",
        'chauffe_eau'         => "Chauffe-eau",
        'placards'            => "Placards",
        'dressing'            => "Dressing",
        'cuisine_equipee'     => "Cuisine équipée",
        'fibre'               => "Fibre internet",
        'securite'            => "Sécurité / Gardien",
        'buanderie'           => "Buanderie",
        'balcon'              => "Balcon / Terrasse",
        'piscine'             => "Piscine",
        'jardin'              => "Jardin",
        'parking'             => "Parking",
        'garage'              => "Garage",
        'dependance'          => "Dépendance",
        'cour'                => "Cour",
        'forage'              => "Forage / puits",
        'groupe_electrogene'  => "Groupe électrogène",
        'cloture_securisee'   => "Clôture sécurisée"
      ];
      foreach($labelsCaracs as $name => $label){
        $checked = !empty($bien[$name]) ? 'checked' : '';
        echo '<label style="display:flex;align-items:center;gap:6px;"><input type="checkbox" name="'.$name.'" '.$checked.'> '.$label.'</label>';
      }
      ?>
    </div>
    <!-- SECTION 4 : Localisation & accès -->
    <h2 style="font-size:16px;font-weight:700;margin-bottom:10px;">Localisation & accès</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-bottom:18px;">
      <div>
        <label>Distance route / asphalte</label>
        <input type="text" name="distance_route" value="<?= htmlspecialchars($bien['distance_route']) ?>" placeholder="ex : 200 m de l'asphalte" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div>
        <label>Proximité écoles</label>
        <input type="text" name="distance_ecole" value="<?= htmlspecialchars($bien['distance_ecole']) ?>" placeholder="ex : 5 min" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div>
        <label>Proximité commerces</label>
        <input type="text" name="distance_commerces" value="<?= htmlspecialchars($bien['distance_commerces']) ?>" placeholder="marché, supérette..." style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div>
        <label>Transports</label>
        <input type="text" name="distance_transport" value="<?= htmlspecialchars($bien['distance_transport']) ?>" placeholder="2 min de la gare bus" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div>
        <label>Latitude (GPS)</label>
        <input type="text" name="latitude" value="<?= htmlspecialchars($bien['latitude']) ?>" placeholder="ex : 5.3456789" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div>
        <label>Longitude (GPS)</label>
        <input type="text" name="longitude" value="<?= htmlspecialchars($bien['longitude']) ?>" placeholder="-4.0123456" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
    </div>

    <!-- SECTION 5 : Propriétaire interne IBIG -->
    <h2 style="font-size:16px;font-weight:700;margin-bottom:10px;">Propriétaire (interne IBIG)</h2>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:14px;margin-bottom:18px;">
      <div>
        <label>Nom du propriétaire</label>
        <input type="text" name="nom_proprietaire" value="<?= htmlspecialchars($bien['nom_proprietaire']) ?>" placeholder="Informations internes IBIG" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div>
        <label>Téléphone propriétaire</label>
        <input type="text" name="tel_proprietaire" value="<?= htmlspecialchars($bien['tel_proprietaire']) ?>" placeholder="Ne sera jamais affiché au public" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
      </div>
      <div style="display:flex;align-items:center;gap:8px;margin-top:22px;">
        <input type="checkbox" name="mandat_signee" <?= $bien['mandat_signee'] ? 'checked' : '' ?>>
        <label>Mandat signé / exclusif IBIG</label>
      </div>
    </div>

    <!-- SECTION 6 : Conditions financières -->
    <h2 style="font-size:16px;font-weight:700;margin-bottom:10px;">Conditions financières</h2>
    <div style="margin-bottom:18px;">
      <textarea name="conditions_paiement" rows="3" style="width:100%;padding:10px;border-radius:6px;border:1px solid #d1d5db;" placeholder="Paiement banque, diaspora, cash, échelonné..."><?= htmlspecialchars($bien['conditions_paiement']) ?></textarea>
    </div>

    <!-- SECTION 7 : Description détaillée -->
    <h2 style="font-size:16px;font-weight:700;margin-bottom:10px;">Description détaillée</h2>
    <div style="margin-bottom:18px;">
      <textarea name="description" rows="6" style="width:100%;padding:10px;border-radius:6px;border:1px solid #d1d5db;"><?= htmlspecialchars($bien['description']) ?></textarea>
    </div>

    <!-- SECTION 8 : Médias -->
    <h2 style="font-size:16px;font-weight:700;margin-bottom:10px;">Médias</h2>

    <div style="margin-bottom:15px;">
      <label>Image principale</label><br>
      <?php if(!empty($bien['image_principale'])): ?>
        <img src="/<?= $bien['image_principale'] ?>" style="width:180px;margin-bottom:8px;border-radius:6px;"><br>
      <?php endif; ?>
      <input type="file" name="image_principale" accept="image/*">
    </div>

    <div style="margin-bottom:20px;">
      <label>Galerie photos (multiple)</label><br>
      <input type="file" name="images[]" accept="image/*" multiple>
      <p style="font-size:12px;color:#6b7280;">Vous pouvez sélectionner plusieurs images.</p>
    </div>

    <div>
      <label>Lien vidéo (YouTube, Drive...)</label>
      <input type="text" name="url_video" value="<?= htmlspecialchars($bien['url_video']) ?>" placeholder="URL vidéo" style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
    </div>

    <!-- SECTION 9 : Visibilité -->
    <h2 style="font-size:16px;font-weight:700;margin-bottom:10px;margin-top:25px;">Visibilité & badge</h2>

    <div style="display:flex;align-items:center;gap:16px;margin-bottom:14px;">
      <label><input type="checkbox" name="visible" <?= $bien['visible'] ? 'checked' : '' ?>> Visible sur le site</label>
      <label><input type="checkbox" name="en_vedette" <?= $bien['en_vedette'] ? 'checked' : '' ?>> Mettre en avant (home)</label>
    </div>

    <div style="margin-bottom:20px;">
      <label>Badge (optionnel)</label>
      <input type="text" name="badge" value="<?= htmlspecialchars($bien['badge']) ?>" placeholder="Nouveau, Urgent, Exclusivité..." style="width:100%;padding:8px 10px;border-radius:6px;border:1px solid #d1d5db;">
    </div>
    <!-- BOUTON ENREGISTRER -->
    <div style="margin-top:20px;text-align:right;">
      <button type="submit" class="btn-main" style=" background:#003c96;color:#fff;padding:10px 18px;border-radius:6px;border:none;font-size:15px;cursor:pointer;">
        Enregistrer le bien
      </button>
    </div>

  </form>
</div>

<?php
/* -----------------------------------------------------------
   BLOC SQL : ENREGISTREMENT DU BIEN (INSERT ou UPDATE)
----------------------------------------------------------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Nettoyage et normalisation
    foreach($_POST as $k => $v){
        $_POST[$k] = trim($v);
    }

    // Champs booléens (checkbox)
    $boolFields = [
      'climatisation','chauffe_eau','placards','dressing','cuisine_equipee','fibre',
      'securite','buanderie','balcon','piscine','jardin','parking','garage',
      'dependance','cour','forage','groupe_electrogene','cloture_securisee',
      'visible','en_vedette','mandat_signee'
    ];

    foreach($boolFields as $bf){
        $_POST[$bf] = isset($_POST[$bf]) ? 1 : 0;
    }

    // Générer le slug automatique depuis le titre
    $slug = slugify($_POST['titre']);

    // Upload image principale
    $imgPath = $bien['image_principale']; 
    if (!empty($_FILES['image_principale']['name'])) {
        $folder = __DIR__ . '/../uploads/';
        if(!is_dir($folder)) mkdir($folder,0777,true);
        $filename = time().'_'.basename($_FILES['image_principale']['name']);
        $target = $folder.$filename;
        if(move_uploaded_file($_FILES['image_principale']['tmp_name'],$target)){
            $imgPath = 'uploads/'.$filename;
        }
    }

    /* -----------------------------------------------------------
       CONSTRUCTION DE LA REQUÊTE SQL
    ----------------------------------------------------------- */
    $fields = [
      'titre','type','transaction','ville','quartier','quartier_type','prix','prix_euro','prix_usd',
      'superficie','superficie_terrain','superficie_habitable','chambres','salles_bain','salons',
      'cuisines','niveaux','annee_construction','etat_general','titre_foncier','disponibilite',
      'distance_route','distance_ecole','distance_commerces','distance_transport','latitude',
      'longitude','nom_proprietaire','tel_proprietaire','mandat_signee','conditions_paiement',
      'description','url_video','visible','en_vedette','badge'
    ];

    // Ajout des caractéristiques booléennes
    $fields = array_merge($fields, $boolFields);

    // Ajout image principale
    $fields[] = 'image_principale';

    $params = [];
    foreach($fields as $f){
        $params[$f] = $_POST[$f] ?? $bien[$f] ?? null;
    }
    $params['image_principale'] = $imgPath;

    // INSERT OU UPDATE ?
    if (!$editMode) {
        /* -------- INSERT -------- */
        $sql = "INSERT INTO immo_biens (slug," . implode(',', $fields) . ",date_ajout)
                VALUES (:slug," . implode(',', array_map(function($f){return ':'.$f;}, $fields)) . ",NOW())";

        $stmt = $pdo->prepare($sql);
        $params['slug'] = $slug;
        $stmt->execute($params);

        $newID = $pdo->lastInsertId();

    } else {
        /* -------- UPDATE -------- */
        $setParts = [];
        foreach($fields as $f){
            $setParts[] = "$f = :$f";
        }

        $sql = "UPDATE immo_biens SET " . implode(',', $setParts) . ", slug=:slug WHERE id=:id";
        $stmt = $pdo->prepare($sql);

        $params['slug'] = $slug;
        $params['id'] = $id;
        $stmt->execute($params);

        $newID = $id;
    }

    /* -----------------------------------------------------------
       UPLOAD DES IMAGES MULTIPLES
    ----------------------------------------------------------- */
    if (!empty($_FILES['images']['name'][0])) {

        $folder = __DIR__ . '/../uploads/';
        if(!is_dir($folder)) mkdir($folder,0777,true);

        foreach($_FILES['images']['name'] as $i => $name){
            if($_FILES['images']['error'][$i] === 0){
                $filename = time().'_'.$i.'_'.basename($name);
                $target = $folder.$filename;

                if(move_uploaded_file($_FILES['images']['tmp_name'][$i], $target)){
                    $pathSQL = 'uploads/'.$filename;
                    $stp = $pdo->prepare("INSERT INTO immo_bien_photos (bien_id, fichier, sort_order) VALUES (?, ?, ?)");
                    $stp->execute([$newID, $pathSQL, $i]);
                }
            }
        }
    }

    /* -----------------------------------------------------------
       REDIRECTION
    ----------------------------------------------------------- */
    header("Location: biens.php?msg=Bien enregistré avec succès");
    exit;
}

?>

<!-- ---------------------------------------------
     JAVASCRIPT INTELLIGENT : Champs dynamiques
---------------------------------------------- -->
<script>
function updateFieldVisibility() {
    const type = document.getElementById('type_bien').value.toLowerCase();
    const blocsHab = document.querySelectorAll('.bloc_habitation');
    const sectionCaracs = document.getElementById('section_caracteristiques');

    if (type === 'terrain') {
        blocsHab.forEach(b => b.style.display = 'none');
        sectionCaracs.style.display = 'none';
    }
    else if (type === 'bureau' || type === 'local commercial') {
        blocsHab.forEach(b => b.style.display = 'none');
        sectionCaracs.style.display = 'grid';
    }
    else {
        blocsHab.forEach(b => b.style.display = 'block');
        sectionCaracs.style.display = 'grid';
    }
}

document.getElementById('type_bien').addEventListener('change', updateFieldVisibility);
window.addEventListener('load', updateFieldVisibility);
</script>
