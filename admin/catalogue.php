<?php
require_once __DIR__ . '/includes/config.php';

$type         = $_GET['type']         ?? '';
$transaction  = $_GET['transaction']  ?? '';
$ville        = $_GET['ville']        ?? '';
$prix_min     = $_GET['prix_min']     ?? '';
$prix_max     = $_GET['prix_max']     ?? '';

$sql = "SELECT * FROM immo_biens WHERE visible = 1";
$params = [];

if ($type !== '') {
    $sql .= " AND type = ?";
    $params[] = $type;
}
if ($transaction !== '') {
    $sql .= " AND transaction = ?";
    $params[] = $transaction;
}
if ($ville !== '') {
    $sql .= " AND ville LIKE ?";
    $params[] = "%$ville%";
}
if ($prix_min !== '') {
    $sql .= " AND prix >= ?";
    $params[] = (int)str_replace(' ','',$prix_min);
}
if ($prix_max !== '') {
    $sql .= " AND prix <= ?";
    $params[] = (int)str_replace(' ','',$prix_max);
}

$sql .= " ORDER BY created_at DESC";

$st = $pdo->prepare($sql);
$st->execute($params);
$biens = $st->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = "Catalogue des biens - IBIG IMMO TRUST";
include __DIR__ . '/includes/header.php';
?>

<div class="container" style="padding:40px 0;max-width:1150px;">
  <h1 style="font-size:26px;font-weight:800;color:#003c96;margin-bottom:15px;">
    Nos biens immobiliers
  </h1>

  <!-- FILTRES -->
  <form method="get" style="background:#fff;padding:14px 16px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.04);margin-bottom:20px;">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:10px;">
      <div>
        <label>Type de bien</label>
        <select name="type">
          <option value="">Tous</option>
          <?php
          $types = ["Terrain","Maison","Appartement","Villa","Immeuble","Bureau","Local commercial"];
          foreach($types as $t){
            $sel = ($type === $t) ? 'selected' : '';
            echo "<option $sel>$t</option>";
          }
          ?>
        </select>
      </div>
      <div>
        <label>Transaction</label>
        <select name="transaction">
          <option value="">Toutes</option>
          <?php
          $transactions = ["A vendre","A louer","Réservé","Vendu","Loué","Archivé"];
          foreach($transactions as $tr){
            $sel = ($transaction === $tr) ? 'selected' : '';
            echo "<option $sel>$tr</option>";
          }
          ?>
        </select>
      </div>
      <div>
        <label>Ville</label>
        <input type="text" name="ville" value="<?= htmlspecialchars($ville) ?>" placeholder="Abidjan, Bouaké...">
      </div>
      <div>
        <label>Prix min (FCFA)</label>
        <input type="text" name="prix_min" value="<?= htmlspecialchars($prix_min) ?>">
      </div>
      <div>
        <label>Prix max (FCFA)</label>
        <input type="text" name="prix_max" value="<?= htmlspecialchars($prix_max) ?>">
      </div>
    </div>
    <div style="margin-top:12px;text-align:right;">
      <button type="submit" class="btn-main"> Filtrer</button>
    </div>
  </form>

  <!-- GRILLE DE BIENS -->
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;">
    <?php if(count($biens) === 0): ?>
      <p>Aucun bien ne correspond à votre recherche pour le moment.</p>
    <?php endif; ?>

    <?php foreach($biens as $b):
      $url = 'bien.php';
      if (!empty($b['slug'])) {
          $url .= '?slug='.urlencode($b['slug']);
      } else {
          $url .= '?id='.$b['id'];
      }
    ?>
      <a href="<?= $url ?>" style="text-decoration:none;color:#111827;">
        <div style="background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 3px 10px rgba(0,0,0,0.06);">
          <img src="<?= htmlspecialchars($b['image_principale']) ?>" style="width:100%;height:170px;object-fit:cover;">
          <div style="padding:12px;">
            <?php if($b['badge']): ?>
              <span style="display:inline-block;background:#e30613;color:#fff;font-size:11px;padding:3px 8px;border-radius:999px;margin-bottom:6px;">
                <?= htmlspecialchars($b['badge']) ?>
              </span>
            <?php endif; ?>
            <h2 style="font-size:16px;font-weight:700;margin:4px 0 6px;">
              <?= htmlspecialchars($b['titre']) ?>
            </h2>
            <p style="color:#e30613;font-weight:700;margin:0 0 4px;">
              <?= number_format($b['prix'],0,',',' ') ?> FCFA
            </p>
            <p style="font-size:13px;color:#6b7280;margin:0 0 4px;">
              <?= htmlspecialchars(trim(($b['ville'] ?? '').' '.($b['quartier'] ?? ''))) ?>
            </p>
            <p style="font-size:12px;color:#6b7280;margin:0;">
              <?= htmlspecialchars($b['type']) ?> · <?= htmlspecialchars($b['transaction']) ?>
            </p>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
