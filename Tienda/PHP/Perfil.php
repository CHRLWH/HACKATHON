<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil de Usuario</title>
        <link rel="stylesheet" href="styles.css">
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
                    <ul style="justify-content:left;">
                        <li class="nav-item" onclick="showSection('chats')">
                        <i class="fas fa-comments" style="min-width: 20px; text-align: center;"></i> Chats Activos
                        </li>

                        <li class="nav-item" onclick="showSection('especificaciones')">
                        <i class="fas fa-user-cog" style="min-width: 20px; text-align: center;"></i> Especificaciones del Usuario
                        </li>

                        <li class="nav-item" onclick="showSection('aniadir-objeto')">
                        <i class="fas fa-plus-circle" style="min-width: 20px; text-align: center;"></i> Añadir Objeto
                        </li>
                    </ul>
                </nav>

                <!-- --------------- -->
                <!-- --------------- -->
                <!-- Subir Productos -->
                <!-- --------------- -->
                <!-- --------------- -->

                <section class="main-section" id="main-section">
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
                        <div class="chat-list">
                            <div class="chat-item" onclick="openChat('chat1')">
                                <div class="avatar">👜</div>
                                <div class="chat-preview">Bolso de Cuero</div>
                            </div>
                            <div class="chat-item" onclick="openChat('chat2')">
                                <div class="avatar">👕</div>
                                <div class="chat-preview">Camiseta Negra</div>
                            </div>
                            <div class="chat-item" onclick="openChat('chat3')">
                                <div class="avatar">👟</div>
                                <div class="chat-preview">Zapatillas Deportivas</div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal oculto por defecto -->
                    <div class="chat-container" id="chat-window" style="display: none;">
                        <div class="chatbox">
                            <div class="top-bar">
                                <button class="back-button" onclick="closeChat()">⬅</button>
                                <div class="avatar" id="chat-avatar">👜</div>
                                <div class="product-name" id="chat-title">Bolso de Cuero</div>
                            </div>
                            <div class="middle" id="chat-messages">
                                <!-- Mensajes dinámicos -->
                            </div>
                            <div class="bottom-bar">
                                <form id="chat-form" onsubmit="sendMessage(event)">
                                    <input type="text" id="message-input" name="message" placeholder="Escribe un mensaje..." required>
                                    <button type="submit">
                                        Enviar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Function to open the chat window
                        function openChat(chatId) {
                            let chatData = {
                                "chat1": { avatar: "👜", title: "Bolso de Cuero" },
                                "chat2": { avatar: "👕", title: "Camiseta Negra" },
                                "chat3": { avatar: "👟", title: "Zapatillas Deportivas" }
                            };

                            // Update the chat window content
                            document.getElementById('chat-avatar').textContent = chatData[chatId].avatar;
                            document.getElementById('chat-title').textContent = chatData[chatId].title;

                            // Show the chat window
                            document.getElementById('chat-window').style.display = 'block';
                        }

                        // Function to close the chat window
                        function closeChat() {
                            // Hide the chat window
                            document.getElementById('chat-window').style.display = 'none';
                        }

                        // Ensure the chat window is closed by default when the page loads
                        document.addEventListener('DOMContentLoaded', function () {
                            closeChat(); // Close the chat window on page load
                        });

                        // Load messages from server
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
                        <div class="user-details">
                            <div class="field">
                                <label for="name">Nombre:</label>
                                <input type="text" id="name" value="Juan Pérez" disabled>
                            </div>
                        
                            <div class="field">
                                <label for="email">Correo Electrónico:</label>
                                <input type="email" id="email" value=<?php echo $row['correo']; ?>>
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