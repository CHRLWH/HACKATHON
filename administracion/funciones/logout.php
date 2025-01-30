<?php
    session_start();
    session_destroy(); // Destruir la sesión
    header("Location: ../../Login/Login.php"); // Redirigir al login
    exit();
?>