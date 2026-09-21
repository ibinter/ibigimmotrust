<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

$currentPage = 'blog';
$pageTitle   = "Top Articles – Blog Immobilier & BTP – IBIG IMMO TRUST";

include __DIR__ . '/includes/header.php';

/* ============================================================
   FONCTION EXCERPT (MANQUANTE) — FIX DÉFINITIF
============================================================ */
function excerpt_blog($text, $limit = 170) {
    $plain = trim(strip_tags($text));

    if (mb_strlen($plain) <= $limit) {
        return $plain;
    }

    // Coupe propre (ne coupe pas un mot en plein milieu)
    $excerpt = mb_substr($plain, 0, $limit);
    $lastSpace = mb_strrpos($excerpt, ' ');
    if ($lastSpace !== false) {
        $excerpt = mb_substr($excerpt, 0, $lastSpace);
    }

    return $excerpt . "…";
}

/* ============================================================
   PARAMÈTRES
============================================================ */
$perPage = 9;
$page    = max(1, (int)($_GET['page'] ?? 1));
$start   = ($page - 1) * $perPage;

/* ============================================================
   TOTAL ARTICLES AVEC VUES
============================================================ */
$stTotal = $pdo->query("
    SELECT COUNT(*) 
    FROM blog_articles
    WHERE views > 0
");
$total = (int)$stTotal->fetchColumn();
$pages = max(1, ceil($total / $perPage));

/* ============================================================
   RÉCUPÉRATION DES ARTICLES CLASSÉS PAR POPULARITÉ
============================================================ */
$st = $pdo->prepare("
    SELECT a.*, c.nom AS categorie, c.slug AS cat_slug
    FROM blog_articles a
    LEFT JOIN blog_categories c ON c.id = a.categorie_id
    WHERE a.views > 0
    ORDER BY a.views DESC, a.created_at DESC
    LIMIT :start, :perpage
");
$st->bindValue(':start', $start, PDO::PARAM_INT);
$st->bindValue(':perpage', $perPage, PDO::PARAM_INT);
$st->execute();
$articles = $st->fetchAll(PDO::FETCH_ASSOC);

?>
<style>
.top-hero {
    background: radial-gradient(circle at top left,#1f2937,#0b1f55 45%,#020617);
    padding:60px 20px 45px;
    color:#fff;
}
.top-hero-inner{
    max-width:1100px;
    margin:0 auto;
}
.top-hero-title{
    font-size:36px;
    font-weight:800;
    margin-bottom:8px;
    color:#ffffff !important; /* TITRE BIEN VISIBILE */
    text-shadow:0 2px 8px rgba(0,0,0,0.35); /* Lisibilité améliorée */
}
.top-hero-sub{
    font-size:16px;
    max-width:680px;
    line-height:1.6;
    color:#e5e7eb;
}
.top-hero-meta{
    margin-top:6px;
    font-size:14px;
    color:#cbd5e1;
}

/* GRID */
.top-layout{
    max-width:1100px;
    margin:0 auto;
    padding:25px 20px 45px;
}
.top-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(260px,1fr));
    gap:20px;
}
.top-card{
    background:#fff;
    border-radius:14px;
    overflow:hidden;
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
    display:flex;
    flex-direction:column;
    text-decoration:none;
    color:#0b1f55;
    position:relative;
}
.top-rank{
    position:absolute;
    top:10px;
    left:10px;
    background:rgba(15,23,42,0.9);
    color:#fff;
    padding:4px 9px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}
.top-card-img{
    width:100%;
    height:170px;
    object-fit:cover;
    background:#e5e7eb;
}
.top-card-body{
    padding:14px 15px 13px;
    display:flex;
    flex-direction:column;
    gap:6px;
    flex:1;
}
.top-card-cat{
    font-size:11px;
    text-transform:uppercase;
    color:#64748b;
    letter-spacing:.08em;
}
.top-card-title{
    font-size:16px;
    font-weight:700;
}
.top-card-excerpt{
    font-size:14px;
    color:#475569;
}
.top-card-meta{
    font-size:12px;
    color:#94a3b8;
    margin-top:auto;
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}
.top-card-meta span{
    display:inline-flex;
    align-items:center;
    gap:5px;
}

/* PAGINATION */
.pagination{
    margin:30px 0 0;
    text-align:center;
}
.pagination a{
    display:inline-block;
    padding:7px 12px;
    margin:0 3px;
    border-radius:8px;
    background:#e2e8f0;
    color:#0b1f55;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
}
.pagination a.active{
    background:#0b1f55;
    color:#fff;
}
.pagination a:hover{
    background:#1d4ed8;
    color:#fff;
}
</style>

<!-- HERO -->
<section class="top-hero">
  <div class="top-hero-inner">
    <h1 class="top-hero-title">Top Articles les plus lus</h1>
    <p class="top-hero-sub">
      Découvrez les articles qui ont le plus intéressé nos lecteurs : immobilier, BTP, financement,
      sécurisation des projets et conseils pour la diaspora.
    </p>
    <div class="top-hero-meta">
      Classement automatique par nombre de vues • <?= $total ?> article<?= $total>1?'s':'' ?> au total
    </div>
  </div>
</section>

<section class="top-layout">

  <?php if(count($articles) === 0): ?>
    <p style="font-size:16px;color:#475569;">
      Aucun article n’a encore suffisamment de vues pour apparaître dans ce classement.
    </p>
  <?php else: ?>

    <div class="top-grid">
      <?php $rank = $start + 1; ?>
      <?php foreach($articles as $a): ?>
        <a href="/blog/<?= $a['slug'] ?>" class="top-card">

          <div class="top-rank">#<?= $rank ?></div>

          <?php if(!empty($a['image'])): ?>
            <img src="/<?= htmlspecialchars($a['image']) ?>"
                 alt="<?= htmlspecialchars($a['titre']) ?>"
                 class="top-card-img" loading="lazy">
          <?php else: ?>
            <div class="top-card-img"></div>
          <?php endif; ?>

          <div class="top-card-body">
            <?php if($a['categorie']): ?>
              <div class="top-card-cat"><?= htmlspecialchars($a['categorie']) ?></div>
            <?php endif; ?>

            <div class="top-card-title"><?= htmlspecialchars($a['titre']) ?></div>

            <div class="top-card-excerpt"><?= excerpt_blog($a['contenu']) ?></div>

            <div class="top-card-meta">
              <span><i class="fa-regular fa-calendar"></i><?= date("d M Y", strtotime($a['created_at'])) ?></span>
              <span><i class="fa-solid fa-eye"></i><?= number_format((int)$a['views'], 0, ',', ' ') ?> vues</span>
            </div>
          </div>

        </a>
      <?php $rank++; endforeach; ?>
    </div>

    <?php if($pages > 1): ?>
    <div class="pagination">
      <?php for($i=1; $i <= $pages; $i++): ?>
        <a href="/blog/top-articles?page=<?= $i ?>" class="<?= ($i == $page ? 'active' : '') ?>">
           <?= $i ?>
        </a>
      <?php endfor; ?>
    </div>
    <?php endif; ?>

  <?php endif; ?>

</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
