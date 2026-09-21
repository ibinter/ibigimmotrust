<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/tracker.php';

$currentPage = 'btp';
$disableHeaderSlider = true;
$pageTitle = "BTP - Construction, finition et rénovation - IBIG IMMO TRUST";
$metaDescription = "Construction, finition, rénovation, reprise de chantiers inachevés et suivi diaspora avec IBIG IMMO TRUST en Côte d'Ivoire.";
$canonicalUrl = url('btp.php');

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

$heroMetrics = [
    ['value' => 'A à Z', 'label' => 'pilotage chantier'],
    ['value' => '24/7', 'label' => 'suivi diaspora'],
    ['value' => '5', 'label' => 'phases de contrôle'],
];

$serviceCards = [
    [
        'image' => url('assets/img/chantier-inacheve.jpg'),
        'tag' => 'Reprise',
        'title' => 'Chantiers inachevés',
        'text' => "Diagnostic technique, relance des travaux, correction des malfaçons et finition avec un cadre plus lisible.",
        'url' => url('chantier-inacheve.php'),
        'cta' => 'Voir le service',
    ],
    [
        'image' => url('assets/img/construction-afrique.jpg'),
        'tag' => 'Neuf',
        'title' => 'Construction neuve',
        'text' => "Maisons, villas, immeubles, locaux et programmes sur mesure avec une coordination globale du terrain à la livraison.",
        'url' => url('construction.php'),
        'cta' => 'Découvrir',
    ],
    [
        'image' => url('assets/img/renovation-afrique.jpg'),
        'tag' => 'Valorisation',
        'title' => 'Rénovation et modernisation',
        'text' => "Réhabilitation, repositionnement esthétique, amélioration technique et montée en valeur pour mieux louer ou vendre.",
        'url' => url('renovation.php'),
        'cta' => 'Voir la rénovation',
    ],
    [
        'image' => url('assets/img/renovation-btp.jpg'),
        'tag' => 'Finitions',
        'title' => 'Finitions et décoration',
        'text' => "Peinture, carrelage, menuiserie, plafonds, éclairage, sanitaires et mise en cohérence finale du bien.",
        'url' => url('renovation.php'),
        'cta' => 'Nos finitions',
    ],
    [
        'image' => url('assets/img/assistance-technique.jpg'),
        'tag' => 'Contrôle',
        'title' => 'Assistance technique',
        'text' => "Si vous avez déjà vos équipes, nous pouvons intervenir en supervision, validation technique et contrôle qualité.",
        'url' => url('btp.php#processus-btp'),
        'cta' => 'Voir le processus',
    ],
    [
        'image' => url('assets/img/suivi-diaspora.jpg'),
        'tag' => 'Diaspora',
        'title' => 'Suivi à distance',
        'text' => "Photos, vidéos, points d'étape, documents et arbitrages à distance pour les propriétaires et investisseurs hors du pays.",
        'url' => url('contact.php'),
        'cta' => 'Parler à un conseiller',
    ],
];

$processSteps = [
    [
        'number' => '1',
        'title' => 'Étude et devis',
        'text' => "Visite du site, relevés, analyse des plans, estimation budgétaire et premières variantes techniques.",
    ],
    [
        'number' => '2',
        'title' => 'Cadrage et contrat',
        'text' => "Planning, responsabilités, phasage, modalités de paiement et définition claire du périmètre d'intervention.",
    ],
    [
        'number' => '3',
        'title' => 'Exécution',
        'text' => "Lancement, coordination des corps de métier, approvisionnement et suivi opérationnel du chantier.",
    ],
    [
        'number' => '4',
        'title' => 'Contrôle qualité',
        'text' => "Rapports, visuels, réunions de chantier, validation des étapes sensibles et correction des écarts.",
    ],
    [
        'number' => '5',
        'title' => 'Livraison',
        'text' => "Tests, corrections finales, remise des clés et possibilité de passer ensuite en valorisation ou mise en location.",
    ],
];

$audiences = [
    [
        'title' => 'Propriétaires de terrains',
        'text' => "Pour lancer une construction résidentielle, locative ou mixte avec une meilleure maîtrise du budget et de l'exécution.",
    ],
    [
        'title' => 'Propriétaires de chantiers bloqués',
        'text' => "Pour relancer, restructurer et terminer un projet qui a été interrompu, mal conduit ou mal exécuté.",
    ],
    [
        'title' => 'Investisseurs et diaspora',
        'text' => "Pour construire ou finir un bien à distance sans perdre la main sur les décisions techniques et financières.",
    ],
    [
        'title' => 'Entreprises et porteurs de projet',
        'text' => "Pour des bureaux, commerces, entrepôts, résidences de service ou opérations à vocation professionnelle.",
    ],
];

$engagements = [
    [
        'title' => 'Transparence de lecture',
        'text' => "Devis détaillés, étapes visibles, niveau d'avancement plus lisible et arbitrages mieux documentés.",
    ],
    [
        'title' => 'Paiements phasés',
        'text' => "Décaissements alignés sur l'avancement réel du chantier et les validations intermédiaires importantes.",
    ],
    [
        'title' => 'Réseau opérationnel',
        'text' => "Artisans et intervenants sélectionnés, encadrés et suivis pour limiter les dérives et les surprises.",
    ],
    [
        'title' => 'Suivi diaspora',
        'text' => "Canaux adaptés pour les propriétaires à distance : WhatsApp, visio, visuels et comptes rendus structurés.",
    ],
    [
        'title' => 'Contrôle final',
        'text' => "Vérification des finitions, installations et cohérence globale avant la remise définitive.",
    ],
    [
        'title' => 'Suite immobilière',
        'text' => "Une fois livré, le bien peut entrer dans une logique de valorisation ou de gestion locative selon votre stratégie.",
    ],
];

$advantages = [
    "Un regard qui relie lecture immobilière, exécution BTP et valorisation future du bien.",
    "Une méthode plus rassurante pour les chantiers à distance, les projets complexes ou les reprises délicates.",
    "Une logique d'intervention qui ne se limite pas à construire, mais à livrer un actif exploitable.",
    "Un meilleur passage entre construction, finition, location, exploitation ou revente.",
];

$faqs = [
    [
        'q' => "Vous intervenez seulement à Abidjan ?",
        'a' => "Nous intervenons prioritairement à Abidjan et sa périphérie, avec étude possible d'autres zones selon le type de chantier et la logistique.",
    ],
    [
        'q' => "Comment suivre le chantier si je suis à l'étranger ?",
        'a' => "Le suivi diaspora repose sur des points d'étape, photos, vidéos, visios et validations à distance quand cela est nécessaire.",
    ],
    [
        'q' => "Pouvez-vous travailler avec mes propres artisans ?",
        'a' => "Oui. Nous pouvons intervenir en contrôle, coordination ou assistance technique si vous avez déjà constitué vos équipes.",
    ],
    [
        'q' => "Comment sont organisés les paiements ?",
        'a' => "Les paiements sont généralement structurés par phase, avec lecture de l'avancement et point de situation avant le décaissement suivant.",
    ],
    [
        'q' => "Pouvez-vous reprendre un chantier avec malfaçons ?",
        'a' => "Oui, après diagnostic. Nous analysons ce qui est récupérable, ce qui doit être repris et dans quelles conditions relancer le projet.",
    ],
    [
        'q' => "Que faut-il préparer avant de vous contacter ?",
        'a' => "Idéalement : localisation, photos récentes, plans si disponibles, budget approximatif, délai souhaité et niveau d'avancement actuel.",
    ],
];

include __DIR__ . '/includes/header.php';
?>

<style>
:root {
  --btp-blue:#0A1628;
  --btp-blue-dark:#060d1a;
  --btp-red:#D4AF37;
  --btp-gold:#E8CC6A;
  --btp-ink:#101828;
  --btp-ink-soft:#1d2939;
  --btp-muted:#667085;
  --btp-line:#e4e7ec;
  --btp-surface:#ffffff;
  --btp-surface-soft:#f8fafc;
  --btp-surface-warm:#FAF7F0;
  --btp-shadow-sm:0 10px 28px rgba(15, 23, 42, 0.06);
  --btp-shadow-md:0 20px 54px rgba(15, 23, 42, 0.10);
  --btp-shadow-lg:0 30px 72px rgba(8, 39, 95, 0.18);
  --btp-radius-sm:16px;
  --btp-radius-md:22px;
  --btp-radius-lg:30px;
  --btp-shell:1240px;
}

.btp-page {
  color:var(--btp-ink);
  background:#fff;
}

.btp-shell {
  width:min(var(--btp-shell), calc(100% - 32px));
  margin:0 auto;
}

.btp-section {
  padding:84px 0;
}

.btp-section--soft {
  background:
    radial-gradient(circle at top right, rgba(212,175,55,.12), transparent 22%),
    linear-gradient(180deg, #FAF7F0 0%, #ffffff 100%);
}

.btp-section--cta {
  background:
    radial-gradient(circle at top left, rgba(255,255,255,.08), transparent 20%),
    linear-gradient(135deg, #060d1a 0%, #0A1628 48%, #D4AF37 100%);
  color:#fff;
}

.btp-section-head {
  display:flex;
  justify-content:space-between;
  align-items:end;
  gap:18px;
  flex-wrap:wrap;
  margin-bottom:28px;
}

.btp-title {
  margin:0 0 10px;
  font-size:clamp(20px, 2vw, 30px);
  line-height:1.08;
  letter-spacing:-0.03em;
  color:var(--btp-ink);
  font-weight:800;
}

.btp-section--cta .btp-title {
  color:#fff;
}

.btp-intro {
  max-width:820px;
  margin:0;
  color:var(--btp-muted);
  font-size:17px;
}

.btp-section--cta .btp-intro {
  color:rgba(255,255,255,.90);
}

.btp-hero {
  position:relative;
  overflow:hidden;
  padding:34px 0 44px;
  background:
    radial-gradient(circle at 12% 12%, rgba(212,175,55,.18), transparent 30%),
    radial-gradient(circle at 88% 16%, rgba(212,175,55,.16), transparent 24%),
    linear-gradient(135deg, #060d1a 0%, #0A1628 50%, #D4AF37 100%);
  color:#fff;
}

.btp-hero::before {
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

.btp-hero-grid {
  position:relative;
  z-index:1;
  display:grid;
  grid-template-columns:minmax(0, 1.04fr) minmax(320px, .96fr);
  gap:34px;
  align-items:center;
}

.btp-hero-pill {
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

.btp-hero-pill i {
  color:#E8CC6A;
}

.btp-hero-title {
  margin:18px 0 16px;
  font-size:clamp(26px, 3vw, 44px);
  line-height:1.03;
  letter-spacing:-0.04em;
  color:#fff;
  font-weight:820;
}

.btp-hero-text {
  margin:0;
  max-width:740px;
  color:rgba(255,255,255,.92);
  font-size:18px;
}

.btp-actions {
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  margin-top:24px;
}

.btp-button,
.btp-button--outline,
.btp-button--ghost,
.btp-button--light,
.btp-button--dark {
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

.btp-button:hover,
.btp-button--outline:hover,
.btp-button--ghost:hover,
.btp-button--light:hover,
.btp-button--dark:hover {
  transform:translateY(-2px);
}

.btp-button {
  background:linear-gradient(135deg, var(--btp-gold) 0%, #E8CC6A 100%);
  color:#2b1900;
  box-shadow:0 16px 36px rgba(212,175,55,.26);
}

.btp-button--outline {
  background:rgba(255,255,255,.10);
  color:#fff;
  border-color:rgba(255,255,255,.24);
}

.btp-button--ghost {
  background:#fff4ef;
  color:var(--btp-red);
  border-color:rgba(212,175,55,.10);
}

.btp-button--light {
  background:#fff;
  color:var(--btp-blue-dark);
  border-color:#d8e3f0;
}

.btp-button--dark {
  background:var(--btp-ink);
  color:#fff;
}

.btp-metrics {
  display:grid;
  grid-template-columns:repeat(3, minmax(0, 1fr));
  gap:12px;
  margin-top:26px;
}

.btp-metric {
  padding:16px 14px;
  border-radius:18px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.14);
  backdrop-filter:blur(8px);
}

.btp-metric strong {
  display:block;
  margin-bottom:4px;
  font-size:26px;
  line-height:1;
  font-weight:800;
}

.btp-metric span {
  color:rgba(255,255,255,.84);
  font-size:13px;
  font-weight:650;
}

.btp-hero-note {
  display:inline-flex;
  align-items:center;
  gap:8px;
  margin-top:18px;
  color:rgba(255,255,255,.92);
  font-size:13px;
  font-weight:650;
}

.btp-hero-card {
  overflow:hidden;
  border-radius:28px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.18);
  box-shadow:var(--btp-shadow-lg);
  backdrop-filter:blur(8px);
}

.btp-hero-card__media img {
  width:100%;
  height:520px;
  object-fit:cover;
}

.btp-hero-card__body {
  padding:22px;
}

.btp-hero-card__title {
  margin:0 0 10px;
  color:#fff;
  font-size:28px;
  line-height:1.08;
  font-weight:800;
}

.btp-hero-card__text {
  margin:0;
  color:rgba(255,255,255,.88);
  font-size:15px;
}

.btp-grid-3 {
  display:grid;
  grid-template-columns:repeat(3, minmax(0, 1fr));
  gap:22px;
}

.btp-grid-4 {
  display:grid;
  grid-template-columns:repeat(4, minmax(0, 1fr));
  gap:18px;
}

.btp-grid-5 {
  display:grid;
  grid-template-columns:repeat(5, minmax(0, 1fr));
  gap:18px;
}

.btp-card,
.btp-mini-card,
.btp-process-card,
.btp-audience-card,
.btp-engagement-card,
.btp-faq,
.btp-split-card {
  background:var(--btp-surface);
  border:1px solid var(--btp-line);
  border-radius:22px;
  box-shadow:var(--btp-shadow-sm);
}

.btp-card {
  overflow:hidden;
}

.btp-card__media {
  position:relative;
}

.btp-card__media img {
  width:100%;
  height:220px;
  object-fit:cover;
}

.btp-card__tag {
  position:absolute;
  top:14px;
  left:14px;
  display:inline-flex;
  align-items:center;
  min-height:34px;
  padding:0 12px;
  border-radius:999px;
  background:#fff;
  color:var(--btp-red);
  font-size:12px;
  font-weight:760;
  box-shadow:var(--btp-shadow-sm);
}

.btp-card__body {
  display:flex;
  flex-direction:column;
  gap:10px;
  padding:18px;
}

.btp-card h3,
.btp-mini-card h3,
.btp-process-card h3,
.btp-audience-card h3,
.btp-engagement-card h3,
.btp-split-card h3 {
  margin:0 0 8px;
  color:var(--btp-ink);
  font-size:21px;
  line-height:1.18;
  font-weight:790;
}

.btp-card p,
.btp-mini-card p,
.btp-process-card p,
.btp-audience-card p,
.btp-engagement-card p,
.btp-split-card p {
  margin:0;
  color:var(--btp-muted);
  font-size:15px;
}

.btp-card__footer {
  margin-top:auto;
  padding-top:8px;
}

.btp-mini-card,
.btp-audience-card,
.btp-engagement-card {
  padding:24px 22px;
}

.btp-mini-card__icon,
.btp-engagement-card__icon {
  width:56px;
  height:56px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:18px;
  background:linear-gradient(135deg, rgba(10,22,40,.08), rgba(212,175,55,.12));
  color:var(--btp-blue);
  font-size:22px;
  margin-bottom:14px;
}

.btp-process-card {
  position:relative;
  overflow:hidden;
  padding:24px 22px;
}

.btp-process-card::before {
  content:"";
  position:absolute;
  top:0;
  left:0;
  width:100%;
  height:4px;
  background:linear-gradient(90deg, var(--btp-blue), var(--btp-red));
}

.btp-process-card__number {
  width:54px;
  height:54px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:18px;
  background:linear-gradient(135deg, var(--btp-blue), var(--btp-red));
  color:#fff;
  font-size:22px;
  font-weight:820;
  box-shadow:0 14px 28px rgba(10,22,40,.20);
  margin-bottom:16px;
}

.btp-split {
  display:grid;
  grid-template-columns:minmax(0, .92fr) minmax(0, 1.08fr);
  gap:32px;
  align-items:center;
}

.btp-split-card {
  overflow:hidden;
}

.btp-split-card img {
  width:100%;
  height:520px;
  object-fit:cover;
}

.btp-split-card__body {
  padding:24px;
}

.btp-list {
  margin:20px 0 0;
  padding:0;
  list-style:none;
  display:grid;
  gap:12px;
}

.btp-list li {
  display:flex;
  gap:12px;
  align-items:flex-start;
  color:var(--btp-ink);
  font-size:15px;
  font-weight:650;
}

.btp-list li::before {
  content:"";
  width:24px;
  height:24px;
  min-width:24px;
  margin-top:2px;
  border-radius:999px;
  background:linear-gradient(135deg, rgba(10,22,40,.12), rgba(212,175,55,.12));
  box-shadow:inset 0 0 0 6px rgba(10,22,40,.14);
}

.btp-faq-grid {
  display:grid;
  gap:14px;
  max-width:920px;
  margin:0 auto;
}

.btp-faq {
  overflow:hidden;
}

.btp-faq summary {
  list-style:none;
  cursor:pointer;
  position:relative;
  padding:20px 22px;
  padding-right:56px;
  font-size:17px;
  font-weight:760;
}

.btp-faq summary::-webkit-details-marker {
  display:none;
}

.btp-faq summary::after {
  content:"+";
  position:absolute;
  right:22px;
  top:50%;
  transform:translateY(-50%);
  color:var(--btp-red);
  font-size:28px;
  font-weight:300;
}

.btp-faq[open] summary::after {
  content:"−";
}

.btp-faq__content {
  padding:0 22px 20px;
  color:var(--btp-muted);
  font-size:15px;
}

.btp-cta-inner {
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:22px;
  flex-wrap:wrap;
}

.btp-cta-inner .btp-title {
  color:#fff;
  margin-bottom:8px;
}

.btp-cta-inner .btp-intro {
  color:rgba(255,255,255,.90);
}

@media (max-width: 1180px) {
  .btp-hero-grid,
  .btp-split {
    grid-template-columns:1fr;
  }

  .btp-grid-5,
  .btp-grid-4 {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }

  .btp-grid-3 {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 760px) {
  .btp-shell {
    width:min(var(--btp-shell), calc(100% - 22px));
  }

  .btp-section {
    padding:68px 0;
  }

  .btp-hero {
    padding:24px 0 32px;
  }

  .btp-hero-title {
    font-size:clamp(20px, 2.2vw, 32px);
  }

  .btp-hero-text,
  .btp-intro {
    font-size:16px;
  }

  .btp-grid-5,
  .btp-grid-4,
  .btp-grid-3,
  .btp-metrics {
    grid-template-columns:1fr;
  }

  .btp-actions,
  .btp-cta-inner {
    flex-direction:column;
    align-items:stretch;
  }

  .btp-button,
  .btp-button--outline,
  .btp-button--ghost,
  .btp-button--light,
  .btp-button--dark {
    width:100%;
  }

  .btp-hero-card__media img,
  .btp-split-card img {
    height:360px;
  }
}
</style>

<main class="btp-page">
  <section class="btp-hero">
    <div class="btp-shell">
      <div class="btp-hero-grid">
        <div>
          <span class="btp-hero-pill">
            <i class="fa-solid fa-helmet-safety"></i>
            Construction, finition, rénovation, reprise
          </span>

          <h1 class="btp-hero-title">
            Construction et BTP : des travaux mieux encadrés, mieux suivis et livrés dans un cadre clair.
          </h1>

          <p class="btp-hero-text">
            IBIG IMMO TRUST accompagne les propriétaires, investisseurs et la diaspora sur la construction neuve,
            la finition, la rénovation, la reprise de chantiers inachevés et la supervision technique complète.
          </p>

          <div class="btp-actions">
            <a href="<?= e(url('contact.php')) ?>" class="btp-button" onclick="trackEvent('click_contact_btp');">
              Décrire mon projet BTP
            </a>
            <a href="<?= e(url('rdv.php')) ?>" class="btp-button--outline" onclick="trackEvent('click_rdv_btp');">
              Prendre rendez-vous technique
            </a>
          </div>

          <div class="btp-metrics">
            <?php foreach ($heroMetrics as $metric): ?>
              <div class="btp-metric">
                <strong><?= e($metric['value']) ?></strong>
                <span><?= e($metric['label']) ?></span>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="btp-hero-note">
            <i class="fa-solid fa-circle-info"></i>
            Études, coordination, contrôle qualité et suivi digital pour les clients sur place ou à distance.
          </div>
        </div>

        <aside class="btp-hero-card">
          <div class="btp-hero-card__media">
            <img src="<?= e(url('assets/img/construction-afrique.jpg')) ?>" alt="BTP IBIG IMMO TRUST">
          </div>
          <div class="btp-hero-card__body">
            <h2 class="btp-hero-card__title">Une exécution plus lisible, du terrain jusqu'à la remise des clés.</h2>
            <p class="btp-hero-card__text">
              Nous cherchons à livrer un chantier exploitable, cohérent et correctement documenté, pas seulement à faire avancer des travaux.
            </p>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <section class="btp-section" id="formules-btp">
    <div class="btp-shell">
      <div class="btp-section-head">
        <div>
          <h2 class="btp-title">Nos principaux services BTP</h2>
          <p class="btp-intro">
            Nous couvrons la reprise de chantiers, la construction neuve, la rénovation, les finitions et la supervision technique.
          </p>
        </div>
      </div>

      <div class="btp-grid-3">
        <?php foreach ($serviceCards as $card): ?>
          <article class="btp-card">
            <div class="btp-card__media">
              <img src="<?= e($card['image']) ?>" alt="<?= e($card['title']) ?>" loading="lazy" decoding="async">
              <span class="btp-card__tag"><?= e($card['tag']) ?></span>
            </div>
            <div class="btp-card__body">
              <h3><?= e($card['title']) ?></h3>
              <p><?= e($card['text']) ?></p>
              <div class="btp-card__footer">
                <a href="<?= e($card['url']) ?>" class="btp-button--ghost"><?= e($card['cta']) ?></a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="btp-section btp-section--soft" id="processus-btp">
    <div class="btp-shell">
      <div class="btp-section-head">
        <div>
          <h2 class="btp-title">Un processus clair, sécurisé et encadré</h2>
          <p class="btp-intro">
            Notre méthode vise à donner de la visibilité, réduire les zones floues et améliorer le contrôle du projet à chaque étape.
          </p>
        </div>
      </div>

      <div class="btp-grid-5">
        <?php foreach ($processSteps as $step): ?>
          <article class="btp-process-card">
            <div class="btp-process-card__number"><?= e($step['number']) ?></div>
            <h3><?= e($step['title']) ?></h3>
            <p><?= e($step['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="btp-section">
    <div class="btp-shell">
      <div class="btp-section-head">
        <div>
          <h2 class="btp-title">À qui s'adresse notre offre BTP ?</h2>
          <p class="btp-intro">
            Nous adaptons l'accompagnement selon le niveau d'avancement du chantier, le profil du client et l'objectif final du bien.
          </p>
        </div>
      </div>

      <div class="btp-grid-4">
        <?php foreach ($audiences as $audience): ?>
          <article class="btp-audience-card">
            <h3><?= e($audience['title']) ?></h3>
            <p><?= e($audience['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="btp-section btp-section--soft">
    <div class="btp-shell btp-split">
      <div class="btp-split-card">
        <img src="<?= e(url('assets/img/suivi-diaspora.jpg')) ?>" alt="Suivi diaspora BTP IBIG IMMO TRUST">
        <div class="btp-split-card__body">
          <h3>Suivi renforcé pour la diaspora</h3>
          <p>
            Photos, vidéos, documents, visios et validations intermédiaires permettent de garder la main sur le chantier même à distance.
          </p>
        </div>
      </div>

      <div>
        <div class="btp-section-head" style="margin-bottom:20px;">
          <div>
            <h2 class="btp-title">Pourquoi cette approche est plus rassurante</h2>
            <p class="btp-intro">
              Construire ou reprendre un chantier exige plus qu'un devis. Il faut un cadre, un suivi et une lecture claire des décisions.
            </p>
          </div>
        </div>

        <ul class="btp-list">
          <?php foreach ($advantages as $item): ?>
            <li><?= e($item) ?></li>
          <?php endforeach; ?>
        </ul>

        <div class="btp-actions" style="margin-top:24px;">
          <a href="<?= e(url('chantier-inacheve.php')) ?>" class="btp-button--dark">Voir les reprises de chantier</a>
          <a href="<?= e(url('construction.php')) ?>" class="btp-button--light">Voir la construction neuve</a>
        </div>
      </div>
    </div>
  </section>

  <section class="btp-section">
    <div class="btp-shell">
      <div class="btp-section-head">
        <div>
          <h2 class="btp-title">Nos garanties et engagements BTP</h2>
          <p class="btp-intro">
            Le chantier doit rester lisible, traçable et pilotable. Voici les repères que nous mettons en avant.
          </p>
        </div>
      </div>

      <div class="btp-grid-3">
        <?php foreach ($engagements as $engagement): ?>
          <article class="btp-engagement-card">
            <div class="btp-engagement-card__icon"><i class="fa-solid fa-circle-check"></i></div>
            <h3><?= e($engagement['title']) ?></h3>
            <p><?= e($engagement['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="btp-section btp-section--soft">
    <div class="btp-shell">
      <div class="btp-section-head" style="justify-content:center;text-align:center;">
        <div>
          <h2 class="btp-title">Questions fréquentes sur nos services BTP</h2>
          <p class="btp-intro" style="margin:0 auto;">
            Quelques réponses utiles avant de lancer un projet de construction, de finition ou de reprise.
          </p>
        </div>
      </div>

      <div class="btp-faq-grid">
        <?php foreach ($faqs as $faq): ?>
          <details class="btp-faq">
            <summary><?= e($faq['q']) ?></summary>
            <div class="btp-faq__content"><?= e($faq['a']) ?></div>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="btp-section btp-section--cta">
    <div class="btp-shell btp-cta-inner">
      <div>
        <h2 class="btp-title">Un projet de construction, de finition ou de rénovation ?</h2>
        <p class="btp-intro">
          Confiez votre chantier à une équipe structurée, transparente et habituée à travailler avec des propriétaires sur place comme à l'étranger.
        </p>
      </div>

      <div class="btp-actions">
        <a href="<?= e(url('contact.php')) ?>" class="btp-button" onclick="trackEvent('click_contact_btp_cta');">
          Décrire mon projet BTP
        </a>
        <a href="<?= e(url('rdv.php')) ?>" class="btp-button--light" onclick="trackEvent('click_rdv_btp');">
          Prendre rendez-vous
        </a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
