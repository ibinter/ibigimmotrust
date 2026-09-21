<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'services';
$pageTitle   = "Loyer garanti – IBIG IMMO TRUST";
$metaDescription = "IBIG IMMO TRUST vous garantit le versement de votre loyer chaque mois, au plus tard le 10 du mois — gestion locative professionnelle en Côte d'Ivoire.";
include __DIR__ . '/includes/header.php';
?>

<style>
/* ==========================================
   LOYER GARANTI – IBIG IMMO TRUST
========================================== */
.lg-hero {
  background: linear-gradient(135deg, #060d1a 0%, #0A1628 55%, #0f2044 100%);
  color: #fff;
  padding: 80px 20px;
  text-align: center;
}
.lg-hero .lg-kicker {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 11px;
  font-weight: 800;
  color: #D4AF37;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  margin-bottom: 18px;
  background: rgba(212,175,55,0.1);
  border: 1px solid rgba(212,175,55,0.25);
  padding: 6px 16px;
  border-radius: 999px;
}
.lg-hero h1 {
  font-size: clamp(26px, 3.2vw, 46px);
  font-weight: 900;
  margin-bottom: 18px;
  line-height: 1.2;
  color: #fff;
}
.lg-hero h1 span { color: #D4AF37; }
.lg-hero p {
  font-size: 17px;
  max-width: 700px;
  margin: 0 auto 32px;
  line-height: 1.7;
  color: rgba(255,255,255,0.85);
}
.lg-hero-btns {
  display: flex;
  gap: 14px;
  justify-content: center;
  flex-wrap: wrap;
}
.lg-btn-gold {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 13px 26px;
  background: linear-gradient(135deg, #D4AF37, #A68920);
  color: #0A1628;
  border-radius: 999px;
  font-weight: 800;
  font-size: 14px;
  text-decoration: none;
  box-shadow: 0 6px 20px rgba(212,175,55,0.35);
  transition: all 0.25s ease;
}
.lg-btn-gold:hover { box-shadow: 0 10px 30px rgba(212,175,55,0.55); transform: translateY(-2px); }
.lg-btn-outline {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 13px 26px;
  border: 1.5px solid rgba(255,255,255,0.35);
  color: #fff;
  border-radius: 999px;
  font-weight: 700;
  font-size: 14px;
  text-decoration: none;
  background: rgba(255,255,255,0.08);
  transition: all 0.25s ease;
}
.lg-btn-outline:hover { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.6); }

/* Badge image hero */
.lg-hero-badges {
  display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
  margin-top: 28px;
}
.lg-badge {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 7px 14px;
  border-radius: 999px;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(212,175,55,0.2);
  font-size: 12.5px;
  font-weight: 600;
  color: rgba(255,255,255,0.8);
}
.lg-badge i { color: #D4AF37; }

/* Sections */
.lg-section {
  padding: 72px 20px;
  background: #fff;
}
.lg-section--soft { background: #f5f7fa; }
.lg-container {
  max-width: 1100px;
  margin: 0 auto;
}
.lg-section-head {
  text-align: center;
  margin-bottom: 44px;
}
.lg-section-head h2 {
  font-size: clamp(22px, 2.5vw, 34px);
  font-weight: 900;
  color: #0A1628;
  margin-bottom: 14px;
  line-height: 1.25;
}
.lg-section-head h2 span { color: #D4AF37; }
.lg-section-head p {
  font-size: 16px;
  color: #374151;
  max-width: 700px;
  margin: 0 auto;
  line-height: 1.7;
}

/* Cards */
.lg-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 24px;
}
.lg-card {
  background: #fff;
  border-radius: 16px;
  padding: 28px;
  box-shadow: 0 8px 28px rgba(0,0,0,0.08);
  border-top: 3px solid #D4AF37;
  transition: all 0.25s ease;
}
.lg-section--soft .lg-card { background: #fff; }
.lg-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.12); }
.lg-card-icon {
  width: 46px;
  height: 46px;
  border-radius: 12px;
  background: rgba(212,175,55,0.1);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 14px;
}
.lg-card-icon i { color: #D4AF37; font-size: 18px; }
.lg-card h3 {
  font-size: 17px;
  font-weight: 900;
  color: #0A1628;
  margin-bottom: 10px;
  line-height: 1.3;
}
.lg-card p {
  font-size: 14.5px;
  color: #374151;
  line-height: 1.65;
  margin: 0;
}

/* Stats bar */
.lg-stats {
  background: linear-gradient(135deg, #060d1a 0%, #0A1628 100%);
  padding: 52px 20px;
}
.lg-stats-grid {
  max-width: 900px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 24px;
  text-align: center;
}
.lg-stat strong {
  display: block;
  font-size: 40px;
  font-weight: 900;
  color: #D4AF37;
  line-height: 1;
  margin-bottom: 8px;
}
.lg-stat span {
  font-size: 13.5px;
  color: rgba(255,255,255,0.7);
  font-weight: 600;
  line-height: 1.4;
}

/* CTA bottom */
.lg-cta {
  background: linear-gradient(135deg, #D4AF37 0%, #A68920 100%);
  padding: 64px 20px;
  text-align: center;
}
.lg-cta h2 {
  font-size: clamp(22px, 2.5vw, 32px);
  font-weight: 900;
  color: #0A1628;
  margin-bottom: 14px;
}
.lg-cta p {
  font-size: 16px;
  color: rgba(10,22,40,0.75);
  margin-bottom: 30px;
  max-width: 600px;
  margin-left: auto;
  margin-right: auto;
  line-height: 1.65;
}
.lg-cta-btns {
  display: flex;
  gap: 14px;
  justify-content: center;
  flex-wrap: wrap;
}
.lg-cta-btn-navy {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 14px 28px;
  background: #0A1628;
  color: #fff;
  border-radius: 999px;
  font-weight: 800;
  font-size: 14px;
  text-decoration: none;
  transition: all 0.25s ease;
}
.lg-cta-btn-navy:hover { background: #060d1a; transform: translateY(-2px); }
.lg-cta-btn-white {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 14px 28px;
  background: #fff;
  color: #0A1628;
  border-radius: 999px;
  font-weight: 700;
  font-size: 14px;
  text-decoration: none;
  transition: all 0.25s ease;
}
.lg-cta-btn-white:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.12); }
</style>

<main>

<!-- HERO -->
<section class="lg-hero">
  <span class="lg-kicker"><i class="fa-solid fa-shield-halved"></i> Gestion locative premium</span>
  <h1>Le loyer garanti<br><span>IBIG IMMO TRUST</span></h1>
  <p>Recevez votre loyer chaque mois, au plus tard le <strong style="color:#D4AF37;">10 du mois</strong>, indépendamment des retards ou impayés de votre locataire — en Côte d'Ivoire et pour la diaspora.</p>
  <div class="lg-hero-btns">
    <a href="contact.php" class="lg-btn-gold"><i class="fa-solid fa-headset"></i> Parler à un conseiller</a>
    <a href="rdv.php" class="lg-btn-outline"><i class="fa-regular fa-calendar-check"></i> Prendre RDV</a>
  </div>
  <div class="lg-hero-badges">
    <span class="lg-badge"><i class="fa-solid fa-calendar-check"></i> Loyer le 10 du mois</span>
    <span class="lg-badge"><i class="fa-solid fa-file-contract"></i> Contrat sécurisé</span>
    <span class="lg-badge"><i class="fa-solid fa-globe"></i> Idéal diaspora</span>
  </div>
</section>

<!-- SECTION 1 — Ce que c'est -->
<section class="lg-section">
  <div class="lg-container">
    <div class="lg-section-head">
      <h2>Qu'est-ce que le <span>loyer garanti</span> ?</h2>
      <p>Un modèle de gestion locative moderne dans lequel IBIG IMMO TRUST s'engage à vous verser votre loyer chaque mois, indépendamment des retards ou impayés du locataire.</p>
    </div>
    <div class="lg-grid">
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-calendar-day"></i></div>
        <h3>Loyer payé au plus tard le 10 du mois</h3>
        <p>Un engagement contractuel : votre loyer vous est versé à date fixe, même si le locataire n'a pas encore payé.</p>
      </div>
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-file-contract"></i></div>
        <h3>Contrat professionnel & clair</h3>
        <p>Tous les engagements (conditions, durée, montant, responsabilités) sont formalisés dans un contrat sécurisé.</p>
      </div>
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-chart-line"></i></div>
        <h3>Suivi digital & transparence</h3>
        <p>Vous recevez un reporting régulier : paiements, maintenance, statut du locataire, dépenses, photos & vidéos lors des visites.</p>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<div class="lg-stats">
  <div class="lg-stats-grid">
    <div class="lg-stat">
      <strong>10</strong>
      <span>du mois — versement garanti</span>
    </div>
    <div class="lg-stat">
      <strong>100%</strong>
      <span>gestion déléguée</span>
    </div>
    <div class="lg-stat">
      <strong>24/7</strong>
      <span>suivi digital disponible</span>
    </div>
    <div class="lg-stat">
      <strong>360°</strong>
      <span>service de A à Z</span>
    </div>
  </div>
</div>

<!-- SECTION 2 — Ce que nous gérons -->
<section class="lg-section lg-section--soft">
  <div class="lg-container">
    <div class="lg-section-head">
      <h2>Ce que nous gérons <span>à votre place</span></h2>
      <p>Vous n'avez plus à gérer les locataires, les visites ou les travaux. IBIG IMMO TRUST s'occupe de tout.</p>
    </div>
    <div class="lg-grid">
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
        <h3>Recherche & sélection des locataires</h3>
        <p>Vérification de solvabilité, dossiers analysés, visites organisées.</p>
      </div>
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-pen-to-square"></i></div>
        <h3>Signature du bail</h3>
        <p>Contrat conforme, dépôt de garantie, état des lieux professionnel.</p>
      </div>
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-coins"></i></div>
        <h3>Perception & sécurisation des loyers</h3>
        <p>Nous assurons le recouvrement, même en cas de retard ou d'impayé.</p>
      </div>
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-screwdriver-wrench"></i></div>
        <h3>Maintenance & petites réparations</h3>
        <p>Interventions rapides pour préserver la qualité et la valeur du bien.</p>
      </div>
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-camera"></i></div>
        <h3>Reporting digital complet</h3>
        <p>Photos, vidéos, rapports périodiques — idéal pour la diaspora.</p>
      </div>
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-folder-open"></i></div>
        <h3>Dossier complet du locataire</h3>
        <p>Archivage et gestion administrative de tous les documents assurés.</p>
      </div>
    </div>
  </div>
</section>

<!-- SECTION 3 — Qui peut en bénéficier -->
<section class="lg-section">
  <div class="lg-container">
    <div class="lg-section-head">
      <h2>Qui peut bénéficier du <span>loyer garanti</span> ?</h2>
    </div>
    <div class="lg-grid">
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-house-user"></i></div>
        <h3>Propriétaires en Côte d'Ivoire</h3>
        <p>Pour sécuriser les loyers et éliminer les soucis de gestion quotidienne.</p>
      </div>
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-globe"></i></div>
        <h3>Diaspora</h3>
        <p>Votre bien est géré entièrement depuis la Côte d'Ivoire, avec un suivi digital complet à distance.</p>
      </div>
      <div class="lg-card">
        <div class="lg-card-icon"><i class="fa-solid fa-briefcase"></i></div>
        <h3>Investisseurs</h3>
        <p>Idéal pour ceux qui souhaitent un revenu locatif stable, prévisible et sans effort.</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="lg-cta">
  <h2>Vous souhaitez bénéficier du loyer garanti ?</h2>
  <p>Un conseiller IBIG IMMO TRUST peut vous accompagner dès aujourd'hui et évaluer votre bien gratuitement.</p>
  <div class="lg-cta-btns">
    <a href="contact.php" class="lg-cta-btn-navy"><i class="fa-solid fa-headset"></i> Demander un audit de mon bien</a>
    <a href="rdv.php" class="lg-cta-btn-white"><i class="fa-regular fa-calendar-check"></i> Prendre rendez-vous</a>
  </div>
</section>

</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
