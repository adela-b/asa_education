<?php
require 'config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validime bazë
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email i pavlefshëm.";
    }

    if (empty($errors)) {
        // Merr përdoruesin nga databaza
        $stmt = $pdo->prepare("SELECT id, full_name, password_hash FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kontrollo fjalëkalimin
        if ($user && password_verify($password, $user['password_hash'])) {
            
            // Ruaj të dhënat në sesion
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $email;
          $_SESSION['is_admin'] = $user['is_admin'];

            // Dergoje te dashboard
            header("Location: dashboard.php");
            exit;

        } else {
            $errors[] = "Email ose fjalëkalim i gabuar.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <title>Kyçu - ASA Education</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="section-container" style="max-width:480px; margin:120px auto;">
    <h1>Kyçu</h1>

    <?php if (!empty($errors)): ?>
      <div class="error-box">
        <ul>
          <?php foreach ($errors as $e): ?>
            <li><?= htmlspecialchars($e) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Fjalëkalimi" required>
        <button type="submit" class="btn btn-red" style="width:100%;">Kyçu</button>
    </form>

    <p style="margin-top:10px;">
      Nuk ke llogari? <a href="register.php">Regjistrohu këtu</a>
    </p>
</div>

</body>
</html>
