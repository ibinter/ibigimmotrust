<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/tracker.php';

$currentPage = 'opportunites';
$pageTitle   = "Hub d'opportunités immobilières & BTP | IBIG IMMO TRUST";

include __DIR__ . '/includes/header.php';
?>

<style>

/* GLOBAL */

:root{
--red:#D4AF37;
--orange:#E8CC6A;
--dark:#0f172a;
--text:#475569;
--line:#e5e7eb;
--radius:20px;
--shadow:0 25px 70px rgba(0,0,0,.08);
}

body{
font-family:Inter,Arial,sans-serif;
color:var(--dark);
}

.container{
width:min(1200px,calc(100% - 30px));
margin:auto;
}

/* HERO */

.hero{
background:
linear-gradient(120deg,#060d1a,#A68920,#D4AF37,#E8CC6A);
color:white;
padding:160px 0;
text-align:center;
}

.hero h1{
color:#ffffff;
font-size:58px;
font-weight:900;
line-height:1.05;
}

.hero p{
color:rgba(255,255,255,0.92);
font-size:20px;
max-width:700px;
}

.hero-buttons{
margin-top:40px;
display:flex;
justify-content:center;
gap:20px;
flex-wrap:wrap;
}

.btn{
padding:18px 36px;
border-radius:14px;
font-weight:900;
font-size:18px;
text-decoration:none;
}

.btn-main{
background:white;
color:#A68920;
}

.btn-alt{
background:rgba(255,255,255,.2);
border:1px solid rgba(255,255,255,.3);
color:white;
}

/* STATS */

.stats{
background:#FAF7F0;
padding:60px 0;
}

.stats-grid{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:30px;
text-align:center;
}

.stat{
font-size:42px;
font-weight:900;
color:var(--red);
}

.stat-label{
font-size:16px;
color:var(--text);
}

/* SECTION */

.section{
padding:110px 0;
}

.section h2{
font-size:44px;
font-weight:900;
margin-bottom:20px;
}

.section p{
font-size:18px;
color:var(--text);
max-width:900px;
}

/* GRID */

.grid{
display:grid;
grid-template-columns:repeat(3,1fr);
gap:28px;
margin-top:40px;
}

.card{
background:white;
border-radius:var(--radius);
padding:36px;
border:1px solid var(--line);
box-shadow:var(--shadow);
transition:.3s;
}

.card:hover{
transform:translateY(-10px);
}

.card h3{
font-size:22px;
margin-bottom:10px;
}

.card p{
color:var(--text);
}

/* CTA */

.cta{
background:linear-gradient(135deg,#0A1628,#D4AF37,#E8CC6A);
color:white;
text-align:center;
padding:120px 0;
}

.cta h2{
font-size:50px;
margin-bottom:20px;
}

.cta p{
font-size:20px;
margin-bottom:30px;
}

.cta a{
background:white;
color:#A68920;
padding:18px 36px;
border-radius:12px;
font-weight:900;
font-size:18px;
text-decoration:none;
}

/* WHATSAPP */

.whatsapp{
position:fixed;
bottom:25px;
right:25px;
background:#25D366;
color:white;
padding:16px 22px;
border-radius:40px;
font-weight:800;
text-decoration:none;
box-shadow:0 10px 30px rgba(0,0,0,.2);
z-index:999;
}

/* RESPONSIVE */

@media(max-width:900px){

.hero h1{
text-shadow:0 8px 24px rgba(0,0,0,0.25);
}

.grid{
grid-template-columns:1fr;
}

.stats-grid{
grid-template-columns:1fr 1fr;
}

.section h2{
font-size:32px;
}

}

.hero-op{
background:linear-gradient(120deg,#0A1628,#A68920,#D4AF37,#E8CC6A);
padding:140px 0;
color:white;
}

.hero-grid{
display:grid;
grid-template-columns:1fr 1fr;
gap:60px;
align-items:center;
}

.hero-text h1{
font-size:58px;
font-weight:900;
line-height:1.05;
margin-bottom:20px;
}

.hero-text p{
font-size:20px;
opacity:.95;
max-width:600px;
}

.hero-buttons{
margin-top:30px;
display:flex;
gap:18px;
flex-wrap:wrap;
}

.btn-main{
background:#ffffff;
color:#A68920;
padding:16px 32px;
border-radius:12px;
font-weight:800;
}

.btn-outline{
border:1px solid rgba(255,255,255,0.35);
color:white;
padding:16px 32px;
border-radius:12px;
}

.hero-image img{
width:100%;
border-radius:20px;
box-shadow:0 25px 70px rgba(0,0,0,.25);
}

@media(max-width:900px){

.hero-grid{
grid-template-columns:1fr;
}

.hero-text h1{
font-size:36px;
}

}

.hero-op{
position:relative;
}

.hero-op::before{
content:"";
position:absolute;
inset:0;
background:rgba(0,0,0,0.15);
}

.hero-grid{
position:relative;
z-index:2;
}

.hero{
position:relative;
}

.hero::before{
content:"";
position:absolute;
inset:0;
background:rgba(0,0,0,0.15);
}

.hero .container{
position:relative;
z-index:2;
}

.cta h2{
color:#ffffff;
}

.cta p{
color:rgba(255,255,255,0.92);
}

.hero h1{
color:#ffffff !important;
text-shadow:0 8px 25px rgba(0,0,0,0.35);
}

.hero-op h1{
color:#ffffff !important;
}

.search-bar{
display:flex;
align-items:center;
gap:12px;
flex-wrap:nowrap;
}

.search-bar *{
white-space:nowrap;
}

.search-bar select,
.search-bar input{
min-width:120px;
max-width:160px;
}

.search-actions{
display:flex;
gap:10px;
margin-left:auto;
}

.search-actions button{
padding:10px 18px;
}

</style>



<!-- HERO -->

<section class="hero-op">
<div class="container hero-grid">

<div class="hero-text">

<h1>
Le Hub des Opportunités
Immobilières & BTP
en Côte d’Ivoire
</h1>

<p>
Maisons inachevées, terrains inexploités, immeubles à rénover,
projets immobiliers bloqués ou investisseurs recherchant projets.

IBIG IMMO TRUST identifie ces opportunités et les transforme
en projets immobiliers réels.
</p>

<div class="hero-buttons">

<a class="btn-main"
href="https://forms.intermark-business.com/ibigimmotrust">

Signaler une opportunité

</a>

<a class="btn-outline"
href="/contact.php">

Nous contacter

</a>

</div>

</div>


<div class="hero-image">

<img src="/assets/img/immeuble-opportunite.jpg"
alt="Opportunités immobilières IBIG IMMO TRUST">

</div>

</div>
</section>



<!-- STATS -->

<section class="stats">

<div class="container">

<div class="stats-grid">

<div>
<div class="stat">100+</div>
<div class="stat-label">Opportunités recherchées</div>
</div>

<div>
<div class="stat">20+</div>
<div class="stat-label">Zones immobilières ciblées</div>
</div>

<div>
<div class="stat">50+</div>
<div class="stat-label">Partenaires & investisseurs</div>
</div>

<div>
<div class="stat">∞</div>
<div class="stat-label">Potentiel immobilier</div>
</div>

</div>

</div>

</section>



<!-- OPPORTUNITES -->

<section class="section">

<div class="container">

<h2>
Opportunités que nous recherchons
</h2>

<p>
Nous recherchons activement des opportunités immobilières et BTP
à fort potentiel en Côte d’Ivoire.
</p>

<div class="grid">

<div class="card">
<h3>Maisons inachevées</h3>
<p>
Projets de construction arrêtés ou abandonnés avec potentiel de finition.
</p>
</div>

<div class="card">
<h3>Terrains stratégiques</h3>
<p>
Terrains urbains ou périurbains adaptés à des projets immobiliers.
</p>
</div>

<div class="card">
<h3>Immeubles à valoriser</h3>
<p>
Bâtiments nécessitant rénovation ou restructuration.
</p>
</div>

<div class="card">
<h3>Projets à financer</h3>
<p>
Promoteurs ou propriétaires recherchant investisseurs.
</p>
</div>

<div class="card">
<h3>Biens de la diaspora</h3>
<p>
Propriétaires vivant à l’étranger souhaitant vendre ou terminer leur projet.
</p>
</div>

<div class="card">
<h3>Opportunités BTP</h3>
<p>
Projets de construction, rénovation ou promotion immobilière.
</p>
</div>

</div>

</div>

</section>



<!-- CTA -->

<section class="cta">

<div class="container">

<h2>
Transformons les opportunités
en projets immobiliers
</h2>

<p>
Si vous connaissez une opportunité immobilière sérieuse,
vous pouvez la soumettre via notre formulaire sécurisé.
</p>

<a href="https://forms.intermark-business.com/ibigimmotrust">

SOUMETTRE UNE OPPORTUNITÉ

</a>

</div>

</section>



<a class="whatsapp"
href="https://wa.me/2250584437474">

📲 Signaler une opportunité

</a>



<?php include __DIR__ . '/includes/footer.php'; ?>