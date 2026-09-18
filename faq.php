<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'faq';
$pageTitle   = "FAQ – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">FAQ • Questions fréquentes</p>

      <h1 class="hero-title">
        Vos questions les plus fréquentes, nos réponses claires.
      </h1>

      <p class="hero-subtitle">
        Retrouvez ici toutes les réponses concernant nos services immobiliers, BTP,
        financement, gestion locative et accompagnement diaspora.
      </p>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="<?php echo BASE_URL; ?>/assets/img/hero-immo-afrique.jpg" alt="FAQ IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>

<!-- ===================== FAQ ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Questions générales</h2>

    <div class="cards-grid">

      <article class="card">
        <h3>ð Qui est IBIG IMMO TRUST ?</h3>
        <p>
          IBIG IMMO TRUST est la branche Immobilier & BTP de INTERMARK BUSINESS INTERNATIONAL GROUP SARL,
          spécialisée dans la construction, la rénovation, les chantiers inachevés, la gestion locative
          et le financement immobilier.
        </p>
      </article>

      <article class="card">
        <h3>ð Dans quelles zones intervenez-vous ?</h3>
        <p>
          Nous intervenons à Abidjan et dans plusieurs villes de Côte d’Ivoire.
          Nous accompagnons également les projets initiés par la diaspora (Europe, USA, Canada…).
        </p>
      </article>

      <article class="card">
        <h3>ð Quels types de biens prenez-vous en charge ?</h3>
        <p>
          Terrains, maisons, appartements, immeubles, locaux professionnels, bureaux,
          chantiers inachevés et projets de construction.
        </p>
      </article>

    </div>


    <!-- BLOC 2 -->
    <h2 class="section-title" style="margin-top:40px;">Financement & modèles économiques</h2>

    <div class="cards-grid">

      <article class="card">
        <h3>ð° Quel est le modèle Direct IBIG ?</h3>
        <p>
          C’est un modèle sans avance où IBIG préfinance la finition ou la construction.
          Le remboursement se fait via les loyers sur une durée de 3 à 7 ans.
        </p>
      </article>

      <article class="card">
        <h3>ð° Comment fonctionne le financement par investisseurs agréés ?</h3>
        <p>
          Le propriétaire apporte 10 à 20% du budget.  
          Des investisseurs privés complètent et se remboursent sur les loyers.
        </p>
      </article>

      <article class="card">
        <h3>ð° Puis-je financer mon projet avec une banque ?</h3>
        <p>
          Oui. Nous collaborons avec des banques et microfinances, montons le dossier
          et supervisons le chantier pour sécuriser les décaissements.
        </p>
      </article>

    </div>


    <!-- BLOC 3 -->
    <h2 class="section-title" style="margin-top:40px;">Gestion locative & propriétaires</h2>

    <div class="cards-grid">

      <article class="card">
        <h3>ð  Offrez-vous le loyer garanti ?</h3>
        <p>
          Oui, selon le contrat. Le propriétaire reçoit son loyer au plus tard le 10 du mois,
          même en cas de retard du locataire (conditions définies dans le mandat).
        </p>
      </article>

      <article class="card">
        <h3>ð  Comment se passe la mise en location ?</h3>
        <p>
          Photos professionnelles, diffusion, visites, contrôle de solvabilité,
          signature du bail, gestion quotidienne et maintenance.
        </p>
      </article>

      <article class="card">
        <h3>ð  Puis-je confier un bien à distance ?</h3>
        <p>
          Oui, grâce à notre système de suivi digital (photos, vidéos, visites virtuelles,
          rapports réguliers). Idéal pour la diaspora.
        </p>
      </article>

    </div>


    <!-- BLOC 4 -->
    <h2 class="section-title" style="margin-top:40px;">Chantiers & Construction</h2>

    <div class="cards-grid">

      <article class="card">
        <h3>ðï¸ Pouvez-vous terminer un chantier abandonné ?</h3>
        <p>
          Oui. Nous analysons l’état du chantier, corrigeons les malfaçons
          et terminons les travaux avec un suivi technique strict.
        </p>
      </article>

      <article class="card">
        <h3>ðï¸ Proposez-vous la construction clé en main ?</h3>
        <p>
          Oui. De la fondation à la finition : planification, construction,
          supervision et livraison prête à habiter.
        </p>
      </article>

      <article class="card">
        <h3>ðï¸ Est-ce possible d’avoir un devis avant d’acheter un terrain ?</h3>
        <p>
          Oui, nous proposons une étude de faisabilité afin d’aider le client
          à prendre une décision éclairée.
        </p>
      </article>

    </div>


    <!-- BLOC 5 -->
    <h2 class="section-title" style="margin-top:40px;">Assistance foncière</h2>

    <div class="cards-grid">

      <article class="card">
        <h3>ð Pouvez-vous vérifier un titre foncier ?</h3>
        <p>
          Oui, nous faisons les vérifications auprès des services compétents
          (documents, morcellement, duplicatas, cadastre…).
        </p>
      </article>

      <article class="card">
        <h3>ð Aidez-vous à sécuriser un achat de terrain ?</h3>
        <p>
          Oui : recherche d’historique, validation des documents, accompagnement notarial,
          et conseil complet avant signature.
        </p>
      </article>

      <article class="card">
        <h3>ð Proposez-vous un service pour la diaspora ?</h3>
        <p>
          Oui. Nous gérons toutes les étapes à distance avec transparence totale :
          documents, négociation, vidéos, rapports.
        </p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Vous n’avez pas trouvé votre réponse ?</h2>
      <p>Notre équipe est disponible pour vous accompagner.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Nous contacter</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
