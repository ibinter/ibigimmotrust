<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'immobilier';
$pageTitle   = "Immobilier – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Immobilier • Gestion locative • Assistance foncière</p>

      <h1 class="hero-title">
        Immobilier IBIG IMMO TRUST : gestion, sécurité et rentabilité pour votre patrimoine.
      </h1>

      <p class="hero-subtitle">
        Découvrez nos services immobiliers dédiés aux propriétaires, investisseurs
        et à la diaspora : loyer garanti, gestion locative, assistance foncière,
        mise en location et financement immobilier.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary">Parler à un conseiller</a>
        <a href="rdv.php" class="btn-secondary">Prendre RDV</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/gestion-locative.jpg" alt="Services immobiliers IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>


<!-- ===================== BLOCS SERVICES ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Nos services immobiliers</h2>
    <p class="section-intro">
      IBIG IMMO TRUST vous accompagne dans la gestion complète, la sécurisation
      et la valorisation de vos biens immobiliers en Côte d’Ivoire.
    </p>

    <div class="cards-grid">

      <article class="card">
        <img src="assets/img/gestion-locative.jpg" class="card-img">
        <h3>Gestion locative professionnelle</h3>
        <p>
          Mise en location, perception des loyers, gestion des locataires,
          maintenance et reporting digital.
        </p>
        <a href="loyer-garanti.php" class="btn-secondary" style="margin-top:8px;">En savoir plus</a>
      </article>

      <article class="card">
        <img src="assets/img/gestion-locative3.jpg" class="card-img">
        <h3>Loyer garanti (option)</h3>
        <p>
          Recevez votre loyer chaque mois, au plus tard le 10, même en cas
          de retard du locataire (selon contrat).
        </p>
        <a href="loyer-garanti.php" class="btn-secondary" style="margin-top:8px;">Découvrir</a>
      </article>

      <article class="card">
        <img src="assets/img/financement-ibig.jpg" class="card-img">
        <h3>Assistance foncière</h3>
        <p>
          Vérification des documents fonciers, analyse juridique et technique,
          validation auprès des autorités.
        </p>
        <a href="assistance-fonciere.php" class="btn-secondary" style="margin-top:8px;">Vérifier mes documents</a>
      </article>

      <article class="card">
        <img src="assets/img/investisseurs-afrique.jpg" class="card-img">
        <h3>Financement immobilier</h3>
        <p>
          Modèle Direct IBIG, investisseurs agréés, banques & microfinances :
          plusieurs solutions selon votre profil.
        </p>
        <a href="financement.php" class="btn-secondary" style="margin-top:8px;">Voir les options</a>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION LOYER + GESTION ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Gestion locative & loyer garanti</h2>
    <p class="section-intro">
      Une solution moderne et sécurisée pour garantir vos revenus locatifs,
      même à distance pour la diaspora.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3>ð Sélection des locataires</h3>
        <p>Visites, dossiers, solvabilité et signature du bail par IBIG.</p>
      </article>

      <article class="card">
        <h3>ð° Perception & sécurisation</h3>
        <p>Nous collectons et sécurisons vos loyers chaque mois.</p>
      </article>

      <article class="card">
        <h3>ð ï¸ Maintenance</h3>
        <p>Interventions, réparations, suivi technique et gestion des incidents.</p>
      </article>

      <article class="card">
        <h3>ð Reporting digital</h3>
        <p>Photos, vidéos, état des lieux et reporting mensuel.</p>
      </article>

    </div>

    <div style="margin-top:25px;">
      <a href="loyer-garanti.php" class="btn-primary btn-large">Découvrir le loyer garanti</a>
    </div>

  </div>
</section>


<!-- ===================== SECTION FONCIER ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Sécurisation foncière</h2>
    <p class="section-intro">
      Nos experts fonciers vérifient vos documents avant tout paiement ou achat.
      Un service essentiel pour éviter les litiges et arnaques foncières.
    </p>

    <div class="cards-grid">
      <article class="card"><h3>ð Vérification des titres</h3><p>ACD, TF, CF, dossiers villageois.</p></article>
      <article class="card"><h3>ð Visite & bornage</h3><p>Contrôle physique du terrain.</p></article>
      <article class="card"><h3>âï¸ Analyse juridique</h3><p>Validité, risque, litige, historique.</p></article>
      <article class="card"><h3>ðï¸ Assistance notariale</h3><p>Contrats et authentification.</p></article>
    </div>

    <div style="margin-top:25px;">
      <a href="assistance-fonciere.php" class="btn-primary btn-large">Assistance foncière complète</a>
    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Vous avez un projet immobilier ?</h2>
      <p>Nos experts vous accompagnent dans la gestion, la sécurité et le financement.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Décrire mon projet</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>