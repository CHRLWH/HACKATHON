<?php
// Conexión a la base de datos
require_once 'funciones/conexionBD.php'; 

// Verificar si el id del producto está presente en la URL
if (isset($_GET['id'])) {
    $producto_id = $_GET['id'];

    // Consulta SQL para obtener la información del producto, el usuario, el estado y la categoría
    $sql = "SELECT 
                o.id AS id_producto, 
                o.nombre AS producto_nombre,
                u.id AS usuario_nombre,
                e.tipo AS estado_producto,
                c.nombre AS categoria_producto,
                o.imagen, o.imagen2, o.imagen3, o.imagen4, o.imagen5
            FROM 
                objeto o
            JOIN 
                usuario u ON o.id_usuario = u.id
            JOIN 
                estado e ON o.id_estado = e.id
            JOIN 
                categorias_objetos c ON o.id_categoria = c.id
            WHERE 
                o.id = :id_producto";


    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_producto', $producto_id, PDO::PARAM_INT);
    $stmt->execute();

    $producto = $stmt->fetch();

    // Verificar si el producto fue encontrado
    if ($producto) {
        // Mostrar detalles del producto
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Detalles del Producto</title>
            
            <link href="css/styleVerProducto.css" rel="stylesheet">
        </head>
        <body>
            
            <div class="container">
                <!-- Información del producto -->
                <div class="product-info">
                    <h1><?php echo $producto['producto_nombre']; ?></h1>
                    <p><strong>Subido por:</strong> <?php echo $producto['usuario_nombre']; ?></p>
                    <p><strong>Estado:</strong> <?php echo $producto['estado_producto'] == 1 ? 'Excelente' : 'Usado'; ?></p>
                    <p><strong>Categoría:</strong> <?php echo $producto['categoria_producto']; ?></p>
                </div>

                <!-- Carrusel de imágenes -->
                <div class="carousel">
                    <div class="carousel-inner">
                        <!-- Se agregan imágenes desde PHP -->
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
                                echo "<div class='carousel-item " . ($key == 0 ? 'active' : '') . "'>";
                                echo "<img src='" . $imagen . "' alt='Imagen del producto'>";
                                echo "</div>";
                            }
                        }
                        ?>
                    </div>

                    <!-- Controles del carrusel -->
                    <button class="carousel-control prev" onclick="moveSlide(-1)">&#10094;</button>
                    <button class="carousel-control next" onclick="moveSlide(1)">&#10095;</button>

                    <div class="carousel-indicators"></div>
                </div>
            </div>

            <div class="container-atras">
                <!-- Enlaces para validar o marcar como no apto -->
                <a href="funciones/actualizarProducto.php?id=<?php echo $producto['id_producto']; ?>&estado=1" class="validar">Validar producto</a>

                <a href="funciones/actualizarProducto.php?id=<?php echo $producto['id_producto']; ?>&estado=0" class="validar">Producto no apto</a>


                <!-- Botón para regresar a la gestión de productos -->
                <a href="adminPanel.php" class="back-button">Volver a la gestión de productos</a>
            </div>
        

            <script src="javascript/scriptVerProducto.js"></script> 
        </body>
        </html>
        <?php
    } else {
        // Si no se encuentra el producto
        echo "<p>Producto no encontrado.</p>";
    }
} else {
    // Si no hay ID en la URL
    echo "<p>Producto no especificado.</p>";
}
?>