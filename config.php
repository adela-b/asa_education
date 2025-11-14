<?php
$host = "localhost";     
$db_name = "asa_education";  
$username = "root";      
$password = "";          

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lidhja me databazën dështoi: " . $e->getMessage());
}

session_start(); // për login
?>
