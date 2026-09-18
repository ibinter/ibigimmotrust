<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

// On supporte soit ?slug=..., soit ?id=
$slug = $_GET['slug'] ?? null;
$id   = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($slug) {
    $st = $pdo->prepare("SELECT * FROM immo_biens WHERE slug = ? AND visible = 1 LIMIT 1");
    $st->execute([$slug]);
} elseif ($id > 0) {
    $st = $pdo->prepare("SELECT * FROM immo_biens WHERE id = ? AND visible = 1 LIMIT 1");
    $st->execute([$id]);
} else {
    die("Bien introuvable.");
}

$bien = $st->fetch(PDO::FETCH_ASSOC);
if (!$bien) {
    die("Bien introuvable ou non disponible.");
}

// Charger les photos
$st2 = $pdo->prepare("SELECT * FROM immo_bien_photos WHERE bien_id = ? ORDER BY sort_order ASC");
$st2->execute([$bien['id']]);
$photos = $st2->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = $bien['titre'] . " - IBIG IMMO TRUST";
<?php
require_once __DIR__.'/includes/config.php';

$id = (int)($_GET['id'] ?? 0);
// ou récupération par slug si tu l’as déjà

// ... ta requête pour charger $bien ...

$pageTitle = $bien['titre'] . " - " . ($bien['ville'] ?? '') . " | IBIG IMMO TRUST";
$metaDescription = substr(strip_tags($bien['description'] ?? ''), 0, 160);
$currentPage = 'biens';

include __DIR__ . '/includes/header.php';
?>

<style>
.bien-hero{
  width:100%;
  height:420px;
  background-size:cover;
  background-position:center;
  border-radius:12px;
  position:relative;
}
.bien-badge{
  position:absolute;
  top:20px;
  left:20px;
  background:#e30613;
  color:#fff;
  padding:6px 12px;
  border-radius:6px;
  font-size:14px;
  font-weight:700;
}
.bien-info-grid{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
  gap:20px;
  margin-top:25px;
}
.bien-table{
  width:100%;
  border-collapse:collapse;
  font-size:15px;
}
.bien-table td{
  padding:8px 0;
  border-bottom:1px solid #eee;
}
.galerie{
  display:flex;
  gap:10px;
  overflow-x:auto;
  margin-top:15px;
}
.galerie img{
  width:180px;
  height:140px;
  object-fit:cover;
  border-radius:8px;
}
.bien-actions a{
  display:inline-block;
  margin-right:10px;
  padding:9px 14px;
  border-radius:6px;
  font-size:14px;
  font-weight:600;
}
.btn-whatsapp{ background:#25D366; color:#fff; }
.btn-appel{ background:#003c96; color:#fff; }
.btn-rdv{ background:#ff9f1c; color:#fff; }
</style>

<div class="container" style="padding-top:30px;padding-bottom:60px;max-width:1150px;">

  <!-- HERO -->
  <div class="bien-hero"
       style="background-image:url('<?= htmlspecialchars($bien['image_principale']) ?>');">
    <?php if ($bien['badge']): ?>
      <div class="bien-badge"><?= htmlspecialchars($bien['badge']) ?></div>
    <?php endif; ?>
  </div>

  <!-- GALERIE -->
  <?php if (count($photos) > 0): ?>
  <div class="galerie">
    <?php foreach($photos as $p): ?>
      <img src="<?= htmlspecialchars($p['fichier']) ?>" alt="Photo du bien">
    <?php endforeach; ?>
  </div>
  <?php endif; ?>

  <!-- TITRE + PRIX -->
  <h1 style="font-size:28px;font-weight:800;margin-top:25px;color:#003c96;">
    <?= htmlspecialchars($bien['titre']) ?>
  </h1>

  <h2 style="font-size:22px;color:#e30613;margin-bottom:10px;">
    <?= number_format($bien['prix'],0,',',' ') ?> FCFA
  </h2>

  <?php if ($bien['prix_euro'] || $bien['prix_usd']): ?>
  <p style="font-size:14px;color:#6b7280;">
    <?php if ($bien['prix_euro']): ?>
      ≈ <?= number_format($bien['prix_euro'],0,',',' ') ?> €
    <?php endif; ?>
    <?php if ($bien['prix_usd']): ?>
      · ≈ <?= number_format($bien['prix_usd'],0,',',' ') ?> $
    <?php endif; ?>
  </p>
  <?php endif; ?>

  <!-- ACTIONS -->
  <div class="bien-actions" style="margin-top:15px;margin-bottom:30px;">
    <a href="https://wa.me/2250584437474?text=Je%20suis%20int%C3%A9ress%C3%A9%20par%20le%20bien%20:<?= urlencode(' '.$bien['titre']) ?>" class="btn-whatsapp">WhatsApp</a>
    <a href="tel:+2250778882592" class="btn-appel">Appeler IBIG IMMO TRUST</a>
    <a href="rdv.php?bien=<?= $bien['id'] ?>" class="btn-rdv">Prendre RDV visite</a>
  </div>

  <!-- DESCRIPTION -->
  <h3 style="font-size:20px;margin-bottom:10px;color:#003c96;">Description</h3>
  <p style="font-size:16px;line-height:1.6;">
    <?= nl2br(htmlspecialchars($bien['description'])) ?>
  </p>

  <!-- CARACTÉRISTIQUES -->
  <h3 style="font-size:20px;margin-top:25px;margin-bottom:10px;color:#003c96;">Caractéristiques principales</h3>

  <div class="bien-info-grid">
    <table class="bien-table">
      <?php
      $infos = [
        "Type de bien"         => $bien['type'],
        "Transaction"          => $bien['transaction'],
        "Ville"                => $bien['ville'],
        "Quartier"             => $bien['quartier'],
        "Zone / environnement" => $bien['quartier_type'],
        "Surface bâtie"        => $bien['superficie'],
        "Superficie terrain"   => $bien['superficie_terrain'],
        "Superficie habitable" => $bien['superficie_habitable'],
        "Chambres"             => $bien['chambres'],
        "Salles de bain"       => $bien['salles_bain'],
        "Salons"               => $bien['salons'],
        "Cuisines"             => $bien['cuisines'],
        "Niveaux"              => $bien['niveaux'],
        "Année de construction"=> $bien['annee_construction'],
        "État général"         => $bien['etat_general'],
        "Documents fonciers"   => $bien['titre_foncier'],
        "Disponibilité"        => $bien['disponibilite'],
      ];
      foreach($infos as $label => $val){
        if ($val !== null && $val !== '') {
          echo "<tr><td><strong>$label</strong></td><td>".htmlspecialchars($val)."</td></tr>";
        }
      }
      ?>
    </table>

    <table class="bien-table">
      <?php
      $caracs = [
        "Climatisation"        => $bien['climatisation'],
        "Chauffe-eau"          => $bien['chauffe_eau'],
        "Placards"             => $bien['placards'],
        "Dressing"             => $bien['dressing'],
        "Cuisine équipée"      => $bien['cuisine_equipee'],
        "Fibre internet"       => $bien['fibre'],
        "Sécurité / gardien"   => $bien['securite'],
        "Buanderie"            => $bien['buanderie'],
        "Balcon / Terrasse"    => $bien['balcon'],
        "Piscine"              => $bien['piscine'],
        "Jardin"               => $bien['jardin'],
        "Parking"              => $bien['parking'],
        "Garage"               => $bien['garage'],
        "Dépendance"           => $bien['dependance'],
        "Cour"                 => $bien['cour'],
        "Forage / puits"       => $bien['forage'],
        "Groupe électrogène"   => $bien['groupe_electrogene'],
        "Clôture sécurisée"    => $bien['cloture_securisee'],
      ];
      foreach($caracs as $label => $val){
        if ((int)$val === 1) {
          echo "<tr><td><strong>$label</strong></td><td>Oui</td></tr>";
        }
      }
      ?>
    </table>
  </div>

  <!-- LOCALISATION -->
  <?php if ($bien['latitude'] && $bien['longitude']): ?>
  <h3 style="font-size:20px;margin-top:25px;margin-bottom:10px;color:#003c96;">Localisation</h3>
  <iframe
    width="100%"
    height="350"
    frameborder="0"
    style="border-radius:12px;"
    src="https://www.google.com/maps?q=<?= $bien['latitude'] ?>,<?= $bien['longitude'] ?>&hl=fr&z=14&output=embed">
  </iframe>
  <?php endif; ?>
  
  <?php if (!empty($bien['latitude']) && !empty($bien['longitude'])): ?>
  <h3>Localisation sur carte</h3>
  <div id="bien-map" style="width:100%;height:320px;border-radius:12px;border:1px solid #e5e7eb;"></div>

  <script>
    function initBienMap(){
      const el = document.getElementById('bien-map');
      if (!el) return;

      const center = {lat: <?= (float)$bien['latitude']; ?>, lng: <?= (float)$bien['longitude']; ?>};
      const map = new google.maps.Map(el, {
        zoom: 15,
        center: center
      });

      new google.maps.Marker({
        position: center,
        map: map,
        title: "<?= addslashes($bien['titre']); ?>"
      });
    }
  </script>

  <!-- si pas déjà chargé sur cette page -->
  <!--
  <script src="https://maps.googleapis.com/maps/api/js?key=VOTRE_CLE_API&callback=initBienMap" async defer></script>
  -->
<?php endif; ?>

  <!-- VIDÉO -->
  <?php if ($bien['url_video']): ?>
  <h3 style="font-size:20px;margin-top:25px;margin-bottom:10px;color:#003c96;">Vidéo du bien</h3>
  <div style="margin-bottom:20px;">
    <iframe width="100%" height="400"
            src="<?= htmlspecialchars($bien['url_video']) ?>"
            frameborder="0"
            style="border-radius:12px;"
            allowfullscreen>
    </iframe>
  </div>
  <?php endif; ?>

  <!-- BIENS SIMILAIRES -->
  <h3 style="font-size:22px;color:#003c96;margin-top:40px;">Biens similaires</h3>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:20px;margin-top:15px;">
    <?php
    $sim = $pdo->prepare("SELECT * FROM immo_biens 
                          WHERE type = ? AND id != ? AND visible = 1 
                          ORDER BY created_at DESC LIMIT 4");
    $sim->execute([$bien['type'], $bien['id']]);
    $similaires = $sim->fetchAll(PDO::FETCH_ASSOC);
    foreach($similaires as $b){
      $url = 'bien.php';
      if (!empty($b['slug'])) {
          $url .= '?slug='.urlencode($b['slug']);
      } else {
          $url .= '?id='.$b['id'];
      }
    ?>
    <a href="<?= $url ?>" style="text-decoration:none;color:#000;">
      <div style="border-radius:10px;overflow:hidden;box-shadow:0 4px 10px rgba(0,0,0,0.08);background:#fff;">
        <img src="<?= htmlspecialchars($b['image_principale']) ?>" style="width:100%;height:160px;object-fit:cover;">
        <div style="padding:12px;">
          <h4 style="font-size:16px;font-weight:700;"><?= htmlspecialchars($b['titre']) ?></h4>
          <p style="color:#e30613;font-weight:600;margin:4px 0;"><?= number_format($b['prix'],0,',',' ') ?> FCFA</p>
          <p style="font-size:13px;color:#6b7280;">
            <?= htmlspecialchars(trim(($b['ville'] ?? '').' '.($b['quartier'] ?? ''))) ?>
          </p>
        </div>
      </div>
    </a>
    <?php } ?>
  </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>