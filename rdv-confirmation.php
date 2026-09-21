<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

$currentPage = 'rdv';
$pageTitle   = "Confirmation de rendez-vous – IBIG IMMO TRUST";

include __DIR__ . '/includes/header.php';
?>

<style>
.rdv-confirm-wrapper{
    max-width: 720px;
    margin: 50px auto 70px;
    padding: 30px 26px;
    background:#ffffff;
    border-radius:18px;
    border:1px solid #e5e7eb;
    box-shadow:0 8px 26px rgba(0,0,0,0.06);
    text-align:center;
}

.rdv-confirm-icon{
    width:70px;
    height:70px;
    border-radius:50%;
    background:#e6ffed;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 18px;
    color:#16a34a;
    font-size:32px;
}

.rdv-confirm-title{
    font-size:26px;
    font-weight:800;
    color:#1B4FD8;
    margin-bottom:8px;
}

.rdv-confirm-text{
    font-size:15px;
    color:#4b5563;
    margin-bottom:18px;
}

.rdv-confirm-highlight{
    font-weight:600;
    color:#111827;
}

.rdv-confirm-infos{
    margin-top:18px;
    font-size:14px;
    color:#6b7280;
    line-height:1.6;
}

.rdv-confirm-actions{
    margin-top:26px;
    display:flex;
    flex-wrap:wrap;
    justify-content:center;
    gap:12px;
}

.rdv-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    padding:10px 18px;
    border-radius:999px;
    font-size:14px;
    font-weight:600;
    text-decoration:none;
    border:1px solid transparent;
}

.rdv-btn-main{
    background:#1B4FD8;
    color:#fff;
}

.rdv-btn-main:hover{
    background:#012a66;
}

.rdv-btn-ghost{
    background:#ffffff;
    color:#1B4FD8;
    border-color:#cbd5f5;
}

.rdv-btn-ghost:hover{
    background:#f3f4ff;
}

.rdv-btn-wa{
    background:#25D366;
    color:#fff;
}

.rdv-btn-wa:hover{
    background:#1ebe5d;
}

@media(max-width:640px){
    .rdv-confirm-wrapper{
        margin:30px 14px 60px;
        padding:24px 18px;
    }
}
</style>

<section>
  <div class="container">
    <div class="rdv-confirm-wrapper">

      <div class="rdv-confirm-icon">
        <i class="fa-solid fa-check"></i>
      </div>

      <h1 class="rdv-confirm-title">Votre demande de rendez-vous a bien été envoyée ✅</h1>

      <p class="rdv-confirm-text">
        Merci de votre confiance. Un <span class="rdv-confirm-highlight">conseiller IBIG IMMO TRUST</span>
        vous contactera dans les prochaines <span class="rdv-confirm-highlight">24 heures</span>
        pour confirmer le rendez-vous et préciser les modalités.
      </p>

      <div class="rdv-confirm-infos">
        <p>
          Pour toute urgence, vous pouvez nous joindre directement au :<br>
          <strong><a href="tel:+2250778882592" style="color:#1B4FD8;text-decoration:none;">
            +225 07 78 88 25 92
          </a></strong>
          ou par WhatsApp.
        </p>
      </div>

      <div class="rdv-confirm-actions">
        <a href="https://wa.me/2250778882592?text=Bonjour%20IBIG%20IMMO%20TRUST%2C%20je%20viens%20de%20faire%20une%20demande%20de%20rendez-vous%20sur%20votre%20site."
           target="_blank"
           class="rdv-btn rdv-btn-wa">
          <i class="fa-brands fa-whatsapp"></i>
          Continuer sur WhatsApp
        </a>

        <a href="tous_les_biens.php" class="rdv-btn rdv-btn-main">
          Voir les biens disponibles
        </a>

        <a href="index.php" class="rdv-btn rdv-btn-ghost">
          Retour à l’accueil
        </a>
      </div>

    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
