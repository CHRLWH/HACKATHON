<?php
// Database connection parameters
$servername = "localhost";
$username = "adminphp"; // Update with your database username
$password = "2002";    // Update with your database password
$dbname = "hackaton"; // Update with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginFailed = false;
?>