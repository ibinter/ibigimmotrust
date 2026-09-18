</main>

<footer class="footer-premium">

  <div class="container footer-grid">

    <!-- COL 1 – PRESENTATION -->
    <div class="footer-col">
      <h3>IBIG IMMO TRUST</h3>
      <p>
        Branche Immobilière & BTP de INTERMARK BUSINESS INTERNATIONAL GROUP SARL.
      </p>
      <p>
        Expertise : gestion locative, chantiers inachevés, construction, rénovation,
        assistance foncière et financement immobilier.
      </p>
    </div>

    <!-- COL 2 – SERVICES -->
    <div class="footer-col">
      <h4>Services</h4>
      <ul>
        <li><a href="<?php echo BASE_URL; ?>/immobilier.php">Immobilier</a></li>
        <li><a href="<?php echo BASE_URL; ?>/btp.php">BTP</a></li>
        <li><a href="<?php echo BASE_URL; ?>/financement.php">Financement</a></li>
        <li><a href="<?php echo BASE_URL; ?>/faq.php">FAQ</a></li>
        <li><a href="<?php echo BASE_URL; ?>/contact.php">Contact</a></li>
      </ul>
    </div>

    <!-- COL 3 – CONTACTS -->
    <div class="footer-col">
      <h4>Contacts</h4>

      <p> Cocody Riviera Palmeraie – Abidjan</p>

      <p>
         <a href="tel:+2252722276014">27 22 27 60 14</a><br>
         <a href="tel:+2250778882592">07 78 88 25 92</a>
      </p>

      <p><a href="mailto:info@ibigimmotrust.com">info@ibigimmotrust.com</a></p>
    </div>

    <!-- COL 4 – RESEAUX -->
    <div class="footer-col">
      <h4>Suivez-nous</h4>
      <div class="social-links">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-instinstagram"></i></a>
        <a href="#"><i class="fab fa-linkedin"></i></a>
      </div>
    </div>

  </div>

  <div class="footer-bottom">
    © <?php echo date('Y'); ?> IBIG IMMO TRUST – Propulsé par INTERMARK BUSINESS INTERNATIONAL GROUP SARL (IBIG SARL) – Tous droits réservés.
  </div>
</footer>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<script>
document.getElementById('burger-btn')?.addEventListener('click', function() {
    document.querySelector('.nav-premium').classList.toggle('open');
});
</script>

<!-- Bouton WhatsApp + Bulle d’aide -->
<div class="whatsapp-help-box">
    <div class="whatsapp-help-text">Besoin d’aide ?</div>
    <a href="https://wa.me/2250778882592?text=Bonjour%20IBIG%20IMMO%20TRUST%2C%20j'ai%20besoin%20d'assistance."
       class="whatsapp-float" target="_blank">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
</div>

<!-- SCRIPT TRACKING DE BASE -->
<script>
function trackEvent(event_name, event_value = "") {
    fetch("/api/event.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ event: event_name, value: event_value })
    });
}
</script>

<!-- 🎯 TRACKING AUTOMATIQUE COMPLET -->
<script>
// TRACKING AUTOMATIQUE IBIG IMMO TRUST

function trackEventAuto(event_name, event_value = "") {
    fetch("/api/event.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ event: event_name, value: event_value })
    });
}

document.addEventListener("DOMContentLoaded", function() {

    // WhatsApp
    document.querySelectorAll("a[href*='wa.me'], a[href*='whatsapp']").forEach(el => {
        el.addEventListener("click", () => trackEventAuto("click_whatsapp", el.href));
    });

    // Téléphone
    document.querySelectorAll("a[href^='tel:']").forEach(el => {
        el.addEventListener("click", () => trackEventAuto("click_telephone", el.href.replace("tel:", "")));
    });

    // Email
    document.querySelectorAll("a[href^='mailto:']").forEach(el => {
        el.addEventListener("click", () => trackEventAuto("click_email", el.href.replace("mailto:", "")));
    });

    // CTA
    document.querySelectorAll(".btn-primary, .btn-secondary").forEach(el => {
        el.addEventListener("click", () => trackEventAuto("click_bouton", el.textContent.trim()));
    });

    // Biens
    document.querySelectorAll("a[href*='bien.php'], a[href*='?id='], a[href*='?slug=']").forEach(el => {
        el.addEventListener("click", () => trackEventAuto("voir_bien", el.href));
    });

    // Liens externes
    document.querySelectorAll("a[href^='http']").forEach(el => {
        if (!el.href.includes(location.hostname)) {
            el.addEventListener("click", () => trackEventAuto("clic_lien_externe", el.href));
        }
    });

});
</script>

<!-- 🕒 TRACKING TEMPS DE VISITE -->
<script>
// TRACKING TEMPS PAR PAGE

let pageStartTime = Date.now();

window.addEventListener("beforeunload", function () {
    let timeSpent = Math.round((Date.now() - pageStartTime) / 1000); 
    
    navigator.sendBeacon("/api/visit_time.php", JSON.stringify({
        page: window.location.pathname,
        time_spent: timeSpent
    }));
});
</script>

</body>
</html>