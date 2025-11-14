<?php
require 'config.php';

$email = $_GET['email'] ?? "";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code']);

    // Merr kodin nga DB
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email=? AND code=? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$email, $code]);
    $row = $stmt->fetch();

    if (!$row) {
        $msg = "Kodi është i gabuar.";
    } elseif (strtotime($row['expires_at']) < time()) {
        $msg = "Kodi ka skaduar.";
    } else {
        header("Location: reset_password.php?email=" . urlencode($email));
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Verifiko Kodin</title>
<link rel="stylesheet" href="style.css">
</head>
<body style="padding:150px; text-align:center; color:white;">

<h2 style="color:#ff4c4c;">Vendos Kodin e Verifikimit</h2>

<form method="POST" style="max-width:350px; margin:auto;">
    <input type="text" name="code" placeholder="Kodi 6-shifror" required
           style="width:100%; padding:12px; border-radius:8px; background:#222; color:white;">
    <button class="btn btn-red" style="margin-top:20px;">Vazhdo</button>
</form>

<?php if ($msg): ?>
<p style="color:#ff4c4c;"><?= $msg ?></p>
<?php endif; ?>

</body>
</html>
