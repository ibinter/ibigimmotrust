<?php
require_once '../includes/config.php';
$biens = $pdo->query("SELECT * FROM immo_biens ORDER BY id DESC")->fetchAll();
?>
<h2>Biens Immobiliers</h2>

<a href="biens_add.php">➕ Ajouter un bien</a>

<table border="1" cellpadding="8">
<tr>
    <th>ID</th>
    <th>Titre</th>
    <th>Type</th>
    <th>Transaction</th>
    <th>Zone</th>
    <th>Prix</th>
    <th>Action</th>
</tr>

<?php foreach($biens as $b): ?>
<tr>
    <td><?= $b["id"] ?></td>
    <td><?= $b["titre"] ?></td>
    <td><?= $b["type"] ?></td>
    <td><?= $b["transaction"] ?></td>
    <td><?= $b["zone"] ?></td>
    <td><?= $b["prix"] ?></td>
    <td>
        <a href="biens_edit.php?id=<?= $b["id"] ?>">Modifier</a> |
        <a href="biens_delete.php?id=<?= $b["id"] ?>" onclick="return confirm('Supprimer ?')">Supprimer</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
