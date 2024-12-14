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
        $descripcion = $_POST['descripcionProducto'];

        if (empty($nombre) || empty($descripcion)) {
            die("No has rellenado los campos.");
        }

        $sql = "INSERT INTO productos (nombre, descripcion, fotosURL) VALUES (:nombre, :descripcion, NULL)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
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
