<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'financement';
$pageTitle   = "Financement immobilier – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Financement • Assistance foncière • Investisseurs • Modèle Direct IBIG</p>

      <h1 class="hero-title">
        Financement immobilier : plusieurs solutions adaptées à votre profil.
      </h1>

      <p class="hero-subtitle">
        Que vous ayez ou non une avance de fonds, IBIG IMMO TRUST propose des
        solutions innovantes pour financer vos travaux, finitions, rénovations
        ou constructions en Côte d’Ivoire.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary">Décrire mon projet</a>
        <a href="rdv.php" class="btn-secondary">Prendre rendez-vous</a>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/financement-ibig.jpg" alt="Financement IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>


<!-- ===================== INTRO ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Nos modèles de financement</h2>
    <p class="section-intro">
      Nous proposons trois solutions flexibles permettant de financer facilement vos travaux,
      chantiers inachevés, constructions ou rénovations, selon votre profil et vos capacités.
    </p>

    <div class="cards-grid">

      <!-- DIRECT IBIG -->
      <article class="card">
        <h3>Modèle Direct IBIG (Sans avance)</h3>
        <p>
          IBIG préfinance les travaux (finition, rénovation ou construction) et se rembourse
          via les loyers perçus après la mise en location du bien.
        </p>
        <a href="direct-ibig.php" class="btn-secondary" style="margin-top:8px;">Détails du modèle</a>
      </article>

      <!-- INVESTISSEURS AGREE -->
      <article class="card">
        <h3>Investisseurs agréés (10 à 20 % d’avance)</h3>
        <p>
          Des investisseurs privés ou institutionnels cofinancent le projet.
          Les loyers remboursent l’investissement sur 3 à 5 ans.
        </p>
        <a href="investisseurs-agrees.php" class="btn-secondary" style="margin-top:8px;">Voir le modèle</a>
      </article>

      <!-- BANQUES & MICROFINANCE -->
      <article class="card">
        <h3>Banques & Microfinances (0 à 30 % d’avance)</h3>
        <p>
          Montage complet de votre dossier bancaire, supervision des travaux
          et garantie de bonne exécution.
        </p>
        <a href="banques-microfinance.php" class="btn-secondary" style="margin-top:8px;">En savoir plus</a>
      </article>

    </div>

  </div>
</section>


<!-- ===================== AVANTAGES ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Pourquoi choisir IBIG pour financer votre projet ?</h2>

    <div class="cards-grid">

      <article class="card">
        <h3>ð Étude de faisabilité complète</h3>
        <p>Analyse du terrain, du budget, des travaux et du potentiel locatif.</p>
      </article>

      <article class="card">
        <h3>ð¼ Solutions adaptées à tous les profils</h3>
        <p>Propriétaires, investisseurs, diaspora, primo-acquéreurs.</p>
      </article>

      <article class="card">
        <h3>ð Remboursements flexibles</h3>
        <p>
          Remboursement direct via les loyers (Direct IBIG & Investisseurs) ou via la banque.
        </p>
      </article>

      <article class="card">
        <h3>ð Suivi digital pour la diaspora</h3>
        <p>Reporting, vidéos, photos, états de travaux en temps réel.</p>
      </article>

      <article class="card">
        <h3>ð Contrats sécurisés</h3>
        <p>Contrats clairs et encadrés : Propriétaire – IBIG – Investisseur/Banque.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== PROCESSUS ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Comment fonctionne le financement IBIG ?</h2>

    <div class="steps-grid">

      <article class="step">
        <div class="step-number">1</div>
        <h3>Analyse & diagnostic</h3>
        <p>Étude du bien, du projet et du potentiel locatif ou commercial.</p>
      </article>

      <article class="step">
        <div class="step-number">2</div>
        <h3>Choix du modèle de financement</h3>
        <p>Direct IBIG, Investisseurs agréés ou Banque/Microfinance.</p>
      </article>

      <article class="step">
        <div class="step-number">3</div>
        <h3>Contrat & mise en place</h3>
        <p>Contrat tripartite sécurisé & étapes de financement.</p>
      </article>

      <article class="step">
        <div class="step-number">4</div>
        <h3>Exécution des travaux</h3>
        <p>Construction, finition, rénovation, reporting continu.</p>
      </article>

      <article class="step">
        <div class="step-number">5</div>
        <h3>Mise en location</h3>
        <p>Préparation du bien, sélection des locataires, mise en gestion IBIG.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Votre projet a besoin d’un financement ?</h2>
      <p>Nos équipes vous proposent la meilleure solution selon votre profil.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Soumettre mon projet</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
