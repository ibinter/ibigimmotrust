<?php
declare(strict_types=1);
$pageTitle = "Nos services | IBIG IMMO TRUST";
$metaDescription = "Immobilier, BTP, gestion locative, financement et assistance foncière – IBIG IMMO TRUST";
$currentPage = "services";
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/header.php";
?>

<style>
/* ===== HERO SERVICES ===== */
.svc-hero {
  background: linear-gradient(135deg, #1340B0 0%, #1B4FD8 60%, #1E57E8 100%);
  padding: 80px 0 60px;
  position: relative;
  overflow: hidden;
}
.svc-hero::before {
  content: '';
  position: absolute;
  top: -100px; right: -100px;
  width: 450px; height: 450px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(232,40,42,0.07) 0%, transparent 70%);
}
.svc-hero .kicker {
  font-size: 11px; font-weight: 800; letter-spacing: 0.15em;
  text-transform: uppercase; color: #E8282A; margin-bottom: 16px;
}
.svc-hero h1 {
  font-size: clamp(26px, 3.5vw, 46px); font-weight: 900;
  color: #fff; margin: 0 0 16px; letter-spacing: -0.02em; line-height: 1.15;
}
.svc-hero h1 span { color: #E8282A; }
.svc-hero .desc {
  font-size: 16px; color: rgba(255,255,255,0.65);
  max-width: 620px; line-height: 1.75; margin: 0;
}

/* ===== GRILLE SERVICES ===== */
.svc-section { padding: 70px 0; background: #FAF7F0; }
.svc-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
  gap: 28px;
}
.svc-card {
  background: #fff;
  border-radius: 18px;
  padding: 34px 28px;
  box-shadow: 0 8px 32px rgba(27,79,216,0.08);
  border: 1px solid rgba(27,79,216,0.06);
  border-top: 3px solid #E8282A;
  display: flex; flex-direction: column; justify-content: space-between;
  transition: all 0.3s ease;
}
.svc-card:hover { transform: translateY(-5px); box-shadow: 0 20px 50px rgba(27,79,216,0.13); }
.svc-icon {
  width: 52px; height: 52px;
  background: linear-gradient(135deg, #E8282A, #C01A1C);
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  font-size: 20px; color: #fff;
  margin-bottom: 20px;
}
.svc-card h3 { font-size: 19px; font-weight: 800; color: #fff; margin-bottom: 10px; }
.svc-card p { font-size: 14px; color: #4B5563; line-height: 1.7; margin-bottom: 22px; flex: 1; }
.svc-actions { display: flex; gap: 10px; flex-wrap: wrap; }
.svc-btn-primary {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 10px 18px; border-radius: 999px;
  background: linear-gradient(135deg, #E8282A, #C01A1C);
  color: #fff; font-size: 13px; font-weight: 800;
  text-decoration: none; transition: all 0.3s ease;
}
.svc-btn-primary:hover { box-shadow: 0 6px 20px rgba(232,40,42,0.4); transform: translateY(-1px); }
.svc-btn-outline {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 10px 18px; border-radius: 999px;
  border: 1.5px solid #1B4FD8; color: #fff;
  font-size: 13px; font-weight: 800; text-decoration: none; transition: all 0.3s ease;
  background: transparent;
}
.svc-btn-outline:hover { background: #1B4FD8; color: #fff; }

/* ===== SECTION ENGAGEMENT ===== */
.svc-engagement {
  padding: 70px 0;
  background: linear-gradient(135deg, #1340B0, #1B4FD8);
  text-align: center;
}
.svc-engagement h2 { font-size: clamp(22px, 3vw, 34px); font-weight: 900; color: #fff; margin-bottom: 14px; }
.svc-engagement p { font-size: 16px; color: rgba(255,255,255,0.65); max-width: 700px; margin: 0 auto 36px; line-height: 1.75; }
.svc-cta-row { display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; }
.svc-cta-gold {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 14px 28px; border-radius: 999px;
  background: linear-gradient(135deg, #E8282A, #C01A1C);
  color: #fff; font-size: 14px; font-weight: 800; text-decoration: none;
  transition: all 0.3s ease;
}
.svc-cta-gold:hover { box-shadow: 0 8px 25px rgba(232,40,42,0.45); transform: translateY(-2px); }
.svc-cta-white {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 14px 28px; border-radius: 999px;
  border: 1.5px solid rgba(255,255,255,0.3); color: #fff;
  font-size: 14px; font-weight: 800; text-decoration: none; transition: all 0.3s ease;
}
.svc-cta-white:hover { background: rgba(255,255,255,0.1); border-color: #E8282A; color: #E8282A; }
</style>

<!-- HERO -->
<section class="svc-hero">
  <div class="container">
    <p class="kicker">IBIG IMMO TRUST &bull; NOS SERVICES</p>
    <h1>Une expertise complète pour <span>sécuriser et valoriser</span><br>votre patrimoine.</h1>
    <p class="desc">De la recherche du bien à la réalisation des travaux, en passant par le financement, la gestion locative et la sécurisation foncière.</p>
  </div>
</section>

<!-- GRILLE -->
<section class="svc-section">
  <div class="container">
    <div class="svc-grid">

      <div class="svc-card">
        <div>
          <div class="svc-icon"><i class="fa-solid fa-building"></i></div>
          <h3>Immobilier</h3>
          <p>Achat, vente, location et gestion de biens immobiliers. Nous accompagnons particuliers, entreprises et diaspora dans la recherche de solutions fiables et sécurisées.</p>
        </div>
        <div class="svc-actions">
          <a href="<?= BASE_URL ?>/tous_les_biens.php" class="svc-btn-primary"><i class="fa-solid fa-arrow-right"></i> Voir les biens</a>
          <a href="<?= BASE_URL ?>/immobilier.php" class="svc-btn-outline">En savoir plus</a>
        </div>
      </div>

      <div class="svc-card">
        <div>
          <div class="svc-icon"><i class="fa-solid fa-helmet-safety"></i></div>
          <h3>BTP & Chantiers</h3>
          <p>Construction de villas, rénovation, clôtures et suivi de chantier. Nos équipes assurent un contrôle rigoureux pour garantir qualité, délais et conformité.</p>
        </div>
        <div class="svc-actions">
          <a href="<?= BASE_URL ?>/btp.php" class="svc-btn-primary"><i class="fa-solid fa-arrow-right"></i> Services BTP</a>
          <a href="<?= BASE_URL ?>/formulaires.php" class="svc-btn-outline">Demander un devis</a>
        </div>
      </div>

      <div class="svc-card">
        <div>
          <div class="svc-icon"><i class="fa-solid fa-coins"></i></div>
          <h3>Financement & Investissement</h3>
          <p>Montage de dossiers de financement, solutions adaptées aux investisseurs et à la diaspora, en partenariat avec des institutions financières reconnues.</p>
        </div>
        <div class="svc-actions">
          <a href="<?= BASE_URL ?>/financement.php" class="svc-btn-primary"><i class="fa-solid fa-arrow-right"></i> Solutions</a>
          <a href="<?= BASE_URL ?>/rdv.php" class="svc-btn-outline">Prendre RDV</a>
        </div>
      </div>

      <div class="svc-card">
        <div>
          <div class="svc-icon"><i class="fa-solid fa-file-shield"></i></div>
          <h3>Assistance foncière</h3>
          <p>Sécurisation juridique des terrains : ACD, titres fonciers, vérification de documents et accompagnement administratif pour éviter tout risque de litige.</p>
        </div>
        <div class="svc-actions">
          <a href="<?= BASE_URL ?>/formulaires.php" class="svc-btn-primary"><i class="fa-solid fa-arrow-right"></i> Faire une demande</a>
          <a href="<?= BASE_URL ?>/contact.php" class="svc-btn-outline">Nous contacter</a>
        </div>
      </div>

      <div class="svc-card">
        <div>
          <div class="svc-icon"><i class="fa-solid fa-key"></i></div>
          <h3>Gestion locative</h3>
          <p>Loyer garanti, sélection rigoureuse des locataires, états des lieux, maintenance et reporting mensuel pour optimiser la rentabilité de votre bien.</p>
        </div>
        <div class="svc-actions">
          <a href="<?= BASE_URL ?>/immobilier.php" class="svc-btn-primary"><i class="fa-solid fa-arrow-right"></i> En savoir plus</a>
          <a href="<?= BASE_URL ?>/rdv.php" class="svc-btn-outline">Prendre RDV</a>
        </div>
      </div>

      <div class="svc-card">
        <div>
          <div class="svc-icon"><i class="fa-solid fa-globe-africa"></i></div>
          <h3>Espace Diaspora</h3>
          <p>Investissez depuis l'étranger en toute sécurité : coordination terrain, photos, vidéos, rapports réguliers et suivi complet de vos projets à distance.</p>
        </div>
        <div class="svc-actions">
          <a href="<?= BASE_URL ?>/diaspora.php" class="svc-btn-primary"><i class="fa-solid fa-arrow-right"></i> Espace Diaspora</a>
          <a href="<?= BASE_URL ?>/contact.php" class="svc-btn-outline">Nous écrire</a>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ENGAGEMENT CTA -->
<section class="svc-engagement">
  <div class="container">
    <h2>Pourquoi choisir IBIG IMMO TRUST&nbsp;?</h2>
    <p>Rigueur, transparence et professionnalisme sont au cœur de notre engagement. Chaque projet est traité avec sérieux pour protéger et valoriser durablement votre patrimoine immobilier.</p>
    <div class="svc-cta-row">
      <a href="<?= BASE_URL ?>/tous_les_biens.php" class="svc-cta-gold"><i class="fa-solid fa-building"></i> Voir nos biens</a>
      <a href="<?= BASE_URL ?>/rdv.php" class="svc-cta-white"><i class="fa-regular fa-calendar-check"></i> Prendre rendez-vous</a>
    </div>
  </div>
</section>

<?php require_once __DIR__ . "/includes/footer.php"; ?>