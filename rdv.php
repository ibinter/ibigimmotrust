<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'rdv';
$pageTitle   = "Prendre rendez-vous – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO SIMPLE & PROPRE ===================== -->
<section class="hero-home" style="padding:60px 0;">
  <div class="container">

    <h1 class="hero-title" style="text-align:center; font-size:30px;">
      Prenez un rendez-vous avec un expert IBIG IMMO TRUST
    </h1>

    <p class="hero-subtitle" style="text-align:center; max-width:700px; margin:0 auto;">
      Que ce soit pour l’immobilier, la construction, la rénovation ou le financement,
      nos conseillers vous recontactent sous 24h.
    </p>

    <div class="hero-img-main" style="margin-top:25px;">
      <img src="assets/img/financement-ibig.jpg" alt="Rendez-vous IBIG IMMO TRUST"
           style="width:100%; border-radius:18px;">
    </div>

  </div>
</section>


<!-- ===================== FORMULAIRE PREMIUM CLEAN ===================== -->
<section class="section">
  <div class="container">

    <!-- TITRE CENTRÉ 100% -->
    <div style="
      width:100%;
      display:flex;
      flex-direction:column;
      justify-content:center;
      align-items:center;
      text-align:center;
      margin-bottom:25px;
    ">

      <h2 class="section-title" style="
        text-align:center !important;
        margin:0 0 5px 0;
        width:100%;
      ">
        Formulaire de prise de rendez-vous
      </h2>

      <p style="
        text-align:center !important;
        margin:0 auto;
        max-width:650px;
        font-size:15px;
        color:#6b7280;
      ">
        Remplissez le formulaire ci-dessous. Un expert vous rappellera rapidement.
      </p>

    </div>

    <?php if (!empty($_GET['success'])): ?>
      <div class="alert success">Votre demande a été envoyée avec succès.</div>
    <?php endif; ?>

    <form action="rdv_process.php" method="post" class="contact-form" style="
      background:#fff;
      padding:25px;
      border-radius:18px;
      border:1px solid #e5e7eb;
      max-width:700px;
      margin:auto;
      box-shadow:0 3px 10px rgba(0,0,0,0.06);
    ">

      <div class="form-row">
        <label>Nom complet *</label>
        <input type="text" name="name" required>
      </div>

      <div class="form-row">
        <label>Téléphone *</label>
        <input type="text" name="phone" required>
      </div>

      <div class="form-row">
        <label>Email (optionnel)</label>
        <input type="email" name="email">
      </div>

      <div class="form-row">
        <label>Objet du rendez-vous *</label>
        <select name="type" required>
          <option value="">-- Sélectionnez --</option>
          <option value="Immobilier">Immobilier</option>
          <option value="Construction">Construction</option>
          <option value="Chantier inachevé">Chantier inachevé</option>
          <option value="Rénovation">Rénovation</option>
          <option value="Financement">Financement</option>
          <option value="Assistance foncière">Assistance foncière</option>
        </select>
      </div>

      <div class="form-row">
        <label>Date souhaitée *</label>
        <input type="date" name="date_rdv" required>
      </div>

      <div class="form-row">
        <label>Heure souhaitée *</label>
        <input type="time" name="time_rdv" required>
      </div>

      <div class="form-row">
        <label>Votre message (optionnel)</label>
        <textarea name="message" rows="4"></textarea>
      </div>

      <button type="submit" class="btn-primary btn-large" style="width:100%; margin-top:10px;">
        Envoyer ma demande de rendez-vous
      </button>

    </form>

  </div>
</section>


<!-- ===================== BOUTON WHATSAPP FLOTTANT ===================== -->
<a href="https://wa.me/2250778882592" 
   target="_blank" 
   style="
     position:fixed;
     bottom:25px;
     right:25px;
     background:#25d366;
     color:#fff;
     padding:14px 20px;
     border-radius:50px;
     font-size:14px;
     box-shadow:0 4px 12px rgba(0,0,0,0.20);
     z-index:999;
     font-weight:600;
     text-decoration:none;
   ">
  ð¬ Besoin d’aide ? WhatsApp
</a>

<?php include __DIR__ . '/includes/footer.php'; ?>
