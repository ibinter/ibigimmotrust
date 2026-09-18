<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'financement';
$pageTitle   = "Investisseurs agréés – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Financement • Investisseurs privés • Partenariat</p>

      <h1 class="hero-title">
        Financement par investisseurs agréés : une solution souple et rapide.
      </h1>

      <p class="hero-subtitle">
        Des investisseurs privés ou institutionnels cofinancent votre projet de
        construction, rénovation ou finition. Vous remboursez sur les loyers générés.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary">Soumettre mon projet</a>
        <a href="rdv.php" class="btn-secondary">Prendre RDV</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/investisseurs-afrique.jpg" alt="Investisseurs agréés IBIG">
      </div>
    </div>

  </div>
</section>


<!-- ===================== SECTION 1 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Principe du modèle investisseurs agréés</h2>
    <p class="section-intro">
      Un groupe d’investisseurs solides finance tout ou partie de votre projet.
      Le remboursement se fait à partir des loyers générés après livraison.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3>ð° Avance demandée : 10 à 20 %</h3>
        <p>Le reste du financement est apporté par les investisseurs.</p>
      </article>

      <article class="card">
        <h3>ð Durée : 3 à 5 ans</h3>
        <p>Remboursements à partir des loyers ou d’un plan adapté.</p>
      </article>

      <article class="card">
        <h3>ð Rentabilité exigée</h3>
        <p>Projets à fort potentiel locatif ou valeur marchande élevée.</p>
      </article>

      <article class="card">
        <h3>ð Contrat tripartite</h3>
        <p>Propriétaire — Investisseur — IBIG pour un maximum de sécurité.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 3 ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Pour quels types de projets ?</h2>

    <div class="cards-grid">

      <article class="card"><h3>ð  Construction de maison</h3><p>Résidence personnelle ou mise en location.</p></article>
      <article class="card"><h3>ð¢ Petits immeubles</h3><p>Investissement à forte rentabilité locative.</p></article>
      <article class="card"><h3>ðï¸ Chantiers inachevés</h3><p>Reprise complète ou finition accélérée.</p></article>
      <article class="card"><h3>ð ï¸ Rénovations lourdes</h3><p>Valorisation avant revente ou location.</p></article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">
    <div>
      <h2>Votre projet est éligible ?</h2>
      <p>Envoyez-nous vos documents et recevez un avis d’éligibilité sous 48h.</p>
    </div>
    <a href="contact.php" class="btn-primary btn-large">Soumettre un dossier</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
