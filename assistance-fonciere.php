<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'services';
$pageTitle   = "Assistance foncière – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Foncier • Sécurisation • Analyse de documents</p>

      <h1 class="hero-title">
        Assistance foncière : sécurisez votre achat, vos titres et vos documents fonciers.
      </h1>

      <p class="hero-subtitle">
        IBIG IMMO TRUST vous accompagne dans la vérification complète de vos documents fonciers,
        l’analyse des risques, la validation auprès des autorités, et la sécurisation de votre
        transaction en Côte d’Ivoire.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary">Vérifier mes documents</a>
        <a href="rdv.php" class="btn-secondary">Prendre rendez-vous</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/financement-ibig.jpg" alt="Assistance foncière IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>


<!-- ===================== SECTION 1 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Pourquoi une assistance foncière est indispensable ?</h2>
    <p class="section-intro">
      Le domaine foncier en Côte d’Ivoire est complexe : faux documents, litiges, doublons,
      ventes multiples, terrains non immatriculés…  
      Une vérification professionnelle est indispensable pour éviter les arnaques et sécuriser
      votre investissement.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3> Vérification des titres fonciers</h3>
        <p>
          Certificat foncier, ACD, titre foncier, attestation villageoise :
          IBIG vérifie l’authenticité et la conformité.
        </p>
      </article>

      <article class="card">
        <h3> Analyse juridique</h3>
        <p>
          Contrats, actes notariés, historiques du terrain,
          risques de litiges ou de double vente.
        </p>
      </article>

      <article class="card">
        <h3> Validation auprès des autorités</h3>
        <p>
          Sondage administratif, vérification au cadastre, conservation foncière,
          services techniques.
        </p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 2 ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Nos services fonciers</h2>

    <div class="cards-grid">

      <article class="card">
        <h3> Vérification complète des documents</h3>
        <p>
          IBIG analyse tous vos documents fonciers et délivre un avis professionnel détaillé.
        </p>
      </article>

      <article class="card">
        <h3> Historique du terrain</h3>
        <p>
          Recherche d’antécédents : anciens propriétaires, droits coutumiers, limites, litiges.
        </p>
      </article>

      <article class="card">
        <h3> Visite & géolocalisation du terrain</h3>
        <p>
          Vérification sur site : bornes, limites, occupation, accès, conformité avec les plans.
        </p>
      </article>

      <article class="card">
        <h3> Assistance notariale</h3>
        <p>
          Préparation, signature et authentification des actes chez un notaire agréé.
        </p>
      </article>

      <article class="card">
        <h3> Accompagnement à l’achat</h3>
        <p>
          IBIG vous accompagne pour sécuriser la transaction et éviter les pièges courants.
        </p>
      </article>

      <article class="card">
        <h3> Assistance diaspora</h3>
        <p>
          Vérification et validation à distance, avec reporting digital complet.
        </p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 3 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Documents que nous vérifions</h2>

    <div class="cards-grid">

      <article class="card">
        <h3> Certificat foncier</h3>
        <p>Authenticité, limites, historique et conformité.</p>
      </article>

      <article class="card">
        <h3> ACD (Arrêté de Concession Définitive)</h3>
        <p>Analyse juridique et confirmation auprès des services techniques.</p>
      </article>

      <article class="card">
        <h3> Titre foncier</h3>
        <p>Vérification auprès de la conservation foncière.</p>
      </article>

      <article class="card">
        <h3> Attestation villageoise</h3>
        <p>Confirmation du chef, cohérence des limites, absence de litige coutumier.</p>
      </article>

      <article class="card">
        <h3> Plans topographiques</h3>
        <p>Bornage, géolocalisation, conformité et limites.</p>
      </article>

      <article class="card">
        <h3> Actes notariés</h3>
        <p>Contrats, procurations, mandats, certificats d’authenticité.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Vous avez un projet foncier ?</h2>
      <p>Faites vérifier vos documents avant de payer une avance.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Faire vérifier mes documents</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
