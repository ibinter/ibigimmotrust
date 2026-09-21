<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

$currentPage = 'rdv';
$pageTitle   = "Prendre rendez-vous – IBIG IMMO TRUST";

// Pré-remplir si on vient d'un bien
$bienId      = isset($_GET['bien']) ? (int)$_GET['bien'] : 0;
$bienLabel   = "";
if ($bienId > 0) {
    $stBien = $pdo->prepare("SELECT id, titre, ville, quartier FROM immo_biens WHERE id = ? AND visible = 1");
    $stBien->execute([$bienId]);
    if ($b = $stBien->fetch(PDO::FETCH_ASSOC)) {
        $bienLabel = $b['titre'] . " (" . trim(($b['ville'] ?? '') . ' ' . ($b['quartier'] ?? '')) . ")";
    }
}

include __DIR__ . '/includes/header.php';
?>

<style>
/* ======= SECTION HERO SIMPLE ======= */
.rdv-hero {
    padding: 50px 0 30px 0;
}
.rdv-hero-title {
    font-size: 30px;
    font-weight: 800;
    text-align: center;
    color: #0A1628;
    margin-bottom: 10px;
}
.rdv-hero-subtitle {
    text-align: center;
    max-width: 680px;
    margin: 0 auto;
    font-size: 15px;
    color: #6b7280;
}
.rdv-hero img {
    width: 100%;
    border-radius: 18px;
    margin-top: 25px;
}

/* ======= FORM PREMIUM ======= */
.rdv-form-wrapper {
    max-width: 900px;
    margin: 0 auto 60px auto;
}
.rdv-form-card {
    background: #ffffff;
    border-radius: 18px;
    border: 1px solid #e5e7eb;
    padding: 25px 25px 28px 25px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
}
.rdv-form-title {
    font-size: 22px;
    font-weight: 700;
    margin-bottom: 6px;
    text-align: center;
    color: #0f2044;
}
.rdv-form-subtitle {
    font-size: 14px;
    color: #6b7280;
    text-align: center;
    margin-bottom: 18px;
}

/* GRID 2 COLS SUR DESKTOP */
.rdv-grid-2 {
    display: grid;
    grid-template-columns: repeat(2, minmax(0,1fr));
    gap: 16px 18px;
}
.rdv-grid-1 {
    display: grid;
    grid-template-columns: minmax(0,1fr);
    gap: 14px;
}

@media (max-width: 768px) {
    .rdv-grid-2 {
        grid-template-columns: minmax(0,1fr);
    }
}

/* LIGNES DE FORMULAIRE */
.rdv-field label {
    display: block;
    font-size: 14px;
    margin-bottom: 4px;
    font-weight: 600;
    color: #374151;
}
.rdv-field input,
.rdv-field select,
.rdv-field textarea {
    width: 100%;
    padding: 11px 12px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    background: #f9fafb;
    font-size: 14px;
    color: #111827;
    transition: 0.2s;
}
.rdv-field textarea {
    min-height: 90px;
    resize: vertical;
}
.rdv-field input:focus,
.rdv-field select:focus,
.rdv-field textarea:focus {
    border-color: #0A1628;
    box-shadow: 0 0 0 2px rgba(10,22,40,0.15);
    background: #ffffff;
    outline: none;
}

/* BADGE BIEN SELECTIONNÉ */
.rdv-bien-box {
    margin-top: 12px;
    padding: 10px 12px;
    border-radius: 10px;
    background: #f1f5f9;
    font-size: 13px;
    color: #1f2933;
}
.rdv-bien-box strong {
    color: #0A1628;
}

/* RADIO / SELECT INLINE */
.rdv-inline-group {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
    font-size: 14px;
}
.rdv-inline-group label {
    font-weight: 500;
}
.rdv-pill {
    padding: 7px 12px;
    border-radius: 999px;
    border: 1px solid #d1d5db;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.rdv-pill input {
    width: auto;
    margin: 0;
}

/* BOUTON ENVOI */
.rdv-submit {
    margin-top: 18px;
}
.rdv-submit button {
    width: 100%;
    padding: 13px 18px;
    border-radius: 999px;
    border: none;
    background: #0A1628;
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}
.rdv-submit button:hover {
    background: #060d1a;
}

/* ALERTES */
.rdv-alert-success {
    background: #ecfdf3;
    border: 1px solid #4ade80;
    color: #166534;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 15px;
}
.rdv-alert-error {
    background: #fef2f2;
    border: 1px solid #fca5a5;
    color: #A68920;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 15px;
}
</style>

<!-- ===================== HERO SIMPLE ===================== -->
<section class="rdv-hero">
  <div class="container">
    <h1 class="rdv-hero-title">
      Prenez rendez-vous avec un expert IBIG IMMO TRUST
    </h1>
    <p class="rdv-hero-subtitle">
      Immobilier, BTP, chantiers inachevés, rénovation, gestion locative, financement : 
      nos conseillers vous rappellent dans les meilleurs délais pour étudier votre projet.
    </p>

  </div>
</section>

<!-- ===================== FORMULAIRE PREMIUM ===================== -->
<section class="section">
  <div class="container rdv-form-wrapper">

    <div class="rdv-form-card">

      <h2 class="rdv-form-title">Formulaire de prise de rendez-vous</h2>
      <p class="rdv-form-subtitle">
        Remplissez les informations ci-dessous. Nous vous contactons pour confirmer la date et l’heure.
      </p>

      <?php if (!empty($_GET['success']) && $_GET['success'] == 1): ?>
        <div class="rdv-alert-success">
           Votre demande de rendez-vous a été envoyée. Nous vous contacterons très prochainement.
        </div>
      <?php elseif (!empty($_GET['error'])): ?>
        <div class="rdv-alert-error">
           Une erreur est survenue. Merci de réessayer ou de nous contacter par téléphone.
        </div>
      <?php endif; ?>

      <form action="rdv_process.php" method="post">

        <!-- INFOS PRINCIPALES -->
        <div class="rdv-grid-2">

          <div class="rdv-field">
            <label>Nom complet *</label>
            <input type="text" name="name" required>
          </div>

          <div class="rdv-field">
            <label>Téléphone (WhatsApp de préférence) *</label>
            <input type="text" name="phone" required>
          </div>

          <div class="rdv-field">
            <label>Email (optionnel)</label>
            <input type="email" name="email">
          </div>

          <div class="rdv-field">
            <label>Objet du rendez-vous *</label>
            <select name="type" required>
              <option value="">-- Sélectionnez --</option>
              <option value="Immobilier">Immobilier (achat / vente)</option>
              <option value="Gestion locative">Gestion locative</option>
              <option value="Construction">Construction clé en main</option>
              <option value="Chantier inachevé">Finition de chantier inachevé</option>
              <option value="Rénovation">Rénovation / réaménagement</option>
              <option value="Financement">Financement immobilier</option>
              <option value="Assistance foncière">Assistance foncière / sécurisation</option>
              <option value="Visite d’un bien">Visite d’un bien précis</option>
              <option value="Autre">Autre projet immobilier / BTP</option>
            </select>
          </div>

        </div>

        <!-- FINALITÉ + BUDGET + LOCALISATION -->
        <div class="rdv-grid-2" style="margin-top:16px;">

          <div class="rdv-field">
            <label>Finalité du projet *</label>
            <input type="text" name="finalite" placeholder="Ex : investissement locatif, résidence principale..." required>
          </div>

          <div class="rdv-field">
            <label>Budget estimatif (optionnel)</label>
            <input type="text" name="budget" placeholder="Ex : 30 à 60 millions, 500 000 F/mois...">
          </div>

          <div class="rdv-field">
            <label>Ville du projet *</label>
            <input type="text" name="ville" placeholder="Ex : Abidjan, Bouaké..." required>
          </div>

          <div class="rdv-field">
            <label>Quartier / zone souhaitée (optionnel)</label>
            <input type="text" name="quartier" placeholder="Ex : Cocody, Yopougon, Riviera...">
          </div>

        </div>

        <!-- DATE / HEURE / PREFERENCE CONTACT / URGENCE -->
        <div class="rdv-grid-2" style="margin-top:16px;">

          <div class="rdv-field">
            <label>Date souhaitée *</label>
            <input type="date" name="date_rdv" required>
          </div>

          <div class="rdv-field">
            <label>Heure souhaitée *</label>
            <input type="time" name="time_rdv" required>
          </div>

          <div class="rdv-field">
            <label>Préférence de contact *</label>
            <div class="rdv-inline-group">
              <label class="rdv-pill">
                <input type="radio" name="preference_contact" value="appel" checked>
                Appel téléphonique
              </label>
              <label class="rdv-pill">
                <input type="radio" name="preference_contact" value="whatsapp">
                WhatsApp
              </label>
            </div>
          </div>

          <div class="rdv-field">
            <label>Niveau d’urgence *</label>
            <div class="rdv-inline-group">
              <label class="rdv-pill">
                <input type="radio" name="urgence" value="normal" checked>
                Normal
              </label>
              <label class="rdv-pill">
                <input type="radio" name="urgence" value="urgent">
                Urgent (&lt; 24h si possible)
              </label>
            </div>
          </div>

        </div>

        <!-- MESSAGE -->
        <div class="rdv-grid-1" style="margin-top:16px;">
          <div class="rdv-field">
            <label>Votre message / détails complémentaires</label>
            <textarea name="message" placeholder="Expliquez votre projet, vos attentes, vos contraintes..."></textarea>
          </div>
        </div>

        <!-- BIEN LIE (SI PRESENT) -->
        <?php if ($bienId > 0 && $bienLabel): ?>
          <div class="rdv-bien-box">
            <strong>Bien sélectionné :</strong><br>
            <?= htmlspecialchars($bienLabel) ?><br>
            <small>Votre demande de rendez-vous sera liée à ce bien.</small>
          </div>
          <input type="hidden" name="bien_id" value="<?= $bienId ?>">
        <?php else: ?>
          <input type="hidden" name="bien_id" value="">
        <?php endif; ?>

        <!-- PROVENANCE -->
        <input type="hidden" name="provenance" value="site_rdv">

        <!-- SUBMIT -->
        <div class="rdv-submit">
          <button type="submit">
            Envoyer ma demande de rendez-vous
          </button>
        </div>

      </form>

    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
