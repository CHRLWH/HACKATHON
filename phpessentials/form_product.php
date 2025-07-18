<?php
    $nombre = $_POST['product-name'];
    $categoria = $_POST['category'];
    $estado = $_POST['condition'];
    $imagenes = []; // Arreglo para almacenar las rutas de las imágenes

    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); 
    }

    foreach ($_FILES['product-image']['tmp_name'] as $key => $tmpName) {
        $fileName = basename($_FILES['product-image']['name'][$key]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $filePath)) {
            $imagenes[] = $filePath;
        }
    }

    // Asegurar que tengamos al menos 5 valores definidos en el arreglo
    for ($i = count($imagenes); $i < 5; $i++) {
        $imagenes[] = null; // Rellenar con null si faltan imágenes
    }

    // Asignar imágenes a variables individuales
    $imagen1 = $imagenes[0] ?? null;
    $imagen2 = $imagenes[1] ?? null;
    $imagen3 = $imagenes[2] ?? null;
    $imagen4 = $imagenes[3] ?? null;
    $imagen5 = $imagenes[4] ?? null;

    // Sanitización de datos para evitar inyecciones SQL
    $nombre = $conn->real_escape_string($nombre);
    $categoria = $conn->real_escape_string($categoria);
    $estado = $conn->real_escape_string($estado);
    $imagen1 = $conn->real_escape_string($imagen1);
    $imagen2 = $conn->real_escape_string($imagen2);
    $imagen3 = $conn->real_escape_string($imagen3);
    $imagen4 = $conn->real_escape_string($imagen4);
    $imagen5 = $conn->real_escape_string($imagen5);

    // Crear la consulta SQL sin bind_param
    $sql = "INSERT INTO objeto (id, nombre, id_usuario, id_estado, id_categoria, imagen, imagen2, imagen3, imagen4, imagen5, validado)
            VALUES (NULL, '$nombre', 1, '$estado', '$categoria', '$imagen1', '$imagen2', '$imagen3', '$imagen4', '$imagen5', 0)";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success mt-3'>Objeto agregado</div>";
    } else {
        echo "<div class='alert alert-danger mt-3'>Se ha producido un error </div>";
    }
?>

<form action="insert_product.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="product-name">Nombre del producto:</label>
        <input type="text" name="product-name" class="form-control" id="product-name" required>
    </div>
    
    <div class="form-group">
        <label for="product-image">Imágenes del producto:</label>
        <input type="file" name="product-image[]" class="form-control" id="product-image" multiple required>
    </div>

    <button type="submit" class="btn btn-primary mt-3">Agregar Producto</button>
</form>