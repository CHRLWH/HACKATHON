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
    <title>Gestionar Productos</title>
    <link rel="stylesheet" href="css/styleProductos.css">
</head>
<body>
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
</body>
</html>


