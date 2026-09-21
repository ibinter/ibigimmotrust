<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

$pageTitle = "Conditions Générales d’Utilisation (CGU) – IBIG IMMO TRUST";
$currentPage = 'cgu';

include __DIR__ . '/includes/header.php';
?>

<style>
.legal-wrapper{
    max-width:900px;
    margin:60px auto;
    padding:20px;
    background:white;
    border-radius:14px;
    box-shadow:0 3px 14px rgba(0,0,0,0.08);
}
.legal-title{
    font-size:30px;
    font-weight:800;
    color:#003c96;
    margin-bottom:10px;
}
.legal-updated{
    font-size:13px;
    color:#6b7280;
    margin-bottom:20px;
}
.legal-wrapper h2{
    font-size:22px;
    font-weight:700;
    color:#e30613;
    margin-top:30px;
}
.legal-wrapper p,
.legal-wrapper ul li{
    font-size:15px;
    color:#374151;
    line-height:1.6;
}
.legal-wrapper ul{
    padding-left:18px;
    margin-top:8px;
}
@media(max-width:768px){
    .legal-wrapper{
        margin:30px 15px;
        padding:18px;
    }
}
</style>

<section class="legal-wrapper">

<h1 class="legal-title">Conditions Générales d’Utilisation (CGU)</h1>
<div class="legal-updated">Dernière mise à jour : <?= date('d/m/Y') ?></div>

<p>
Les présentes Conditions Générales d’Utilisation encadrent l’accès et l’utilisation du site  
<strong>IBIG IMMO TRUST</strong> et de l’ensemble de ses services digitaux.
</p>

<h2>1. Objet du site</h2>
<p>
Le site permet d’accéder aux services suivants :
gestion locative, estimation, BTP, chantiers inachevés, assistance foncière, prise de rendez-vous,
demandes de devis et mise en relation.
</p>

<h2>2. Acceptation des CGU</h2>
<p>
Toute utilisation du site implique votre acceptation pleine et entière des présentes CGU.
</p>

<h2>3. Accès au site</h2>
<p>
Le site est accessible 24h/24 et 7j/7, sauf opérations de maintenance ou cas de force majeure.
</p>

<h2>4. Obligations de l’utilisateur</h2>
<ul>
    <li>Fournir des informations exactes et vérifiables</li>
    <li>Ne pas usurper l’identité d’un tiers</li>
    <li>Ne pas perturber le fonctionnement du site ou tenter d’en contourner la sécurité</li>
    <li>Ne pas soumettre de messages abusifs, frauduleux ou contraires à la loi</li>
</ul>

<h2>5. Données personnelles</h2>
<p>
Pour toute information relative au traitement des données personnelles, consultez notre  
<a href="politique-confidentialite.php">Politique de Confidentialité</a>.
</p>

<h2>6. Modifications des CGU</h2>
<p>
IBIG IMMO TRUST se réserve le droit de modifier les présentes CGU à tout moment afin
d’adapter le site à ses évolutions ou à la réglementation.
</p>

<h2>7. Contact</h2>
<p>
Pour toute question relative aux CGU :  
<strong>contact@ibigimmotrust.com</strong>
</p>

</section>

<?php include __DIR__ . '/includes/footer.php'; ?>