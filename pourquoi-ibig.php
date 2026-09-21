<?php
declare(strict_types=1);
$pageTitle = "Pourquoi choisir IBIG IMMO TRUST | Immobilier, BTP & Financement en Côte d'Ivoire";
$metaDescription = "Pourquoi choisir IBIG IMMO TRUST ? Agence immobilière à Abidjan spécialisée en immobilier, BTP, assistance foncière et financement pour particuliers, entreprises et diaspora.";
$currentPage = "pourquoi";
require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/header.php";
?>

<style>
/* ===== HERO ===== */
.pq-hero {
  background: linear-gradient(135deg, #1340B0 0%, #1B4FD8 60%, #1E57E8 100%);
  padding: 75px 0 60px;
  position: relative; overflow: hidden; text-align: center;
}
.pq-hero::before {
  content:''; position:absolute; top:-100px; left:50%; transform:translateX(-50%);
  width:600px; height:400px;
  background:radial-gradient(ellipse,rgba(232,40,42,0.06) 0%,transparent 70%);
}
.pq-hero .kicker { font-size:11px; font-weight:800; letter-spacing:0.15em; text-transform:uppercase; color:#E8282A; margin-bottom:14px; }
.pq-hero h1 { font-size:clamp(24px,3.2vw,44px); font-weight:900; color:#fff; margin:0 0 14px; letter-spacing:-0.02em; line-height:1.2; }
.pq-hero h1 span { color:#E8282A; }
.pq-hero .desc { font-size:16px; color:rgba(255,255,255,0.65); max-width:700px; margin:0 auto; line-height:1.75; }

/* ===== GRID RAISONS ===== */
.pq-section { padding:70px 0; background:#FAF7F0; }
.pq-intro h2 { font-size:clamp(20px,2.5vw,30px); font-weight:800; color:#1B4FD8; margin-bottom:10px; }
.pq-intro p { font-size:15px; color:#4B5563; line-height:1.75; max-width:780px; margin-bottom:42px; }

.pq-grid {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(270px,1fr));
  gap:26px;
}
.pq-card {
  background:#fff;
  border-radius:16px;
  padding:30px 26px;
  box-shadow:0 6px 24px rgba(27,79,216,0.07);
  border:1px solid rgba(27,79,216,0.05);
  border-left:4px solid #E8282A;
  transition:all 0.3s ease;
}
.pq-card:hover { transform:translateY(-4px); box-shadow:0 16px 40px rgba(27,79,216,0.12); }
.pq-card .icon {
  width:44px; height:44px;
  background:linear-gradient(135deg,#E8282A,#C01A1C);
  border-radius:11px;
  display:flex; align-items:center; justify-content:center;
  color:#1B4FD8; font-size:17px; margin-bottom:16px;
}
.pq-card h3 { font-size:16px; font-weight:800; color:#1B4FD8; margin-bottom:8px; }
.pq-card p { font-size:14px; color:#4B5563; line-height:1.7; }

/* ===== STATS ===== */
.pq-stats {
  padding:60px 0;
  background:linear-gradient(135deg,#1340B0,#1B4FD8);
}
.pq-stats-grid {
  display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr));
  gap:30px; text-align:center;
}
.pq-stat-val { font-size:42px; font-weight:900; color:#E8282A; line-height:1; }
.pq-stat-label { font-size:13px; color:rgba(255,255,255,0.55); margin-top:8px; font-weight:600; text-transform:uppercase; letter-spacing:0.06em; }

/* ===== ENGAGEMENT ===== */
.pq-engagement { padding:70px 0; background:#FAF7F0; text-align:center; }
.pq-engagement h2 { font-size:clamp(20px,2.5vw,32px); font-weight:900; color:#1B4FD8; margin-bottom:12px; }
.pq-engagement p { font-size:15px; color:#4B5563; max-width:700px; margin:0 auto 34px; line-height:1.75; }
.pq-cta-row { display:flex; justify-content:center; gap:14px; flex-wrap:wrap; }
.pq-btn-gold {
  display:inline-flex; align-items:center; gap:8px;
  padding:14px 28px; border-radius:999px;
  background:linear-gradient(135deg,#E8282A,#C01A1C);
  color:#1B4FD8; font-size:14px; font-weight:800; text-decoration:none; transition:all 0.3s ease;
}
.pq-btn-gold:hover { box-shadow:0 8px 25px rgba(232,40,42,0.45); transform:translateY(-2px); }
.pq-btn-navy {
  display:inline-flex; align-items:center; gap:8px;
  padding:14px 28px; border-radius:999px;
  background:#1B4FD8; color:#fff;
  font-size:14px; font-weight:800; text-decoration:none; transition:all 0.3s ease;
}
.pq-btn-navy:hover { background:#1a3060; transform:translateY(-2px); }
</style>

<!-- HERO -->
<section class="pq-hero">
  <div class="container">
    <p class="kicker">IBIG IMMO TRUST &bull; NOS ATOUTS</p>
    <h1>Pourquoi choisir <span>IBIG IMMO TRUST</span>&nbsp;?</h1>
    <p class="desc">Une agence immobilière de confiance basée à Abidjan, spécialisée dans l'immobilier, le BTP, l'assistance foncière et le financement pour particuliers, entreprises et diaspora.</p>
  </div>
</section>

<!-- RAISONS -->
<section class="pq-section">
  <div class="container">
    <div class="pq-intro">
      <h2>Un partenaire immobilier fiable en Côte d'Ivoire</h2>
      <p>Choisir IBIG IMMO TRUST, c'est faire le choix d'un partenaire stratégique capable de sécuriser chaque étape de votre projet immobilier. De la recherche du bien à la construction, en passant par la vérification foncière et le financement, nous offrons une approche globale et professionnelle.</p>
    </div>

    <div class="pq-grid">

      <div class="pq-card">
        <div class="icon"><i class="fa-solid fa-chart-line"></i></div>
        <h3>Expertise immobilière en Côte d'Ivoire</h3>
        <p>Notre parfaite connaissance du marché d'Abidjan et de l'intérieur du pays nous permet d'identifier les meilleures opportunités d'achat, de vente et d'investissement.</p>
      </div>

      <div class="pq-card">
        <div class="icon"><i class="fa-solid fa-file-shield"></i></div>
        <h3>Sécurité foncière et assistance ACD</h3>
        <p>Vérification des documents, ACD, titres fonciers et conformité juridique pour éviter tout risque de litige sur vos acquisitions foncières.</p>
      </div>

      <div class="pq-card">
        <div class="icon"><i class="fa-solid fa-helmet-safety"></i></div>
        <h3>BTP et gestion de projets de construction</h3>
        <p>Construction, rénovation et suivi de chantier avec des équipes qualifiées, des délais maîtrisés et un contrôle permanent de la qualité des travaux.</p>
      </div>

      <div class="pq-card">
        <div class="icon"><i class="fa-solid fa-coins"></i></div>
        <h3>Solutions de financement immobilier</h3>
        <p>Grâce à nos partenaires bancaires et financiers, nous accompagnons nos clients dans l'accès à des solutions de financement adaptées à leurs capacités.</p>
      </div>

      <div class="pq-card">
        <div class="icon"><i class="fa-solid fa-globe-africa"></i></div>
        <h3>Spécialiste de la diaspora</h3>
        <p>Vous résidez à l'étranger ? IBIG IMMO TRUST est le relais de confiance pour investir, construire et gérer des biens en Côte d'Ivoire, à distance et en toute sécurité.</p>
      </div>

      <div class="pq-card">
        <div class="icon"><i class="fa-solid fa-arrows-spin"></i></div>
        <h3>Une approche globale et transparente</h3>
        <p>Immobilier, BTP, gestion locative et financement réunis au sein d'une même structure pour offrir clarté, efficacité et sérénité à nos clients.</p>
      </div>

      <div class="pq-card">
        <div class="icon"><i class="fa-solid fa-key"></i></div>
        <h3>Gestion locative sécurisée</h3>
        <p>Sélection rigoureuse des locataires, contrats sécurisés, encaissement et suivi des loyers, entretien et valorisation continue de votre patrimoine.</p>
      </div>

      <div class="pq-card">
        <div class="icon"><i class="fa-solid fa-chart-bar"></i></div>
        <h3>Suivi, transparence & reporting</h3>
        <p>Reporting périodique, comptes rendus de gestion, visibilité sur les loyers, les charges et l'état de votre patrimoine, en toute transparence.</p>
      </div>

    </div>
  </div>
</section>

<!-- STATS -->
<section class="pq-stats">
  <div class="container">
    <div class="pq-stats-grid">
      <div><div class="pq-stat-val">360°</div><div class="pq-stat-label">Accompagnement projet</div></div>
      <div><div class="pq-stat-val">24/7</div><div class="pq-stat-label">Suivi diaspora</div></div>
      <div><div class="pq-stat-val">A à Z</div><div class="pq-stat-label">Vision immobilière & BTP</div></div>
      <div><div class="pq-stat-val">3</div><div class="pq-stat-label">Villes couvertes</div></div>
    </div>
  </div>
</section>

<!-- ENGAGEMENT -->
<section class="pq-engagement">
  <div class="container">
    <h2>Notre engagement envers nos clients</h2>
    <p>Rigueur, transparence et professionnalisme sont au cœur de notre engagement. Chez IBIG IMMO TRUST, chaque projet est traité avec sérieux afin de protéger et valoriser durablement votre patrimoine immobilier.</p>
    <div class="pq-cta-row">
      <a href="<?= BASE_URL ?>/tous_les_biens.php" class="pq-btn-gold"><i class="fa-solid fa-building"></i> Voir nos biens</a>
      <a href="<?= BASE_URL ?>/services.php" class="pq-btn-navy"><i class="fa-solid fa-list"></i> Nos services</a>
      <a href="<?= BASE_URL ?>/rdv.php" class="pq-btn-gold"><i class="fa-regular fa-calendar-check"></i> Prendre RDV</a>
    </div>
  </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>