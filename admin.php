<?php
require 'config.php';

// Kontrollo nëse përdoruesi është i kyçur
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Kontrollo nëse është admin
if ($_SESSION['is_admin'] != 1) {
    die("Nuk keni qasje në këtë seksion.");
}

// Merr të gjithë përdoruesit
$stmt = $pdo->prepare("SELECT id, full_name, email, is_admin, created_at FROM users ORDER BY created_at DESC");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
// =====================
// STATISTIKAT
// =====================

// Total users
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

// Total admins
$totalAdmins = $pdo->query("SELECT COUNT(*) FROM users WHERE is_admin = 1")->fetchColumn();

// Users active (me kurse)
$activeUsers = $pdo->query("
    SELECT COUNT(DISTINCT user_id) 
    FROM user_courses
")->fetchColumn();

// Total courses selected
$totalCourses = $pdo->query("SELECT COUNT(*) FROM user_courses")->fetchColumn();

// Most popular course
$popularQuery = $pdo->query("
    SELECT course_name, COUNT(*) AS cnt 
    FROM user_courses 
    GROUP BY course_name 
    ORDER BY cnt DESC 
    LIMIT 1
");
$popularCourse = $popularQuery->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="sq">
<head>
  <meta charset="UTF-8">
  <title>Paneli i Administratorit</title>
  <p style="text-align:center; margin-top:20px;">
  <a href="admin_courses.php" class="btn btn-red">Menaxho kurset</a>
</p>
<p style="text-align:center; margin-top:20px;">
  <a href="admin_notifications.php" class="btn btn-red">Njoftimet</a>
</p>

  <link rel="stylesheet" href="style.css">
  <style>
    body { padding-top: 120px; }
    table {
      width: 90%;
      margin: auto;
      background: #1a1a1a;
      color: white;
      border-collapse: collapse;
      border: 1px solid #333;
    }
    th, td {
      padding: 12px;
      border: 1px solid #333;
      text-align: left;
    }
    th { background: #ff4c4c; }
    .delete-btn {
      color: #ff4c4c; text-decoration:none; font-weight:600;
    }
    .delete-btn:hover {
      color: white;
    }
  </style>
</head>
<body>

<h1 style="text-align:center; color:#ff4c4c;">Paneli i Administratorit</h1>
<div style="display:flex; justify-content:center; gap:25px; flex-wrap:wrap; margin:30px auto; width:90%;">

    <div style="
        background:#1a1a1a; 
        padding:20px;
        border-radius:10px;
        width:220px;
        text-align:center;
        box-shadow:0 0 10px rgba(255,76,76,0.2);
        border:1px solid #333;">
        <h3 style="color:#ff4c4c;">Përdorues total</h3>
        <p style="font-size:28px; margin-top:10px;"><?= $totalUsers ?></p>
    </div>

    <div style="
        background:#1a1a1a; 
        padding:20px;
        border-radius:10px;
        width:220px;
        text-align:center;
        box-shadow:0 0 10px rgba(255,76,76,0.2);
        border:1px solid #333;">
        <h3 style="color:#ff4c4c;">Adminë</h3>
        <p style="font-size:28px; margin-top:10px;"><?= $totalAdmins ?></p>
    </div>

    <div style="
        background:#1a1a1a; 
        padding:20px;
        border-radius:10px;
        width:220px;
        text-align:center;
        box-shadow:0 0 10px rgba(255,76,76,0.2);
        border:1px solid #333;">
        <h3 style="color:#ff4c4c;">Përdorues aktiv</h3>
        <p style="font-size:28px; margin-top:10px;"><?= $activeUsers ?></p>
    </div>

    <div style="
        background:#1a1a1a; 
        padding:20px;
        border-radius:10px;
        width:220px;
        text-align:center;
        box-shadow:0 0 10px rgba(255,76,76,0.2);
        border:1px solid #333;">
        <h3 style="color:#ff4c4c;">Kurset totale</h3>
        <p style="font-size:28px; margin-top:10px;"><?= $totalCourses ?></p>
    </div>

    <div style="
        background:#1a1a1a; 
        padding:20px;
        border-radius:10px;
        width:300px;
        text-align:center;
        box-shadow:0 0 10px rgba(255,76,76,0.2);
        border:1px solid #333;">
        <h3 style="color:#ff4c4c;">Kursi më popullor</h3>
        <p style="font-size:24px; margin-top:10px;">
            <?= $popularCourse ? $popularCourse['course_name'] . " ({$popularCourse['cnt']} regjistrime)" : "Asnjë kurs" ?>
        </p>
    </div>

</div>


<table>
  <tr>
    <th>ID</th>
    <th>Emri</th>
    <th>Email</th>
    <th>Roli</th>
    <th>Data Regjistrimit</th>
    <th>Veprime</th>
  </tr>

  <?php foreach ($users as $u): ?>
    <tr>
      <td><?= $u['id'] ?></td>
      <td><?= htmlspecialchars($u['full_name']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= $u['is_admin'] ? 'Administrator' : 'Përdorues' ?></td>
      <td><?= $u['created_at'] ?></td>
      <td>
        <a class="delete-btn" href="delete_user.php?id=<?= $u['id'] ?>">Fshi</a>
      </td>
    </tr>
    
  <?php endforeach; ?>
</table>
<hr style="margin:50px 0; border-color:#333;">

<h1 style="text-align:center; color:#ff4c4c; margin-bottom:20px;">
  Kurset e Përdoruesve
</h1>

<?php
// Merr të gjithë user + kurset
$query = $pdo->prepare("
    SELECT users.full_name, users.email, user_courses.course_name 
    FROM users
    LEFT JOIN user_courses ON users.id = user_courses.user_id
    ORDER BY users.full_name ASC
");
$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
  <tr>
    <th>Përdoruesi</th>
    <th>Email</th>
    <th>Kursi</th>
  </tr>

  <?php foreach ($data as $row): ?>
  <tr>
    <td><?= htmlspecialchars($row['full_name']) ?></td>
    <td><?= htmlspecialchars($row['email']) ?></td>
    <td><?= $row['course_name'] ? htmlspecialchars($row['course_name']) : "<span style='color:#777;'>Asnjë kurs</span>" ?></td>
  </tr>
  <?php endforeach; ?>
</table>

</body>
</html>
