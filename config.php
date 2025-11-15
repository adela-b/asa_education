<?php
session_start();

$host = "mysql.railway.internal";
$db_name = "railway";
$username = "root";
$password = "aCofDFLnyXaOGNFJnMUNUVsINjtuJrmJ";
$port = 3306;

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$db_name;charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lidhja me databazën dështoi: " . $e->getMessage());
}
?>


