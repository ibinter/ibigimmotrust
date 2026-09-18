<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'partenaires';
$pageTitle   = "Partenaires & Collaborations – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Partenaires • Investisseurs • Collaborations</p>

      <h1 class="hero-title">
        Collaborons pour développer des projets immobiliers & BTP durables en Côte d’Ivoire.
      </h1>

      <p class="hero-subtitle">
        IBIG IMMO TRUST travaille avec des investisseurs, entreprises BTP, banques, microfinances,
        fournisseurs, promoteurs immobiliers et organisations internationales pour réaliser
        des projets solides, sécurisés et rentables.
      </p>

      <div class="hero-cta">
        <a href="#partenaires" class="btn-primary">Voir nos partenaires</a>
        <a href="contact.php" class="btn-secondary">Devenir partenaire</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="<?php echo BASE_URL; ?>/assets/img/investisseurs-afrique.jpg" alt="Partenaires IBIG">
      </div>
    </div>

  </div>
</section>


<!-- ===================== QUI PEUT COLLABORER ? ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Qui peut travailler avec IBIG IMMO TRUST ?</h2>
    <p class="section-intro">
      Nous sommes ouverts à plusieurs formes de collaborations selon les besoins et objectifs
      des partenaires, en Côte d’Ivoire comme à l’international.
    </p>

    <div class="cards-grid">

      <!-- INVESTISSEURS -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/investisseurs-afrique.jpg" class="card-img">
        <h3>Investisseurs privés & institutionnels</h3>
        <p>
          Financement de projets immobiliers, modèles partagés sur loyers, co-investissement,
          participation à des chantiers à forte rentabilité.
        </p>
      </article>

      <!-- ENTREPRISES BTP -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/construction-afrique.jpg" class="card-img">
        <h3>Entreprises & artisans BTP</h3>
        <p>
          Collaboration technique, sous-traitance, corps de métiers spécialisés,
          appels d’offres et projets de grande envergure.
        </p>
      </article>

      <!-- BANQUES & MICROFINANCES -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/banque-credit.jpg" class="card-img">
        <h3>Banques & microfinances</h3>
        <p>
          Financement immobilier, prêts construction, crédits hypothécaires,
          supervision IBIG pour sécuriser les décaissements.
        </p>
      </article>

      <!-- PROMOTEURS -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/projet-btp.jpg" class="card-img">
        <h3>Promoteurs immobiliers</h3>
        <p>
          Finishing, reprise de chantiers, valorisation, aménagements et gestion locative
          post-livraison.
        </p>
      </article>

      <!-- FOURNISSEURS -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/renovation-btp.jpg" class="card-img">
        <h3>Fournisseurs & distributeurs</h3>
        <p>
          Fourniture de matériaux, équipements, fenêtres, portes, peinture,
          carrelage, électricité, plomberie, climatisation.
        </p>
      </article>

      <!-- DIASPORA -->
      <article class="card">
        <img src="<?php echo BASE_URL; ?>/assets/img/financement-ibig.jpg" class="card-img">
        <h3>Communautés & diaspora</h3>
        <p>
          Programmes d’investissement sécurisés, suivi digital, projets groupés,
          cohabitations financières et solutions clé en main.
        </p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== AVANTAGES PARTENARIAT ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Pourquoi collaborer avec IBIG IMMO TRUST ?</h2>

    <div class="cards-grid">

      <article class="card">
        <h3>Fiabilité & transparence</h3>
        <p>
          Processus contractuel clair, reporting continu, documents sécurisés
          et suivi digital.
        </p>
      </article>

      <article class="card">
        <h3>Réseau technique & professionnel</h3>
        <p>
          Corps de métiers qualifiés, ingénieurs, artisans, experts fonciers
          et partenaires financiers.
        </p>
      </article>

      <article class="card">
        <h3>Gestion complète</h3>
        <p>
          De la faisabilité à la livraison, y compris la gestion locative
          et le recouvrement des loyers.
        </p>
      </article>

      <article class="card">
        <h3>Modèles de financement innovants</h3>
        <p>
          Direct IBIG, co-financement, investisseurs, financement bancaire,
          montages hybrides.
        </p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Vous souhaitez devenir partenaire ?</h2>
      <p>
        Contactez notre équipe pour discuter d’une collaboration stratégique,
        technique ou financière.
      </p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Devenir partenaire</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
