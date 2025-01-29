<?php
// Conexión a la base de datos
$host = 'localhost';
$db = 'hackaton';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Inicializar la variable $producto
$producto = null;

// Verificar si se proporcionó un ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $producto_id = intval($_GET['id']);
    
    try {
        // Consulta SQL para obtener la información del producto
        $sql = "SELECT 
            id, 
            nombre,
            id_usuario,
            id_estado,
            id_categoria,
            imagen, imagen2, imagen3, imagen4, imagen5
        FROM 
            objeto
        WHERE 
            id = ?";

        // Preparar y ejecutar la consulta
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $producto_id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        $producto = $result->fetch_assoc();

        $stmt->close();

    } catch (Exception $e) {
        // Manejo de errores
        $error = "Ha ocurrido un error al intentar cargar el producto: " . $e->getMessage();
    }
}

// Si se encontró el producto, mostrar el HTML
if ($producto) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detalles del Producto</title>
        <style>
            :root {
                --primary-bg: #95572e;
                --accent-color: #c39243;
                --text-light: #fefaef;
                --card-bg: #131105;
                --primary-gold: #c39243;
                --light-bg: #fefaef;
                --dark-green: #5c640f;
                --dark-brown: #131105;
            }

            body {
                background-color: var(--light-bg);
                color: var(--dark-brown);
                font-family: Bahnschrift, Haettenschweiler, "Arial Narrow Bold", sans-serif;
            }

            .container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 20px;
            }

            .product-info {
                background-color: var(--light-bg);
                border: 1px solid var(--primary-gold);
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 20px;
            }

            .product-info h1 {
                color: var(--primary-bg);
            }

            .carousel {
                position: relative;
                overflow: hidden;
                border-radius: 8px;
                border: 1px solid var(--primary-gold);
            }

            .carousel-inner {
                display: flex;
                transition: transform 0.5s ease;
            }

            .carousel-item {
                flex: 0 0 100%;
            }

            .carousel-item img {
                width: 100%;
                height: auto;
                object-fit: cover;
            }

            .carousel-control {
                position: absolute;
                top: 50%;
                transform: translateY(-50%);
                background-color: var(--primary-gold);
                color: var(--text-light);
                border: none;
                padding: 10px;
                cursor: pointer;
            }

            .carousel-control.prev {
                left: 10px;
            }

            .carousel-control.next {
                right: 10px;
            }

            .carousel-indicators {
                position: absolute;
                bottom: 10px;
                left: 50%;
                transform: translateX(-50%);
                display: flex;
                gap: 5px;
            }

            .indicator {
                width: 10px;
                height: 10px;
                background-color: var(--accent-color);
                border-radius: 50%;
                cursor: pointer;
            }

            .indicator.active {
                background-color: var(--primary-bg);
            }
        </style>
    </head>
    <body>
        <div class="container">
            <!-- Información del producto -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
                <p><strong>ID de Usuario:</strong> <?php echo htmlspecialchars($producto['id_usuario']); ?></p>
                <p><strong>ID de Estado:</strong> <?php echo htmlspecialchars($producto['id_estado']); ?></p>
                <p><strong>ID de Categoría:</strong> <?php echo htmlspecialchars($producto['id_categoria']); ?></p>
            </div>

            <!-- Carrusel de imágenes -->
            <div class="carousel">
                <div class="carousel-inner">
                    <?php 
                    $imagenes = [
                        $producto['imagen'],
                        $producto['imagen2'],
                        $producto['imagen3'],
                        $producto['imagen4'],
                        $producto['imagen5']
                    ];

                    foreach ($imagenes as $key => $imagen) {
                        if (!empty($imagen)) {
                            echo "<div class='carousel-item " . ($key === 0 ? 'active' : '') . "'>";
                            echo "<img src='" . htmlspecialchars($imagen) . "' alt='Imagen del producto'>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>

                <!-- Controles del carrusel -->
                <button class="carousel-control prev" onclick="moveSlide(-1)">&#10094;</button>
                <button class="carousel-control next" onclick="moveSlide(1)">&#10095;</button>

                <div class="carousel-indicators">
                    <?php
                    foreach ($imagenes as $key => $imagen) {
                        if (!empty($imagen)) {
                            echo "<span class='indicator " . ($key === 0 ? 'active' : '') . "' onclick='moveToSlide($key)'></span>";
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <script src="../js/js.js"></script> 
    </body>
    </html>
    <?php
} else {
    // Si no se encuentra el producto o hay un error
    if (isset($error)) {
        echo "<p>Error: " . htmlspecialchars($error) . "</p>";
    } else {
        echo "<p>Producto no encontrado o ID no proporcionado.</p>";
    }
}

// Cerrar la conexión
$conn->close();
?>