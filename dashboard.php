<?php
require 'config.php';

// Kontrollo sesionin
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$success = "";
$errors = [];

// ================================
//   SHTIMI I KURSEVE NË DATABASE
// ================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_name'])) {
    $course = trim($_POST['course_name']);

    if ($course !== "") {
        $stmt = $pdo->prepare("INSERT INTO user_courses (user_id, course_name) VALUES (?, ?)");
        $stmt->execute([$_SESSION['user_id'], $course]);
        $success = "Kursi u shtua me sukses!";
    }
}

// ================================
//   MARRJA E KURSEVE TË USER-IT
// ================================
$stmt = $pdo->prepare("SELECT id, course_name FROM user_courses WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <title>Paneli i Përdoruesit | ASA Education</title>
  <link rel="stylesheet" href="style.css">

  <style>
    html {
  scroll-behavior: smooth;
}
    body { padding-top: 120px; color: white; }

    .dash-container {
      width: 90%;
      max-width: 900px;
      margin: auto;
      text-align: center;
    }

    .course-box {
      background: #1a1a1a;
      padding: 15px;
      border-radius: 8px;
      margin: 10px 0;
      border: 1px solid #333;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .course-box a {
      color: #ff4c4c;
      font-weight: bold;
      text-decoration: none;
    }

    .success-msg {
      background: #004d00;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      color: #b9ffb9;
      font-weight: 600;
    }
  </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="logo">
        <img src="images/logo.png" alt="ASA Logo">
    </div>

    <ul class="nav-links">
        <li><a href="index.php#home">Kreu</a></li>
        <li><a href="index.php#courses">Kurset</a></li>
        <li><a href="institution.html">Institucioni</a></li>
        <li><a href="logout.php" style="color:#ff4c4c;">Dil</a></li>
    </ul>
</nav>

<div class="dash-container">

    <h1 style="font-size:38px; color:#ff4c4c;">
        Mirë se erdhe, <?= htmlspecialchars($_SESSION['full_name']); ?> 👋
    </h1>

    <p style="margin-top:15px; font-size:20px; color:#ddd;">
        Email: <strong><?= htmlspecialchars($_SESSION['email']); ?></strong>
    </p>

    <!-- MESAZH SUKSESI -->
    <?php if ($success): ?>
    <div class="success-msg"><?= $success ?></div>
    <?php endif; ?>

    <!-- =============================
         FORMULARI I SHTIMIT TË KURSEVE
    ============================== -->
    <hr style="margin:40px 0; border-color:#333;">

<h2 style="color:#ff4c4c;" id="addCourse">Zgjidh kurset që dëshiron</h2>

    <hr style="margin:40px 0; border-color:#333;">

    <h2 style="color:#ff4c4c;">Zgjidh kurset që dëshiron</h2>

    <form method="POST" style="margin-top:20px;">
        <select name="course_name" style="padding:12px; border-radius:8px;"<?php
$selected = $_GET['selected'] ?? "";
?>>
           <option value="Artistët e Vegjël" <?= $selected == "Artistët e Vegjël" ? "selected" : "" ?>>Artistët e Vegjël</option>
            <option value="Talente të Reja"<?=$selected== "Talente të Reja"? "selected": ""?>>Talente të Reja</option>
            <option value="Artistët & Arkitektët e Rinj" <?=$selected== "Artistët & Arkitektët e Rinj"? "selected": ""?>>Artistët & Arkitektët e Rinj</option>
            <option value="Grafik Dizajn"<?=$selected== "Grafik Dizajn"? "selected": ""?>>Grafik Dizajn</option>
            <option value="Seanca Individuale"<?=$selected== "Seanca Individuale"? "selected": ""?>>Seanca Individuale</option>
        </select>

        <button class="btn btn-red" style="margin-left:10px;">Shto kursin</button>
    </form>

    <!-- =============================
         SHFAQJA E KURSEVE TË USERIT
    ============================== -->

    <hr style="margin:40px 0; border-color:#333;">
    <h2 style="color:#ff4c4c;">Kursët e tua</h2>

    <?php if (count($courses) === 0): ?>
        <p style="color:#bbb; margin-top:20px;">Nuk ke zgjedhur ende asnjë kurs.</p>

    <?php else: ?>

        <?php foreach ($courses as $c): ?>
            <div class="course-box">
                <span><?= htmlspecialchars($c['course_name']); ?></span>
                <a href="remove_course.php?id=<?= $c['id'] ?>">Hiqe</a>
            </div>
        <?php endforeach; ?>
            
    <?php endif; ?>
    <hr style="margin:40px 0; border-color:#333;">

<h2 style="color:#ff4c4c;">Njoftimet e tua</h2>

<?php
$stmt = $pdo->prepare("
    SELECT title, message, created_at 
    FROM notifications
    WHERE user_id = ? OR user_id IS NULL
    ORDER BY created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$notes = $stmt->fetchAll();
?>

<?php if (count($notes) === 0): ?>
    <p style="color:#bbb;">Nuk ka asnjë njoftim.</p>

<?php else: ?>
    <?php foreach ($notes as $n): ?>
        <div style="background:#1a1a1a; padding:15px; margin:10px 0; border-radius:8px;">
            <h3 style="color:#ff4c4c;"><?= htmlspecialchars($n['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars($n['message'])) ?></p>
            <small style="color:#777;"><?= $n['created_at'] ?></small>
        </div>
    <?php endforeach; ?>
<?php endif; ?>


    <a href="logout.php" class="btn btn-red" style="margin-top:40px; display:inline-block;">
        Dil nga llogaria
    </a>

</div>

</body>
</html>
