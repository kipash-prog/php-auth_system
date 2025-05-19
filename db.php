<?php
// db.php - Database connection file

$host = 'localhost';
$db   = 'auth_system';
$user = 'root'; // Change this if your MySQL username is different
$pass = '';     // Change this if your MySQL password is not empty
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Handle connection error
    die('Database connection failed: ' . $e->getMessage());
} 