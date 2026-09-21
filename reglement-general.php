<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

$pageTitle = "Règlement Général d’Utilisation du Site – IBIG IMMO TRUST";
$currentPage = 'reglement';

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

<h1 class="legal-title">Règlement Général d’Utilisation du Site</h1>
<div class="legal-updated">
    Dernière mise à jour : <?= date('d/m/Y') ?>
</div>

<p>
Le présent règlement définit les règles qui encadrent l’utilisation du site  
<strong>IBIG IMMO TRUST</strong>, ses formulaires, ses modules de prise de rendez-vous,  
ainsi que l’ensemble de ses outils digitaux.
</p>

<h2>1. Accès aux services</h2>
<p>
L’accès au site est gratuit. Certains services peuvent être soumis à validation  
humaine, à vérification manuelle ou à des conditions contractuelles spécifiques.
</p>

<h2>2. Usage autorisé</h2>
<ul>
    <li>Demander une estimation d’un bien</li>
    <li>Décrire un projet immobilier ou BTP</li>
    <li>Prendre rendez-vous en ligne</li>
    <li>Soumettre une demande de gestion locative ou de chantier</li>
    <li>Demander une vérification foncière</li>
</ul>

<h2>3. Usage interdit</h2>
<ul>
    <li>Fournir de fausses informations ou usurper une identité</li>
    <li>Réaliser des envois massifs ou spams via les formulaires</li>
    <li>Tenter d’accéder à l’administration du site sans autorisation</li>
    <li>Contourner les dispositifs de sécurité et protections du site</li>
</ul>

<h2>4. Suspension d’accès</h2>
<p>
IBIG IMMO TRUST se réserve le droit de suspendre l’accès à tout utilisateur  
dont les actions mettent en danger la sécurité du site, perturbent les services  
ou contreviennent aux règles énoncées dans le présent règlement.
</p>

<h2>5. Signalement & assistance</h2>
<p>
Pour tout signalement, question ou besoin d’assistance concernant le règlement :  
<br>
<strong>contact@ibigimmotrust.com</strong>
</p>

</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
