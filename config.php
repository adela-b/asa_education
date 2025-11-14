<?php

session_start(); // fillon sesionin

// Merr kredencialet e Railway nga environment variables
$host = getenv('MYSQLHOST');
$db_name = getenv('MYSQLDATABASE');
$username = getenv('MYSQLUSER');
$password = getenv('MYSQLPASSWORD');

// Kontrollo nëse nuk janë gjetur variablat
if (!$host) {
    die("Railway nuk po jep variablat e MySQL. A e dhe Database-in si plugin?");
}

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8mb4",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Lidhja me databazën dështoi: " . $e->getMessage());
}

?>

