<?php
declare(strict_types=1);
session_start();

if (!isset($_SESSION['user']['id'])) {
    header("Location: /rdv.php?msg=connexion_requise");
    exit;
}

$userEmail = htmlspecialchars($_SESSION['user']['email'] ?? '');
$userRole  = $_SESSION['user']['role'] ?? 'client';

$pageTitle = "Espace client sécurisé | IBIG IMMO TRUST";
$metaDescription = "Espace client IBIG IMMO TRUST : gestion immobilière sécurisée, suivi locatif, documents et assistance personnalisée.";
$currentPage = "espace-client";

require_once __DIR__ . "/includes/config.php";
require_once __DIR__ . "/includes/header.php";
?>

<style>
.ec-hero {
  background: linear-gradient(135deg, #060d1a 0%, #0A1628 60%, #0f2044 100%);
  padding: 70px 0 55px;
  position: relative; overflow: hidden;
}
.ec-hero::before {
  content:''; position:absolute; top:-80px; right:-80px;
  width:380px; height:380px; border-radius:50%;
  background:radial-gradient(circle,rgba(212,175,55,0.07) 0%,transparent 70%);
}
.ec-hero .kicker { font-size:11px; font-weight:800; letter-spacing:0.15em; text-transform:uppercase; color:#D4AF37; margin-bottom:14px; }
.ec-hero h1 { font-size:clamp(22px,3vw,38px); font-weight:900; color:#fff; margin:0 0 10px; letter-spacing:-0.02em; }
.ec-hero .desc { font-size:15px; color:rgba(255,255,255,0.65); max-width:600px; line-height:1.7; }
.ec-hero .user-badge {
  display:inline-flex; align-items:center; gap:8px;
  background:rgba(212,175,55,0.12); border:1px solid rgba(212,175,55,0.25);
  border-radius:999px; padding:8px 16px; margin-top:20px;
  font-size:13px; color:#D4AF37; font-weight:700;
}

.ec-section { padding:60px 0; background:#FAF7F0; }
.ec-grid {
  display:grid; grid-template-columns:repeat(auto-fit,minmax(260px,1fr)); gap:24px;
}
.ec-card {
  background:#fff; border-radius:16px; padding:28px;
  box-shadow:0 6px 24px rgba(10,22,40,0.07);
  border:1px solid rgba(10,22,40,0.06);
  border-top:3px solid #D4AF37;
  transition:all 0.3s ease;
}
.ec-card:hover { transform:translateY(-4px); box-shadow:0 16px 40px rgba(10,22,40,0.12); }
.ec-card .icon {
  width:46px; height:46px;
  background:linear-gradient(135deg,#D4AF37,#A68920);
  border-radius:12px;
  display:flex; align-items:center; justify-content:center;
  color:#0A1628; font-size:18px; margin-bottom:16px;
}
.ec-card h3 { font-size:16px; font-weight:800; color:#0A1628; margin-bottom:8px; }
.ec-card p { font-size:14px; color:#4B5563; line-height:1.7; margin-bottom:16px; }
.ec-card-badge {
  display:inline-block; padding:4px 12px; border-radius:999px;
  font-size:11px; font-weight:800; letter-spacing:0.05em; text-transform:uppercase;
  background:rgba(212,175,55,0.1); color:#A68920;
  margin-bottom:14px;
}
.ec-card-link {
  display:inline-flex; align-items:center; gap:6px;
  font-size:13px; font-weight:800; color:#0A1628; text-decoration:none;
  border-bottom:1.5px solid rgba(212,175,55,0.4); padding-bottom:2px;
  transition:all 0.2s;
}
.ec-card-link:hover { color:#A68920; border-color:#D4AF37; }
</style>

<section class="ec-hero">
  <div class="container">
    <p class="kicker">ESPACE CLIENT &bull; SÉCURISÉ</p>
    <h1>Bienvenue dans votre espace personnel</h1>
    <p class="desc">Gérez vos biens immobiliers, votre gestion locative et vos documents en toute transparence.</p>
    <div class="user-badge"><i class="fa-solid fa-circle-user"></i> <?= $userEmail ?></div>
  </div>
</section>

<section class="ec-section">
  <div class="container">
    <div class="ec-grid">

      <div class="ec-card">
        <span class="ec-card-badge">Immobilier</span>
        <div class="icon"><i class="fa-solid fa-building"></i></div>
        <h3>Mes biens immobiliers</h3>
        <p>Consultez la liste de vos biens gérés, en cours d'acquisition ou de construction, avec un suivi clair et structuré.</p>
        <a href="#" class="ec-card-link">Voir mes biens <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="ec-card">
        <span class="ec-card-badge">Gestion locative</span>
        <div class="icon"><i class="fa-solid fa-key"></i></div>
        <h3>Gestion locative</h3>
        <p>Suivez vos loyers, contrats de location, états des lieux, interventions de maintenance et performances locatives.</p>
        <a href="#" class="ec-card-link">Accéder <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="ec-card">
        <span class="ec-card-badge">Documents</span>
        <div class="icon"><i class="fa-solid fa-folder-open"></i></div>
        <h3>Documents & rapports</h3>
        <p>Accédez à vos documents fonciers, contrats, rapports de gestion et justificatifs en toute sécurité.</p>
        <a href="#" class="ec-card-link">Voir mes documents <i class="fa-solid fa-arrow-right"></i></a>
      </div>

      <div class="ec-card">
        <span class="ec-card-badge">Assistance</span>
        <div class="icon"><i class="fa-regular fa-calendar-check"></i></div>
        <h3>Rendez-vous & demandes</h3>
        <p>Planifiez un rendez-vous avec votre gestionnaire ou soumettez une demande spécifique à nos équipes.</p>
        <a href="<?= BASE_URL ?>/rdv.php" class="ec-card-link">Prendre RDV <i class="fa-solid fa-arrow-right"></i></a>
      </div>

    </div>
  </div>
</section>

<?php include __DIR__ . "/includes/footer.php"; ?>