<?php
include '../../phpessentials/sesioncheck.php';
include '../../phpessentials/obtener_categorias.php';
include '../../phpessentials/obtener_productos.php';
include '../../phpessentials/funciones.php'; // Ensure this file correctly defines $pdo

$status = $_GET['status'] ?? 'all';
$filtro = $_GET['filtro'] ?? null;
$busqueda = $_GET['busqueda'] ?? '';
$filtroTitulo = $_GET['filtroTitulo'] ?? null;
$estadoFilter = $_GET['estado'] ?? null; // Estado filter
$headingText = 'Listado de Productos';

// Fetch categories
foreach ($categorias as $categoria) {
    if ($categoria['id'] == $filtro) {
        $headingText = htmlspecialchars($categoria['nombre']);
        break;
    }
}

// Fetch states
$queryEstados = "SELECT * FROM estado";
$stmtEstados = $pdo->prepare($queryEstados);
$stmtEstados->execute();
$estados = $stmtEstados->fetchAll(PDO::FETCH_ASSOC);

// Build query dynamically
$query = "
    SELECT o.id, o.nombre, o.imagen, o.id_estado, c.Nombre AS categoria_nombre, e.tipo AS estado_tipo 
    FROM objeto o
    LEFT JOIN categorias_objetos c ON o.id_categoria = c.id
    LEFT JOIN estado e ON o.id_estado = e.id
    WHERE 1=1
";

$params = [];

// Apply filters
if ($filtro) {
    $query .= " AND o.id_categoria = :filtro";
    $params[':filtro'] = $filtro;
}

if ($estadoFilter) {
    $query .= " AND o.id_estado = :estadoFilter";
    $params[':estadoFilter'] = $estadoFilter;
}

if (!empty($busqueda)) {
    $query .= " AND o.nombre LIKE :busqueda";
    $params[':busqueda'] = '%' . $busqueda . '%';
}

$query .= " ORDER BY o.id DESC";

try {
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error en la consulta: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
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

        *{
            font-family: Bahnschrift, Haettenschweiler, "Arial Narrow Bold", sans-serif;

        }

        body {
            background-color: var(--light-bg);
        }

        nav{
            padding-left: 1%;
            background-color: var(--primary-bg);
            border-bottom: 1px solid var(--accent-color);
        }

        .containerOtro {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .boton {
            background-color: var(--dark-green);
            color: var(--text-light);
        }

        .boton:hover {
            background-color: var(--accent-color);
            color: var(--dark-brown);
        }

        .filtro {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
        }

        .filtro .d-flex {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filtro .form-select {
            padding: 10px;
            font-size: 16px;
            border: 1px solid var(--accent-color);
            border-radius: 4px;
            background-color: var(--text-light);
            color: var(--dark-brown);
            width: auto;
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23c39243%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 10px top 50%;
            background-size: 12px auto;
        }

        .filtro .form-select:hover {
            border-color: var(--primary-bg);
        }

        .filtro .form-select:focus {
            outline: none;
            border-color: var(--primary-bg);
            box-shadow: 0 0 5px rgba(149, 87, 46, 0.5);
        }

        .filtro .btn-primary {
            padding: 10px 20px;
            font-size: 16px;
            background-color: var(--primary-bg);
            color: var(--text-light);
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .filtro .btn-primary:hover {
            background-color: var(--accent-color);
        }

        .filtro .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 5px rgba(149, 87, 46, 0.5);
        }

        @media (max-width: 768px) {
            .filtro .d-flex {
                flex-direction: column;
                align-items: stretch;
            }

            .filtro .form-select,
            .filtro .btn-primary {
                width: 100%;
            }
        }

        .footer {
  background-color: var(--primary-bg);
  color: var(--text-light);
  padding: 3rem 0;
}

.footer-link {
  color: var(--primary-gold);
  text-decoration: none;
  transition: color 0.3s ease;
}

.footer-link:hover {
  color: var(--dark-green);
}

.footer hr {
  border-color: var(--accent-color);
  opacity: 0.2;
}

.footer .navbar-brand img {
  width: 100px;
  height: 50px;
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

    <div id="modalOverlayPerfil">
        <div id="modalContentPerfil">
            <button id="closeModal" onclick="cerrarModal()"><i class="fa-solid fa-xmark"></i></button>
            <iframe class="iframePerfil" src="http://localhost/HACKATHON/Tienda/PHP/Perfil.php" title="Contenido"></iframe>
        </div>
    </div>
    <h1 class="text-left display-2 " style="margin-left: 6.5em; margin-top: 2em; font-weight: bold;"><?php echo $headingText; ?></h1>
    <hr>
    <div class="container">
        <form method="GET" action="PaginaProducto.php">
            <input type="hidden" name="filtro" value="<?php echo htmlspecialchars($filtro); ?>">
            <div class="d-flex justify-content-end mb-3">
                <select id="estado" name="estado" class="form-select w-auto">
                    <option value="">Seleccionar Estado</option>
                    <?php foreach ($estados as $estado): ?>
                        <option value="<?php echo $estado['id']; ?>" <?php echo ($estadoFilter == $estado['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($estado['tipo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn boton ms-2"><i class="fa-solid fa-filter"></i> Filtrar</button>
            </div>
        </form>

        <div id="modalOverlayProducto" class="modal-overlayProducto">
            <div id="modalContentProducto" class="modal-contentProducto">
                <button id="closeModal" onclick="cerrarModalProducto()" class="close-modalProducto"><i class="fa-solid fa-xmark"></i></button>
                <iframe id="modalIframeProducto" title="Detalles del Producto"></iframe>
            </div>
        </div>

        <div class="row">
    <?php if (count($productos) > 0): ?>
        <?php foreach ($productos as $producto): ?>
            <div class="col-md-4 mb-3"> <!-- Aumenta el tamaño de las tarjetas -->
                <div class="card p-3" style="font-size: 1.2rem;"> <!-- Se agranda el texto -->
                    <img src="<?php echo htmlspecialchars($producto['imagen']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" style="height: 250px; width:250px; object-fit: cover;">
                    <div class="card-body">
                        <h2 class="card-title fs-5"><?php echo htmlspecialchars($producto['nombre']); ?></h2> <!-- Aumenta el tamaño del título -->
                        <p class="card-text fs-5">Categoría: <?php echo htmlspecialchars($producto['categoria_nombre']); ?></p>
                        <p class="card-text fs-5">Estado: <strong><?php echo htmlspecialchars($producto['estado_tipo']); ?></strong></p>
                        <a href="#" class="btn boton btn-lg" onclick="abrirModalProducto(<?php echo $producto['id']; ?>); return false;">
                            <i class="fa-solid fa-magnifying-glass"></i> Ver Producto
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-center fs-4">No se encontraron productos.</p> <!-- Texto más grande -->
    <?php endif; ?>
</div>

    </div>
    <footer class="footer justify-content-center">
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
                    <img src="../../img/logomadrid.jpg" alt="Logo Madrid" class="img-fluid" style="max-height: 80px;">
                    <img src="../../img/4-removebg-preview (1).png" alt="Hilan Logo" class="img-fluid" style="max-height: 80px;">
                    <img src="../../img/logouax.png" alt="Logo UAX" class="img-fluid" style="max-height: 140px;">
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