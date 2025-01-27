<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: loginAdmin.php");
    exit();
}

require_once 'funciones/conexionBD.php'; // Conexión a la base de datos

// Obtener el filtro de validación si existe
$validado = isset($_GET['validado']) ? $_GET['validado'] : 'todos'; // 'todos', 'validados', 'no_validados'

// Consulta SQL con filtrado por validación
$sql = "SELECT * FROM objeto";
if ($validado === 'validados') {
    $sql .= " WHERE validado = 1"; // Solo productos validados
} elseif ($validado === 'no_validados') {
    $sql .= " WHERE validado = 0"; // Solo productos no validados
}

$stmt = $conexion->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link rel="stylesheet" href="css/styleAdminPanel.css"> <!-- Enlace al CSS para mejorar el diseño -->
    <link rel="stylesheet" href="css/styleProductos.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../ProductoTiendaIvan/css/csst.css">
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
                <a href = "gestionarUsuarios.php" class = "btn btn-primary"> Gestionar usuarios </a>
                <a href = "gestionarUsuarios.php" class = "btn btn-primary" > Gestionar chat </a>
            </div>
            <!-- Botón a la derecha -->
            <a href="funciones/logout.php" class="btn btn-secondary">Cerrar sesión</a>
        </div>
    </header>

    <!-- Contenido Principal -->
    <div class="container">
        <h1>Gestionar Productos</h1>

        <!-- Filtro de validación -->
        <form method="GET" action="">
            <select name="validado" onchange="this.form.submit()">
                <option value="todos" <?php echo $validado == 'todos' ? 'selected' : ''; ?>>Todos</option>
                <option value="validados" <?php echo $validado == 'validados' ? 'selected' : ''; ?>>Validados</option>
                <option value="no_validados" <?php echo $validado == 'no_validados' ? 'selected' : ''; ?>>No validados</option>
            </select>
        </form>

        <!-- Mostrar productos -->
        <div class="productos-container">
            <?php
            if (count($result) > 0) {
                foreach ($result as $row) {
                    $imagenes = [
                        $row['imagen'], 
                        $row['imagen2'], 
                        $row['imagen3'], 
                        $row['imagen4'], 
                        $row['imagen5']
                    ];
                    // Filtrar imágenes vacías
                    $imagenes = array_filter($imagenes);
                    ?>
                    <div class="producto-card">
                        <h3><?php echo $row['nombre']; ?></h3>
                        <div class="producto-imagen">
                            <img src="<?php echo $imagenes[0]; ?>" alt="Imagen principal" class="imagen-principal">
                        </div>
                        <a href="verProducto.php?id=<?php echo $row['id']; ?>" class="ver-detalles">Ver detalles</a>
                        <p>Status: <?php echo $row['validado'] == 1 ? 'Validado' : 'No Validado'; ?></p>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No hay productos disponibles.</p>";
            }
            ?>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <hr class="mb-5">
            <div class="row g-4 justify-content-center text-light text-center mb-5">
                <div class="col-md-4">
                    <a href="../privacidad.html" class="footer-link">Política de privacidad</a>
                </div>
                <div class="col-md-4">
                    <a href="../Terminos.html" class="footer-link">Términos y condiciones</a>
                </div>
                <div class="col-md-4">
                    <a href="../QyA.html" class="footer-link">QyA</a>
                </div>
            </div>
            <div class="d-flex justify-content-around align-items-center">
            <img src="../img/logomadrid.jpg" alt="Logo Madrid" class="img-fluid" style="max-height: 100px;">
            
            <img src="../img/4-removebg-preview (1).png" alt="Hilan Logo" class="img-fluid" style="max-height: 80px;">
            
            <img src="../img/logouax.png" alt="Logo UAX" class="img-fluid" style="max-height: 100px;">
        </div>
        </div>
    </footer>
</body>

</html>

