<?php
// Conexión a la base de datos
require_once 'conexionBD.php'; 

// Recoger datos desde la URL
$id = $_GET['id'] ?? null; // ID del producto
$estado = filter_var($_GET['estado'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE); // Estado validado (1 o 0)

// Mensajes para depuración
if ($id === null || $estado === null) {
    echo "Error: Datos no válidos. ID: {$id}, Estado: {$estado}";
    exit;
}

try {
    // Preparar la consulta SQL
    $sql = "UPDATE objeto SET validado = :estado WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':estado', $estado, PDO::PARAM_BOOL);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        echo "Producto actualizado correctamente. Redirigiendo...";
        header("Location: ../gestionarProductos.php");
        exit;
    } else {
        echo "Error al ejecutar la consulta.";
    }
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
}

?>


