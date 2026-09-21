<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'projets';
$pageTitle   = "Nos Projets & Réalisations – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Projets • Réalisations • Chantiers</p>

      <h1 class="hero-title">
        Découvrez nos réalisations en construction, rénovation et gestion immobilière.
      </h1>

      <p class="hero-subtitle">
        IBIG IMMO TRUST transforme des terrains, des chantiers inachevés et des biens en actifs
        modernes, rentables et sécurisés. Voici une sélection de projets réalisés et en cours.
      </p>

      <div class="hero-cta">
        <a href="#projets-grid" class="btn-primary">Voir les projets</a>
        <a href="contact.php" class="btn-secondary">Soumettre un projet</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="<?php echo BASE_URL; ?>/assets/img/construction-afrique2.jpg" alt="Projets IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>


<!-- ===================== GRID DES PROJETS ===================== -->
<section id="projets-grid" class="section">
  <div class="container">

    <h2 class="section-title">Nos projets récents</h2>
    <p class="section-intro">
      Voici quelques réalisations montrant l’expertise de IBIG IMMO TRUST dans la construction,
      la rénovation et la gestion immobilière en Côte d’Ivoire.
    </p>

    <div class="cards-grid">

      <!-- PROJET 1 -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/chantier-inacheve.jpg" class="card-img" alt="">
        <h3>Finition d’un chantier inachevé – Cocody</h3>
        <p>
          Reprise complète du chantier (maçonnerie, enduits, plomberie, électricité),
          livraison dans les délais et mise en location assurée par IBIG.
        </p>
      </article>

      <!-- PROJET 2 -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/renovation-afrique.jpg" class="card-img" alt="">
        <h3>Rénovation totale – Bingerville</h3>
        <p>
          Modernisation intérieure/extérieure, peinture premium, carrelage, sanitaires,
          éclairage LED et aménagement valorisant le bien pour la location.
        </p>
      </article>

      <!-- PROJET 3 -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/construction-afrique.jpg" class="card-img" alt="">
        <h3>Construction villa duplex – Abatta</h3>
        <p>
          Construction clé en main réalisée de la fondation à la toiture,
          avec supervision IBIG et optimisation du budget client.
        </p>
      </article>

      <!-- PROJET 4 -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/gestion-locative.jpg" class="card-img" alt="">
        <h3>Gestion locative d’un immeuble – Riviera</h3>
        <p>
          Mise en location, perception des loyers, maintenance, reporting digital
          et option loyer garanti selon contrat.
        </p>
      </article>

      <!-- PROJET 5 -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/renovation-btp.jpg" class="card-img" alt="">
        <h3>Valorisation locative – Angré</h3>
        <p>
          Amélioration esthétique, rénovation des pièces, décoration moderne
          et optimisation du rendement locatif.
        </p>
      </article>

      <!-- PROJET 6 -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/investisseurs-afrique.jpg" class="card-img" alt="">
        <h3>Accompagnement diaspora – USA & Europe</h3>
        <p>
          Suivi en temps réel, reporting digital, vidéos de chantier et gestion complète
          pour les clients vivant hors de Côte d’Ivoire.
        </p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== AVANT / APRÈS ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Avant / Après</h2>
    <p class="section-intro">
      Quelques exemples de transformations majeures réalisées par nos équipes.
    </p>

    <div class="cards-grid">

      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/chantier-inacheve.jpg" class="card-img">
        <h3>Avant : chantier abandonné</h3>
        <p>Structure brute et malfaçons.</p>
      </article>

      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/construction-afrique.jpg" class="card-img">
        <h3>Après : finition Premium IBIG</h3>
        <p>Livraison clé en main, prêt à habiter / louer.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Vous avez un projet en tête ?</h2>
      <p>
        Expliquez-nous votre besoin : construction, rénovation, gestion ou financement.
      </p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Présenter mon projet</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
