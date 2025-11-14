<?php
require 'config.php';

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validime
    if ($full_name === '') {
        $errors[] = "Emri i plotë është i detyrueshëm.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Emaili nuk është i vlefshëm.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Fjalëkalimi duhet të ketë të paktën 6 karaktere.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Fjalëkalimet nuk përputhen.";
    }

    if (empty($errors)) {
        // Kontrollo nëse email ekziston
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = "Ky email është i regjistruar tashmë.";
        } else {
            // Ruaj përdoruesin
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (full_name, email, password_hash) VALUES (?, ?, ?)");

            if ($insert->execute([$full_name, $email, $hash])) {
                $success = "Regjistrimi u krye me sukses! Tani mund të kyçeni.";
            } else {
                $errors[] = "Ka ndodhur një problem gjatë regjistrimit.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <title>Regjistrohu - ASA Education</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="section-container" style="max-width:480px; margin:120px auto;">
  <h1>Regjistrohu</h1>

  <?php if (!empty($errors)): ?>
    <div class="error-box">
      <ul>
        <?php foreach ($errors as $e): ?>
          <li><?= htmlspecialchars($e) ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="success-box">
      <?= htmlspecialchars($success) ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <input type="text" name="full_name" placeholder="Emri i plotë" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Fjalëkalimi" required>
    <input type="password" name="confirm_password" placeholder="Përsërit fjalëkalimin" required>

    <button type="submit" class="btn btn-red" style="width:100%;">Regjistrohu</button>
  </form>

  <p style="margin-top:10px;">
    Ke llogari? <a href="#" onclick="openLogin()">Kyçu këtu</a>
  </p>

</div>

<script src="script.js"></script>

</body>
</html>
