<?php
ob_start();
require_once __DIR__ . '/includes/config.php';

$currentPage = 'blog';
$DOMAIN = 'https://ibigimmotrust.com';

/* ============================================================
   1. RÉCUPÉRER ARTICLE VIA SLUG
============================================================ */
$slug = trim($_GET['slug'] ?? "");

if ($slug === "") {
    http_response_code(404);
    die("Article introuvable.");
}

$st = $pdo->prepare("
    SELECT a.*, c.nom AS categorie, c.slug AS cat_slug
    FROM blog_articles a
    LEFT JOIN blog_categories c ON c.id = a.categorie_id
    WHERE a.slug = ?
    LIMIT 1
");
$st->execute([$slug]);
$article = $st->fetch(PDO::FETCH_ASSOC);

if (!$article) {
    http_response_code(404);
    die("Article introuvable.");
}

$pageTitle = htmlspecialchars($article['titre']) . " – IBIG IMMO TRUST";

/* ============================================================
   2. AUGMENTER VUES
============================================================ */
$pdo->prepare("UPDATE blog_articles SET views = views + 1 WHERE id = ?")
    ->execute([$article['id']]);
$article['views']++;

/* ============================================================
   3. SEO + OPEN GRAPH (VERSION BÉTON)
============================================================ */
$descriptionSEO = trim(strip_tags($article['contenu']));
$descriptionSEO = mb_substr($descriptionSEO, 0, 160);

$imageSEO = !empty($article['image'])
    ? $DOMAIN . '/' . ltrim($article['image'], '/')
    : $DOMAIN . '/assets/img/og-default.jpg';

$urlSEO = $DOMAIN . '/blog/' . rawurlencode($slug);

$auteur = !empty($article['auteur'])
    ? htmlspecialchars($article['auteur'])
    : 'IBIG IMMO TRUST';

/* ============================================================
   4. GESTION COMMENTAIRES — VERSION PROPRE
============================================================ */
$notifComment = "";

if (!empty($_POST['add_comment'])) {

    $nom = trim($_POST['nom'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $commentaire = trim($_POST['commentaire'] ?? "");

    if ($nom === "" || $commentaire === "") {
        $notifComment = "<div class='cm-alert error'>Veuillez remplir les champs obligatoires.</div>";
    } else {

        $add = $pdo->prepare("
            INSERT INTO blog_comments (article_id, nom, email, commentaire, status, created_at)
            VALUES (?, ?, ?, ?, 'pending', NOW())
        ");
        $add->execute([$article['id'], $nom, $email, $commentaire]);

        $notifComment = "<div class='cm-alert success'>Votre commentaire est enregistré et en attente de validation.</div>";
    }
}

/* Récupération des commentaires validés */
$stCom = $pdo->prepare("
    SELECT nom, commentaire, created_at
    FROM blog_comments
    WHERE article_id = ? AND status = 'approved'
    ORDER BY created_at DESC
");
$stCom->execute([$article['id']]);
$comments = $stCom->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title><?= $pageTitle ?></title>
<meta name="description" content="<?= htmlspecialchars($descriptionSEO) ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CANONICAL -->
<link rel="canonical" href="<?= $urlSEO ?>">

<!-- OPEN GRAPH / FACEBOOK / WHATSAPP -->
<meta property="og:type" content="article">
<meta property="og:site_name" content="IBIG IMMO TRUST">
<meta property="og:title" content="<?= htmlspecialchars($article['titre']) ?>">
<meta property="og:description" content="<?= htmlspecialchars($descriptionSEO) ?>">
<meta property="og:image" content="<?= $imageSEO ?>">
<meta property="og:image:secure_url" content="<?= $imageSEO ?>">
<meta property="og:image:type" content="image/png">
<meta property="og:image:type" content="image/jpeg">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="<?= $urlSEO ?>">

<!-- TWITTER / X -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($article['titre']) ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($descriptionSEO) ?>">
<meta name="twitter:image" content="<?= $imageSEO ?>">

<!-- STYLE -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/front.css?v=1">
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* ============================================================
   STYLE LUXE — RESPONSIVE + LECTURE CONFORT
============================================================ */

/* Progress bar */
.read-progress {
    position: fixed;
    top: 0;
    left: 0;
    height: 4px;
    background: linear-gradient(90deg,#cfa34a,#e0c172);
    width: 0%;
    z-index: 9999;
}

/* HEADER ARTICLE */
.article-header {
    max-width: 900px;
    margin: 0 auto;
    padding: 25px;
}

.article-title {
    font-size: 40px;
    font-weight: 800;
    color: #0b1f55;
}

.breadcrumb {
    font-size: 14px;
    margin-bottom: 15px;
    color: #5b6575;
}
.breadcrumb a {
    color: #0b1f55;
    text-decoration: none;
}

.article-img {
    width: 100%;
    max-height: 450px;
    object-fit: cover;
    border-radius: 16px;
    margin: 22px 0;
}

/* MÉTA */
.meta-top {
    margin: 12px 0 25px;
    font-size: 15px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    color: #475569;
}
.meta-item { display: flex; align-items: center; gap: 6px; }
.meta-sep { color: #aaa; }

/* TAGS */
.tag-chip {
    background: #f1f5f9;
    padding: 6px 12px;
    border-radius: 30px;
    display: inline-block;
    margin: 5px;
    font-size: 13px;
    font-weight: 600;
    color: #0b1f55;
    text-decoration: none;
}
.tag-chip:hover {
    background: #0b1f55;
    color: white;
}

/* CONTENU */
.article-content {
    max-width: 900px;
    margin: 0 auto;
    padding: 20px;
    font-size: 18px;
    line-height: 1.75;
    color: #1e293b;
}
.article-content img {
    max-width: 100%;
    border-radius: 14px;
    margin: 20px 0;
}

/* SHARE */
.share-box {
    max-width: 900px;
    margin: 40px auto;
    padding: 20px;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    background: white;
}
.share-links {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.share-btn {
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 14px;
    color: white;
    font-weight: 600;
    text-decoration: none;
    display: flex;
    gap: 6px;
    align-items: center;
}
.fb { background:#1877f2; }
.wa { background:#25d366; }
.in { background:#0A66C2; }
.tw { background:black; }
.ml { background:#f97316; }

/* COMMENTAIRES */
.cm-alert {
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 15px;
    font-weight: 600;
}
.cm-alert.success { background:#e6f7ed; color:#216c3e; }
.cm-alert.error { background:#fde2e2; color:#a52626; }

.comment-box {
    background:#f8fafc;
    padding:15px;
    border-radius:12px;
    margin-bottom:12px;
}

.comment-form input, 
.comment-form textarea {
    width:100%;
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
    border:1px solid #d6d9e0;
}

/* CTA */
.article-cta {
    background: #0b1f55; /* ton bleu principal */
    color: #ffffff !important; /* texte en blanc */
    padding: 55px 25px;
    text-align: center;
    border-radius: 18px;
    margin-top: 40px;
}

.article-cta h2,
.article-cta p {
    color: #ffffff !important; /* force le blanc */
}

.article-cta h2 {
    font-size: 26px;
    font-weight: 800;
    margin-bottom: 12px;
}

.article-cta p {
    font-size: 17px;
    line-height: 1.6;
    opacity: 0.95;
}

/* Bouton */
.article-cta a {
    margin-top: 18px;
    display: inline-block;
    padding: 14px 28px;
    background: #ffffff;
    color: #0b1f55 !important; /* texte foncé sur fond blanc */
    border-radius: 12px;
    font-weight: 700;
    font-size: 16px;
    text-decoration: none;
}

@media(max-width:480px){
    .article-title{ font-size:28px; }
    .article-cta a{ width:100%; text-align:center; }
}
</style>
</head>
<body>

<?php include __DIR__ . '/includes/tracker.php'; ?>

<div class="read-progress" id="progressBar"></div>

<?php include __DIR__ . '/includes/header.php'; ?>

<div class="article-header">

    <div class="breadcrumb">
        <a href="/">Accueil</a> →
        <a href="/blog">Blog</a> →
        <?= htmlspecialchars($article['titre']) ?>
    </div>

    <?php if (!empty($article['image'])): ?>
        <img src="/<?= htmlspecialchars($article['image']) ?>"
             alt="<?= htmlspecialchars($article['titre']) ?>"
             class="article-img">
    <?php endif; ?>

    <h1 class="article-title"><?= htmlspecialchars($article['titre']) ?></h1>

    <div class="meta-top">

        <?php if ($article['categorie']): ?>
        <span class="meta-item">
            <i class="fa-solid fa-folder-open"></i>
            <a href="/blog/categorie/<?= $article['cat_slug'] ?>">
                <?= htmlspecialchars($article['categorie']) ?>
            </a>
        </span>
        <span class="meta-sep">•</span>
        <?php endif; ?>

        <span class="meta-item"><i class="fa-solid fa-eye"></i> <?= number_format($article['views']) ?> vues</span>
        <span class="meta-sep">•</span>

        <span class="meta-item">
            <i class="fa-regular fa-calendar"></i>
            <?= date("d/m/Y", strtotime($article['created_at'])) ?>
        </span>
        <span class="meta-sep">•</span>

        <span class="meta-item">
            <i class="fa-solid fa-user-pen"></i> <?= $auteur ?>
        </span>

    </div>

    <?php if (!empty($article['tags'])): ?>
        <div>
        <?php foreach (explode(',', $article['tags']) as $t): ?>
            <a class="tag-chip" href="/blog/tag/<?= urlencode(trim($t)) ?>">
                #<?= htmlspecialchars(trim($t)) ?>
            </a>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="article-content"><?= $article['contenu'] ?></div>

<!-- PARTAGE -->
<div class="share-box">
    <h3>Partager cet article</h3>
    <div class="share-links">
        <a class="share-btn wa" href="https://wa.me/?text=<?= urlencode($article['titre']." ".$urlSEO) ?>" target="_blank"><i class="fa-brands fa-whatsapp"></i> WhatsApp</a>
        <a class="share-btn fb" href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($urlSEO) ?>" target="_blank"><i class="fa-brands fa-facebook"></i> Facebook</a>
        <a class="share-btn in" href="https://www.linkedin.com/shareArticle?url=<?= urlencode($urlSEO) ?>" target="_blank"><i class="fa-brands fa-linkedin"></i> LinkedIn</a>
        <a class="share-btn tw" href="https://twitter.com/intent/tweet?url=<?= urlencode($urlSEO) ?>" target="_blank"><i class="fa-brands fa-x-twitter"></i> X</a>
        <a class="share-btn ml" href="mailto:?subject=<?= urlencode($article['titre']) ?>&body=<?= urlencode($urlSEO) ?>"><i class="fa-solid fa-envelope"></i> Email</a>
    </div>
</div>

<!-- COMMENTAIRES -->
<div class="comments-section" style="max-width:900px;margin:50px auto;">
    <h3 style="font-size:22px;font-weight:800;color:#0b1f55;">Commentaires</h3>

    <?= $notifComment ?>

    <?php if(count($comments)==0): ?>
        <p>Aucun commentaire pour le moment.</p>
    <?php endif; ?>

    <?php foreach($comments as $c): ?>
        <div class="comment-box">
            <strong><?= htmlspecialchars($c['nom']) ?></strong>
            <br>
            <em style="font-size:12px;color:#64748b;"><?= date("d/m/Y H:i", strtotime($c['created_at'])) ?></em>
            <p><?= nl2br(htmlspecialchars($c['commentaire'])) ?></p>
        </div>
    <?php endforeach; ?>
</div>

<!-- FORMULAIRE COMMENTAIRE -->
<div class="comment-form" style="max-width:900px;margin:40px auto;">
    <h4 style="font-size:20px;font-weight:700;">Laisser un commentaire</h4>

    <form method="post">
        <input type="hidden" name="add_comment" value="1">

        <input type="text" name="nom" placeholder="Votre nom *" required>
        <input type="email" name="email" placeholder="Votre email (facultatif)">
        <textarea name="commentaire" placeholder="Votre message…" required></textarea>

        <button style="padding:12px 18px;background:#0b1f55;color:#fff;border:none;border-radius:8px;font-weight:700;">
            Envoyer
        </button>
    </form>
</div>

<!-- CTA -->
<div class="article-cta">
    <h2>Un projet immobilier, BTP ou financement ?</h2>
    <p>Nos experts vous accompagnent dans toutes les étapes.</p>
    <a href="/contact.php">Décrire mon projet</a>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

<!-- Progress bar JS -->
<script>
document.addEventListener("scroll", function() {
    let docHeight = document.body.scrollHeight - window.innerHeight;
    let scrolled = (window.scrollY / docHeight) * 100;
    document.getElementById("progressBar").style.width = scrolled + "%";
});
</script>

</body>
</html>
