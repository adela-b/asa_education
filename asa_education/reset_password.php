<?php
require 'config.php';

$email = $_GET['email'] ?? "";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass = $_POST['password'];
    $confirm = $_POST['confirm'];

    if ($pass !== $confirm) {
        $msg = "Fjalëkalimet nuk përputhen.";
    } elseif (strlen($pass) < 6) {
        $msg = "Fjalëkalimi duhet të jetë të paktën 6 karaktere.";
    } else {
        $hash = password_hash($pass, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password_hash=? WHERE email=?");
        $stmt->execute([$hash, $email]);

        // Fshi reset-et e vjetra
        $pdo->prepare("DELETE FROM password_resets WHERE email=?")->execute([$email]);

        header("Location: login.php?reset=success");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
<link rel="stylesheet" href="style.css">
</head>
<body style="padding:150px; text-align:center; color:white;">

<h2 style="color:#ff4c4c;">Vendos Fjalëkalimin e Ri</h2>

<form method="POST" style="max-width:350px; margin:auto;">
    <input type="password" name="password" placeholder="Fjalëkalimi i ri" required
           style="width:100%; padding:12px; border-radius:8px; background:#222; color:white;">
    <input type="password" name="confirm" placeholder="Përsërit fjalëkalimin" required
           style="width:100%; padding:12px; border-radius:8px; background:#222; color:white; margin-top:10px;">
    <button class="btn btn-red" style="margin-top:20px;">Ndrysho fjalëkalimin</button>
</form>

<?php if ($msg): ?>
<p style="color:#ff4c4c;"><?= $msg ?></p>
<?php endif; ?>

</body>
</html>
