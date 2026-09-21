<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/tracker.php';

$currentPage = 'home';
$disableHeaderSlider = false;
$pageTitle = "IBIG IMMO TRUST - Immobilier, BTP et valorisation en Côte d'Ivoire";
$metaDescription = "Immobilier, BTP, gestion locative, reprise de chantiers, rénovation et financement structuré en Côte d'Ivoire avec IBIG IMMO TRUST.";
$canonicalUrl = url('');

function home_h(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function home_fix_text(?string $value): string
{
    $text = trim((string) $value);
    if ($text === '') {
        return '';
    }

    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    $text = strtr($text, [
        'Ã¢â‚¬â„¢' => "'",
        'Ã¢â‚¬Ëœ' => "'",
        'Ã¢â‚¬Å“' => '"',
        'Ã¢â‚¬Â' => '"',
        'Ã¢â‚¬â€œ' => '-',
        'Ã¢â‚¬â€' => '-',
        'Ã¢â‚¬Â¦' => '...',
        'Ã€' => 'À',
        'Ã‚' => 'Â',
        'Ã‡' => 'Ç',
        'Ãˆ' => 'È',
        'Ã‰' => 'É',
        'ÃŠ' => 'Ê',
        'Ã‹' => 'Ë',
        'ÃŽ' => 'Î',
        'Ã”' => 'Ô',
        'Ã–' => 'Ö',
        'Ã™' => 'Ù',
        'Ã›' => 'Û',
        'Ãœ' => 'Ü',
        'Ã ' => 'à',
        'Ã¢' => 'â',
        'Ã¤' => 'ä',
        'Ã§' => 'ç',
        'Ã¨' => 'è',
        'Ã©' => 'é',
        'Ãª' => 'ê',
        'Ã«' => 'ë',
        'Ã®' => 'î',
        'Ã¯' => 'ï',
        'Ã´' => 'ô',
        'Ã¶' => 'ö',
        'Ã¹' => 'ù',
        'Ã»' => 'û',
        'Ã¼' => 'ü',
        'Â°' => '°',
        'Â²' => '²',
        'Â' => '',
    ]);

    $text = preg_replace('/\s+/u', ' ', $text);
    return trim((string) $text);
}

function home_slugify(string $text): string
{
    $text = home_fix_text($text);
    $converted = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
    if (is_string($converted) && $converted !== '') {
        $text = $converted;
    }

    $text = preg_replace('/[^a-zA-Z0-9]+/', '-', $text);
    $text = strtolower(trim((string) $text, '-'));

    return $text !== '' ? $text : 'bien';
}

function home_image_path(?string $path): string
{
    $value = trim((string) $path);
    if ($value === '') {
        return '/assets/img/no-image.jpg';
    }

    if (strpos($value, 'http://') === 0 || strpos($value, 'https://') === 0) {
        return $value;
    }

    return '/' . ltrim($value, '/');
}

function home_property_url(array $property): string
{
    $slug = trim((string) ($property['slug'] ?? ''));
    if ($slug === '') {
        $slug = home_slugify(
            trim(
                home_fix_text((string) ($property['type'] ?? '')) . ' ' .
                home_fix_text((string) ($property['titre'] ?? '')) . ' ' .
                home_fix_text((string) ($property['ville'] ?? ''))
            )
        ) . '-' . (int) ($property['id'] ?? 0);
    }

    return '/bien/' . rawurlencode($slug);
}

function home_transaction_key(?string $value): string
{
    $normalized = strtolower(home_fix_text((string) $value));

    if (in_array($normalized, ['vente', 'a vendre', 'à vendre', 'a-vendre', 'avendre'], true)) {
        return 'vente';
    }

    if (in_array($normalized, ['location', 'a louer', 'à louer', 'a-louer', 'alouer'], true)) {
        return 'location';
    }

    return $normalized;
}

function home_transaction_label(?string $value): string
{
    $key = home_transaction_key($value);

    if ($key === 'vente') {
        return 'À vendre';
    }

    if ($key === 'location') {
        return 'À louer';
    }

    $text = home_fix_text((string) $value);
    return $text !== '' ? ucfirst($text) : '';
}

function home_type_label(?string $value): string
{
    $normalized = strtolower(home_fix_text((string) $value));

    $labels = [
        'villa' => 'Villa',
        'duplex' => 'Duplex',
        'maison' => 'Maison',
        'appartement' => 'Appartement',
        'terrain' => 'Terrain',
        'immeuble' => 'Immeuble',
        'bureau' => 'Bureau',
        'local commercial' => 'Local commercial',
        'entrepot' => 'Entrepôt',
        'entrepôt' => 'Entrepôt',
    ];

    if (isset($labels[$normalized])) {
        return $labels[$normalized];
    }

    return $normalized !== '' ? ucwords($normalized) : 'Bien immobilier';
}

function home_price_label($price): string
{
    $amount = (float) $price;
    if ($amount <= 0) {
        return 'Prix sur demande';
    }

    return number_format($amount, 0, ',', ' ') . ' FCFA';
}

function home_location_label(array $property): string
{
    $parts = [];

    $city = home_fix_text((string) ($property['ville'] ?? ''));
    $district = home_fix_text((string) ($property['quartier'] ?? ''));

    if ($city !== '') {
        $parts[] = $city;
    }

    if ($district !== '') {
        $parts[] = $district;
    }

    return $parts ? implode(' - ', $parts) : 'Localisation à confirmer';
}

function home_measure_label(?string $value): string
{
    $text = home_fix_text($value);
    if ($text === '') {
        return '';
    }

    if (preg_match('/m(?:²|2)\b/ui', $text)) {
        return $text;
    }

    return preg_match('/\d/u', $text) ? $text . ' m²' : $text;
}

function home_property_tokens(array $property): array
{
    $tokens = [];

    $surface = home_measure_label((string) ($property['superficie'] ?? ($property['superficie_habitable'] ?? '')));
    if ($surface !== '') {
        $tokens[] = $surface;
    }

    if (!empty($property['chambres'])) {
        $tokens[] = (int) $property['chambres'] . ' ch.';
    }

    if (!empty($property['salles_bain'])) {
        $tokens[] = (int) $property['salles_bain'] . ' sdb';
    }

    $availability = home_fix_text((string) ($property['disponibilite'] ?? ''));
    if ($availability !== '') {
        $tokens[] = $availability;
    }

    return array_slice($tokens, 0, 3);
}

function home_visibility_badge(array $property): string
{
    $visibility = strtolower(trim((string) ($property['niveau_visibilite'] ?? '')));

    if ($visibility === 'exclusif') {
        return 'Exclusivité';
    }

    if ($visibility === 'vedette') {
        return 'En vedette';
    }

    $badge = home_fix_text((string) ($property['badge'] ?? ''));
    return $badge !== '' ? $badge : 'Sélection';
}

function home_count_label(int $value): string
{
    if ($value >= 1000) {
        return number_format($value, 0, ',', ' ');
    }

    return (string) $value;
}

$services = [
    [
        'icon' => 'fa-solid fa-building-circle-check',
        'title' => 'Immobilier',
        'text' => 'Achat, vente, location, valorisation et sécurisation de biens selon une lecture claire du potentiel.',
        'url' => url('immobilier.php'),
        'cta' => 'Découvrir',
    ],
    [
        'icon' => 'fa-solid fa-helmet-safety',
        'title' => 'BTP et exécution',
        'text' => 'Construction, finition, rénovation, reprise de chantiers et pilotage technique avec suivi terrain.',
        'url' => url('btp.php'),
        'cta' => 'Voir nos solutions',
    ],
    [
        'icon' => 'fa-solid fa-hand-holding-dollar',
        'title' => 'Financement structuré',
        'text' => 'Direct IBIG, investisseurs partenaires, banques et microfinances selon le profil du projet.',
        'url' => url('financement.php'),
        'cta' => 'Étudier un montage',
    ],
    [
        'icon' => 'fa-solid fa-chart-line',
        'title' => 'Gestion de projet',
        'text' => "Coordination, contrôle qualité, reporting et accompagnement décisionnel du démarrage à l'exploitation.",
        'url' => url('contact.php'),
        'cta' => 'Parler à un expert',
    ],
];

$constructionServices = [
    [
        'title' => 'Reprise de chantiers inachevés',
        'text' => "Audit de l'existant, budget correctif, relance des travaux et remise en mouvement du projet.",
        'image' => url('assets/img/chantier-inacheve.jpg'),
        'url' => url('chantier-inacheve.php'),
    ],
    [
        'title' => 'Rénovation et revalorisation',
        'text' => 'Réhabilitation, remise au goût du jour, optimisation des espaces et amélioration du rendement locatif.',
        'image' => url('assets/img/renovation-afrique.jpg'),
        'url' => url('renovation.php'),
    ],
    [
        'title' => 'Construction clé en main',
        'text' => 'Maisons, villas, immeubles, bureaux et programmes sur mesure pilotés avec méthode.',
        'image' => url('assets/img/construction-afrique.jpg'),
        'url' => url('construction.php'),
    ],
];

$fundingModels = [
    [
        'tag' => 'Sans avance selon éligibilité',
        'title' => 'Modèle Direct IBIG',
        'text' => "Selon le dossier, IBIG peut structurer une exécution progressive et un remboursement adossé à l'exploitation future du bien.",
        'url' => url('direct-ibig.php'),
    ],
    [
        'tag' => 'Financement partenaire',
        'title' => 'Investisseurs agréés',
        'text' => "Mobilisation d'investisseurs pour finir, développer ou repositionner un actif immobilier avec cadre convenu.",
        'url' => url('investisseurs-agrees.php'),
    ],
    [
        'tag' => 'Accompagnement dossier',
        'title' => 'Banques et microfinances',
        'text' => 'Préparation du dossier, cadrage budgétaire, lecture de faisabilité et supervision des fonds débloqués.',
        'url' => url('banques-microfinance.php'),
    ],
];

$trustPoints = [
    [
        'icon' => 'fa-solid fa-shield-halved',
        'title' => 'Cadre sécurisé',
        'text' => "Analyse, contrat, exécution et suivi documenté pour limiter les zones d'ombre.",
    ],
    [
        'icon' => 'fa-solid fa-globe',
        'title' => 'Suivi diaspora',
        'text' => "Photos, vidéos, rapports et points d'étape pour garder le contrôle à distance.",
    ],
    [
        'icon' => 'fa-solid fa-screwdriver-wrench',
        'title' => 'Double expertise',
        'text' => 'Lecture immobilière et capacité BTP pour aller au-delà de la simple mise en relation.',
    ],
    [
        'icon' => 'fa-solid fa-layer-group',
        'title' => 'Vision globale',
        'text' => 'Terrain, chantier, location, valorisation et financement pensés dans le même cadre.',
    ],
];

$marketHighlights = [
    [
        'icon' => 'fa-solid fa-compass-drafting',
        'title' => 'Lecture investisseur',
        'text' => "Chaque opportunité est lue avec une logique de valeur, d'usage, de rentabilité et de marge de progression.",
    ],
    [
        'icon' => 'fa-solid fa-map-location-dot',
        'title' => 'Ancrage terrain',
        'text' => "Le travail ne s'arrête pas à l'annonce : nous regardons la zone, les accès, la cohérence et le niveau de maturité du bien.",
    ],
    [
        'icon' => 'fa-solid fa-clipboard-check',
        'title' => 'Processus lisible',
        'text' => "Un parcours plus clair, des sections mieux structurées et des appels à l'action orientés conversion.",
    ],
    [
        'icon' => 'fa-solid fa-bolt',
        'title' => 'Décision plus rapide',
        'text' => 'Catégories rapides, biens mis en avant et contacts visibles pour réduire les frictions.',
    ],
];

$quickCategories = [
    ['label' => 'Villas et duplex', 'url' => url('tous_les_biens.php?type=villa')],
    ['label' => 'Appartements', 'url' => url('tous_les_biens.php?type=appartement')],
    ['label' => 'Terrains', 'url' => url('tous_les_biens.php?type=terrain')],
    ['label' => 'Immeubles', 'url' => url('tous_les_biens.php?type=immeuble')],
    ['label' => 'Bureaux', 'url' => url('tous_les_biens.php?type=bureau')],
    ['label' => 'Gestion locative', 'url' => url('loyer-garanti.php')],
    ['label' => 'Chantiers inachevés', 'url' => url('chantier-inacheve.php')],
    ['label' => 'Financement', 'url' => url('financement.php')],
];

$processSteps = [
    [
        'number' => '1',
        'title' => 'Diagnostic',
        'text' => "Comprendre le besoin, le contexte, le niveau d'urgence et la faisabilité globale.",
    ],
    [
        'number' => '2',
        'title' => 'Cadrage',
        'text' => "Poser un budget, un planning, une logique d'exécution et des règles de suivi.",
    ],
    [
        'number' => '3',
        'title' => 'Pilotage',
        'text' => 'Coordonner, contrôler, documenter et corriger les écarts au fur et à mesure.',
    ],
    [
        'number' => '4',
        'title' => 'Valorisation',
        'text' => "Rendre le bien exploitable, vendable, louable ou plus attractif selon l'objectif.",
    ],
];

$reassuranceCards = [
    [
        'title' => 'Analyse avant action',
        'text' => "Nous cherchons d'abord la bonne lecture du projet avant de proposer une solution.",
    ],
    [
        'title' => 'Budget plus lisible',
        'text' => 'Les coûts, risques et scénarios sont mieux posés pour éviter les décisions floues.',
    ],
    [
        'title' => 'Pilotage terrain réel',
        'text' => "L'accompagnement reste connecté à l'exécution et pas seulement à la promesse commerciale.",
    ],
    [
        'title' => 'Objectif de résultat',
        'text' => 'Le bien doit ressortir plus clair, plus exploitable, mieux valorisé et mieux suivi.',
    ],
];

$testimonials = [
    [
        'name' => "K. N'DRI - France",
        'text' => "Depuis la France, j'ai pu suivre la reprise de ma villa à Bingerville avec des retours réguliers et une vraie visibilité sur les étapes.",
    ],
    [
        'name' => 'A. ADOU - Canada',
        'text' => "L'accompagnement m'a rassuré pour piloter mon projet immobilier à distance. Le cadre était clair et le suivi bien structuré.",
    ],
    [
        'name' => 'F. GUEI - Allemagne',
        'text' => "Les comptes rendus et les visuels de chantier m'ont permis de garder une vision concrète de l'avancement.",
    ],
    [
        'name' => 'R. KOSSONOU - Belgique',
        'text' => "J'ai pu rénover puis remettre un appartement sur le marché sans devoir être présent en permanence.",
    ],
    [
        'name' => 'Y. YAPI - Abidjan',
        'text' => "Bonne coordination des équipes et meilleur rythme d'exécution. Le projet a gagné en lisibilité et en efficacité.",
    ],
    [
        'name' => 'M. TRAORE - Yamoussoukro',
        'text' => "Un chantier bloqué depuis longtemps a pu être repris puis finalisé avec une méthode plus claire.",
    ],
];

$faqs = [
    [
        'q' => "IBIG IMMO TRUST intervient-il seulement à Abidjan ?",
        'a' => "Nous intervenons prioritairement à Abidjan et sur d'autres zones à fort potentiel selon la nature du projet.",
    ],
    [
        'q' => "Pouvez-vous reprendre un chantier déjà commencé ?",
        'a' => "Oui. Nous commençons par un diagnostic technique et budgétaire avant de proposer une stratégie de reprise.",
    ],
    [
        'q' => "Le suivi est-il prévu pour la diaspora ?",
        'a' => "Oui. Le suivi digital fait partie de notre approche pour permettre une bonne lecture du projet à distance.",
    ],
    [
        'q' => "Aidez-vous aussi sur le financement ?",
        'a' => "Oui. Selon le profil et l'objectif, nous pouvons orienter vers Direct IBIG, des investisseurs partenaires ou des institutions financières.",
    ],
];

$homeStats = [
    'total' => 0,
    'city_total' => 0,
    'vedette_total' => 0,
    'exclusif_total' => 0,
];

$featuredProperties = [];
$heroProperty = null;

try {
    $statsStatement = $pdo->query(
        "SELECT
            COUNT(*) AS total,
            COUNT(DISTINCT NULLIF(TRIM(ville), '')) AS city_total,
            SUM(CASE WHEN LOWER(TRIM(COALESCE(niveau_visibilite, 'standard'))) = 'vedette' THEN 1 ELSE 0 END) AS vedette_total,
            SUM(CASE WHEN LOWER(TRIM(COALESCE(niveau_visibilite, 'standard'))) = 'exclusif' THEN 1 ELSE 0 END) AS exclusif_total
         FROM immo_biens
         WHERE visible = 1
           AND statut_publication = 'valide'"
    );
    $homeStats = $statsStatement->fetch(PDO::FETCH_ASSOC) ?: $homeStats;

    $featuredStatement = $pdo->query(
        "SELECT
            id, titre, slug, image_principale, prix, ville, quartier, type, transaction,
            niveau_visibilite, disponibilite, superficie, superficie_habitable, chambres,
            salles_bain, badge, priorite, created_at
         FROM immo_biens
         WHERE visible = 1
           AND statut_publication = 'valide'
           AND LOWER(TRIM(COALESCE(niveau_visibilite, 'standard'))) = 'vedette'
         ORDER BY COALESCE(priorite, 0) DESC, created_at DESC, id DESC
         LIMIT 4"
    );
    $featuredProperties = $featuredStatement->fetchAll(PDO::FETCH_ASSOC);

    if (count($featuredProperties) < 4) {
        $fallbackStatement = $pdo->query(
            "SELECT
                id, titre, slug, image_principale, prix, ville, quartier, type, transaction,
                niveau_visibilite, disponibilite, superficie, superficie_habitable, chambres,
                salles_bain, badge, priorite, created_at
             FROM immo_biens
             WHERE visible = 1
               AND statut_publication = 'valide'
             ORDER BY
                CASE LOWER(TRIM(COALESCE(niveau_visibilite, 'standard')))
                    WHEN 'exclusif' THEN 0
                    WHEN 'vedette' THEN 1
                    ELSE 2
                END,
                COALESCE(priorite, 0) DESC,
                created_at DESC,
                id DESC
             LIMIT 12"
        );

        $existingIds = array_map(static fn(array $item): int => (int) $item['id'], $featuredProperties);
        foreach ($fallbackStatement->fetchAll(PDO::FETCH_ASSOC) as $property) {
            if (in_array((int) $property['id'], $existingIds, true)) {
                continue;
            }

            $featuredProperties[] = $property;
            $existingIds[] = (int) $property['id'];

            if (count($featuredProperties) >= 4) {
                break;
            }
        }
    }

    $heroStatement = $pdo->query(
        "SELECT
            id, titre, slug, image_principale, prix, ville, quartier, type, transaction,
            niveau_visibilite, disponibilite, superficie, superficie_habitable, chambres,
            salles_bain, badge, priorite, created_at
         FROM immo_biens
         WHERE visible = 1
           AND statut_publication = 'valide'
         ORDER BY
            CASE LOWER(TRIM(COALESCE(niveau_visibilite, 'standard')))
                WHEN 'exclusif' THEN 0
                WHEN 'vedette' THEN 1
                ELSE 2
            END,
            COALESCE(priorite, 0) DESC,
            created_at DESC,
            id DESC
         LIMIT 1"
    );
    $heroProperty = $heroStatement->fetch(PDO::FETCH_ASSOC) ?: null;
} catch (Throwable $exception) {
    $featuredProperties = [];
    $heroProperty = null;
}

$heroMetrics = [
    [
        'value' => home_count_label((int) ($homeStats['total'] ?? 0)),
        'label' => 'biens actifs',
    ],
    [
        'value' => home_count_label((int) ($homeStats['city_total'] ?? 0)),
        'label' => 'villes couvertes',
    ],
    [
        'value' => home_count_label((int) ($homeStats['exclusif_total'] ?? 0)),
        'label' => 'exclusivités',
    ],
    [
        'value' => '360°',
        'label' => 'accompagnement',
    ],
];

include __DIR__ . '/includes/header.php';
?>

<style>
:root {
  --home-blue:#1B4FD8;
  --home-blue-dark:#1340B0;
  --home-red:#E8282A;
  --home-red-dark:#C01A1C;
  --home-gold:#FF5557;
  --home-ink:#101828;
  --home-ink-soft:#1d2939;
  --home-muted:#667085;
  --home-muted-2:#98a2b3;
  --home-line:#e4e7ec;
  --home-surface:#ffffff;
  --home-surface-soft:#f8fafc;
  --home-surface-warm:#FAF7F0;
  --home-shadow-sm:0 10px 28px rgba(15, 23, 42, 0.06);
  --home-shadow-md:0 20px 54px rgba(15, 23, 42, 0.10);
  --home-shadow-lg:0 30px 70px rgba(8, 39, 95, 0.20);
  --home-radius-sm:16px;
  --home-radius-md:22px;
  --home-radius-lg:30px;
  --home-shell:1260px;
}

.home-page {
  color:var(--home-ink);
  background:#fff;
}

.home-shell {
  width:min(var(--home-shell), calc(100% - 32px));
  margin:0 auto;
}

.home-section {
  padding:84px 0;
}

.home-section--soft {
  background:
    radial-gradient(circle at top right, rgba(232,40,42,.10), transparent 28%),
    linear-gradient(180deg, #FAF7F0 0%, #ffffff 100%);
}

.home-section--blue {
  background:
    radial-gradient(circle at top left, rgba(255,255,255,.08), transparent 20%),
    linear-gradient(135deg, #1340B0 0%, #1B4FD8 52%, #1E57E8 100%);
  color:#fff;
}

.home-section-head {
  display:flex;
  justify-content:space-between;
  align-items:end;
  gap:18px;
  flex-wrap:wrap;
  margin-bottom:28px;
}

.home-section-title {
  margin:0 0 10px;
  font-size:clamp(20px, 2vw, 30px);
  line-height:1.08;
  letter-spacing:-0.03em;
  color:var(--home-ink);
  font-weight:800;
}

.home-section--blue .home-section-title {
  color:#fff;
}

.home-section-intro {
  max-width:820px;
  margin:0;
  color:var(--home-muted);
  font-size:17px;
}

.home-section--blue .home-section-intro {
  color:rgba(255,255,255,.88);
}

.home-pill {
  display:inline-flex;
  align-items:center;
  gap:8px;
  min-height:36px;
  padding:0 14px;
  border-radius:999px;
  background:rgba(255,255,255,.12);
  border:1px solid rgba(255,255,255,.18);
  color:#fff;
  font-size:13px;
  font-weight:700;
}

.home-hero {
  position:relative;
  overflow:hidden;
  padding:34px 0 40px;
  background:
    radial-gradient(circle at 12% 12%, rgba(232,40,42,.18), transparent 30%),
    radial-gradient(circle at 86% 18%, rgba(232,40,42,.08), transparent 28%),
    linear-gradient(135deg, #1340B0 0%, #1B4FD8 55%, #1E57E8 100%);
  color:#fff;
}

.home-hero::before {
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

.home-hero__grid {
  position:relative;
  z-index:1;
  display:grid;
  grid-template-columns:minmax(0, 1.08fr) minmax(320px, .92fr);
  gap:34px;
  align-items:center;
}

.home-hero__eyebrow {
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
  letter-spacing:.02em;
}

.home-hero__eyebrow i {
  color:#FF5557;
}

.home-hero__title {
  margin:18px 0 16px;
  font-size:clamp(26px, 3vw, 44px);
  line-height:1.02;
  letter-spacing:-0.04em;
  font-weight:820;
  color:#fff;
}

.home-hero__text {
  max-width:720px;
  margin:0;
  color:rgba(255,255,255,.92);
  font-size:18px;
}

.home-hero__actions {
  display:flex;
  flex-wrap:wrap;
  gap:14px;
  margin-top:24px;
}

.home-button,
.home-button--outline,
.home-button--ghost,
.home-button--light,
.home-button--dark {
  display:inline-flex;
  align-items:center;
  justify-content:center;
  gap:10px;
  min-height:52px;
  padding:0 22px;
  border-radius:14px;
  border:1px solid transparent;
  font-size:15px;
  font-weight:750;
  text-decoration:none;
  transition:transform .2s ease, box-shadow .2s ease, background .2s ease;
}

.home-button:hover,
.home-button--outline:hover,
.home-button--ghost:hover,
.home-button--light:hover,
.home-button--dark:hover {
  transform:translateY(-2px);
}

.home-button {
  background:linear-gradient(135deg, var(--home-gold) 0%, #FF5557 100%);
  color:#2b1900;
  box-shadow:0 16px 36px rgba(232,40,42,.35);
}

.home-button--outline {
  background:rgba(255,255,255,.10);
  color:#fff;
  border-color:rgba(255,255,255,.24);
}

.home-button--ghost {
  background:#fff4ef;
  color:var(--home-red-dark);
  border-color:rgba(232,40,42,.12);
}

.home-button--light {
  background:#fff;
  color:var(--home-blue-dark);
  border-color:#d8e3f0;
}

.home-button--dark {
  background:var(--home-ink);
  color:#fff;
}

.home-hero__metrics {
  display:grid;
  grid-template-columns:repeat(4, minmax(0, 1fr));
  gap:12px;
  margin-top:26px;
}

.home-metric {
  padding:16px 14px;
  border-radius:18px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.14);
  backdrop-filter:blur(8px);
}

.home-metric strong {
  display:block;
  margin-bottom:4px;
  font-size:26px;
  line-height:1;
  font-weight:800;
  color:#fff;
}

.home-metric span {
  color:rgba(255,255,255,.82);
  font-size:13px;
  font-weight:600;
}

.home-hero__note {
  display:inline-flex;
  align-items:center;
  gap:8px;
  margin-top:18px;
  color:rgba(255,255,255,.92);
  font-size:13px;
  font-weight:600;
}

.home-hero__note i {
  color:#FF5557;
}

.home-spotlight {
  position:relative;
  z-index:1;
}

.home-spotlight-card {
  overflow:hidden;
  border-radius:28px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.18);
  box-shadow:var(--home-shadow-lg);
  backdrop-filter:blur(8px);
}

.home-spotlight-card__media {
  position:relative;
  display:block;
  aspect-ratio:16 / 11;
  overflow:hidden;
}

.home-spotlight-card__media img {
  width:100%;
  height:100%;
  object-fit:cover;
}

.home-spotlight-card__badge {
  position:absolute;
  top:16px;
  left:16px;
  display:inline-flex;
  align-items:center;
  min-height:34px;
  padding:0 14px;
  border-radius:999px;
  background:#fff;
  color:var(--home-blue-dark);
  font-size:12px;
  font-weight:750;
  box-shadow:var(--home-shadow-sm);
}

.home-spotlight-card__body {
  padding:22px;
}

.home-spotlight-card__eyebrow {
  display:flex;
  flex-wrap:wrap;
  gap:10px;
  margin-bottom:10px;
  color:rgba(255,255,255,.78);
  font-size:12px;
  font-weight:700;
}

.home-spotlight-card__title {
  margin:0 0 10px;
  font-size:26px;
  line-height:1.1;
  font-weight:780;
  color:#fff;
}

.home-spotlight-card__meta {
  margin:0 0 14px;
  color:rgba(255,255,255,.84);
  font-size:14px;
}

.home-spotlight-card__price {
  margin:0 0 16px;
  color:#FF5557;
  font-size:28px;
  font-weight:820;
}

.home-token-row {
  display:flex;
  flex-wrap:wrap;
  gap:8px;
}

.home-token {
  display:inline-flex;
  align-items:center;
  gap:6px;
  min-height:32px;
  padding:0 10px;
  border-radius:999px;
  background:rgba(255,255,255,.10);
  border:1px solid rgba(255,255,255,.14);
  color:#fff;
  font-size:12px;
  font-weight:650;
}

.home-search-wrap {
  position:relative;
  z-index:2;
  margin-top:28px;
}

.home-search {
  display:grid;
  gap:18px;
  padding:22px;
  border-radius:28px;
  background:#fff;
  box-shadow:var(--home-shadow-md);
  border:1px solid rgba(16,24,40,.06);
}

.home-search__grid {
  display:grid;
  grid-template-columns:1.25fr 1fr 1fr 1fr 1fr 170px;
  gap:12px;
  align-items:end;
}

.home-field {
  display:flex;
  flex-direction:column;
  gap:8px;
}

.home-field label {
  color:var(--home-muted);
  font-size:12px;
  font-weight:750;
  text-transform:uppercase;
  letter-spacing:.05em;
}

.home-field input,
.home-field select,
.home-field textarea {
  width:100%;
  min-height:48px;
  padding:0 14px;
  border-radius:14px;
  border:1px solid #d9e0ea;
  background:#fff;
  color:var(--home-ink);
  font-size:14px;
  outline:none;
  transition:border-color .2s ease, box-shadow .2s ease;
}

.home-field textarea {
  min-height:140px;
  padding:14px;
  resize:vertical;
}

.home-field input:focus,
.home-field select:focus,
.home-field textarea:focus {
  border-color:rgba(27,79,216,.42);
  box-shadow:0 0 0 4px rgba(27,79,216,.08);
}

.home-search__cta {
  width:100%;
}

.home-chip-links {
  display:flex;
  flex-wrap:wrap;
  gap:10px;
}

.home-chip-link {
  display:inline-flex;
  align-items:center;
  min-height:38px;
  padding:0 14px;
  border-radius:999px;
  background:var(--home-surface-warm);
  border:1px solid rgba(232,40,42,.10);
  color:var(--home-red-dark);
  font-size:13px;
  font-weight:720;
  transition:transform .2s ease, background .2s ease;
}

.home-chip-link:hover {
  transform:translateY(-2px);
  background:#fff0e6;
}

.home-grid-4 {
  display:grid;
  grid-template-columns:repeat(4, minmax(0, 1fr));
  gap:18px;
}

.home-grid-3 {
  display:grid;
  grid-template-columns:repeat(3, minmax(0, 1fr));
  gap:22px;
}

.home-trust-card,
.home-service-card,
.home-highlight-card,
.home-fund-card,
.home-step-card,
.home-reassurance-card,
.home-testimonial-card,
.home-lead-card,
.home-form-card,
.home-property-card {
  background:var(--home-surface);
  border:1px solid var(--home-line);
  border-radius:22px;
  box-shadow:var(--home-shadow-sm);
}

.home-trust-card,
.home-service-card,
.home-highlight-card,
.home-fund-card,
.home-step-card,
.home-reassurance-card,
.home-testimonial-card {
  padding:24px 22px;
}

.home-trust-card__icon,
.home-service-card__icon,
.home-highlight-card__icon {
  width:56px;
  height:56px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:18px;
  background:linear-gradient(135deg, rgba(27,79,216,.08), rgba(232,40,42,.12));
  color:var(--home-blue);
  font-size:22px;
  margin-bottom:14px;
}

.home-trust-card h3,
.home-service-card h3,
.home-highlight-card h3,
.home-fund-card h3,
.home-step-card h3,
.home-reassurance-card h3 {
  margin:0 0 8px;
  font-size:20px;
  line-height:1.18;
  color:var(--home-ink);
  font-weight:780;
}

.home-trust-card p,
.home-service-card p,
.home-highlight-card p,
.home-fund-card p,
.home-step-card p,
.home-reassurance-card p {
  margin:0;
  color:var(--home-muted);
  font-size:15px;
}

.home-property-card {
  overflow:hidden;
}

.home-empty-card {
  padding:28px 24px;
  border:1px dashed #d7deea;
  border-radius:22px;
  background:linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
  box-shadow:var(--home-shadow-sm);
  color:var(--home-muted);
}

.home-empty-card h3 {
  margin:0 0 8px;
  color:var(--home-ink);
  font-size:22px;
  line-height:1.15;
  font-weight:780;
}

.home-empty-card p {
  margin:0;
  font-size:15px;
}

.home-property-card__media {
  position:relative;
  display:block;
  aspect-ratio:16 / 10;
  overflow:hidden;
}

.home-property-card__media img {
  width:100%;
  height:100%;
  object-fit:cover;
  transition:transform .35s ease;
}

.home-property-card:hover .home-property-card__media img {
  transform:scale(1.05);
}

.home-property-card__badge {
  position:absolute;
  top:14px;
  left:14px;
  display:inline-flex;
  align-items:center;
  min-height:34px;
  padding:0 12px;
  border-radius:999px;
  background:#fff;
  color:var(--home-red-dark);
  font-size:12px;
  font-weight:760;
  box-shadow:var(--home-shadow-sm);
}

.home-property-card__body {
  display:flex;
  flex-direction:column;
  gap:10px;
  padding:18px;
}

.home-property-card__eyebrow {
  display:flex;
  flex-wrap:wrap;
  gap:10px;
  color:var(--home-muted);
  font-size:12px;
  font-weight:700;
}

.home-property-card__title {
  margin:0;
  color:var(--home-ink);
  font-size:18px;
  line-height:1.3;
  font-weight:760;
}

.home-property-card__location {
  color:var(--home-muted);
  font-size:14px;
}

.home-property-card__tokens {
  display:flex;
  flex-wrap:wrap;
  gap:8px;
}

.home-property-card__token {
  display:inline-flex;
  align-items:center;
  min-height:30px;
  padding:0 10px;
  border-radius:999px;
  background:#f8fafc;
  border:1px solid #eef2f6;
  color:var(--home-ink-soft);
  font-size:12px;
  font-weight:650;
}

.home-property-card__footer {
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:12px;
  margin-top:auto;
}

.home-property-card__price {
  color:var(--home-red);
  font-size:20px;
  font-weight:820;
}

.home-service-card {
  display:flex;
  flex-direction:column;
  height:100%;
}

.home-media-card {
  overflow:hidden;
  border-radius:24px;
  border:1px solid var(--home-line);
  background:#fff;
  box-shadow:var(--home-shadow-md);
}

.home-media-card img {
  width:100%;
  height:520px;
  object-fit:cover;
}

.home-media-card__overlay {
  padding:24px;
  background:linear-gradient(180deg, rgba(255,255,255,.96) 0%, rgba(248,250,252,.96) 100%);
}

.home-media-card__overlay h3 {
  margin:0 0 8px;
  font-size:24px;
  line-height:1.12;
  font-weight:800;
}

.home-media-card__overlay p {
  margin:0;
  color:var(--home-muted);
  font-size:15px;
}

.home-split {
  display:grid;
  grid-template-columns:minmax(0, .96fr) minmax(0, 1.04fr);
  gap:34px;
  align-items:center;
}

.home-list {
  margin:20px 0 0;
  padding:0;
  list-style:none;
  display:grid;
  gap:12px;
}

.home-list li {
  display:flex;
  gap:12px;
  align-items:flex-start;
  color:var(--home-ink);
  font-size:15px;
  font-weight:650;
}

.home-list li::before {
  content:"";
  width:24px;
  height:24px;
  min-width:24px;
  margin-top:2px;
  border-radius:999px;
  background:linear-gradient(135deg, rgba(27,79,216,.12), rgba(232,40,42,.12));
  box-shadow:inset 0 0 0 6px rgba(27,79,216,.14);
}

.home-fund-card__tag {
  display:inline-flex;
  align-items:center;
  min-height:34px;
  padding:0 12px;
  border-radius:999px;
  background:#fff4ef;
  color:var(--home-red-dark);
  font-size:12px;
  font-weight:760;
  margin-bottom:14px;
}

.home-step-card {
  position:relative;
  overflow:hidden;
}

.home-step-card::before {
  content:"";
  position:absolute;
  top:0;
  left:0;
  width:100%;
  height:4px;
  background:linear-gradient(90deg, var(--home-blue), var(--home-red));
}

.home-step-card__number {
  width:54px;
  height:54px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:18px;
  background:linear-gradient(135deg, var(--home-blue), var(--home-red));
  color:#fff;
  font-size:22px;
  font-weight:820;
  box-shadow:0 14px 28px rgba(27,79,216,.20);
  margin-bottom:16px;
}

.home-testimonials {
  overflow:hidden;
  mask-image:linear-gradient(90deg, transparent 0, #000 6%, #000 94%, transparent 100%);
}

.home-testimonials__track {
  display:flex;
  gap:22px;
  width:max-content;
  animation:homeTestimonials 72s linear infinite;
}

.home-testimonials__track:hover {
  animation-play-state:paused;
}

.home-testimonial-card {
  width:320px;
}

.home-testimonial-card::before {
  content:"★★★★★";
  display:block;
  margin-bottom:12px;
  color:var(--home-gold);
  letter-spacing:2px;
  font-size:15px;
}

.home-testimonial-card p {
  margin:0 0 14px;
  color:var(--home-ink-soft);
  font-size:15px;
}

.home-testimonial-card strong {
  color:var(--home-red);
  font-size:14px;
  font-weight:760;
}

.home-lead {
  display:grid;
  grid-template-columns:minmax(0, .92fr) minmax(0, 1.08fr);
  gap:28px;
  align-items:start;
}

.home-lead-card,
.home-form-card {
  padding:28px 24px;
}

.home-lead-card h3,
.home-form-card h3 {
  margin:0 0 10px;
  font-size:30px;
  line-height:1.08;
  font-weight:800;
}

.home-lead-card p,
.home-form-card p {
  margin:0;
  color:var(--home-muted);
  font-size:15px;
}

.home-contact-lines {
  display:grid;
  gap:14px;
  margin-top:20px;
}

.home-contact-line {
  display:flex;
  gap:14px;
  align-items:flex-start;
  padding:14px;
  border-radius:16px;
  background:#fafbfd;
  border:1px solid #eef2f6;
}

.home-contact-line__icon {
  width:46px;
  height:46px;
  min-width:46px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:14px;
  background:#eef4ff;
  color:var(--home-blue);
  font-size:18px;
}

.home-contact-line strong {
  display:block;
  margin-bottom:3px;
  font-size:15px;
  font-weight:760;
}

.home-contact-line span {
  color:var(--home-muted);
  font-size:14px;
}

.home-form-grid {
  display:grid;
  grid-template-columns:repeat(2, minmax(0, 1fr));
  gap:16px;
  margin-top:18px;
}

.home-form-grid .home-field--full {
  grid-column:1 / -1;
}

.home-form-note {
  margin-top:12px;
  color:var(--home-muted);
  font-size:13px;
}

.home-faq-grid {
  display:grid;
  gap:14px;
  max-width:920px;
  margin:0 auto;
}

.home-faq {
  border-radius:18px;
  border:1px solid var(--home-line);
  background:#fff;
  overflow:hidden;
  box-shadow:var(--home-shadow-sm);
}

.home-faq summary {
  list-style:none;
  cursor:pointer;
  position:relative;
  padding:20px 22px;
  padding-right:56px;
  font-size:17px;
  font-weight:760;
}

.home-faq summary::-webkit-details-marker {
  display:none;
}

.home-faq summary::after {
  content:"+";
  position:absolute;
  right:22px;
  top:50%;
  transform:translateY(-50%);
  color:var(--home-red);
  font-size:28px;
  font-weight:300;
}

.home-faq[open] summary::after {
  content:"−";
}

.home-faq__content {
  padding:0 22px 20px;
  color:var(--home-muted);
  font-size:15px;
}

.home-final-cta {
  position:relative;
  overflow:hidden;
}

.home-final-cta__inner {
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:22px;
  flex-wrap:wrap;
}

.home-final-cta__text h2 {
  margin:0 0 8px;
  font-size:clamp(22px, 2.5vw, 36px);
  line-height:1.02;
  letter-spacing:-0.03em;
  font-weight:820;
}

.home-final-cta__text p {
  margin:0;
  max-width:760px;
  color:rgba(255,255,255,.90);
  font-size:17px;
}

.home-final-cta__actions {
  display:flex;
  flex-wrap:wrap;
  gap:14px;
}

@keyframes homeTestimonials {
  0% { transform:translateX(0); }
  100% { transform:translateX(-50%); }
}

@media (max-width: 1200px) {
  .home-hero__grid,
  .home-split,
  .home-lead {
    grid-template-columns:1fr;
  }

  .home-search__grid {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }

  .home-grid-4 {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }

  .home-grid-3 {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }

  .home-hero__metrics {
    grid-template-columns:repeat(2, minmax(0, 1fr));
  }
}

@media (max-width: 760px) {
  .home-shell {
    width:min(var(--home-shell), calc(100% - 22px));
  }

  .home-section {
    padding:68px 0;
  }

  .home-hero {
    padding:24px 0 28px;
  }

  .home-hero__title {
    font-size:clamp(20px, 2.2vw, 32px);
  }

  .home-hero__text,
  .home-section-intro,
  .home-final-cta__text p {
    font-size:16px;
  }

  .home-grid-4,
  .home-grid-3,
  .home-search__grid,
  .home-form-grid,
  .home-hero__metrics {
    grid-template-columns:1fr;
  }

  .home-hero__actions,
  .home-final-cta__actions {
    flex-direction:column;
    align-items:stretch;
  }

  .home-button,
  .home-button--outline,
  .home-button--ghost,
  .home-button--light,
  .home-button--dark {
    width:100%;
  }

  .home-media-card img {
    height:380px;
  }

  .home-testimonial-card {
    width:290px;
  }
}
</style>

<main class="home-page">
  <section class="home-hero" style="padding:24px 0 28px;">
    <div class="home-shell">
      <div class="home-search-wrap">
        <form class="home-search" action="<?= home_h(url('tous_les_biens.php')) ?>" method="get">
          <div class="home-search__grid">
            <div class="home-field">
              <label for="home-ville">Ville</label>
              <input id="home-ville" type="text" name="ville" placeholder="Abidjan, Cocody, Bingerville">
            </div>

            <div class="home-field">
              <label for="home-type">Type</label>
              <select id="home-type" name="type">
                <option value="">Tous</option>
                <option value="villa">Villa</option>
                <option value="appartement">Appartement</option>
                <option value="terrain">Terrain</option>
                <option value="immeuble">Immeuble</option>
                <option value="bureau">Bureau</option>
              </select>
            </div>

            <div class="home-field">
              <label for="home-transaction">Transaction</label>
              <select id="home-transaction" name="transaction">
                <option value="">Toutes</option>
                <option value="vente">Vente</option>
                <option value="location">Location</option>
              </select>
            </div>

            <div class="home-field">
              <label for="home-prix-min">Prix min.</label>
              <input id="home-prix-min" type="text" name="prix_min" placeholder="50 000 000">
            </div>

            <div class="home-field">
              <label for="home-prix-max">Prix max.</label>
              <input id="home-prix-max" type="text" name="prix_max" placeholder="150 000 000">
            </div>

            <button class="home-button--dark home-search__cta" type="submit" onclick="trackEvent('search_home_properties');">
              Rechercher
            </button>
          </div>

          <div class="home-chip-links">
            <?php foreach ($quickCategories as $category): ?>
              <a class="home-chip-link" href="<?= home_h($category['url']) ?>" onclick="trackEvent('quick_category_home');">
                <?= home_h($category['label']) ?>
              </a>
            <?php endforeach; ?>
          </div>
        </form>
      </div>
    </div>
  </section>

  <section class="home-section">
    <div class="home-shell">
      <div class="home-grid-4">
        <?php foreach ($trustPoints as $point): ?>
          <article class="home-trust-card">
            <div class="home-trust-card__icon"><i class="<?= home_h($point['icon']) ?>"></i></div>
            <h3><?= home_h($point['title']) ?></h3>
            <p><?= home_h($point['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-section home-section--soft">
    <div class="home-shell">
      <div class="home-section-head">
        <div>
          <h2 class="home-section-title">Une plateforme pensée pour mieux orienter les décisions immobilières</h2>
          <p class="home-section-intro">
            La page d'accueil doit inspirer confiance, accélérer la lecture du besoin et rendre l'accès aux opportunités plus direct.
            Nous avons donc recentré l'expérience sur la lisibilité, la valeur des biens et le passage à l'action.
          </p>
        </div>
      </div>

      <div class="home-grid-4">
        <?php foreach ($marketHighlights as $item): ?>
          <article class="home-highlight-card">
            <div class="home-highlight-card__icon"><i class="<?= home_h($item['icon']) ?>"></i></div>
            <h3><?= home_h($item['title']) ?></h3>
            <p><?= home_h($item['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-section">
    <div class="home-shell">
      <div class="home-section-head">
        <div>
          <h2 class="home-section-title">Biens en vedette</h2>
          <p class="home-section-intro">
            Une sélection de biens attractifs, visibles et prêts à générer une prise de contact rapide.
          </p>
        </div>
        <a href="<?= home_h(url('tous_les_biens.php')) ?>" class="home-button--dark" onclick="trackEvent('view_all_properties_home');">
          Voir tous les biens
        </a>
      </div>

      <div class="home-grid-3">
        <?php if (!empty($featuredProperties)): ?>
          <?php foreach ($featuredProperties as $property): ?>
            <?php
            $propertyUrl = home_property_url($property);
            $propertyImage = home_image_path((string) ($property['image_principale'] ?? ''));
            $propertyTitle = home_fix_text((string) ($property['titre'] ?? 'Bien immobilier'));
            $propertyType = home_type_label($property['type'] ?? '');
            $propertyTransaction = home_transaction_label($property['transaction'] ?? '');
            $propertyLocation = home_location_label($property);
            $propertyPrice = home_price_label($property['prix'] ?? 0);
            $propertyTokens = home_property_tokens($property);
            ?>
            <article class="home-property-card">
              <a class="home-property-card__media" href="<?= home_h($propertyUrl) ?>">
                <img src="<?= home_h($propertyImage) ?>" alt="<?= home_h($propertyTitle) ?>" loading="lazy" decoding="async">
                <span class="home-property-card__badge"><?= home_h(home_visibility_badge($property)) ?></span>
              </a>
              <div class="home-property-card__body">
                <div class="home-property-card__eyebrow">
                  <span><?= home_h($propertyType) ?></span>
                  <?php if ($propertyTransaction !== ''): ?>
                    <span><?= home_h($propertyTransaction) ?></span>
                  <?php endif; ?>
                </div>
                <h3 class="home-property-card__title"><?= home_h($propertyTitle) ?></h3>
                <div class="home-property-card__location"><?= home_h($propertyLocation) ?></div>

                <?php if (!empty($propertyTokens)): ?>
                  <div class="home-property-card__tokens">
                    <?php foreach ($propertyTokens as $token): ?>
                      <span class="home-property-card__token"><?= home_h($token) ?></span>
                    <?php endforeach; ?>
                  </div>
                <?php endif; ?>

                <div class="home-property-card__footer">
                  <div class="home-property-card__price"><?= home_h($propertyPrice) ?></div>
                  <a href="<?= home_h($propertyUrl) ?>" class="home-button--light" onclick="trackEvent('click_featured_property_home');">
                    Voir
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        <?php else: ?>
          <article class="home-empty-card">
            <h3>Les biens en vedette seront affichés ici.</h3>
            <p>La section reste prête même si aucune annonce vedette n'est remontée temporairement par la base.</p>
          </article>
        <?php endif; ?>
      </div>

      <div class="home-section-head" style="margin-top:26px;">
        <div>
          <h3 class="home-section-title" style="font-size:26px;margin-bottom:6px;">Vous cherchez un bien précis ou un projet à fort potentiel ?</h3>
          <p class="home-section-intro">Maison, terrain, immeuble, location premium, dossier diaspora ou opportunité investisseur.</p>
        </div>
        <a href="<?= home_h(url('contact.php')) ?>" class="home-button--dark" onclick="trackEvent('property_help_home');">
          Être orienté
        </a>
      </div>
    </div>
  </section>

  <section class="home-section home-section--soft">
    <div class="home-shell">
      <div class="home-section-head">
        <div>
          <h2 class="home-section-title">Nos domaines d'intervention</h2>
          <p class="home-section-intro">
            Une approche globale qui relie acquisition, exécution, gestion, financement et valorisation.
          </p>
        </div>
      </div>

      <div class="home-grid-4">
        <?php foreach ($services as $service): ?>
          <article class="home-service-card">
            <div class="home-service-card__icon"><i class="<?= home_h($service['icon']) ?>"></i></div>
            <h3><?= home_h($service['title']) ?></h3>
            <p><?= home_h($service['text']) ?></p>
            <div style="margin-top:auto;padding-top:16px;">
              <a href="<?= home_h($service['url']) ?>" class="home-button--dark" onclick="trackEvent('click_service_home');">
                <?= home_h($service['cta']) ?>
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-section">
    <div class="home-shell">
      <div class="home-section-head">
        <div>
          <h2 class="home-section-title">Finition de chantiers, rénovation et construction</h2>
          <p class="home-section-intro">
            Nous intervenons quand un projet doit être relancé, restructuré, terminé ou repositionné pour retrouver sa valeur.
          </p>
        </div>
      </div>

      <div class="home-grid-3">
        <?php foreach ($constructionServices as $service): ?>
          <article class="home-property-card">
            <div class="home-property-card__media">
              <img src="<?= home_h($service['image']) ?>" alt="<?= home_h($service['title']) ?>" loading="lazy" decoding="async">
            </div>
            <div class="home-property-card__body">
              <div class="home-property-card__eyebrow">
                <span>BTP</span>
                <span>Suivi terrain</span>
              </div>
              <h3 class="home-property-card__title"><?= home_h($service['title']) ?></h3>
              <div class="home-property-card__location"><?= home_h($service['text']) ?></div>
              <div class="home-property-card__tokens">
                <span class="home-property-card__token">Exécution</span>
                <span class="home-property-card__token">Coordination</span>
                <span class="home-property-card__token">Reporting</span>
              </div>
              <div class="home-property-card__footer">
                <span></span>
                <a href="<?= home_h($service['url']) ?>" class="home-button--light" onclick="trackEvent('click_btp_service_home');">
                  Voir le service
                </a>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-section home-section--soft">
    <div class="home-shell home-split">
      <div class="home-media-card">
        <img src="<?= home_h(url('assets/img/gestion-locative3.jpg')) ?>" alt="Suivi diaspora avec IBIG IMMO TRUST">
        <div class="home-media-card__overlay">
          <h3>Investir depuis l'étranger avec plus de contrôle</h3>
          <p>Suivi visuel, points d'étape, coordination terrain et meilleure lecture des dépenses et des délais.</p>
        </div>
      </div>

      <div>
        <h2 class="home-section-title">Une offre pensée pour la diaspora</h2>
        <p class="home-section-intro">
          Si vous vivez hors de Côte d'Ivoire, nous mettons en place un cadre de suivi lisible pour construire,
          finir, rénover, exploiter ou sécuriser votre bien sans naviguer à l'aveugle.
        </p>

        <ul class="home-list">
          <li>Comptes rendus périodiques avec visuels et synthèses d'avancement</li>
          <li>Coordination des équipes, contrôle qualité et suivi budgétaire</li>
          <li>Orientation sur les choix techniques, locatifs ou financiers les plus cohérents</li>
          <li>Accompagnement jusqu'à la valorisation, la location ou la remise en exploitation</li>
        </ul>

        <div class="home-hero__actions" style="margin-top:24px;">
          <a href="<?= home_h(url('diaspora.php')) ?>" class="home-button--dark" onclick="trackEvent('click_diaspora_home');">
            Voir l'offre diaspora
          </a>
          <a href="<?= home_h(url('rdv.php')) ?>" class="home-button--light" onclick="trackEvent('click_diaspora_rdv_home');">
            Programmer un échange
          </a>
        </div>
      </div>
    </div>
  </section>

  <section class="home-section">
    <div class="home-shell">
      <div class="home-section-head">
        <div>
          <h2 class="home-section-title">Financement : plusieurs logiques selon votre profil</h2>
          <p class="home-section-intro">
            Nous cherchons le montage le plus cohérent selon le potentiel du bien, la destination du projet et votre capacité d'engagement.
          </p>
        </div>
      </div>

      <div class="home-grid-3">
        <?php foreach ($fundingModels as $model): ?>
          <article class="home-fund-card">
            <span class="home-fund-card__tag"><?= home_h($model['tag']) ?></span>
            <h3><?= home_h($model['title']) ?></h3>
            <p><?= home_h($model['text']) ?></p>
            <div style="margin-top:18px;">
              <a href="<?= home_h($model['url']) ?>" class="home-button--dark" onclick="trackEvent('click_funding_model_home');">
                En savoir plus
              </a>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-section home-section--soft">
    <div class="home-shell">
      <div class="home-section-head">
        <div>
          <h2 class="home-section-title">Un processus plus clair, plus rassurant, plus pilotable</h2>
          <p class="home-section-intro">
            Un projet immobilier ne se limite pas à un bien ou à un chantier. Il faut un cadre de lecture, d'exécution et de décision.
          </p>
        </div>
      </div>

      <div class="home-grid-4">
        <?php foreach ($processSteps as $step): ?>
          <article class="home-step-card">
            <div class="home-step-card__number"><?= home_h($step['number']) ?></div>
            <h3><?= home_h($step['title']) ?></h3>
            <p><?= home_h($step['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-section">
    <div class="home-shell">
      <div class="home-section-head">
        <div>
          <h2 class="home-section-title">Pourquoi cette approche inspire davantage confiance</h2>
          <p class="home-section-intro">
            Parce que la valeur ne vient pas seulement d'une annonce ou d'un devis, mais de la manière dont le projet est cadré, lu et exécuté.
          </p>
        </div>
      </div>

      <div class="home-grid-4">
        <?php foreach ($reassuranceCards as $card): ?>
          <article class="home-reassurance-card">
            <h3><?= home_h($card['title']) ?></h3>
            <p><?= home_h($card['text']) ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-section home-section--soft">
    <div class="home-shell">
      <div class="home-section-head">
        <div>
          <h2 class="home-section-title">Ils nous font confiance</h2>
          <p class="home-section-intro">
            Des retours qui parlent de coordination, de visibilité, de reprise de chantier et de suivi à distance.
          </p>
        </div>
      </div>

      <div class="home-testimonials" aria-label="Témoignages clients">
        <div class="home-testimonials__track">
          <?php foreach (array_merge($testimonials, $testimonials) as $testimonial): ?>
            <article class="home-testimonial-card">
              <p><?= home_h($testimonial['text']) ?></p>
              <strong><?= home_h($testimonial['name']) ?></strong>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="home-section">
    <div class="home-shell home-lead">
      <aside class="home-lead-card">
        <h3>Parlez-nous de votre projet immobilier</h3>
        <p>
          Terrain, maison, immeuble, chantier inachevé, rénovation, location ou financement :
          nous pouvons vous orienter vers la bonne lecture et la bonne prochaine étape.
        </p>

        <ul class="home-list">
          <li>Analyse rapide du besoin et de l'objectif</li>
          <li>Orientation vers la solution la plus pertinente</li>
          <li>Prise de contact structurée avec un cadre clair</li>
        </ul>

        <div class="home-contact-lines">
          <div class="home-contact-line">
            <div class="home-contact-line__icon"><i class="fa-solid fa-phone-volume"></i></div>
            <div>
              <strong>Téléphone et WhatsApp</strong>
              <span>(+225) 05 84 43 74 74, 01 53 59 55 44, 05 65 90 47 79</span>
            </div>
          </div>

          <div class="home-contact-line">
            <div class="home-contact-line__icon"><i class="fa-solid fa-envelope"></i></div>
            <div>
              <strong>Email</strong>
              <span>contact@ibigimmotrust.com</span>
            </div>
          </div>

          <div class="home-contact-line">
            <div class="home-contact-line__icon"><i class="fa-solid fa-location-dot"></i></div>
            <div>
              <strong>Zone d'intervention</strong>
              <span>Abidjan et autres localités selon la nature du projet.</span>
            </div>
          </div>
        </div>
      </aside>

      <div class="home-form-card">
        <h3>Décrivez votre besoin</h3>
        <p>Quelques informations suffisent pour orienter l'échange et préparer la suite.</p>

        <form action="<?= home_h(url('contact.php')) ?>" method="get">
          <div class="home-form-grid">
            <div class="home-field">
              <label for="nom">Nom complet</label>
              <input id="nom" type="text" name="nom" placeholder="Votre nom complet">
            </div>

            <div class="home-field">
              <label for="phone">Téléphone ou WhatsApp</label>
              <input id="phone" type="tel" name="phone" placeholder="Votre contact">
            </div>

            <div class="home-field">
              <label for="projet">Type de projet</label>
              <select id="projet" name="projet">
                <option value="">Sélectionner</option>
                <option value="achat">Achat de bien</option>
                <option value="vente">Vente de bien</option>
                <option value="location">Location</option>
                <option value="gestion-locative">Gestion locative</option>
                <option value="chantier">Chantier inachevé</option>
                <option value="renovation">Rénovation</option>
                <option value="construction">Construction</option>
                <option value="financement">Financement</option>
              </select>
            </div>

            <div class="home-field">
              <label for="zone">Zone ou ville</label>
              <input id="zone" type="text" name="zone" placeholder="Ex. Cocody, Bingerville, Yamoussoukro">
            </div>

            <div class="home-field">
              <label for="budget_estime">Budget estimatif</label>
              <input id="budget_estime" type="text" name="budget_estime" placeholder="Ex. 30 000 000 FCFA">
            </div>

            <div class="home-field">
              <label for="delai">Délai souhaité</label>
              <input id="delai" type="text" name="delai" placeholder="Ex. Immédiat, 3 mois, 6 mois">
            </div>

            <div class="home-field home-field--full">
              <label for="details">Détails du projet</label>
              <textarea id="details" name="details" placeholder="Décrivez le besoin, la localisation, le niveau d'avancement, le budget estimatif, les contraintes et l'objectif recherché."></textarea>
            </div>

            <div class="home-field home-field--full">
              <button type="submit" class="home-button" onclick="trackEvent('submit_home_project_form');">
                Envoyer ma demande
              </button>
              <div class="home-form-note">
                En envoyant cette demande, vous manifestez votre intérêt pour un accompagnement, une orientation ou une étude de faisabilité.
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  <section class="home-section home-section--soft">
    <div class="home-shell">
      <div class="home-section-head" style="justify-content:center;text-align:center;">
        <div>
          <h2 class="home-section-title">Questions fréquentes</h2>
          <p class="home-section-intro" style="margin:0 auto;">
            Quelques réponses rapides pour les propriétaires, investisseurs et membres de la diaspora.
          </p>
        </div>
      </div>

      <div class="home-faq-grid">
        <?php foreach ($faqs as $faq): ?>
          <details class="home-faq">
            <summary><?= home_h($faq['q']) ?></summary>
            <div class="home-faq__content"><?= home_h($faq['a']) ?></div>
          </details>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="home-section home-section--blue home-final-cta">
    <div class="home-shell home-final-cta__inner">
      <div class="home-final-cta__text">
        <h2>Un projet immobilier, BTP ou financement à structurer ?</h2>
        <p>
          Nos équipes sont prêtes à vous accompagner avec une méthode plus lisible, plus professionnelle et plus orientée résultat.
        </p>
      </div>

      <div class="home-final-cta__actions">
        <a href="<?= home_h(url('contact.php')) ?>" class="home-button" onclick="trackEvent('cta_final_contact_home');">
          Décrire mon projet
        </a>
        <a href="<?= home_h(url('rdv.php')) ?>" class="home-button--light" onclick="trackEvent('cta_final_rdv_home');">
          Prendre rendez-vous
        </a>
      </div>
    </div>
  </section>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>
