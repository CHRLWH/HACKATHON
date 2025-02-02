<?php
require 'conexion.php';

// Obtener el filtro desde la URL (por defecto "todos")
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

// Obtener el término de búsqueda
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

try {
    // Consultar productos según el filtro y la búsqueda
    $sql = "SELECT o.*, c.nombre as categoria_nombre, e.tipo as estado_tipo 
            FROM objeto o 
            LEFT JOIN categorias_objetos c ON o.id_categoria = c.id 
            LEFT JOIN estado e ON o.id_estado = e.id 
            WHERE o.validado = 1";
    $params = [];

    if ($filtro !== 'todos') {
        $sql .= " AND o.id_categoria = :filtro";
        $params[':filtro'] = $filtro;
    }

    if (!empty($busqueda)) {
        $sql .= " AND o.nombre LIKE :busqueda";
        $params[':busqueda'] = "%$busqueda%";
    }

    $sql .= " ORDER BY o.id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error al obtener productos: " . $e->getMessage());
}
?>
