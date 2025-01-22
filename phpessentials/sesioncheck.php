<?php
// Verificar si la sesión está activa
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redirigir al login si no está iniciada la sesión
    header("Location: ../../../Login/LoginHtml/Login.php"); // Cambia "/ruta/a/tu/login.php" por la ruta real
    exit; // Detener la ejecución del script
}

// Si la sesión está activa, el usuario permanece en la página
echo "Bienvenido, " . htmlspecialchars($_SESSION['user_name']) . "!";
?>
