<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/tracker.php';

$currentPage = 'diaspora';
$pageTitle   = "Offre Diaspora – Investir depuis l’étranger | IBIG IMMO TRUST";

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

include __DIR__ . '/includes/header.php';
?>

<style>
:root{
  --immo-red:#D4AF37;
  --immo-red-dark:#A68920;
  --immo-orange:#E8CC6A;
  --immo-gold:#E8CC6A;
  --immo-dark:#0A1628;
  --immo-dark-2:#0f2044;
  --immo-text:#334155;
  --immo-muted:#64748b;
  --immo-line:#e2e8f0;
  --immo-soft:#FAF7F0;
  --immo-soft-2:#f0ead6;
  --immo-white:#ffffff;
  --shadow-xs:0 4px 16px rgba(15,23,42,.06);
  --shadow-sm:0 14px 34px rgba(15,23,42,.08);
  --shadow-md:0 24px 60px rgba(15,23,42,.12);
  --shadow-lg:0 30px 80px rgba(212,175,55,.15);
  --radius-sm:16px;
  --radius-md:22px;
  --radius-lg:30px;
  --container:1240px;
}

*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{
  margin:0;
  font-family:Inter, Arial, Helvetica, sans-serif;
  color:var(--immo-dark);
  background:#fff;
  line-height:1.65;
}
img{max-width:100%;display:block}
a{text-decoration:none;color:inherit}
button,input,select,textarea{font:inherit}

.container{
  width:min(var(--container), calc(100% - 32px));
  margin:0 auto;
}

.section{
  padding:84px 0;
  position:relative;
}

.section-alt{
  background:
    radial-gradient(circle at top right, rgba(212,175,55,.08), transparent 28%),
    linear-gradient(180deg,#FAF7F0 0%,#ffffff 100%);
}

.section-head{
  margin-bottom:30px;
}

.section-title{
  margin:0 0 14px;
  font-size:clamp(20px,2vw,30px);
  line-height:1.08;
  font-weight:900;
  letter-spacing:-.03em;
  color:var(--immo-dark);
}

.section-intro{
  margin:0;
  max-width:860px;
  font-size:17px;
  color:var(--immo-muted);
}

.btn-primary,
.btn-secondary,
.btn-light,
.btn-dark{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  min-height:52px;
  padding:0 22px;
  border-radius:14px;
  border:none;
  cursor:pointer;
  font-size:15px;
  font-weight:800;
  transition:.25s ease;
  text-decoration:none;
  white-space:nowrap;
}

.btn-primary{
  background:linear-gradient(135deg,var(--immo-red) 0%, var(--immo-gold) 100%);
  color:#2f1b00;
  box-shadow:0 14px 34px rgba(212,175,55,.28);
}
.btn-primary:hover{
  transform:translateY(-2px);
  box-shadow:0 18px 40px rgba(212,175,55,.35);
}

.btn-secondary{
  background:rgba(255,255,255,.14);
  color:#fff;
  border:1px solid rgba(255,255,255,.24);
}
.btn-secondary:hover{
  background:rgba(255,255,255,.20);
  transform:translateY(-2px);
}

.btn-light{
  background:#fff;
  color:var(--immo-red);
  border:1px solid rgba(212,175,55,.10);
}
.btn-light:hover{
  transform:translateY(-2px);
}

.btn-dark{
  background:var(--immo-dark);
  color:#fff;
  box-shadow:var(--shadow-xs);
}
.btn-dark:hover{
  background:#09101d;
  transform:translateY(-2px);
}

.btn-large{
  min-height:58px;
  padding:0 28px;
  font-size:16px;
}

/* HERO */
.diaspora-hero{
  position:relative;
  overflow:hidden;
  padding:48px 0 90px;
  background:
    radial-gradient(circle at 12% 10%, rgba(212,175,55,.15), transparent 25%),
    radial-gradient(circle at 90% 15%, rgba(212,175,55,.08), transparent 25%),
    linear-gradient(135deg, #060d1a 0%, #0A1628 50%, #0f2044 100%);
  color:#fff;
}

.diaspora-hero::before{
  content:"";
  position:absolute;
  inset:0;
  background:
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(180deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size:34px 34px;
  opacity:.35;
  pointer-events:none;
}

.hero-grid{
  position:relative;
  z-index:1;
  display:grid;
  grid-template-columns:1.06fr .94fr;
  gap:40px;
  align-items:center;
}

.hero-eyebrow{
  display:inline-flex;
  align-items:center;
  gap:10px;
  margin:0 0 16px;
  padding:10px 14px;
  border-radius:999px;
  background:rgba(255,255,255,.12);
  border:1px solid rgba(255,255,255,.20);
  font-size:13px;
  font-weight:900;
  text-transform:uppercase;
  letter-spacing:.05em;
}

.hero-title{
  margin:0 0 18px;
  font-size:clamp(26px,3vw,44px);
  line-height:1.05;
  font-weight:900;
  letter-spacing:-.03em;
  text-shadow:0 8px 24px rgba(0,0,0,.15);
}

.hero-text{
  margin:0 0 20px;
  max-width:760px;
  font-size:18px;
  color:rgba(255,255,255,.93);
}

.hero-actions{
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  margin:0 0 22px;
}

.hero-points{
  display:flex;
  flex-wrap:wrap;
  gap:12px;
}

.hero-points span{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:11px 15px;
  border-radius:999px;
  background:rgba(255,255,255,.16);
  border:1px solid rgba(255,255,255,.25);
  font-size:14px;
  font-weight:700;
  backdrop-filter:blur(6px);
}

.hero-points span::before{
  content:"\2713";
  color:#ffe07d;
  font-weight:900;
}

.hero-visual{
  position:relative;
}

.hero-visual-main{
  overflow:hidden;
  border-radius:28px;
  border:1px solid rgba(255,255,255,.22);
  box-shadow:var(--shadow-lg);
  background:rgba(255,255,255,.08);
}

.hero-visual-main img{
  width:100%;
  height:600px;
  object-fit:cover;
}

.hero-float{
  position:absolute;
  max-width:250px;
  padding:16px 18px;
  border-radius:18px;
  background:rgba(255,255,255,.14);
  border:1px solid rgba(255,255,255,.24);
  backdrop-filter:blur(10px);
  box-shadow:var(--shadow-sm);
  color:#fff;
}

.hero-float.top{
  top:18px;
  left:-16px;
}

.hero-float.bottom{
  right:-10px;
  bottom:18px;
}

.hero-float strong{
  display:block;
  margin-bottom:6px;
  font-size:15px;
}

.hero-float span{
  font-size:13px;
  color:rgba(255,255,255,.92);
}

/* INTRO BAR */
.intro-strip{
  position:relative;
  z-index:2;
  margin-top:-30px;
}

.intro-box{
  display:grid;
  grid-template-columns:repeat(3,1fr);
  gap:18px;
  padding:22px;
  background:#fff;
  border-radius:24px;
  border:1px solid rgba(15,23,42,.06);
  box-shadow:var(--shadow-md);
}

.intro-item{
  display:flex;
  gap:14px;
  align-items:flex-start;
}

.intro-icon{
  width:54px;
  height:54px;
  min-width:54px;
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(135deg, rgba(212,175,55,.08), rgba(212,175,55,.12));
  color:var(--immo-red);
  font-size:24px;
}

.intro-item h3{
  margin:0 0 4px;
  font-size:17px;
  line-height:1.2;
  font-weight:900;
}

.intro-item p{
  margin:0;
  font-size:14px;
  color:var(--immo-muted);
}

/* SPLIT */
.split-grid{
  display:grid;
  grid-template-columns:.95fr 1.05fr;
  gap:36px;
  align-items:center;
}

.visual-card{
  position:relative;
  overflow:hidden;
  border-radius:28px;
  box-shadow:var(--shadow-md);
  background:#f3f4f6;
  min-height:560px;
}

.visual-card img{
  width:100%;
  height:560px;
  object-fit:cover;
}

.visual-overlay{
  position:absolute;
  left:20px;
  right:20px;
  bottom:20px;
  padding:20px;
  border-radius:20px;
  background:rgba(15,23,42,.72);
  color:#fff;
  backdrop-filter:blur(8px);
}

.visual-overlay h3{
  margin:0 0 8px;
  font-size:22px;
  line-height:1.2;
  font-weight:900;
}

.visual-overlay p{
  margin:0;
  font-size:14px;
  color:rgba(255,255,255,.92);
}

.feature-list{
  list-style:none;
  padding:0;
  margin:18px 0 0;
  display:grid;
  gap:12px;
}

.feature-list li{
  display:flex;
  gap:12px;
  align-items:flex-start;
  color:var(--immo-dark);
  font-weight:700;
}

.feature-list li::before{
  content:"\2713";
  width:26px;
  height:26px;
  min-width:26px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:50%;
  background:rgba(212,175,55,.08);
  color:var(--immo-red);
  font-size:14px;
  font-weight:900;
  margin-top:1px;
}

/* BENEFITS */
.cards-grid{
  display:grid;
  grid-template-columns:repeat(3,minmax(0,1fr));
  gap:22px;
}

.benefit-card{
  background:#fff;
  border:1px solid var(--immo-line);
  border-radius:22px;
  padding:24px 20px;
  box-shadow:var(--shadow-sm);
  transition:.28s ease;
  height:100%;
}

.benefit-card:hover{
  transform:translateY(-6px);
  box-shadow:var(--shadow-md);
}

.benefit-icon{
  width:56px;
  height:56px;
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(135deg, rgba(212,175,55,.08), rgba(212,175,55,.12));
  color:var(--immo-red);
  font-size:24px;
  margin-bottom:14px;
}

.benefit-card h3{
  margin:0 0 8px;
  font-size:20px;
  line-height:1.2;
  font-weight:900;
}

.benefit-card p{
  margin:0;
  color:var(--immo-muted);
  font-size:15px;
}

/* STEPS */
.steps-grid{
  display:grid;
  grid-template-columns:repeat(4,1fr);
  gap:22px;
}

.step{
  position:relative;
  overflow:hidden;
  background:#fff;
  border:1px solid var(--immo-line);
  border-radius:22px;
  padding:28px 22px;
  box-shadow:var(--shadow-sm);
}

.step::after{
  content:"";
  position:absolute;
  top:0;
  left:0;
  width:100%;
  height:4px;
  background:linear-gradient(90deg,var(--immo-red),var(--immo-gold));
}

.step-number{
  width:54px;
  height:54px;
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(135deg,var(--immo-red),var(--immo-gold));
  color:#fff;
  font-size:22px;
  font-weight:900;
  margin-bottom:16px;
  box-shadow:0 12px 24px rgba(212,175,55,.20);
}

.step h3{
  margin:0 0 8px;
  font-size:20px;
  line-height:1.2;
  font-weight:900;
}

.step p{
  margin:0;
  color:var(--immo-muted);
  font-size:15px;
}

/* PACKAGE */
.offer-box{
  background:#fff;
  border:1px solid var(--immo-line);
  border-radius:28px;
  padding:28px;
  box-shadow:var(--shadow-md);
}

.offer-top{
  display:flex;
  justify-content:space-between;
  align-items:flex-start;
  gap:22px;
  flex-wrap:wrap;
  margin-bottom:22px;
}

.offer-badge{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:8px 14px;
  border-radius:999px;
  background:#fff4ef;
  color:var(--immo-red);
  border:1px solid rgba(212,175,55,.08);
  font-size:12px;
  font-weight:900;
  margin-bottom:12px;
}

.offer-box h3{
  margin:0 0 8px;
  font-size:30px;
  line-height:1.08;
  font-weight:900;
  color:var(--immo-dark);
}

.offer-box p{
  margin:0;
  color:var(--immo-muted);
  font-size:16px;
  max-width:820px;
}

.offer-note{
  min-width:250px;
  padding:18px;
  border-radius:20px;
  background:linear-gradient(135deg, rgba(212,175,55,.04), rgba(212,175,55,.08));
  border:1px solid rgba(212,175,55,.08);
}

.offer-note strong{
  display:block;
  margin-bottom:6px;
  font-size:18px;
  line-height:1.2;
}

.offer-note span{
  color:var(--immo-muted);
  font-size:14px;
}

.offer-grid{
  display:grid;
  grid-template-columns:repeat(2,minmax(0,1fr));
  gap:18px;
  margin-top:18px;
}

.offer-item{
  display:flex;
  gap:14px;
  align-items:flex-start;
  padding:18px;
  border-radius:20px;
  background:#fafafa;
  border:1px solid #edf0f3;
}

.offer-item .icon{
  width:46px;
  height:46px;
  min-width:46px;
  border-radius:14px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:#fff1ed;
  color:var(--immo-red);
  font-size:20px;
}

.offer-item h4{
  margin:0 0 4px;
  font-size:16px;
  line-height:1.2;
  font-weight:900;
}

.offer-item p{
  margin:0;
  font-size:14px;
  color:var(--immo-muted);
}

/* CTA */
.cta-section{
  position:relative;
  overflow:hidden;
  padding:90px 0;
  background:
    radial-gradient(circle at left center, rgba(212,175,55,.12), transparent 28%),
    linear-gradient(135deg,#060d1a 0%,#0A1628 50%,#0f2044 100%);
  color:#fff;
}

.cta-section::before{
  content:"";
  position:absolute;
  inset:0;
  background:
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(180deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size:34px 34px;
  opacity:.45;
  pointer-events:none;
}

.cta-inner{
  position:relative;
  z-index:1;
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:26px;
  flex-wrap:wrap;
}

.cta-inner h2{
  margin:0 0 8px;
  font-size:clamp(20px,2.2vw,32px);
  line-height:1.05;
  font-weight:900;
  letter-spacing:-.03em;
}

.cta-inner p{
  margin:0;
  max-width:780px;
  color:rgba(255,255,255,.92);
  font-size:17px;
}

/* RESPONSIVE */
@media (max-width: 1180px){
  .hero-grid,
  .split-grid{
    grid-template-columns:1fr;
  }

  .intro-box,
  .cards-grid,
  .steps-grid,
  .offer-grid{
    grid-template-columns:repeat(2,1fr);
  }

  .hero-visual-main img,
  .visual-card img{
    height:460px;
  }

  .hero-float.top{left:12px}
  .hero-float.bottom{right:12px}
}

@media (max-width: 760px){
  .section{
    padding:68px 0;
  }

  .diaspora-hero{
    padding:24px 0 66px;
  }

  .container{
    width:min(var(--container), calc(100% - 22px));
  }

  .intro-box,
  .cards-grid,
  .steps-grid,
  .offer-grid{
    grid-template-columns:1fr;
  }

  .hero-actions,
  .cta-inner{
    flex-direction:column;
    align-items:stretch;
  }

  .btn-primary,
  .btn-secondary,
  .btn-light,
  .btn-dark{
    width:100%;
  }

  .hero-float{
    position:static;
    margin-top:14px;
    max-width:none;
  }

  .hero-visual-main img,
  .visual-card img{
    height:340px;
  }

  .offer-box{
    padding:22px 18px;
  }
}

.hero-title{
  font-size:56px;
  font-weight:800;
  line-height:1.1;
  color:#ffffff;
  letter-spacing:-1px;
}

.hero-intro{
  font-size:18px;
  line-height:1.7;
  color:rgba(255,255,255,0.92);
  max-width:720px;
}

.hero-diaspora{
  background:linear-gradient(135deg,#D4AF37,#A68920);
  color:white;
  padding:110px 0;
}

.hero-badge{
  display:inline-block;
  padding:8px 16px;
  border-radius:30px;
  background:rgba(255,255,255,0.15);
  color:#fff;
  font-weight:600;
  font-size:13px;
  backdrop-filter:blur(8px);
}


</style>

<!-- ===================== HERO ===================== -->
<section class="diaspora-hero">
  <div class="container hero-grid">

    <div>
      <div class="hero-eyebrow">&#127758; Offre diaspora &#8226; Immobilier &#8226; Suivi à distance</div>

      <h1 class="hero-title">Investir depuis l’étranger en toute confiance</h1>

      <p class="hero-text">
        Une approche adaptée aux clients hors de Côte d’Ivoire : coordination,
        reporting, supervision et exécution structurée pour construire, rénover,
        louer, sécuriser ou valoriser un bien immobilier en toute sérénité.
      </p>

      <div class="hero-actions">
        <a href="/contact.php" class="btn-primary btn-large" onclick="trackEvent('diaspora_contact_top');">
          Décrire mon projet
        </a>
        <a href="/rdv.php" class="btn-secondary btn-large" onclick="trackEvent('diaspora_rdv_top');">
          Programmer un échange
        </a>
      </div>

      <div class="hero-points">
        <span>Suivi digital structuré</span>
        <span>Coordination terrain</span>
        <span>Vision claire des budgets</span>
      </div>
    </div>

    <div class="hero-visual">
      <div class="hero-visual-main">
        <img src="/assets/img/investisseurs-afrique.jpg" alt="Offre diaspora IBIG IMMO TRUST">
      </div>

      <div class="hero-float top">
        <strong>&#128205; Depuis l’étranger, sans perte de contrôle</strong>
        <span>Un cadre organisé pour piloter votre projet immobilier à distance.</span>
      </div>

      <div class="hero-float bottom">
        <strong>&#128247; Reporting visuel &amp; comptes rendus</strong>
        <span>Photos, vidéos, étapes et décisions partagées avec régularité.</span>
      </div>
    </div>

  </div>
</section>

<!-- ===================== INTRO STRIP ===================== -->
<div class="intro-strip">
  <div class="container">
    <div class="intro-box">

      <div class="intro-item">
        <div class="intro-icon">&#128241;</div>
        <div>
          <h3>Suivi à distance</h3>
          <p>Vous gardez une visibilité continue sur le projet, même hors du pays.</p>
        </div>
      </div>

      <div class="intro-item">
        <div class="intro-icon">&#128176;</div>
        <div>
          <h3>Lecture budgétaire</h3>
          <p>Contrôle des dépenses, étapes d’engagement et meilleure lisibilité financière.</p>
        </div>
      </div>

      <div class="intro-item">
        <div class="intro-icon">&#127959;</div>
        <div>
          <h3>Supervision structurée</h3>
          <p>Exécution, coordination, reporting et suivi terrain selon un cadre défini.</p>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ===================== PRESENTATION ===================== -->
<section class="section">
  <div class="container split-grid">

    <div class="visual-card">
      <img src="/assets/img/construction-afrique.jpg" alt="Projet immobilier piloté pour la diaspora">
      <div class="visual-overlay">
        <h3>Une offre spéciale pour la diaspora</h3>
        <p>
          Un dispositif pensé pour les clients qui souhaitent investir, construire,
          finir, rénover ou mettre en location un bien en Côte d’Ivoire tout en vivant à l’étranger.
        </p>
      </div>
    </div>

    <div>
      <div class="section-head">
        <h2 class="section-title">Une formule rassurante, claire et opérationnelle</h2>
        <p class="section-intro" style="text-align:left;max-width:none;">
          Vous vivez hors du pays et souhaitez construire, finir, rénover, louer ou sécuriser un bien immobilier
          en Côte d’Ivoire ? IBIG IMMO TRUST met en place un cadre de suivi clair, pratique et rassurant,
          afin de limiter l’incertitude et de mieux piloter chaque décision.
        </p>
      </div>

      <ul class="feature-list">
        <li>Suivi digital du chantier avec comptes rendus périodiques</li>
        <li>Photos, vidéos et étapes d’avancement communiquées</li>
        <li>Contrôle de l’utilisation des budgets et du calendrier</li>
        <li>Gestion locative après livraison selon la stratégie retenue</li>
        <li>Coordination avec partenaires techniques et financiers</li>
        <li>Orientation sur les opportunités les plus pertinentes</li>
      </ul>

      <div style="display:flex;gap:14px;flex-wrap:wrap;margin-top:24px;">
        <a href="/contact.php" class="btn-dark" onclick="trackEvent('diaspora_contact_mid');">
          Demander un accompagnement
        </a>
        <a href="/rdv.php" class="btn-light" onclick="trackEvent('diaspora_rdv_mid');">
          Prendre RDV
        </a>
      </div>
    </div>

  </div>
</section>

<!-- ===================== AVANTAGES ===================== -->
<section class="section section-alt">
  <div class="container">

    <div class="section-head">
      <h2 class="section-title">Ce que l’offre diaspora vous apporte concrètement</h2>
      <p class="section-intro">
        Une meilleure visibilité, plus de contrôle, moins d’incertitude et un accompagnement structuré
        pour avancer avec davantage de confiance sur votre projet immobilier.
      </p>
    </div>

    <div class="cards-grid">

      <article class="benefit-card">
        <div class="benefit-icon">&#128248;</div>
        <h3>Reporting visuel régulier</h3>
        <p>Réception d’images, de vidéos et de synthèses pour suivre les étapes réelles du projet.</p>
      </article>

      <article class="benefit-card">
        <div class="benefit-icon">&#128736;</div>
        <h3>Coordination des intervenants</h3>
        <p>Organisation des échanges avec les équipes techniques, artisans, fournisseurs ou partenaires terrain.</p>
      </article>

      <article class="benefit-card">
        <div class="benefit-icon">&#128202;</div>
        <h3>Suivi du budget et du délai</h3>
        <p>Lecture plus claire des engagements financiers, du calendrier et de l’avancement réel.</p>
      </article>

      <article class="benefit-card">
        <div class="benefit-icon">&#127969;</div>
        <h3>Valorisation du bien</h3>
        <p>Orientation vers une meilleure exploitation : location, mise en valeur, finition ou repositionnement.</p>
      </article>

      <article class="benefit-card">
        <div class="benefit-icon">&#128274;</div>
        <h3>Cadre plus sécurisé</h3>
        <p>Décisions mieux cadrées, suivi plus structuré et limitation des zones d’ombre dans le pilotage.</p>
      </article>

      <article class="benefit-card">
        <div class="benefit-icon">&#127760;</div>
        <h3>Confort de pilotage à distance</h3>
        <p>Vous restez impliqué dans les décisions importantes sans devoir être physiquement présent en permanence.</p>
      </article>

    </div>

  </div>
</section>

<!-- ===================== ETAPES ===================== -->
<section class="section">
  <div class="container">

    <div class="section-head">
      <h2 class="section-title">Comment se déroule l’accompagnement diaspora</h2>
      <p class="section-intro">
        Une démarche progressive, structurée et lisible, depuis la compréhension du besoin
        jusqu’au suivi opérationnel et à la valorisation du bien.
      </p>
    </div>

    <div class="steps-grid">

      <article class="step">
        <div class="step-number">1</div>
        <h3>Échange initial</h3>
        <p>Compréhension du besoin, du contexte, des objectifs, du budget et du niveau d’avancement du projet.</p>
      </article>

      <article class="step">
        <div class="step-number">2</div>
        <h3>Cadrage du projet</h3>
        <p>Définition des priorités, des modalités de suivi, des jalons et des attentes en matière de reporting.</p>
      </article>

      <article class="step">
        <div class="step-number">3</div>
        <h3>Suivi &amp; coordination</h3>
        <p>Supervision terrain, remontées d’informations, contrôle du calendrier et accompagnement des décisions.</p>
      </article>

      <article class="step">
        <div class="step-number">4</div>
        <h3>Livraison &amp; exploitation</h3>
        <p>Mise en location, valorisation, exploitation ou nouvelle phase selon la stratégie retenue pour le bien.</p>
      </article>

    </div>

  </div>
</section>

<!-- ===================== CONTENU DE L’OFFRE ===================== -->
<section class="section section-alt">
  <div class="container">

    <div class="offer-box">

      <div class="offer-top">
        <div>
          <span class="offer-badge">&#127757; Offre structurée pour clients hors de Côte d’Ivoire</span>
          <h3>L’offre Diaspora IBIG IMMO TRUST</h3>
          <p>
            Une solution d’accompagnement pensée pour vous permettre de suivre,
            sécuriser et faire avancer votre projet immobilier sans être présent en continu sur le terrain.
          </p>
        </div>

        <div class="offer-note">
          <strong>Approche sur mesure</strong>
          <span>
            L’accompagnement est ajusté selon la nature du projet :
            construction, rénovation, chantier inachevé, location, sécurisation ou valorisation.
          </span>
        </div>
      </div>

      <div class="offer-grid">

        <div class="offer-item">
          <div class="icon">&#128221;</div>
          <div>
            <h4>Diagnostic de départ</h4>
            <p>Lecture de la situation existante, des besoins, des contraintes et des objectifs du projet.</p>
          </div>
        </div>

        <div class="offer-item">
          <div class="icon">&#128241;</div>
          <div>
            <h4>Suivi digital périodique</h4>
            <p>Transmission de comptes rendus, visuels, points d’étape et éléments de compréhension.</p>
          </div>
        </div>

        <div class="offer-item">
          <div class="icon">&#128176;</div>
          <div>
            <h4>Visibilité budgétaire</h4>
            <p>Contrôle de l’évolution du budget, des besoins financiers et des arbitrages à réaliser.</p>
          </div>
        </div>

        <div class="offer-item">
          <div class="icon">&#127959;</div>
          <div>
            <h4>Coordination opérationnelle</h4>
            <p>Suivi des équipes, du chantier, des fournisseurs et de l’organisation terrain si nécessaire.</p>
          </div>
        </div>

        <div class="offer-item">
          <div class="icon">&#127970;</div>
          <div>
            <h4>Gestion après livraison</h4>
            <p>Orientation possible vers la location, la gestion locative ou la valorisation du bien livré.</p>
          </div>
        </div>

        <div class="offer-item">
          <div class="icon">&#128200;</div>
          <div>
            <h4>Vision de valorisation</h4>
            <p>Conseils pour mieux exploiter le potentiel du bien selon sa localisation et sa destination.</p>
          </div>
        </div>

      </div>

    </div>

  </div>
</section>

<!-- ===================== CTA FINAL ===================== -->
<section class="cta-section">
  <div class="container cta-inner">

    <div>
      <h2>Vous vivez à l’étranger et avez un projet immobilier en Côte d’Ivoire ?</h2>
      <p>
        IBIG IMMO TRUST vous propose un accompagnement plus clair, plus structuré
        et plus rassurant pour faire avancer votre projet à distance dans de meilleures conditions.
      </p>
    </div>

    <div style="display:flex;gap:14px;flex-wrap:wrap;">
      <a href="/contact.php" class="btn-primary btn-large" onclick="trackEvent('diaspora_cta_contact');">
        Décrire mon projet
      </a>
      <a href="/rdv.php" class="btn-light btn-large" onclick="trackEvent('diaspora_cta_rdv');">
        Programmer un échange
      </a>
    </div>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>