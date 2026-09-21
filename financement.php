<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/tracker.php';

$currentPage = 'financement';
$disableHeaderSlider = true;
$pageTitle = "Financement immobilier - IBIG IMMO TRUST";
$metaDescription = "Solutions de financement immobilier, investisseurs agréés, modèle Direct IBIG et accompagnement bancaire en Côte d'Ivoire.";
$canonicalUrl = url('financement.php');

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

$heroMetrics = [
    ['value' => '3', 'label' => 'modèles de financement'],
    ['value' => '0 à 30 %', 'label' => "apport selon le montage"],
    ['value' => '24/7', 'label' => 'suivi diaspora'],
];

$fundingModels = [
    [
        'tag' => 'Sans avance',
        'title' => 'Modèle Direct IBIG',
        'subtitle' => 'Pour certains projets éligibles',
        'text' => "IBIG peut porter l'exécution du projet puis organiser un remboursement progressif via l'exploitation future du bien.",
        'points' => [
            "Approche pensée pour la finition, la rénovation ou certains montages ciblés.",
            "Lecture orientée rentabilité, occupation et capacité réelle de remboursement.",
            "Cadre utile quand le bien a un bon potentiel locatif ou commercial.",
        ],
        'url' => url('direct-ibig.php'),
        'cta' => 'Voir le modèle',
    ],
    [
        'tag' => "10 à 20 % d'apport",
        'title' => 'Investisseurs agréés',
        'subtitle' => 'Co-financement structuré',
        'text' => "Des investisseurs partenaires peuvent intervenir pour financer une partie du projet selon sa solidité, sa destination et son rendement attendu.",
        'points' => [
            "Mobilisation d'un partenaire financier selon le niveau de risque du projet.",
            "Remboursement généralement appuyé sur les revenus futurs du bien.",
            "Intéressant pour les projets à potentiel mais nécessitant un apport complémentaire.",
        ],
        'url' => url('investisseurs-agrees.php'),
        'cta' => 'Découvrir',
    ],
    [
        'tag' => "0 à 30 % d'apport",
        'title' => 'Banques et microfinances',
        'subtitle' => 'Montage et accompagnement',
        'text' => "Nous aidons à structurer le dossier, à sécuriser le plan d'exécution et à encadrer l'utilisation des fonds débloqués.",
        'points' => [
            "Préparation du dossier et lecture de faisabilité avant soumission.",
            "Coordination entre client, institution financière et terrain.",
            "Suivi de chantier pour mieux protéger les fonds investis.",
        ],
        'url' => url('banques-microfinance.php'),
        'cta' => 'En savoir plus',
    ],
];

$advantages = [
    [
        'icon' => 'fa-solid fa-magnifying-glass-chart',
        'title' => 'Étude de faisabilité',
        'text' => "Nous analysons le terrain, le bien, le budget, les travaux et le potentiel d'exploitation avant de recommander un montage.",
    ],
    [
        'icon' => 'fa-solid fa-sliders',
        'title' => 'Solution adaptée au profil',
        'text' => "Propriétaire, investisseur, diaspora ou porteur de projet : le bon modèle dépend de votre capacité d'apport et du niveau de maturité du dossier.",
    ],
    [
        'icon' => 'fa-solid fa-building-columns',
        'title' => 'Cadre contractuel plus clair',
        'text' => "Nous cherchons à mieux structurer les responsabilités, les décaissements et les validations importantes.",
    ],
    [
        'icon' => 'fa-solid fa-chart-line',
        'title' => 'Vision rentabilité',
        'text' => "Le financement est lu en lien avec la valorisation future, la mise en location, la revente ou l'exploitation commerciale.",
    ],
    [
        'icon' => 'fa-solid fa-mobile-screen-button',
        'title' => 'Suivi digital diaspora',
        'text' => "Photos, vidéos, reporting, points d'étape et coordination à distance pour garder une visibilité concrète sur le projet.",
    ],
    [
        'icon' => 'fa-solid fa-shield-halved',
        'title' => 'Meilleure maîtrise du risque',
        'text' => "Le cadrage financier est relié au terrain, à l'exécution et à la cohérence globale du projet.",
    ],
];

$processSteps = [
    [
        'number' => '1',
        'title' => 'Diagnostic du projet',
        'text' => "Lecture du bien, du terrain, du budget, du besoin réel et du potentiel locatif ou commercial.",
    ],
    [
        'number' => '2',
        'title' => 'Choix du montage',
        'text' => "Sélection du modèle le plus cohérent : Direct IBIG, investisseurs agréés ou financement institutionnel.",
    ],
    [
        'number' => '3',
        'title' => 'Cadrage et contrat',
        'text' => "Définition du périmètre, des engagements, du planning, du financement et des conditions de suivi.",
    ],
    [
        'number' => '4',
        'title' => 'Exécution encadrée',
        'text' => "Travaux, finition, rénovation ou construction avec contrôle des étapes clés et visibilité progressive.",
    ],
    [
        'number' => '5',
        'title' => 'Valorisation du bien',
        'text' => "Mise en location, exploitation ou suivi post-travaux selon la stratégie retenue pour le remboursement ou la rentabilité.",
    ],
];

$profiles = [
    [
        'title' => 'Propriétaires avec chantier bloqué',
        'text' => "Pour relancer un bien inachevé, financer les travaux manquants et rendre le projet exploitable.",
    ],
    [
        'title' => 'Diaspora et investisseurs',
        'text' => "Pour structurer un projet à distance avec un cadre plus lisible sur le financement et l'exécution.",
    ],
    [
        'title' => 'Propriétaires sans apport immédiat',
        'text' => "Pour étudier si le potentiel du bien permet un modèle plus souple orienté exploitation future.",
    ],
    [
        'title' => 'Clients bancaires ou microfinance',
        'text' => "Pour mieux préparer le dossier, rassurer l'institution et piloter correctement les fonds débloqués.",
    ],
];

$faqs = [
    [
        'q' => "Peut-on financer un projet sans apport personnel ?",
        'a' => "Cela dépend du type de projet, de sa localisation, de son potentiel d'exploitation et du niveau de risque. Le modèle Direct IBIG n'est pas automatique : il nécessite une vraie étude.",
    ],
    [
        'q' => "Le remboursement peut-il se faire avec les loyers ?",
        'a' => "Oui, dans certains montages structurés. Cela suppose que le bien puisse être livré, mis en location dans de bonnes conditions et générer des revenus cohérents.",
    ],
    [
        'q' => "Intervenez-vous seulement sur le financement ou aussi sur les travaux ?",
        'a' => "Nous pouvons intervenir sur les deux. L'intérêt de l'approche IBIG est justement de relier financement, exécution, contrôle et valorisation du bien.",
    ],
    [
        'q' => "Je vis à l'étranger, puis-je suivre le projet facilement ?",
        'a' => "Oui. Un suivi adapté à la diaspora peut être mis en place avec reporting, visios, documents, visuels et arbitrages à distance.",
    ],
    [
        'q' => "Quels éléments faut-il préparer pour faire étudier un dossier ?",
        'a' => "La localisation du bien, quelques photos, les documents disponibles, le niveau d'avancement, le budget estimatif et l'objectif final du projet sont de très bons points de départ.",
    ],
];

include __DIR__ . '/includes/header.php';
?>

<style>
:root {
  --fin-blue:#0A1628;
  --fin-blue-dark:#092a65;
  --fin-red:#D4AF37;
  --fin-gold:#f4bc49;
  --fin-ink:#101828;
  --fin-ink-soft:#1d2939;
  --fin-muted:#667085;
  --fin-line:#e4e7ec;
  --fin-surface:#ffffff;
  --fin-surface-soft:#f8fafc;
  --fin-surface-warm:#fff8f1;
  --fin-shadow-sm:0 10px 28px rgba(15, 23, 42, 0.06);
  --fin-shadow-md:0 20px 54px rgba(15, 23, 42, 0.10);
  --fin-shadow-lg:0 32px 76px rgba(9, 42, 101, 0.18);
  --fin-radius-sm:16px;
  --fin-radius-md:22px;
  --fin-radius-lg:30px;
  --fin-shell:1240px;
}

.fin-page {
  color:var(--fin-ink);
  background:#fff;
}

.fin-shell {
  width:min(var(--fin-shell), calc(100% - 32px));
  margin:0 auto;
}

.fin-section {
  padding:84px 0;
}

.fin-section--soft {
  background:
    radial-gradient(circle at top right, rgba(244,188,73,.12), transparent 22%),
    linear-gradient(180deg, #fffaf4 0%, #ffffff 100%);
}

.fin-section--cta {
  background:
    radial-gradient(circle at top left, rgba(255,255,255,.08), transparent 20%),
    linear-gradient(135deg, #092a65 0%, #0A1628 48%, #D4AF37 100%);
  color:#fff;
}

.fin-head {
  display:flex;
  justify-content:space-between;
  align-items:end;
  gap:18px;
  flex-wrap:wrap;
  margin-bottom:28px;
}

.fin-title {
  margin:0 0 10px;
  font-size:clamp(20px, 2vw, 30px);
  line-height:1.08;
  letter-spacing:-0.03em;
  color:var(--fin-ink);
  font-weight:800;
}

.fin-section--cta .fin-title {
  color:#fff;
}

.fin-intro {
  max-width:820px;
  margin:0;
  color:var(--fin-muted);
  font-size:17px;
}

.fin-section--cta .fin-intro {
  color:rgba(255,255,255,.92);
}

.fin-hero {
  position:relative;
  overflow:hidden;
  padding:34px 0 44px;
  background:
    radial-gradient(circle at 10% 10%, rgba(244,188,73,.18), transparent 24%),
    radial-gradient(circle at 90% 18%, rgba(212,175,55,.18), transparent 22%),
    linear-gradient(135deg, #092a65 0%, #0A1628 52%, #D4AF37 100%);
  color:#fff;
}

.fin-hero::before {
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

.fin-hero-grid {
  position:relative;
  z-index:1;
  display:grid;
  grid-template-columns:minmax(0, 1.04fr) minmax(320px, .96fr);
  gap:34px;
  align-items:center;
}

.fin-pill {
  display:inline-flex;
  align-items:center;
  gap:10px;
  min-height:38px;
  padding:0 14px;
  border-radius:999px;
  background:rgba(255,255,255,.14);
  border:1px solid rgba(255,255,255,.20);
  font-size:13px;
  font-weight:700;
}

.fin-pill i {
  color:#ffdb7c;
}

.fin-hero-title {
  margin:18px 0 16px;
  font-size:clamp(26px, 3vw, 44px);
  line-height:1.03;
  letter-spacing:-0.04em;
  color:#fff;
  font-weight:820;
}

.fin-hero-text {
  margin:0;
  max-width:760px;
  color:rgba(255,255,255,.92);
  font-size:18px;
}

.fin-actions {
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  margin-top:24px;
}

.fin-button,
.fin-button--outline,
.fin-button--ghost,
.fin-button--light,
.fin-button--dark {
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

.fin-button:hover,
.fin-button--outline:hover,
.fin-button--ghost:hover,
.fin-button--light:hover,
.fin-button--dark:hover {
  transform:translateY(-2px);
}

.fin-button {
  background:linear-gradient(135deg, var(--fin-gold) 0%, #ffd878 100%);
  color:#2b1900;
  box-shadow:0 16px 36px rgba(244,188,73,.24);
}

.fin-button--outline {
  background:rgba(255,255,255,.10);
  color:#fff;
  border-color:rgba(255,255,255,.24);
}

.fin-button--ghost {
  background:#fff4ef;
  color:var(--fin-red);
  border-color:rgba(212,175,55,.10);
}

.fin-button--light {
  background:#fff;
  color:var(--fin-blue-dark);
  border-color:#d8e3f0;
}

.fin-button--dark {
  background:var(--fin-ink);
  color:#fff;
}

.fin-metrics {
  display:grid;
  grid-template-columns:repeat(3, minmax(0, 1fr));
  gap:12px;
  margin-top:26px;
}

.fin-metric {
  padding:16px 14px;
  border-radius:18px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.14);
  backdrop-filter:blur(8px);
}

.fin-metric strong {
  display:block;
  margin-bottom:4px;
  font-size:26px;
  line-height:1;
  font-weight:800;
}

.fin-metric span {
  color:rgba(255,255,255,.84);
  font-size:13px;
  font-weight:650;
}

.fin-note {
  display:inline-flex;
  align-items:center;
  gap:8px;
  margin-top:18px;
  color:rgba(255,255,255,.92);
  font-size:13px;
  font-weight:650;
}

.fin-hero-card {
  overflow:hidden;
  border-radius:28px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.18);
  box-shadow:var(--fin-shadow-lg);
  backdrop-filter:blur(8px);
}

.fin-hero-card__media img {
  width:100%;
  height:520px;
  object-fit:cover;
}

.fin-hero-card__body {
  padding:22px;
}

.fin-hero-card__title {
  margin:0 0 10px;
  color:#fff;
  font-size:28px;
  line-height:1.08;
  font-weight:800;
}

.fin-hero-card__text {
  margin:0 0 18px;
  color:rgba(255,255,255,.88);
  font-size:15px;
}

.fin-hero-card__list {
  margin:0;
  padding:0;
  list-style:none;
  display:grid;
  gap:10px;
}

.fin-hero-card__list li {
  display:flex;
  gap:10px;
  align-items:flex-start;
  color:#fff;
  font-size:14px;
  font-weight:650;
}

.fin-hero-card__list li::before {
  content:"";
  width:10px;
  height:10px;
  min-width:10px;
  margin-top:6px;
  border-radius:999px;
  background:#ffd878;
}

.fin-grid-3 {
  display:grid;
  grid-template-columns:repeat(3, minmax(0, 1fr));
  gap:22px;
}

.fin-grid-4 {
  display:grid;
  grid-template-columns:repeat(4, minmax(0, 1fr));
  gap:18px;
}

.fin-grid-5 {
  display:grid;
  grid-template-columns:repeat(5, minmax(0, 1fr));
  gap:18px;
}

.fin-card,
.fin-feature,
.fin-step,
.fin-profile,
.fin-faq,
.fin-split-card {
  background:var(--fin-surface);
  border:1px solid var(--fin-line);
  border-radius:22px;
  box-shadow:var(--fin-shadow-sm);
}

.fin-card {
  overflow:hidden;
  display:flex;
  flex-direction:column;
}

.fin-card__body {
  display:flex;
  flex-direction:column;
  gap:12px;
  padding:22px;
  height:100%;
}

.fin-tag {
  display:inline-flex;
  align-items:center;
  width:max-content;
  min-height:34px;
  padding:0 12px;
  border-radius:999px;
  background:#fff4ef;
  color:var(--fin-red);
  font-size:12px;
  font-weight:760;
}

.fin-subtag {
  color:var(--fin-blue);
  font-size:14px;
  font-weight:700;
}

.fin-card h3,
.fin-feature h3,
.fin-step h3,
.fin-profile h3,
.fin-split-card h3 {
  margin:0;
  color:var(--fin-ink);
  font-size:22px;
  line-height:1.15;
  font-weight:790;
}

.fin-card p,
.fin-feature p,
.fin-step p,
.fin-profile p,
.fin-split-card p {
  margin:0;
  color:var(--fin-muted);
  font-size:15px;
}

.fin-list {
  margin:0;
  padding:0;
  list-style:none;
  display:grid;
  gap:10px;
}

.fin-list li {
  display:flex;
  gap:10px;
  align-items:flex-start;
  color:var(--fin-ink-soft);
  font-size:14px;
  font-weight:650;
}

.fin-list li::before {
  content:"";
  width:18px;
  height:18px;
  min-width:18px;
  margin-top:2px;
  border-radius:999px;
  background:linear-gradient(135deg, rgba(10,22,40,.12), rgba(212,175,55,.12));
  box-shadow:inset 0 0 0 5px rgba(10,22,40,.14);
}

.fin-card__footer {
  margin-top:auto;
  padding-top:6px;
}

.fin-feature,
.fin-profile {
  padding:24px 22px;
}

.fin-feature__icon {
  width:56px;
  height:56px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:18px;
  background:linear-gradient(135deg, rgba(10,22,40,.08), rgba(212,175,55,.12));
  color:var(--fin-blue);
  font-size:22px;
  margin-bottom:14px;
}

.fin-step {
  position:relative;
  overflow:hidden;
  padding:24px 22px;
}

.fin-step::before {
  content:"";
  position:absolute;
  top:0;
  left:0;
  width:100%;
  height:4px;
  background:linear-gradient(90deg, var(--fin-blue), var(--fin-red));
}

.fin-step__number {
  width:54px;
  height:54px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:18px;
  background:linear-gradient(135deg, var(--fin-blue), var(--fin-red));
  color:#fff;
  font-size:22px;
  font-weight:820;
  box-shadow:0 14px 28px rgba(10,22,40,.20);
  margin-bottom:16px;
}

.fin-split {
  display:grid;
  grid-template-columns:minmax(0, .92fr) minmax(0, 1.08fr);
  gap:32px;
  align-items:center;
}

.fin-split-card {
  overflow:hidden;
}

.fin-split-card img {
  width:100%;
  height:520px;
  object-fit:cover;
}

.fin-split-card__body {
  padding:24px;
}

.fin-bullets {
  margin:20px 0 0;
  padding:0;
  list-style:none;
  display:grid;
  gap:12px;
}

.fin-bullets li {
  display:flex;
  gap:12px;
  align-items:flex-start;
  color:var(--fin-ink);
  font-size:15px;
  font-weight:650;
}

.fin-bullets li::before {
  content:"";
  width:24px;
  height:24px;
  min-width:24px;
  margin-top:2px;
  border-radius:999px;
  background:linear-gradient(135deg, rgba(10,22,40,.12), rgba(212,175,55,.12));
  box-shadow:inset 0 0 0 6px rgba(10,22,40,.14);
}

.fin-faq-grid {
  display:grid;
  gap:14px;
  max-width:920px;
  margin:0 auto;
}

.fin-faq {
  overflow:hidden;
}

.fin-faq summary {
  list-style:none;
  cursor:pointer;
  position:relative;
  padding:20px 22px;
  padding-right:56px;
  font-size:17px;
  font-weight:760;
}

.fin-faq summary::-webkit-details-marker {
  display:none;
}

.fin-faq summary::after {
  content:"+";
  position:absolute;
  right:22px;
  top:50%;
  transform:translateY(-50%);
  color:var(--fin-red);
  font-size:28px;
  font-weight:300;
}

.fin-faq[open] summary::after {
  content:"−";
}

.fin-faq__content {
  padding:0 22px 20px;
  color:var(--fin-muted);
  font-size:15px;
}

.fin-cta-inner {
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:22px;
  flex-wrap:wrap;
}

@media (max-width: 1180px) {
  .fin-hero-grid,
  .fin-split {
    grid-template-columns:1fr;
  }

  .fin-grid-5,
  .fin-grid-4 {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }

  .fin-grid-3 {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 760px) {
  .fin-shell {
    width:min(var(--fin-shell), calc(100% - 22px));
  }

  .fin-section {
    padding:68px 0;
  }

  .fin-hero {
    padding:24px 0 32px;
  }

  .fin-hero-title {
    font-size:clamp(20px, 2.2vw, 32px);
  }

  .fin-hero-text,
  .fin-intro {
    font-size:16px;
  }

  .fin-grid-5,
  .fin-grid-4,
  .fin-grid-3,
  .fin-metrics {
    grid-template-columns:1fr;
  }

  .fin-actions,
  .fin-cta-inner {
    flex-direction:column;
    align-items:stretch;
  }

  .fin-button,
  .fin-button--outline,
  .fin-button--ghost,
  .fin-button--light,
  .fin-button--dark {
    width:100%;
  }

  .fin-hero-card__media img,
  .fin-split-card img {
    height:360px;
  }
}
</style>

<main class="fin-page">
  <section class="fin-hero">
    <div class="fin-shell">
      <div class="fin-hero-grid">
        <div>
          <span class="fin-pill">
            <i class="fa-solid fa-sack-dollar"></i>
            Financement, investisseurs, banques, montage de projet
          </span>

          <h1 class="fin-hero-title">
            Financement immobilier : plusieurs solutions structurées selon votre profil et le potentiel du projet.
          </h1>

          <p class="fin-hero-text">
            Que vous disposiez ou non d'un apport immédiat, IBIG IMMO TRUST peut vous aider à cadrer
            un financement pour des travaux, une finition, une rénovation, un chantier inachevé
            ou une construction à valoriser.
          </p>

          <div class="fin-actions">
            <a href="<?= e(url('contact.php')) ?>" class="fin-button">Décrire mon projet</a>
            <a href="<?= e(url('rdv.php')) ?>" class="fin-button--outline">Prendre rendez-vous</a>
          </div>

          <div class="fin-metrics">
            <?php foreach ($heroMetrics as $metric): ?>
              <div class="fin-metric">
                <strong><?= e($metric['value']) ?></strong>
                <span><?= e($metric['label']) ?></span>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="fin-note">
            <i class="fa-solid fa-circle-info"></i>
            Lecture financière, exécution terrain et valorisation future sont pensées ensemble.
          </div>
        </div>

        <aside class="fin-hero-card">
          <div class="fin-hero-card__media">
            <img src="<?= e(url('assets/img/financement-ibig.jpg')) ?>" alt="Financement immobilier IBIG IMMO TRUST">
          </div>
          <div class="fin-hero-card__body">
            <h2 class="fin-hero-card__title">Une logique de financement reliée à la réalité du bien.</h2>
            <p class="fin-hero-card__text">
              Nous ne cherchons pas seulement à financer. Nous cherchons à rendre le projet plus viable, plus lisible et mieux exploitable.
            </p>
            <ul class="fin-hero-card__list">
              <li>Étude de faisabilité avant orientation vers un modèle.</li>
              <li>Lecture du risque, du calendrier et de la rentabilité future.</li>
              <li>Suivi utile pour les projets sur place comme à distance.</li>
            </ul>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <section class="fin-section">
    <div class="fin-shell">
      <div class="fin-head">
        <div>
          <h2 class="fin-title">Nos modèles de financement</h2>
          <p class="fin-intro">
            Trois grandes portes d'entrée permettent d'adapter le montage à votre capacité d'apport, au potentiel du bien et à l'objectif final du projet.
          </p>
        </div>
      </div>

      <div class="fin-grid-3">
        <?php foreach ($fundingModels as $model): ?>
          <article class="fin-card">
            <div class="fin-card__body">
              <span class="fin-tag"><?= e($model['tag']) ?></span>
              <h3><?= e($model['title']) ?></h3>
              <div class="fin-subtag"><?= e($model['subtitle']) ?></div>
              <p><?= e($model['text']) ?></p>
              <ul class="fin-list">
                <?php foreach ($model['points'] as $point): ?>
                  <li><?= e($point) ?></li>
                <?php endforeach; ?>
              </ul>
              <div class="fin-card__footer">
                <a href="<?= e($model['url']) ?>" class="fin-button--ghost"><?= e($model['cta']) ?></a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="fin-section fin-section--soft">
    <div class="fin-shell">
      <div class="fin-head">
        <div>
          <h2 class="fin-title">Pourquoi choisir IBIG pour financer votre projet ?</h2>
          <p class="fin-intro">
            L'intérêt de l'approche IBIG est de relier financement, terrain, exécution et exploitation future du bien dans une seule lecture cohérente.
          </p>
        </div>
      </div>

      <div class="fin-grid-3">
        <?php foreach ($advantages as $advantage): ?>
          <article class="fin-feature">
            <div class="fin-feature__icon"><i class="<?= e($advantage['icon']) ?>"></i></div>
            <h3><?= e($advantage['title']) ?></h3>
            <p><?= e($advantage['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="fin-section">
    <div class="fin-shell">
      <div class="fin-head">
        <div>
          <h2 class="fin-title">Comment fonctionne le financement IBIG ?</h2>
          <p class="fin-intro">
            Le processus vise à réduire les zones floues et à donner une meilleure lisibilité au client avant, pendant et après le financement.
          </p>
        </div>
      </div>

      <div class="fin-grid-5">
        <?php foreach ($processSteps as $step): ?>
          <article class="fin-step">
            <div class="fin-step__number"><?= e($step['number']) ?></div>
            <h3><?= e($step['title']) ?></h3>
            <p><?= e($step['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="fin-section fin-section--soft">
    <div class="fin-shell fin-split">
      <div class="fin-split-card">
        <img src="<?= e(url('assets/img/investisseurs-afrique.jpg')) ?>" alt="Investisseurs et financement immobilier IBIG IMMO TRUST">
        <div class="fin-split-card__body">
          <h3>Un financement pensé pour débloquer et valoriser</h3>
          <p>
            Le bon montage n'est pas seulement celui qui apporte des fonds. C'est celui qui aide réellement à terminer, exploiter et rentabiliser le bien.
          </p>
        </div>
      </div>

      <div>
        <div class="fin-head" style="margin-bottom:20px;">
          <div>
            <h2 class="fin-title">À qui s'adresse cette offre ?</h2>
            <p class="fin-intro">
              Nous adaptons l'accompagnement au profil du client, au niveau de maturité du projet et au type de résultat recherché.
            </p>
          </div>
        </div>

        <div class="fin-grid-4">
          <?php foreach ($profiles as $profile): ?>
            <article class="fin-profile">
              <h3><?= e($profile['title']) ?></h3>
              <p><?= e($profile['text']) ?></p>
            </article>
          <?php endforeach; ?>
        </div>

        <ul class="fin-bullets">
          <li>Le projet est lu à la fois sous l'angle financier et sous l'angle opérationnel.</li>
          <li>Le suivi peut aller jusqu'à la livraison, la mise en location ou l'exploitation.</li>
          <li>La diaspora garde une visibilité plus concrète sur les décisions importantes.</li>
        </ul>
      </div>
    </div>
  </section>

  <section class="fin-section">
    <div class="fin-shell">
      <div class="fin-head" style="justify-content:center;text-align:center;">
        <div>
          <h2 class="fin-title">Questions fréquentes</h2>
          <p class="fin-intro" style="margin:0 auto;">
            Quelques réponses utiles pour mieux comprendre la logique de financement avant de soumettre votre projet.
          </p>
        </div>
      </div>

      <div class="fin-faq-grid">
        <?php foreach ($faqs as $faq): ?>
          <details class="fin-faq">
            <summary><?= e($faq['q']) ?></summary>
            <div class="fin-faq__content"><?= e($faq['a']) ?></div>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="fin-section fin-section--cta">
    <div class="fin-shell fin-cta-inner">
      <div>
        <h2 class="fin-title">Votre projet a besoin d'un financement structuré ?</h2>
        <p class="fin-intro">
          Nous pouvons vous aider à clarifier le besoin, choisir le bon modèle et cadrer l'exécution pour donner plus de solidité à votre projet.
        </p>
      </div>

      <div class="fin-actions">
        <a href="<?= e(url('contact.php')) ?>" class="fin-button">Soumettre mon projet</a>
        <a href="<?= e(url('rdv.php')) ?>" class="fin-button--light">Parler à un conseiller</a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
