<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'btp';
$pageTitle   = "Chantiers inachevés – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">BTP • Finition • Supervision • Rattrapage</p>

      <h1 class="hero-title">
        Reprise & finition de chantiers inachevés.  
        Livraison sécurisée, rapide & professionnelle.
      </h1>

      <p class="hero-subtitle">
        Votre chantier a été abandonné, mal réalisé ou bloqué ?  
        IBIG IMMO TRUST reprend votre projet, sécurise la vision initiale
        et assure une livraison clé en main avec supervision complète.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary">Décrire mon chantier</a>
        <a href="rdv.php" class="btn-secondary">Prendre rendez-vous</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/chantier-inacheve.jpg" alt="Chantiers inachevés IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>


<!-- ===================== SECTION 1 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Reprendre un chantier inachevé : notre expertise</h2>
    <p class="section-intro">
      IBIG IMMO TRUST se charge du diagnostic technique, du rattrapage des erreurs,
      de la planification, de la gestion des artisans et de la supervision complète.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3>ð Diagnostic complet</h3>
        <p>État des lieux, défauts, malfaçons, estimation des travaux restants.</p>
      </article>

      <article class="card">
        <h3>ð§± Correction & reprise</h3>
        <p>
          Reprise de maçonnerie, électricité, plomberie, charpente, étanchéité
          et finitions.
        </p>
      </article>

      <article class="card">
        <h3>ð ï¸ Organisation des corps de métiers</h3>
        <p>
          Coordination des artisans qualifiés et suivi rigoureux du chantier.
        </p>
      </article>

      <article class="card">
        <h3>ð¸ Reporting digital diaspora</h3>
        <p>
          Photos, vidéos, visites virtuelles et comptes-rendus techniques.
        </p>
      </article>

      <article class="card">
        <h3>ð§ Planification & calendrier</h3>
        <p>
          Planning clair, étapes validées et supervision hebdomadaire.
        </p>
      </article>

      <article class="card">
        <h3>ð Livraison sécurisée</h3>
        <p>
          Contrôle qualité, corrections finales, nettoyage et remise des clés.
        </p>
      </article>

    </div>

  </div>
</section>



<!-- ===================== SECTION 2 ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Pour quels types de chantiers ?</h2>

    <div class="cards-grid">

      <article class="card"><h3>ð  Maisons individuelles</h3><p>Rattrapage complet ou finitions finales.</p></article>
      <article class="card"><h3>ð¢ Immeubles</h3><p>Travaux structurels ou finitions des appartements.</p></article>
      <article class="card"><h3>ð¬ Locaux commerciaux</h3><p>Reprise et mise aux normes techniques.</p></article>
      <article class="card"><h3>ðï¸ Résidences meublées</h3><p>Finitions, déco, installations électriques & plomberie.</p></article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 3 ===================== -->
<section class="section">
  <div class="container">
    
    <h2 class="section-title">Notre processus de reprise</h2>
    <div class="steps-grid">

      <article class="step">
        <div class="step-number">1</div>
        <h3>Visite & diagnostic</h3>
        <p>État réel du chantier, malfaçons, travaux restants, budget.</p>
      </article>

      <article class="step">
        <div class="step-number">2</div>
        <h3>Devis & contrat</h3>
        <p>Proposition détaillée, calendrier, responsabilités & garanties.</p>
      </article>

      <article class="step">
        <div class="step-number">3</div>
        <h3>Démarrage & contrôle</h3>
        <p>Supervision IBIG, coordination & contrôles techniques.</p>
      </article>

      <article class="step">
        <div class="step-number">4</div>
        <h3>Finitions & livraison</h3>
        <p>Corrections, peinture, installations, nettoyage final.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Votre chantier est bloqué ou inachevé ?</h2>
      <p>Obtenez un diagnostic complet et un plan de reprise en 48h.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Décrire mon chantier</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
