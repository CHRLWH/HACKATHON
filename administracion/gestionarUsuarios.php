<?php
    session_start();
    if (!isset($_SESSION['admin_id'])) {
        header("Location: ../Login/Login.php");
        exit();
    }

    require_once '../administracion/funciones/conexionBD.php'; // Conexión a la base de datos

    // Obtener el filtro de estado si existe
    $estado = isset($_GET['estado']) ? $_GET['estado'] : 'todos'; // 'todos', 'activo', 'inactivo'

    // Consulta SQL con filtrado por estado
    $sql = "SELECT * FROM usuario"; 
    if ($estado === 'activo') {
        $sql .= " WHERE estado = 1"; // Solo usuarios activos
    } elseif ($estado === 'inactivo') {
        $sql .= " WHERE estado = 0"; // Solo usuarios inactivos
    }

    $stmt = $conexion->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se ha hecho clic en los botones de bloqueo/desbloqueo
    if (isset($_GET['accion']) && isset($_GET['id'])) {
        $accion = $_GET['accion'];  // 'bloquear' o 'desbloquear'
        $id = $_GET['id'];

        // Actualizar el estado del usuario en la base de datos
        if ($accion == 'bloquear') {
            $sql = "UPDATE usuario SET estado = 0 WHERE id = :id"; // Bloquear usuario (estado = 0)
        } elseif ($accion == 'desbloquear') {
            $sql = "UPDATE usuario SET estado = 1 WHERE id = :id"; // Desbloquear usuario (estado = 1)
        }

        $stmt = $conexion->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirigir para evitar reenvío del formulario
        header("Location: gestionarUsuarios.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Gestionar Usuarios</title>
        <link rel="stylesheet" href="css/styleAdminPanel.css">
        <link rel="stylesheet" href="css/styleUsuarios.css">
    </head>

    <body>
        <!-- Header -->
        <header class="header">
            <div class="container d-flex align-items-center justify-content-between">
                <!-- Logo a la izquierda -->
                <a href="#" class="navbar-brand">
                    <img src="../img/4-removebg-preview (1).png" alt="Logo" class="logo">
                </a>
                <!-- Botones centrados -->
                <div class="header-buttons d-flex justify-content-center">
                    <a href = "adminPanel.php" class = "btn btn-primary"> Gestionar productos </a>
                    <a href = "gestionarUsuarios.php" class = "btn btn-primary" > Gestionar chat </a>
                </div>
                <!-- Botón a la derecha -->
                <a href="funciones/logout.php" class="btn btn-secondary">Cerrar sesión</a>
            </div>
        </header>

        <div class="container">
            <br><br><br><br><br>
            <br><br><br><br><br>
            <h1>Gestionar Usuarios</h1>

            <!-- Filtro de estado -->
            <form method="GET" action="">
                <select name="estado" onchange="this.form.submit()">
                    <option value="todos" <?php echo $estado == 'todos' ? 'selected' : ''; ?>>Todos</option>
                    <option value="activo" <?php echo $estado == 'activo' ? 'selected' : ''; ?>>Activos</option>
                    <option value="inactivo" <?php echo $estado == 'inactivo' ? 'selected' : ''; ?>>Inactivos</option>
                </select>
            </form>

            <!-- Mostrar usuarios -->
            <div class="usuarios-container">
                <?php
                if (count($result) > 0) {
                    foreach ($result as $row) {
                        ?>
                        <div class="usuario-card">
                            <h3>ID: <?php echo $row['id']; ?></h3>
                            <p>Codigo CAM: <?php echo $row['codigo_CAM']; ?> </p>
                            <p>Fecha de Inscripción: <?php echo $row['fecha_inscripcion']; ?></p>
                            <p>Correo: <?php echo $row['correo']; ?></p>
                            <p>Estado: <?php echo $row['estado'] == 1 ? 'Activo' : 'Inactivo'; ?></p>

                            <!-- Botones para bloquear y desbloquear -->
                            <?php if ($row['estado'] == 1): ?>
                                <a href="?accion=bloquear&id=<?php echo $row['id']; ?>" class="btn-bloquear">Bloquear</a>
                            <?php else: ?>
                                <a href="?accion=desbloquear&id=<?php echo $row['id']; ?>" class="btn-desbloquear">Activar</a>
                            <?php endif; ?>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>No hay usuarios disponibles.</p>";
                }
                ?>
            </div>
        </div>
        <!-- Footer -->
        <footer class="footer">
            <div class="container text-center">
                <!-- Logo centrado -->
                <a href="#" class="navbar-brand d-block">
                    <img src="../img/4-removebg-preview (1).png" alt="Logo" class="logo">
                </a>
                <!-- Texto debajo del logo -->
                <p class="footer-text">&copy; 2024 Tu Empresa. Todos los derechos reservados.</p>
            </div>
        </footer>
    </body>
</html>