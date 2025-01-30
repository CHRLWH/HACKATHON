<?php
session_start();
if (!isset($_SESSION['user_id'])) {

    header("Location: http://localhost/HACKATHON/Login/Login.php");
    exit;
}

echo "Bienvenido, " . htmlspecialchars($_SESSION['user_name']) . "!";
?>
