<?php
// includes/db_connect.php

$host = 'localhost';
$dbname = 'computer_cafe_db';
$username = 'root';
$password = ''; // Default XAMPP password is empty

try {
    date_default_timezone_set('Asia/Manila');
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // In production, log this error instead of showing it
    die("Database connection failed: " . $e->getMessage());
}
?>
