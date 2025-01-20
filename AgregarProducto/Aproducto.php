<?php
$servidor = 'localhost';
$BBDD = 'hackaton';
$usuario = 'usuariophpdam';
$contra = '1234qwerty..';

try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$BBDD;charset=utf8", $usuario, $contra);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre = $_POST['nombreProducto'];

        // Valores estáticos para id_usuario, id_estado e id_categoria (ajústalos según tus necesidades)
        $id_usuario = 1; // Cambia a un ID válido de la tabla `usuario`
        $id_estado = 1; // Cambia a un ID válido de la tabla `estado`
        $id_categoria = 1; // Cambia a un ID válido de la tabla `categorias_objetos`

        if (empty($nombre)) {
            die("No has rellenado los campos obligatorios.");
        }

        // Crear carpeta 'uploads' si no existe
        if (!file_exists('uploads')) {
            mkdir('uploads', 0777, true);
        }

        // Manejar la subida de imágenes (máximo 5 imágenes)
        $fotoPaths = [];
        foreach ($_FILES['fotos']['tmp_name'] as $key => $tmp_name) {
            if ($key >= 5) break; // Limitar a 5 imágenes
            if (!empty($tmp_name)) {
                $filename = basename($_FILES['fotos']['name'][$key]);
                $destination = "uploads/" . $filename;
                if (move_uploaded_file($tmp_name, $destination)) {
                    $fotoPaths[] = $destination;
                } else {
                    echo "Error al mover el archivo: " . $filename . "<br>";
                }
            }
        }

        // Completar columnas de imágenes con valores vacíos si faltan
        while (count($fotoPaths) < 5) {
            $fotoPaths[] = ''; // Cadena vacía en lugar de NULL
        }

        // Insertar datos en la base de datos
        $sql = "INSERT INTO objeto (nombre, id_usuario, id_estado, id_categoria, imagen, imagen2, imagen3, imagen4, imagen5) 
                VALUES (:nombre, :id_usuario, :id_estado, :id_categoria, :imagen, :imagen2, :imagen3, :imagen4, :imagen5)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':id_usuario' => $id_usuario,
            ':id_estado' => $id_estado,
            ':id_categoria' => $id_categoria,
            ':imagen' => $fotoPaths[0],
            ':imagen2' => $fotoPaths[1],
            ':imagen3' => $fotoPaths[2],
            ':imagen4' => $fotoPaths[3],
            ':imagen5' => $fotoPaths[4],
        ]);

        echo "<p>Producto agregado correctamente.</p>";
        echo '<a href="Aproducto.html">Volver a Agregar Producto</a>';
    } else {
        echo "Algo ha fallado";
    }
} catch (PDOException $e) {
    die("Error en la BBDD: " . $e->getMessage());
}
?>
