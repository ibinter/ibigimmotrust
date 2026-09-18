<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

$currentPage = 'biens';
$pageTitle   = "Tous nos biens disponibles – IBIG IMMO TRUST";

include __DIR__ . '/includes/header.php';

/* ********************************************
   1ï¸â£ LECTURE DES FILTRES & TRI
********************************************* */

$ville       = trim($_GET['ville'] ?? '');
$quartier    = trim($_GET['quartier'] ?? '');
$type        = trim($_GET['type'] ?? '');
$transaction = trim($_GET['transaction'] ?? '');
$prix_min    = trim($_GET['prix_min'] ?? '');
$prix_max    = trim($_GET['prix_max'] ?? '');
$avec_piscine= isset($_GET['avec_piscine']) ? 1 : 0;
$avec_jardin = isset($_GET['avec_jardin']) ? 1 : 0;
$sort        = $_GET['sort'] ?? 'date_desc';

$where  = " WHERE visible = 1 ";
$params = [];

// Ville exacte
if ($ville !== '') {
    $where .= " AND ville = :ville ";
    $params[':ville'] = $ville;
}

// Quartier contient
if ($quartier !== '') {
    $where .= " AND quartier LIKE :quartier ";
    $params[':quartier'] = "%".$quartier."%";
}

// Type
if ($type !== '') {
    $where .= " AND type = :type ";
    $params[':type'] = $type;
}

// Transaction (A vendre / A louer)
if ($transaction !== '') {
    $where .= " AND transaction = :transaction ";
    $params[':transaction'] = $transaction;
}

// Prix min/max
if ($prix_min !== '') {
    $where .= " AND prix >= :prix_min ";
    $params[':prix_min'] = (int)preg_replace('/\D/','',$prix_min);
}
if ($prix_max !== '') {
    $where .= " AND prix <= :prix_max ";
    $params[':prix_max'] = (int)preg_replace('/\D/','',$prix_max);
}

// Piscine / Jardin (colonnes TINYINT(1) existantes)
if ($avec_piscine) {
    $where .= " AND piscine = 1 ";
}
if ($avec_jardin) {
    $where .= " AND jardin = 1 ";
}

/* ********************************************
   2ï¸â£ TRI
********************************************* */
switch ($sort) {
    case 'price_asc':
        $orderBy = " prix ASC, created_at DESC ";
        break;
    case 'price_desc':
        $orderBy = " prix DESC, created_at DESC ";
        break;
    case 'date_asc':
        $orderBy = " created_at ASC ";
        break;
    case 'date_desc':
    default:
        $orderBy = " created_at DESC ";
        break;
}

/* ********************************************
   3ï¸â£ PAGINATION
********************************************* */
$perPage = 12;
$page    = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset  = ($page - 1) * $perPage;

// Total
$stmtCount = $pdo->prepare("SELECT COUNT(*) FROM immo_biens $where");
$stmtCount->execute($params);
$total = (int)$stmtCount->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));

// Biens pour la page
$sql = "SELECT * FROM immo_biens $where ORDER BY $orderBy LIMIT :limit OFFSET :offset";
$stmt = $pdo->prepare($sql);
foreach($params as $k => $v){
    $stmt->bindValue($k, $v);
}
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$biens = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ********************************************
   4ï¸â£ FONCTION POUR GARDER LES FILTRES DANS LES LIENS
********************************************* */
function tlb_build_query(array $extra = []): string {
    $base = $_GET;
    foreach($extra as $k=>$v){
        if ($v === null) unset($base[$k]);
        else $base[$k] = $v;
    }
    $q = http_build_query($base);
    return $q ? ('?'.$q) : '';
}

?>
<style>
.tlb-wrapper{
    max-width:1200px;
    margin:0 auto 40px;
    padding:0 15px;
}

/* Header filtres + tri */
.tlb-header-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap:16px;
    margin:25px 0 15px;
    flex-wrap:wrap;
}
.tlb-header-top-left h2{
    font-size:24px;
    font-weight:800;
    margin:0;
    color:#003c96;
}
.tlb-header-top-left p{
    margin:4px 0 0;
    color:#6b7280;
    font-size:13px;
}
.tlb-header-top-right{
    display:flex;
    gap:10px;
    align-items:flex-end;
    flex-wrap:wrap;
}
.tlb-sort-select{
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #d1d5db;
    font-size:13px;
}

/* Filtres */
.tlb-filters{
    background:#fff;
    border-radius:12px;
    padding:15px;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
    margin-bottom:20px;
}
.tlb-filters form{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
    gap:10px 12px;
}
.tlb-filters select,
.tlb-filters input{
    width:100%;
    padding:6px 8px;
    border-radius:6px;
    border:1px solid #d1d5db;
    font-size:13px;
}
.tlb-filters .checkboxes{
    display:flex;
    flex-direction:column;
    gap:4px;
    font-size:13px;
    color:#374151;
}
.tlb-filters .checkboxes label{
    display:flex;
    align-items:center;
    gap:6px;
}
.tlb-filters .actions{
    display:flex;
    gap:8px;
    align-items:flex-end;
}
.tlb-filters button{
    padding:7px 14px;
    border-radius:6px;
    border:none;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
}
.tlb-btn-primary{
    background:#003c96;
    color:#fff;
}
.tlb-btn-reset{
    background:#e5e7eb;
    color:#374151;
    text-decoration:none;
    display:inline-block;
    text-align:center;
}

/* Grid */
.tlb-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:22px;
}

/* Card */
.tlb-card{
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 4px 15px rgba(0,0,0,0.1);
    border-left:5px solid #ff9f1c;
    text-decoration:none;
    color:#111827;
    position:relative;
    display:flex;
    flex-direction:column;
}
.tlb-card img{
    width:100%;
    height:190px;
    object-fit:cover;
}
.tlb-card-body{
    padding:12px 14px 14px;
}
.tlb-type{
    font-size:12px;
    color:#6b7280;
    margin-bottom:3px;
}
.tlb-card h3{
    font-size:17px;
    margin:4px 0;
    font-weight:700;
}
.tlb-price{
    color:#e30613;
    font-weight:700;
    margin:4px 0;
}
.tlb-location{
    color:#6b7280;
    font-size:13px;
    margin:0 0 4px;
}
.tlb-meta{
    font-size:12px;
    color:#6b7280;
}

/* Badge */
.tlb-badge{
    position:absolute;
    top:10px;
    left:10px;
    background:#e30613;
    color:#fff;
    font-size:11px;
    padding:4px 8px;
    border-radius:999px;
    font-weight:700;
    text-transform:uppercase;
}
.tlb-badge.badge-nouveau{background:#16a34a;}
.tlb-badge.badge-exclusivite{background:#2563eb;}
.tlb-badge.badge-urgent{background:#e30613;}

/* Actions bas de carte */
.tlb-card-footer{
    padding:8px 14px 12px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:8px;
}
.tlb-whatsapp{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:6px 10px;
    border-radius:999px;
    font-size:12px;
    background:#25D366;
    color:#fff;
    text-decoration:none;
}
.tlb-whatsapp i{font-size:13px;}
.tlb-more{
    font-size:12px;
    color:#003c96;
    text-decoration:none;
    font-weight:600;
}

/* Pagination */
.tlb-pagination{
    margin-top:26px;
    display:flex;
    justify-content:center;
    flex-wrap:wrap;
    gap:6px;
}
.tlb-pagination a,
.tlb-pagination span{
    min-width:32px;
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #d1d5db;
    font-size:13px;
    text-align:center;
    text-decoration:none;
    color:#374151;
}
.tlb-pagination .active{
    background:#003c96;
    color:#fff;
    border-color:#003c96;
}
.tlb-pagination .disabled{
    opacity:0.4;
    cursor:default;
}

/* Carte */
.tlb-map-wrapper{
    margin-top:30px;
}
#tous-biens-map{
    width:100%;
    height:360px;
    border-radius:12px;
    border:1px solid #e5e7eb;
    overflow:hidden;
}

/* Responsive */
@media(max-width:768px){
    .tlb-header-top{
        align-items:flex-start;
    }
}
</style>

<!-- ======================= EN-TÊTE GRADIENT ======================= -->
<section class="encart-biens" style="padding:40px 0;">
  <div class="container">
    <h1 class="section-title" style="text-align:center;color:#fff;">
      ð¡ Tous Nos Biens Disponibles
    </h1>
    <p class="section-intro" style="text-align:center;color:#fff;">
      Vente • Location • Investissement • Terrains • Villas • Appartements
    </p>
  </div>
</section>

<div class="tlb-wrapper">

  <!-- HEADER TOP : total + tri -->
  <div class="tlb-header-top">
    <div class="tlb-header-top-left">
      <h2>Catalogue complet</h2>
      <p><?= $total ?> bien(s) trouvé(s)</p>
    </div>
    <div class="tlb-header-top-right">
      <form method="get">
        <?php
        // garder tous les filtres dans le form de tri
        foreach(['ville','quartier','type','transaction','prix_min','prix_max','avec_piscine','avec_jardin'] as $k){
            if(isset($_GET[$k])){
                if(is_array($_GET[$k])) continue;
                echo '<input type="hidden" name="'.htmlspecialchars($k).'" value="'.htmlspecialchars($_GET[$k]).'">';
            }
        }
        ?>
        <select name="sort" class="tlb-sort-select" onchange="this.form.submit()">
          <option value="date_desc" <?= $sort==='date_desc'?'selected':'' ?>>Plus récents</option>
          <option value="date_asc"  <?= $sort==='date_asc'?'selected':''  ?>>Plus anciens</option>
          <option value="price_asc" <?= $sort==='price_asc'?'selected':'' ?>>Prix croissant</option>
          <option value="price_desc"<?= $sort==='price_desc'?'selected':''?>>Prix décroissant</option>
        </select>
      </form>
    </div>
  </div>

  <!-- ======================= FILTRES ======================= -->
  <div class="tlb-filters">
    <form method="get">

      <div>
        <select name="ville">
          <option value="">Toutes les villes</option>
          <?php
            $villes = $pdo->query("SELECT DISTINCT ville FROM immo_biens WHERE ville IS NOT NULL AND ville <> '' ORDER BY ville ASC")->fetchAll(PDO::FETCH_COLUMN);
            foreach($villes as $v):
          ?>
            <option value="<?= htmlspecialchars($v) ?>" <?= $ville===$v?'selected':'' ?>>
              <?= htmlspecialchars($v) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <input type="text" name="quartier" placeholder="Quartier"
               value="<?= htmlspecialchars($quartier) ?>">
      </div>

      <div>
        <select name="type">
          <option value="">Type de bien</option>
          <?php
            $types = ['Terrain','Appartement','Maison','Villa','Immeuble','Bureau','Local commercial'];
            foreach($types as $t):
          ?>
            <option value="<?= htmlspecialchars($t) ?>" <?= $type===$t?'selected':'' ?>>
              <?= htmlspecialchars($t) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <select name="transaction">
          <option value="">Transaction</option>
          <option value="A vendre" <?= $transaction==='A vendre'?'selected':'' ?>>A vendre</option>
          <option value="A louer"  <?= $transaction==='A louer'?'selected':''  ?>>A louer</option>
        </select>
      </div>

      <div>
        <input type="text" name="prix_min" placeholder="Prix min (FCFA)"
               value="<?= htmlspecialchars($prix_min) ?>">
      </div>

      <div>
        <input type="text" name="prix_max" placeholder="Prix max (FCFA)"
               value="<?= htmlspecialchars($prix_max) ?>">
      </div>

      <div class="checkboxes">
        <label>
          <input type="checkbox" name="avec_piscine" value="1" <?= $avec_piscine?'checked':'' ?>>
          Avec piscine
        </label>
        <label>
          <input type="checkbox" name="avec_jardin" value="1" <?= $avec_jardin?'checked':'' ?>>
          Avec jardin
        </label>
      </div>

      <div class="actions">
        <button type="submit" class="tlb-btn-primary">ð Rechercher</button>
        <a href="tous_les_biens.php" class="tlb-btn-reset">â»ï¸ Réinitialiser</a>
      </div>

    </form>
  </div>

  <!-- ======================= GRILLE DES BIENS ======================= -->
  <?php if (count($biens) === 0): ?>
    <p>Aucun bien ne correspond à vos critères pour le moment.</p>
  <?php else: ?>
    <div class="tlb-grid">
      <?php foreach($biens as $b): 
        $img = !empty($b['image_principale']) ? $b['image_principale'] : "assets/img/no-image.jpg";
        $url = "bien.php";
        if (!empty($b['slug'])) $url .= "?slug=".urlencode($b['slug']);
        else $url .= "?id=".$b['id'];

        $typeAff  = $b['type'] ?? '';
        $transAff = $b['transaction'] ?? '';

        // Badge
        $badgeTexte  = '';
        $badgeClass  = '';
        if (!empty($b['badge'])) {
            $badgeTexte = $b['badge'];
            $badgeSlug  = strtolower($b['badge']);
            if (str_contains($badgeSlug,'urgent'))        $badgeClass = 'badge-urgent';
            elseif (str_contains($badgeSlug,'nouveau'))   $badgeClass = 'badge-nouveau';
            elseif (str_contains($badgeSlug,'exclu'))     $badgeClass = 'badge-exclusivite';
        }

        // WhatsApp message vers IBIG IMMO (à adapter si besoin)
        $waText = rawurlencode("Bonjour IBIG IMMO TRUST, je suis intéressé par ce bien : ".$b['titre']." (ID ".$b['id'].").");
      ?>
        <div class="tlb-card">
          <?php if($badgeTexte): ?>
            <span class="tlb-badge <?= $badgeClass ?>"><?= htmlspecialchars($badgeTexte) ?></span>
          <?php endif; ?>

          <a href="<?= $url ?>" style="text-decoration:none;color:inherit;">
            <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($b['titre']) ?>">
            <div class="tlb-card-body">
              <div class="tlb-type">
                <?= htmlspecialchars(trim($typeAff.' · '.$transAff)) ?>
              </div>
              <h3><?= htmlspecialchars($b['titre']) ?></h3>

              <?php if(!empty($b['prix'])): ?>
                <p class="tlb-price">
                  <?= number_format($b['prix'],0,',',' ') ?> FCFA
                </p>
              <?php endif; ?>

              <p class="tlb-location">
                <?= htmlspecialchars(trim(($b['ville'] ?? '').' • '.($b['quartier'] ?? ''))) ?>
              </p>

              <p class="tlb-meta">
                <?php
                  $surf = $b['superficie'] ?? ($b['superficie_habitable'] ?? '');
                  if ($surf) echo htmlspecialchars($surf).' · ';
                  if (!empty($b['chambres'])) echo (int)$b['chambres']." ch.";
                ?>
              </p>
            </div>
          </a>

          <div class="tlb-card-footer">
            <a class="tlb-whatsapp" target="_blank"
               href="https://wa.me/2250778882592?text=<?= $waText ?>">
              <i class="fa-brands fa-whatsapp"></i> WhatsApp
            </a>
            <a href="<?= $url ?>" class="tlb-more">Voir le bien →</a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <!-- ======================= PAGINATION ======================= -->
  <?php if ($totalPages > 1): ?>
    <div class="tlb-pagination">
      <?php if($page > 1): ?>
        <a href="<?= tlb_build_query(['page'=>$page-1]) ?>">&laquo;</a>
      <?php else: ?>
        <span class="disabled">&laquo;</span>
      <?php endif; ?>

      <?php
      $start = max(1, $page-3);
      $end   = min($totalPages, $page+3);
      for($i=$start; $i<=$end; $i++):
      ?>
        <?php if($i == $page): ?>
          <span class="active"><?= $i ?></span>
        <?php else: ?>
          <a href="<?= tlb_build_query(['page'=>$i]) ?>"><?= $i ?></a>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if($page < $totalPages): ?>
        <a href="<?= tlb_build_query(['page'=>$page+1]) ?>">&raquo;</a>
      <?php else: ?>
        <span class="disabled">&raquo;</span>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <!-- ======================= CARTE GOOGLE MAPS ======================= -->
  <div class="tlb-map-wrapper">
    <h3 style="font-size:18px;font-weight:700;color:#003c96;margin-bottom:6px;">
      Voir les biens sur une carte
    </h3>
    <p style="font-size:13px;color:#6b7280;margin-bottom:8px;">
      Les biens disposant de coordonnées GPS (latitude / longitude) apparaîtront ci-dessous.
    </p>
    <div id="tous-biens-map"></div>
  </div>

</div>

<?php
// Données pour la carte : uniquement les biens de la page avec latitude/longitude
$mapData = [];
foreach($biens as $b){
    if (!empty($b['latitude']) && !empty($b['longitude'])) {
        $u = 'bien.php';
        if (!empty($b['slug'])) $u .= '?slug='.urlencode($b['slug']);
        else $u .= '?id='.$b['id'];

        $mapData[] = [
            'id'    => (int)$b['id'],
            'lat'   => (float)$b['latitude'],
            'lng'   => (float)$b['longitude'],
            'titre' => $b['titre'],
            'url'   => $u,
        ];
    }
}
?>

<script>
const TOUS_BIENS_MAP_DATA = <?php echo json_encode($mapData); ?>;

function initTousBiensMap(){
  const el = document.getElementById('tous-biens-map');
  if (!el) return;

  if (!TOUS_BIENS_MAP_DATA || TOUS_BIENS_MAP_DATA.length === 0) {
    el.innerHTML = "<p style='padding:15px;font-size:13px;color:#6b7280;'>Aucun bien géolocalisé pour le moment.</p>";
    return;
  }

  let bounds = new google.maps.LatLngBounds();
  const first = TOUS_BIENS_MAP_DATA[0];
  const map = new google.maps.Map(el, {
    zoom: 12,
    center: {lat:first.lat, lng:first.lng}
  });

  TOUS_BIENS_MAP_DATA.forEach(b => {
    const pos = {lat:b.lat, lng:b.lng};
    bounds.extend(pos);

    const marker = new google.maps.Marker({
      position: pos,
      map: map,
      title: b.titre
    });

    const info = new google.maps.InfoWindow({
      content: "<strong>"+b.titre+"</strong><br><a href='"+b.url+"'>Voir le bien</a>"
    });

    marker.addListener('click', ()=>info.open(map,marker));
  });

  if (TOUS_BIENS_MAP_DATA.length > 1) {
    map.fitBounds(bounds);
  }
}
</script>

<!-- â ï¸ À ACTIVER QUAND TU AURAS TA CLÉ GOOGLE MAPS (remplace VOTRE_CLE_API) -->
<!--
<script src="https://maps.googleapis.com/maps/api/js?key=api_key:3c469ef810c3cd4bf377ea6e92ec2eee63ecf028af01bc7c0ee3f138e9e09605&callback=initTousBiensMap" async defer></script>
-->

<?php include __DIR__ . '/includes/footer.php'; ?>