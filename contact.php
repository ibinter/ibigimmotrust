<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';
$currentPage = 'contact';
$pageTitle   = "Contactez-nous – IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="hero-home" style="padding:60px 0;">
  <div class="container hero-grid">

    <div>
      <p class="hero-kicker">Contact • Assistance • Conseils</p>

      <h1 class="hero-title">
        Besoin d’un accompagnement ? Une question ? Un projet immobilier ?
      </h1>

      <p class="hero-subtitle">
        Notre équipe vous répond rapidement pour vous orienter dans vos projets
        immobiliers, BTP, financement ou gestion locative.
      </p>
    </div>

    <div class="hero-image">
      <div class="hero-img-main">
        <img src="<?php echo BASE_URL; ?>/assets/img/hero-immo-afrique.jpg" alt="Contact IBIG IMMO TRUST">
      </div>
    </div>

  </div>
</section>


<!-- ===================== CONTACT FORM ===================== -->
<section class="section">
  <div class="container">

    <!-- TITRE CENTRÉ -->
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
        margin-bottom:5px;
        text-align:center !important;
      ">
        Envoyez-nous un message
      </h2>

      <p style="
        text-align:center !important;
        margin:0 auto;
        max-width:650px;
        font-size:15px;
        color:#6b7280;
      ">
        Remplissez le formulaire ci-dessous. Un conseiller vous contactera très rapidement.
      </p>
    </div>

    <?php if (!empty($_GET['success'])): ?>
      <div class="alert success">Votre message a bien été envoyé. Nous vous contacterons très bientôt.</div>
    <?php endif; ?>

    <?php if (!empty($_GET['error'])): ?>
      <div class="alert error">Erreur lors de l’envoi du message. Merci de réessayer.</div>
    <?php endif; ?>

    <form class="contact-form" action="process_contact.php" method="POST" style="
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
        <label>Numéro de téléphone *</label>
        <input type="text" name="phone" required>
      </div>

      <div class="form-row">
        <label>Email (optionnel)</label>
        <input type="email" name="email">
      </div>

      <div class="form-row">
        <label>Objet *</label>
        <input type="text" name="subject" required>
      </div>

      <div class="form-row">
        <label>Votre message *</label>
        <textarea name="message" rows="5" required></textarea>
      </div>

      <button type="submit" class="btn-primary btn-large" style="width:100%;">
        Envoyer le message
      </button>

    </form>

  </div>
</section>


<!-- ===================== CONTACT INFOS ===================== -->
<section class="section section-alt">
  <div class="container contact-grid">

    <!-- INFOS -->
    <div>
      <h2 class="section-title">Nos coordonnées</h2>

      <ul class="contact-infos">
        <li><strong>Adresse :</strong> INTERMARK BUSINESS INTERNATIONAL GROUP SARL, Cocody – Riviera Palmeraie, Abidjan</li>
        <li><strong>Téléphone :</strong> 27 22 27 60 14 / 07 78 88 25 92</li>
        <li><strong>Email :</strong> info@ibigimmotrust.com</li>
        <li><strong>Zone d’intervention :</strong> Côte d’Ivoire & Diaspora (Europe, USA, Canada)</li>
      </ul>

      <h4 style="margin-top:20px; color:var(--blue);">Réseaux sociaux</h4>
      <ul class="contact-infos">
        <li>Facebook</li>
        <li>Instagram</li>
        <li>LinkedIn</li>
      </ul>
    </div>

    <!-- MAP -->
    <div>
      <h2 class="section-title">Localisation</h2>
      <p class="section-intro">
        Nous sommes situés à Cocody – Riviera Palmeraie (siège social IBIG GROUP).
      </p>

      <iframe
        src="https://www.google.com/maps?q=Cocody%20Riviera%20Palmeraie&output=embed"
        width="100%" height="300"
        style="border:0; border-radius:18px;" allowfullscreen>
      </iframe>
    </div>

  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>