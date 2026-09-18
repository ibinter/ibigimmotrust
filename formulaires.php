<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>IBIG IMMO TRUST – Formulaire de collecte</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Police Google -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --blue:#003c96;
      --blue-dark:#001f54;
      --orange:#ff9f1c;
      --red:#e30613;
      --bg:#f4f6fb;
      --card:#ffffff;
      --muted:#6b7280;
      --radius:18px;
    }

    *{box-sizing:border-box;margin:0;padding:0;}
    body{
      font-family:"Inter",system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
      background:
        radial-gradient(circle at top left, rgba(0,60,150,0.08), transparent 55%),
        radial-gradient(circle at bottom right, rgba(255,159,28,0.10), transparent 50%),
        var(--bg);
      color:#111827;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:24px;
    }

    .wf-shell{
      width:100%;
      max-width:960px;
      display:grid;
      grid-template-columns:1.3fr 1fr;
      gap:24px;
      background:var(--card);
      border-radius:24px;
      padding:24px;
      box-shadow:0 18px 40px rgba(15,23,42,0.15);
      border:1px solid rgba(148,163,184,0.25);
    }

    @media(max-width:900px){
      .wf-shell{
        grid-template-columns:1fr;
        padding:16px;
        gap:16px;
      }
    }

    .wf-left{
      background:linear-gradient(145deg,#f9fafb,#eef2ff);
      border-radius:20px;
      padding:18px 18px 14px;
      color:#111827;
      display:flex;
      flex-direction:column;
      justify-content:space-between;
      gap:18px;
      border:1px solid rgba(191,219,254,0.8);
    }

    .brand-badge{
      display:inline-flex;
      align-items:center;
      gap:8px;
      background:#e0ecff;
      border-radius:999px;
      padding:6px 12px;
      font-size:12px;
      text-transform:uppercase;
      letter-spacing:0.09em;
      border:1px solid rgba(37,99,235,0.25);
      color:#1f2937;
    }

    .brand-dot{
      width:10px;height:10px;border-radius:999px;
      background:linear-gradient(135deg,var(--orange),#ffd166);
    }

    .wf-title{
      font-size:26px;
      line-height:1.3;
      font-weight:600;
      margin-top:12px;
      color:var(--blue);
    }
    @media(max-width:600px){
      .wf-title{font-size:22px;}
    }

    .wf-subtitle{
      font-size:14px;
      color:#4b5563;
      margin-top:8px;
    }

    .wf-points{
      margin-top:18px;
      list-style:none;
      display:grid;
      gap:8px;
      font-size:13px;
      color:#374151;
    }

    .wf-points li{
      display:flex;
      gap:8px;
      align-items:flex-start;
    }

    .wf-points span.bullet{
      width:18px;height:18px;border-radius:999px;
      display:inline-flex;align-items:center;justify-content:center;
      font-size:11px;
      background:#e0f2fe;
      border:1px solid #93c5fd;
      color:#1d4ed8;
      flex-shrink:0;
      margin-top:2px;
    }

    .wf-metric{
      margin-top:20px;
      display:grid;
      grid-template-columns:repeat(3,minmax(0,1fr));
      gap:10px;
      font-size:12px;
    }
    @media(max-width:700px){
      .wf-metric{grid-template-columns:1fr 1fr;}
    }

    .wf-metric-card{
      background:linear-gradient(135deg,#eff6ff,#e0ecff);
      border-radius:14px;
      padding:8px 10px;
      border:1px solid rgba(129,140,248,0.6);
    }
    .wf-metric-label{color:#4b5563;margin-bottom:4px;}
    .wf-metric-value{font-size:15px;font-weight:600;color:#111827;}

    .wf-right{
      background:var(--card);
      border-radius:20px;
      padding:20px 20px 18px;
      display:flex;
      flex-direction:column;
      gap:16px;
      position:relative;
      overflow:hidden;
      border:1px solid rgba(229,231,235,0.9);
    }

    .wf-progress-wrap{
      margin-bottom:4px;
    }

    .wf-progress-label{
      display:flex;
      justify-content:space-between;
      font-size:11px;
      text-transform:uppercase;
      letter-spacing:0.12em;
      color:var(--muted);
      margin-bottom:6px;
    }

    .wf-progress{
      width:100%;
      height:6px;
      border-radius:999px;
      background:#e5e7eb;
      overflow:hidden;
    }
    .wf-progress-inner{
      height:100%;
      width:0%;
      border-radius:999px;
      background:linear-gradient(90deg,var(--blue),var(--orange));
      transition:width 0.3s ease;
    }

    .wf-step{
      display:none;
      animation:fadeIn 0.25s ease;
    }
    .wf-step.active{display:block;}

    @keyframes fadeIn{
      from{opacity:0;transform:translateY(4px);}
      to{opacity:1;transform:translateY(0);}
    }

    .wf-question{
      font-size:18px;
      font-weight:600;
      margin-bottom:6px;
      color:#111827;
    }
    @media(max-width:600px){
      .wf-question{font-size:16px;}
    }

    .wf-help{
      font-size:13px;
      color:var(--muted);
      margin-bottom:16px;
    }

    .wf-field{
      margin-bottom:14px;
    }

    label.wf-label{
      display:block;
      font-size:13px;
      font-weight:500;
      color:#374151;
      margin-bottom:4px;
    }

    input[type="text"],
    input[type="tel"],
    input[type="email"],
    input[type="number"],
    select,
    textarea{
      width:100%;
      border-radius:12px;
      border:1px solid #d1d5db;
      padding:9px 11px;
      font-size:14px;
      outline:none;
      transition:border-color 0.15s ease, box-shadow 0.15s ease, background-color 0.15s ease;
      background:#ffffff;
    }
    @media(max-width:600px){
      input[type="text"],
      input[type="tel"],
      input[type="email"],
      input[type="number"],
      select,
      textarea{font-size:15px;padding:10px 12px;}
    }

    textarea{min-height:90px;resize:vertical;}

    input:focus,
    select:focus,
    textarea:focus{
      border-color:var(--blue);
      box-shadow:0 0 0 1px rgba(37,99,235,0.18);
      background:#f9fafb;
    }

    .wf-inline{
      display:grid;
      grid-template-columns:repeat(2,minmax(0,1fr));
      gap:10px;
    }
    @media(max-width:600px){
      .wf-inline{grid-template-columns:1fr;}
    }

    .wf-options{
      display:grid;
      gap:8px;
      margin-top:6px;
    }

    .wf-option{
      border-radius:12px;
      border:1px solid #e5e7eb;
      padding:9px 10px;
      font-size:13px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      gap:10px;
      cursor:pointer;
      transition:all 0.15s ease;
      background:#ffffff;
    }

    .wf-option input{
      margin-right:8px;
    }

    .wf-option:hover{
      border-color:var(--blue);
      background:#eff6ff;
    }

    .wf-option.active{
      border-color:var(--blue);
      background:#e0ecff;
    }

    .wf-option-main{
      display:flex;
      align-items:center;
      gap:6px;
    }

    .wf-badge{
      font-size:11px;
      padding:2px 6px;
      border-radius:999px;
      border:1px solid rgba(15,23,42,0.1);
      color:#4b5563;
      background:#f3f4f6;
      white-space:nowrap;
    }

    .wf-footer{
      margin-top:10px;
      padding-top:10px;
      display:flex;
      align-items:center;
      justify-content:space-between;
      border-top:1px solid #e5e7eb;
      gap:8px;
    }
    @media(max-width:500px){
      .wf-footer{flex-direction:column-reverse; gap:12px;}
      .btn{width:100%;}
    }

    .btn{
      border:none;
      border-radius:999px;
      font-size:13px;
      padding:8px 14px;
      cursor:pointer;
      display:inline-flex;
      align-items:center;
      gap:6px;
      font-weight:500;
      white-space:nowrap;
      justify-content:center;
    }

    .btn-primary{
      background:linear-gradient(135deg,var(--blue),var(--blue-dark));
      color:#f9fafb;
    }

    .btn-primary:hover{
      filter:brightness(1.05);
    }

    .btn-ghost{
      background:transparent;
      color:#4b5563;
    }

    .btn-ghost:hover{
      background:#f3f4f6;
    }

    .wf-step-indicator{
      font-size:11px;
      color:var(--muted);
    }

    .wf-error{
      margin-top:6px;
      font-size:12px;
      color:#b91c1c;
      display:none;
    }

    .wf-error.visible{
      display:block;
    }

    .wf-success{
      text-align:center;
      padding:20px 10px 10px;
    }
    .wf-success h2{
      font-size:20px;
      margin-bottom:6px;
      color:#111827;
    }
    .wf-success p{
      font-size:14px;
      color:#4b5563;
    }

    .wf-tagline{
      margin-top:8px;
      font-size:12px;
      color:#6b7280;
    }

  </style>
</head>
<body>
  <div class="wf-shell">
    <!-- COLONNE GAUCHE – Branding / Promesse -->
    <div class="wf-left">
      <div>
        <div class="brand-badge">
          <span class="brand-dot"></span>
          IBIG IMMO TRUST • FORMULAIRE UNIQUE
        </div>
        <h1 class="wf-title">
          Dites-nous votre projet immobilier & BTP,<br>
          nous vous rappelons avec une solution clé en main.
        </h1>
        <p class="wf-subtitle">
          Finition de chantier inachevé, construction sur terrain, gestion locative
          (avec ou sans loyer garanti), financement, assistance foncière, rénovation…
        </p>

        <ul class="wf-points">
          <li>
            <span class="bullet">1</span>
            <div>Remplissez ce formulaire en moins de 2 minutes.</div>
          </li>
          <li>
            <span class="bullet">2</span>
            <div>Un conseiller IBIG IMMO TRUST analyse votre demande.</div>
          </li>
          <li>
            <span class="bullet">3</span>
            <div>Vous recevez une proposition personnalisée et un plan d’action.</div>
          </li>
        </ul>

        <div class="wf-metric">
          <div class="wf-metric-card">
            <div class="wf-metric-label">Chantiers repris & finalisés</div>
            <div class="wf-metric-value">+120</div>
          </div>
          <div class="wf-metric-card">
            <div class="wf-metric-label">Projets diaspora suivis</div>
            <div class="wf-metric-value">+60</div>
          </div>
          <div class="wf-metric-card">
            <div class="wf-metric-label">Taux de satisfaction</div>
            <div class="wf-metric-value">96 %</div>
          </div>
        </div>
      </div>
      <div class="wf-tagline">
        IBIG IMMO TRUST – Immobilier & BTP Premium en Côte d’Ivoire.
      </div>
    </div>

    <!-- COLONNE DROITE – Formulaire dynamique -->
    <div class="wf-right">
      <div class="wf-progress-wrap">
        <div class="wf-progress-label">
          <span>Étapes</span>
          <span id="wf-progress-text">0%</span>
        </div>
        <div class="wf-progress">
          <div class="wf-progress-inner" id="wf-progress-bar"></div>
        </div>
      </div>

      <form id="ibigForm">
        <!-- STEP 1 : IDENTITÉ -->
        <div class="wf-step active" data-step="1">
          <div class="wf-question">Commençons par faire connaissance 👋</div>
          <div class="wf-help">Ces informations permettent à un conseiller de vous recontacter.</div>

          <div class="wf-field">
            <label class="wf-label" for="full_name">Nom & Prénom *</label>
            <input type="text" id="full_name" name="full_name" required>
          </div>

          <div class="wf-inline">
            <div class="wf-field">
              <label class="wf-label" for="phone">Numéro WhatsApp *</label>
              <input type="tel" id="phone" name="phone" placeholder="+225 07 xx xx xx xx" required>
            </div>
            <div class="wf-field">
              <label class="wf-label" for="email">E-mail (optionnel)</label>
              <input type="email" id="email" name="email" placeholder="vous@exemple.com">
            </div>
          </div>

          <div class="wf-field">
            <label class="wf-label" for="location">Ville / Pays de résidence *</label>
            <input type="text" id="location" name="location" placeholder="Abidjan, Paris, Montréal…" required>
          </div>

          <div class="wf-field">
            <label class="wf-label">Êtes-vous actuellement en Côte d’Ivoire ?</label>
            <div class="wf-options">
              <label class="wf-option">
                <div class="wf-option-main">
                  <input type="radio" name="is_local" value="oui" checked>
                  <span>Oui, je suis en Côte d’Ivoire</span>
                </div>
                <span class="wf-badge">Local</span>
              </label>
              <label class="wf-option">
                <div class="wf-option-main">
                  <input type="radio" name="is_local" value="non">
                  <span>Non, je suis à l’étranger / diaspora</span>
                </div>
                <span class="wf-badge">Diaspora</span>
              </label>
            </div>
          </div>

          <div class="wf-error" id="wf-error-1"></div>
        </div>

        <!-- STEP 2 : BESOIN PRINCIPAL -->
        <div class="wf-step" data-step="2">
          <div class="wf-question">Quel est votre besoin principal ?</div>
          <div class="wf-help">Choisissez l’option qui décrit le mieux votre projet.</div>

          <div class="wf-field">
            <label class="wf-label" for="main_need">Je souhaite principalement… *</label>
            <select id="main_need" name="main_need" required>
              <option value="">Sélectionnez une option</option>
              <option value="acheter">Acheter un bien</option>
              <option value="vendre">Vendre un bien</option>
              <option value="gestion_locative">Faire gérer mon bien (gestion locative)</option>
              <option value="gestion_premium">Gestion locative premium (versement garanti avant le 10)</option>
              <option value="finir_chantier">Finir un chantier inachevé</option>
              <option value="construire">Construire sur mon terrain</option>
              <option value="renover">Rénover / moderniser un bien</option>
              <option value="cloturer">Faire clôturer mon terrain</option>
              <option value="financement">Obtenir un financement</option>
              <option value="foncier">Assistance foncière / juridique</option>
              <option value="investir">Investir dans un projet rentable</option>
              <option value="autre">Autre besoin</option>
            </select>
          </div>

          <div class="wf-error" id="wf-error-2"></div>
        </div>

        <!-- STEP 3 : DÉTAILS SELON LE BESOIN -->
        <div class="wf-step" data-step="3">
          <div class="wf-question" id="need-title">Précisez votre projet</div>
          <div class="wf-help" id="need-help">
            Quelques questions pour mieux comprendre votre situation.
          </div>

          <div id="need-branches">
            <!-- … les branches comme défini précédemment … -->
            <!-- on garde exactement ce que tu as déjà créé -->
          </div>

          <div class="wf-error" id="wf-error-3"></div>
        </div>

        <!-- STEP 4 : MESSAGE LIBRE + VALIDATION -->
        <div class="wf-step" data-step="4">
          <div class="wf-question">Un dernier mot sur votre projet ?</div>
          <div class="wf-help">
            Ajoutez des précisions utiles (délais, contraintes, préférences…). Facultatif mais très utile.
          </div>
          <div class="wf-field">
            <label class="wf-label" for="message">Message complémentaire</label>
            <textarea id="message" name="message" placeholder="Ex : Je pars bientôt à l’étranger, j’ai besoin d’une solution rapide pour mon chantier à Bingerville…"></textarea>
          </div>
          <div class="wf-field">
            <label class="wf-label">
              <input type="checkbox" name="confirm" required>
              Je certifie que les informations fournies sont exactes et j’autorise IBIG IMMO TRUST à me contacter.
            </label>
          </div>
          <div class="wf-error" id="wf-error-4"></div>
        </div>

        <!-- STEP 5 : ÉCRAN DE SUCCÈS -->
        <div class="wf-step" data-step="5">
          <div class="wf-success">
            <h2>Merci, votre demande a bien été envoyée ✅</h2>
            <p>
              Un conseiller IBIG IMMO TRUST vous contactera rapidement pour analyser votre projet
              et vous proposer un plan d’action personnalisé.
            </p>
            <p class="wf-tagline" style="margin-top:10px;">
              Gardez votre téléphone à portée de main. Vous pouvez également préparer vos documents
              (photos, plans, titres fonciers) pour accélérer l’étude de votre dossier.
            </p>
          </div>
        </div>
      </form>

      <div class="wf-footer">
        <button type="button" class="btn btn-ghost" id="btn-prev">← Précédent</button>
        <div class="wf-step-indicator" id="wf-step-indicator">Étape 1 sur 4</div>
        <button type="button" class="btn btn-primary" id="btn-next">Suivant →</button>
      </div>

    </div>
  </div>

  <script>
    const totalSteps = 4;
    let currentStep = 1;

    const steps = document.querySelectorAll(".wf-step");
    const progressBar = document.getElementById("wf-progress-bar");
    const progressText = document.getElementById("wf-progress-text");
    const stepIndicator = document.getElementById("wf-step-indicator");
    const btnPrev = document.getElementById("btn-prev");
    const btnNext = document.getElementById("btn-next");
    const form = document.getElementById("ibigForm");

    const errorBoxes = {
      1: document.getElementById("wf-error-1"),
      2: document.getElementById("wf-error-2"),
      3: document.getElementById("wf-error-3"),
      4: document.getElementById("wf-error-4"),
    };

    function updateProgress(){
      const percent = Math.round(((currentStep - 1) / totalSteps) * 100);
      progressBar.style.width = percent + "%";
      progressText.textContent = percent + "%";
      stepIndicator.textContent = currentStep <= totalSteps
        ? `Étape ${currentStep} sur ${totalSteps}`
        : `Terminé`;
    }

    function showStep(step){
      steps.forEach(s => s.classList.remove("active"));
      const target = document.querySelector(`.wf-step[data-step="${step}"]`);
      if(target){ target.classList.add("active"); }

      btnPrev.style.visibility = (step === 1 || step === 5) ? "hidden" : "visible";

      if(step === totalSteps){
        btnNext.textContent = "Envoyer ma demande ✓";
      } else if(step === 5){
        btnNext.style.display = "none";
      } else {
        btnNext.style.display = "inline-flex";
        btnNext.textContent = "Suivant →";
      }

      updateProgress();
    }

    function validateStep(step){
      Object.values(errorBoxes).forEach(box => {
        if(box){ box.classList.remove("visible"); box.textContent = ""; }
      });

      if(step === 1){
        const name = document.getElementById("full_name").value.trim();
        const phone = document.getElementById("phone").value.trim();
        const loc = document.getElementById("location").value.trim();
        if(!name || !phone || !loc){
          errorBoxes[1].textContent = "Merci de renseigner au minimum votre nom, téléphone et ville/pays.";
          errorBoxes[1].classList.add("visible");
          return false;
        }
      }
      if(step === 2){
        const need = document.getElementById("main_need").value;
        if(!need){
          errorBoxes[2].textContent = "Merci de sélectionner votre besoin principal.";
          errorBoxes[2].classList.add("visible");
          return false;
        }
      }
      if(step === 3){
        const need = document.getElementById("main_need").value;
        if(!need){
          errorBoxes[3].textContent = "Veuillez d’abord choisir un besoin principal à l’étape précédente.";
          errorBoxes[3].classList.add("visible");
          return false;
        }
      }
      if(step === 4){
        const confirm = form.querySelector("input[name='confirm']");
        if(!confirm.checked){
          errorBoxes[4].textContent = "Vous devez confirmer l’exactitude des informations pour continuer.";
          errorBoxes[4].classList.add("visible");
          return false;
        }
      }
      return true;
    }

    function updateNeedBranch(){
      const need = document.getElementById("main_need").value;
      const branches = document.querySelectorAll(".wf-branch");
      branches.forEach(b => b.style.display="none");
      if(!need) return;
      const target = document.querySelector(`.wf-branch[data-need="${need}"]`);
      if(target) target.style.display="block";

      const title = document.getElementById("need-title");
      const help = document.getElementById("need-help");
      const texts = { /* … même map que précédemment … */ };

      if(texts[need]){
        title.textContent = texts[need][0];
        help.textContent = texts[need][1];
      } else {
        title.textContent = "Précisez votre projet";
        help.textContent = "Quelques questions pour mieux comprendre votre situation.";
      }
    }

    document.getElementById("main_need").addEventListener("change", updateNeedBranch);

    document.querySelectorAll(".wf-option input[type='radio']").forEach(r => {
      r.addEventListener("change", () => {
        const name = r.name;
        document.querySelectorAll(`.wf-option input[name="${name}"]`).forEach(i => {
          i.closest(".wf-option").classList.remove("active");
        });
        r.closest(".wf-option").classList.add("active");
      });
    });

    btnNext.addEventListener("click", async () => {
      if(currentStep <= totalSteps && !validateStep(currentStep)) return;

      if(currentStep === totalSteps){
        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        try{
          const response = await fetch("/api/immo_lead.php", {
            method:"POST",
            headers:{"Content-Type":"application/json"},
            body:JSON.stringify(data)
          });
          const result = await response.json();
          if(result.redirect){
            window.location.href = result.redirect;
          }
        }catch(e){
          console.warn("Erreur d’envoi (placeholder):", e);
        }

        currentStep = 5;
        showStep(currentStep);
        return;
      }

      currentStep++;
      showStep(currentStep);
    });

    btnPrev.addEventListener("click", () => {
      if(currentStep > 1 && currentStep <= totalSteps){
        currentStep--;
        showStep(currentStep);
      }
    });

    form.addEventListener("submit", e => e.preventDefault());

    updateProgress();
    updateNeedBranch();
    btnPrev.style.visibility = "hidden";
  </script>
</body>
</html>