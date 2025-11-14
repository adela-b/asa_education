<?php
require 'config.php';

// Duhet të jetë admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    die("Akses i ndaluar.");
}

// Kontrollo ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Mos lejo fshirjen e vet adminit
    if ($id == $_SESSION['user_id']) {
        die("Nuk mund të fshish veten.");
    }

    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: admin.php");
    exit;
}
