<?php
$servidor = 'localhost';
$BBDD = 'hackaton';
$usuario = 'usuariophpdam';
$contra = 'Serpent7054';

try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$BBDD;charset=utf8", $usuario, $contra);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el filtro desde la URL (por defecto "todos")
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

    // Consultar productos según el filtro
    if ($filtro === 'todos') {
        $sql = "SELECT id, nombre, id_categoria, imagen, imagen2, imagen3, imagen4, imagen5 FROM objeto ORDER BY id";
    } else {
        $sql = "SELECT o.id, o.nombre, o.id_categoria, o.imagen, o.imagen2, o.imagen3, o.imagen4, o.imagen5
                FROM objeto o
                JOIN categorias_objetos c ON o.id_categoria = c.id
                WHERE c.nombre = :filtro
                ORDER BY o.id";
    }

    $stmt = $pdo->prepare($sql);
    if ($filtro !== 'todos') {
        $stmt->bindParam(':filtro', $filtro, PDO::PARAM_STR);
    }
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la BBDD: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crafter</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/cssT.css">
</head>
<body>
  <!-- Productos -->
  <section class="container mb-5">
    <h2 class="mb-4">Productos</h2>
    
    <!-- Botones de Filtro -->
    <div class="btn-group mb-3" role="group">
      <a href="productos.php?filtro=todos" class="btn btn-outline-secondary">Todos</a>
      <a href="productos.php?filtro=ropa" class="btn btn-outline-secondary">Ropa</a>
      <a href="productos.php?filtro=muebles" class="btn btn-outline-secondary">Muebles</a>
      <a href="productos.php?filtro=juguetes" class="btn btn-outline-secondary">Juguetes</a>
    </div>

    <!-- Grid de Productos -->
    <div class="row g-4">
      <?php if (count($productos) > 0): ?>
        <?php foreach ($productos as $producto): ?>
          <div class="col-md-4">
            <div class="card h-100">
              <!-- Carrusel de Imágenes -->
              <div id="carousel-<?php echo $producto['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                  <?php 
                  // Generar imágenes dinámicamente
                  $imagenes = [
                      $producto['imagen'],
                      $producto['imagen2'],
                      $producto['imagen3'],
                      $producto['imagen4'],
                      $producto['imagen5']
                  ];
                  foreach ($imagenes as $index => $ruta_imagen): 
                    if (!empty($ruta_imagen)): // Solo agregar imágenes válidas
                  ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                      <img src="<?php echo htmlspecialchars($ruta_imagen); ?>" class="d-block w-100" alt="Imagen de <?php echo htmlspecialchars($producto['nombre']); ?>">
                    </div>
                  <?php 
                    endif;
                  endforeach; 
                  ?>
                </div>
                <?php if (count(array_filter($imagenes)) > 1): ?>
                  <button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?php echo $producto['id']; ?>" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                  </button>
                  <button class="carousel-control-next" type="button" data-bs-target="#carousel-<?php echo $producto['id']; ?>" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Siguiente</span>
                  </button>
                <?php endif; ?>
              </div>

              <!-- Detalles del Producto -->
              <div class="card-body">
                <h5 class="card-title"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                <p class="card-text">Categoría ID: <?php echo htmlspecialchars($producto['id_categoria']); ?></p>
                <button class="btn btn-warning">Ver producto</button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-center">No hay productos disponibles en esta categoría.</p>
      <?php endif; ?>
    </div>
  </section>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
