<?php
require 'config.php';

// Vetëm adminët
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    die("Akses i ndaluar.");
}

$success = "";

// SHTO NJOFTIM
if (isset($_POST['create_notification'])) {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);
    $user_id = $_POST['user_id'] === "all" ? null : intval($_POST['user_id']);

    if ($title && $message) {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $title, $message]);
        $success = "Njoftimi u dërgua me sukses!";
    }
}

// Fshi njoftim
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $pdo->prepare("DELETE FROM notifications WHERE id = ?")->execute([$id]);
    header("Location: admin_notifications.php");
    exit;
}

// Merr listën e userëve për dropdown
$users = $pdo->query("SELECT id, full_name FROM users ORDER BY full_name ASC")->fetchAll();

// Merr njoftimet
$notifications = $pdo->query("
    SELECT notifications.*, users.full_name 
    FROM notifications
    LEFT JOIN users ON notifications.user_id = users.id
    ORDER BY created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<title>Njoftimet | Admin</title>
<link rel="stylesheet" href="style.css">
<style>
body { padding:120px 30px; color:white; }
.box { background:#1a1a1a; padding:25px; border-radius:10px; max-width:700px; margin:auto; }
input, textarea, select {
    width:100%; padding:12px; margin-top:10px; border-radius:6px;
    border:none; background:#222; color:white;
}
table { width:95%; margin:auto; margin-top:40px; background:#1a1a1a; border-collapse:collapse; }
th,td { padding:12px; border:1px solid #333; }
th { background:#ff4c4c; }
a.btn-del { color:#ff4c4c; font-weight:bold; }
.success { background:#004d00; padding:12px; border-radius:6px; margin-bottom:10px; }
</style>
</head>
<body>

<h1 style="text-align:center; color:#ff4c4c;">Njoftimet</h1>

<div class="box">

<?php if ($success): ?>
<div class="success"><?= $success ?></div>
<?php endif; ?>

<h2>Dërgo një njoftim</h2>

<form method="POST">

    <label>Dërgo njoftimin për:</label>
    <select name="user_id">
        <option value="all">Për të gjithë përdoruesit</option>
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['full_name']) ?></option>
        <?php endforeach; ?>
    </select>

    <input type="text" name="title" placeholder="Titulli" required>
    <textarea name="message" rows="4" placeholder="Mesazhi" required></textarea>

    <button name="create_notification" class="btn btn-red" style="margin-top:10px;">Dërgo njoftimin</button>
</form>
</div>

<h2 style="text-align:center; margin-top:40px;">Lista e njoftimeve</h2>

<table>
<tr>
  <th>Titulli</th>
  <th>Përdoruesi</th>
  <th>Mesazhi</th>
  <th>Data</th>
  <th>Veprime</th>
</tr>

<?php foreach ($notifications as $n): ?>
<tr>
  <td><?= htmlspecialchars($n['title']) ?></td>
  <td><?= $n['full_name'] ? htmlspecialchars($n['full_name']) : "Të gjithë" ?></td>
  <td><?= nl2br(htmlspecialchars($n['message'])) ?></td>
  <td><?= $n['created_at'] ?></td>
  <td><a href="admin_notifications.php?delete=<?= $n['id'] ?>" class="btn-del">Fshi</a></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
