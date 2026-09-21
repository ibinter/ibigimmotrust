<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/tracker.php';

$currentPage = 'biens';

function bien_h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function bien_fix_text(?string $value): string
{
    $text = trim((string) $value);
    if ($text === '') {
        return '';
    }

    $previous = null;
    $tries = 0;

    while ($tries < 3 && $text !== $previous) {
        $previous = $text;
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if (preg_match('/(?:Ã.|â.|Â|Å.|œ|�)/u', $text)) {
            $converted = @iconv('Windows-1252', 'UTF-8//IGNORE', $text);
            if (is_string($converted) && $converted !== '') {
                $text = $converted;
            }
        }

        $tries++;
    }

    $text = strtr($text, [
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
        'Å“' => 'œ',
        'Å’' => 'Œ',
        'â€™' => "'",
        'â€˜' => "'",
        'â€œ' => '"',
        'â€' => '"',
        'â€“' => '-',
        'â€”' => '-',
        'â€¦' => '...',
        'â€¢' => '•',
        'âœ“' => '✓',
        'âœ”' => '✔',
        'âžœ' => '➜',
        'Â ' => ' ',
        'Â' => '',
        "\xc2\xa0" => ' ',
    ]);

    $text = preg_replace('/\x{FFFD}+/u', '', $text);
    $text = preg_replace('/[^\P{C}\n\t]+/u', '', $text);
    $text = strtr($text, [
        'Ãƒ¢ââ€š¬ââ‚¬œ' => ' - ',
        'Ãƒ°Ã…¸ââ‚¬Â¹' => '•',
        'Ãƒ¢ââ€š¬Â¢' => '•',
        'Ãƒ¢ââ€š¬ââ€ž¢' => '’',
        'Ãƒ°Ã…¸ââ‚¬œââ‚¬Å¾' => 'Document',
        'Ãƒ°Ã…¸ââ‚¬œ' => 'Localisation',
        'Ãƒ°Ã…¸ââ‚¬â„¢Â°' => 'Prix de vente',
        'bÃƒÆ’Â¢tie' => 'bâtie',
        'mÃƒâ€šÂ²' => 'm²',
        'situÃƒÆ’Â©e' => 'située',
        'piÃƒÆ’Â¨ces' => 'pièces',
        'DÃƒÆ’Â©pendance' => 'Dépendance',
        'CaractÃƒÆ’Â©ristiques' => 'Caractéristiques',
        'intÃƒÆ’Â©grÃƒÆ’Â©' => 'intégré',
        'idÃƒÆ’Â©al' => 'idéal',
        'activitÃƒÆ’Â©' => 'activité',
        'PossibilitÃƒÆ’Â©' => 'Possibilité',
        'ArrÃƒÆ’ÂªtÃƒÆ’Â©' => 'Arrêté',
        'DÃƒÆ’Â©finitive' => 'Définitive',
        'dÃƒ¢ââ€š¬ââ€ž¢eau' => 'd’eau',
        'dÃƒ¢ââ€š¬ââ€ž¢extension' => 'd’extension',
        'deÃƒâ€šÂ Plateau ÃƒÆ’Â' => 'de Plateau à',
    ]);
    $text = preg_replace("/[ \t]+/u", ' ', $text);
    $text = preg_replace("/\n{3,}/u", "\n\n", $text);

    return trim((string) $text);
}

function bien_garble_score(?string $value): int
{
    $text = (string) $value;
    if ($text === '') {
        return 0;
    }

    $needles = [
        'Ãƒ',
        'Ã¢',
        'Ã‚',
        'Ã…',
        'Â',
        'â€',
        'â‚',
        'Æ’',
        'Å“',
        'Å’',
        'ï¿½',
        '�',
        '????',
    ];

    $score = 0;

    foreach ($needles as $needle) {
        $score += substr_count($text, $needle);
    }

    return $score;
}

function bien_is_garbled(?string $value, int $threshold = 3): bool
{
    return bien_garble_score($value) >= $threshold;
}

function bien_slugify(string $text): string
{
    $text = bien_fix_text($text);
    $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text) ?: $text;
    $text = preg_replace('/[^a-zA-Z0-9]+/', '-', $text);
    $text = strtolower(trim((string) $text, '-'));

    return $text !== '' ? $text : 'bien';
}

function bien_extract_id_from_slug(string $slug): int
{
    if (preg_match('/-(\d+)\s*$/', $slug, $matches)) {
        return (int) $matches[1];
    }

    return 0;
}

function bien_transaction_key(?string $value): string
{
    $normalized = strtolower(trim(bien_fix_text((string) $value)));

    if ($normalized === '') {
        return '';
    }

    if (in_array($normalized, ['vente', 'a vendre', 'a-vendre', 'à vendre', 'avendre'], true)) {
        return 'vente';
    }

    if (in_array($normalized, ['location', 'a louer', 'a-louer', 'à louer', 'alouer'], true)) {
        return 'location';
    }

    return $normalized;
}

function bien_transaction_variants(?string $value): array
{
    $key = bien_transaction_key($value);

    if ($key === 'vente') {
        return ['vente', 'a vendre', 'a-vendre', 'à vendre', 'avendre'];
    }

    if ($key === 'location') {
        return ['location', 'a louer', 'a-louer', 'à louer', 'alouer'];
    }

    return $key !== '' ? [$key] : [];
}

function bien_transaction_label(?string $value): string
{
    $key = bien_transaction_key($value);

    if ($key === 'vente') {
        return 'À vendre';
    }

    if ($key === 'location') {
        return 'À louer';
    }

    $text = bien_fix_text((string) $value);
    return $text !== '' ? ucfirst($text) : 'Transaction';
}

function bien_type_label(?string $value): string
{
    $normalized = strtolower(trim(bien_fix_text((string) $value)));

    $labels = [
        'appartement' => 'Appartement',
        'maison' => 'Maison',
        'villa' => 'Villa',
        'duplex' => 'Duplex',
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

function bien_money($price): string
{
    $amount = (float) $price;
    if ($amount <= 0) {
        return 'Prix sur demande';
    }

    return number_format($amount, 0, ',', ' ') . ' FCFA';
}

function bien_lower(string $text): string
{
    if (function_exists('mb_strtolower')) {
        return mb_strtolower($text, 'UTF-8');
    }

    return strtolower($text);
}

function bien_measure_label(?string $value): string
{
    $text = bien_fix_text($value);
    if ($text === '') {
        return '';
    }

    if (preg_match('/m(?:²|2)\b/ui', $text)) {
        return $text;
    }

    return preg_match('/\d/u', $text) ? $text . ' m²' : $text;
}

function bien_absolute_url(string $path): string
{
    if (strpos($path, 'http://') === 0 || strpos($path, 'https://') === 0) {
        return $path;
    }

    return rtrim((string) BASE_URL, '/') . '/' . ltrim($path, '/');
}

function bien_image_path(?string $path): string
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

function bien_property_url(array $bien): string
{
    $slug = trim((string) ($bien['slug'] ?? ''));
    if ($slug === '') {
        $slug = bien_slugify(
            trim(
                bien_type_label($bien['type'] ?? '') . ' ' .
                bien_fix_text((string) ($bien['titre'] ?? '')) . ' ' .
                bien_fix_text((string) ($bien['ville'] ?? ''))
            )
        ) . '-' . (int) ($bien['id'] ?? 0);
    }

    return '/bien/' . rawurlencode($slug);
}

function bien_location_label(array $bien): string
{
    $parts = [];

    $ville = bien_fix_text((string) ($bien['ville'] ?? ''));
    $quartier = bien_fix_text((string) ($bien['quartier'] ?? ''));

    if ($ville !== '') {
        $parts[] = $ville;
    }

    if ($quartier !== '') {
        $parts[] = $quartier;
    }

    return $parts ? implode(' - ', $parts) : 'Localisation à confirmer';
}

function bien_full_description(array $bien): string
{
    $full = (string) ($bien['description_detaillee'] ?? '');
    if (trim($full) !== '') {
        return $full;
    }

    return (string) ($bien['description'] ?? '');
}

function bien_fallback_description_html(array $bien): string
{
    $title = bien_fix_text((string) ($bien['titre'] ?? 'Bien immobilier'));
    $transactionSource = bien_fix_text((string) ($bien['transaction'] ?? ''));
    $transactionLabel = $transactionSource !== '' ? bien_transaction_label($transactionSource) : 'Disponible';
    $typeSource = bien_fix_text((string) ($bien['type'] ?? ''));
    $typeLabel = $typeSource !== '' ? bien_type_label($typeSource) : 'bien immobilier';
    $locationLabel = bien_location_label($bien);
    $priceLabel = bien_money($bien['prix'] ?? 0);
    $surfaceTerrain = bien_measure_label((string) ($bien['superficie_terrain'] ?? ''));
    $surfaceHabitable = bien_measure_label((string) ($bien['superficie_habitable'] ?? ''));
    $surfaceBuilt = bien_fix_text((string) ($bien['superficie'] ?? ''));
    $availability = bien_fix_text((string) ($bien['disponibilite'] ?? ''));
    $zone = bien_fix_text((string) ($bien['quartier_type'] ?? ''));

    $paragraphs = [];
    $paragraphs[] = '<p><strong>' . bien_h($transactionLabel . ' - ' . $title) . '</strong></p>';

    $intro = 'IBIG IMMO TRUST vous propose ce ' . bien_lower($typeLabel);
    if ($locationLabel !== '') {
        $intro .= ' situé à ' . $locationLabel;
    }
    if ($surfaceTerrain !== '') {
        $intro .= ', sur un terrain de ' . $surfaceTerrain;
    } elseif ($surfaceHabitable !== '') {
        $intro .= ', avec une surface habitable de ' . $surfaceHabitable;
    } elseif ($surfaceBuilt !== '') {
        $intro .= ', avec une surface annoncée de ' . $surfaceBuilt;
    }
    $intro .= '.';
    $paragraphs[] = '<p>' . bien_h($intro) . '</p>';

    $highlights = [];
    if (!empty($bien['chambres'])) {
        $highlights[] = (int) $bien['chambres'] . ' chambre' . ((int) $bien['chambres'] > 1 ? 's' : '');
    }
    if (!empty($bien['salles_bain'])) {
        $highlights[] = (int) $bien['salles_bain'] . ' salle' . ((int) $bien['salles_bain'] > 1 ? 's' : '') . ' de bain';
    }
    if (!empty($bien['salons'])) {
        $highlights[] = (int) $bien['salons'] . ' salon' . ((int) $bien['salons'] > 1 ? 's' : '');
    }
    if (!empty($bien['cuisines'])) {
        $highlights[] = (int) $bien['cuisines'] . ' cuisine' . ((int) $bien['cuisines'] > 1 ? 's' : '');
    }
    if (!empty($bien['dependance'])) {
        $highlights[] = 'Dépendance';
    }
    if (!empty($bien['jardin'])) {
        $highlights[] = 'Jardin';
    }
    if (!empty($bien['parking'])) {
        $highlights[] = 'Parking';
    }
    if (!empty($bien['garage'])) {
        $highlights[] = 'Garage';
    }
    if (!empty($bien['balcon'])) {
        $highlights[] = 'Balcon ou terrasse';
    }
    if (!empty($bien['cour'])) {
        $highlights[] = 'Cour';
    }
    if (!empty($bien['piscine'])) {
        $highlights[] = 'Piscine';
    }

    if (!empty($highlights)) {
        $items = array_map(static fn(string $item): string => '<li>' . bien_h($item) . '</li>', $highlights);
        $paragraphs[] = '<p><strong>Points clés :</strong></p><ul>' . implode('', $items) . '</ul>';
    }

    $details = [];
    if ($zone !== '') {
        $details[] = 'Zone : ' . $zone;
    }
    if ($availability !== '') {
        $details[] = 'Disponibilité : ' . $availability;
    }
    if ($priceLabel !== 'Prix sur demande') {
        $details[] = 'Prix : ' . $priceLabel;
    }

    if (!empty($details)) {
        $paragraphs[] = '<p>' . bien_h(implode(' | ', $details)) . '</p>';
    }

    $paragraphs[] = '<p>Visite sur rendez-vous.</p>';

    return implode("\n", $paragraphs);
}

function bien_fallback_description_text(array $bien): string
{
    $title = bien_fix_text((string) ($bien['titre'] ?? 'Bien immobilier'));
    $transactionSource = bien_fix_text((string) ($bien['transaction'] ?? ''));
    $transactionLabel = $transactionSource !== '' ? bien_transaction_label($transactionSource) : 'Disponible';
    $typeSource = bien_fix_text((string) ($bien['type'] ?? ''));
    $typeLabel = $typeSource !== '' ? bien_type_label($typeSource) : 'bien immobilier';
    $locationLabel = bien_location_label($bien);
    $priceLabel = bien_money($bien['prix'] ?? 0);

    $parts = [];
    $parts[] = $transactionLabel . ' - ' . $title . '.';

    $sentence = 'IBIG IMMO TRUST vous propose ce ' . bien_lower($typeLabel);
    if ($locationLabel !== '') {
        $sentence .= ' situé à ' . $locationLabel;
    }
    $sentence .= '.';
    $parts[] = $sentence;

    if (!empty($bien['chambres']) || !empty($bien['salles_bain'])) {
        $detailParts = [];
        if (!empty($bien['chambres'])) {
            $detailParts[] = (int) $bien['chambres'] . ' chambre' . ((int) $bien['chambres'] > 1 ? 's' : '');
        }
        if (!empty($bien['salles_bain'])) {
            $detailParts[] = (int) $bien['salles_bain'] . ' salle' . ((int) $bien['salles_bain'] > 1 ? 's' : '') . ' de bain';
        }
        $parts[] = implode(', ', $detailParts) . '.';
    }

    if ($priceLabel !== 'Prix sur demande') {
        $parts[] = 'Prix : ' . $priceLabel . '.';
    }

    $parts[] = 'Visite sur rendez-vous.';

    return implode(' ', $parts);
}

function bien_description_plain(array $bien, int $limit = 170): string
{
    $text = bien_fix_text(strip_tags(bien_full_description($bien)));

    if (bien_is_garbled($text)) {
        $text = bien_fallback_description_text($bien);
    }

    if ($text === '') {
        return 'Découvrez ce bien immobilier proposé par IBIG IMMO TRUST avec un accompagnement rapide, structuré et sécurisé.';
    }

    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $limit - 1)) . '...';
    }

    if (strlen($text) <= $limit) {
        return $text;
    }

    return rtrim(substr($text, 0, $limit - 1)) . '...';
}

function bien_render_description(array $bien): string
{
    $raw = trim(bien_full_description($bien));
    if ($raw === '') {
        return bien_fallback_description_html($bien);
    }

    $raw = bien_fix_text($raw);

    if (bien_is_garbled($raw)) {
        return bien_fallback_description_html($bien);
    }

    if (preg_match('/<\s*(p|br|ul|ol|li|strong|em|b|i|h[2-6]|blockquote|div|span)\b/i', $raw)) {
        $allowed = '<p><br><ul><ol><li><strong><em><b><i><h2><h3><h4><h5><h6><blockquote>';
        $safe = strip_tags($raw, $allowed);
        $safe = preg_replace('/<p>\s*<\/p>/', '', (string) $safe);

        if (bien_is_garbled(strip_tags($safe))) {
            return bien_fallback_description_html($bien);
        }

        return $safe !== '' ? $safe : '<p>' . bien_h($raw) . '</p>';
    }

    $raw = str_replace(["\r\n", "\r"], "\n", $raw);
    $lines = preg_split("/\n+/u", $raw) ?: [];
    $blocks = [];
    $paragraphLines = [];
    $listItems = [];

    $flushParagraph = static function () use (&$paragraphLines, &$blocks): void {
        if (empty($paragraphLines)) {
            return;
        }

        $content = implode('<br>', array_map('bien_h', $paragraphLines));
        $blocks[] = '<p>' . $content . '</p>';
        $paragraphLines = [];
    };

    $flushList = static function () use (&$listItems, &$blocks): void {
        if (empty($listItems)) {
            return;
        }

        $blocks[] = '<ul><li>' . implode('</li><li>', array_map('bien_h', $listItems)) . '</li></ul>';
        $listItems = [];
    };

    foreach ($lines as $line) {
        $line = trim((string) $line);
        if ($line === '') {
            $flushParagraph();
            $flushList();
            continue;
        }

        if (preg_match('/^(?:caracteristiques|caractéristiques|équipements|equipements|points forts|informations clés|informations cles)\b.*:?$/iu', $line)) {
            $flushParagraph();
            $flushList();
            $blocks[] = '<p><strong>' . bien_h($line) . '</strong></p>';
            continue;
        }

        $bulletLine = preg_replace('/^(?:[-*•·▪◦✓✔➜»]+|\d+[.)])\s*/u', '', $line, -1, $count);
        if ($count > 0) {
            $flushParagraph();
            $listItems[] = trim((string) $bulletLine);
            continue;
        }

        if (!empty($listItems)) {
            $flushList();
        }

        $paragraphLines[] = $line;
    }

    $flushParagraph();
    $flushList();

    $html = $blocks ? implode("\n", $blocks) : '<p>' . nl2br(bien_h($raw)) . '</p>';

    if (bien_is_garbled(strip_tags($html))) {
        return bien_fallback_description_html($bien);
    }

    return $html;
}

function bien_video_embed_url(?string $value): ?string
{
    $url = trim((string) $value);
    if ($url === '') {
        return null;
    }

    if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/)([A-Za-z0-9_-]{6,})~', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }

    if (preg_match('~youtube\.com/embed/([A-Za-z0-9_-]{6,})~', $url, $matches)) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }

    if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $matches)) {
        return 'https://player.vimeo.com/video/' . $matches[1];
    }

    return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
}

function bien_meta_tokens(array $bien): array
{
    $tokens = [];

    if (!empty($bien['superficie'])) {
        $tokens[] = bien_fix_text((string) $bien['superficie']);
    } elseif (!empty($bien['superficie_habitable'])) {
        $tokens[] = bien_measure_label((string) $bien['superficie_habitable']);
    }

    if (!empty($bien['chambres'])) {
        $tokens[] = (int) $bien['chambres'] . ' chambre' . ((int) $bien['chambres'] > 1 ? 's' : '');
    }

    if (!empty($bien['salles_bain'])) {
        $tokens[] = (int) $bien['salles_bain'] . ' salle' . ((int) $bien['salles_bain'] > 1 ? 's' : '') . ' de bain';
    }

    if (!empty($bien['jardin'])) {
        $tokens[] = 'Jardin';
    }

    if (!empty($bien['piscine'])) {
        $tokens[] = 'Piscine';
    }

    return $tokens;
}

$slug = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';
$id = isset($_GET['id']) ? max(0, (int) $_GET['id']) : 0;
$slugId = $slug !== '' ? bien_extract_id_from_slug($slug) : 0;

$bien = null;

try {
    if ($slug !== '') {
        $statement = $pdo->prepare(
            "SELECT *
             FROM immo_biens
             WHERE slug = :slug
               AND visible = 1
               AND statut_publication = 'valide'
             LIMIT 1"
        );
        $statement->execute([':slug' => $slug]);
        $bien = $statement->fetch(PDO::FETCH_ASSOC) ?: null;

        if ($bien === null && $slugId > 0) {
            $statement = $pdo->prepare(
                "SELECT *
                 FROM immo_biens
                 WHERE id = :id
                   AND visible = 1
                   AND statut_publication = 'valide'
                 LIMIT 1"
            );
            $statement->execute([':id' => $slugId]);
            $bien = $statement->fetch(PDO::FETCH_ASSOC) ?: null;
        }
    } elseif ($id > 0) {
        $statement = $pdo->prepare(
            "SELECT *
             FROM immo_biens
             WHERE id = :id
               AND visible = 1
               AND statut_publication = 'valide'
             LIMIT 1"
        );
        $statement->execute([':id' => $id]);
        $bien = $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }
} catch (Throwable $exception) {
    error_log('BIEN fetch error: ' . $exception->getMessage());
}

if ($bien === null) {
    http_response_code(404);

    $pageTitle = 'Bien introuvable | IBIG IMMO TRUST';
    $metaDescription = 'Ce bien n\'est plus disponible. Consultez l\'ensemble de nos biens immobiliers avec IBIG IMMO TRUST.';
    $metaRobots = 'noindex,nofollow';
    $canonicalUrl = rtrim((string) BASE_URL, '/') . '/tous_les_biens.php';

    include __DIR__ . '/includes/header.php';
    ?>
    <style>
    .bien-empty {
        max-width: 780px;
        margin: 120px auto 90px;
        padding: 0 20px;
    }
    .bien-empty__card {
        border: 1px solid #e5e7eb;
        border-radius: 24px;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        padding: 36px 28px;
        text-align: center;
    }
    .bien-empty__title {
        margin: 0 0 10px;
        font-size: 30px;
        color: #0f2d6b;
    }
    .bien-empty__text {
        margin: 0 0 18px;
        color: #5f6b7a;
        line-height: 1.7;
    }
    .bien-empty__action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 48px;
        padding: 0 22px;
        border-radius: 999px;
        background: #0f4ab8;
        color: #ffffff;
        text-decoration: none;
        font-weight: 700;
    }
    </style>

    <section class="bien-empty">
      <div class="bien-empty__card">
        <h1 class="bien-empty__title">Bien introuvable</h1>
        <p class="bien-empty__text">Ce bien n'est plus disponible ou l'adresse demandée est incomplète. Vous pouvez continuer votre recherche dans notre catalogue complet.</p>
        <a class="bien-empty__action" href="/tous_les_biens.php">Voir tous les biens</a>
      </div>
    </section>
    <?php
    include __DIR__ . '/includes/footer.php';
    exit;
}

$title = bien_fix_text((string) ($bien['titre'] ?? 'Bien immobilier'));
$typeSource = bien_fix_text((string) ($bien['type'] ?? ''));
$typeLabel = $typeSource !== '' ? bien_type_label($typeSource) : 'Bien immobilier';
$displayTypeLabel = $typeSource !== '' ? $typeLabel : '';
$ville = bien_fix_text((string) ($bien['ville'] ?? ''));
$quartier = bien_fix_text((string) ($bien['quartier'] ?? ''));
$locationLabel = bien_location_label($bien);
$transactionSource = bien_fix_text((string) ($bien['transaction'] ?? ''));
$displayTransactionLabel = $transactionSource !== '' ? bien_transaction_label($transactionSource) : '';
$transactionLabel = $displayTransactionLabel !== '' ? $displayTransactionLabel : 'Disponible';
$heroBadgeLabel = $displayTransactionLabel !== '' ? $displayTransactionLabel : 'Disponible';
$heroKicker = implode(' • ', array_filter([$displayTypeLabel, $displayTransactionLabel]));
$heroKicker = $heroKicker !== '' ? $heroKicker : 'Bien immobilier';
$priceLabel = bien_money($bien['prix'] ?? 0);
$descriptionPlain = bien_description_plain($bien);
$officialSlug = trim((string) ($bien['slug'] ?? ''));

if ($officialSlug === '') {
    $officialSlug = bien_slugify(trim($typeLabel . ' ' . $title . ' ' . $ville)) . '-' . (int) $bien['id'];
}

$canonicalUrl = rtrim((string) BASE_URL, '/') . '/bien/' . rawurlencode($officialSlug);

if ($id > 0) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $canonicalUrl);
    exit;
}

if ($slug !== '' && $slug !== $officialSlug) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $canonicalUrl);
    exit;
}

$gallery = [];
$seenGallery = [];

$mainImagePath = bien_image_path((string) ($bien['image_principale'] ?? ''));
$gallery[] = [
    'src' => $mainImagePath,
    'absolute' => bien_absolute_url($mainImagePath),
    'alt' => trim($title . ' - ' . $locationLabel),
];
$seenGallery[$mainImagePath] = true;

try {
    $photosStatement = $pdo->prepare(
        "SELECT id, fichier
         FROM immo_bien_photos
         WHERE bien_id = :bien_id
           AND fichier IS NOT NULL
           AND fichier <> ''
         ORDER BY sort_order ASC, id ASC"
    );
    $photosStatement->execute([':bien_id' => (int) $bien['id']]);

    foreach ($photosStatement->fetchAll(PDO::FETCH_ASSOC) as $photo) {
        $path = bien_image_path((string) ($photo['fichier'] ?? ''));
        if (isset($seenGallery[$path])) {
            continue;
        }

        $gallery[] = [
            'src' => $path,
            'absolute' => bien_absolute_url($path),
            'alt' => trim($title . ' - ' . $locationLabel),
        ];
        $seenGallery[$path] = true;
    }
} catch (Throwable $exception) {
    error_log('BIEN photos error: ' . $exception->getMessage());
}

$heroImage = $gallery[0]['src'];
$shareImage = $gallery[0]['absolute'];
$ogImage = $shareImage;
$surfaceTerrain = bien_fix_text((string) ($bien['superficie_terrain'] ?? ''));
$surfaceHabitable = bien_fix_text((string) ($bien['superficie_habitable'] ?? ''));
$surfaceBuilt = bien_fix_text((string) ($bien['superficie'] ?? ''));
$etatGeneral = bien_fix_text((string) ($bien['etat_general'] ?? ''));
$availability = bien_fix_text((string) ($bien['disponibilite'] ?? ''));
$badge = bien_fix_text((string) ($bien['badge'] ?? ''));
$zone = bien_fix_text((string) ($bien['quartier_type'] ?? ''));
$quickFacts = bien_meta_tokens($bien);
$seoLead = trim(
    $typeLabel
    . ($displayTransactionLabel !== '' ? ' ' . bien_lower($displayTransactionLabel) : '')
    . ($ville !== '' ? ' à ' . $ville : '')
    . ($quartier !== '' ? ' - ' . $quartier : '')
);

$pageTitle = $title
    . ($displayTransactionLabel !== '' ? ' | ' . $displayTransactionLabel : '')
    . ($ville !== '' ? ' à ' . $ville : '')
    . ' | IBIG IMMO TRUST';
$metaDescription = trim(
    preg_replace(
        '/\s+/u',
        ' ',
        $seoLead . ' - ' . $priceLabel . '. ' . $descriptionPlain
    )
);

if (function_exists('mb_strlen') && function_exists('mb_substr') && mb_strlen($metaDescription) > 165) {
    $metaDescription = rtrim(mb_substr($metaDescription, 0, 164)) . '...';
}

$schemaListing = [
    '@context' => 'https://schema.org',
    '@type' => 'RealEstateListing',
    'name' => $title,
    'url' => $canonicalUrl,
    'description' => $metaDescription,
    'image' => array_map(static fn(array $item): string => $item['absolute'], $gallery),
    'datePosted' => !empty($bien['created_at']) ? date(DATE_ATOM, strtotime((string) $bien['created_at'])) : null,
    'dateModified' => !empty($bien['updated_at']) ? date(DATE_ATOM, strtotime((string) $bien['updated_at'])) : null,
    'offers' => [
        '@type' => 'Offer',
        'priceCurrency' => 'XOF',
        'price' => !empty($bien['prix']) ? (int) $bien['prix'] : null,
        'availability' => 'https://schema.org/InStock',
        'url' => $canonicalUrl,
    ],
    'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => $ville !== '' ? $ville : 'Abidjan',
        'addressRegion' => 'Abidjan',
        'addressCountry' => 'CI',
    ],
];

if (!empty($bien['chambres'])) {
    $schemaListing['numberOfRooms'] = (int) $bien['chambres'];
}

if ($surfaceHabitable !== '') {
    $schemaListing['floorSize'] = [
        '@type' => 'QuantitativeValue',
        'value' => preg_replace('/[^\d.,]/', '', $surfaceHabitable),
        'unitCode' => 'MTK',
    ];
}

$schemaListing['offers'] = array_filter($schemaListing['offers'], static fn($value): bool => $value !== null && $value !== '');
$schemaListing = array_filter($schemaListing, static fn($value): bool => $value !== null && $value !== '');

$schemaBreadcrumb = [
    '@context' => 'https://schema.org',
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Accueil',
            'item' => rtrim((string) BASE_URL, '/') . '/',
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Tous les biens',
            'item' => rtrim((string) BASE_URL, '/') . '/tous_les_biens.php',
        ],
        [
            '@type' => 'ListItem',
            'position' => 3,
            'name' => $title,
            'item' => $canonicalUrl,
        ],
    ],
];

$metaOG = implode("\n", [
    '<meta property="og:image:width" content="1200">',
    '<meta property="og:image:height" content="630">',
    '<meta name="twitter:image" content="' . bien_h($shareImage) . '">',
    '<script type="application/ld+json">' . json_encode($schemaListing, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>',
    '<script type="application/ld+json">' . json_encode($schemaBreadcrumb, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>',
]);

$priceEuro = !empty($bien['prix_euro']) ? number_format((float) $bien['prix_euro'], 0, ',', ' ') . ' EUR' : '';
$priceUsd = !empty($bien['prix_usd']) ? number_format((float) $bien['prix_usd'], 0, ',', ' ') . ' USD' : '';

$facts = [
    'Type de bien' => $typeLabel,
    'Transaction' => $displayTransactionLabel,
    'Ville' => $ville,
    'Quartier' => $quartier,
    'Zone' => $zone,
    'Surface bâtie' => $surfaceBuilt,
    'Terrain' => bien_measure_label($surfaceTerrain),
    'Surface habitable' => bien_measure_label($surfaceHabitable),
    'Chambres' => !empty($bien['chambres']) ? (string) (int) $bien['chambres'] : '',
    'Salles de bain' => !empty($bien['salles_bain']) ? (string) (int) $bien['salles_bain'] : '',
    'Salons' => !empty($bien['salons']) ? (string) (int) $bien['salons'] : '',
    'Cuisines' => !empty($bien['cuisines']) ? (string) (int) $bien['cuisines'] : '',
    'Niveaux' => !empty($bien['niveaux']) ? (string) (int) $bien['niveaux'] : '',
    'Année de construction' => bien_fix_text((string) ($bien['annee_construction'] ?? '')),
    'État général' => $etatGeneral,
    'Disponibilité' => $availability,
    'Référence' => (string) (int) $bien['id'],
];

$equipments = [
    'Climatisation' => $bien['climatisation'] ?? 0,
    'Chauffe-eau' => $bien['chauffe_eau'] ?? 0,
    'Placards' => $bien['placards'] ?? 0,
    'Dressing' => $bien['dressing'] ?? 0,
    'Cuisine équipée' => $bien['cuisine_equipee'] ?? 0,
    'Fibre internet' => $bien['fibre'] ?? 0,
    'Sécurité' => $bien['securite'] ?? 0,
    'Buanderie' => $bien['buanderie'] ?? 0,
    'Balcon ou terrasse' => $bien['balcon'] ?? 0,
    'Piscine' => $bien['piscine'] ?? 0,
    'Jardin' => $bien['jardin'] ?? 0,
    'Parking' => $bien['parking'] ?? 0,
    'Garage' => $bien['garage'] ?? 0,
    'Dépendance' => $bien['dependance'] ?? 0,
    'Cour' => $bien['cour'] ?? 0,
    'Forage' => $bien['forage'] ?? 0,
    'Groupe électrogène' => $bien['groupe_electrogene'] ?? 0,
    'Clôture sécurisée' => $bien['cloture_securisee'] ?? 0,
];

$enabledEquipments = [];
foreach ($equipments as $label => $enabled) {
    if ((int) $enabled === 1) {
        $enabledEquipments[] = $label;
    }
}

$waMessage = rawurlencode(
    'Bonjour IBIG IMMO TRUST, je suis intéressé par ce bien : ' .
    $title .
    ' (Réf. ' . (int) $bien['id'] . ') - ' .
    $canonicalUrl
);

$shareText = trim($title . ' | ' . $priceLabel . ' | ' . $locationLabel . ' | ' . $canonicalUrl);
$videoFile = bien_image_path((string) ($bien['video_fichier'] ?? ''));
$hasLocalVideo = !empty($bien['video_fichier']);
$videoEmbedUrl = bien_video_embed_url($bien['url_video'] ?? null);

$related = [];
try {
    $transactionVariants = bien_transaction_variants($bien['transaction'] ?? null);
    $transactionPlaceholders = $transactionVariants ? implode(',', array_fill(0, count($transactionVariants), '?')) : "''";

    $relatedSql = "
        SELECT id, titre, slug, image_principale, prix, ville, quartier, type, transaction, niveau_visibilite, created_at
        FROM immo_biens
        WHERE visible = 1
          AND statut_publication = 'valide'
          AND id <> ?
        ORDER BY
          CASE WHEN ? <> '' AND LOWER(TRIM(COALESCE(ville, ''))) = LOWER(TRIM(?)) THEN 0 ELSE 1 END,
          CASE WHEN ? <> '' AND LOWER(TRIM(COALESCE(type, ''))) = LOWER(TRIM(?)) THEN 0 ELSE 1 END,
          CASE WHEN LOWER(TRIM(COALESCE(transaction, ''))) IN ($transactionPlaceholders) THEN 0 ELSE 1 END,
          CASE LOWER(TRIM(COALESCE(niveau_visibilite, 'standard')))
            WHEN 'exclusif' THEN 0
            WHEN 'vedette' THEN 1
            ELSE 2
          END,
          COALESCE(en_vedette, 0) DESC,
          created_at DESC,
          id DESC
        LIMIT 6
    ";

    $relatedParams = [
        (int) $bien['id'],
        $ville,
        $ville,
        $typeLabel,
        $typeLabel,
    ];

    foreach ($transactionVariants as $variant) {
        $relatedParams[] = strtolower($variant);
    }

    $relatedStatement = $pdo->prepare($relatedSql);
    $relatedStatement->execute($relatedParams);
    $related = $relatedStatement->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $exception) {
    error_log('BIEN related error: ' . $exception->getMessage());
}

include __DIR__ . '/includes/header.php';
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">

<style>
:root {
    --bien-blue:#1B4FD8;
    --bien-blue-dark: #0d2c6d;
    --bien-blue-soft: #eef4ff;
    --bien-red:#E8282A;
    --bien-green: #16a34a;
    --bien-gold: #d3a42f;
    --bien-text: #1f2937;
    --bien-muted: #667085;
    --bien-line: #e4e7ec;
    --bien-bg: #f5f8fc;
    --bien-white: #ffffff;
    --bien-shadow: 0 18px 46px rgba(15, 23, 42, 0.08);
    --bien-shadow-soft: 0 10px 28px rgba(15, 23, 42, 0.06);
    --bien-radius: 24px;
    --bien-radius-md: 18px;
}

.bien-detail {
    max-width: 1240px;
    margin: 0 auto;
    padding: 28px 16px 72px;
    color: var(--bien-text);
}

.bien-breadcrumb {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin: 0 auto 20px;
    font-size: 13px;
    color: var(--bien-muted);
}

.bien-breadcrumb a {
    color: var(--bien-blue);
    text-decoration: none;
    font-weight: 600;
}

.bien-breadcrumb span {
    color: var(--bien-muted);
}

.bien-hero {
    display: grid;
    grid-template-columns: minmax(0, 1.18fr) minmax(320px, 0.82fr);
    gap: 24px;
    align-items: start;
}

.bien-media-card,
.bien-side-card,
.bien-section,
.bien-related,
.bien-contact-card {
    background: var(--bien-white);
    border: 1px solid var(--bien-line);
    border-radius: var(--bien-radius);
    box-shadow: var(--bien-shadow-soft);
}

.bien-media-card {
    overflow: hidden;
}

.bien-media-main {
    position: relative;
    display: block;
    aspect-ratio: 16 / 10;
    background: #d9dee8;
}

.bien-media-main img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.bien-badges {
    position: absolute;
    left: 18px;
    top: 18px;
    right: 18px;
    display: flex;
    justify-content: space-between;
    gap: 10px;
    pointer-events: none;
}

.bien-badge {
    display: inline-flex;
    align-items: center;
    min-height: 34px;
    padding: 0 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.01em;
    color: #fff;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.18);
}

.bien-badge--gold {
    background: linear-gradient(135deg, #f0c14d, #c69218);
    color: #2c2100;
}

.bien-badge--blue {
    background: rgba(8, 30, 76, 0.82);
    backdrop-filter: blur(8px);
}

.bien-thumb-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
    gap: 10px;
    padding: 14px;
    background: #fbfcfe;
}

.bien-thumb {
    display: block;
    overflow: hidden;
    aspect-ratio: 1;
    border-radius: 14px;
    border: 1px solid #e8eef7;
    background: #edf2f9;
}

.bien-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.2s ease;
}

.bien-thumb:hover img {
    transform: scale(1.04);
}

.bien-side-card {
    padding: 24px;
    position: sticky;
    top: 118px;
}

.bien-kicker {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    min-height: 32px;
    padding: 0 12px;
    border-radius: 999px;
    background: var(--bien-blue-soft);
    color: var(--bien-blue);
    font-size: 12px;
    font-weight: 700;
}

.bien-title {
    margin: 14px 0 12px;
    font-size: clamp(28px, 4vw, 42px);
    line-height: 1.08;
    letter-spacing: -0.02em;
    color: var(--bien-blue-dark);
    font-weight: 750;
}

.bien-location {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 14px;
    color: var(--bien-muted);
    font-size: 15px;
    line-height: 1.5;
}

.bien-chip-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 0 0 18px;
}

.bien-chip {
    display: inline-flex;
    align-items: center;
    min-height: 34px;
    padding: 0 12px;
    border-radius: 999px;
    background: #f8fafc;
    border: 1px solid #e7edf4;
    color: #344054;
    font-size: 13px;
    font-weight: 600;
}

.bien-price {
    margin: 0;
    font-size: 34px;
    line-height: 1.05;
    color: var(--bien-red);
    font-weight: 800;
}

.bien-price-alt {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
    color: var(--bien-muted);
    font-size: 14px;
}

.bien-facts-inline {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px;
    margin: 22px 0;
}

.bien-fact-inline {
    border: 1px solid #e7edf4;
    border-radius: 18px;
    background: #fbfdff;
    padding: 14px 16px;
}

.bien-fact-inline strong {
    display: block;
    margin-bottom: 4px;
    color: var(--bien-blue-dark);
    font-size: 18px;
    font-weight: 750;
}

.bien-fact-inline span {
    color: var(--bien-muted);
    font-size: 13px;
}

.bien-actions {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 10px;
    margin-top: 22px;
}

.bien-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 9px;
    min-height: 48px;
    padding: 0 16px;
    border-radius: 14px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid transparent;
    transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
}

.bien-button:hover {
    transform: translateY(-1px);
    box-shadow: 0 10px 18px rgba(15, 23, 42, 0.08);
}

.bien-button--wa {
    background: #22c55e;
    color: #ffffff;
}

.bien-button--call {
    background: var(--bien-blue);
    color: #ffffff;
}

.bien-button--rdv {
    background: #ffffff;
    color: var(--bien-blue-dark);
    border-color: #d8e3f0;
}

.bien-share {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 10px;
    margin-top: 18px;
    padding-top: 18px;
    border-top: 1px dashed #d9e2ec;
}

.bien-share__label {
    color: #344054;
    font-size: 13px;
    font-weight: 700;
}

.bien-share button {
    min-height: 36px;
    padding: 0 12px;
    border-radius: 999px;
    border: 1px solid #d8e3f0;
    background: #ffffff;
    color: var(--bien-blue-dark);
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.18s ease, color 0.18s ease, border-color 0.18s ease;
}

.bien-share button:hover {
    background: var(--bien-blue);
    border-color: var(--bien-blue);
    color: #ffffff;
}

.bien-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 320px;
    gap: 24px;
    margin-top: 26px;
}

.bien-section {
    padding: 24px;
}

.bien-section + .bien-section {
    margin-top: 20px;
}

.bien-section__head {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 18px;
}

.bien-section__head h2,
.bien-related h2 {
    margin: 0;
    font-size: 24px;
    line-height: 1.15;
    color: var(--bien-blue-dark);
    font-weight: 750;
}

.bien-section__head p {
    margin: 0;
    color: var(--bien-muted);
    font-size: 14px;
}

.bien-description {
    color: #344054;
    font-size: 16px;
    line-height: 1.78;
}

.bien-description p:first-child {
    margin-top: 0;
}

.bien-description p:last-child {
    margin-bottom: 0;
}

.bien-description ul,
.bien-description ol {
    padding-left: 22px;
}

.bien-stats-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 12px 18px;
}

.bien-stat {
    display: grid;
    gap: 6px;
    padding: 14px 0;
    border-bottom: 1px solid #edf1f5;
}

.bien-stat__label {
    color: var(--bien-muted);
    font-size: 13px;
    font-weight: 600;
}

.bien-stat__value {
    color: #101828;
    font-size: 15px;
    font-weight: 650;
}

.bien-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.bien-tag {
    display: inline-flex;
    align-items: center;
    min-height: 38px;
    padding: 0 14px;
    border-radius: 999px;
    background: #f4fbf6;
    border: 1px solid #dcefe1;
    color: #166534;
    font-size: 13px;
    font-weight: 700;
}

.bien-contact-card {
    padding: 22px;
    position: sticky;
    top: 118px;
}

.bien-contact-card h3 {
    margin: 0 0 10px;
    color: var(--bien-blue-dark);
    font-size: 22px;
    font-weight: 750;
}

.bien-contact-card p {
    margin: 0 0 16px;
    color: var(--bien-muted);
    font-size: 14px;
    line-height: 1.7;
}

.bien-contact-points {
    display: grid;
    gap: 10px;
    margin-bottom: 16px;
}

.bien-contact-point {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #344054;
    font-size: 14px;
    font-weight: 600;
}

.bien-contact-note {
    margin-top: 14px;
    padding: 14px 16px;
    border-radius: 16px;
    background: #f8fafc;
    color: #475467;
    font-size: 13px;
    line-height: 1.6;
}

.bien-video-frame,
.bien-video-player {
    width: 100%;
    border: none;
    border-radius: 18px;
    background: #111827;
}

.bien-video-frame {
    aspect-ratio: 16 / 9;
}

.bien-video-player {
    display: block;
    max-height: 460px;
}

.bien-related {
    margin-top: 26px;
    padding: 24px;
}

.bien-related__head {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 18px;
}

.bien-related__head p {
    margin: 0;
    color: var(--bien-muted);
    font-size: 14px;
}

.bien-related-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 18px;
}

.bien-related-card {
    display: flex;
    flex-direction: column;
    min-width: 0;
    border: 1px solid #e7edf4;
    border-radius: 20px;
    overflow: hidden;
    background: #ffffff;
    text-decoration: none;
    color: inherit;
    box-shadow: 0 10px 24px rgba(15, 23, 42, 0.05);
    transition: transform 0.18s ease, box-shadow 0.18s ease;
}

.bien-related-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 16px 28px rgba(15, 23, 42, 0.09);
}

.bien-related-card img {
    width: 100%;
    aspect-ratio: 16 / 10;
    object-fit: cover;
}

.bien-related-card__body {
    padding: 16px;
}

.bien-related-card__eyebrow {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 8px;
    color: var(--bien-muted);
    font-size: 12px;
    font-weight: 700;
}

.bien-related-card__title {
    margin: 0 0 8px;
    color: var(--bien-blue-dark);
    font-size: 17px;
    line-height: 1.35;
    font-weight: 700;
}

.bien-related-card__meta {
    color: var(--bien-muted);
    font-size: 13px;
    line-height: 1.6;
}

.bien-related-card__price {
    margin-top: 12px;
    color: var(--bien-red);
    font-size: 18px;
    font-weight: 800;
}

.bien-toast {
    position: fixed;
    right: 18px;
    bottom: 18px;
    z-index: 10000;
    display: inline-flex;
    align-items: center;
    min-height: 44px;
    padding: 0 16px;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.92);
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    box-shadow: 0 14px 34px rgba(0, 0, 0, 0.24);
    opacity: 0;
    pointer-events: none;
    transform: translateY(10px);
    transition: opacity 0.18s ease, transform 0.18s ease;
}

.bien-toast.is-visible {
    opacity: 1;
    transform: translateY(0);
}

@media (max-width: 1100px) {
    .bien-hero,
    .bien-layout {
        grid-template-columns: 1fr;
    }

    .bien-side-card,
    .bien-contact-card {
        position: static;
        top: auto;
    }

    .bien-related-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 780px) {
    .bien-detail {
        padding-top: 20px;
    }

    .bien-side-card,
    .bien-section,
    .bien-related,
    .bien-contact-card {
        padding: 20px;
        border-radius: 20px;
    }

    .bien-thumb-grid {
        grid-template-columns: repeat(4, minmax(0, 1fr));
    }

    .bien-actions {
        grid-template-columns: 1fr;
    }

    .bien-facts-inline,
    .bien-stats-grid,
    .bien-related-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 560px) {
    .bien-breadcrumb {
        font-size: 12px;
        margin-bottom: 16px;
    }

    .bien-title {
        font-size: 28px;
    }

    .bien-price {
        font-size: 28px;
    }

    .bien-thumb-grid {
        grid-template-columns: repeat(3, minmax(0, 1fr));
        padding: 12px;
    }

    .bien-share {
        gap: 8px;
    }

    .bien-share button {
        flex: 1 1 calc(50% - 8px);
    }
}
</style>

<section class="bien-detail">
  <nav class="bien-breadcrumb" aria-label="Fil d'Ariane">
    <a href="/">Accueil</a>
    <span>/</span>
    <a href="/tous_les_biens.php">Tous les biens</a>
    <?php if ($ville !== ''): ?>
      <span>/</span>
      <a href="/tous_les_biens.php?ville=<?= bien_h(rawurlencode($ville)) ?>"><?= bien_h($ville) ?></a>
    <?php endif; ?>
    <span>/</span>
    <span><?= bien_h($title) ?></span>
  </nav>

  <div class="bien-hero">
    <div class="bien-media-card">
      <a class="bien-media-main glightbox" href="<?= bien_h($gallery[0]['absolute']) ?>" data-gallery="bien-gallery">
        <img src="<?= bien_h($heroImage) ?>" alt="<?= bien_h($gallery[0]['alt']) ?>" loading="eager" decoding="async">
        <div class="bien-badges">
          <div>
            <?php if ($badge !== ''): ?>
              <span class="bien-badge bien-badge--gold"><?= bien_h($badge) ?></span>
            <?php endif; ?>
          </div>
          <div>
            <span class="bien-badge bien-badge--blue"><?= bien_h($heroBadgeLabel) ?></span>
          </div>
        </div>
      </a>

      <?php if (count($gallery) > 1): ?>
        <div class="bien-thumb-grid">
          <?php foreach ($gallery as $index => $item): ?>
            <?php if ($index === 0): ?>
              <?php continue; ?>
            <?php endif; ?>
            <a class="bien-thumb glightbox" href="<?= bien_h($item['absolute']) ?>" data-gallery="bien-gallery">
              <img src="<?= bien_h($item['src']) ?>" alt="<?= bien_h($item['alt']) ?>" loading="lazy" decoding="async">
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <aside class="bien-side-card">
      <span class="bien-kicker"><?= bien_h($heroKicker) ?></span>
      <h1 class="bien-title"><?= bien_h($title) ?></h1>

      <p class="bien-location">
        <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
        <span><?= bien_h($locationLabel) ?></span>
      </p>

      <div class="bien-chip-row">
        <span class="bien-chip"><?= bien_h($typeLabel) ?></span>
        <?php if ($etatGeneral !== ''): ?>
          <span class="bien-chip"><?= bien_h($etatGeneral) ?></span>
        <?php endif; ?>
        <?php if ($availability !== ''): ?>
          <span class="bien-chip">Disponibilité : <?= bien_h($availability) ?></span>
        <?php endif; ?>
      </div>

      <p class="bien-price"><?= bien_h($priceLabel) ?></p>

      <?php if ($priceEuro !== '' || $priceUsd !== ''): ?>
        <div class="bien-price-alt">
          <?php if ($priceEuro !== ''): ?>
            <span>≈ <?= bien_h($priceEuro) ?></span>
          <?php endif; ?>
          <?php if ($priceUsd !== ''): ?>
            <span>≈ <?= bien_h($priceUsd) ?></span>
          <?php endif; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($quickFacts)): ?>
        <div class="bien-facts-inline">
          <?php foreach (array_slice($quickFacts, 0, 4) as $token): ?>
            <div class="bien-fact-inline">
              <strong><?= bien_h($token) ?></strong>
              <span>Information clé</span>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <div class="bien-actions">
        <a class="bien-button bien-button--wa" href="https://wa.me/2250584437474?text=<?= $waMessage ?>" target="_blank" rel="noopener">
          <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
          <span>WhatsApp</span>
        </a>
        <a class="bien-button bien-button--call" href="tel:+2250584437474">
          <i class="fa-solid fa-phone" aria-hidden="true"></i>
          <span>Appeler</span>
        </a>
        <a class="bien-button bien-button--rdv" href="/rdv.php?bien=<?= (int) $bien['id'] ?>">
          <i class="fa-solid fa-calendar-check" aria-hidden="true"></i>
          <span>Prendre rendez-vous</span>
        </a>
      </div>

      <div class="bien-share" data-url="<?= bien_h($canonicalUrl) ?>" data-title="<?= bien_h($title) ?>" data-text="<?= bien_h($shareText) ?>">
        <span class="bien-share__label">Partager ce bien :</span>
        <button type="button" data-share="whatsapp">WhatsApp</button>
        <button type="button" data-share="facebook">Facebook</button>
        <button type="button" data-share="twitter">X</button>
        <button type="button" data-share="linkedin">LinkedIn</button>
        <button type="button" data-share="copy">Copier le lien</button>
      </div>
    </aside>
  </div>

  <div class="bien-layout">
    <div>
      <section class="bien-section">
        <div class="bien-section__head">
          <div>
            <h2>Description détaillée</h2>
            <p>Une présentation claire du bien, de son positionnement et de ses points forts.</p>
          </div>
        </div>
        <div class="bien-description">
          <?= bien_render_description($bien) ?>
        </div>
      </section>

      <?php if ($hasLocalVideo || $videoEmbedUrl !== null): ?>
        <section class="bien-section">
          <div class="bien-section__head">
            <div>
              <h2>Vidéo du bien</h2>
              <p>Visualisez les volumes, la circulation et l'ambiance générale.</p>
            </div>
          </div>

          <?php if ($hasLocalVideo): ?>
            <video class="bien-video-player" controls preload="metadata">
              <source src="<?= bien_h($videoFile) ?>">
              Votre navigateur ne prend pas en charge la lecture vidéo.
            </video>
          <?php elseif ($videoEmbedUrl !== null): ?>
            <iframe class="bien-video-frame" src="<?= bien_h($videoEmbedUrl) ?>" loading="lazy" allowfullscreen title="Vidéo du bien"></iframe>
          <?php endif; ?>
        </section>
      <?php endif; ?>

      <?php if (!empty($gallery[1])): ?>
        <section class="bien-section">
          <div class="bien-section__head">
            <div>
              <h2>Galerie</h2>
              <p>Consultez les visuels complémentaires de ce bien.</p>
            </div>
          </div>
          <div class="bien-thumb-grid">
            <?php foreach ($gallery as $item): ?>
              <a class="bien-thumb glightbox" href="<?= bien_h($item['absolute']) ?>" data-gallery="bien-gallery">
                <img src="<?= bien_h($item['src']) ?>" alt="<?= bien_h($item['alt']) ?>" loading="lazy" decoding="async">
              </a>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>

      <?php if (!empty($related)): ?>
        <section class="bien-related">
          <div class="bien-related__head">
            <div>
              <h2>Biens similaires</h2>
              <p>Une sélection proche par zone, typologie ou transaction pour prolonger votre recherche.</p>
            </div>
          </div>

          <div class="bien-related-grid">
            <?php foreach ($related as $item): ?>
              <?php
              $relatedTitle = bien_fix_text((string) ($item['titre'] ?? 'Bien immobilier'));
              $relatedUrl = bien_property_url($item);
              $relatedImage = bien_image_path((string) ($item['image_principale'] ?? ''));
              $relatedLocation = bien_location_label($item);
              $relatedPrice = bien_money($item['prix'] ?? 0);
              $relatedEyebrow = [];

              $relatedTypeSource = bien_fix_text((string) ($item['type'] ?? ''));
              if ($relatedTypeSource !== '') {
                  $relatedEyebrow[] = bien_type_label($relatedTypeSource);
              }

              $relatedTransactionSource = bien_fix_text((string) ($item['transaction'] ?? ''));
              if ($relatedTransactionSource !== '') {
                  $relatedEyebrow[] = bien_transaction_label($relatedTransactionSource);
              }
              ?>
              <a class="bien-related-card" href="<?= bien_h($relatedUrl) ?>">
                <img src="<?= bien_h($relatedImage) ?>" alt="<?= bien_h($relatedTitle . ' - ' . $relatedLocation) ?>" loading="lazy" decoding="async">
                <div class="bien-related-card__body">
                  <?php if (!empty($relatedEyebrow)): ?>
                    <div class="bien-related-card__eyebrow">
                      <?php foreach ($relatedEyebrow as $eyebrowItem): ?>
                        <span><?= bien_h($eyebrowItem) ?></span>
                      <?php endforeach; ?>
                    </div>
                  <?php endif; ?>
                  <h3 class="bien-related-card__title"><?= bien_h($relatedTitle) ?></h3>
                  <div class="bien-related-card__meta"><?= bien_h($relatedLocation) ?></div>
                  <div class="bien-related-card__price"><?= bien_h($relatedPrice) ?></div>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </section>
      <?php endif; ?>
    </div>

    <div>
      <section class="bien-section">
        <div class="bien-section__head">
          <div>
            <h2>Caractéristiques</h2>
            <p>Les informations essentielles pour qualifier rapidement le bien.</p>
          </div>
        </div>

        <div class="bien-stats-grid">
          <?php foreach ($facts as $label => $value): ?>
            <?php if (trim((string) $value) === ''): ?>
              <?php continue; ?>
            <?php endif; ?>
            <div class="bien-stat">
              <span class="bien-stat__label"><?= bien_h($label) ?></span>
              <span class="bien-stat__value"><?= bien_h($value) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="bien-section">
        <div class="bien-section__head">
          <div>
            <h2>Confort et équipements</h2>
            <p>Un aperçu lisible des prestations disponibles sur place.</p>
          </div>
        </div>

        <?php if (!empty($enabledEquipments)): ?>
          <div class="bien-tags">
            <?php foreach ($enabledEquipments as $equipment): ?>
              <span class="bien-tag"><?= bien_h($equipment) ?></span>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <p class="bien-description">Aucun équipement complémentaire n'a encore été renseigné pour ce bien.</p>
        <?php endif; ?>
      </section>

      <aside class="bien-contact-card">
        <h3>Organiser une visite</h3>
        <p>Notre équipe vous aide à qualifier rapidement le bien, confirmer sa disponibilité et préparer la visite dans de bonnes conditions.</p>

        <div class="bien-contact-points">
          <div class="bien-contact-point">
            <i class="fa-solid fa-phone-volume" aria-hidden="true"></i>
            <span>Réponse rapide par téléphone ou WhatsApp</span>
          </div>
          <div class="bien-contact-point">
            <i class="fa-solid fa-file-lines" aria-hidden="true"></i>
            <span>Informations pratiques et accompagnement dossier</span>
          </div>
          <div class="bien-contact-point">
            <i class="fa-solid fa-shield-heart" aria-hidden="true"></i>
            <span>Suivi structuré avec IBIG IMMO TRUST</span>
          </div>
        </div>

        <a class="bien-button bien-button--wa" href="https://wa.me/2250584437474?text=<?= $waMessage ?>" target="_blank" rel="noopener">
          <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
          <span>Discuter maintenant</span>
        </a>

        <div class="bien-contact-note">
          Référence utile : <strong><?= bien_h((string) (int) $bien['id']) ?></strong>.
          Conservez-la si vous souhaitez demander une visite, un rappel ou un rendez-vous.
        </div>
      </aside>
    </div>
  </div>
</section>

<div class="bien-toast" id="bienToast" role="status" aria-live="polite"></div>

<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  if (window.GLightbox) {
    window.GLightbox({
      selector: '.glightbox',
      touchNavigation: true,
      loop: true
    });
  }
});

document.addEventListener('click', function (event) {
  const button = event.target.closest('[data-share]');
  if (!button) {
    return;
  }

  const box = button.closest('.bien-share');
  if (!box) {
    return;
  }

  const url = box.dataset.url || window.location.href;
  const text = box.dataset.text || document.title + ' | ' + url;

  switch (button.dataset.share) {
    case 'whatsapp':
      window.open('https://wa.me/?text=' + encodeURIComponent(text), '_blank', 'noopener');
      break;
    case 'facebook':
      window.open('https://www.facebook.com/sharer/sharer.php?u=' + encodeURIComponent(url), '_blank', 'noopener');
      break;
    case 'twitter':
      window.open('https://twitter.com/intent/tweet?text=' + encodeURIComponent(text), '_blank', 'noopener');
      break;
    case 'linkedin':
      window.open('https://www.linkedin.com/sharing/share-offsite/?url=' + encodeURIComponent(url), '_blank', 'noopener');
      break;
    case 'copy':
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(function () {
          showBienToast('Lien copié.');
        }).catch(function () {
          fallbackCopy(url);
        });
      } else {
        fallbackCopy(url);
      }
      break;
  }
});

function fallbackCopy(text) {
  const input = document.createElement('input');
  input.value = text;
  document.body.appendChild(input);
  input.select();

  try {
    document.execCommand('copy');
    showBienToast('Lien copié.');
  } catch (error) {
    showBienToast('Copie impossible. Veuillez copier le lien manuellement.');
  }

  document.body.removeChild(input);
}

function showBienToast(message) {
  const toast = document.getElementById('bienToast');
  if (!toast) {
    return;
  }

  toast.textContent = message;
  toast.classList.add('is-visible');

  window.clearTimeout(showBienToast._timer);
  showBienToast._timer = window.setTimeout(function () {
    toast.classList.remove('is-visible');
  }, 2200);
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
