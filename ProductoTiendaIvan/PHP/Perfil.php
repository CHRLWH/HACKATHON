<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil de Usuario</title>
        <link rel="stylesheet" href="styles.css">
    </head>

    <body>
        <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Configuración de la base de datos
                $host = 'localhost';
                $db = 'hackaton';
                $user = 'adminphp';
                $pass = '2002';

                $conn = new mysqli($host, $user, $pass, $db);

                if ($conn->connect_error) {
                    die("Error de conexión: " . $conn->connect_error);
                }

                $nombre = $_POST['product-name'];
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
                $imagen1 = $conn->real_escape_string($imagen1);
                $imagen2 = $conn->real_escape_string($imagen2);
                $imagen3 = $conn->real_escape_string($imagen3);
                $imagen4 = $conn->real_escape_string($imagen4);
                $imagen5 = $conn->real_escape_string($imagen5);

                // Crear la consulta SQL sin bind_param
                $sql = "INSERT INTO objeto (id, nombre, id_usuario, id_estado, id_categoria, imagen, imagen2, imagen3, imagen4, imagen5, validado)
                        VALUES (NULL, '$nombre', 1, 1, 1, '$imagen1', '$imagen2', '$imagen3', '$imagen4', '$imagen5', 0)";

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

        <div class="profile-container">
            <h1>Perfil de Usuario</h1>
            <div class="content">
                <nav class="sidebar">
                        <li class="nav-item" onclick="showSection('chats')">
                            Chats Activos
                        </li>

                        <li class="nav-item" onclick="showSection('especificaciones')">
                            Especificaciones del Usuario
                        </li>

                        <li class="nav-item" onclick="showSection('aniadir-objeto')">
                            Añadir Objeto
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
                        <h2>Añadir un nuevo producto</h2>

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
                                            <label class="form-label fw-bold">
                                                Imágenes del Producto
                                            </label>

                                            <input type="file" id="product-image" name="product-image[]" class="form-control d-none" accept="image/*" multiple aria-label="Cargar imágenes">
                                            
                                            <div class="drop-zone" id="drop-zone" aria-label="Zona de carga de imágenes">
                                                Arrastra y suelta archivos aquí o haz clic para seleccionar.
                                            </div>

                                            <div class="preview-container" id="preview-container"></div>
                                        </div>

                                        <button type="submit" class="btn per    danger w-100">
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
                        <h2>Chats Activos</h2>
                        <div class="chat-container">
                            <div class="chatbox">
                                <div class="top-bar">
                                    <div class="avatar">
                                        <p>👜</p>
                                    </div>
                                    <div class="product-name">
                                        Bolso de Cuero
                                    </div>
                                </div>
                                <div class="middle" id="chat-messages">
                                    <!-- Los mensajes se cargarán aquí dinámicamente -->
                                </div>
                                <div class="bottom-bar">
                                    <form id="chat-form" method="POST" action="chat.php">
                                        <input type="text" name="message" id="message-input" placeholder="Escribe un mensaje..." required>
                                        <button type="submit">
                                            Enviar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        async function loadMessages() {
                            const response = await fetch('chat.php');
                            const messages = await response.text();
                            document.getElementById('chat-messages').innerHTML = messages;
                        }

                        document.getElementById('chat-form').addEventListener('submit', async (e) => {
                            e.preventDefault();
                            const formData = new FormData(e.target);
                            await fetch('chat.php', {
                                method: 'POST',
                                body: formData
                            });
                            e.target.reset();
                            loadMessages();
                        });

                        setInterval(loadMessages, 3000); // Actualiza cada 3 segundos

                        window.onload = loadMessages;
                    </script>

                    <!-- ------------------ -->
                    <!-- ------------------ -->
                    <!-- Cambiar Contraseña -->
                    <!-- ------------------ -->
                    <!-- ------------------ -->

                    <div id="especificaciones" class="section-content">
                        <h2>Especificaciones del Usuario</h2>
                        <div class="user-details">
                            <div class="field">
                                <label for="name">Nombre:</label>
                                <input type="text" id="name" value="Juan Pérez" disabled>
                            </div>
                        
                            <div class="field">
                                <label for="email">Correo Electrónico:</label>
                                <input type="email" id="email" value="juan.perez@example.com">
                            </div>
                        
                            <button id="recover-password" onclick="sendRecoveryEmail()">Recuperar Contraseña</button>
                            <p id="recovery-message" class="hidden">
                                Se ha enviado un mensaje de recuperación a tu correo.
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <script src="script.js"></script>
    </body>
</html>