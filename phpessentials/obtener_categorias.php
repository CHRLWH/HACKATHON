<?php
require 'conexion.php';

try {
    // Obtener todas las categorías desde la base de datos
    $sql_categorias = "SELECT id, nombre FROM categorias_objetos";
    $stmt_categorias = $pdo->prepare($sql_categorias);
    $stmt_categorias->execute();
    $categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

    // Obtener la cantidad total de categorías
    $sql_total_categorias = "SELECT COUNT(*) FROM categorias_objetos";
    $stmt_total_categorias = $pdo->prepare($sql_total_categorias);
    $stmt_total_categorias->execute();
    $total_categorias = $stmt_total_categorias->fetchColumn();
} catch (PDOException $e) {
    die("Error al obtener categorías: " . $e->getMessage());
}
?>
