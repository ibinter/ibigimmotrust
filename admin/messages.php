<?php
require_once __DIR__ . '/../includes/config.php';
session_start();

// Protection admin
if (!isset($_SESSION['admin'])) {
    die("Accès refusé");
}

// Suppression d’un message
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    $del = $pdo->prepare("DELETE FROM contact_messages WHERE id=?");
    $del->execute([$id]);
    header("Location: messages.php?deleted=1");
    exit;
}

// Récupération messages
$messages = $pdo->query("
    SELECT id, name, phone, email, subject, message, 
           COALESCE(ip_address, '—') AS ip_address,
           created_at
    FROM contact_messages
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Messages reçus - IBIG IMMO TRUST</title>

    <?php include __DIR__ . '/menu.php'; ?>

    <style>
        body{
            font-family:system-ui, sans-serif;
            background:#f4f6fb;
            padding:20px;
        }
        h2{ 
            margin:20px 0; 
            color:#003c96; 
            font-size:22px;
        }

        table{
            width:100%;
            border-collapse:collapse;
            background:white;
            border-radius:10px;
            overflow:hidden;
            box-shadow:0 2px 5px rgba(0,0,0,0.08);
        }
        th, td{
            padding:12px;
            border-bottom:1px solid #eee;
            font-size:14px;
            vertical-align:top;
        }
        th{
            background:#003c96;
            color:white;
            font-weight:600;
            text-align:left;
        }
        tr:hover{ background:#f0f4ff; }

        .msg{
            background:#fafafa;
            padding:8px;
            border-radius:6px;
            font-size:13px;
            white-space:pre-line;
        }
        .alert{
            background:#d1fae5;
            border-left:5px solid #10b981;
            padding:10px;
            border-radius:8px;
            margin-bottom:20px;
        }
        .btn-del{
            background:#e30613;
            padding:6px 10px;
            font-size:12px;
            border-radius:6px;
            color:white;
            text-decoration:none;
        }
        .btn-del:hover{
            background:#b90410;
        }
    </style>
</head>
<body>

<h2> Messages reçus</h2>

<?php if (!empty($_GET['deleted'])): ?>
<div class="alert">Message supprimé avec succès.</div>
<?php endif; ?>

<table>
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Téléphone</th>
    <th>Email</th>
    <th>Objet</th>
    <th>Message</th>
    <th>IP</th>
    <th>Date</th>
    <th>Action</th>
</tr>

<?php if (empty($messages)): ?>
<tr>
    <td colspan="9" style="text-align:center; padding:15px; color:#6b7280;">
        Aucun message pour le moment.
    </td>
</tr>
<?php endif; ?>

<?php foreach($messages as $m): ?>
<tr>
    <td><?= $m['id'] ?></td>
    <td><?= htmlspecialchars($m['name']) ?></td>
    <td><?= htmlspecialchars($m['phone']) ?></td>
    <td><?= htmlspecialchars($m['email']) ?></td>
    <td><?= htmlspecialchars($m['subject']) ?></td>
    <td class="msg"><?= nl2br(htmlspecialchars($m['message'])) ?></td>
    <td><?= htmlspecialchars($m['ip_address']) ?></td>
    <td><?= $m['created_at'] ?></td>
    <td>
        <a href="messages.php?delete=<?= $m['id'] ?>"
           class="btn-del"
           onclick="return confirm('Supprimer ce message ?');">
           Supprimer
        </a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
