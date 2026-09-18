<?php
// ======================================
// IBIG IMMO BOT – PACK C (BOT + LEADS) – VERSION STABLE
// ======================================

header('Content-Type: application/json; charset=utf-8');
mb_internal_encoding('UTF-8');
session_set_cookie_params([
    'lifetime' => 86400,
    'path' => '/',
    'domain' => 'ibigimmotrust.com',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'None'
]);

session_start();

// En production : on logge les erreurs mais on ne les affiche pas (pour ne pas casser le JSON)
ini_set('display_errors', 0);
error_reporting(E_ALL);

// -------------------------------------------------------
// Connexion PDO (adaptation éventuelle)
// -------------------------------------------------------
/*
 * Adapte ce require si besoin. Idéalement, ton config global contient $pdo.
 * Exemple courant : /includes/config.php avec $pdo déjà initialisé.
 */
require_once __DIR__ . '/../includes/config.php'; // À adapter selon ton projet

if (!isset($pdo) || !($pdo instanceof PDO)) {
    // Sécurité : si pas de PDO, on ne plante pas le bot, on continue sans sauvegarde
    $pdo = null;
}

// -------------------------------------------------------
// UTILITAIRES
// -------------------------------------------------------

if (!function_exists('str_contains')) {
    function str_contains($haystack, $needle) {
        return $needle !== '' && mb_strpos($haystack, $needle) !== false;
    }
}

function norm(string $txt): string {
    return mb_strtolower(trim(preg_replace('/\s+/u',' ',$txt)), 'UTF-8');
}

// -------------------------------------------------------
// LECTURE MESSAGE
// -------------------------------------------------------

$raw  = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!is_array($data) || !isset($data['message'])) {
    echo json_encode(["reply" => "Je n’ai pas reçu votre message. Pouvez-vous le répéter ?"], JSON_UNESCAPED_UNICODE);
    exit;
}

$user_raw = trim((string)$data["message"]);
$user     = norm($user_raw);

if ($user === "") {
    echo json_encode(["reply" => "Je n’ai pas reçu votre message. Pouvez-vous le répéter ?"], JSON_UNESCAPED_UNICODE);
    exit;
}

// -------------------------------------------------------
// CONTEXTE EN SESSION
// -------------------------------------------------------

if (!isset($_SESSION["immo_bot_ctx"])) {
    $_SESSION["immo_bot_ctx"] = [
        "step"     => "start",
        "name"     => null,
        "phone"    => null,
        "need"     => null,
        "type"     => null,
        "zone"     => null,
        "budget"   => null,
        "diaspora" => false,
        "saved"    => false
    ];
}
$ctx =& $_SESSION["immo_bot_ctx"];

// -------------------------------------------------------
// DÉTECTIONS AUTOMATIQUES
// -------------------------------------------------------

function detect_need(string $t): ?string {
    if (str_contains($t, "achat") || str_contains($t, "acheter")) return "achat";
    if (str_contains($t, "vente") || str_contains($t, "vendre"))   return "vente";
    if (str_contains($t, "location") || str_contains($t, "louer")) return "location";
    if (str_contains($t, "construction") || str_contains($t, "chantier") || str_contains($t, "btp")) return "construction";
    if (str_contains($t, "gestion")) return "gestion locative";
    if (str_contains($t, "invest"))  return "investissement";
    return null;
}

function detect_type(string $t): ?string {
    foreach (["terrain","maison","appartement","immeuble","villa","studio"] as $x) {
        if (str_contains($t, $x)) return $x;
    }
    return null;
}

function detect_zone(string $t): ?string {
    foreach (["abidjan","cocody","angre","angré","riviera","yopougon","bingerville","koumassi","marcory","plateau","treichville"] as $z) {
        if (str_contains($t, $z)) return ucfirst($z);
    }
    return null;
}

function detect_budget(string $t): ?string {
    if (
        !str_contains($t,"m") &&
        !str_contains($t,"million") &&
        !str_contains($t,"fcfa") &&
        !str_contains($t,"budget")
    ) {
        return null;
    }

    // ex : 80M / 50 m / 100 millions
    if (preg_match('/(\d+)\s*m\b/u',$t,$m)) {
        return number_format(((int)$m[1])*1000000, 0, ' ', ' ') . " FCFA";
    }

    if (preg_match('/(\d+)\s*mill/u',$t,$m)) {
        return number_format(((int)$m[1])*1000000, 0, ' ', ' ') . " FCFA";
    }

    // ex : 50 000 000
    if (preg_match('/(\d[\d\s]{5,})/u',$t,$m)) {
        $n = preg_replace('/\s+/', '', $m[1]);
        return number_format(((int)$n), 0, ' ', ' ') . " FCFA";
    }

    return null;
}

function detect_diaspora(string $t): bool {
    foreach (["france","paris","canada","belgique","allemagne","usa","italie","espagne","uk","londres","europe"] as $p) {
        if (str_contains($t, $p)) return true;
    }
    return false;
}

// Mise à jour automatique selon le message courant
if (!$ctx["need"])   $ctx["need"]   = detect_need($user);
if (!$ctx["type"])   $ctx["type"]   = detect_type($user);
if (!$ctx["zone"])   $ctx["zone"]   = detect_zone($user);
if (!$ctx["budget"]) $ctx["budget"] = detect_budget($user);
if (!$ctx["diaspora"] && detect_diaspora($user)) $ctx["diaspora"] = true;

// -------------------------------------------------------
// COMMANDES GLOBALES
// -------------------------------------------------------

if (str_contains($user,"reset") || str_contains($user,"recommencer")) {
    unset($_SESSION["immo_bot_ctx"]);
    echo json_encode([
        "reply" => "D’accord 😊 On recommence depuis le début.\nQuel est votre nom complet ?"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

if (str_contains($user,"ibig") && str_contains($user,"quoi")) {
    echo json_encode([
        "reply" =>
            "IBIG IMMO TRUST est la branche Immobilier & BTP du groupe INTERMARK BUSINESS.\n\n".
            "Nous accompagnons nos clients pour :\n".
            "• Achat & vente\n".
            "• Location\n".
            "• Construction & reprise de chantiers inachevés\n".
            "• Rénovation\n".
            "• Gestion locative (classique & PREMIUM)\n".
            "• Investissements immobiliers (locaux & diaspora).\n\n".
            "Expliquez-moi maintenant votre projet 🙂"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// -------------------------------------------------------
// SAUVEGARDE EN BASE (ROBUSTE, NE PLANTE JAMAIS LE BOT)
// -------------------------------------------------------

function save_lead_if_needed(array &$ctx, ?PDO $pdo): void {
    if ($ctx["saved"]) return; // déjà enregistré
    if (!$pdo) return;         // pas de PDO → on ne fait rien

    if (!$ctx["name"] || !$ctx["phone"] || !$ctx["need"] || !$ctx["type"] || !$ctx["zone"]) {
        return; // infos insuffisantes
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO immo_leads (full_name, phone, need, property_type, zone, budget, is_diaspora, source)
            VALUES (:n, :p, :need, :type, :zone, :budget, :diaspora, 'BOT')
        ");
        $stmt->execute([
            ':n'        => $ctx["name"],
            ':p'        => $ctx["phone"],
            ':need'     => $ctx["need"],
            ':type'     => $ctx["type"],
            ':zone'     => $ctx["zone"],
            ':budget'   => $ctx["budget"],
            ':diaspora' => $ctx["diaspora"] ? 1 : 0
        ]);

        $ctx["saved"] = true;
    } catch (Throwable $e) {
        // On ignore l'erreur en production (table manquante, etc.)
        // Option : error_log("IMMO BOT DB ERROR: ".$e->getMessage());
    }
}

// -------------------------------------------------------
// WORKFLOW PRINCIPAL (ÉTAPES)
// -------------------------------------------------------

switch ($ctx["step"]) {

    case "start":
        $ctx["step"] = "ask_name";
        echo json_encode([
            "reply" => "Très bien 👌 Pour commencer, quel est votre nom complet ?"
        ], JSON_UNESCAPED_UNICODE);
        exit;

    case "ask_name":
        $ctx["name"] = $user_raw;
        $ctx["step"] = "ask_phone";
        echo json_encode([
            "reply" => "Merci {$ctx['name']} 🙏 Quel est votre numéro WhatsApp ?"
        ], JSON_UNESCAPED_UNICODE);
        exit;

    case "ask_phone":
        $ctx["phone"] = $user_raw;
        $ctx["step"]  = "ask_need";
        echo json_encode([
            "reply" => "Parfait 👍 Votre projet concerne : achat, vente, location, construction, gestion locative ou investissement ?"
        ], JSON_UNESCAPED_UNICODE);
        exit;

    case "ask_need":
        if (!$ctx["need"]) {
            echo json_encode([
                "reply" => "Merci 😊 Pour mieux vous orienter : achat, vente, location, construction, gestion locative ou investissement ?"
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $ctx["step"] = "ask_details";
        echo json_encode([
            "reply" => "Très bien, projet de **{$ctx['need']}** 👍\nPouvez-vous préciser le type de bien (terrain, maison, immeuble…) et la zone (Cocody, Riviera, Yopougon, Bingerville…)?"
        ], JSON_UNESCAPED_UNICODE);
        exit;

    case "ask_details":
        // On attend au moins type + zone ; budget sera pris si présent
        if (!$ctx["type"] || !$ctx["zone"]) {
            echo json_encode([
                "reply" => "Merci 🙂 Il me faut à la fois le type de bien (terrain, maison, appartement…) et la zone (commune/quartier)."
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        // Si type + zone OK mais pas de budget → on le demande avant le résumé
        if (!$ctx["budget"]) {
            echo json_encode([
                "reply" => "Très bien 😊 et quel budget prévoyez-vous pour ce projet ? (ex : 50M, 80 millions FCFA…)"
            ], JSON_UNESCAPED_UNICODE);
            // On ne change pas de step ici : le prochain message mettra à jour budget via detect_budget()
            // et on repassera dans ask_details avec type + zone + budget.
            exit;
        }

        // À ce stade, on a assez pour enregistrer le lead
        save_lead_if_needed($ctx, $pdo);

        $resume  = "Merci 🙏 Voici le résumé de votre projet :\n\n";
        $resume .= "🏠 Bien : {$ctx['type']}\n";
        $resume .= "📍 Zone : {$ctx['zone']}\n";
        if ($ctx['budget'])   $resume .= "💰 Budget : {$ctx['budget']}\n";
        if ($ctx['diaspora']) $resume .= "🌍 Situation : Diaspora\n";
        $resume .= "👤 Nom : {$ctx['name']}\n";
        $resume .= "📱 WhatsApp : {$ctx['phone']}\n\n";
        $resume .= "Un conseiller IBIG IMMO TRUST vous contactera très rapidement pour analyser les meilleures options.\n\n";
        $resume .= "Souhaitez-vous que je vous explique aussi les options de financement (Direct IBIG, investisseurs, banques) ou la gestion locative ?";

        $ctx["step"] = "conversation";

        echo json_encode(["reply" => $resume], JSON_UNESCAPED_UNICODE);
        exit;
}

// -------------------------------------------------------
// MODE CONVERSATION APRÈS RÉSUMÉ
// -------------------------------------------------------

// FINANCEMENT : Direct IBIG
if (str_contains($user,"direct ibig") || (str_contains($user,"direct") && str_contains($user,"ibig"))) {
    echo json_encode([
        "reply" =>
            "🔵 FINANCEMENT DIRECT IBIG\n\n".
            "• IBIG finance une partie du projet (construction ou acquisition locative)\n".
            "• Remboursement sur les loyers ou sur un échéancier défini\n".
            "• Le bien reste à votre nom\n".
            "• Suivi digital (photos, vidéos, rapports) – idéal pour la diaspora\n\n".
            "Voulez-vous qu’un conseiller vous appelle pour une simulation personnalisée ?"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// FINANCEMENT : Investisseurs
if (str_contains($user,"investisseur") || str_contains($user,"investisseurs")) {
    echo json_encode([
        "reply" =>
            "🟡 FINANCEMENT PAR INVESTISSEURS\n\n".
            "• Montages pour immeubles, mini-résidences, projets à forte rentabilité\n".
            "• Apport des investisseurs + apport personnel\n".
            "• Contrats encadrés, suivi assuré par IBIG IMMO TRUST\n\n".
            "Souhaitez-vous que nous vérifions votre éligibilité à ce type de montage ?"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// FINANCEMENT : Banques / microfinances
if (
    str_contains($user,"banque") ||
    str_contains($user,"crédit") ||
    str_contains($user,"credit") ||
    str_contains($user,"prêt")   ||
    str_contains($user,"pret")   ||
    str_contains($user,"microfinance")
) {
    echo json_encode([
        "reply" =>
            "🟢 FINANCEMENT BANCAIRE / MICROFINANCE\n\n".
            "• Crédit immobilier possible jusqu’à 10–15 ans selon les dossiers\n".
            "• IBIG IMMO TRUST vous accompagne pour monter, déposer et suivre le dossier\n".
            "• Possibilité de combiner apport + crédit + accompagnement IBIG\n\n".
            "Voulez-vous qu’on vous rappelle pour préparer votre dossier bancaire ?"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// GESTION LOCATIVE
if (str_contains($user,"gestion locative") || str_contains($user,"gestion")) {
    echo json_encode([
        "reply" =>
            "📊 GESTION LOCATIVE IBIG IMMO TRUST\n\n".
            "Deux grandes formules :\n".
            "• Gestion classique : encaissement des loyers, suivi des locataires, entretien\n".
            "• Gestion PREMIUM : formule avec Loyer Garanti IBIG (sous conditions)\n\n".
            "Nous pouvons étudier votre bien et vous proposer la meilleure formule.\n".
            "Souhaitez-vous un rendez-vous téléphonique avec un gestionnaire ?"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// CONSTRUCTION / CHANTIER INACHEVÉ
if ($ctx["need"] === "construction" || str_contains($user,"chantier")) {
    echo json_encode([
        "reply" =>
            "🏗️ CONSTRUCTION & CHANTIERS INACHEVÉS\n\n".
            "IBIG IMMO TRUST vous accompagne pour :\n".
            "• Étude technique (plans, devis, phasage)\n".
            "• Suivi de chantier (photos/vidéos, reporting digital)\n".
            "• Reprise de chantier inachevé et mise en conformité\n\n".
            "Souhaitez-vous qu’un ingénieur BTP vous rappelle pour un pré-diagnostic gratuit ?"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// LOCATION
if ($ctx["need"] === "location") {
    echo json_encode([
        "reply" =>
            "🔑 LOCATION\n\n".
            "Nous pouvons vous proposer des biens en location en fonction de :\n".
            "• La zone\n".
            "• Le type (studio, 2 pièces, 3 pièces, villa…)\n".
            "• Votre budget mensuel\n\n".
            "Souhaitez-vous recevoir une liste de biens par WhatsApp dès qu’un conseiller a filtré les offres ?"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// INVESTISSEMENT
if ($ctx["need"] === "investissement") {
    echo json_encode([
        "reply" =>
            "📈 INVESTISSEMENT IMMOBILIER\n\n".
            "Avec IBIG IMMO TRUST, vous pouvez investir dans :\n".
            "• Terrains à forte valeur future\n".
            "• Immeubles ou mini-résidences locatives\n".
            "• Projets clé en main avec gestion locative intégrée\n\n".
            "Préférez-vous un revenu locatif mensuel ou une forte plus-value à la revente ?"
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

// -------------------------------------------------------
// FALLBACK GÉNÉRIQUE
// -------------------------------------------------------

echo json_encode([
    "reply" => "Je comprends 😊 Pouvez-vous préciser si votre projet concerne : achat, vente, location, construction, gestion locative ou investissement ?"
], JSON_UNESCAPED_UNICODE);
exit;
