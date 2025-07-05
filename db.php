<?php
// db.php

$host = 'localhost';       // Database host (usually localhost)
$user = 'root';            // MySQL username (default for XAMPP/WAMP is 'root')
$password = '';            // MySQL password (default for XAMPP/WAMP is empty)
$database = 'studymate';   // The name of the database

// Create connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optional: set charset to UTF-8
$conn->set_charset("utf8");

?>
