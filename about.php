<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'about';
$pageTitle   = "À propos – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">À propos • Notre histoire • Notre mission</p>

      <h1 class="hero-title">
        IBIG IMMO TRUST : Votre partenaire de confiance en Immobilier & BTP.
      </h1>

      <p class="hero-subtitle">
        Branche Immobilier & BTP de <strong>INTERMARK BUSINESS INTERNATIONAL GROUP SARL</strong>,
        nous accompagnons propriétaires, investisseurs et diaspora dans la construction,
        la rénovation, la gestion locative et le financement de projets immobiliers en Côte d’Ivoire.
      </p>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="<?php echo BASE_URL; ?>/assets/img/hero-immo-afrique.jpg" alt="À propos IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>

<!-- ===================== PRESENTATION ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Qui sommes-nous ?</h2>
    <p class="section-intro">
      IBIG IMMO TRUST est une entité spécialisée dans la gestion des projets immobiliers
      et BTP en Côte d’Ivoire, offrant des solutions modernes, sécurisées et adaptées
      au marché local et à la diaspora.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3>Un écosystème structuré</h3>
        <p>
          IBIG IMMO TRUST fait partie du groupe <strong>INTERMARK BUSINESS INTERNATIONAL GROUP SARL</strong>,
          un groupe pluridisciplinaire intervenant dans la formation, le digital, les services
          et l’immobilier. Cette synergie nous permet de proposer un accompagnement solide et intégré.
        </p>
      </article>

      <article class="card">
        <h3>Un partenaire pour la diaspora</h3>
        <p>
          Suivi digital, transparence totale, reporting vidéo & photo, contrats sécurisés,
          assistance foncière : nous accompagnons les Ivoiriens de l’étranger dans leurs
          investissements immobiliers.
        </p>
      </article>

      <article class="card">
        <h3>Une expertise complète</h3>
        <p>
          De la reprise de chantier à la construction, de la rénovation à la gestion locative,
          nous assurons un cycle complet avec un haut niveau de professionnalisme.
        </p>
      </article>

      <article class="card">
        <h3>Des solutions de financement innovantes</h3>
        <p>
          Modèle Direct IBIG, investisseurs agréés, financement bancaire, montages hybrides :
          chaque profil a une solution adaptée.
        </p>
      </article>

    </div>

  </div>
</section>

<!-- ===================== MISSION ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Notre mission</h2>
    <p class="section-intro">
      Offrir aux propriétaires, investisseurs et familles un accompagnement fiable,
      moderne et transparent pour leurs projets immobiliers et BTP.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3>ð¡ï¸ Sécuriser vos investissements</h3>
        <p>
          Vérification foncière, contrats solides, supervision continue et reporting.
        </p>
      </article>

      <article class="card">
        <h3>ðï¸ Réaliser des projets durables</h3>
        <p>
          Construction, finition, rénovation : nous garantissons la qualité et le respect des normes.
        </p>
      </article>

      <article class="card">
        <h3>ð Valoriser votre patrimoine</h3>
        <p>
          Mise en location optimisée, gestion locative premium, option loyer garanti.
        </p>
      </article>

      <article class="card">
        <h3>ð Accompagner la diaspora</h3>
        <p>
          Transparence à distance, vidéos, photos, suivi digital, documents légaux sécurisés.
        </p>
      </article>

    </div>

  </div>
</section>

<!-- ===================== VALEURS ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Nos valeurs</h2>
    <p class="section-intro">Elles guident toutes nos interventions.</p>

    <div class="cards-grid">

      <article class="card">
        <h3>ðµ Transparence</h3>
        <p>Communication claire & reporting continu.</p>
      </article>

      <article class="card">
        <h3>ð§ Responsabilité</h3>
        <p>Nous engageons notre réputation sur chaque projet.</p>
      </article>

      <article class="card">
        <h3>ð´ Intégrité</h3>
        <p>Décisions guidées par l’éthique et l’intérêt du client.</p>
      </article>

      <article class="card">
        <h3>ð¦ Excellence</h3>
        <p>Travail soigné, matériaux de qualité, standards élevés.</p>
      </article>

    </div>

  </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Vous souhaitez discuter de votre projet ?</h2>
      <p>
        Un conseiller IBIG IMMO TRUST peut vous accompagner dès aujourd’hui.
      </p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Nous contacter</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
