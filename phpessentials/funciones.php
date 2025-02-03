<?php
function getEstadoClass($tipo) {
    switch(strtolower($tipo)) {
        case 'excelente':
            return 'text-success fw-bold';
        case 'bien':
            return 'text-primary fw-bold';
        case 'defectuoso':
            return 'text-danger fw-bold';
        default:
            return 'text-muted';
    }
}
// Asegúrate de que la conexión a la base de datos está incluida
include 'conexion.php';  // Asegúrate de que la ruta sea correcta

function obtenerProductoPorId($productoId) {
    global $conexion;  // Utiliza la variable global de conexión

    // Verificar si la conexión es válida
    if ($conexion === null) {
        die("Error de conexión a la base de datos");
    }

    // Preparar la consulta para obtener el producto por ID
    $sql = "SELECT * FROM productos WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->execute([$productoId]);

    // Obtener el producto
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
