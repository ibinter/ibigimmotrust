<?php
require_once __DIR__ . '/includes/config.php';
include __DIR__ . '/includes/tracker.php';

$pageTitle = "Politique de Confidentialité – IBIG IMMO TRUST";
$currentPage = 'confidentialite';

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

<h1 class="legal-title">Politique de Confidentialité</h1>
<div class="legal-updated">Dernière mise à jour : <?= date('d/m/Y') ?></div>

<p>
Cette politique explique comment <strong>IBIG IMMO TRUST</strong> collecte, traite et protège vos
données personnelles, conformément aux bonnes pratiques internationales et au RGPD.
</p>

<h2>1. Données collectées</h2>
<p>Nous collectons différentes catégories de données :</p>
<ul>
    <li>Nom et prénom</li>
    <li>Adresse email</li>
    <li>Numéro de téléphone</li>
    <li>Ville / localisation</li>
    <li>Messages, demandes, RDV, formulaires, leads</li>
    <li>Informations immobilières ou techniques liées à votre projet</li>
    <li>Données de navigation (pages vues, clics, device, navigateur…)</li>
</ul>

<h2>2. Finalités de la collecte</h2>
<ul>
    <li>Répondre à vos demandes (immobilier, BTP, gestion locative…)</li>
    <li>Prendre des rendez-vous ou vous recontacter</li>
    <li>Analyser les performances du site</li>
    <li>Améliorer l’expérience utilisateur</li>
    <li>Assurer la sécurité du site et prévenir les abus</li>
</ul>

<h2>3. Conservation des données</h2>
<p>
Les données sont conservées pendant une durée maximale de  
<strong>36 mois</strong>, sauf obligations légales contraires.
</p>

<h2>4. Sécurité et confidentialité</h2>
<p>
Nous appliquons des mesures strictes de sécurité :
</p>
<ul>
    <li>Bases de données sécurisées hébergées en Europe</li>
    <li>Chiffrement SSL/TLS</li>
    <li>Firewalls applicatifs et protection anti-intrusion</li>
    <li>Logs internes et anti-spam</li>
    <li>Accès restreint aux équipes autorisées</li>
</ul>

<h2>5. Vos droits</h2>
<p>Vous disposez des droits suivants :</p>
<ul>
    <li>Droit d’accès</li>
    <li>Droit de rectification</li>
    <li>Droit de suppression</li>
    <li>Droit d’opposition</li>
    <li>Droit de limitation</li>
</ul>

<h2>6. Cookies & tracking</h2>
<p>
Nous utilisons des cookies internes pour mesurer les performances, analyser le trafic et
sécuriser le site. Aucun cookie publicitaire tiers n’est utilisé.
</p>

<h2>7. Partage des données</h2>
<p>
Vos données ne sont jamais revendues.  
Elles peuvent être partagées uniquement avec :
</p>
<ul>
    <li>Nos équipes internes</li>
    <li>Nos prestataires techniques (hébergement, sécurité, SMS…)</li>
    <li>Services partenaires pour la gestion immobilière ou BTP (si vous l’acceptez)</li>
</ul>

<h2>8. Contact</h2>
<p>
Pour toute demande relative à vos données personnelles :  
<strong>contact@ibigimmotrust.com</strong>
</p>

</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
