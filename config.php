<?php

$dsn = "mysql:host=" . getenv("DB_HOST") . ";dbname=" . getenv("DB_NAME") . ";charset=utf8mb4";

try {
    $pdo = new PDO($dsn, getenv("DB_USER"), getenv("DB_PASS"), [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (PDOException $e) {
    die("Lidhja me databazën dështoi: " . $e->getMessage());
}
