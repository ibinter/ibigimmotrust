<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'services';
$pageTitle   = "Modèle Direct IBIG – Préfinancement Immobilier";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Financement innovant • Modèle exclusif IBIG</p>

      <h1 class="hero-title">
        Le Modèle Direct IBIG : réalisez votre chantier sans avance, remboursé sur les loyers.
      </h1>

      <p class="hero-subtitle">
        IBIG IMMO TRUST préfinance la finition ou la construction de votre bien.
        Vous ne payez rien au départ : les travaux sont remboursés grâce aux loyers générés
        après mise en location, sur une période de <strong>3 à 7 ans</strong>.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary">Éligibilité & devis</a>
        <a href="rdv.php" class="btn-secondary">Prendre rendez-vous</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/chantier-inacheve.jpg" alt="Direct IBIG – Préfinancement immobilier">
      </div>
    </div>

  </div>
</section>


<!-- ===================== SECTION 1 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Qu’est-ce que le Modèle Direct IBIG ?</h2>
    <p class="section-intro">
      C’est une solution unique sur le marché ivoirien qui permet à un propriétaire de
      <strong>finir ou construire son bien sans débourser d’avance</strong>.
      IBIG IMMO TRUST finance les travaux, puis se rembourse sur les loyers perçus.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3> Avance propriétaire : 0 FCFA</h3>
        <p>Aucune sortie d’argent initiale. IBIG finance 100% des travaux éligibles.</p>
      </article>

      <article class="card">    
      <h3> Remboursement sur 3 à 7 ans</h3>
        <p>Les loyers perçus servent à rembourser progressivement le financement IBIG.</p>
      </article>

      <article class="card">
        <h3> Mise en location assurée</h3>
        <p>
          IBIG prend en charge la location, les visites, la gestion locative
          et, selon le contrat, le loyer garanti.
        </p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 2 ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Projets éligibles au Direct IBIG</h2>
    <p class="section-intro">
      Le modèle est conçu pour transformer les biens en difficulté en actifs rentables.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3> Chantiers inachevés</h3>
        <p>Maisons, immeubles, studios, locaux professionnels.</p>
      </article>

      <article class="card">
        <h3> Biens délabrés ou abandonnés</h3>
        <p>Rénovation complète + mise en valeur marketing.</p>
      </article>

      <article class="card">
        <h3> Projets diaspora bloqués</h3>
        <p>IBIG reprend le chantier et garantit un suivi digital complet.</p>
      </article>

      <article class="card">
        <h3> Petites constructions</h3>
        <p>Studios, mini-villas, logements locatifs rapides à rentabiliser.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 3 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Comment fonctionne le Direct IBIG ?</h2>

    <div class="steps-grid">

      <div class="step">
        <div class="step-number">1</div>
        <h3>Diagnostic technique</h3>
        <p>Analyse du chantier, estimation des coûts, étude de faisabilité et rentabilité.</p>
      </div>

      <div class="step">
        <div class="step-number">2</div>
        <h3>Contrat de préfinancement</h3>
        <p>Conditions, montant financé, durée, obligations, calendrier des travaux.</p>
      </div>

      <div class="step">
        <div class="step-number">3</div>
        <h3>Exécution & supervision</h3>
        <p>
          IBIG mobilise ses équipes, supervise les travaux, fournit un reporting régulier
          (photos, vidéos, visites).
        </p>
      </div>

      <div class="step">
        <div class="step-number">4</div>
        <h3>Mise en location & remboursement</h3>
        <p>Les loyers perçus servent à rembourser IBIG jusqu’à la fin du contrat.</p>
      </div>

    </div>

  </div>
</section>


<!-- ===================== SECTION 4 ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Avantages du Modèle Direct IBIG</h2>

    <div class="cards-grid">

      <article class="card">
        <h3> Démarrage rapide</h3>
        <p>Les travaux commencent même si vous manquez de budget.</p>
      </article>

      <article class="card">
        <h3> Aucun prêt bancaire nécessaire</h3>
        <p>Pas de dossier bancaire, pas de taux, pas d'endettement.</p>
      </article>

      <article class="card">
        <h3> Suivi pour la diaspora</h3>
        <p>Reporting digital complet et transparence totale.</p>
      </article>

      <article class="card">
        <h3> Valorisation patrimoniale</h3>
        <p>Votre bien devient rentable et prend de la valeur immédiatement.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Votre chantier est bloqué ou inachevé ?</h2>
      <p>Découvrez si vous êtes éligible au Modèle Direct IBIG.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Demander mon étude gratuite</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
