<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'btp';
$pageTitle   = "Construction de maisons & immeubles – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">BTP • Construction neuve • Livraison clé en main</p>

      <h1 class="hero-title">
        Construction de maisons & immeubles :  
        Un accompagnement complet, professionnel & sécurisé.
      </h1>

      <p class="hero-subtitle">
        IBIG IMMO TRUST réalise vos projets de construction (maisons, villas, immeubles, bureaux)
        depuis l’étude jusqu’à la remise des clés, avec supervision complète et reporting digital.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary">Démarrer mon projet</a>
        <a href="rdv.php" class="btn-secondary">Prendre rendez-vous</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/construction-afrique.jpg" alt="Construction IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>


<!-- ===================== SECTION 1 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Ce que nous construisons</h2>
    <p class="section-intro">
      Nous réalisons des constructions modernes et durables, adaptées au marché ivoirien
      et aux standards internationaux, avec un haut niveau de supervision technique.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3> Maisons individuelles</h3>
        <p>Construction complète sur plans ou sur mesure (auto-construction possible).</p>
      </article>

      <article class="card">
        <h3> Villas & duplex</h3>
        <p>Projets premium pour résidences principales ou à but locatif.</p>
      </article>

      <article class="card">
        <h3> Petits immeubles</h3>
        <p>R+1 à R+4, budget optimisé, circulation d’air, rentabilité locative renforcée.</p>
      </article>

      <article class="card">
        <h3> Locaux professionnels</h3>
        <p>Bureaux, showrooms, petits commerces, boutiques.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 2 ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Notre approche : qualité, sécurité, transparence</h2>

    <div class="cards-grid">

      <article class="card">
        <h3> Étude & plans</h3>
        <p>Plans d’architecte, métrés, modèles 3D, optimisation du budget.</p>
      </article>

      <article class="card">
        <h3> Construction durable</h3>
        <p>Qualité des matériaux, respect des normes techniques, artisans qualifiés.</p>
      </article>

      <article class="card">
        <h3> Suivi & supervision</h3>
        <p>Supervision régulière, contrôles techniques, validation des étapes.</p>
      </article>

      <article class="card">
        <h3> Reporting diaspora</h3>
        <p>Photos + vidéos hebdomadaires, visites virtuelles, réunions de suivi.</p>
      </article>

      <article class="card">
        <h3> Budget sécurisé</h3>
        <p>Contrats clairs, étapes de paiement sécurisées, transparence totale.</p>
      </article>

      <article class="card">
        <h3> Livraison clé en main</h3>
        <p>Nettoyage, finitions, corrections, remise finale des clés.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION PROCESS ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Processus de construction IBIG IMMO TRUST</h2>

    <div class="steps-grid">

      <article class="step">
        <div class="step-number">1</div>
        <h3>Analyse du terrain</h3>
        <p>Étude du sol, accès, bornage, vérifications foncières.</p>
      </article>

      <article class="step">
        <div class="step-number">2</div>
        <h3>Plans & devis</h3>
        <p>Plans d’architecture + devis complet + planning détaillé.</p>
      </article>

      <article class="step">
        <div class="step-number">3</div>
        <h3>Lancement du chantier</h3>
        <p>Fondation, élévation, toiture, installations techniques.</p>
      </article>

      <article class="step">
        <div class="step-number">4</div>
        <h3>Finitions & équipements</h3>
        <p>Carrelage, peinture, portes, électricité, plomberie, déco.</p>
      </article>

      <article class="step">
        <div class="step-number">5</div>
        <h3>Livraison & mise en location</h3>
        <p>Contrôle qualité, nettoyage final et mise en gestion IBIG si souhaité.</p>
      </article>

    </div>

  </div>
</section>



<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Vous avez un projet de construction ?</h2>
      <p>Nos architectes, ingénieurs et superviseurs sont prêts à vous accompagner.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Démarrer mon projet</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>