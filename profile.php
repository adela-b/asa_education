<?php
require 'config.php';

// Duhet të jetë i kyçur
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = "";
$errors = [];

// Merr të dhënat aktuale të userit
$stmt = $pdo->prepare("SELECT full_name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// NËSE FORMULARET JANË SUBMITUAR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Ndryshimi i emrit/emailit
    if (isset($_POST['save_profile'])) {
        $full_name = trim($_POST['full_name']);
        $email = trim($_POST['email']);

        if ($full_name === "") $errors[] = "Emri nuk mund të jetë bosh.";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email i pavlefshëm.";

        if (empty($errors)) {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
            $stmt->execute([$full_name, $email, $user_id]);

            $_SESSION['full_name'] = $full_name;
            $_SESSION['email'] = $email;

            $success = "Profili u përditësua me sukses!";
        }
    }

    // Ndryshimi i fjalëkalimit
    if (isset($_POST['change_password'])) {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        // Merr passwordin aktual
        $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $row = $stmt->fetch();

        if (!password_verify($current, $row['password_hash'])) {
            $errors[] = "Fjalëkalimi aktual është i gabuar.";
        }
        if (strlen($new) < 6) {
            $errors[] = "Fjalëkalimi i ri duhet të ketë të paktën 6 karaktere.";
        }
        if ($new !== $confirm) {
            $errors[] = "Fjalëkalimet e reja nuk përputhen.";
        }

        if (empty($errors)) {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
            $update->execute([$hash, $user_id]);

            $success = "Fjalëkalimi u ndryshua me sukses!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Profili im</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body { padding-top: 120px; }

        .profile-container {
            width: 90%;
            max-width: 650px;
            background: #1a1a1a;
            margin: auto;
            padding: 30px;
            border-radius: 12px;
            color: white;
            box-shadow: 0 0 15px rgba(255, 76, 76, 0.2);
        }

        .profile-container h1 {
            color: #ff4c4c;
            margin-bottom: 20px;
        }

        .profile-container input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background: #222;
            border: none;
            border-radius: 8px;
            color: white;
        }

        .profile-container button {
            width: 100%;
            padding: 12px;
            margin-top: 10px;
        }

        .error-box, .success-box {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .error-box { background: #7a0000; color: white; }
        .success-box { background: #004d00; color: white; }
    </style>
</head>
<body>

<div class="profile-container">

    <h1>Profili im</h1>

    <?php if (!empty($errors)): ?>
        <div class="error-box">
            <?php foreach ($errors as $e): ?>
                <p><?= htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success-box">
            <?= htmlspecialchars($success); ?>
        </div>
    <?php endif; ?>

    <!-- FORM 1: Ndryshimi i Emrit / Emailit -->
    <form method="POST">
        <h3>Informacionet personale</h3>

        <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']); ?>" required>
        <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required>

        <button type="submit" name="save_profile" class="btn btn-red">Ruaj ndryshimet</button>
    </form>

    <hr style="margin:30px 0; border-color:#333;">

    <!-- FORM 2: Ndryshimi i Passwordit -->
    <form method="POST">
        <h3>Ndrysho fjalëkalimin</h3>

        <input type="password" name="current_password" placeholder="Fjalëkalimi aktual" required>
        <input type="password" name="new_password" placeholder="Fjalëkalimi i ri" required>
        <input type="password" name="confirm_password" placeholder="Përsërit fjalëkalimin e ri" required>

        <button type="submit" name="change_password" class="btn btn-red">Ndrysho fjalëkalimin</button>
    </form>

</div>

</body>
</html>
