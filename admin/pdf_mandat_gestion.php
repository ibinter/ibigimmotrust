<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/../includes/config.php';

if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Mandat introuvable");
}

// Charger le bien
$stmt = $pdo->prepare("SELECT * FROM immo_biens WHERE id = ?");
$stmt->execute([$id]);
$bien = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bien) {
    die("Bien non trouvé");
}

// Forcer un affichage imprimable
header("Content-Type: text/html; charset=UTF-8");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Mandat Gestion – IBIG IMMO TRUST</title>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 40px;
    font-size: 14px;
    color: #000;
}
h1, h2 {
    text-align: center;
    margin-bottom: 5px;
}
.section {
    margin-top: 25px;
}
p {
    line-height: 1.6;
    text-align: justify;
}
.table-info {
    width: 100%;
    margin-top: 10px;
    border-collapse: collapse;
}
.table-info td {
    padding: 8px 5px;
}
.signature {
    margin-top: 60px;
    display: flex;
    justify-content: space-between;
}
.signature div {
    width: 45%;
    text-align: center;
}
</style>

<script>
window.onload = function(){
    window.print();
};
</script>

</head>
<body>

<h1>MANDAT DE GESTION LOCATIVE</h1>
<h2>IBIG IMMO TRUST</h2>

<div class="section">
    <h3>1. Informations sur le bien</h3>
    <table class="table-info">
        <tr><td><strong>Titre :</strong></td><td><?= htmlspecialchars($bien['titre']) ?></td></tr>
        <tr><td><strong>Adresse :</strong></td><td><?= htmlspecialchars($bien['ville']) ?>, <?= htmlspecialchars($bien['quartier']) ?></td></tr>
        <tr><td><strong>Type :</strong></td><td><?= htmlspecialchars($bien['type']) ?></td></tr>
        <tr><td><strong>Statut :</strong></td><td><?= htmlspecialchars($bien['statut']) ?></td></tr>
        <tr><td><strong>Prix proposé :</strong></td><td><?= number_format($bien['prix'],0,',',' ') ?> FCFA</td></tr>
    </table>
</div>

<div class="section">
    <h3>2. Objet du mandat</h3>
    <p>
        Le présent mandat a pour objet la gestion locative du bien décrit ci-dessus par 
        <strong>IBIG IMMO TRUST</strong>, comprenant : gestion des locataires, perception des loyers, 
        entretien, suivi administratif, reporting digital et optimisation de la rentabilité.
    </p>
</div>

<div class="section">
    <h3>3. Durée du mandat</h3>
    <p>
        Le présent mandat est conclu pour une durée initiale de <strong>12 mois renouvelables</strong>, sauf dénonciation
        par l'une des parties avec un préavis de 30 jours.
    </p>
</div>

<div class="section">
    <h3>4. Rémunération</h3>
    <p>
        Les honoraires de gestion locative sont fixés à 
        <strong>15% à 20% du loyer mensuel</strong>, selon les conditions convenues avec le propriétaire.
        Tout frais supplémentaire fera l’objet d’un accord explicite.
    </p>
</div>

<div class="section">
    <h3>5. Engagements IBIG IMMO TRUST</h3>
    <p>
        IBIG IMMO TRUST s’engage à gérer le bien conformément aux normes professionnelles :
        sélection rigoureuse des locataires, encaissements sécurisés, gestion administrative,
        interventions techniques, reporting en ligne, et assistance permanente.
    </p>
</div>

<div class="section">
    <h3>6. Signatures</h3>
    <div class="signature">
        <div>
            <strong>Le Mandant (Propriétaire)</strong><br><br><br>
            Signature :
        </div>
        <div>
            <strong>IBIG IMMO TRUST</strong><br><br><br>
            Signature :
        </div>
    </div>
</div>

</body>
</html>
