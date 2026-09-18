<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'services';
$pageTitle   = "Loyer garanti – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Gestion locative premium • Loyer garanti</p>

      <h1 class="hero-title">
        Le loyer garanti IBIG IMMO TRUST : sécurité, ponctualité & tranquillité.
      </h1>

      <p class="hero-subtitle">
        Recevez votre loyer chaque mois, au plus tard le <strong>10 du mois</strong>,
        grâce à notre modèle de gestion locative professionnelle adapté aux propriétaires
        en Côte d’Ivoire et à la diaspora.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary">Parler à un conseiller</a>
        <a href="rdv.php" class="btn-secondary">Prendre RDV</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/gestion-locative2.jpg" alt="Loyer garanti IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>


<!-- ===================== SECTION 1 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Qu’est-ce que le loyer garanti IBIG IMMO TRUST ?</h2>
    <p class="section-intro">
      Un modèle de gestion locative moderne dans lequel IBIG IMMO TRUST s’engage à vous verser
      votre loyer chaque mois, indépendamment des retards ou impayés du locataire.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3>ð Loyer payé au plus tard le 10 du mois</h3>
        <p>
          Un engagement contractuel : votre loyer vous est versé à date fixe,
          même si le locataire n’a pas encore payé.
        </p>
      </article>

      <article class="card">
        <h3>ð Contrat professionnel & clair</h3>
        <p>
          Tous les engagements (conditions, durée, montant, responsabilités)
          sont formalisés dans un contrat sécurisé.
        </p>
      </article>

      <article class="card">
        <h3>ð± Suivi digital & transparence</h3>
        <p>
          Vous recevez un reporting régulier : paiements, maintenance, statut du locataire,
          dépenses, photos & vidéos lors des visites.
        </p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 2 ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Ce que nous gérons à votre place</h2>
    <p class="section-intro">
      Vous n’avez plus à gérer les locataires, les visites ou les travaux.
      IBIG IMMO TRUST s’occupe de tout.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3>ð Recherche & sélection des locataires</h3>
        <p>Vérification de solvabilité, dossiers analysés, visites organisées.</p>
      </article>

      <article class="card">
        <h3>ðï¸ Signature du bail</h3>
        <p>Contrat conforme, dépôt de garantie, état des lieux professionnel.</p>
      </article>

      <article class="card">
        <h3>ð° Perception & sécurisation des loyers</h3>
        <p>Nous assurons le recouvrement, même en cas de retard.</p>
      </article>

      <article class="card">
        <h3>ð ï¸ Maintenance & petites réparations</h3>
        <p>Interventions rapides pour préserver la qualité du bien.</p>
      </article>

      <article class="card">
        <h3>ð¸ Reporting digital</h3>
        <p>Photos, vidéos, rapports périodiques – idéal pour la diaspora.</p>
      </article>

      <article class="card">
        <h3>ð Dossier complet du locataire</h3>
        <p>Archivage et gestion administrative assurés.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION 3 ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Qui peut bénéficier du loyer garanti ?</h2>

    <div class="cards-grid">
      <article class="card">
        <h3>ð¡ Propriétaires en Côte d’Ivoire</h3>
        <p>Pour sécuriser les loyers et éviter les soucis de gestion quotidienne.</p>
      </article>

      <article class="card">
        <h3>ð Diaspora</h3>
        <p>Votre bien est géré entièrement depuis la Côte d’Ivoire,
           avec un suivi digital complet.</p>
      </article>

      <article class="card">
        <h3>ð¼ Investisseurs</h3>
        <p>Idéal pour ceux qui souhaitent un revenu locatif stable et prévisible.</p>
      </article>
    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Vous souhaitez bénéficier du loyer garanti ?</h2>
      <p>Un conseiller peut vous accompagner dès maintenant.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Demander un audit de mon bien</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
