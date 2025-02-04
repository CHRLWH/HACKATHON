<?php
include '../../phpessentials/sesioncheck.php';
include '../../phpessentials/obtener_categorias.php';
include '../../phpessentials/obtener_productos.php';
include '../../phpessentials/funciones.php'; // Ensure this file correctly defines $pdo
$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$filtro = isset($_GET['filtro']) ? $_GET['filtro'] : null;
$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';
$filtroTitulo = isset($_GET['filtroTitulo']) ? $_GET['filtroTitulo'] : null;
$headingText = 'Listado de Productos';

// Variables for new filters
$estadoFilter = isset($_GET['estado']) ? $_GET['estado'] : null; // Estado filter
$categoryFilter = isset($_GET['filtro']) ? $_GET['filtro'] : null; // Category filter
$searchQuery = isset($_GET['busqueda']) ? $_GET['busqueda'] : ''; // Search query
$categoryTitle = isset($_GET['filtroTitulo']) ? $_GET['filtroTitulo'] : null; // Filter Title

// Default heading
if ($filtro) {
    // Look for the category name based on the selected filter ID
    foreach ($categorias as $categoria) {
        if ($categoria['id'] == $filtro) {
            $headingText = htmlspecialchars($categoria['nombre']);
            break;
        }
    }
}
// Base query
$query = "
    SELECT o.id, o.nombre, o.id_estado, o.imagen, c.Nombre AS category, e.tipo AS estado
    FROM objeto o
    INNER JOIN categorias_objetos c ON o.id = c.id
    INNER JOIN estado e ON o.id_estado = e.id
    WHERE c.id = :filtro
";

// Add the filter for 'estado' if it is set
if ($estadoFilter) {
    $query .= " AND o.id_estado = :estadoFilter";
}

// Modify the query for the search filter
if ($busqueda) {
    $query .= " AND o.nombre LIKE :busqueda";
}

$query .= " ORDER BY o.id DESC";

try {
    // Prepare and execute the query
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':filtro', $filtro, PDO::PARAM_INT);
    
    // Bind the estado filter if it's set
    if ($estadoFilter) {
        $stmt->bindParam(':estadoFilter', $estadoFilter, PDO::PARAM_INT);
    }

    if ($busqueda) {
        $searchTerm = '%' . $busqueda . '%';
        $stmt->bindParam(':busqueda', $searchTerm, PDO::PARAM_STR);
    }

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Productos</title>
    <link rel="icon" type="image/x-icon" href="../../img/1-2feccb09.ico">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="../css/csst.css">

    <style>
       :root {
  --primary-bg: #95572e; /* Brown */
  --accent-color: #c39243; /* Gold */
  --text-light: #fefaef; /* Light beige */
  --card-bg: #131105; /* Dark brown */
  --primary-gold: #c39243; /* Gold */
  --light-bg: #fefaef; /* Light beige */
  --dark-green: #5c640f; /* Dark green */
  --dark-brown: #131105; /* Dark brown */
}

body {
  font-family: Bahnschrift, sans-serif;
  background-color: var(--light-bg);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  font-size: 24px;
  margin-bottom: 30px;
  text-align: center;
  color: var(--primary-bg);
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  justify-content: center;
}

.product-card {
  background: var(--text-light);
  border: 1px solid var(--accent-color);
  border-radius: 8px;
  overflow: hidden;
  padding: 0;
  box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s;
}

.product-card:hover {
  transform: scale(1.05);
}

.product-image {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.product-info {
  padding: 15px;
  text-align: center;
}

.product-name {
  font-size: 18px;
  margin: 0 0 10px 0;
  color: var(--primary-bg);
}

.product-category {
  color: var(--dark-green);
  font-size: 14px;
  margin: 5px 0;
}

.product-status {
  margin: 10px 0;
  font-size: 14px;
  color: var(--dark-brown);
}

.status-label {
  font-weight: bold;
  color: var(--primary-bg);
}

.view-button {
  display: block;
  width: 80%;
  margin: 10px auto;
  background-color: var(--primary-bg);
  color: var(--text-light);
  padding: 10px;
  text-decoration: none;
  border-radius: 4px;
  font-size: 14px;
  text-align: center;
  border: none;
  cursor: pointer;
  transition: background-color 0.3s;
}

.view-button:hover {
  background-color: var(--accent-color);
}

@media (max-width: 900px) {
  .products-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 600px) {
  .products-grid {
    grid-template-columns: repeat(1, 1fr);
  }
}

footer {
  margin-top: 40em;
  background-color: var(--dark-brown);
  color: var(--text-light);
  padding: 20px 0;
}
.content-wrapper {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    position: relative;
}

.filter-container {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-bottom: 20px;
}

#estado {
    padding: 10px;
    font-size: 16px;
    border: 1px solid var(--accent-color);
    border-radius: 4px;
    background-color: var(--text-light);
    color: var(--dark-brown);
    width: 200px;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23c39243%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
    background-repeat: no-repeat;
    background-position: right 10px top 50%;
    background-size: 12px auto;
}

#estado:hover {
    border-color: var(--primary-bg);
}

#estado:focus {
    outline: none;
    border-color: var(--primary-bg);
    box-shadow: 0 0 5px rgba(149, 87, 46, 0.5);
}

button[type="submit"] {
    padding: 10px 20px;
    font-size: 16px;
    background-color: var(--primary-bg);
    color: var(--text-light);
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button[type="submit"]:hover {
    background-color: var(--accent-color);
}

button[type="submit"]:focus {
    outline: none;
    box-shadow: 0 0 5px rgba(149, 87, 46, 0.5);
}

@media (max-width: 768px) {
    .filter-container {
        flex-direction: column;
        align-items: stretch;
    }

    #estado, button[type="submit"] {
        width: 100%;
    }
}
    </style>
</head>
<body>
<header class="shop-header" style="font-size:20px;">
        <nav class="navbar navbar-expand-lg navbar-dark py-3">
            <div class="container">
                <a class="navbar-brand" href="http://localhost/HACKATHON/Tienda/PHP/TiendaV2.php">
                    <img src="../../img/4-removebg-preview (1).png" alt="Logo" style="width: 150px; height: 70px;">
                </a>

                <!-- Botón de menú para móviles -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div id="modalOverlayPerfil">
                            <div id="modalContentPerfil">
                                <button id="closeModal" onclick="cerrarModal()"><i class="fa-solid fa-xmark"></i></button>
                                <iframe class="iframePerfil" src="http://localhost/HACKATHON/Tienda/PHP/Perfil.php" title="Contenido"></iframe>
                            </div>
                        </div>
                <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
    <ul class="navbar-nav">
        <?php foreach ($categorias as $categoria): ?>
            <li class="nav-item">
                <a href="PaginaProducto.php?filtro=<?php echo $categoria['id']; ?><?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?>"
                   class="nav-link <?php echo isset($filtro) && $filtro == $categoria['id'] ? 'active' : ''; ?>"
                   style="transition: 0.3s;">
                    <?php echo htmlspecialchars($categoria['nombre']); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
                <!-- Botones alineados a la derecha -->
                <div class="d-flex ms-auto">
    <!-- Botón de usuario -->
                    <button onclick="abrirModal()" class="btn buttonBackground btn-link text-light" type="button" title="Perfil" style="width: 60px; height: 60px;">
                    <i class="fa-solid fa-user fa-lg"></i>
                    </button>
                </div>
                    
                <div class="d-flex ms-auto">

                    <!-- Botón de salir -->
                    <button onclick="cerrarSesion()" class="btn buttonBackground btn-link text-light" type="submit" title="CerrarSesion" style="margin-left: 10px; width: 60px; height: 60px;">
                        <i class="fa-solid fa-right-to-bracket fa-lg"></i>
                    </button>
                </div>
            </div>
        </nav>
    </header>
    <div class="container">
    <h1 class="fs-1 text-dark text-xl-start" style="margin-top:1em; margin-bottom:1em;"><?php echo $headingText; ?></h1>
    <hr class="dark">
    <form method="GET" action="">
    <div class="content-wrapper">
    <div class="filter-container">
        <select name="estado" id="estado">
            <option value="">Seleccione un estado</option>
            <?php
            // Retrieve the list of states (you need to fetch them from the 'estado' table)
            try {
                $stmt = $pdo->prepare("SELECT id, tipo FROM estado ORDER BY tipo");
                $stmt->execute();
                $estados = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($estados as $estado) {
                    // Check if the current estado matches the selected filter
                    $selected = ($estado['id'] == $estadoFilter) ? 'selected' : '';
                    echo "<option value='" . $estado['id'] . "' $selected>" . htmlspecialchars($estado['tipo']) . "</option>";
                }
            } catch (PDOException $e) {
                echo "<option value=''>Error al obtener estados</option>";
            }
            ?>
        </select>
        <button type="submit">Filtrar</button>
    </div>
</div>
</form>
    <div class="container mt-4">
                <h1 class="mb-4">Listado de Productos</h1>
                <div class="row g-4">
                    <?php if (count($productos) > 0): ?>
                        <!-- Modal global (debe estar FUERA del foreach) -->
                        <div id="modalOverlayProducto" class="modal-overlayProducto">
                            <div id="modalContentProducto" class="modal-contentProducto">
                                <button id="closeModal" onclick="cerrarModalProducto()" class="close-modalProducto"><i class="fa-solid fa-xmark"></i></button>
                                <iframe id="modalIframeProducto" title="Detalles del Producto"></iframe>
                            </div>
                        </div>

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
                                        <button onclick="abrirModalProducto(<?php echo $producto['id']; ?>)" class="btn-custom">
                                        <i class="fa-solid fa-magnifying-glass"></i> Ver producto
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center">No se encontraron productos.</p>
                    <?php endif; ?>
                </div>
            </div>
    <footer class="footer">
            <div class="container">
                <hr class="mb-5">
                <div class="row g-4 justify-content-center text-light text-center mb-5">
                    <div class="col-md-4">
                        <a href="http://localhost/HACKATHON/Footer/privacidad.html" class="footer-link text-light">Política de privacidad</a>
                    </div>

                    <div class="col-md-4">
                        <a href="http://localhost/HACKATHON/Footer/Terminos.html" class="footer-link text-light">Términos y condiciones</a>
                    </div>

                    <div class="col-md-4">
                        <a href="http://localhost/HACKATHON/Footer/QyA.html" class="footer-link text-light">QyA</a>
                    </div>
                </div>

                <div class="d-flex justify-content-around align-items-center">
                    <img src="../img/logomadrid.jpg" alt="Logo Madrid" class="img-fluid" style="max-height: 80px;">
                    <img src="../img/4-removebg-preview (1).png" alt="Hilan Logo" class="img-fluid" style="max-height: 80px;">
                    <img src="../img/logouax.png" alt="Logo UAX" class="img-fluid" style="max-height: 140px;">
                </div>
            </div>
        </footer>
        <script>
            // Función para abrir el modal
            function abrirModal() {
                document.getElementById('modalOverlayPerfil').style.display = 'flex';
            }

            // Función para cerrar el modal
            function cerrarModal() {
                document.getElementById('modalOverlayPerfil').style.display = 'none';
            }

            // Función para abrir el modal del producto
             // Función para abrir el modal del producto
             function abrirModalProducto(productoId) {
                let modal = document.getElementById('modalOverlayProducto');  // Selecciona el único modal
                let iframe = document.getElementById('modalIframeProducto');

                modal.style.display = 'flex';
                iframe.src = `verProducto.php?id=${productoId}`;  // Carga el producto seleccionado
            }

            // Función para cerrar el modal del producto
            function cerrarModalProducto() {
                let modal = document.getElementById('modalOverlayProducto');
                let iframe = document.getElementById('modalIframeProducto');

                modal.style.display = 'none';
                iframe.src = '';  // Limpia el iframe para evitar recargas innecesarias
            }


            function cerrarSesion() {
                fetch('../../phpessentials/logout.php', { method: 'POST' }) 
                .then(() => window.location.href = "http://localhost/HACKATHON/Login/Login.php") 
                .catch(error => console.error('Error:', error));
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
