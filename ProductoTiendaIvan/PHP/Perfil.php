<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil de Usuario</title>
        <link rel="stylesheet" href="styles.css">
        <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fefaef;
        }
        .section-content {
            padding: 20px;
        }
        .chat-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .chat-item {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #95572e;
            color: #fefaef;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .chat-item:hover {
            background-color: #5c640f;
        }
        .avatar {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            font-size: 24px;
        }
        .chat-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: white;
            transform: translateX(100%);
            transition: transform 0.5s ease-in-out;
            display: flex;
            flex-direction: column;
        }
        .chat-container.active {
            transform: translateX(0);
        }
        .chatbox {
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .top-bar {
            display: flex;
            align-items: center;
            padding: 10px;
            background-color: #95572e;
            color: #fefaef;
        }
        .back-button {
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            margin-right: 10px;
            color: #fefaef;
        }
        .middle {
            flex-grow: 1;
            overflow-y: auto;
            padding: 20px;
        }
        .bottom-bar {
            padding: 10px;
            background-color: #f1f1f1;
        }
        .chat-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: white;
        transform: translateX(100%);
        transition: transform 0.5s ease-in-out;
        display: flex;
        flex-direction: column;
        }

        .chat-container.active {
            transform: translateX(0);
        }
    </style>
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

<div class="chat-container" id="chat-window">
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
                <input type="text" id="message-input" placeholder="Escribe un mensaje..." required>
                <button type="submit">Enviar</button>
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
        document.getElementById('chat-window').classList.add('active');
    }

    // Function to close the chat window
    function closeChat() {
        // Hide the chat window
        document.getElementById('chat-window').classList.remove('active');
    }

    // Function to send a message
    function sendMessage(event) {
        event.preventDefault(); // Prevent form submission
        const messageInput = document.getElementById('message-input');
        const message = messageInput.value.trim();

        if (message) {
            const chatMessages = document.getElementById('chat-messages');

            // Create a new message element
            const messageElement = document.createElement('div');
            messageElement.classList.add('message', 'outgoing'); // Add 'outgoing' class for styling
            messageElement.textContent = message;

            // Append the message to the chat messages container
            chatMessages.appendChild(messageElement);

            // Scroll to the bottom of the chat
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Clear the input field
            messageInput.value = '';
        }
    }

    // Ensure the chat window is closed by default when the page loads
    document.addEventListener('DOMContentLoaded', function () {
        closeChat(); // Close the chat window on page load
    });
</script>

                    
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