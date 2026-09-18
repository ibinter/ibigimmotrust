<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'btp';
$pageTitle   = "BTP – Construction & Finition – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Construction • Finition • Rénovation • Chantiers inachevés</p>

      <h1 class="hero-title">
        Construction & BTP : des travaux sécurisés, supervisés et livrés clé en main.
      </h1>

      <p class="hero-subtitle">
        IBIG IMMO TRUST accompagne propriétaires, investisseurs et diaspora dans
        la finition de chantiers, la construction neuve, la rénovation et la supervision
        technique complète de vos projets BTP.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary">Décrire mon projet</a>
        <a href="rdv.php" class="btn-secondary">Prendre RDV</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/construction-afrique.jpg" alt="BTP IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>


<!-- ===================== NOS DOMAINES ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Nos domaines d’intervention en BTP</h2>
    <p class="section-intro">
      Nous intervenons sur toute la chaîne de valeur BTP : diagnostic, étude,
      réalisation, supervision, livraison et reporting digital pour la diaspora.
    </p>

    <div class="cards-grid">

      <article class="card">
        <img src="assets/img/chantier-inacheve.jpg" class="card-img">
        <h3>Chantiers inachevés</h3>
        <p>Nous reprenons les chantiers abandonnés ou mal réalisés et assurons la finition complète.</p>
        <a href="chantier-inacheve.php" class="btn-secondary" style="margin-top:8px;">Voir le service</a>
      </article>

      <article class="card">
        <img src="assets/img/construction-afrique.jpg" class="card-img">
        <h3>Construction neuve</h3>
        <p>De la fondation à la remise des clés, nous réalisons vos maisons, immeubles et locaux professionnels.</p>
        <a href="construction.php" class="btn-secondary" style="margin-top:8px;">Découvrir</a>
      </article>

      <article class="card">
        <img src="assets/img/renovation-afrique.jpg" class="card-img">
        <h3>Rénovation & modernisation</h3>
        <p>Mise à niveau technique et esthétique de biens anciens ou dégradés.</p>
        <a href="renovation.php" class="btn-secondary" style="margin-top:8px;">Voir la rénovation</a>
      </article>

      <article class="card">
        <img src="assets/img/renovation-btp.jpg" class="card-img">
        <h3>Finitions & décoration</h3>
        <p>Peinture, carrelage, faux plafond, portes, éclairage et décoration intérieure.</p>
        <a href="renovation.php" class="btn-secondary" style="margin-top:8px;">Nos finitions</a>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION PROCESS ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Notre processus de construction</h2>
    <p class="section-intro">
      Un processus clair, transparent et sécurisé, adapté aux propriétaires résidents et à la diaspora.
    </p>

    <div class="steps-grid">

      <article class="step">
        <div class="step-number">1</div>
        <h3>Étude & devis</h3>
        <p>Analyse technique, estimation des coûts, planification des travaux.</p>
      </article>

      <article class="step">
        <div class="step-number">2</div>
        <h3>Contrat & planification</h3>
        <p>Contrat clair, calendrier, étapes de paiement sécurisées.</p>
      </article>

      <article class="step">
        <div class="step-number">3</div>
        <h3>Exécution & supervision</h3>
        <p>Réalisation par artisans qualifiés et supervision hebdomadaire IBIG.</p>
      </article>

      <article class="step">
        <div class="step-number">4</div>
        <h3>Reporting & contrôle qualité</h3>
        <p>Photos, vidéos, situations de travaux, contrôles techniques continus.</p>
      </article>

      <article class="step">
        <div class="step-number">5</div>
        <h3>Livraison & assistance</h3>
        <p>Nettoyage, remise des clés, corrections, finitions finales.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION GARANTIES ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Nos garanties & engagements</h2>

    <div class="cards-grid">

      <article class="card">
        <h3>ð Transparence totale</h3>
        <p>Budgets, matériaux, photos, vidéos et suivis partagés.</p>
      </article>

      <article class="card">
        <h3>ð ï¸ Artisans qualifiés</h3>
        <p>Réseau de professionnels certifiés pour chaque corps de métier.</p>
      </article>

      <article class="card">
        <h3>ð Paiements sécurisés</h3>
        <p>Étapes de paiement claires, contrôles techniques IBIG.</p>
      </article>

      <article class="card">
        <h3>ð Suivi diaspora</h3>
        <p>Reporting vidéo et visites virtuelles à chaque étape.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">
    <div>
      <h2>Un projet de construction ou de finition ?</h2>
      <p>Confiez votre projet à une équipe professionnelle & transparente.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Décrire mon projet</a>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>