<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

$currentPage = 'biens';
$pageTitle   = "Catalogue des biens – IBIG IMMO TRUST";

include __DIR__ . '/includes/header.php';

/* ============================
   LECTURE DES FILTRES & TRI
============================ */
$type         = trim($_GET['type'] ?? '');
$transaction  = trim($_GET['transaction'] ?? '');
$ville        = trim($_GET['ville'] ?? '');
$min_price    = trim($_GET['min_price'] ?? '');
$max_price    = trim($_GET['max_price'] ?? '');
$chambres     = trim($_GET['chambres'] ?? '');
$view         = $_GET['view'] ?? 'grid';
$sort         = $_GET['sort'] ?? 'date_desc'; // nouveau
$page         = max(1, (int)($_GET['page'] ?? 1));
$perPage      = 12;

/* ============================
   CONSTRUCTION WHERE + PARAMS
============================ */
$where  = " WHERE visible = 1";
$params = [];

if ($type !== '') {
    $where .= " AND (type = :type OR type_bien = :type)";
    $params[':type'] = $type;
}

if ($transaction !== '') {
    $where .= " AND (transaction = :trans OR statut = :trans)";
    $params[':trans'] = $transaction;
}

if ($ville !== '') {
    $where .= " AND ville LIKE :ville";
    $params[':ville'] = '%' . $ville . '%';
}

// Nettoyage des prix (on enlève tout sauf chiffres)
if ($min_price !== '') {
    $p = preg_replace('/\D/', '', $min_price);
    if ($p !== '') {
        $where .= " AND prix >= :min_price";
        $params[':min_price'] = (int)$p;
    }
}

if ($max_price !== '') {
    $p = preg_replace('/\D/', '', $max_price);
    if ($p !== '') {
        $where .= " AND prix <= :max_price";
        $params[':max_price'] = (int)$p;
    }
}

if ($chambres !== '' && ctype_digit($chambres)) {
    $where .= " AND chambres >= :chambres";
    $params[':chambres'] = (int)$chambres;
}

/* ============================
   TRI
============================ */
switch ($sort) {
    case 'price_asc':
        $orderBy = "prix ASC, created_at DESC";
        break;
    case 'price_desc':
        $orderBy = "prix DESC, created_at DESC";
        break;
    case 'date_asc':
        $orderBy = "created_at ASC";
        break;
    case 'date_desc':
    default:
        $orderBy = "created_at DESC";
        break;
}

/* ============================
   PAGINATION : NB TOTAL
============================ */
$sqlCount = "SELECT COUNT(*) FROM immo_biens" . $where;
$st = $pdo->prepare($sqlCount);
$st->execute($params);
$total = (int)$st->fetchColumn();
$totalPages = max(1, (int)ceil($total / $perPage));

if ($page > $totalPages) {
    $page = $totalPages;
}
$offset = ($page - 1) * $perPage;

/* ============================
   RÉCUPÉRATION DES BIENS
============================ */
$sql = "SELECT * FROM immo_biens" . $where . " 
        ORDER BY $orderBy 
        LIMIT :limit OFFSET :offset";

$st = $pdo->prepare($sql);
foreach ($params as $k => $v) {
    $st->bindValue($k, $v);
}
$st->bindValue(':limit', $perPage, PDO::PARAM_INT);
$st->bindValue(':offset', $offset, PDO::PARAM_INT);
$st->execute();
$biens = $st->fetchAll(PDO::FETCH_ASSOC);

/* ============================
   FONCTION LIENS (garde filtres)
============================ */
function catalogue_build_query(array $extra = []): string {
    $base = $_GET;
    foreach ($extra as $k => $v) {
        if ($v === null) {
            unset($base[$k]);
        } else {
            $base[$k] = $v;
        }
    }
    $q = http_build_query($base);
    return $q ? ('?' . $q) : '';
}
?>

<style>
.catalogue-wrapper{
    max-width:1250px;
    margin:40px auto 50px;
    padding:0 15px;
}

/* Titre + vue + tri */
.catalogue-header{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap:16px;
    margin-bottom:18px;
    flex-wrap:wrap;
}
.catalogue-header-left h1{
    font-size:26px;
    font-weight:800;
    color:#003c96;
    margin:0;
}
.catalogue-header-left p{
    margin:4px 0 0;
    color:#6b7280;
}

/* Vue grille / liste */
.catalogue-header-right{
    display:flex;
    gap:12px;
    align-items:flex-end;
    flex-wrap:wrap;
}
.view-toggle{
    display:flex;
    gap:8px;
}
.view-toggle a{
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #d1d5db;
    font-size:13px;
    text-decoration:none;
    color:#374151;
    display:inline-flex;
    align-items:center;
    gap:6px;
}
.view-toggle a.active{
    background:#003c96;
    color:#fff;
    border-color:#003c96;
}

/* Select tri */
.sort-select{
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #d1d5db;
    font-size:13px;
}

/* Filtres */
.catalogue-filters{
    background:#f9fafb;
    border-radius:12px;
    padding:12px 12px 8px;
    margin-bottom:20px;
}
.catalogue-filters form{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
    gap:10px 12px;
}
.catalogue-filters label{
    display:block;
    font-size:12px;
    font-weight:600;
    color:#4b5563;
    margin-bottom:3px;
}
.catalogue-filters select,
.catalogue-filters input{
    width:100%;
    padding:6px 8px;
    border-radius:6px;
    border:1px solid #d1d5db;
    font-size:13px;
}
.catalogue-filters .filter-actions{
    display:flex;
    gap:8px;
    align-items:flex-end;
}
.catalogue-filters button{
    padding:7px 14px;
    border-radius:6px;
    border:none;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
}
.btn-filter{
    background:#003c96;
    color:#fff;
}
.btn-reset{
    background:#e5e7eb;
    color:#374151;
}

/* Grille / liste */
.catalogue-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:18px;
}
.catalogue-list{
    display:flex;
    flex-direction:column;
    gap:14px;
}

/* Card commune */
.catalogue-card{
    background:#fff;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 4px 12px rgba(0,0,0,0.06);
    border-left:5px solid #ff9f1c;
    text-decoration:none;
    color:#111827;
    display:flex;
    flex-direction:column;
    position:relative;
}
.catalogue-card img{
    width:100%;
    height:170px;
    object-fit:cover;
}
.catalogue-card-body{
    padding:10px 12px 12px;
}
.catalogue-type{
    font-size:12px;
    color:#6b7280;
    margin-bottom:3px;
}
.catalogue-card h3{
    font-size:16px;
    font-weight:700;
    margin:0 0 4px;
}
.catalogue-price{
    color:#e30613;
    font-weight:700;
    margin-bottom:4px;
}
.catalogue-location{
    font-size:13px;
    color:#6b7280;
    margin:0 0 4px;
}
.catalogue-meta{
    font-size:12px;
    color:#6b7280;
}

/* Badge */
.catalogue-badge{
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
.catalogue-badge.badge-urgent{ background:#e30613; }
.catalogue-badge.badge-nouveau{ background:#16a34a; }
.catalogue-badge.badge-exclusivite{ background:#2563eb; }

/* Boutons actions (WhatsApp + favoris) */
.card-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-top:6px;
}
.whatsapp-btn{
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:12px;
    padding:6px 10px;
    border-radius:999px;
    background:#25D366;
    color:#fff;
    text-decoration:none;
}
.whatsapp-btn i{
    font-size:13px;
}
.wishlist-toggle{
    background:transparent;
    border:none;
    cursor:pointer;
    font-size:18px;
    color:#d1d5db;
}
.wishlist-toggle.active{
    color:#e30613;
}

/* Mode liste */
.catalogue-card.list{
    flex-direction:row;
}
.catalogue-card.list img{
    width:220px;
    height:100%;
}
.catalogue-card.list .catalogue-card-body{
    flex:1;
}

/* Pagination */
.catalogue-pagination{
    margin-top:22px;
    display:flex;
    justify-content:center;
    gap:6px;
    flex-wrap:wrap;
}
.catalogue-pagination a,
.catalogue-pagination span{
    min-width:32px;
    padding:6px 10px;
    border-radius:6px;
    border:1px solid #d1d5db;
    font-size:13px;
    text-align:center;
    text-decoration:none;
    color:#374151;
}
.catalogue-pagination .active-page{
    background:#003c96;
    color:#fff;
    border-color:#003c96;
}
.catalogue-pagination .disabled{
    opacity:0.4;
    cursor:default;
}

/* Bloc carte */
.catalogue-map-wrapper{
    margin-top:25px;
}
#catalogue-map{
    width:100%;
    height:360px;
    border-radius:12px;
    overflow:hidden;
    border:1px solid #e5e7eb;
}

/* Mobile */
@media(max-width:768px){
    .catalogue-header{
        align-items:flex-start;
    }
    .catalogue-card.list img{
        width:140px;
    }
}
</style>

<div class="catalogue-wrapper">

  <div class="catalogue-header">
    <div class="catalogue-header-left">
      <h1>Catalogue de nos biens</h1>
      <p><?= $total ?> bien(s) trouvé(s)</p>
    </div>

    <div class="catalogue-header-right">
      <!-- Tri -->
      <form method="get">
        <?php
          // on garde les filtres en hidden
          foreach (['type','transaction','ville','min_price','max_price','chambres','view'] as $f) {
              if (!empty($_GET[$f])) {
                  echo '<input type="hidden" name="'.htmlspecialchars($f).'" value="'.htmlspecialchars($_GET[$f]).'">';
              }
          }
        ?>
        <select name="sort" class="sort-select" onchange="this.form.submit()">
          <option value="date_desc" <?= $sort==='date_desc'?'selected':'' ?>>Plus récents</option>
          <option value="date_asc" <?= $sort==='date_asc'?'selected':'' ?>>Plus anciens</option>
          <option value="price_asc" <?= $sort==='price_asc'?'selected':'' ?>>Prix croissant</option>
          <option value="price_desc" <?= $sort==='price_desc'?'selected':'' ?>>Prix décroissant</option>
        </select>
      </form>

      <!-- Vue -->
      <div class="view-toggle">
        <a href="<?= catalogue_build_query(['view' => 'grid', 'page' => 1]) ?>" 
           class="<?= $view === 'grid' ? 'active' : '' ?>">
          <i class="fa-solid fa-border-all"></i> Grille
        </a>
        <a href="<?= catalogue_build_query(['view' => 'list', 'page' => 1]) ?>" 
           class="<?= $view === 'list' ? 'active' : '' ?>">
          <i class="fa-solid fa-list"></i> Liste
        </a>
      </div>
    </div>
  </div>

  <!-- ================== FILTRES ================== -->
  <div class="catalogue-filters">
    <form method="get">

      <div>
        <label>Type de bien</label>
        <select name="type">
          <option value="">Tous</option>
          <?php
            $types = ['Terrain','Maison','Appartement','Villa','Immeuble','Bureau','Local commercial'];
            foreach($types as $t):
          ?>
            <option value="<?= htmlspecialchars($t) ?>" <?= $type === $t ? 'selected' : '' ?>>
              <?= htmlspecialchars($t) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Transaction</label>
        <select name="transaction">
          <option value="">Toutes</option>
          <?php
            $transList = ['A vendre','A louer','Réservé','Vendu','Loué'];
            foreach($transList as $tr):
          ?>
            <option value="<?= htmlspecialchars($tr) ?>" <?= $transaction === $tr ? 'selected' : '' ?>>
              <?= htmlspecialchars($tr) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Ville</label>
        <input type="text" name="ville" placeholder="Abidjan, Yamoussoukro..." 
               value="<?= htmlspecialchars($ville) ?>">
      </div>

      <div>
        <label>Prix min (FCFA)</label>
        <input type="text" name="min_price" placeholder="ex : 5 000 000" 
               value="<?= htmlspecialchars($min_price) ?>">
      </div>

      <div>
        <label>Prix max (FCFA)</label>
        <input type="text" name="max_price" placeholder="ex : 100 000 000" 
               value="<?= htmlspecialchars($max_price) ?>">
      </div>

      <div>
        <label>Chambres (min.)</label>
        <select name="chambres">
          <option value="">Toutes</option>
          <?php for($i=1;$i<=6;$i++): ?>
            <option value="<?= $i ?>" <?= $chambres == (string)$i ? 'selected' : '' ?>>
              <?= $i ?>+
            </option>
          <?php endfor; ?>
        </select>
      </div>

      <div class="filter-actions">
        <button type="submit" class="btn-filter">Filtrer</button>
        <button type="button" class="btn-reset"
                onclick="window.location='catalogue.php';">
          Réinitialiser
        </button>
      </div>

      <!-- conserver la vue -->
      <input type="hidden" name="view" value="<?= htmlspecialchars($view) ?>">
      <!-- conserver le tri -->
      <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">

    </form>
  </div>

  <!-- ================== LISTE DES BIENS ================== -->

  <?php if (count($biens) === 0): ?>
    <p>Aucun bien ne correspond à vos critères pour le moment.</p>
  <?php else: ?>

    <?php if ($view === 'list'): ?>
      <div class="catalogue-list">
        <?php foreach($biens as $b): 
            $url = 'bien.php';
            if (!empty($b['slug'])) {
                $url .= '?slug='.urlencode($b['slug']);
            } else {
                $url .= '?id='.$b['id'];
            }
            $img = !empty($b['image_principale'])
                ? htmlspecialchars($b['image_principale'])
                : 'assets/img/no-image.jpg';

            $typeAff  = $b['type'] ?? ($b['type_bien'] ?? '');
            $transAff = $b['transaction'] ?? ($b['statut'] ?? '');

            // badge
            $badgeClasse = '';
            $badgeTexte  = '';
            if (!empty($b['badge'])) {
                $badgeTexte = $b['badge'];
                $slugBadge  = strtolower(trim($b['badge']));
                if (str_contains($slugBadge,'urgent')) $badgeClasse = 'badge-urgent';
                elseif (str_contains($slugBadge,'nouveau')) $badgeClasse = 'badge-nouveau';
                elseif (str_contains($slugBadge,'exclu')) $badgeClasse = 'badge-exclusivite';
            }
            $waMsg = rawurlencode("Bonjour IBIG IMMO TRUST, je suis intéressé par ce bien : ".$b['titre']." (ID ".$b['id'].").");
        ?>
        <div class="catalogue-card list" data-bien-id="<?= (int)$b['id'] ?>">
          <?php if ($badgeTexte): ?>
            <span class="catalogue-badge <?= $badgeClasse ?>"><?= htmlspecialchars($badgeTexte) ?></span>
          <?php endif; ?>

          <a href="<?= $url ?>" style="display:flex;flex:1;text-decoration:none;color:inherit;">
            <img src="<?= $img ?>" alt="Photo bien">
            <div class="catalogue-card-body">
              <div class="catalogue-type">
                <?= htmlspecialchars(trim($typeAff.' · '.$transAff)) ?>
              </div>
              <h3><?= htmlspecialchars($b['titre']) ?></h3>

              <?php if(!empty($b['prix'])): ?>
                <p class="catalogue-price">
                  <?= number_format($b['prix'],0,',',' ') ?> FCFA
                </p>
              <?php endif; ?>

              <p class="catalogue-location">
                <?= htmlspecialchars(trim(($b['ville'] ?? '').' • '.($b['quartier'] ?? ''))) ?>
              </p>

              <p class="catalogue-meta">
                <?php
                  $surf = $b['superficie'] ?? ($b['superficie_habitable'] ?? '');
                  if ($surf) {
                      echo htmlspecialchars($surf).' · ';
                  }
                  if (!empty($b['chambres'])) {
                      echo (int)$b['chambres'] . ' ch.';
                  }
                ?>
              </p>
            </div>
          </a>

          <div class="card-actions">
            <a class="whatsapp-btn" target="_blank"
               href="https://wa.me/2250778882592?text=<?= $waMsg ?>">
              <i class="fa-brands fa-whatsapp"></i> WhatsApp
            </a>
            <button type="button" class="wishlist-toggle" title="Ajouter aux favoris">
              <i class="fa-solid fa-heart"></i>
            </button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="catalogue-grid">
        <?php foreach($biens as $b): 
            $url = 'bien.php';
            if (!empty($b['slug'])) {
                $url .= '?slug='.urlencode($b['slug']);
            } else {
                $url .= '?id='.$b['id'];
            }
            $img = !empty($b['image_principale'])
                ? htmlspecialchars($b['image_principale'])
                : 'assets/img/no-image.jpg';

            $typeAff  = $b['type'] ?? ($b['type_bien'] ?? '');
            $transAff = $b['transaction'] ?? ($b['statut'] ?? '');

            $badgeClasse = '';
            $badgeTexte  = '';
            if (!empty($b['badge'])) {
                $badgeTexte = $b['badge'];
                $slugBadge  = strtolower(trim($b['badge']));
                if (str_contains($slugBadge,'urgent')) $badgeClasse = 'badge-urgent';
                elseif (str_contains($slugBadge,'nouveau')) $badgeClasse = 'badge-nouveau';
                elseif (str_contains($slugBadge,'exclu')) $badgeClasse = 'badge-exclusivite';
            }
            $waMsg = rawurlencode("Bonjour IBIG IMMO TRUST, je suis intéressé par ce bien : ".$b['titre']." (ID ".$b['id'].").");
        ?>
        <div class="catalogue-card" data-bien-id="<?= (int)$b['id'] ?>">
          <?php if ($badgeTexte): ?>
            <span class="catalogue-badge <?= $badgeClasse ?>"><?= htmlspecialchars($badgeTexte) ?></span>
          <?php endif; ?>

          <a href="<?= $url ?>" style="text-decoration:none;color:inherit;">
            <img src="<?= $img ?>" alt="Photo bien">
            <div class="catalogue-card-body">
              <div class="catalogue-type">
                <?= htmlspecialchars(trim($typeAff.' · '.$transAff)) ?>
              </div>
              <h3><?= htmlspecialchars($b['titre']) ?></h3>

              <?php if(!empty($b['prix'])): ?>
                <p class="catalogue-price">
                  <?= number_format($b['prix'],0,',',' ') ?> FCFA
                </p>
              <?php endif; ?>

              <p class="catalogue-location">
                <?= htmlspecialchars(trim(($b['ville'] ?? '').' • '.($b['quartier'] ?? ''))) ?>
              </p>

              <p class="catalogue-meta">
                <?php
                  $surf = $b['superficie'] ?? ($b['superficie_habitable'] ?? '');
                  if ($surf) {
                      echo htmlspecialchars($surf).' · ';
                  }
                  if (!empty($b['chambres'])) {
                      echo (int)$b['chambres'] . ' ch.';
                  }
                ?>
              </p>
            </div>
          </a>

          <div class="card-actions">
            <a class="whatsapp-btn" target="_blank"
               href="https://wa.me/2250778882592?text=<?= $waMsg ?>">
              <i class="fa-brands fa-whatsapp"></i> WhatsApp
            </a>
            <button type="button" class="wishlist-toggle" title="Ajouter aux favoris">
              <i class="fa-solid fa-heart"></i>
            </button>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

  <?php endif; ?>

  <!-- ================== PAGINATION ================== -->
  <?php if ($totalPages > 1): ?>
    <div class="catalogue-pagination">

      <?php if ($page > 1): ?>
        <a href="<?= catalogue_build_query(['page' => $page-1]) ?>">&laquo;</a>
      <?php else: ?>
        <span class="disabled">&laquo;</span>
      <?php endif; ?>

      <?php
        $start = max(1, $page - 3);
        $end   = min($totalPages, $page + 3);
        for ($i = $start; $i <= $end; $i++):
      ?>
        <?php if ($i == $page): ?>
          <span class="active-page"><?= $i ?></span>
        <?php else: ?>
          <a href="<?= catalogue_build_query(['page' => $i]) ?>"><?= $i ?></a>
        <?php endif; ?>
      <?php endfor; ?>

      <?php if ($page < $totalPages): ?>
        <a href="<?= catalogue_build_query(['page' => $page+1]) ?>">&raquo;</a>
      <?php else: ?>
        <span class="disabled">&raquo;</span>
      <?php endif; ?>

    </div>
  <?php endif; ?>

  <!-- ================== CARTE (PRETE POUR GOOGLE MAPS) ================== -->
  <div class="catalogue-map-wrapper">
    <h2 style="font-size:18px;font-weight:700;margin-bottom:8px;color:#003c96;">
      Voir les biens sur une carte
    </h2>
    <p style="font-size:13px;color:#6b7280;margin-bottom:10px;">
      Les biens disposant de coordonnées GPS apparaîtront sur la carte ci-dessous.
    </p>
    <div id="catalogue-map"></div>
  </div>

</div>

<script>
// ===== WISHLIST LOCALSTORAGE =====
(function(){
  const STORAGE_KEY = 'ibig_wishlist';
  function loadWishlist(){
    try{
      return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
    }catch(e){
      return [];
    }
  }
  function saveWishlist(list){
    localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
  }

  const wishlist = loadWishlist();

  document.querySelectorAll('.catalogue-card').forEach(card => {
    const id = card.getAttribute('data-bien-id');
    if(!id) return;
    const btn = card.querySelector('.wishlist-toggle');
    if(!btn) return;

    if (wishlist.includes(id)) {
      btn.classList.add('active');
    }

    btn.addEventListener('click', function(e){
      e.preventDefault();
      const idx = wishlist.indexOf(id);
      if (idx === -1) {
        wishlist.push(id);
        btn.classList.add('active');
      } else {
        wishlist.splice(idx,1);
        btn.classList.remove('active');
      }
      saveWishlist(wishlist);
    });
  });
})();
</script>

<?php
// ===== DATA POUR GOOGLE MAPS (seulement biens visibles sur la page) =====
$biensMap = [];
foreach($biens as $b){
    if (!empty($b['latitude']) && !empty($b['longitude'])) {
        $url = 'bien.php';
        if (!empty($b['slug'])) {
            $url .= '?slug='.urlencode($b['slug']);
        } else {
            $url .= '?id='.$b['id'];
        }
        $biensMap[] = [
            'id'    => (int)$b['id'],
            'lat'   => (float)$b['latitude'],
            'lng'   => (float)$b['longitude'],
            'titre' => $b['titre'],
            'url'   => $url,
        ];
    }
}
?>

<script>
var CATALOGUE_BIENS = <?php echo json_encode($biensMap); ?>;

// Init Google Maps catalogue
function initCatalogueMap(){
  const el = document.getElementById('catalogue-map');
  if (!el || !CATALOGUE_BIENS || CATALOGUE_BIENS.length === 0) {
    el.innerHTML = "<p style='padding:15px;font-size:13px;color:#6b7280;'>Aucun bien avec coordonnées GPS pour le moment.</p>";
    return;
  }

  let bounds = new google.maps.LatLngBounds();
  const map = new google.maps.Map(el, {
    zoom: 12,
    center: {lat: CATALOGUE_BIENS[0].lat, lng: CATALOGUE_BIENS[0].lng}
  });

  CATALOGUE_BIENS.forEach(b => {
    const pos = {lat: b.lat, lng: b.lng};
    bounds.extend(pos);
    const marker = new google.maps.Marker({
      position: pos,
      map: map,
      title: b.titre
    });
    const infowindow = new google.maps.InfoWindow({
      content: "<strong>"+b.titre+"</strong><br><a href='"+b.url+"'>Voir le bien</a>"
    });
    marker.addListener('click', () => infowindow.open(map, marker));
  });

  if (CATALOGUE_BIENS.length > 1) {
    map.fitBounds(bounds);
  }
}
</script>

<!-- A ACTIVER QUAND TU AURAS TA CLÉ GOOGLE MAPS -->
<!--
<script src="https://maps.googleapis.com/maps/api/js?key=VOTRE_CLE_API&callback=initCatalogueMap" async defer></script>
-->

<?php include __DIR__ . '/includes/footer.php'; ?>