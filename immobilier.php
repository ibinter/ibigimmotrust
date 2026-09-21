<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/tracker.php';

$currentPage = 'immobilier';
$disableHeaderSlider = true;
$pageTitle = "Immobilier - IBIG IMMO TRUST";
$metaDescription = "Gestion locative, loyer garanti, assistance foncière, mise en location et financement immobilier avec IBIG IMMO TRUST en Côte d'Ivoire.";
$canonicalUrl = url('immobilier.php');

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

$serviceCards = [
    [
        'image' => url('assets/img/gestion-locative.jpg'),
        'tag' => 'Gestion',
        'title' => 'Gestion locative professionnelle',
        'text' => 'Mise en location, perception des loyers, relation locataire, maintenance et reporting structuré.',
        'url' => url('loyer-garanti.php'),
        'cta' => 'Découvrir le service',
    ],
    [
        'image' => url('assets/img/gestion-locative3.jpg'),
        'tag' => 'Revenus',
        'title' => 'Loyer garanti en option',
        'text' => "Une solution pensée pour sécuriser les revenus locatifs et réduire les périodes d'incertitude selon les conditions du contrat.",
        'url' => url('loyer-garanti.php'),
        'cta' => 'Voir le loyer garanti',
    ],
    [
        'image' => url('assets/img/documents-fonciers.jpg'),
        'tag' => 'Foncier',
        'title' => 'Assistance foncière',
        'text' => 'Vérification des documents, lecture du risque, validation de cohérence et accompagnement avant engagement.',
        'url' => url('assistance-fonciere.php'),
        'cta' => 'Vérifier mes documents',
    ],
    [
        'image' => url('assets/img/financement-ibig.jpg'),
        'tag' => 'Financement',
        'title' => 'Financement immobilier',
        'text' => 'Direct IBIG, investisseurs partenaires, banques et microfinances selon la nature du projet et le profil du client.',
        'url' => url('financement.php'),
        'cta' => 'Étudier les options',
    ],
];

$managementPillars = [
    [
        'icon' => 'fa-solid fa-user-check',
        'title' => 'Sélection des locataires',
        'text' => 'Visites, qualification des dossiers, lecture de solvabilité et sécurisation du cadre locatif.',
    ],
    [
        'icon' => 'fa-solid fa-wallet',
        'title' => 'Perception et sécurisation',
        'text' => 'Suivi des encaissements, rappels, régularisation et meilleure stabilité des revenus locatifs.',
    ],
    [
        'icon' => 'fa-solid fa-screwdriver-wrench',
        'title' => 'Maintenance et incidents',
        'text' => 'Organisation des interventions, suivi technique, coordination des réparations et traçabilité.',
    ],
    [
        'icon' => 'fa-solid fa-chart-pie',
        'title' => 'Reporting digital',
        'text' => "Synthèses, visuels, état d'occupation, informations utiles et visibilité renforcée pour la diaspora.",
    ],
];

$foncierChecks = [
    [
        'title' => 'Vérification des titres',
        'text' => 'ACD, TF, CF, attestations, dossiers villageois et cohérence des pièces remises.',
    ],
    [
        'title' => 'Visite terrain et bornage',
        'text' => 'Contrôle physique du site, environnement immédiat, accès, limites et occupation réelle.',
    ],
    [
        'title' => 'Lecture juridique',
        'text' => "Historique du dossier, points de vigilance, risques potentiels et niveau de sécurité de l'opération.",
    ],
    [
        'title' => 'Assistance de formalisation',
        'text' => 'Orientation sur les démarches, contractualisation, authentification et meilleure préparation du dossier.',
    ],
];

$audiences = [
    [
        'title' => 'Propriétaires',
        'text' => 'Mieux louer, mieux suivre, mieux valoriser et mieux sécuriser un patrimoine immobilier.',
    ],
    [
        'title' => 'Investisseurs',
        'text' => "Lire le potentiel d'exploitation, sécuriser les décisions et structurer la rentabilité.",
    ],
    [
        'title' => 'Diaspora',
        'text' => 'Garder le contrôle à distance grâce à un cadre plus clair, plus visuel et plus pilotable.',
    ],
];

$advantages = [
    'Une approche qui relie immobilier, terrain, exploitation et exécution.',
    'Une lecture orientée sécurité, rendement et qualité d’exploitation.',
    'Des solutions utilisables aussi bien pour la présence locale que pour la diaspora.',
    'Un passage plus fluide entre assistance foncière, mise en location et financement.',
];

$faqs = [
    [
        'q' => "La gestion locative est-elle possible si je vis hors de Côte d'Ivoire ?",
        'a' => "Oui. Le suivi a été pensé pour permettre au propriétaire à distance de garder une visibilité claire sur son bien et son exploitation.",
    ],
    [
        'q' => "Le loyer garanti s'applique-t-il à tous les biens ?",
        'a' => "Non. Le loyer garanti dépend du bien, du profil locatif et des conditions définies au contrat.",
    ],
    [
        'q' => "Pouvez-vous intervenir avant un achat pour vérifier un terrain ?",
        'a' => "Oui. L'assistance foncière est justement là pour mieux lire le dossier avant de payer ou de vous engager.",
    ],
    [
        'q' => "L'immobilier IBIG se limite-t-il à la location ?",
        'a' => "Non. L'offre couvre aussi la sécurisation foncière, la valorisation, l'orientation patrimoniale et le financement.",
    ],
];

include __DIR__ . '/includes/header.php';
?>

<style>
:root {
  --imm-blue:#1B4FD8;
  --imm-blue-dark:#1340B0;
  --imm-red:#E8282A;
  --imm-gold:#FF5557;
  --imm-ink:#101828;
  --imm-ink-soft:#1d2939;
  --imm-muted:#667085;
  --imm-line:#e4e7ec;
  --imm-surface:#ffffff;
  --imm-surface-soft:#f8fafc;
  --imm-surface-warm:#FAF7F0;
  --imm-shadow-sm:0 10px 28px rgba(15, 23, 42, 0.06);
  --imm-shadow-md:0 20px 54px rgba(15, 23, 42, 0.10);
  --imm-shadow-lg:0 30px 72px rgba(8, 39, 95, 0.18);
  --imm-radius-sm:16px;
  --imm-radius-md:22px;
  --imm-radius-lg:30px;
  --imm-shell:1240px;
}

.imm-page {
  color:var(--imm-ink);
  background:#fff;
}

.imm-shell {
  width:min(var(--imm-shell), calc(100% - 32px));
  margin:0 auto;
}

.imm-section {
  padding:84px 0;
}

.imm-section--soft {
  background:
    radial-gradient(circle at top right, rgba(232,40,42,.12), transparent 22%),
    linear-gradient(180deg, #FAF7F0 0%, #ffffff 100%);
}

.imm-section--cta {
  background:
    radial-gradient(circle at top left, rgba(255,255,255,.08), transparent 20%),
    linear-gradient(135deg, #1340B0 0%, #1B4FD8 55%, #1E57E8 100%);
  color:#fff;
}

.imm-section-head {
  display:flex;
  justify-content:space-between;
  align-items:end;
  gap:18px;
  flex-wrap:wrap;
  margin-bottom:28px;
}

.imm-title {
  margin:0 0 10px;
  font-size:clamp(20px, 2vw, 30px);
  line-height:1.08;
  letter-spacing:-0.03em;
  color:var(--imm-ink);
  font-weight:800;
}

.imm-section--cta .imm-title {
  color:#fff;
}

.imm-intro {
  max-width:780px;
  margin:0;
  color:var(--imm-muted);
  font-size:17px;
}

.imm-section--cta .imm-intro {
  color:rgba(255,255,255,.90);
}

.imm-hero {
  position:relative;
  overflow:hidden;
  padding:34px 0 44px;
  background:
    radial-gradient(circle at 12% 12%, rgba(232,40,42,.18), transparent 30%),
    radial-gradient(circle at 88% 16%, rgba(232,40,42,.16), transparent 24%),
    linear-gradient(135deg, #1340B0 0%, #1B4FD8 50%, #E8282A 100%);
  color:#fff;
}

.imm-hero::before {
  content:"";
  position:absolute;
  inset:0;
  background:
    linear-gradient(90deg, rgba(255,255,255,.05) 1px, transparent 1px),
    linear-gradient(180deg, rgba(255,255,255,.05) 1px, transparent 1px);
  background-size:34px 34px;
  opacity:.55;
  pointer-events:none;
}

.imm-hero-grid {
  position:relative;
  z-index:1;
  display:grid;
  grid-template-columns:minmax(0, 1.02fr) minmax(320px, .98fr);
  gap:34px;
  align-items:center;
}

.imm-hero-pill {
  display:inline-flex;
  align-items:center;
  gap:10px;
  min-height:38px;
  padding:0 14px;
  border-radius:999px;
  background:rgba(255,255,255,.14);
  border:1px solid rgba(255,255,255,.18);
  font-size:13px;
  font-weight:700;
}

.imm-hero-pill i {
  color:#FF5557;
}

.imm-hero-title {
  margin:18px 0 16px;
  font-size:clamp(26px, 3vw, 44px);
  line-height:1.03;
  letter-spacing:-0.04em;
  color:#fff;
  font-weight:820;
}

.imm-hero-text {
  margin:0;
  max-width:720px;
  color:rgba(255,255,255,.92);
  font-size:18px;
}

.imm-actions {
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  margin-top:24px;
}

.imm-button,
.imm-button--outline,
.imm-button--ghost,
.imm-button--light,
.imm-button--dark {
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  min-height:52px;
  padding:0 22px;
  border-radius:14px;
  border:1px solid transparent;
  font-size:15px;
  font-weight:760;
  text-decoration:none;
  transition:transform .2s ease, box-shadow .2s ease, background .2s ease;
}

.imm-button:hover,
.imm-button--outline:hover,
.imm-button--ghost:hover,
.imm-button--light:hover,
.imm-button--dark:hover {
  transform:translateY(-2px);
}

.imm-button {
  background:linear-gradient(135deg, var(--imm-gold) 0%, #FF5557 100%);
  color:#2b1900;
  box-shadow:0 16px 36px rgba(232,40,42,.26);
}

.imm-button--outline {
  background:rgba(255,255,255,.10);
  color:#fff;
  border-color:rgba(255,255,255,.24);
}

.imm-button--ghost {
  background:#fff4ef;
  color:var(--imm-red);
  border-color:rgba(232,40,42,.10);
}

.imm-button--light {
  background:#fff;
  color:var(--imm-blue-dark);
  border-color:#d8e3f0;
}

.imm-button--dark {
  background:var(--imm-ink);
  color:#fff;
}

.imm-metrics {
  display:grid;
  grid-template-columns:repeat(3, minmax(0, 1fr));
  gap:12px;
  margin-top:26px;
}

.imm-metric {
  padding:16px 14px;
  border-radius:18px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.14);
  backdrop-filter:blur(8px);
}

.imm-metric strong {
  display:block;
  margin-bottom:4px;
  font-size:26px;
  line-height:1;
  font-weight:800;
}

.imm-metric span {
  color:rgba(255,255,255,.84);
  font-size:13px;
  font-weight:650;
}

.imm-hero-note {
  display:inline-flex;
  align-items:center;
  gap:8px;
  margin-top:18px;
  color:rgba(255,255,255,.92);
  font-size:13px;
  font-weight:650;
}

.imm-hero-card {
  overflow:hidden;
  border-radius:28px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.18);
  box-shadow:var(--imm-shadow-lg);
  backdrop-filter:blur(8px);
}

.imm-hero-card__media img {
  width:100%;
  height:520px;
  object-fit:cover;
}

.imm-hero-card__body {
  padding:22px;
}

.imm-hero-card__title {
  margin:0 0 10px;
  color:#fff;
  font-size:28px;
  line-height:1.08;
  font-weight:800;
}

.imm-hero-card__text {
  margin:0;
  color:rgba(255,255,255,.88);
  font-size:15px;
}

.imm-grid-4 {
  display:grid;
  grid-template-columns:repeat(4, minmax(0, 1fr));
  gap:18px;
}

.imm-grid-3 {
  display:grid;
  grid-template-columns:repeat(3, minmax(0, 1fr));
  gap:22px;
}

.imm-card,
.imm-mini-card,
.imm-audience-card,
.imm-process-card,
.imm-faq,
.imm-split-card {
  background:var(--imm-surface);
  border:1px solid var(--imm-line);
  border-radius:22px;
  box-shadow:var(--imm-shadow-sm);
}

.imm-card {
  overflow:hidden;
}

.imm-card__media {
  position:relative;
}

.imm-card__media img {
  width:100%;
  height:220px;
  object-fit:cover;
}

.imm-card__tag {
  position:absolute;
  top:14px;
  left:14px;
  display:inline-flex;
  align-items:center;
  min-height:34px;
  padding:0 12px;
  border-radius:999px;
  background:#fff;
  color:var(--imm-red);
  font-size:12px;
  font-weight:760;
  box-shadow:var(--imm-shadow-sm);
}

.imm-card__body {
  display:flex;
  flex-direction:column;
  gap:10px;
  padding:18px;
}

.imm-card h3,
.imm-mini-card h3,
.imm-audience-card h3,
.imm-process-card h3,
.imm-split-card h3 {
  margin:0 0 8px;
  color:var(--imm-ink);
  font-size:21px;
  line-height:1.18;
  font-weight:790;
}

.imm-card p,
.imm-mini-card p,
.imm-audience-card p,
.imm-process-card p,
.imm-split-card p {
  margin:0;
  color:var(--imm-muted);
  font-size:15px;
}

.imm-card__footer {
  margin-top:auto;
  padding-top:8px;
}

.imm-mini-card,
.imm-audience-card,
.imm-process-card {
  padding:24px 22px;
}

.imm-mini-card__icon {
  width:56px;
  height:56px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:18px;
  background:linear-gradient(135deg, rgba(27,79,216,.08), rgba(232,40,42,.12));
  color:var(--imm-blue);
  font-size:22px;
  margin-bottom:14px;
}

.imm-split {
  display:grid;
  grid-template-columns:minmax(0, .92fr) minmax(0, 1.08fr);
  gap:32px;
  align-items:center;
}

.imm-split-card {
  overflow:hidden;
}

.imm-split-card img {
  width:100%;
  height:520px;
  object-fit:cover;
}

.imm-split-card__body {
  padding:24px;
}

.imm-list {
  margin:20px 0 0;
  padding:0;
  list-style:none;
  display:grid;
  gap:12px;
}

.imm-list li {
  display:flex;
  gap:12px;
  align-items:flex-start;
  color:var(--imm-ink);
  font-size:15px;
  font-weight:650;
}

.imm-list li::before {
  content:"";
  width:24px;
  height:24px;
  min-width:24px;
  margin-top:2px;
  border-radius:999px;
  background:linear-gradient(135deg, rgba(27,79,216,.12), rgba(232,40,42,.12));
  box-shadow:inset 0 0 0 6px rgba(27,79,216,.14);
}

.imm-process-card {
  position:relative;
  overflow:hidden;
}

.imm-process-card::before {
  content:"";
  position:absolute;
  top:0;
  left:0;
  width:100%;
  height:4px;
  background:linear-gradient(90deg, var(--imm-blue), var(--imm-red));
}

.imm-process-card__number {
  width:54px;
  height:54px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:18px;
  background:linear-gradient(135deg, var(--imm-blue), var(--imm-red));
  color:#fff;
  font-size:22px;
  font-weight:820;
  box-shadow:0 14px 28px rgba(27,79,216,.20);
  margin-bottom:16px;
}

.imm-faq-grid {
  display:grid;
  gap:14px;
  max-width:900px;
  margin:0 auto;
}

.imm-faq {
  overflow:hidden;
}

.imm-faq summary {
  list-style:none;
  cursor:pointer;
  position:relative;
  padding:20px 22px;
  padding-right:56px;
  font-size:17px;
  font-weight:760;
}

.imm-faq summary::-webkit-details-marker {
  display:none;
}

.imm-faq summary::after {
  content:"+";
  position:absolute;
  right:22px;
  top:50%;
  transform:translateY(-50%);
  color:var(--imm-red);
  font-size:28px;
  font-weight:300;
}

.imm-faq[open] summary::after {
  content:"−";
}

.imm-faq__content {
  padding:0 22px 20px;
  color:var(--imm-muted);
  font-size:15px;
}

.imm-cta-inner {
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:22px;
  flex-wrap:wrap;
}

.imm-cta-inner .imm-title {
  color:#fff;
  margin-bottom:8px;
}

.imm-cta-inner .imm-intro {
  color:rgba(255,255,255,.90);
}

@media (max-width: 1180px) {
  .imm-hero-grid,
  .imm-split {
    grid-template-columns:1fr;
  }

  .imm-grid-4 {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }

  .imm-grid-3 {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 760px) {
  .imm-shell {
    width:min(var(--imm-shell), calc(100% - 22px));
  }

  .imm-section {
    padding:68px 0;
  }

  .imm-hero {
    padding:24px 0 32px;
  }

  .imm-hero-title {
    font-size:clamp(20px, 2.2vw, 32px);
  }

  .imm-hero-text,
  .imm-intro {
    font-size:16px;
  }

  .imm-grid-4,
  .imm-grid-3,
  .imm-metrics {
    grid-template-columns:1fr;
  }

  .imm-actions,
  .imm-cta-inner {
    flex-direction:column;
    align-items:stretch;
  }

  .imm-button,
  .imm-button--outline,
  .imm-button--ghost,
  .imm-button--light,
  .imm-button--dark {
    width:100%;
  }

  .imm-hero-card__media img,
  .imm-split-card img {
    height:360px;
  }
}
</style>

<main class="imm-page">
  <section class="imm-hero">
    <div class="imm-shell">
      <div class="imm-hero-grid">
        <div>
          <span class="imm-hero-pill">
            <i class="fa-solid fa-building-shield"></i>
            Immobilier, gestion locative, assistance foncière
          </span>

          <h1 class="imm-hero-title">
            Immobilier IBIG IMMO TRUST : mieux gérer, mieux sécuriser et mieux valoriser votre patrimoine.
          </h1>

          <p class="imm-hero-text">
            Nous accompagnons les propriétaires, investisseurs et membres de la diaspora
            sur la gestion locative, le loyer garanti, la mise en location, la sécurisation foncière
            et les solutions de financement immobilier.
          </p>

          <div class="imm-actions">
            <a href="<?= e(url('contact.php')) ?>" class="imm-button">Parler à un conseiller</a>
            <a href="<?= e(url('rdv.php')) ?>" class="imm-button--outline">Prendre rendez-vous</a>
          </div>

          <div class="imm-metrics">
            <div class="imm-metric">
              <strong>360°</strong>
              <span>lecture du patrimoine</span>
            </div>
            <div class="imm-metric">
              <strong>24/7</strong>
              <span>suivi diaspora</span>
            </div>
            <div class="imm-metric">
              <strong>10</strong>
              <span>leviers de sécurisation</span>
            </div>
          </div>

          <div class="imm-hero-note">
            <i class="fa-solid fa-circle-info"></i>
            Une approche qui relie exploitation locative, sécurité documentaire et valorisation.
          </div>
        </div>

        <aside class="imm-hero-card">
          <div class="imm-hero-card__media">
            <img src="<?= e(url('assets/img/hero-immo-afrique3.jpg')) ?>" alt="Immobilier IBIG IMMO TRUST">
          </div>
          <div class="imm-hero-card__body">
            <h2 class="imm-hero-card__title">Une logique orientée revenus, sécurité et maîtrise.</h2>
            <p class="imm-hero-card__text">
              Que vous cherchiez à louer, sécuriser, faire vérifier un terrain ou préparer un financement,
              la lecture du projet reste structurée et pilotable.
            </p>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <section class="imm-section">
    <div class="imm-shell">
      <div class="imm-section-head">
        <div>
          <h2 class="imm-title">Nos services immobiliers</h2>
          <p class="imm-intro">
            Une offre pensée pour la gestion complète, la sécurisation et la valorisation des biens immobiliers en Côte d'Ivoire.
          </p>
        </div>
      </div>

      <div class="imm-grid-4">
        <?php foreach ($serviceCards as $card): ?>
          <article class="imm-card">
            <div class="imm-card__media">
              <img src="<?= e($card['image']) ?>" alt="<?= e($card['title']) ?>" loading="lazy" decoding="async">
              <span class="imm-card__tag"><?= e($card['tag']) ?></span>
            </div>
            <div class="imm-card__body">
              <h3><?= e($card['title']) ?></h3>
              <p><?= e($card['text']) ?></p>
              <div class="imm-card__footer">
                <a href="<?= e($card['url']) ?>" class="imm-button--ghost"><?= e($card['cta']) ?></a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="imm-section imm-section--soft">
    <div class="imm-shell imm-split">
      <div class="imm-split-card">
        <img src="<?= e(url('assets/img/reporting-digital.jpg')) ?>" alt="Gestion locative IBIG IMMO TRUST">
        <div class="imm-split-card__body">
          <h3>Gestion locative et loyer garanti</h3>
          <p>
            Une solution plus moderne pour stabiliser les revenus locatifs, améliorer le suivi du bien
            et garder une lecture claire de l'exploitation, y compris à distance.
          </p>
        </div>
      </div>

      <div>
        <div class="imm-section-head" style="margin-bottom:20px;">
          <div>
            <h2 class="imm-title">Ce que couvre la gestion immobilière</h2>
            <p class="imm-intro">
              Nous structurons la relation locative, le suivi du bien et l'information utile au propriétaire.
            </p>
          </div>
        </div>

        <div class="imm-grid-4">
          <?php foreach ($managementPillars as $pillar): ?>
            <article class="imm-mini-card">
              <div class="imm-mini-card__icon"><i class="<?= e($pillar['icon']) ?>"></i></div>
              <h3><?= e($pillar['title']) ?></h3>
              <p><?= e($pillar['text']) ?></p>
            </article>
          <?php endforeach; ?>
        </div>

        <div class="imm-actions" style="margin-top:24px;">
          <a href="<?= e(url('loyer-garanti.php')) ?>" class="imm-button">Découvrir le loyer garanti</a>
          <a href="<?= e(url('rdv.php')) ?>" class="imm-button--light">Échanger avec un conseiller</a>
        </div>
      </div>
    </div>
  </section>

  <section class="imm-section">
    <div class="imm-shell">
      <div class="imm-section-head">
        <div>
          <h2 class="imm-title">Sécurisation foncière</h2>
          <p class="imm-intro">
            Avant un achat ou un paiement important, nous aidons à mieux lire les documents, le terrain et le niveau réel de sécurité du dossier.
          </p>
        </div>
      </div>

      <div class="imm-grid-4">
        <?php foreach ($foncierChecks as $check): ?>
          <article class="imm-mini-card">
            <h3><?= e($check['title']) ?></h3>
            <p><?= e($check['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="imm-actions" style="margin-top:24px;">
        <a href="<?= e(url('assistance-fonciere.php')) ?>" class="imm-button">Assistance foncière complète</a>
        <a href="<?= e(url('contact.php')) ?>" class="imm-button--ghost">Soumettre un dossier</a>
      </div>
    </div>
  </section>

  <section class="imm-section imm-section--soft">
    <div class="imm-shell">
      <div class="imm-section-head">
        <div>
          <h2 class="imm-title">Pour qui cette offre immobilière est-elle utile ?</h2>
          <p class="imm-intro">
            L'accompagnement est pensé pour différents profils, avec une même exigence de lisibilité et de résultat.
          </p>
        </div>
      </div>

      <div class="imm-grid-3">
        <?php foreach ($audiences as $audience): ?>
          <article class="imm-audience-card">
            <h3><?= e($audience['title']) ?></h3>
            <p><?= e($audience['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="imm-section">
    <div class="imm-shell imm-split">
      <div>
        <div class="imm-section-head" style="margin-bottom:20px;">
          <div>
            <h2 class="imm-title">Pourquoi cette approche est plus cohérente</h2>
            <p class="imm-intro">
              L'immobilier n'est pas traité comme un simple catalogue, mais comme un ensemble de décisions à sécuriser et à faire évoluer.
            </p>
          </div>
        </div>

        <ul class="imm-list">
          <?php foreach ($advantages as $item): ?>
            <li><?= e($item) ?></li>
          <?php endforeach; ?>
        </ul>

        <div class="imm-actions" style="margin-top:24px;">
          <a href="<?= e(url('tous_les_biens.php')) ?>" class="imm-button--dark">Voir les biens disponibles</a>
          <a href="<?= e(url('financement.php')) ?>" class="imm-button--light">Explorer le financement</a>
        </div>
      </div>

      <div class="imm-split-card">
        <img src="<?= e(url('assets/img/proprietaire-relax.jpg')) ?>" alt="Propriétaire accompagné par IBIG IMMO TRUST">
        <div class="imm-split-card__body">
          <h3>Une meilleure lecture du patrimoine immobilier</h3>
          <p>
            Notre rôle est aussi de rendre le projet plus clair : que faut-il sécuriser, que faut-il mettre en location,
            que faut-il valoriser et quelle stratégie est la plus cohérente ?
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="imm-section imm-section--soft">
    <div class="imm-shell">
      <div class="imm-section-head">
        <div>
          <h2 class="imm-title">Un parcours plus lisible</h2>
          <p class="imm-intro">
            Chaque projet immobilier mérite un chemin clair, avec des étapes simples à comprendre et à piloter.
          </p>
        </div>
      </div>

      <div class="imm-grid-4">
        <article class="imm-process-card">
          <div class="imm-process-card__number">1</div>
          <h3>Comprendre le besoin</h3>
          <p>Lecture du bien, du projet, du niveau d'urgence et du résultat recherché.</p>
        </article>

        <article class="imm-process-card">
          <div class="imm-process-card__number">2</div>
          <h3>Qualifier le dossier</h3>
          <p>Documents, situation locative, potentiel, contraintes et points de vigilance.</p>
        </article>

        <article class="imm-process-card">
          <div class="imm-process-card__number">3</div>
          <h3>Mettre en œuvre</h3>
          <p>Gestion, accompagnement, vérification, orientation ou mobilisation du bon service.</p>
        </article>

        <article class="imm-process-card">
          <div class="imm-process-card__number">4</div>
          <h3>Suivre et valoriser</h3>
          <p>Exploitation, reporting, sécurisation et amélioration continue de la valeur du bien.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="imm-section">
    <div class="imm-shell">
      <div class="imm-section-head" style="justify-content:center;text-align:center;">
        <div>
          <h2 class="imm-title">Questions fréquentes</h2>
          <p class="imm-intro" style="margin:0 auto;">
            Quelques réponses rapides sur l'immobilier, la gestion locative et l'assistance foncière.
          </p>
        </div>
      </div>

      <div class="imm-faq-grid">
        <?php foreach ($faqs as $faq): ?>
          <details class="imm-faq">
            <summary><?= e($faq['q']) ?></summary>
            <div class="imm-faq__content"><?= e($faq['a']) ?></div>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="imm-section imm-section--cta">
    <div class="imm-shell imm-cta-inner">
      <div>
        <h2 class="imm-title">Vous avez un projet immobilier à structurer ?</h2>
        <p class="imm-intro">Nos équipes vous accompagnent sur la gestion, la sécurité foncière, la mise en location et le financement.</p>
      </div>

      <div class="imm-actions">
        <a href="<?= e(url('contact.php')) ?>" class="imm-button">Décrire mon projet</a>
        <a href="<?= e(url('rdv.php')) ?>" class="imm-button--light">Prendre rendez-vous</a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
