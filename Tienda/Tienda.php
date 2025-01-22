<?php
$servidor = 'localhost';
$BBDD = 'hackaton';
$usuario = 'usuariophpdam';
$contra = '1234qwerty..';

try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$BBDD;charset=utf8", $usuario, $contra);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT id, nombre, imagen, imagen2, imagen3, imagen4, imagen5 FROM objeto ORDER BY id";
    $stmt = $pdo->query($sql);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error en la BBDD: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zona de Productos</title>
    <link rel="stylesheet" href="Tienda.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-danger mb-4">Productos Disponibles</h2>
        <div class="row">
            <?php foreach ($productos as $producto): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <?php 
                        $imagen = !empty($producto['imagen']) ? $producto['imagen'] : 
                                 (!empty($producto['imagen2']) ? $producto['imagen2'] : 
                                 (!empty($producto['imagen3']) ? $producto['imagen3'] : 
                                 (!empty($producto['imagen4']) ? $producto['imagen4'] : 
                                 (!empty($producto['imagen5']) ? $producto['imagen5'] : 'default-image.jpg'))));
                        ?>
                        <img src="<?= htmlspecialchars($imagen) ?>" class="card-img-top" alt="<?= htmlspecialchars($producto['nombre']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($producto['nombre']) ?></h5>
                        
                            <button class="btn btn-danger">Solicitar Chat</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2024 Zona de Productos. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
