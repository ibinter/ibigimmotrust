<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

$currentPage = 'blog';
$pageTitle   = "Blog Immobilier & BTP – IBIG IMMO TRUST";

include __DIR__ . '/includes/header.php';

/* ============================================
   PARAMÈTRES DE BASE
============================================ */
$perPage = 7; // 1 article "UNE" + 6 cartes sur la première page
$page    = max(1, (int)($_GET['page'] ?? 1));
$start   = ($page - 1) * $perPage;

$search   = trim($_GET['search'] ?? "");
$tag      = trim($_GET['tag'] ?? "");
$catSlug  = trim($_GET['cat_slug'] ?? "");
$catId    = trim($_GET['cat'] ?? "");

/* ============================================
   FILTRE CATÉGORIE VIA URL REWRITING
   /blog/categorie/slug
============================================ */
if ($catSlug !== "") {
    $stCat = $pdo->prepare("SELECT id FROM blog_categories WHERE slug = ? LIMIT 1");
    $stCat->execute([$catSlug]);
    if ($row = $stCat->fetch(PDO::FETCH_ASSOC)) {
        $catId = $row['id'];
    }
}

/* ============================================
   CONSTRUCTION DES CONDITIONS
============================================ */
$where  = [];
$params = [];

if ($search !== "") {
    $where[] = "(a.titre LIKE ? OR a.contenu LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($catId !== "") {
    $where[] = "a.categorie_id = ?";
    $params[] = $catId;
}

if ($tag !== "") {
    $where[] = "a.tags LIKE ?";
    $params[] = "%$tag%";
}

$whereSQL = "";
if (!empty($where)) {
    $whereSQL = "WHERE " . implode(" AND ", $where);
}

/* ============================================
   TOTAL ARTICLES
============================================ */
$stTotal = $pdo->prepare("
    SELECT COUNT(*)
    FROM blog_articles a
    LEFT JOIN blog_categories c ON c.id = a.categorie_id
    $whereSQL
");
$stTotal->execute($params);
$total = (int)$stTotal->fetchColumn();
$pages = max(1, ceil($total / $perPage));

/* ============================================
   ARTICLES (PAGE COURANTE)
============================================ */
$sql = "
    SELECT a.*, c.nom AS categorie, c.slug AS cat_slug
    FROM blog_articles a
    LEFT JOIN blog_categories c ON c.id = a.categorie_id
    $whereSQL
    ORDER BY a.created_at DESC
    LIMIT $start, $perPage
";
$st = $pdo->prepare($sql);
$st->execute($params);
$articles = $st->fetchAll(PDO::FETCH_ASSOC);

/* ============================================
   LISTE DES CATÉGORIES (SIDEBAR)
============================================ */
$cats = $pdo->query("
    SELECT id, nom, slug 
    FROM blog_categories 
    ORDER BY nom
")->fetchAll(PDO::FETCH_ASSOC);

/* ============================================
   LISTE DES TAGS UNIQUES (SIDEBAR)
============================================ */
$allTags = $pdo->query("
    SELECT tags FROM blog_articles
    WHERE tags IS NOT NULL AND tags != ''
")->fetchAll(PDO::FETCH_COLUMN);

$tagArray = [];
foreach ($allTags as $line) {
    foreach (explode(',', $line) as $tg) {
        $clean = trim($tg);
        if ($clean !== "") {
            $tagArray[$clean] = true;
        }
    }
}
$tagList = array_keys($tagArray);
sort($tagList);

/* ============================================
   ARTICLES RÉCENTS (SIDEBAR)
============================================ */
$recentPosts = $pdo->query("
    SELECT titre, slug, created_at
    FROM blog_articles
    ORDER BY created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

/* ============================================
   EXTRAIT COURT
============================================ */
function excerpt_blog($text, $limit = 170) {
    $plain = strip_tags($text);
    return mb_strlen($plain) > $limit 
        ? mb_substr($plain, 0, $limit) . "…" 
        : $plain;
}

/* ============================================
   QUERYSTRING POUR PAGINATION
============================================ */
$queryExtra = [];
if ($search !== "") $queryExtra[] = 'search='.urlencode($search);
if ($tag !== "")    $queryExtra[] = 'tag='.urlencode($tag);
if ($catSlug !== "")$queryExtra[] = 'cat_slug='.urlencode($catSlug);
if ($catId !== "" && $catSlug === "") $queryExtra[] = 'cat='.urlencode($catId);
$extra = $queryExtra ? '&'.implode('&', $queryExtra) : '';

?>
<style>
/* ==============================
   HERO BLOG MAGAZINE – PREMIUM
============================== */
.hero-blog-mag {
    background: radial-gradient(circle at top left,#0f2044,#0A1628 50%,#060d1a);
    padding:70px 20px 50px;
    color:#fff;
}
.hero-blog-inner {
    max-width:1200px;
    margin:0 auto;
    display:flex;
    flex-direction:column;
    gap:14px;
}
.hero-blog-kicker {
    font-size:14px;
    letter-spacing:.22em;
    text-transform:uppercase;
    color:rgba(212,175,55,0.25);
    font-weight:700;
}
.hero-blog-title {
    font-size:40px;
    font-weight:800;
    max-width:780px;
    line-height:1.25;
    color:#ffffff;
    text-shadow:0 4px 14px rgba(0,0,0,0.35);
}
.hero-blog-sub {
    font-size:18px;
    max-width:700px;
    color:#f1f5f9;
    line-height:1.55;
}
.hero-blog-meta-line {
    font-size:15px;
    color:#cbd5e1;
    margin-top:4px;
}

/* ==============================
   LAYOUT GLOBAL
============================== */
.blog-layout {
    max-width:1200px;
    margin:0 auto;
    padding:25px 20px 40px;
    display:grid;
    gap:26px;
    grid-template-columns:minmax(0,2.3fr) minmax(0,1fr);
}
@media(max-width:900px){
    .blog-layout{
        grid-template-columns:1fr;
    }
}

/* ==============================
   SEARCH / FILTRES EN HAUT
============================== */
.blog-topbar{
    margin-bottom:18px;
    display:flex;
    flex-wrap:wrap;
    align-items:center;
    gap:12px;
    justify-content:space-between;
}
.search-box-blog{
    flex:1;
    min-width:220px;
}
.search-box-blog input{
    width:100%;
    padding:11px 14px;
    border-radius:999px;
    border:1px solid #cbd5e1;
    font-size:14px;
}
.current-filters{
    font-size:13px;
    color:#64748b;
}
.filter-badges{
    display:flex;
    flex-wrap:wrap;
    gap:6px;
    margin-top:4px;
}
.filter-badge{
    background:#e2e8f0;
    padding:4px 9px;
    border-radius:999px;
    font-size:12px;
    color:#0f172a;
}

/* ==============================
   MAGAZINE – COLONNE PRINCIPALE
============================== */
.mag-featured{
    margin-bottom:25px;
}
.mag-featured-card{
    border-radius:18px;
    overflow:hidden;
    background:#020617;
    color:#fff;
    display:grid;
    grid-template-columns: minmax(0,1.4fr) minmax(0,1.6fr);
    min-height:260px;
}
.mag-featured-img{
    width:100%;
    height:100%;
    min-height:260px;
    object-fit:cover;
}
.mag-featured-body{
    padding:20px 22px;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
}
.mag-featured-cat{
    font-size:11px;
    text-transform:uppercase;
    letter-spacing:.12em;
    opacity:.85;
    margin-bottom:6px;
}
.mag-featured-title{
    font-size:24px;
    font-weight:800;
    margin:0 0 8px;
    color:#ffffff;
    text-shadow:0 2px 8px rgba(0,0,0,0.45);
}
.mag-featured-excerpt{
    font-size:14px;
    opacity:.95;
    margin-bottom:10px;
}
.mag-featured-meta{
    font-size:13px;
    opacity:.9;
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    align-items:center;
}
.mag-meta-item{
    display:flex;
    align-items:center;
    gap:6px;
}
.mag-featured-link{
    margin-top:10px;
    display:inline-flex;
    align-items:center;
    gap:6px;
    font-size:14px;
    font-weight:600;
    color:rgba(212,175,55,0.6);
    text-decoration:none;
}
.mag-featured-link:hover{
    text-decoration:underline;
}

@media(max-width:800px){
    .mag-featured-card{
        grid-template-columns:1fr;
    }
}

/* GRID DES AUTRES ARTICLES */
.mag-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(230px,1fr));
    gap:20px;
}
.mag-card{
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
    transition:.18s;
    display:flex;
    flex-direction:column;
    height:100%;
}
.mag-card:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 26px rgba(0,0,0,0.15);
}
.mag-card-img{
    width:100%;
    height:160px;
    object-fit:cover;
    background:#e5e7eb;
}
.mag-card-body{
    padding:14px 15px 13px;
    display:flex;
    flex-direction:column;
    gap:6px;
    flex:1;
}
.mag-card-cat{
    font-size:11px;
    text-transform:uppercase;
    color:#64748b;
    letter-spacing:.08em;
}
.mag-card-title{
    font-size:16px;
    font-weight:700;
    color:#0f2044;
}
.mag-card-excerpt{
    font-size:14px;
    color:#475569;
}
.mag-card-meta{
    font-size:12px;
    color:#94a3b8;
    margin-top:auto;
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}
.mag-card-meta span{
    display:inline-flex;
    align-items:center;
    gap:5px;
}

/* ==============================
   SIDEBAR MAGAZINE
============================== */
.sidebar{
    display:flex;
    flex-direction:column;
    gap:22px;
}
.sb-block{
    background:#fff;
    border-radius:14px;
    padding:16px 16px 14px;
    box-shadow:0 4px 14px rgba(0,0,0,0.08);
}
.sb-title{
    font-size:15px;
    font-weight:700;
    color:#0f2044;
    margin-bottom:10px;
}

/* Récents */
.sb-recent-item{
    padding:7px 0;
    border-bottom:1px solid #e5e7eb;
}
.sb-recent-item:last-child{
    border-bottom:none;
}
.sb-recent-link{
    text-decoration:none;
    font-size:14px;
    color:#0f2044;
    font-weight:600;
}
.sb-recent-link:hover{
    text-decoration:underline;
}
.sb-recent-date{
    font-size:11px;
    color:#9ca3af;
}

/* Catégories */
.sb-cat-list a{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:5px 0;
    font-size:14px;
    text-decoration:none;
    color:#0f2044;
}
.sb-cat-list a span.count{
    font-size:11px;
    color:#9ca3af;
}

/* Tags */
.sb-tags{
    display:flex;
    flex-wrap:wrap;
    gap:6px;
}
.sb-tag{
    font-size:12px;
    padding:5px 10px;
    background:#e2e8f0;
    border-radius:12px;
    text-decoration:none;
    color:#0f2044;
    font-weight:600;
}
.sb-tag:hover{
    background:#0f2044;
    color:#fff;
}

/* ==============================
   PAGINATION
============================== */
.pagination{
    margin:28px 0 0;
    text-align:center;
}
.pagination a{
    display:inline-block;
    padding:7px 12px;
    margin:0 3px;
    border-radius:8px;
    background:#e2e8f0;
    color:#0f2044;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
}
.pagination a.active{
    background:#0f2044;
    color:#fff;
}
.pagination a:hover{
    background:#0A1628;
    color:#fff;
}
</style>

<!-- ==============================
   HERO MAGAZINE
============================== -->
<section class="hero-blog-mag">
  <div class="hero-blog-inner">
    <div class="hero-blog-kicker">IBIG IMMO TRUST • BLOG EXPERT</div>
    <h1 class="hero-blog-title">
      Conseils, analyses et retours d’expérience en Immobilier, BTP & Financement.
    </h1>
    <p class="hero-blog-sub">
      Explorez nos articles pour sécuriser vos projets immobiliers, maîtriser vos chantiers
      et trouver le bon modèle de financement, y compris pour la diaspora.
    </p>
    <div class="hero-blog-meta-line">
      <?= $total ?> article<?= $total>1?'s':'' ?> publié<?= $total>1?'s':'' ?> • Mises à jour régulières
    </div>
  </div>
</section>

<!-- ==============================
   LAYOUT MAGAZINE
============================== -->
<section class="blog-layout">

  <!-- COLONNE PRINCIPALE -->
  <div>

    <!-- BARRE DE RECHERCHE + FILTRES ACTIFS -->
    <div class="blog-topbar">
      <div class="search-box-blog">
        <form action="/blog" method="get">
          <input type="text" name="search" placeholder="Rechercher un article…"
                 value="<?= htmlspecialchars($search) ?>">
        </form>
      </div>

      <div class="current-filters">
        <?php if($search || $tag || $catSlug || $catId): ?>
          <div>Filtres actifs :</div>
          <div class="filter-badges">
            <?php if($search): ?>
              <span class="filter-badge">Recherche : “<?= htmlspecialchars($search) ?>”</span>
            <?php endif; ?>
            <?php if($tag): ?>
              <span class="filter-badge">Tag : #<?= htmlspecialchars($tag) ?></span>
            <?php endif; ?>
            <?php if($catSlug || $catId): ?>
              <span class="filter-badge">Catégorie filtrée</span>
            <?php endif; ?>
          </div>
        <?php else: ?>
          Affichage des derniers articles.
        <?php endif; ?>
      </div>
    </div>

    <?php if(count($articles) === 0): ?>

      <p style="font-size:16px;color:#475569;">
        Aucun article trouvé pour ces critères.
      </p>

    <?php else: ?>

      <?php
      // Article "UNE" sur la première page
      $featured = null;
      $others   = $articles;

      if ($page === 1 && count($articles) > 0) {
          $featured = $articles[0];
          $others   = array_slice($articles, 1);
      }
      ?>

      <?php if($featured): ?>
      <!-- ARTICLE EN UNE -->
      <div class="mag-featured">
        <a href="/blog/<?= $featured['slug'] ?>" style="text-decoration:none;color:inherit;">
          <article class="mag-featured-card">
            <div>
              <?php if(!empty($featured['image'])): ?>
                <img src="/<?= htmlspecialchars($featured['image']) ?>" 
                     alt="<?= htmlspecialchars($featured['titre']) ?>" 
                     class="mag-featured-img" loading="lazy">
              <?php else: ?>
                <div class="mag-featured-img" style="background:#111827;"></div>
              <?php endif; ?>
            </div>
            <div class="mag-featured-body">
              <div>
                <?php if($featured['categorie']): ?>
                  <div class="mag-featured-cat">
                    <?= htmlspecialchars($featured['categorie']) ?>
                  </div>
                <?php endif; ?>
                <h2 class="mag-featured-title">
                  <?= htmlspecialchars($featured['titre']) ?>
                </h2>
                <p class="mag-featured-excerpt">
                  <?= excerpt_blog($featured['contenu'], 220) ?>
                </p>
              </div>
              <div>
                <div class="mag-featured-meta">
                  <span class="mag-meta-item">
                    <i class="fa-regular fa-calendar"></i>
                    <?= date("d M Y", strtotime($featured['created_at'])) ?>
                  </span>
                  <span class="mag-meta-item">
                    <i class="fa-solid fa-eye"></i>
                    <?= number_format((int)$featured['views'], 0, ',', ' ') ?> vues
                  </span>
                </div>
                <div>
                  <span class="mag-featured-link">
                    Lire l’article complet <span>→</span>
                  </span>
                </div>
              </div>
            </div>
          </article>
        </a>
      </div>
      <?php endif; ?>

      <!-- AUTRES ARTICLES EN GRID MAGAZINE -->
      <?php if(count($others) > 0): ?>
      <div class="mag-grid">
        <?php foreach($others as $a): ?>
          <a href="/blog/<?= $a['slug'] ?>" style="text-decoration:none;color:inherit;">
            <article class="mag-card">
              <?php if(!empty($a['image'])): ?>
                <img src="/<?= htmlspecialchars($a['image']) ?>" 
                     alt="<?= htmlspecialchars($a['titre']) ?>" 
                     class="mag-card-img" loading="lazy">
              <?php else: ?>
                <div class="mag-card-img"></div>
              <?php endif; ?>

              <div class="mag-card-body">
                <?php if($a['categorie']): ?>
                  <div class="mag-card-cat">
                    <?= htmlspecialchars($a['categorie']) ?>
                  </div>
                <?php endif; ?>

                <div class="mag-card-title">
                  <?= htmlspecialchars($a['titre']) ?>
                </div>

                <div class="mag-card-excerpt">
                  <?= excerpt_blog($a['contenu']) ?>
                </div>

                <div class="mag-card-meta">
                  <span>
                    <i class="fa-regular fa-calendar"></i>
                    <?= date("d M Y", strtotime($a['created_at'])) ?>
                  </span>
                  <span>
                    <i class="fa-solid fa-eye"></i>
                    <?= number_format((int)$a['views'], 0, ',', ' ') ?> vues
                  </span>
                </div>
              </div>
            </article>
          </a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <!-- PAGINATION -->
      <?php if($pages > 1): ?>
      <div class="pagination">
        <?php for($i=1; $i <= $pages; $i++): ?>
          <a href="/blog?page=<?= $i . $extra ?>"
             class="<?= ($i == $page ? 'active' : '') ?>">
             <?= $i ?>
          </a>
        <?php endfor; ?>
      </div>
      <?php endif; ?>

    <?php endif; ?>

  </div>

  <!-- SIDEBAR -->
  <aside class="sidebar">

    <!-- ARTICLES RÉCENTS -->
    <div class="sb-block">
      <div class="sb-title">Articles récents</div>
      <?php foreach($recentPosts as $rp): ?>
        <div class="sb-recent-item">
          <a href="/blog/<?= $rp['slug'] ?>" class="sb-recent-link">
            <?= htmlspecialchars($rp['titre']) ?>
          </a>
          <div class="sb-recent-date">
            <?= date("d M Y", strtotime($rp['created_at'])) ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    
    <!-- TOP ARTICLES -->
<div class="sb-block">
  <div class="sb-title">Top articles les plus lus</div>
  <p style="font-size:13px;color:#64748b;margin-bottom:8px;">
    Découvrez les contenus les plus consultés de nos lecteurs.
  </p>
  <a href="/blog/top-articles" class="sb-recent-link">
    Voir le classement →
  </a>
</div>

    <!-- CATÉGORIES -->
    <div class="sb-block">
      <div class="sb-title">Catégories</div>
      <div class="sb-cat-list">
        <?php foreach($cats as $c): ?>
          <a href="/blog/categorie/<?= $c['slug'] ?>">
            <span><?= htmlspecialchars($c['nom']) ?></span>
            <span class="count">›</span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- TAGS -->
    <?php if(count($tagList) > 0): ?>
    <div class="sb-block">
      <div class="sb-title">Tags</div>
      <div class="sb-tags">
        <?php foreach($tagList as $tg): ?>
          <a href="/blog/tag/<?= urlencode($tg) ?>" class="sb-tag">
            #<?= htmlspecialchars($tg) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </aside>

</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
