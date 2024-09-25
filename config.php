<?php
// config.php - Database connection using PDO
try {
    // Create a new PDO instance
    $conn = new PDO("mysql:host=localhost;dbname=net", "princy", "misa2026");
    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} 
catch (PDOException $e) {
    // Handle connection error
    die('Cannot connect to database: ' . $e->getMessage());
}
?>
