<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/tracker.php';

$currentPage = 'quisommesnous';
$disableHeaderSlider = true;
$pageTitle = "Qui sommes-nous ? - IBIG IMMO TRUST";
$metaDescription = "Découvrez IBIG IMMO TRUST, sa mission, ses engagements et son approche dans l'immobilier, le BTP, la gestion locative et le financement en Côte d'Ivoire.";
$canonicalUrl = url('quisommesnous.php');

if (!function_exists('e')) {
    function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

$heroMetrics = [
    ['value' => '360°', 'label' => 'accompagnement projet'],
    ['value' => '24/7', 'label' => 'suivi diaspora'],
    ['value' => 'A à Z', 'label' => 'vision immobilière et BTP'],
];

$pillars = [
    [
        'title' => 'Immobilier',
        'text' => "Vente, location, gestion locative, valorisation et accompagnement des propriétaires comme des investisseurs.",
    ],
    [
        'title' => 'BTP',
        'text' => "Construction, finition, rénovation, reprise de chantiers inachevés et supervision technique.",
    ],
    [
        'title' => 'Financement',
        'text' => "Lecture de faisabilité, modèle Direct IBIG, investisseurs agréés et accompagnement bancaire ou microfinance.",
    ],
];

$values = [
    [
        'icon' => 'fa-solid fa-shield-halved',
        'title' => 'Transparence',
        'text' => "Nous cherchons à rendre les décisions plus lisibles grâce à des devis clairs, des étapes visibles et un meilleur suivi.",
    ],
    [
        'icon' => 'fa-solid fa-helmet-safety',
        'title' => 'Rigueur terrain',
        'text' => "Nos interventions s'appuient sur une logique d'exécution, de contrôle et de coordination adaptée aux réalités du terrain.",
    ],
    [
        'icon' => 'fa-solid fa-globe',
        'title' => 'Accompagnement diaspora',
        'text' => "Nous mettons en place un suivi à distance utile et rassurant pour les clients vivant hors de Côte d'Ivoire.",
    ],
    [
        'icon' => 'fa-solid fa-chart-line',
        'title' => 'Vision de valorisation',
        'text' => "Nous ne lisons pas un bien seulement comme un actif à vendre, mais comme un projet à exploiter, sécuriser et rentabiliser.",
    ],
];

$strengths = [
    "Une lecture qui relie immobilier, BTP, gestion locative et financement dans une seule stratégie.",
    "Une approche rassurante pour les propriétaires qui veulent garder une vision claire de leur projet.",
    "Une capacité à intervenir aussi bien sur un bien existant que sur un chantier, une rénovation ou un projet à lancer.",
    "Un accompagnement pensé pour la diaspora, les investisseurs, les familles et les porteurs de projets patrimoniaux.",
];

$teamFocus = [
    [
        'title' => 'Conseil et cadrage',
        'text' => "Nous aidons à clarifier le besoin, les risques, le budget, le calendrier et la meilleure voie d'action.",
    ],
    [
        'title' => 'Exécution et coordination',
        'text' => "Nous pilotons ou suivons les intervenants afin de rendre le projet plus cohérent et plus maîtrisé.",
    ],
    [
        'title' => 'Suivi et relation client',
        'text' => "Nous mettons l'accent sur la visibilité, la communication et la continuité de l'accompagnement.",
    ],
];

include __DIR__ . '/includes/header.php';
?>

<style>
:root {
  --about-blue:#0A1628;
  --about-blue-dark:#060d1a;
  --about-red:#D4AF37;
  --about-gold:#f4bc49;
  --about-ink:#101828;
  --about-ink-soft:#1d2939;
  --about-muted:#667085;
  --about-line:#e4e7ec;
  --about-surface:#ffffff;
  --about-surface-soft:#f8fafc;
  --about-surface-warm:#FAF7F0;
  --about-shadow-sm:0 10px 28px rgba(15, 23, 42, 0.06);
  --about-shadow-md:0 20px 54px rgba(15, 23, 42, 0.10);
  --about-shadow-lg:0 32px 76px rgba(8, 39, 95, 0.18);
  --about-shell:1240px;
}

.about-page {
  color:var(--about-ink);
  background:#fff;
}

.about-shell {
  width:min(var(--about-shell), calc(100% - 32px));
  margin:0 auto;
}

.about-section {
  padding:84px 0;
}

.about-section--soft {
  background:
    radial-gradient(circle at top right, rgba(244,188,73,.12), transparent 22%),
    linear-gradient(180deg, #fffaf4 0%, #ffffff 100%);
}

.about-section--cta {
  background:
    radial-gradient(circle at top left, rgba(255,255,255,.08), transparent 20%),
    linear-gradient(135deg, #060d1a 0%, #0A1628 48%, #D4AF37 100%);
  color:#fff;
}

.about-head {
  display:flex;
  justify-content:space-between;
  align-items:end;
  gap:18px;
  flex-wrap:wrap;
  margin-bottom:28px;
}

.about-title {
  margin:0 0 10px;
  font-size:clamp(20px, 2vw, 30px);
  line-height:1.08;
  letter-spacing:-0.03em;
  color:var(--about-ink);
  font-weight:800;
}

.about-section--cta .about-title {
  color:#fff;
}

.about-intro {
  max-width:820px;
  margin:0;
  color:var(--about-muted);
  font-size:17px;
}

.about-section--cta .about-intro {
  color:rgba(255,255,255,.92);
}

.about-hero {
  position:relative;
  overflow:hidden;
  padding:34px 0 44px;
  background:
    radial-gradient(circle at 10% 10%, rgba(244,188,73,.18), transparent 24%),
    radial-gradient(circle at 90% 18%, rgba(212,175,55,.18), transparent 22%),
    linear-gradient(135deg, #060d1a 0%, #0A1628 52%, #D4AF37 100%);
  color:#fff;
}

.about-hero::before {
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

.about-hero-grid {
  position:relative;
  z-index:1;
  display:grid;
  grid-template-columns:minmax(0, 1.04fr) minmax(320px, .96fr);
  gap:34px;
  align-items:center;
}

.about-pill {
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

.about-pill i {
  color:#ffdb7c;
}

.about-hero-title {
  margin:18px 0 16px;
  font-size:clamp(26px, 3vw, 44px);
  line-height:1.03;
  letter-spacing:-0.04em;
  color:#fff;
  font-weight:820;
}

.about-hero-text {
  margin:0;
  max-width:760px;
  color:rgba(255,255,255,.92);
  font-size:18px;
}

.about-actions {
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  margin-top:24px;
}

.about-button,
.about-button--outline,
.about-button--ghost,
.about-button--light,
.about-button--dark {
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

.about-button:hover,
.about-button--outline:hover,
.about-button--ghost:hover,
.about-button--light:hover,
.about-button--dark:hover {
  transform:translateY(-2px);
}

.about-button {
  background:linear-gradient(135deg, var(--about-gold) 0%, #ffd878 100%);
  color:#2b1900;
  box-shadow:0 16px 36px rgba(244,188,73,.24);
}

.about-button--outline {
  background:rgba(255,255,255,.10);
  color:#fff;
  border-color:rgba(255,255,255,.24);
}

.about-button--ghost {
  background:#fff4ef;
  color:var(--about-red);
  border-color:rgba(212,175,55,.10);
}

.about-button--light {
  background:#fff;
  color:var(--about-blue-dark);
  border-color:#d8e3f0;
}

.about-button--dark {
  background:var(--about-ink);
  color:#fff;
}

.about-metrics {
  display:grid;
  grid-template-columns:repeat(3, minmax(0, 1fr));
  gap:12px;
  margin-top:26px;
}

.about-metric {
  padding:16px 14px;
  border-radius:18px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.14);
  backdrop-filter:blur(8px);
}

.about-metric strong {
  display:block;
  margin-bottom:4px;
  font-size:26px;
  line-height:1;
  font-weight:800;
}

.about-metric span {
  color:rgba(255,255,255,.84);
  font-size:13px;
  font-weight:650;
}

.about-note {
  display:inline-flex;
  align-items:center;
  gap:8px;
  margin-top:18px;
  color:rgba(255,255,255,.92);
  font-size:13px;
  font-weight:650;
}

.about-hero-card {
  overflow:hidden;
  border-radius:28px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.18);
  box-shadow:var(--about-shadow-lg);
  backdrop-filter:blur(8px);
}

.about-hero-card__media img {
  width:100%;
  height:520px;
  object-fit:cover;
}

.about-hero-card__body {
  padding:22px;
}

.about-hero-card__title {
  margin:0 0 10px;
  color:#fff;
  font-size:28px;
  line-height:1.08;
  font-weight:800;
}

.about-hero-card__text {
  margin:0;
  color:rgba(255,255,255,.88);
  font-size:15px;
}

.about-grid-3 {
  display:grid;
  grid-template-columns:repeat(3, minmax(0, 1fr));
  gap:22px;
}

.about-grid-4 {
  display:grid;
  grid-template-columns:repeat(4, minmax(0, 1fr));
  gap:18px;
}

.about-card,
.about-value,
.about-team-card,
.about-split-card {
  background:var(--about-surface);
  border:1px solid var(--about-line);
  border-radius:22px;
  box-shadow:var(--about-shadow-sm);
}

.about-card,
.about-value,
.about-team-card {
  padding:24px 22px;
}

.about-card h3,
.about-value h3,
.about-team-card h3,
.about-split-card h3 {
  margin:0 0 10px;
  color:var(--about-ink);
  font-size:22px;
  line-height:1.15;
  font-weight:790;
}

.about-card p,
.about-value p,
.about-team-card p,
.about-split-card p {
  margin:0;
  color:var(--about-muted);
  font-size:15px;
}

.about-value__icon {
  width:56px;
  height:56px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:18px;
  background:linear-gradient(135deg, rgba(10,22,40,.08), rgba(212,175,55,.12));
  color:var(--about-blue);
  font-size:22px;
  margin-bottom:14px;
}

.about-split {
  display:grid;
  grid-template-columns:minmax(0, .92fr) minmax(0, 1.08fr);
  gap:32px;
  align-items:center;
}

.about-split-card {
  overflow:hidden;
}

.about-split-card img {
  width:100%;
  height:520px;
  object-fit:cover;
}

.about-split-card__body {
  padding:24px;
}

.about-list {
  margin:20px 0 0;
  padding:0;
  list-style:none;
  display:grid;
  gap:12px;
}

.about-list li {
  display:flex;
  gap:12px;
  align-items:flex-start;
  color:var(--about-ink);
  font-size:15px;
  font-weight:650;
}

.about-list li::before {
  content:"";
  width:24px;
  height:24px;
  min-width:24px;
  margin-top:2px;
  border-radius:999px;
  background:linear-gradient(135deg, rgba(10,22,40,.12), rgba(212,175,55,.12));
  box-shadow:inset 0 0 0 6px rgba(10,22,40,.14);
}

.about-cta-inner {
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:22px;
  flex-wrap:wrap;
}

@media (max-width: 1180px) {
  .about-hero-grid,
  .about-split {
    grid-template-columns:1fr;
  }

  .about-grid-4 {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }

  .about-grid-3 {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 760px) {
  .about-shell {
    width:min(var(--about-shell), calc(100% - 22px));
  }

  .about-section {
    padding:68px 0;
  }

  .about-hero {
    padding:24px 0 32px;
  }

  .about-hero-title {
    font-size:clamp(20px, 2.2vw, 32px);
  }

  .about-hero-text,
  .about-intro {
    font-size:16px;
  }

  .about-grid-4,
  .about-grid-3,
  .about-metrics {
    grid-template-columns:1fr;
  }

  .about-actions,
  .about-cta-inner {
    flex-direction:column;
    align-items:stretch;
  }

  .about-button,
  .about-button--outline,
  .about-button--ghost,
  .about-button--light,
  .about-button--dark {
    width:100%;
  }

  .about-hero-card__media img,
  .about-split-card img {
    height:360px;
  }
}
</style>

<main class="about-page">
  <section class="about-hero">
    <div class="about-shell">
      <div class="about-hero-grid">
        <div>
          <span class="about-pill">
            <i class="fa-solid fa-building-shield"></i>
            Immobilier, BTP, gestion locative et financement
          </span>

          <h1 class="about-hero-title">
            Une structure pensée pour sécuriser, piloter et valoriser les projets immobiliers de façon plus cohérente.
          </h1>

          <p class="about-hero-text">
            IBIG IMMO TRUST est la branche immobilière et BTP de INTERMARK BUSINESS INTERNATIONAL GROUP SARL.
            Nous accompagnons propriétaires, investisseurs, familles et diaspora dans l'immobilier, la construction,
            la gestion locative, la reprise de chantiers et les solutions de financement.
          </p>

          <div class="about-actions">
            <a href="<?= e(url('contact.php')) ?>" class="about-button">Décrire mon projet</a>
            <a href="<?= e(url('rdv.php')) ?>" class="about-button--outline">Prendre rendez-vous</a>
          </div>

          <div class="about-metrics">
            <?php foreach ($heroMetrics as $metric): ?>
              <div class="about-metric">
                <strong><?= e($metric['value']) ?></strong>
                <span><?= e($metric['label']) ?></span>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="about-note">
            <i class="fa-solid fa-circle-info"></i>
            Notre objectif est de transformer un bien ou un projet en actif plus lisible, plus solide et mieux exploitable.
          </div>
        </div>

        <aside class="about-hero-card">
          <div class="about-hero-card__media">
            <img src="<?= e(url('assets/img/hero-immo-afrique3.jpg')) ?>" alt="Présentation IBIG IMMO TRUST">
          </div>
          <div class="about-hero-card__body">
            <h2 class="about-hero-card__title">Une approche qui relie terrain, stratégie et exécution.</h2>
            <p class="about-hero-card__text">
              Nous cherchons à aller au-delà d'une simple mise en relation en construisant un accompagnement plus complet, plus structuré et plus rassurant.
            </p>
          </div>
        </aside>
      </div>
    </div>
  </section>

  <section class="about-section">
    <div class="about-shell">
      <div class="about-head">
        <div>
          <h2 class="about-title">Notre cœur d'intervention</h2>
          <p class="about-intro">
            Nous couvrons plusieurs dimensions du projet patrimonial afin d'aider nos clients à mieux décider, mieux exécuter et mieux valoriser leurs actifs.
          </p>
        </div>
      </div>

      <div class="about-grid-3">
        <?php foreach ($pillars as $pillar): ?>
          <article class="about-card">
            <h3><?= e($pillar['title']) ?></h3>
            <p><?= e($pillar['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="about-section about-section--soft">
    <div class="about-shell about-split">
      <div class="about-split-card">
        <img src="<?= e(url('assets/img/support-client.jpg')) ?>" alt="Accompagnement client IBIG IMMO TRUST">
        <div class="about-split-card__body">
          <h3>Une entreprise engagée pour votre patrimoine</h3>
          <p>
            Notre mission est d'aider à transformer les biens et projets en actifs plus durables, plus rentables, mieux sécurisés et correctement pilotés.
          </p>
        </div>
      </div>

      <div>
        <div class="about-head" style="margin-bottom:20px;">
          <div>
            <h2 class="about-title">Pourquoi notre approche fait la différence</h2>
            <p class="about-intro">
              Nous intervenons avec une logique de transparence, de rigueur et de continuité, là où beaucoup de projets souffrent de flou ou de dispersion.
            </p>
          </div>
        </div>

        <ul class="about-list">
          <?php foreach ($strengths as $strength): ?>
            <li><?= e($strength) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>
  </section>

  <section class="about-section">
    <div class="about-shell">
      <div class="about-head" style="justify-content:center;text-align:center;">
        <div>
          <h2 class="about-title">Nos valeurs et engagements</h2>
          <p class="about-intro" style="margin:0 auto;">
            L'immobilier et le BTP exigent méthode, confiance et cohérence. Ces repères structurent notre manière de travailler.
          </p>
        </div>
      </div>

      <div class="about-grid-4">
        <?php foreach ($values as $value): ?>
          <article class="about-value">
            <div class="about-value__icon"><i class="<?= e($value['icon']) ?>"></i></div>
            <h3><?= e($value['title']) ?></h3>
            <p><?= e($value['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="about-section about-section--soft">
    <div class="about-shell">
      <div class="about-head" style="justify-content:center;text-align:center;">
        <div>
          <h2 class="about-title">Une équipe engagée pour vos projets</h2>
          <p class="about-intro" style="margin:0 auto;">
            Ingénieurs, juristes, experts fonciers, superviseurs, artisans et conseillers peuvent intervenir selon les besoins du projet.
          </p>
        </div>
      </div>

      <div class="about-grid-3">
        <?php foreach ($teamFocus as $item): ?>
          <article class="about-team-card">
            <h3><?= e($item['title']) ?></h3>
            <p><?= e($item['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="about-section about-section--cta">
    <div class="about-shell about-cta-inner">
      <div>
        <h2 class="about-title">Vous avez un projet immobilier ou BTP ?</h2>
        <p class="about-intro">
          Décrivez votre besoin et échangeons sur la meilleure manière de sécuriser, structurer et faire avancer votre projet.
        </p>
      </div>

      <div class="about-actions">
        <a href="<?= e(url('contact.php')) ?>" class="about-button">Décrire mon projet</a>
        <a href="<?= e(url('immobilier.php')) ?>" class="about-button--light">Découvrir nos services</a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
