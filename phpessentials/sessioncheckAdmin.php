<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    // Si no hay una sesión de administrador, redirige al login
    header("Location: http://localhost/HACKATHON/Login/Login.php");
    exit();
}

?>
