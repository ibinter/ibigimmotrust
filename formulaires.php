<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>IBIG IMMO TRUST – Collecte Demandes Immobilières & BTP</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* =========================
   VARIABLES IBIG
========================= */
:root{
  --ibi-blue:#0A1628;
  --ibi-blue-dark:#060d1a;
  --ibi-orange:#E8CC6A;
  --ibi-bg:#f4f6fb;
  --card:#ffffff;
  --muted:#6b7280;
  --radius:18px;
  --shadow:0 18px 45px rgba(15,23,42,.15);
}

*{box-sizing:border-box;margin:0;padding:0}

/* =========================
   BASE
========================= */
body{
  font-family:Inter,system-ui,-apple-system,sans-serif;
  background:
    radial-gradient(circle at top left, rgba(10,22,40,.08), transparent 55%),
    radial-gradient(circle at bottom right, rgba(212,175,55,.12), transparent 55%),
    var(--ibi-bg);
  min-height:100vh;
  display:flex;
  justify-content:center;
  align-items:center;
  padding:24px;
  color:#111827;
}

/* =========================
   WRAPPER
========================= */
.ibig-wrapper{
  width:100%;
  max-width:1100px;
  background:var(--card);
  border-radius:26px;
  box-shadow:var(--shadow);
  display:grid;
  grid-template-columns:1.35fr 1fr;
  gap:28px;
  padding:28px;
}

@media(max-width:900px){
  .ibig-wrapper{
    grid-template-columns:1fr;
    padding:18px;
  }
}

/* =========================
   COL GAUCHE – BRANDING
========================= */
.ibig-left{
  background:linear-gradient(145deg,#f9fafb,#FAF7F0);
  border-radius:22px;
  padding:26px;
  border:1px solid rgba(191,219,254,.9);
  display:flex;
  flex-direction:column;
  justify-content:space-between;
}

.brand-pill{
  display:inline-flex;
  align-items:center;
  gap:8px;
  font-size:12px;
  letter-spacing:.1em;
  text-transform:uppercase;
  background:#e8e3d4;
  border-radius:999px;
  padding:6px 14px;
  border:1px solid rgba(37,99,235,.3);
}

.brand-dot{
  width:10px;height:10px;border-radius:50%;
  background:linear-gradient(135deg,var(--ibi-orange),#ffd166);
}

.ibig-left h1{
  margin-top:14px;
  font-size:28px;
  line-height:1.3;
  color:var(--ibi-blue);
}

.ibig-left p{
  margin-top:10px;
  font-size:14px;
  color:#374151;
}

.steps-info{
  margin-top:22px;
  list-style:none;
  display:grid;
  gap:10px;
  font-size:13px;
}

.steps-info li{
  display:flex;
  gap:10px;
}

.steps-info span{
  width:20px;height:20px;
  border-radius:50%;
  background:#fdf8ec;
  border:1px solid rgba(212,175,55,0.4);
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:11px;
  font-weight:600;
  color:#0A1628;
}

/* =========================
   COL DROITE – FORM
========================= */
.ibig-right{
  border:1px solid #e5e7eb;
  border-radius:22px;
  padding:22px;
  position:relative;
}

/* PROGRESS */
.progress-wrap{
  margin-bottom:14px;
}
.progress-label{
  display:flex;
  justify-content:space-between;
  font-size:11px;
  letter-spacing:.12em;
  text-transform:uppercase;
  color:var(--muted);
  margin-bottom:6px;
}
.progress{
  height:6px;
  background:#e5e7eb;
  border-radius:999px;
  overflow:hidden;
}
.progress-inner{
  height:100%;
  width:0%;
  background:linear-gradient(90deg,var(--ibi-blue),var(--ibi-orange));
  transition:.3s;
}

/* =========================
   STEPS
========================= */
.step{display:none}
.step.active{
  display:block;
  animation:fade .25s ease;
}
@keyframes fade{
  from{opacity:0;transform:translateY(4px)}
  to{opacity:1;transform:translateY(0)}
}

.step h2{
  font-size:19px;
  margin-bottom:6px;
}
.step p.help{
  font-size:13px;
  color:var(--muted);
  margin-bottom:16px;
}

/* =========================
   FIELDS
========================= */
.field{margin-bottom:14px}
label{
  font-size:13px;
  font-weight:500;
  display:block;
  margin-bottom:4px;
}
input,select,textarea{
  width:100%;
  padding:10px 12px;
  border-radius:12px;
  border:1px solid #d1d5db;
  font-size:14px;
}
textarea{min-height:90px}
input:focus,select:focus,textarea:focus{
  outline:none;
  border-color:var(--ibi-blue);
  background:#f9fafb;
}

/* =========================
   FOOTER
========================= */
.form-footer{
  margin-top:14px;
  padding-top:12px;
  border-top:1px solid #e5e7eb;
  display:flex;
  justify-content:space-between;
  gap:10px;
}

button{
  border:none;
  border-radius:999px;
  padding:10px 18px;
  font-size:13px;
  cursor:pointer;
}
.btn-primary{
  background:linear-gradient(135deg,var(--ibi-blue),var(--ibi-blue-dark));
  color:#fff;
}
.btn-ghost{
  background:#f3f4f6;
  color:#374151;
}

/* =========================
   SUCCESS
========================= */
.success{text-align:center}
.success h2{font-size:22px;margin-bottom:8px}
.success p{font-size:14px;color:#374151}
</style>
</head>

<body>

<div class="ibig-wrapper">

<!-- GAUCHE -->
<div class="ibig-left">
  <div>
    <div class="brand-pill"><span class="brand-dot"></span> IBIG IMMO TRUST</div>
    <h1>Décrivez votre projet immobilier ou BTP</h1>
    <p>
      Construction, finition de chantier, gestion locative,
      investissement, assistance foncière ou financement.
    </p>

    <ul class="steps-info">
      <li><span>1</span>Informations de contact</li>
      <li><span>2</span>Nature du projet</li>
      <li><span>3</span>Détails techniques & financiers</li>
      <li><span>4</span>Validation & envoi</li>
    </ul>
  </div>

  <small style="font-size:12px;color:#6b7280">
    IBIG IMMO TRUST – Immobilier & BTP Premium en Côte d’Ivoire
  </small>
</div>

<!-- DROITE -->
<div class="ibig-right">

<div class="progress-wrap">
  <div class="progress-label">
    <span id="stepLabel">Vos coordonnées</span>
    <span id="percent">0%</span>
  </div>
  <div class="progress"><div class="progress-inner" id="bar"></div></div>
</div>

<form id="form" method="POST" action="/api/immo_lead.php">

<!-- TRACKING -->
<input type="hidden" name="source" value="site_web">
<input type="hidden" name="lead_type" value="immo_btp">
<input type="hidden" name="page_url" id="page_url">
<input type="text" name="company" style="display:none">

<!-- STEP 1 -->
<div class="step active">
  <h2>Vos coordonnées</h2>
  <p class="help">Pour qu’un conseiller puisse vous rappeler.</p>

  <div class="field"><label>Nom & Prénom *</label><input name="full_name" required></div>
  <div class="field"><label>Téléphone WhatsApp *</label><input name="phone" required></div>
  <div class="field"><label>Email</label><input name="email"></div>
  <div class="field"><label>Ville / Pays *</label><input name="location" required></div>
</div>

<!-- STEP 2 -->
<div class="step">
  <h2>Votre besoin principal</h2>
  <p class="help">Choisissez l’option la plus proche de votre projet.</p>

  <div class="field">
    <label>Type de projet *</label>
    <select name="main_need" required>
      <option value="">Choisir…</option>
      <option value="achat">Achat immobilier</option>
      <option value="vente">Vente immobilière</option>
      <option value="chantier">Finition chantier</option>
      <option value="construction">Construction neuve</option>
      <option value="gestion">Gestion locative</option>
      <option value="renovation">Rénovation</option>
      <option value="foncier">Assistance foncière</option>
      <option value="financement">Financement</option>
      <option value="investissement">Investissement</option>
    </select>
  </div>

  <div class="field">
    <label>Budget estimatif</label>
    <input name="budget" placeholder="Ex : 20 à 50 millions FCFA">
  </div>
</div>

<!-- STEP 3 -->
<div class="step">
  <h2>Détails du projet</h2>

  <div class="field">
    <label>Délai souhaité *</label>
    <select name="urgency" required>
      <option value="">Choisir…</option>
      <option value="urgent">Urgent (&lt; 30 jours)</option>
      <option value="court">1 à 3 mois</option>
      <option value="moyen">Moyen terme</option>
      <option value="long">Long terme</option>
    </select>
  </div>

  <div class="field">
    <label>Documents fonciers disponibles ?</label>
    <select name="documents">
      <option value="">Choisir…</option>
      <option value="oui">Oui (ACD, CPF, TF…)</option>
      <option value="partiel">Partiellement</option>
      <option value="non">Non</option>
    </select>
  </div>

  <div class="field">
    <label>Message complémentaire</label>
    <textarea name="message"></textarea>
  </div>
</div>

<!-- STEP 4 -->
<div class="step">
  <h2>Validation</h2>
  <label>
    <input type="checkbox" name="confirm" required>
    Je confirme l’exactitude des informations fournies
  </label>
</div>

<!-- STEP 5 -->
<div class="step success">
  <h2>Demande envoyée ✅</h2>
  <p>Un conseiller IBIG IMMO TRUST vous contactera sous peu.</p>
</div>

<div class="form-footer">
  <button type="button" class="btn-ghost" id="prev">← Précédent</button>
  <button type="button" class="btn-primary" id="next">Suivant →</button>
</div>

</form>
</div>
</div>

<script>
let step=0;
const steps=document.querySelectorAll('.step');
const bar=document.getElementById('bar');
const percent=document.getElementById('percent');
const label=document.getElementById('stepLabel');
const next=document.getElementById('next');
const prev=document.getElementById('prev');

const labels=[
  "Vos coordonnées",
  "Votre besoin",
  "Détails du projet",
  "Validation",
  "Confirmation"
];

document.getElementById('page_url').value=location.href;

function show(){
  steps.forEach(s=>s.classList.remove('active'));
  steps[step].classList.add('active');
  bar.style.width=(step/4*100)+'%';
  percent.textContent=Math.round(step/4*100)+'%';
  label.textContent=labels[step];
  prev.style.display=step===0?'none':'inline-block';
  next.textContent=step===3?'Envoyer':'Suivant →';
  if(step===4) next.style.display='none';
}

next.onclick=()=>{
  if(step===3){
    document.getElementById('form').submit();
    return;
  }
  step++; show();
}
prev.onclick=()=>{ if(step>0){ step--; show(); } }
show();
</script>

</body>
</html>
