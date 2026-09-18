<?php
require_once __DIR__ . '/includes/config.php'; 

include __DIR__ . '/includes/tracker.php';

$currentPage = 'home';
$pageTitle   = "IBIG IMMO TRUST – Immobilier & BTP en Côte d’Ivoire";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Immobilier • BTP • Financement • Gestion locative</p>

      <h1 class="hero-title">
        Transformez vos terrains, chantiers inachevés et biens immobiliers en actifs rentables.
      </h1>

      <p class="hero-subtitle">
        IBIG IMMO TRUST vous accompagne dans la gestion locative professionnelle, la finition de chantiers,
        la rénovation immobilière, la construction neuve et le financement adapté à votre profil,
        y compris pour la diaspora.
      </p>

      <div class="hero-cta">
        <a href="contact.php" class="btn-primary"
           onclick="trackEvent('click_contact_home');">
           Décrire mon projet
        </a>
        <a href="rdv.php" class="btn-secondary"
           onclick="trackEvent('click_rdv_home');">
           Prendre RDV
        </a>

      </div>

      <div class="hero-badges">
        <span> Loyer garanti (conditions)</span>
        <span> Suivi digital diaspora</span>
        <span> Financement Direct IBIG</span>
      </div>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="assets/img/hero-immo-afrique.jpg" alt="IBIG IMMO TRUST – Immobilier en Afrique">
      </div>
    </div>

  </div>
</section>

<?php
$homeBiens = $pdo->query("
    SELECT * FROM immo_biens 
    WHERE visible = 1 
    ORDER BY created_at DESC
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);
?>

<section style="padding:40px 0;background:#fff5f0;">
  <div class="container" style="max-width:1150px;">

    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:18px;">
      <h2 style="font-size:22px;font-weight:800;color:#e30613;">
         Nos biens disponibles
      </h2>

      <a href="catalogue.php" style="font-size:14px;color:#e30613;font-weight:600;text-decoration:none;">
        Voir tout le catalogue →
      </a>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;">

      <?php foreach($homeBiens as $b):
        $url = 'bien.php';
        $url .= (!empty($b['slug'])) ? '?slug='.urlencode($b['slug']) : '?id='.$b['id'];
      ?>
        <a href="<?= $url ?>" style="text-decoration:none;color:#111827;">
          <div style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(0,0,0,0.08);border-left:5px solid #ff9f1c;">

            <img src="<?= htmlspecialchars($b['image_principale']) ?>" 
                 style="width:100%;height:160px;object-fit:cover;">

            <div style="padding:12px;">
              <p style="font-size:12px;color:#6b7280;margin:0 0 4px;">
                <?= htmlspecialchars($b['type']) ?> · <?= htmlspecialchars($b['transaction']) ?>
              </p>

              <h3 style="font-size:16px;font-weight:700;margin:0 0 6px;">
                <?= htmlspecialchars($b['titre']) ?>
              </h3>

              <p style="color:#e30613;font-weight:700;margin:0 0 4px;">
                <?= number_format($b['prix'],0,',',' ') ?> FCFA
              </p>

              <p style="font-size:13px;color:#6b7280;margin:0;">
                <?= htmlspecialchars(trim(($b['ville'] ?? '').' '.($b['quartier'] ?? ''))) ?>
              </p>
            </div>

          </div>
        </a>
      <?php endforeach; ?>

      <?php if(count($homeBiens) === 0): ?>
        <p>Aucun bien publié pour le moment.</p>
      <?php endif; ?>

    </div>
  </div>
</section>


<!-- ===================== SECTION : NOS DOMAINES ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Nos domaines d’intervention</h2>
    <p class="section-intro">
      Nous couvrons toute la chaîne de valeur : assistance foncière, construction,
      reprise de chantiers inachevés, rénovation, gestion locative et modèles de financement.
    </p>

    <div class="cards-grid">

      <article class="card">
        <img src="assets/img/gestion-locative.jpg" class="card-img">
        <h3>Immobilier</h3>
        <p>Gestion locative, mise en location professionnelle, assistance foncière, loyer garanti.</p>
        <a href="immobilier.php" class="btn-secondary" style="margin-top:8px;">Découvrir</a>
      </article>

      <article class="card">
        <img src="assets/img/construction-afrique.jpg" class="card-img">
        <h3>BTP – Construction & Finition</h3>
        <p>Chantiers inachevés, construction neuve, rénovation, supervision et contrôle qualité.</p>
        <a href="btp.php" class="btn-secondary" style="margin-top:8px;">En savoir plus</a>
      </article>

      <article class="card">
        <img src="assets/img/financement-ibig.jpg" class="card-img">
        <h3>Financement immobilier</h3>
        <p>Modèle Direct IBIG, Investisseurs agréés, Banques et microfinances.</p>
        <a href="financement.php" class="btn-secondary" style="margin-top:8px;">Voir les options</a>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION BTP ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Finition de chantiers & construction</h2>
    <p class="section-intro">
      Vous avez un chantier inachevé ou mal réalisé ?  
      IBIG IMMO TRUST reprend, optimise et supervise votre projet jusqu’à la livraison.
    </p>

    <div class="cards-grid">

      <article class="card">
        <img src="assets/img/chantier-inacheve.jpg" class="card-img">
        <h3>Chantiers inachevés</h3>
        <p>Reprise complète, diagnostic technique, planification et livraison clé en main.</p>
        <a href="chantier-inacheve.php" class="btn-secondary">Voir le service</a>
      </article>

      <article class="card">
        <img src="assets/img/renovation-afrique.jpg" class="card-img">
        <h3>Rénovation immobilière</h3>
        <p>Modernisation, esthétique, valorisation et optimisation du bien.</p>
        <a href="renovation.php" class="btn-secondary">Voir la rénovation</a>
      </article>

      <article class="card">
        <img src="assets/img/construction-afrique.jpg" class="card-img">
        <h3>Construction clé en main</h3>
        <p>Maisons, immeubles, locaux, bureaux, résidences meublées.</p>
        <a href="construction.php" class="btn-secondary">Découvrir</a>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION FINANCEMENT ===================== -->
<section class="section">
  <div class="container">

    <h2 class="section-title">Financement : plusieurs modèles adaptés à votre profil</h2>
    <p class="section-intro">
      Que vous disposiez ou non d’une avance, IBIG IMMO TRUST vous accompagne
      avec trois solutions flexibles.
    </p>

    <div class="cards-grid">

      <article class="card">
        <h3>Modèle Direct IBIG (sans avance)</h3>
        <p>IBIG finance vos travaux et se rembourse via les loyers après mise en location.</p>
        <a href="direct-ibig.php" class="btn-secondary">En savoir plus</a>
      </article>

      <article class="card">
        <h3>Investisseurs agréés</h3>
        <p>Des investisseurs financent avec vous. Remboursement sur 3 à 5 ans.</p>
        <a href="investisseurs-agrees.php" class="btn-secondary">Voir le modèle</a>
      </article>

      <article class="card">
        <h3>Banques & Microfinances</h3>
        <p>Montage, accompagnement, supervision et garantie de bonne exécution.</p>
        <a href="banques-microfinance.php" class="btn-secondary">Découvrir</a>
      </article>

    </div>

  </div>
</section>


<!-- ===================== SECTION PROCESS ===================== -->
<section class="section section-alt">
  <div class="container">

    <h2 class="section-title">Un processus clair, sécurisé & transparent</h2>

    <div class="steps-grid">

      <article class="step">
        <div class="step-number">1</div>
        <h3>Diagnostic & étude</h3>
        <p>Analyse technique et financière du projet.</p>
      </article>

      <article class="step">
        <div class="step-number">2</div>
        <h3>Proposition & contrat</h3>
        <p>Offre claire, calendrier, budget, mode de financement.</p>
      </article>

      <article class="step">
        <div class="step-number">3</div>
        <h3>Travaux & supervision</h3>
        <p>Contrôle qualité, reporting digital, visites.</p>
      </article>

      <article class="step">
        <div class="step-number">4</div>
        <h3>Mise en location</h3>
        <p>Recherche de locataires, gestion locative, loyer garanti.</p>
      </article>

    </div>

  </div>
</section>


<!-- ===================== CTA ===================== -->
<section class="section-cta">
  <div class="container cta-inner">

    <div>
      <h2>Un projet immobilier, BTP ou financement ?</h2>
      <p>Nos équipes sont prêtes à vous accompagner dès aujourd’hui.</p>
    </div>

    <a href="contact.php" class="btn-primary btn-large">Décrire mon projet</a>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
