<?php
session_start();
session_destroy(); // Destruir la sesión
header("Location: ../loginAdmin.php"); // Redirigir al login
exit();
?>
