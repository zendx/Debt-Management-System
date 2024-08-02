<?php
$host = 'localhost';
$db   = 'dms';
$user = 'root';
$pass = '';

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, $options);
} catch (PDOException $e) {
    // Handle the error and display a user-friendly message
    error_log($e->getMessage()); // Log the error
    die('Database connection failed.'); // Show a generic message to the user
}
?>