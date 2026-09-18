<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'services';
$pageTitle   = "Rénovation immobilière – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Rénovation • Modernisation • Mise en valeur</p>

      <h1 class="hero-title">
        Rénovation immobilière : modernisez, valorisez et augmentez la rentabilité de votre bien.
      </h1>

      <p class="hero-subtitle">
        IBIG IMMO TRUST transforme vos biens anciens ou dégradés en espaces modernes,
        fonctionnels et attractifs, prêts pour la location ou la revente.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary">Demander un devis</a>
        <a href="rdv.php" class="btn-secondary">Prendre RDV</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/renovation-btp.jpg" alt="Rénovation immobilière IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>


<!-- ===================== SECTION 1 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Pourquoi rénover votre bien ?</h2>
    <p class="section-intro">
      Une rénovation bien réalisée permet d’augmenter la valeur de votre bien jusqu’à
      <strong>+40%</strong> et d’attirer des locataires plus solvables.  
      La rénovation est aussi la solution idéale pour redonner vie à un bien abandonné ou vieillissant.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3>ð Augmentation de valeur</h3>
        <p>Modernisation, optimisation des espaces, finitions professionnelles.</p>
      </article>

      <article class="card">
        <h3>ð° Rentabilité locative</h3>
        <p>
          Un bien rénové se loue plus vite, plus cher et avec moins de risques
          d’impayés.
        </p>
      </article>

      <article class="card">
        <h3>ð§ Correction des défauts</h3>
        <p>
          Infiltrations, fissures, électricité, plomberie, isolation :
          nous corrigeons tout.
        </p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 2 ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Nos services de rénovation</h2>

    <div class="cards-grid">

      <article class="card">
        <h3>ðï¸ Rénovation complète</h3>
        <p>Nous reprenons entièrement le bien : murs, sols, plafonds, réseaux, peinture.</p>
      </article>

      <article class="card">
        <h3>ð ï¸ Rénovation technique</h3>
        <p>
          Électricité, plomberie, maçonnerie, étanchéité, menuiserie,
          façades, sanitaires.
        </p>
      </article>

      <article class="card">
        <h3>ð¨ Décoration & design intérieur</h3>
        <p>
          Modernisation esthétique : peinture, carrelage, faux plafonds,
          éclairage, ameublement.
        </p>
      </article>

      <article class="card">
        <h3>ð  Rénovation de pièces spécifiques</h3>
        <p>
          Cuisines, salles de bains, salons, chambres, bureaux, boutiques.
        </p>
      </article>

      <article class="card">
        <h3>ð¸ Mise en valeur marketing</h3>
        <p>Photos professionnelles & home staging pour revente ou location.</p>
      </article>

      <article class="card">
        <h3>ð Suivi pour la diaspora</h3>
        <p>Photos, vidéos, rapports réguliers : transparence totale.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 3 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Types de biens que nous rénovons</h2>

    <div class="cards-grid">

      <article class="card">
        <h3>ð  Maisons & villas</h3>
        <p>Modernisation complète, valorisation et optimisation des volumes.</p>
      </article>

      <article class="card">
        <h3>ð¢ Immeubles</h3>
        <p>Rénovation des appartements, espaces communs, façades, toitures.</p>
      </article>

      <article class="card">
        <h3>ð¬ Locaux commerciaux</h3>
        <p>Boutiques, bureaux, entrepôts, showrooms.</p>
      </article>

      <article class="card">
        <h3>ðï¸ Studios & résidences meublées</h3>
        <p>
          Rénovation rapide et modernisation pour maximiser la rentabilité
          locative.
        </p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Votre bien a besoin d’une rénovation ?</h2>
      <p>Un expert IBIG peut analyser votre projet dès aujourd’hui.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Demander un devis</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
