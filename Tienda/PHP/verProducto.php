<?php
    // Conexión a la base de datos
    $host = 'localhost';
    $db = 'hackaton';
    $user = 'root';
    $pass = '';
    include '../../phpessentials/sesioncheck.php';
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
            --light-bg: rgb(248, 225, 163);
            --dark-green: #5c640f;
            --dark-brown: #131105;
        }

        body {
            background-color: var(--light-bg);
            color: var(--dark-brown);
            font-family: Bahnschrift, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            max-width: 90%;
            margin: 40px auto;
            padding: 20px;
            background: var(--text-light);
            border-radius: 12px;
            box-shadow: 0px 6px 20px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--primary-gold);
        }

        .carousel {
            flex: 1;
            max-width: 50%;
            border-radius: 12px;
            border: 2px solid var(--primary-gold);
            overflow: hidden;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.3);
        }

        .carousel-inner {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .carousel-item {
            flex: 0 0 100%;
        }

        .carousel-item img {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 10px;
        }

        .product-info {
            flex: 1;
            padding-left: 20px;
        }

        .product-info h1 {
            background: var(--primary-bg);
            color: var(--text-light);
            font-size: 28px;
            text-align: center;
            padding: 10px;
            border-radius: 10px;
        }

        .product-info h2 {
            color: var(--dark-green);
            font-size: 20px;
            margin-top: 10px;
        }

        .buttons {
            margin-top: 20px;
        }

        .buttons button {
            background-color: var(--primary-gold);
            color: white;
            border: none;
            padding: 10px 20px;
            margin-right: 10px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .buttons button:hover {
            background-color: var(--accent-color);
        }
    </style>
</head>
<body>
    <div class="container">
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
        </div>

        <div class="product-info">
            <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
            <h2>ID de Usuario: <?php echo htmlspecialchars($producto['id_usuario']); ?></h2>
            <h2>ID de Estado: <?php echo htmlspecialchars($producto['id_estado']); ?></h2>
            <h2>ID de Categoría: <?php echo htmlspecialchars($producto['id_categoria']); ?></h2>
            
            <div class="buttons">
                <button onclick="reportProduct()">Reportar</button>
                <button onclick="requestChat()">Solicitar Chat</button>
            </div>
        </div>
    </div>

    <script>
        function reportProduct() {
            alert("Producto reportado.");
        }
        function requestChat() {
            alert("Chat solicitado.");
        }
    </script>
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