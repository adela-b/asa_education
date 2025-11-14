<?php
require 'config.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Kontrollo nëse email ekziston
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->rowCount() === 0) {
        $msg = "Ky email nuk është i regjistruar.";
    } else {
        // Gjenero kod 6-shifror
        $code = rand(100000, 999999);
        $expires = date("Y-m-d H:i:s", time() + 600); // 10 minuta

        // Ruaje në DB
        $pdo->prepare("INSERT INTO password_resets (email, code, expires_at) VALUES (?, ?, ?)")
            ->execute([$email, $code, $expires]);

        // Dërgo emailin
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;

        // SET YOUR EMAIL HERE
        $mail->Username = "EMAILIYT@gmail.com";
        $mail->Password = "KODI-APLIKACIONIT";  
        $mail->SMTPSecure = "tls";
        $mail->Port = 587;

        $mail->setFrom("EMAILIYT@gmail.com", "ASA Education");
        $mail->addAddress($email);
        $mail->Subject = "Kodi per reset password";
        $mail->Body = "Kodi juaj per reset password eshte: $code (vlen 10 minuta)";

        if ($mail->send()) {
            header("Location: verify_code.php?email=" . urlencode($email));
            exit;
        } else {
            $msg = "Gabim gjatë dërgimit të kodit.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<link rel="stylesheet" href="style.css">
</head>
<body style="padding:150px; color:white; text-align:center;">

<h1 style="color:#ff4c4c;">Reset Password</h1>

<p>Vendos email-in për të marrë kodin e verifikimit:</p>

<form method="POST" style="max-width:400px; margin:auto;">
    <input type="email" name="email" placeholder="Email" required 
           style="width:100%; padding:12px; border-radius:8px; background:#222; color:white;">
    <button class="btn btn-red" style="margin-top:15px;">Dërgo Kodin</button>
</form>

<?php if ($msg): ?>
<p style="color:#ff4c4c; margin-top:20px;"><?= $msg ?></p>
<?php endif; ?>

</body>
</html>
