<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginAdmin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/styleAdminPanel.css"> <!-- Enlace al CSS para mejorar el diseño -->
</head>
<body>
    <div class="container">
        <h1>Bienvenido, <?php echo $_SESSION['admin_usuario']; ?></h1>
        <p>Este es el panel de administración. Aquí puedes gestionar productos y usuarios.</p>

        <!-- Aquí agregarías los enlaces o botones para gestionar los productos y usuarios -->
        <div class="admin-actions">
            <a href="gestionarProductos.php" class="button">Gestionar Productos</a>
            <a href="gestionarUsuarios.php" class="button">Gestionar Usuarios</a>
        </div>

        <a href="funciones/logout.php" class="logout-button">Cerrar sesión</a>
    </div>
</body>
</html>

