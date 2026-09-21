<?php
/* ==========================================================
   HEADER PREMIUM LUXE — IBIG IMMO TRUST
   Design : Bleu Marine #0A1628 | Or #D4AF37
   Version : 3.0 Premium
========================================================== */

if (!isset($pageTitle)) {
    $pageTitle = defined('SITE_NAME') ? SITE_NAME : 'IBIG IMMO TRUST';
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isUserConnected = isset($_SESSION['user_id']);
if (!isset($currentPage)) {
    $currentPage = '';
}

$pagesAvecSlider = ['home', 'immobilier', 'btp', 'financement'];
$disableHeaderSlider = $disableHeaderSlider ?? false;

if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

$metaDescription = $metaDescription ?? 'IBIG IMMO TRUST : Immobilier, BTP, financement, gestion locative, assistance foncière et services immobiliers en Côte d\'Ivoire.';
$metaRobots = $metaRobots ?? 'index,follow';
$canonicalUrl = $canonicalUrl ?? ((isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : 'https') . '://' . ($_SERVER['HTTP_HOST'] ?? 'ibigimmotrust.com') . ($_SERVER['REQUEST_URI'] ?? '/'));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title><?= e($pageTitle) ?></title>
<meta name="description" content="<?= e($metaDescription) ?>">
<meta name="robots" content="<?= e($metaRobots) ?>">
<link rel="canonical" href="<?= e($canonicalUrl) ?>">

<!-- Open Graph -->
<meta property="og:locale" content="fr_FR">
<meta property="og:type" content="website">
<meta property="og:title" content="<?= e($pageTitle) ?>">
<meta property="og:description" content="<?= e($metaDescription) ?>">
<meta property="og:url" content="<?= e($canonicalUrl) ?>">
<meta property="og:image" content="<?= e($ogImage ?? BASE_URL.'/assets/img/default.jpg') ?>">
<meta property="og:site_name" content="IBIG IMMO TRUST">

<!-- Twitter -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= e($pageTitle) ?>">
<meta name="twitter:description" content="<?= e($metaDescription) ?>">

<?= $metaOG ?? '' ?>

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<!-- CSS -->
<link rel="stylesheet" href="<?= BASE_URL; ?>/assets/css/style.css?v=<?= time(); ?>">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<!-- Favicons -->
<link rel="icon" type="image/x-icon" href="<?= BASE_URL; ?>/assets/img/favicon.ico">
<link rel="icon" type="image/png" sizes="32x32" href="<?= BASE_URL; ?>/assets/img/favicon-32x32.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?= BASE_URL; ?>/assets/img/apple-touch-icon.png">

<style>
/* ============================================================
   VARIABLES LUXE — IBIG IMMO TRUST
============================================================ */
:root {
  --navy:        #0A1628;
  --navy-deep:   #060d1a;
  --navy-mid:    #0f2044;
  --navy-light:  #1a3060;
  --gold:        #D4AF37;
  --gold-light:  #E8CC6A;
  --gold-dark:   #A68920;
  --white:       #ffffff;
  --cream:       #FAF7F0;
  --text:        #111827;
  --muted:       #6B7280;
  --line:        #E5E7EB;
  --radius:      14px;
  --radius-sm:   8px;
  --radius-lg:   20px;
  --transition:  all 0.3s cubic-bezier(0.4,0,0.2,1);
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
  color: var(--text);
  background: #fff;
  padding-top: 0;
  line-height: 1.6;
}
a { text-decoration: none; color: inherit; }
img { max-width: 100%; display: block; }

/* ── Espacement global des titres et paragraphes ── */
h1, h2, h3, h4 {
  line-height: 1.25;
  margin-bottom: 0.65em;
}
p { line-height: 1.7; margin-bottom: 1em; }
p:last-child { margin-bottom: 0; }
h2 + p, h3 + p { margin-top: 0; }

/* ============================================================
   ANNOUNCEMENT STRIP — TICKER DÉFILANT
============================================================ */
.announcement-strip {
  background: #ffffff;
  padding: 9px 0;
  border-bottom: 2px solid rgba(212,175,55,0.4);
  overflow: hidden;
  -webkit-mask-image: linear-gradient(90deg, transparent 0%, #000 6%, #000 94%, transparent 100%);
  mask-image: linear-gradient(90deg, transparent 0%, #000 6%, #000 94%, transparent 100%);
}

.strip-track {
  display: flex;
  align-items: center;
  gap: 0;
  white-space: nowrap;
  animation: strip-scroll 28s linear infinite;
  width: max-content;
}

.strip-track:hover { animation-play-state: paused; }

@keyframes strip-scroll {
  0%   { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}

.strip-item {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 0 28px;
  font-size: 12.5px;
  font-weight: 700;
  color: #1a1a1a;
  letter-spacing: 0.04em;
  text-transform: uppercase;
}

.strip-item .gold { color: var(--gold); }
.strip-item .sep {
  width: 4px; height: 4px;
  background: var(--gold);
  border-radius: 50%;
  opacity: 0.6;
  flex-shrink: 0;
}

/* ============================================================
   HEADER PRINCIPAL
============================================================ */
.header-premium {
  background: var(--navy);
  position: sticky;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 9999;
  border-bottom: 1px solid rgba(212,175,55,0.12);
  transition: var(--transition);
}

.header-premium.scrolled {
  background: rgba(10,22,40,0.97);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  box-shadow: 0 8px 40px rgba(10,22,40,0.4);
}

.header-premium.scrolled .header-row-top { min-height: 54px; }
.header-premium.scrolled .header-row-bottom { display: none !important; }
.announcement-strip { transition: max-height 0.3s ease, padding 0.3s ease, opacity 0.25s ease; overflow: hidden; }
.announcement-strip.hidden { max-height: 0; padding: 0; opacity: 0; pointer-events: none; }

.header-inner {
  max-width: 1380px;
  margin: 0 auto;
  padding: 0 24px;
}

/* LIGNE 1 — Logo + Nav + Actions */
.header-row-top {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  min-height: 72px;
}

/* LOGO */
.brand {
  flex: 0 0 auto;
  display: flex;
  align-items: center;
  gap: 14px;
  text-decoration: none;
}

.logo-img { height: 52px; width: auto; }

.brand-text { display: flex; flex-direction: column; line-height: 1.1; }

.brand-name {
  font-size: 17px;
  font-weight: 900;
  color: #fff;
  letter-spacing: 0.02em;
}

.brand-name .gold { color: var(--gold); }

.brand-tag {
  font-size: 10px;
  font-weight: 600;
  color: rgba(255,255,255,0.45);
  letter-spacing: 0.1em;
  text-transform: uppercase;
  margin-top: 2px;
}

/* NAV */
.nav-premium {
  flex: 1 1 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 2px;
  flex-wrap: nowrap;
}

.nav-premium > a {
  white-space: nowrap;
  color: rgba(255,255,255,0.75);
  font-size: 14px;
  font-weight: 600;
  padding: 9px 14px;
  border-radius: var(--radius-sm);
  transition: var(--transition);
  position: relative;
}

.nav-premium > a::after {
  content: '';
  position: absolute;
  bottom: 4px; left: 14px; right: 14px;
  height: 2px;
  background: var(--gold);
  border-radius: 2px;
  transform: scaleX(0);
  transition: transform 0.3s ease;
}

.nav-premium > a:hover {
  color: #fff;
  background: rgba(255,255,255,0.07);
}

.nav-premium > a:hover::after { transform: scaleX(1); }
.nav-premium > a.active { color: var(--gold); }
.nav-premium > a.active::after { transform: scaleX(1); }

/* HEADER TOOLS */
.header-tools {
  flex: 0 0 auto;
  display: flex;
  align-items: center;
  gap: 8px;
}

.btn-head {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  border-radius: 999px;
  font-size: 13px;
  font-weight: 700;
  transition: var(--transition);
  white-space: nowrap;
  text-decoration: none;
}

.btn-head-outline {
  border: 1.5px solid rgba(212,175,55,0.4);
  color: rgba(255,255,255,0.8);
}

.btn-head-outline:hover {
  border-color: var(--gold);
  color: var(--gold);
}

.btn-head-gold {
  background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
  color: var(--navy);
  box-shadow: 0 4px 15px rgba(212,175,55,0.3);
}

.btn-head-gold:hover {
  box-shadow: 0 6px 25px rgba(212,175,55,0.5);
  transform: translateY(-1px);
}

/* BURGER */
.nav-toggle {
  display: none;
  align-items: center;
  justify-content: center;
  width: 42px; height: 42px;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(212,175,55,0.2);
  border-radius: var(--radius-sm);
  color: var(--gold);
  font-size: 18px;
  cursor: pointer;
  transition: var(--transition);
  outline: none;
}

.nav-toggle:hover { background: rgba(255,255,255,0.14); }

/* ============================================================
   LIGNE 2 — BARRE DE RECHERCHE IMMOBILIÈRE
============================================================ */
.header-row-bottom {
  background: rgba(0,0,0,0.25);
  border-top: 1px solid rgba(212,175,55,0.08);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  padding: 10px 0;
  flex-wrap: nowrap;
}

.blink-biens {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  font-size: 12.5px;
  font-weight: 700;
  border-radius: 999px;
  background: rgba(212,175,55,0.1);
  border: 1px solid rgba(212,175,55,0.25);
  color: var(--gold);
  white-space: nowrap;
  text-decoration: none;
  transition: var(--transition);
  animation: pulseBiens 2s ease-in-out infinite;
}

.blink-biens:hover {
  background: rgba(212,175,55,0.2);
  color: var(--gold-light);
}

@keyframes pulseBiens {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

/* Recherche immobilière */
.immo-search {
  display: flex;
  align-items: center;
  flex: 1 1 0;
  min-width: 0;
  max-width: 800px;
  background: rgba(255,255,255,0.07);
  border: 1px solid rgba(212,175,55,0.18);
  border-radius: 999px;
  padding: 3px 3px 3px 14px;
  gap: 4px;
  transition: var(--transition);
}

.immo-search:focus-within {
  border-color: rgba(212,175,55,0.45);
  background: rgba(255,255,255,0.1);
}

.immo-search select,
.immo-search input {
  border: none;
  outline: none;
  height: 32px;
  font-size: 13px;
  padding: 0 8px;
  background: transparent;
  color: rgba(255,255,255,0.85);
  font-family: inherit;
  flex: 1 1 0;
  min-width: 0;
}

.immo-search select option { background: var(--navy); color: #fff; }
.immo-search input::placeholder { color: rgba(255,255,255,0.4); }

.immo-search .vr {
  width: 1px; height: 20px;
  background: rgba(255,255,255,0.12);
  flex-shrink: 0;
}

.search-button {
  height: 34px;
  padding: 0 18px;
  background: linear-gradient(135deg, var(--gold), var(--gold-dark));
  color: var(--navy);
  border: none;
  border-radius: 999px;
  font-size: 13px;
  font-weight: 800;
  cursor: pointer;
  transition: var(--transition);
  display: flex;
  align-items: center;
  gap: 6px;
  white-space: nowrap;
  flex-shrink: 0;
}

.search-button:hover {
  box-shadow: 0 4px 15px rgba(212,175,55,0.4);
  transform: scale(1.02);
}

.btn-row {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

.btn-publish {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  background: linear-gradient(135deg, var(--gold), var(--gold-dark));
  color: var(--navy);
  border-radius: 999px;
  font-size: 12.5px;
  font-weight: 800;
  white-space: nowrap;
  text-decoration: none;
  transition: var(--transition);
  box-shadow: 0 4px 12px rgba(212,175,55,0.25);
}

.btn-publish:hover {
  box-shadow: 0 6px 20px rgba(212,175,55,0.4);
  transform: translateY(-1px);
}

.btn-rdv {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  background: rgba(255,255,255,0.1);
  border: 1px solid rgba(255,255,255,0.2);
  color: rgba(255,255,255,0.85);
  border-radius: 999px;
  font-size: 12.5px;
  font-weight: 700;
  white-space: nowrap;
  text-decoration: none;
  transition: var(--transition);
}

.btn-rdv:hover {
  background: rgba(255,255,255,0.15);
  color: #fff;
  border-color: rgba(255,255,255,0.4);
}

.btn-partner {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 14px;
  background: rgba(255,255,255,0.06);
  border: 1px solid rgba(212,175,55,0.2);
  color: rgba(255,255,255,0.7);
  border-radius: 999px;
  font-size: 12.5px;
  font-weight: 700;
  white-space: nowrap;
  text-decoration: none;
  transition: var(--transition);
}

.btn-partner:hover {
  background: rgba(212,175,55,0.1);
  color: var(--gold);
  border-color: rgba(212,175,55,0.4);
}

.btn-account {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 7px 12px;
  background: rgba(255,255,255,0.07);
  border: 1px solid rgba(255,255,255,0.12);
  color: rgba(255,255,255,0.65);
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  text-decoration: none;
  transition: var(--transition);
}

.btn-account:hover {
  background: rgba(255,255,255,0.12);
  color: #fff;
}

/* ============================================================
   MENU MOBILE
============================================================ */
#mobile-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(6,13,26,0.75);
  z-index: 9998;
  backdrop-filter: blur(4px);
}

#mobile-menu {
  position: fixed;
  top: 0; right: -100%;
  width: min(320px, 90vw);
  height: 100%;
  background: var(--navy);
  z-index: 9999;
  overflow-y: auto;
  transition: right 0.35s cubic-bezier(0.4,0,0.2,1);
  border-left: 1px solid rgba(212,175,55,0.15);
}

.mobile-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 18px 20px;
  border-bottom: 1px solid rgba(212,175,55,0.12);
}

.mobile-header span {
  font-size: 15px;
  font-weight: 700;
  color: var(--gold);
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

#mobile-close {
  width: 34px; height: 34px;
  background: rgba(255,255,255,0.07);
  border: 1px solid rgba(255,255,255,0.1);
  border-radius: 8px;
  color: rgba(255,255,255,0.6);
  font-size: 16px;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: var(--transition);
}

#mobile-close:hover { background: rgba(255,255,255,0.14); color: #fff; }

.mobile-nav {
  padding: 12px 0;
}

.mobile-nav a {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 13px 20px;
  font-size: 14px;
  font-weight: 600;
  color: rgba(255,255,255,0.72);
  text-decoration: none;
  transition: var(--transition);
  border-left: 3px solid transparent;
}

.mobile-nav a i {
  width: 18px;
  color: var(--gold);
  font-size: 14px;
  text-align: center;
}

.mobile-nav a:hover,
.mobile-nav a.mobile-highlight {
  background: rgba(212,175,55,0.07);
  color: var(--gold);
  border-left-color: var(--gold);
}

.mobile-separator {
  height: 1px;
  background: rgba(212,175,55,0.1);
  margin: 12px 20px;
}

.mobile-actions {
  padding: 0 16px 24px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.mobile-btn-primary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 13px 20px;
  background: linear-gradient(135deg, var(--gold), var(--gold-dark));
  color: var(--navy);
  border-radius: var(--radius);
  font-size: 14px;
  font-weight: 800;
  text-decoration: none;
  text-align: center;
  transition: var(--transition);
}

.mobile-btn-primary:hover { filter: brightness(1.05); }

.mobile-btn-secondary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  background: rgba(255,255,255,0.08);
  border: 1.5px solid rgba(255,255,255,0.2);
  color: rgba(255,255,255,0.85);
  border-radius: var(--radius);
  font-size: 14px;
  font-weight: 700;
  text-decoration: none;
  transition: var(--transition);
}

.mobile-btn-secondary:hover { background: rgba(255,255,255,0.14); color: #fff; }

.mobile-btn-partner {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 12px 20px;
  background: rgba(212,175,55,0.1);
  border: 1px solid rgba(212,175,55,0.25);
  color: var(--gold);
  border-radius: var(--radius);
  font-size: 14px;
  font-weight: 700;
  text-decoration: none;
  transition: var(--transition);
}

.mobile-btn-partner:hover { background: rgba(212,175,55,0.18); }

.mobile-btn-outline {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 11px 20px;
  background: transparent;
  border: 1px solid rgba(255,255,255,0.15);
  color: rgba(255,255,255,0.55);
  border-radius: var(--radius);
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  transition: var(--transition);
}

.mobile-btn-outline:hover { border-color: rgba(255,255,255,0.35); color: #fff; }

/* ============================================================
   HERO SLIDER
============================================================ */
.hero-slider {
  position: relative;
  height: 620px;
  overflow: hidden;
  background: var(--navy-deep);
}

.slide {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center;
  opacity: 0;
  transition: opacity 1s ease;
}

.slide::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(10,22,40,0.85) 0%, rgba(10,22,40,0.55) 60%, rgba(10,22,40,0.3) 100%);
}

.slide.active { opacity: 1; }

.slide-content {
  position: absolute;
  bottom: 90px;
  left: 80px;
  max-width: 580px;
  z-index: 5;
}

.kicker {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-size: 11px;
  font-weight: 800;
  color: var(--gold);
  letter-spacing: 0.15em;
  text-transform: uppercase;
  margin-bottom: 14px;
  background: rgba(212,175,55,0.1);
  border: 1px solid rgba(212,175,55,0.25);
  padding: 5px 14px;
  border-radius: 999px;
}

.kicker::before {
  content: '';
  width: 20px; height: 1px;
  background: var(--gold);
}

.slide-content h1 {
  font-size: clamp(30px, 4vw, 52px);
  font-weight: 900;
  color: #fff;
  line-height: 1.15;
  margin-bottom: 14px;
  letter-spacing: -0.02em;
}

.slide-content p {
  font-size: 16px;
  color: rgba(255,255,255,0.72);
  line-height: 1.65;
  margin-bottom: 28px;
  max-width: 480px;
}

.hero-actions {
  display: flex;
  gap: 12px;
  flex-wrap: wrap;
}

.hero-btn {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 13px 24px;
  border-radius: 999px;
  font-size: 14px;
  font-weight: 700;
  transition: var(--transition);
  text-decoration: none;
  border: none;
}

.hero-btn.primary {
  background: linear-gradient(135deg, var(--gold), var(--gold-dark));
  color: var(--navy);
  box-shadow: 0 6px 25px rgba(212,175,55,0.35);
}

.hero-btn.primary:hover {
  box-shadow: 0 10px 35px rgba(212,175,55,0.55);
  transform: translateY(-2px);
}

.hero-btn.outline {
  color: #fff;
  background: rgba(255,255,255,0.1);
  border: 1.5px solid rgba(255,255,255,0.3);
  backdrop-filter: blur(10px);
}

.hero-btn.outline:hover {
  background: rgba(255,255,255,0.18);
  border-color: rgba(255,255,255,0.6);
  transform: translateY(-2px);
}

/* Slider controls */
.hero-prev,
.hero-next {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
  width: 46px; height: 46px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: rgba(255,255,255,0.12);
  border: 1px solid rgba(255,255,255,0.2);
  color: #fff;
  font-size: 18px;
  cursor: pointer;
  transition: var(--transition);
  backdrop-filter: blur(10px);
}

.hero-prev { left: 24px; }
.hero-next { right: 24px; }

.hero-prev:hover, .hero-next:hover {
  background: var(--gold);
  color: var(--navy);
  border-color: var(--gold);
}

.slider-dots {
  position: absolute;
  left: 50%;
  bottom: 28px;
  transform: translateX(-50%);
  z-index: 10;
  display: flex;
  gap: 8px;
}

.slider-dots span {
  width: 8px; height: 8px;
  border-radius: 50%;
  background: rgba(255,255,255,0.4);
  cursor: pointer;
  transition: var(--transition);
}

.slider-dots span.active {
  background: var(--gold);
  width: 24px;
  border-radius: 4px;
}

/* ============================================================
   RESPONSIVE
============================================================ */
@media (max-width: 1100px) {
  .brand-text { display: none; }
  .nav-premium { display: none; }
  .header-tools { display: none; }
  .nav-toggle { display: flex !important; }
  .header-row-bottom { display: none; }
}

@media (max-width: 900px) {
  .hero-slider { height: 500px; }
  .slide-content { left: 24px; right: 24px; max-width: none; bottom: 70px; }
  .slide-content h1 { font-size: 30px; }
  .slide-content p { font-size: 15px; }
}

@media (max-width: 500px) {
  .hero-slider { height: 450px; }
  .slide-content h1 { font-size: 26px; }
  .hero-actions { flex-direction: column; }
  .hero-btn { justify-content: center; }
  .hero-prev, .hero-next { display: none; }
  .announcement-strip { display: none; }
}
</style>
</head>
<body>

<!-- ANNOUNCEMENT STRIP — TICKER -->
<div class="announcement-strip">
  <div class="strip-track" aria-hidden="true">
    <?php
    $stripItems = [
      ['icon'=>'fa-solid fa-star gold', 'text'=>'IBIG IMMO TRUST'],
      ['sep'=>true],
      ['text'=>'Immobilier'],
      ['sep'=>true],
      ['text'=>'BTP &amp; Construction'],
      ['sep'=>true],
      ['text'=>'Financement'],
      ['sep'=>true],
      ['text'=>'Gestion Locative'],
      ['sep'=>true],
      ['icon'=>'fa-solid fa-location-dot gold', 'text'=>"Côte d'Ivoire"],
      ['sep'=>true],
      ['text'=>'Diaspora'],
      ['sep'=>true],
      ['text'=>'Assistance foncière'],
      ['sep'=>true],
      ['icon'=>'fa-solid fa-shield-halved gold', 'text'=>'Loyer garanti'],
      ['sep'=>true],
      ['text'=>'Investissement sécurisé'],
      ['sep'=>true],
      ['icon'=>'fa-solid fa-star gold', 'text'=>'IBIG IMMO TRUST'],
    ];
    // Render twice for seamless loop
    for ($r = 0; $r < 2; $r++):
      foreach ($stripItems as $it):
        if (!empty($it['sep'])): ?>
          <span class="strip-item"><span class="sep"></span></span>
        <?php else: ?>
          <span class="strip-item">
            <?php if (!empty($it['icon'])): ?>
              <i class="<?= $it['icon'] ?>"></i>
            <?php endif; ?>
            <?= $it['text'] ?>
          </span>
        <?php endif;
      endforeach;
    endfor;
    ?>
  </div>
</div>

<!-- HEADER PRINCIPAL -->
<header class="header-premium" id="main-header">
  <div class="header-inner">

    <!-- LIGNE 1 : Logo + Nav + Actions -->
    <div class="header-row-top">

      <a class="brand" href="<?= BASE_URL; ?>/">
        <img src="<?= BASE_URL; ?>/assets/img/logo.png" class="logo-img" alt="IBIG IMMO TRUST" width="52" height="52">
        <span class="brand-text">
          <span class="brand-name">IBIG <span class="gold">IMMO</span> TRUST</span>
          <span class="brand-tag">Immobilier · BTP · Financement</span>
        </span>
      </a>

      <nav class="nav-premium" aria-label="Navigation principale">
        <a href="<?= BASE_URL; ?>/" class="<?= $currentPage==='home'?'active':'' ?>">
          <i class="fa-solid fa-house" style="font-size:11px;opacity:.7;"></i> Accueil
        </a>
        <a href="<?= BASE_URL; ?>/immobilier.php" class="<?= $currentPage==='immobilier'?'active':'' ?>">Immobilier</a>
        <a href="<?= BASE_URL; ?>/btp.php" class="<?= $currentPage==='btp'?'active':'' ?>">BTP</a>
        <a href="<?= BASE_URL; ?>/financement.php" class="<?= $currentPage==='financement'?'active':'' ?>">Financement</a>
        <a href="<?= BASE_URL; ?>/diaspora.php" class="<?= $currentPage==='diaspora'?'active':'' ?>">Diaspora</a>

        <a href="<?= BASE_URL; ?>/contact.php" class="<?= $currentPage==='contact'?'active':'' ?>">Contact</a>
      </nav>

      <div class="header-tools">
        <a href="<?= BASE_URL; ?>/tous_les_biens.php" class="btn-head btn-head-outline">
          <i class="fa-solid fa-building"></i> Nos biens
        </a>
        <a href="<?= BASE_URL; ?>/publier-bien.php" class="btn-head btn-head-gold">
          <i class="fa-solid fa-plus"></i> Publier
        </a>
        <?php if($isUserConnected): ?>
          <a href="<?= BASE_URL; ?>/compte/dashboard.php" class="btn-account">
            <i class="fa-solid fa-user"></i>
          </a>
        <?php else: ?>
          <a href="<?= BASE_URL; ?>/compte/connexion.php" class="btn-account">
            <i class="fa-solid fa-user"></i>
          </a>
        <?php endif; ?>
      </div>

      <button class="nav-toggle" id="burger-btn" aria-label="Ouvrir le menu">
        <i class="fa-solid fa-bars"></i>
      </button>

    </div>

    <!-- LIGNE 2 : Barre immobilière -->
    <div class="header-row-bottom">

      <a href="<?= BASE_URL; ?>/tous_les_biens.php" class="blink-biens">
        <i class="fa-solid fa-location-dot"></i>
        Biens disponibles
      </a>

      <form action="<?= BASE_URL; ?>/recherche.php" method="GET" class="immo-search">
        <select name="transaction">
          <option value="">Vente / Location</option>
          <option value="vente">Vente</option>
          <option value="location">Location</option>
        </select>
        <span class="vr"></span>
        <select name="ville">
          <option value="">Ville</option>
          <option>Abidjan</option>
          <option>Bingerville</option>
          <option>Grand Bassam</option>
          <option>Yamoussoukro</option>
          <option>San Pedro</option>
          <option>Bouaké</option>
        </select>
        <span class="vr"></span>
        <select name="type">
          <option value="">Type de bien</option>
          <option value="villa">Villa</option>
          <option value="duplex">Duplex</option>
          <option value="appartement">Appartement</option>
          <option value="studio">Studio</option>
          <option value="immeuble">Immeuble</option>
          <option value="terrain">Terrain</option>
          <option value="bureau">Bureau</option>
          <option value="Magasin">Magasin</option>
        </select>
        <span class="vr"></span>
        <input type="number" name="budget" placeholder="Budget (XOF)">
        <button type="submit" class="search-button">
          <i class="fa-solid fa-magnifying-glass"></i> Rechercher
        </button>
      </form>

      <div class="btn-row">
        <a href="<?= BASE_URL; ?>/publier-bien.php" class="btn-publish">
          <i class="fa-solid fa-plus"></i> Publier un bien
        </a>
        <a href="<?= BASE_URL; ?>/rdv.php" class="btn-rdv">
          <i class="fa-regular fa-calendar"></i> RDV
        </a>
        <a href="https://www.ibigpartners.com/" class="btn-partner" target="_blank" rel="noopener">
          <i class="fa-solid fa-handshake"></i> Partenaire
        </a>
        <a href="<?= BASE_URL; ?>/compte/connexion.php" class="btn-account">
          <i class="fa-solid fa-user"></i> Connexion
        </a>
      </div>

    </div>

  </div>
</header>

<!-- OVERLAY MOBILE -->
<div id="mobile-overlay" aria-hidden="true"></div>

<!-- MENU MOBILE -->
<aside id="mobile-menu" aria-label="Menu mobile" aria-hidden="true">
  <div class="mobile-header">
    <span>Menu</span>
    <button id="mobile-close" aria-label="Fermer">
      <i class="fa-solid fa-xmark"></i>
    </button>
  </div>

  <nav class="mobile-nav">
    <a href="<?= BASE_URL; ?>/"><i class="fa-solid fa-house"></i> Accueil</a>
    <a href="<?= BASE_URL; ?>/immobilier.php"><i class="fa-solid fa-building"></i> Immobilier</a>
    <a href="<?= BASE_URL; ?>/btp.php"><i class="fa-solid fa-helmet-safety"></i> BTP & Construction</a>
    <a href="<?= BASE_URL; ?>/financement.php"><i class="fa-solid fa-coins"></i> Financement</a>
    <a href="<?= BASE_URL; ?>/diaspora.php"><i class="fa-solid fa-globe"></i> Diaspora</a>

    <a href="<?= BASE_URL; ?>/contact.php"><i class="fa-solid fa-envelope"></i> Contact</a>
    <a href="<?= BASE_URL; ?>/tous_les_biens.php" class="mobile-highlight">
      <i class="fa-solid fa-location-dot"></i> Biens disponibles
    </a>
  </nav>

  <div class="mobile-separator"></div>

  <div class="mobile-actions">
    <a href="<?= $isUserConnected ? BASE_URL.'/publier-bien.php' : BASE_URL.'/compte/connexion.php?redirect=publier-bien'; ?>"
       class="mobile-btn-primary">
      <i class="fa-solid fa-plus"></i> Publier un bien GRATUITEMENT
    </a>
    <a href="<?= BASE_URL; ?>/rdv.php" class="mobile-btn-secondary">
      <i class="fa-regular fa-calendar-check"></i> Prendre RDV
    </a>
    <a href="https://www.ibigpartners.com/" class="mobile-btn-partner" target="_blank" rel="noopener">
      <i class="fa-solid fa-handshake"></i> Devenir partenaire
    </a>
    <?php if($isUserConnected): ?>
      <a href="<?= BASE_URL; ?>/compte/dashboard.php" class="mobile-btn-outline">
        <i class="fa-solid fa-user"></i> Mon compte
      </a>
    <?php else: ?>
      <a href="<?= BASE_URL; ?>/compte/connexion.php" class="mobile-btn-outline">
        <i class="fa-solid fa-user"></i> Connexion / Inscription
      </a>
    <?php endif; ?>
  </div>
</aside>

<?php if (!$disableHeaderSlider && in_array($currentPage, $pagesAvecSlider, true)): ?>
<section class="hero-slider" aria-label="Bannière principale">

  <div class="slide active" style="background-image:url('<?= BASE_URL; ?>/assets/img/slide1.jpg');">
    <div class="slide-content">
      <span class="kicker">IBIG IMMO TRUST</span>
      <h1>Votre patrimoine,<br>notre expertise</h1>
      <p>Immobilier · BTP · Gestion locative · Financement — un accompagnement premium, fiable et rapide en Côte d'Ivoire.</p>
      <div class="hero-actions">
        <a href="<?= BASE_URL; ?>/tous_les_biens.php" class="hero-btn primary">
          <i class="fa-solid fa-location-dot"></i> Voir les biens
        </a>
        <a href="<?= BASE_URL; ?>/rdv.php" class="hero-btn outline">
          <i class="fa-regular fa-calendar-check"></i> Prendre RDV
        </a>
      </div>
    </div>
  </div>

  <div class="slide" style="background-image:url('<?= BASE_URL; ?>/assets/img/slide2.jpg');">
    <div class="slide-content">
      <span class="kicker">BTP &amp; CHANTIERS</span>
      <h1>Construire<br>en toute confiance</h1>
      <p>Clôtures · Villas · Rénovation · Suivi de chantier — des experts pour sécuriser vos investissements.</p>
      <div class="hero-actions">
        <a href="<?= BASE_URL; ?>/btp.php" class="hero-btn primary">
          <i class="fa-solid fa-helmet-safety"></i> Services BTP
        </a>
        <a href="<?= BASE_URL; ?>/contact.php" class="hero-btn outline">
          <i class="fa-solid fa-phone"></i> Parler à un expert
        </a>
      </div>
    </div>
  </div>

  <div class="slide" style="background-image:url('<?= BASE_URL; ?>/assets/img/slide3.jpg');">
    <div class="slide-content">
      <span class="kicker">DIASPORA &amp; INVESTISSEURS</span>
      <h1>Investissez<br>intelligemment</h1>
      <p>Assistance foncière · Financement · Montages financiers — des solutions adaptées à vos objectifs d'investissement.</p>
      <div class="hero-actions">
        <a href="<?= BASE_URL; ?>/financement.php" class="hero-btn primary">
          <i class="fa-solid fa-coins"></i> Solutions financement
        </a>
        <a href="<?= BASE_URL; ?>/diaspora.php" class="hero-btn outline">
          <i class="fa-solid fa-globe"></i> Espace Diaspora
        </a>
      </div>
    </div>
  </div>

  <div class="hero-prev" role="button" aria-label="Slide précédente">&#10094;</div>
  <div class="hero-next" role="button" aria-label="Slide suivante">&#10095;</div>
  <div class="slider-dots" aria-label="Pagination"></div>
</section>
<?php endif; ?>

<!-- SCRIPTS HEADER -->
<script>
// Slider
(function(){
  let index = 0;
  const slides = document.querySelectorAll(".hero-slider .slide");
  const dotsContainer = document.querySelector(".hero-slider .slider-dots");
  const btnNext = document.querySelector(".hero-slider .hero-next");
  const btnPrev = document.querySelector(".hero-slider .hero-prev");
  if(!slides.length || !dotsContainer) return;
  slides.forEach((_,i)=>{
    const dot=document.createElement("span");
    if(i===0) dot.classList.add("active");
    dot.addEventListener("click",()=>showSlide(i));
    dotsContainer.appendChild(dot);
  });
  const dots=dotsContainer.querySelectorAll("span");
  function showSlide(n){
    slides[index].classList.remove("active");
    dots[index].classList.remove("active");
    index=(n+slides.length)%slides.length;
    slides[index].classList.add("active");
    dots[index].classList.add("active");
  }
  let timer=setInterval(()=>showSlide(index+1),6000);
  function resetAuto(){ clearInterval(timer); timer=setInterval(()=>showSlide(index+1),6000); }
  if(btnNext) btnNext.addEventListener("click",()=>{showSlide(index+1);resetAuto();});
  if(btnPrev) btnPrev.addEventListener("click",()=>{showSlide(index-1);resetAuto();});
})();

// Mobile menu
(function(){
  const burger=document.getElementById("burger-btn");
  const menu=document.getElementById("mobile-menu");
  const overlay=document.getElementById("mobile-overlay");
  const close=document.getElementById("mobile-close");
  if(!burger||!menu||!overlay||!close) return;
  let opened=false;
  function openMenu(){
    opened=true;
    menu.style.right="0";
    overlay.style.display="block";
    menu.setAttribute("aria-hidden","false");
    document.body.style.overflow="hidden";
    burger.innerHTML='<i class="fa-solid fa-xmark" style="color:var(--gold)"></i>';
  }
  function closeMenu(){
    opened=false;
    menu.style.right="-100%";
    overlay.style.display="none";
    menu.setAttribute("aria-hidden","true");
    document.body.style.overflow="";
    burger.innerHTML='<i class="fa-solid fa-bars"></i>';
  }
  burger.addEventListener("click",()=>opened?closeMenu():openMenu());
  overlay.addEventListener("click",closeMenu);
  close.addEventListener("click",closeMenu);
  document.addEventListener("keydown",(e)=>{ if(e.key==="Escape"&&opened) closeMenu(); });
})();

// Sticky scroll effect — shrink header + hide strip on scroll
(function(){
  const h=document.getElementById("main-header");
  const strip=document.querySelector(".announcement-strip");
  if(!h) return;
  function check(){
    const s=window.scrollY>30;
    h.classList.toggle("scrolled",s);
    if(strip) strip.classList.toggle("hidden",s);
  }
  check();
  window.addEventListener("scroll",check,{passive:true});
})();
</script>

<main>
