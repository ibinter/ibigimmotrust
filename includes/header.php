<?php if (!isset($pageTitle)) { $pageTitle = SITE_NAME; } ?>
<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="assets/css/front.css?v=1">
<head>

<meta charset="UTF-8">
<title><?php echo e($pageTitle); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

/* ===== HEADER FIX COMPACT ===== */
.header-flex {
    max-width: 1300px;
    margin: auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 25px;
    padding: 0 15px;
}

.nav-premium {
    display: flex;
    align-items: center;
    gap: 22px;
    flex-wrap: nowrap;
}

.nav-premium a {
    font-size: 15px;
    color: #001f54;
    font-weight: 600;
    text-decoration: none;
    padding: 4px 0;
    white-space: nowrap;
}

.nav-premium a.active {
    color: #e30613;
    border-bottom: 2px solid #e30613;
}

.nav-right {
    display: flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap;
}

.nav-btn {
    padding: 5px 12px;
    border: 1px solid #003c96;
    border-radius: 6px;
    color: #003c96;
    font-size: 14px;
    font-weight: 600;
}

.nav-btn-primary {
    padding: 6px 14px;
    background: #003c96;
    color: #fff !important;
    font-size: 14px;
    border-radius: 6px;
    font-weight: 600;
}

header, .header-flex, .nav-premium, .nav-right {
    overflow: hidden;
}

html, body {
    overflow-x: hidden !important;
}

/* ==========================================
    TOPBAR
========================================== */
.topbar-marquee {
  width: 100%;
  background: linear-gradient(90deg,#ff6a00,#e30613);
  color: #fff;
  padding: 7px 0;
  font-size: 13px;
  font-weight: 600;
  text-align: center;
  letter-spacing: 0.4px;
}

/* ==========================================
    HEADER
========================================== */
.header-premium{
  background:#fff;
  width:100%;
  padding:14px 0;
  box-shadow:0 3px 10px rgba(0,0,0,0.05);
  position:sticky;
  top:0;
  z-index:9999;
}

.header-flex{
  max-width:1300px;
  margin:auto;
  display:flex;
  justify-content:space-between;
  align-items:center;
  padding:0 20px;
}

.logo-img{
  height:60px;
  width:auto;
}

/* MENU DESKTOP */
.nav-premium{
  display:flex;
  align-items:center;
  gap:30px;
}

.nav-premium a{
  color:#001f54;
  font-size:16px;
  text-decoration:none;
  font-weight:500;
  padding-bottom:3px;
  border-bottom:2px solid transparent;
  transition:0.3s;
}

.nav-premium a:hover{
  color:#003c96;
  border-bottom:2px solid #003c96;
}

.nav-premium a.active{
  color:#e30613;
  border-bottom:2px solid #e30613;
}

.nav-btn {
  padding:7px 16px;
  border:1px solid #003c96;
  color:#003c96;
  border-radius:8px;
}

.nav-btn-primary {
  padding:8px 18px;
  background:#003c96;
  color:#fff !important;
  font-weight:600;
  border-radius:10px;
}

/* ==========================================
    MENU MOBILE
========================================== */
.nav-toggle{
  display:none;
  font-size:30px;
  cursor:pointer;
  color:#003c96;
  z-index:99999;
}

#mobile-menu{
  position:fixed;
  top:0;
  right:-100%;
  width:75%;
  height:100vh;
  background:#fff;
  padding:90px 25px;
  display:flex;
  flex-direction:column;
  gap:24px;
  box-shadow:-4px 0 30px rgba(0,0,0,0.25);
  transition:0.35s ease;
  z-index:99998;
}

#mobile-menu a{
  font-size:20px;
  color:#001f54;
  font-weight:500;
  text-decoration:none;
}

#mobile-menu .nav-btn-primary{
  padding:14px 20px;
  text-align:center;
  font-size:18px;
  background:#003c96;
  color:#fff !important;
}

/* ==========================================
    SLIDER HERO
========================================== */
.hero-slider{
  width:100%;
  height:500px;
  position:relative;
  overflow:hidden;
}

.slide{
  width:100%;
  height:100%;
  position:absolute;
  top:0;
  left:0;
  background-size:cover;
  background-position:center;
  opacity:0;
  transition:opacity 1s ease;
}

.slide.active{
  opacity:1;
}

.slide-content{
  position:absolute;
  top:50%;
  left:8%;
  transform:translateY(-50%);
  color:#fff;
  text-shadow:0 4px 10px rgba(0,0,0,0.4);
}

.slide-content h1{
  font-size:48px;
  font-weight:800;
  margin-bottom:12px;
}

.slide-content p{
  font-size:20px;
  margin-bottom:25px;
}

.btn-primary{
  display:inline-block;
  padding:12px 25px;
  background:#003c96;
  color:#fff;
  font-size:18px;
  border-radius:10px;
  text-decoration:none;
}

/* Boutons slider */
.prev, .next{
  position:absolute;
  top:50%;
  transform:translateY(-50%);
  background:rgba(255,255,255,0.8);
  color:#003c96;
  padding:12px 18px;
  border-radius:8px;
  cursor:pointer;
  font-size:26px;
  z-index:50;
}

.prev{ left:20px; }
.next{ right:20px; }

/* Dots */
.slider-dots{
  position:absolute;
  bottom:20px;
  left:50%;
  transform:translateX(-50%);
  display:flex;
  gap:10px;
}

.slider-dots span{
  width:12px;
  height:12px;
  background:#fff;
  opacity:.5;
  border-radius:50%;
  cursor:pointer;
}

.slider-dots .active{
  opacity:1;
  transform:scale(1.2);
}

/* ============================================================
    ENCART PREMIUM – NOS BIENS DISPONIBLES
============================================================ */
.encart-biens {
  background: linear-gradient(135deg, #ff6a00, #e30613);
  padding: 60px 0;
  border-radius: 0;
  margin-top: 40px;
}

.encart-biens .section-title,
.encart-biens .section-intro {
  color: #fff !important;
}

.encart-biens .bien-card {
  background: #ffffff;
  border: none;
  box-shadow: 0 6px 18px rgba(0,0,0,0.12);
}

.encart-biens .bien-statut {
  border-radius: 20px;
}

/* ==========================================
   RESPONSIVE
========================================== */
@media(max-width:900px){

  /* HEADER MOBILE */
  .header-premium{
    background:#fff;
    padding:6px 0;
    height:68px;
    box-shadow:0 2px 6px rgba(0,0,0,0.08);
    position:sticky;
    top:0;
    z-index:1000;
  }

  .header-flex{
    height:68px;
    padding:0 12px;
    display:flex;
    align-items:center;
    justify-content:space-between;
  }

  .logo-img{
    height:44px;
    width:auto;
  }

  /* On cache le menu horizontal, on garde uniquement le burger */
  .nav-premium,
  .nav-right{
    display:none !important;
  }

  .nav-toggle{
    display:flex !important;
    align-items:center;
    justify-content:center;
  }

  .nav-toggle i{
    font-size:30px;
    color:#003c96;
  }

  /* SLIDER MOBILE */
  .hero-slider{
    height:420px;
    margin-top:0; /* important : pas de marge bizarre */
  }

  .slide-content{
    top:60%;               /* on descend le texte */
    left:7%;
    right:7%;
    transform:translateY(-50%);
    text-align:center;
  }

  .slide-content h1{
    font-size:30px;
    line-height:36px;
    margin-bottom:10px;
  }

  .slide-content p{
    font-size:17px;
    margin-bottom:16px;
  }

  .slide-content .btn-primary{
    font-size:16px;
    padding:11px 22px;
    border-radius:8px;
  }

  .prev, .next{
    padding:8px 12px;
    font-size:22px;
  }
}

/* ==========================================
   BOUTON WHATSAPP FLOTTANT + BULLE D'AIDE
========================================== */

/* Conteneur du bouton + bulle */
.whatsapp-help-box {
    position: fixed;
    bottom: 22px;
    right: 22px;
    display: flex;
    align-items: center;
    gap: 12px;
    z-index: 999999;
}

/* Bulle de texte */
.whatsapp-help-text {
    background: #003c96;
    color: #fff;
    padding: 10px 16px;
    border-radius: 12px;
    font-size: 15px;
    font-weight: 600;
    box-shadow: 0 6px 18px rgba(0,0,0,0.25);
    animation: floatHelp 1.6s infinite ease-in-out;
    white-space: nowrap;
}

/* Animation légère */
@keyframes floatHelp {
    0% { transform: translateY(0); }
    50% { transform: translateY(-4px); }
    100% { transform: translateY(0); }
}

/* Bouton rond WhatsApp */
.whatsapp-float {
    background: #25D366;
    color: #fff;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 32px;
    text-decoration: none;
    box-shadow: 0 6px 18px rgba(0,0,0,0.25);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.whatsapp-float:hover {
    transform: scale(1.12);
    box-shadow: 0 8px 25px rgba(0,0,0,0.35);
}

</style>
</head>

<body>

<!--  TOPBAR -->
<div class="topbar-marquee">
  <marquee scrollamount="4">
    BIENVENUE CHEZ IBIG IMMO TRUST — IMMOBILIER • BTP • FINANCEMENT • GESTION LOCATIVE
  </marquee>
</div>

<!--  HEADER -->
<header class="header-premium">
  <div class="header-flex">

    <a href="<?php echo BASE_URL; ?>/">
      <img src="<?php echo BASE_URL; ?>/assets/img/logo.png" class="logo-img">
    </a>

    <nav class="nav-premium">
      <a href="<?php echo BASE_URL; ?>/" class="<?php echo ($currentPage=='home'?'active':''); ?>">Accueil</a>
      <a href="<?php echo BASE_URL; ?>/immobilier.php" class="<?php echo ($currentPage=='immobilier'?'active':''); ?>">Immobilier</a>
      <a href="<?php echo BASE_URL; ?>/btp.php" class="<?php echo ($currentPage=='btp'?'active':''); ?>">BTP</a>
      <a href="<?php echo BASE_URL; ?>/tous_les_biens.php" class="<?php echo ($currentPage=='biens'?'active':''); ?>">Nos Biens Disponibles</a>
      <a href="<?php echo BASE_URL; ?>/financement.php" class="<?php echo ($currentPage=='financement'?'active':''); ?>">Financement</a>
      <a href="<?php echo BASE_URL; ?>/faq.php" class="<?php echo ($currentPage=='faq'?'active':''); ?>">FAQ</a>
      <a href="<?php echo BASE_URL; ?>/formulaires.php" class="<?php echo ($currentPage=='formulaires'?'active':''); ?>">Formulaires</a>
</nav>

<div class="nav-right">
      <a href="<?php echo BASE_URL; ?>/contact.php" class="nav-btn">Contact</a>
      <a href="<?php echo BASE_URL; ?>/rdv.php" class="nav-btn-primary">Prendre RDV</a>
</div>

    <div class="nav-toggle" id="burger-btn">
      <i class="fa-solid fa-bars"></i>
    </div>

  </div>
</header>

<!--  MENU MOBILE -->
<div id="mobile-menu">
  <a href="<?php echo BASE_URL; ?>/">Accueil</a>
  <a href="<?php echo BASE_URL; ?>/immobilier.php">Immobilier</a>
  <a href="<?php echo BASE_URL; ?>/btp.php">BTP</a>
  <a href="<?php echo BASE_URL; ?>/financement.php">Financement</a>
  <a href="<?php echo BASE_URL; ?>/faq.php">FAQ</a>
  <a href="<?php echo BASE_URL; ?>/formulaires.php">Formulaires</a>
  <a href="<?php echo BASE_URL; ?>/contact.php">Contact</a>
  <a href="<?php echo BASE_URL; ?>/rdv.php" class="nav-btn-primary">Prendre RDV</a>
</div>

<!--  MENU MOBILE JS -->
<script>
const burger = document.getElementById("burger-btn");
const menu = document.getElementById("mobile-menu");
let isOpen = false;

burger.onclick = function(){
  isOpen = !isOpen;
  menu.style.right = isOpen ? "0" : "-100%";
  burger.innerHTML = isOpen 
    ? '<i class="fa-solid fa-times"></i>'
    : '<i class="fa-solid fa-bars"></i>';
};
</script>


<!-- ======================================
      SLIDER HERO PREMIUM
====================================== -->
<section class="hero-slider">

  <div class="slide active" style="background-image:url('<?php echo BASE_URL; ?>/assets/img/slide1.jpg');">
    <div class="slide-content">
      <h1>Votre patrimoine, notre expertise</h1>
      <p>Immobilier • BTP • Gestion Locative • Financement</p>
      <a href="<?php echo BASE_URL; ?>/contact.php" class="btn-primary">Parler à un Conseiller</a>
    </div>
  </div>

  <div class="slide" style="background-image:url('<?php echo BASE_URL; ?>/assets/img/slide2.jpg');">
    <div class="slide-content">
      <h1>Construire en toute confiance</h1>
      <p>BTP • Rénovation • Suivi de chantier</p>
      <a href="<?php echo BASE_URL; ?>/btp.php" class="btn-primary">Nos Services BTP</a>
    </div>
  </div>

  <div class="slide" style="background-image:url('<?php echo BASE_URL; ?>/assets/img/slide3.jpg');">
    <div class="slide-content">
      <h1>Investissez intelligemment</h1>
      <p>Assistance foncière • Recherche de biens • Financement</p>
      <a href="<?php echo BASE_URL; ?>/financement.php" class="btn-primary">Solutions Financement</a>
    </div>
  </div>

  <div class="prev">&#10094;</div>
  <div class="next">&#10095;</div>
  <div class="slider-dots"></div>

</section>

<!-- SLIDER JS -->
<script>
document.addEventListener("DOMContentLoaded", function(){

  let index = 0;
  const slides = document.querySelectorAll(".slide");
  const dotsContainer = document.querySelector(".slider-dots");

  slides.forEach((s,i)=>{
    const dot = document.createElement("span");
    if(i===0) dot.classList.add("active");
    dot.onclick = () => showSlide(i);
    dotsContainer.appendChild(dot);
  });

  const dots = document.querySelectorAll(".slider-dots span");

  function showSlide(n){
    slides[index].classList.remove("active");
    dots[index].classList.remove("active");
    index = (n + slides.length) % slides.length;
    slides[index].classList.add("active");
    dots[index].classList.add("active");
  }

  setInterval(()=>{ showSlide(index+1); }, 6000);

  document.querySelector(".next").onclick = ()=> showSlide(index+1);
  document.querySelector(".prev").onclick = ()=> showSlide(index-1);

});
</script>

<main>