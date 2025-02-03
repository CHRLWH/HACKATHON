<?php
include '../../phpessentials/sesioncheck.php';
include '../../phpessentials/obtener_categorias.php';
include '../../phpessentials/obtener_productos.php';
include '../../phpessentials/funciones.php';
?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hilan-Tienda</title>
        <link rel="icon" type="image/x-icon" href="../../img/1-2feccb09.ico">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" href="../css/csst.css">
    </head>



    <body>
    <!-- Modal de consentimiento de cookies -->
        <header class="shop-header">
            <nav class="navbar navbar-expand-lg navbar-dark py-3">
                <div class="container">
                    <a class="navbar-brand" href="#">
                        <img src="../../img/4-removebg-preview (1).png" alt="Logo" style="width: 100px; height: 50px;">
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <!-- Los iconos de login y cerrar sesión se quedan en la derecha -->
                        </ul>

                        <!-- Botón de usuario -->
                        <button onclick="abrirModal()" class="btn buttonBackground btn-link text-light" type="submit" title="Perfil">
                            <img src="../../img/icons8-customer-32.png" alt="Perfil" width="24" height="24">
                        </button>

                        <!-- Botón de salir -->
                        <button onclick="cerrarSesion()" class="btn buttonBackground btn-link text-light" type="submit" title="CerrarSesion" style="margin-left: 10px">
                            <i class="fa-solid fa-right-to-bracket"></i>
                        </button>

                        <!-- Modal para el perfil -->
                        <div id="modalOverlayPerfil">
                            <div id="modalContentPerfil">
                                <button id="closeModal" onclick="cerrarModal()"><i class="fa-solid fa-xmark"></i></button>
                                <iframe class="iframePerfil" src="http://localhost/HACKATHON/Tienda/PHP/Perfil.php" title="Contenido"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

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
                <a href="TiendaV2.php?filtro=todos<?php echo !empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''; ?>" class="btn btn-outline-secondary <?php echo $filtro === 'todos' ? 'active' : ''; ?>">
                    Todos
                </a>

                <?php foreach ($categorias as $categoria): ?>
                    <a href="TiendaV2.php?filtro=<?php echo $categoria['id'] . (!empty($busqueda) ? '&busqueda=' . urlencode($busqueda) : ''); ?>" 
                    class="btn btn-outline-secondary <?php echo $filtro == $categoria['id'] ? 'active' : ''; ?>">
                      <i class="fa-solid fa-filter"></i>  <?php echo htmlspecialchars($categoria['nombre']); ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="container mt-4">
            <!-- Grid de Productos -->
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
        </section>

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
                    <img src="../../img/Imagen de WhatsApp 2025-02-03 a las 00.35.34_f954b894.jpg" alt="Logo Madrid" class="img-fluid" style="max-height: 100px;">
                    <img src="../../img/4-removebg-preview (1).png" alt="Hilan Logo" class="img-fluid" style="max-height: 80px;">
                    <img src="../../img/Imagen de WhatsApp 2025-02-03 a las 00.35.34_66f17eaf.jpg" alt="Logo UAX" class="img-fluid" style="max-height: 100px;">
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