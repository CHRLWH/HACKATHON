<?php
$servidor = 'localhost';
$BBDD = 'hackaton';
$usuario = 'root';
$contra = '';

try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$BBDD;charset=utf8", $usuario, $contra);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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

    // Obtener el filtro desde la URL (por defecto "todos")
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 'todos';

    // Obtener el término de búsqueda
    $busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

    // Consultar productos según el filtro y la búsqueda
    $sql = "SELECT o.*, c.nombre as categoria_nombre, e.tipo as estado_tipo 
            FROM objeto o 
            LEFT JOIN categorias_objetos c ON o.id_categoria = c.id 
            LEFT JOIN estado e ON o.id_estado = e.id 
            WHERE 1=1";
    $params = array();

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
    die("Error en la BBDD: " . $e->getMessage());
}

// Función para obtener la clase de estado
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
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hilan-Tienda</title>
    <link href="../LoginCss/Login.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../../img/1-2feccb09.ico">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/cssT.css">
</head>
<body>
    <!-- El header permanece igual -->
    <header class="shop-header">
        <nav class="navbar navbar-expand-lg navbar-dark py-3">
            <div class="container">
                <a class="navbar-brand" href="#">  <img src="../../img/4-removebg-preview (1).png" alt="Logo" style="width: 100px; height: 50px;"></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Tienda</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Muebles</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Juguetes</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Ropa</a></li>
                    </ul>
                        <button onclick="abrirModal()" class="btn buttonBackground btn-link text-light" type="submit" title="Buscar">
                            <img src="../../img/icons8-customer-32.png" alt="Buscar" width="24" height="24">
                        </button>
                        <div id="modalOverlay">
                            <div id="modalContent">
                                <!-- Botón para cerrar -->
                                <button id="closeModal" onclick="cerrarModal()">X</button>

                                <!-- Contenido dentro del modal -->
                                <iframe src="http://localhost/HACKATHON/ProductoTiendaIvan/PHP/Perfil.php" title="Contenido"></iframe>
                            </div>
                        </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- El carrusel permanece igual -->
    <section class="hero-carousel mb-5">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="../../img/21-habitacion-bebe-luxor-muebles-ros.jpg" class="d-block w-100" alt="Handmade Gifts">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="display-4 mb-4">Muebles</h1>
                        <div class="input-group w-75 mx-auto">
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="../../img/Captura de pantalla 2024-12-15 212004.png" class="d-block w-100" alt="Artisan Crafts">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="display-4 mb-4">Juguetes</h1>
                        <div class="input-group w-75 mx-auto">
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="../../img/maxresdefault.jpg" class="d-block w-100" alt="Support Local Creators">
                    <div class="carousel-caption d-none d-md-block">
                        <h1 class="display-4 mb-4">Ropa</h1>
                        <div class="input-group w-75 mx-auto">
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>

    <!-- Productos -->
    <section class="container mb-5">
        <h2 class="mb-4">Productos</h2>
        <form class="d-flex" action="TiendaV2.php" method="GET">
                        <input type="search" name="busqueda" class="form-control search-bar-headder me-2" placeholder="Buscar productos" value="<?php echo htmlspecialchars($busqueda); ?>">
                        <input type="hidden" name="filtro" value="<?php echo htmlspecialchars($filtro); ?>">
                    </form>
        <!-- Botones de Filtro -->
         <hr>
        <div class="btn-group mb-3" role="group">
            <a href="TiendaV2.php?filtro=todos<?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?>" class="btn btn-outline-secondary <?php echo $filtro === 'todos' ? 'active' : ''; ?>">Todos</a>
            <?php foreach ($categorias as $categoria): ?>
                <a href="TiendaV2.php?filtro=<?php echo $categoria['id'] . (!empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''); ?>" 
                   class="btn btn-outline-secondary <?php echo $filtro == $categoria['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Grid de Productos -->
        <div class="container mt-4">
            <h1 class="mb-4">Listado de Productos</h1>
            <div class="row g-4">
                <?php if (count($productos) > 0): ?>
                    <?php foreach ($productos as $producto): ?>
                        <div class="col-md-4">
                            <div class="card h-100">
                                <!-- Carrusel de Imágenes -->
                                <div id="carousel-<?php echo $producto['id']; ?>" class="carousel slide" data-bs-ride="carousel">
                                    <div class="carousel-inner">
                                        <?php 
                                        $imagenes = [
                                            $producto['imagen'],
                                            $producto['imagen2'],
                                            $producto['imagen3'],
                                            $producto['imagen4'],
                                            $producto['imagen5']
                                        ];
                                        foreach ($imagenes as $index => $ruta_imagen): 
                                            if (!empty($ruta_imagen)):
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
                                    <p class="card-text">Categoría: <?php echo htmlspecialchars($producto['categoria_nombre']); ?></p>
                                    <p class="card-text">
                                        Estado: 
                                        <span class="<?php echo getEstadoClass($producto['estado_tipo']); ?>">
                                            <?php echo htmlspecialchars($producto['estado_tipo']); ?>
                                        </span>
                                    </p>
                                    <button onclick="abrirModalProducto(<?php echo $producto['id']; ?>)" class="btn btn-warning">Ver producto</button>                                
                                </div>
                                <div id="modalOverlay" class="modal-overlay">
                                <div id="modalContent" class="modal-content">
                                    <button id="closeModal" onclick="cerrarModalProducto()" class="close-modal">X</button>
                                    <iframe src="http://localhost/HACKATHON/ProductoTiendaIvan/PHP/verProducto.php" id="modalIframe" src="" title="Detalles del Producto"></iframe>
                                </div>
                            </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">No se encontraron productos.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <hr class="mb-5">
            <div class="row g-4 justify-content-center text-light text-center mb-5">
                <div class="col-md-4">
                    <a href="../../privacidad.html" class="footer-link">Política de privacidad</a>
                </div>
                <div class="col-md-4">
                    <a href="../../Terminos.html" class="footer-link">Términos y condiciones</a>
                </div>
                <div class="col-md-4">
                    <a href="../../QyA.html" class="footer-link">QyA</a>
                </div>
            </div>
            <div class="d-flex justify-content-around align-items-center">
            <img src="../../img/logomadrid.jpg" alt="Logo Madrid" class="img-fluid" style="max-height: 100px;">
            
            <img src="../../img/4-removebg-preview (1).png" alt="Hilan Logo" class="img-fluid" style="max-height: 80px;">
            
            <img src="../../img/logouax.png" alt="Logo UAX" class="img-fluid" style="max-height: 100px;">
        </div>
        </div>
    </footer>
    <script>
         // Función para abrir el modal
         function abrirModal() {
            document.getElementById('modalOverlay').style.display = 'flex';
        }

        // Función para cerrar el modal
        function cerrarModal() {
            document.getElementById('modalOverlay').style.display = 'none';
        }
    </script>

    <script>
        function abrirModalProducto(productoId) {
            document.getElementById('modalOverlay').style.display = 'flex';
            document.getElementById('modalIframe').src = `verProducto.php?id=${productoId}`;
        }

        function cerrarModalProducto() {
            document.getElementById('modalOverlay').style.display = 'none';
            document.getElementById('modalIframe').src = 'http://localhost/HACKATHON/ProductoTiendaIvan/PHP/verProducto.php';
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

