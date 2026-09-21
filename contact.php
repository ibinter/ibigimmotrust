<?php
declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/tracker.php';

$currentPage = 'contact';
$pageTitle   = "Contactez-nous – IBIG IMMO TRUST";

include __DIR__ . '/includes/header.php';
?>

<style>
/* ===== HERO CONTACT ===== */
.hero-contact {
  background: linear-gradient(135deg, #060d1a 0%, #0A1628 60%, #0f2044 100%);
  padding: 80px 0 60px;
  position: relative;
  overflow: hidden;
}
.hero-contact::before {
  content: '';
  position: absolute;
  top: -80px; right: -80px;
  width: 400px; height: 400px;
  border-radius: 50%;
  background: radial-gradient(circle, rgba(212,175,55,0.07) 0%, transparent 70%);
  pointer-events: none;
}
.hero-contact .hero-kicker {
  font-size: 11px;
  font-weight: 800;
  letter-spacing: 0.15em;
  text-transform: uppercase;
  color: #D4AF37;
  margin-bottom: 18px;
}
.hero-contact h1 {
  font-size: clamp(26px, 3.5vw, 46px);
  font-weight: 900;
  color: #fff;
  margin: 0 0 16px;
  letter-spacing: -0.02em;
  line-height: 1.15;
}
.hero-contact h1 span { color: #D4AF37; }
.hero-contact .hero-desc {
  font-size: 16px;
  color: rgba(255,255,255,0.65);
  max-width: 600px;
  line-height: 1.75;
  margin: 0;
}
.hero-contact-badges {
  display: flex;
  gap: 20px;
  margin-top: 32px;
  flex-wrap: wrap;
}
.hero-contact-badges .badge-item {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 13px;
  font-weight: 600;
  color: rgba(255,255,255,0.65);
}
.hero-contact-badges .badge-item i { color: #D4AF37; font-size: 14px; }

/* ===== SECTION CONTACT MAIN ===== */
.contact-main {
  padding: 70px 0;
  background: #FAF7F0;
}
.contact-layout {
  display: grid;
  grid-template-columns: 1.1fr 0.9fr;
  gap: 50px;
  align-items: start;
}
@media(max-width: 900px) {
  .contact-layout { grid-template-columns: 1fr; }
}

/* ===== FORMULAIRE ===== */
.contact-form-card {
  background: #fff;
  border-radius: 18px;
  padding: 40px;
  box-shadow: 0 8px 40px rgba(10,22,40,0.10);
  border: 1px solid rgba(10,22,40,0.06);
}
.contact-form-card h2 {
  font-size: 22px;
  font-weight: 800;
  color: #0A1628;
  margin: 0 0 6px;
}
.contact-form-card .form-sub {
  font-size: 14px;
  color: #6B7280;
  margin-bottom: 28px;
}
.form-row {
  margin-bottom: 18px;
}
.form-row label {
  display: block;
  font-size: 13px;
  font-weight: 700;
  color: #0A1628;
  margin-bottom: 6px;
  letter-spacing: 0.02em;
}
.form-row input,
.form-row select,
.form-row textarea {
  width: 100%;
  padding: 12px 16px;
  border: 1.5px solid #E5E7EB;
  border-radius: 10px;
  font-size: 14px;
  color: #111827;
  background: #fff;
  transition: all 0.25s ease;
  outline: none;
  font-family: inherit;
  box-sizing: border-box;
}
.form-row input:focus,
.form-row select:focus,
.form-row textarea:focus {
  border-color: #A68920;
  box-shadow: 0 0 0 3px rgba(212,175,55,0.12);
}
.form-row textarea { min-height: 120px; resize: vertical; }
.form-row-2col {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}
@media(max-width:600px) { .form-row-2col { grid-template-columns: 1fr; } }
.btn-contact-submit {
  width: 100%;
  padding: 15px 30px;
  background: linear-gradient(135deg, #D4AF37, #A68920);
  color: #0A1628;
  border: none;
  border-radius: 999px;
  font-size: 15px;
  font-weight: 800;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  letter-spacing: 0.02em;
  margin-top: 10px;
}
.btn-contact-submit:hover {
  box-shadow: 0 8px 25px rgba(212,175,55,0.45);
  transform: translateY(-2px);
}
.alert {
  padding: 14px 20px;
  border-radius: 10px;
  margin-bottom: 20px;
  font-size: 14px;
  font-weight: 600;
}
.alert.success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
.alert.error   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }

/* ===== INFOS CONTACT (droite) ===== */
.contact-info-col { display: flex; flex-direction: column; gap: 20px; }

.contact-info-card {
  background: #fff;
  border-radius: 16px;
  padding: 28px;
  box-shadow: 0 4px 20px rgba(10,22,40,0.07);
  border: 1px solid rgba(10,22,40,0.06);
}
.contact-info-card h3 {
  font-size: 15px;
  font-weight: 800;
  color: #0A1628;
  margin: 0 0 18px;
  display: flex;
  align-items: center;
  gap: 10px;
}
.contact-info-card h3 i {
  width: 34px; height: 34px;
  background: linear-gradient(135deg, #D4AF37, #A68920);
  color: #0A1628;
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 13px;
  flex-shrink: 0;
}
.contact-detail-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px; }
.contact-detail-list li {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  font-size: 14px;
  color: #374151;
  line-height: 1.5;
}
.contact-detail-list li .icon {
  width: 30px; height: 30px;
  background: rgba(212,175,55,0.1);
  border-radius: 7px;
  display: flex; align-items: center; justify-content: center;
  color: #A68920;
  font-size: 12px;
  flex-shrink: 0;
  margin-top: 1px;
}
.contact-detail-list li a { color: #0A1628; font-weight: 600; text-decoration: none; }
.contact-detail-list li a:hover { color: #D4AF37; }

/* Horaires */
.horaires-grid { display: flex; flex-direction: column; gap: 8px; }
.horaire-row {
  display: flex;
  justify-content: space-between;
  font-size: 13px;
  padding: 8px 12px;
  border-radius: 8px;
  background: #F8F6F0;
}
.horaire-row .jour { font-weight: 700; color: #0A1628; }
.horaire-row .heure { color: #6B7280; }
.horaire-row.open .heure { color: #059669; font-weight: 700; }

/* WhatsApp CTA */
.whatsapp-cta {
  display: flex;
  align-items: center;
  gap: 12px;
  background: linear-gradient(135deg, #0A1628, #0f2044);
  border-radius: 16px;
  padding: 24px;
  text-decoration: none;
  transition: all 0.3s ease;
  border: 1px solid rgba(212,175,55,0.15);
}
.whatsapp-cta:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(10,22,40,0.2); }
.whatsapp-cta .wa-icon {
  width: 50px; height: 50px;
  background: #25D366;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  font-size: 22px;
  color: #fff;
  flex-shrink: 0;
}
.whatsapp-cta .wa-text strong {
  display: block;
  font-size: 15px;
  font-weight: 800;
  color: #fff;
  margin-bottom: 3px;
}
.whatsapp-cta .wa-text span {
  font-size: 13px;
  color: rgba(255,255,255,0.55);
}

/* ===== MAP SECTION ===== */
.contact-map-section {
  padding: 0 0 70px;
  background: #FAF7F0;
}
.map-wrapper {
  border-radius: 18px;
  overflow: hidden;
  box-shadow: 0 8px 40px rgba(10,22,40,0.12);
  border: 1px solid rgba(10,22,40,0.06);
  height: 350px;
}
.map-wrapper iframe { width: 100%; height: 100%; border: none; display: block; }
</style>

<!-- ===================== HERO ===================== -->
<section class="hero-contact">
  <div class="container">
    <p class="hero-kicker">CONTACT &bull; ASSISTANCE &bull; CONSEILS</p>
    <h1>Besoin d'un <span>accompagnement</span>&nbsp;?</h1>
    <p class="hero-desc">
      Notre équipe vous répond rapidement pour vous orienter dans vos projets
      immobiliers, BTP, financement ou gestion locative.
    </p>
    <div class="hero-contact-badges">
      <div class="badge-item"><i class="fa-solid fa-clock"></i> Réponse sous 24h</div>
      <div class="badge-item"><i class="fa-solid fa-shield-halved"></i> Échange confidentiel</div>
      <div class="badge-item"><i class="fa-solid fa-globe-africa"></i> Service Diaspora disponible</div>
    </div>
  </div>
</section>

<!-- ===================== FORMULAIRE + INFOS ===================== -->
<section class="contact-main">
  <div class="container">
    <div class="contact-layout">

      <!-- FORMULAIRE -->
      <div class="contact-form-card">
        <h2>Envoyez-nous un message</h2>
        <p class="form-sub">Décrivez votre besoin, nous vous rappelons rapidement.</p>

        <?php if (!empty($_GET['success'])): ?>
          <div class="alert success">
            &#10004; Votre message a bien été envoyé. Nous vous contacterons rapidement.
          </div>
        <?php endif; ?>
        <?php if (!empty($_GET['error'])): ?>
          <div class="alert error">
            &#10006; Une erreur est survenue. Merci de réessayer.
          </div>
        <?php endif; ?>

        <form action="send_email.php" method="POST">
          <div class="form-row-2col">
            <div class="form-row">
              <label for="name">Nom complet *</label>
              <input type="text" id="name" name="name" placeholder="Jean Kouassi" required>
            </div>
            <div class="form-row">
              <label for="phone">Téléphone *</label>
              <input type="tel" id="phone" name="phone" placeholder="+225 07 00 00 00 00" required>
            </div>
          </div>

          <div class="form-row">
            <label for="email">Email (optionnel)</label>
            <input type="email" id="email" name="email" placeholder="votre@email.com">
          </div>

          <div class="form-row">
            <label for="subject">Objet *</label>
            <select id="subject" name="subject" required>
              <option value="">Choisir un sujet...</option>
              <option>Achat / Vente immobilière</option>
              <option>Location d'un bien</option>
              <option>Gestion locative</option>
              <option>Projet BTP / Construction</option>
              <option>Financement immobilier</option>
              <option>Espace Diaspora</option>
              <option>Autre demande</option>
            </select>
          </div>

          <div class="form-row">
            <label for="message">Votre message *</label>
            <textarea id="message" name="message" rows="5" placeholder="Décrivez votre projet ou votre demande..." required></textarea>
          </div>

          <button type="submit" class="btn-contact-submit">
            <i class="fa-solid fa-paper-plane"></i> Envoyer le message
          </button>
        </form>
      </div>

      <!-- INFOS CONTACT -->
      <div class="contact-info-col">

        <!-- Coordonnées -->
        <div class="contact-info-card">
          <h3><i class="fa-solid fa-location-dot"></i> Nos coordonnées</h3>
          <ul class="contact-detail-list">
            <li>
              <div class="icon"><i class="fa-solid fa-map-pin"></i></div>
              <span>Cocody Riviera Palmeraie, Abidjan, Côte d'Ivoire</span>
            </li>
            <li>
              <div class="icon"><i class="fa-solid fa-phone"></i></div>
              <div>
                <a href="tel:+2252722276014">+225 27 22 27 60 14</a><br>
                <a href="tel:+2250584437474">+225 05 84 43 74 74</a><br>
                <a href="tel:+2250153595544">+225 01 53 59 55 44</a>
              </div>
            </li>
            <li>
              <div class="icon"><i class="fa-solid fa-envelope"></i></div>
              <a href="mailto:contact@ibigimmotrust.com">contact@ibigimmotrust.com</a>
            </li>
          </ul>
        </div>

        <!-- Horaires -->
        <div class="contact-info-card">
          <h3><i class="fa-solid fa-clock"></i> Horaires d'ouverture</h3>
          <div class="horaires-grid">
            <div class="horaire-row open">
              <span class="jour">Lundi – Vendredi</span>
              <span class="heure">08h00 – 18h00</span>
            </div>
            <div class="horaire-row open">
              <span class="jour">Samedi</span>
              <span class="heure">09h00 – 14h00</span>
            </div>
            <div class="horaire-row">
              <span class="jour">Dimanche</span>
              <span class="heure">Sur RDV uniquement</span>
            </div>
          </div>
        </div>

        <!-- WhatsApp -->
        <a href="https://wa.me/2250584437474?text=Bonjour%20IBIG%20IMMO%20TRUST%2C%20je%20souhaite%20des%20informations." class="whatsapp-cta" target="_blank" rel="noopener">
          <div class="wa-icon"><i class="fab fa-whatsapp"></i></div>
          <div class="wa-text">
            <strong>Discuter sur WhatsApp</strong>
            <span>Réponse rapide garantie</span>
          </div>
        </a>

      </div>
    </div>
  </div>
</section>

<!-- ===================== MAP ===================== -->
<section class="contact-map-section">
  <div class="container">
    <div class="map-wrapper">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.0!2d-3.9417!3d5.3600!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMjEnMzYuMCJOIDPCsDU2JzMwLjAiVw!5e0!3m2!1sfr!2sci!4v1694000000000!5m2!1sfr!2sci"
        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
        title="IBIG IMMO TRUST - Cocody Riviera Palmeraie Abidjan">
      </iframe>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>