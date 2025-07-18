<?php
    // insert_product.php

    require 'conexion.php';  // Incluir la conexión
    require 'upload_images.php';  // Incluir la función de carga de imágenes

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['product-name'];

        // Llamar a la función para subir las imágenes
        $imagenes = uploadImages($_FILES['product-image']);

        // Asignar imágenes a variables individuales
        list($imagen1, $imagen2, $imagen3, $imagen4, $imagen5) = $imagenes + [null, null, null, null, null];

        // Sanitización de datos para evitar inyecciones SQL
        $nombre = $conn->real_escape_string($nombre);
        $imagen1 = $conn->real_escape_string($imagen1);
        $imagen2 = $conn->real_escape_string($imagen2);
        $imagen3 = $conn->real_escape_string($imagen3);
        $imagen4 = $conn->real_escape_string($imagen4);
        $imagen5 = $conn->real_escape_string($imagen5);

        // Crear la consulta SQL
        $sql = "INSERT INTO objeto (id, nombre, id_usuario, id_estado, id_categoria, imagen, imagen2, imagen3, imagen4, imagen5, validado)
                VALUES (NULL, '$nombre', 1, 1, 1, '$imagen1', '$imagen2', '$imagen3', '$imagen4', '$imagen5', 0)";

        // Ejecutar la consulta
        if ($conn->query($sql) === TRUE) {
            echo "<div class='alert alert-success mt-3'>Objeto agregado</div>";
        } else {
            echo "<div class='alert alert-danger mt-3'>Se ha producido un error: " . $conn->error . "</div>";
        }

        // Cerrar la conexión
        $conn->close();
    }
?>