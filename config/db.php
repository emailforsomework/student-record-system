<?php
// Database configuration
$host = 'localhost';
$dbname = 'school_db';
$username = 'root'; // Update with your MySQL username
$password = 'WJ28@krhps'; // Update with your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Connection successful
} catch (PDOException $e) {
    // Handle connection error
    die("Connection failed: " . $e->getMessage());
}
?>