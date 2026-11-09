<?php
$host = 'localhost';
$db   = 'lab_mini_project';
$user = 'root';
$pass = ''; // Change this if your MySQL has a password.

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
         PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
    );
} catch (PDOException $e) {
    exit("Database connection failed. Check config.php and import database.sql.");
}
?>
