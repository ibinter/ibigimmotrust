<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

$pageTitle = "Mentions Légales – IBIG IMMO TRUST";
$currentPage = 'legal';
include __DIR__ . '/includes/header.php';
?>

<style>
.legal-wrapper{
    max-width:900px;
    margin:50px auto;
    padding:30px;
    background:white;
    border-radius:12px;
    box-shadow:0 4px 14px rgba(0,0,0,0.06);
    color:#334155;
    font-size:16px;
    line-height:1.7;
}
.legal-title{
    font-size:32px;
    font-weight:800;
    color:#003c96;
    margin-bottom:10px;
}
.legal-updated{
    font-size:14px;
    color:#64748b;
    margin-bottom:25px;
}
.legal-wrapper h2{
    font-size:22px;
    color:#e30613;
    margin-top:30px;
    margin-bottom:10px;
    font-weight:700;
}
.legal-wrapper p, 
.legal-wrapper ul{
    margin-bottom:18px;
}
.legal-wrapper ul{
    padding-left:20px;
}
</style>

<section class="legal-wrapper">

<h1 class="legal-title">Mentions Légales</h1>
<div class="legal-updated">Dernière mise à jour : <?= date('d/m/Y') ?></div>

<p>
Le présent site est édité par <strong>IBIG IMMO TRUST</strong>, une marque de  
<strong>INTERMARK BUSINESS INTERNATIONAL GROUP SARL</strong>, entreprise spécialisée  
dans la formation professionnelle, l’immobilier, le BTP, la gestion locative et les solutions digitales.
</p>

<h2>1. Informations sur l’éditeur</h2>
<p>
<strong>Dénomination :</strong> INTERMARK BUSINESS INTERNATIONAL GROUP SARL<br>
<strong>Sigle :</strong> IBIG SARL / IBIG IMMO TRUST<br>
<strong>RCCM :</strong> N°CI-ABJ-03-2023-B13-05718<br>
<strong>NCC :</strong> 2302502 V<br>
<strong>Siège social :</strong> Abidjan, Côte d’Ivoire<br>
<strong>Email :</strong> contact@ibigimmotrust.com<br>
<strong>Téléphone :</strong> +225 07 78 88 25 92
</p>

<h2>2. Hébergement</h2>
<p>
Le site est hébergé par : <strong>LWS – Ligne Web Services</strong>.
</p>

<h2>3. Propriété intellectuelle</h2>
<p>
Tous les textes, images, logos, vidéos et contenus présents sur le site sont protégés  
et appartiennent exclusivement à IBIG IMMO TRUST.
</p>

<h2>4. Responsabilité</h2>
<p>
IBIG IMMO TRUST ne peut être tenu responsable en cas d’indisponibilité temporaire  
du site, d’erreurs techniques, de perte de données ou d’utilisation non conforme.
</p>

<h2>5. Contact</h2>
<p>
Pour toute question relative aux mentions légales, contactez :  
<strong>contact@ibigimmotrust.com</strong>
</p>

</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
