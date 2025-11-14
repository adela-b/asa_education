<?php
require 'config.php';

// ADMIN CHECK
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    die("Akses i ndaluar.");
}

// Delete Course
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $stmt = $pdo->prepare("DELETE FROM user_courses WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: admin_courses.php");
    exit;
}

// Edit Course (Save)
if (isset($_POST['edit_course'])) {
    $id = intval($_POST['course_id']);
    $name = trim($_POST['course_name']);

    $stmt = $pdo->prepare("UPDATE user_courses SET course_name=? WHERE id=?");
    $stmt->execute([$name, $id]);

    header("Location: admin_courses.php");
    exit;
}

// GET all courses
$query = $pdo->prepare("
    SELECT user_courses.*, users.full_name 
    FROM user_courses
    JOIN users ON user_courses.user_id = users.id
    ORDER BY users.full_name ASC
");
$query->execute();
$data = $query->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<title>Kurse | Admin</title>
<link rel="stylesheet" href="style.css">
<style>
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
}
th { background: #ff4c4c; }
.edit-btn, .delete-btn {
  text-decoration: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-weight: bold;
}
.edit-btn { background: #0066ff; color: white; }
.delete-btn { background: #cc0000; color: white; }
.modal {
  position: fixed; top:0; left:0; width:100%; height:100%;
  background: rgba(0,0,0,0.8);
  display:none; justify-content:center; align-items:center;
}
.modal-content {
  background:#1a1a1a; padding:20px; border-radius:10px;
  width:300px; text-align:center; color:white;
}
</style>
</head>
<body>

<h1 style="text-align:center; color:#ff4c4c;">Menaxhimi i Kurseve</h1>

<table>
<tr>
  <th>Përdoruesi</th>
  <th>Kursi</th>
  <th>Veprime</th>
</tr>

<?php foreach ($data as $row): ?>
<tr>
  <td><?= htmlspecialchars($row['full_name']) ?></td>
  <td><?= htmlspecialchars($row['course_name']) ?></td>
  <td>
      <a href="#" class="edit-btn" onclick="openModal(<?= $row['id'] ?>, '<?= $row['course_name'] ?>')">Ndrysho</a>
      <a href="admin_courses.php?delete=<?= $row['id'] ?>" class="delete-btn">Fshi</a>
  </td>
</tr>
<?php endforeach; ?>
</table>

<!-- MODAL EDITING -->
<div id="editModal" class="modal">
  <div class="modal-content">
      <h3>Ndrysho kursin</h3>

      <form method="POST">
          <input type="hidden" name="course_id" id="modal_course_id">
          <input type="text" name="course_name" id="modal_course_name" style="padding:10px; width:90%; margin-top:10px;">
          <button name="edit_course" class="btn btn-red" style="width:100%; margin-top:10px;">Ruaj</button>
      </form>
      <button onclick="closeModal()" class="btn btn-white" style="margin-top:10px;">Mbyll</button>
  </div>
</div>

<script>
function openModal(id, name) {
  document.getElementById("editModal").style.display = "flex";
  document.getElementById("modal_course_id").value = id;
  document.getElementById("modal_course_name").value = name;
}

function closeModal() {
  document.getElementById("editModal").style.display = "none";
}
</script>

</body>
</html>
