<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil de Usuario</title>
        <link rel="stylesheet" href="../css/styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        

    </head>

    <body>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Configuración de la base de datos
                $host = 'localhost';
                $db = 'hackaton';
                $user = 'root';
                $pass = '';

                $conn = new mysqli($host, $user, $pass, $db);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $nombre = $_POST['product-name'];
                $categoria = $_POST['category'];
                $estado = $_POST['condition'];
                $imagenes = []; // Arreglo para almacenar las rutas de las imágenes

                $uploadDir = 'uploads/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true); 
                }

                foreach ($_FILES['product-image']['tmp_name'] as $key => $tmpName) {
                    $fileName = basename($_FILES['product-image']['name'][$key]);
                    $filePath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpName, $filePath)) {
                        $imagenes[] = $filePath;
                    }
                }

                // Asegurar que tengamos al menos 5 valores definidos en el arreglo
                for ($i = count($imagenes); $i < 5; $i++) {
                    $imagenes[] = null; // Rellenar con `null` si faltan imágenes
                }

                // Asignar imágenes a variables individuales
                $imagen1 = $imagenes[0] ?? null;
                $imagen2 = $imagenes[1] ?? null;
                $imagen3 = $imagenes[2] ?? null;
                $imagen4 = $imagenes[3] ?? null;
                $imagen5 = $imagenes[4] ?? null;

                // Sanitización de datos para evitar inyecciones SQL
                $nombre = $conn->real_escape_string($nombre);
                $categoria = $conn->real_escape_string($categoria);
                $estado = $conn->real_escape_string($estado);
                $imagen1 = $conn->real_escape_string($imagen1);
                $imagen2 = $conn->real_escape_string($imagen2);
                $imagen3 = $conn->real_escape_string($imagen3);
                $imagen4 = $conn->real_escape_string($imagen4);
                $imagen5 = $conn->real_escape_string($imagen5);

                // Crear la consulta SQL sin bind_param
                $sql = "INSERT INTO objeto (id, nombre, id_usuario, id_estado, id_categoria, imagen, imagen2, imagen3, imagen4, imagen5, validado)
                        VALUES (NULL, '$nombre', 1, '$estado', '$categoria', '$imagen1', '$imagen2', '$imagen3', '$imagen4', '$imagen5', 0)";

                // Ejecutar la consulta
                if ($conn->query($sql) === TRUE) {
                    echo "<div class='alert alert-success mt-3'>Objeto agregado</div>";
                } else {
                    echo "<div class='alert alert-danger mt-3'>Se ha producido un error </div>";
                }
                // Cerrar la conexión
                $conn->close();
            }
        ?>

    <div class="profile-container" style="max-width: 900px; margin: 30px auto; background-color: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 10px; overflow: hidden;">
        <h1 style="background-color: #95572e; color: #fefaef; padding: 20px; margin: 0; text-align: center;">Perfil de Usuario</h1>
        <div class="content">
            <nav class="sidebar" style="background-color: #ffdab9; width: 30%; padding: 20px;">
                <ul style="list-style-type: none; padding: 0;">
                    <li class="nav-item" onclick="showSection('chats')" style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; padding: 10px; background-color: #95572e; color: #fefaef; margin-bottom: 10px; text-align: left; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;">
                        <i class="fas fa-comments" style="min-width: 20px; text-align: center;"></i> Chats Activos
                    </li>
                    <li class="nav-item" onclick="showSection('especificaciones')" style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; padding: 10px; background-color: #95572e; color: #fefaef; margin-bottom: 10px; text-align: left; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;">
                        <i class="fas fa-user-cog" style="min-width: 20px; text-align: center;"></i> Especificaciones del Usuario
                    </li>
                    <li class="nav-item" onclick="showSection('aniadir-objeto')" style="display: flex; align-items: center; justify-content: flex-start; gap: 10px; padding: 10px; background-color: #95572e; color: #fefaef; margin-bottom: 10px; text-align: left; border-radius: 5px; cursor: pointer; transition: background-color 0.3s;">
                        <i class="fas fa-plus-circle" style="min-width: 20px; text-align: center;"></i> Añadir Objeto
                    </li>
                </ul>
            </nav>

                <section class="main-section" id="main-section">
                    
                    <!-- --------------- -->
                    <!-- --------------- -->
                    <!-- Subir Productos -->
                    <!-- --------------- -->
                    <!-- --------------- -->

                    <div id="aniadir-objeto" class="section-content">
                        

                        <div class="container my-5">
                            <div class="row justify-content-center">
                                <div class="col-md-6">
                                    <form action="#" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow-sm bg-white">
                                        <div class="mb-3">
                                            <label for="product-name" class="form-label fw-bold">
                                                Nombre del Producto
                                            </label>

                                            <input type="text" id="product-name" name="product-name" class="form-control" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="category" class="form-label fw-bold">
                                                Categoría
                                            </label>

                                            <select id="category" name="category" class="form-select" required>
                                                <option value="1">Prendas</option>
                                                <option value="2">Muebles</option>
                                                <option value="3">Electrónica</option>
                                                <option value="4">Juguetes</option>
                                                <option value="5">Deportes</option>
                                                <option value="6">Hogar</option>
                                                <option value="7">Herramientas</option>
                                                <option value="8">Libros</option>
                                                <option value="9">Juguete</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="condition" class="form-label fw-bold">
                                                Estado
                                            </label>

                                            <select id="condition" name="condition" class="form-select" required>
                                                <option value="1">Excelente</option>
                                                <option value="2">Bien</option>
                                                <option value="3">Defectuoso</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                Imágenes del Producto
                                            </label>

                                            <input type="file" id="product-image" name="product-image[]" class="form-control d-none" accept="image/*" multiple aria-label="Cargar imágenes">
                                            
                                            <div class="drop-zone" id="drop-zone" aria-label="Zona de carga de imágenes">
                                                Arrastra y suelta archivos aquí o haz clic para seleccionar.
                                            </div>

                                            <div class="preview-container" id="preview-container"></div>
                                        </div>

                                        <button type="submit" class="btn per  danger w-100">
                                            Agregar Producto
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ---------------- -->
                    <!-- ---------------- -->
                    <!-- CHAT DE USUARIOS -->
                    <!-- ---------------- -->
                    <!-- ---------------- -->
                    <div id="chats" class="section-content">
    <!-- Chat 1 -->
    <div class="chat-container">
        <div class="accordion">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="avatar">
                    <p>👜</p>
                </div>
                <div class="product-name">
                    Bolso de Cuero
                </div>
                <div class="toggle-icon">+</div>
            </div>
            <div class="accordion-content">
                <div class="chatbox">
                    <div class="middle" id="chat-messages-1">
                        <!-- Los mensajes del chat se cargarán aquí dinámicamente -->
                    </div>
                    <div class="bottom-bar">
                        <form id="chat-form-1" method="POST" action="chat.php">
                            <input type="text" name="message" id="message-input-1" placeholder="Escribe un mensaje..." required>
                            <button type="submit">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chat 2 (ejemplo para múltiples chats) -->
    <div class="chat-container">
        <div class="accordion">
            <div class="accordion-header" onclick="toggleAccordion(this)">
                <div class="avatar">
                    <p>👟</p>
                </div>
                <div class="product-name">
                    Zapatillas Deportivas
                </div>
                <div class="toggle-icon"></div>
            </div>
            <div class="accordion-content">
                <div class="chatbox">
                    <div class="middle" id="chat-messages-2">
                        <!-- Los mensajes del chat se cargarán aquí dinámicamente -->
                    </div>
                    <div class="bottom-bar">
                        <form id="chat-form-2" method="POST" action="chat.php">
                            <input type="text" name="message" id="message-input-2" placeholder="Escribe un mensaje..." required>
                            <button type="submit">Enviar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                    <!-- ------------------ -->
                    <!-- ------------------ -->
                    <!-- Cambiar Contraseña -->
                    <!-- ------------------ -->
                    <!-- ------------------ -->

                    <div id="especificaciones" class="section-content">
                        
                        <div class="user-details">
                            <div class="field">
                                <label for="name">Nombre:</label>
                                <input type="text" id="name" value="Juan Pérez" disabled>
                            </div>
                        
                            <div class="field">
                                <label for="email">Correo Electrónico:</label>
                                <input type="email" id="email" value="juan.perez@example.com">
                            </div>
                        

                        </div>
                    </div>
                </section>
            </div>
        </div>
        <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>

        <script src="../js/script.js"></script>
    </body>
</html>