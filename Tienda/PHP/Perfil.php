<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Perfil de Usuario</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="../css/styles.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
            }

            .chat-window {
                background-color: var(--text-light);
                border: 1px solid var(--primary-bg);
                border-radius: 8px;
                overflow: hidden;
                margin-bottom: 20px;
            }

            .chat-header {
                background-color: var(--primary-bg);
                color: var(--text-light);
                padding: 10px;
                font-weight: bold;
                position: relative;
                border-bottom: 1px solid #ccc;
            }
            .chat-messages {
                padding: 10px;
                max-height: 300px;
                overflow-y: auto;
            }

            .bubble {
                max-width: 80%;
                padding: 10px;
                margin-bottom: 10px;
                border-radius: 20px;
            }

            .current-user {
                background-color: var(--dark-green);
                color: var(--text-light);
                margin-left: auto;
            }

            .other-user {
                background-color: var(--primary-bg);
                color: var(--text-light);
            }

            .chat-input {
                display: flex;
                padding: 10px;
                background-color: var(--text-light);
            }

            .chat-input input {
                flex-grow: 1;
                padding: 5px;
                border: 1px solid var(--primary-bg);
                border-radius: 4px;
            }

            .chat-input button {
                background-color: var(--primary-gold);
                color: var(--text-light);
                border: none;
                padding: 5px 10px;
                margin-left: 5px;
                border-radius: 4px;
                cursor: pointer;
            }

            .close-btn {
                background: none;       /* Quita el fondo por defecto */
                border: none;           /* Quita el borde */
                font-size: 24px;        /* Tamaño de la cruz */
                color: #333;            /* Color de la cruz */
                cursor: pointer;        /* Cambia el cursor al pasar sobre el botón */
                position: absolute;     /* Posicionamiento absoluto */
                top: 10px;              /* Ajusta la distancia desde arriba */
                right: 10px;            /* Ajusta la distancia desde la derecha */
                line-height: 1;         /* Ajusta el interlineado si es necesario */
            }

            /* Opcional: efecto hover */
            .close-btn:hover {
                color: #f00;            /* Cambia de color al pasar el cursor */
            }
        </style>
    </head>

    <body>
        <?php
            // perfil.php
            // Incluir archivo de sesión para verificar que el usuario está autenticado
            require '../../phpessentials/sesion.php';
            // Incluir archivo de conexión para acceder a la base de datos
            require '../../phpessentials/conexion.php';
        ?>

        <div class="profile-container">
            <h1>Perfil de Usuario <i class="fa-solid fa-user"></i></h1>
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
                                            <label for="category" class="form-label fw-bold">Categoría</label>
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
                                            <label for="condition" class="form-label fw-bold">Estado</label>
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

                                        <button type="submit" class="btn per    danger w-100">
                                        <i class="fa-solid fa-arrow-up"></i>  Agregar Producto
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="chats" class="section-content">
                        <div class="chat-list">
                            <?php
                            $chatDirectory = __DIR__ . '/chats/';
                            if (!file_exists($chatDirectory)) {
                                mkdir($chatDirectory, 0777, true);
                            }
                            $chatFiles = glob($chatDirectory . 'chat_*.txt');
                            if (empty($chatFiles)) {
                                echo "<p>No hay chats activos.</p>";
                            } else {
                                foreach ($chatFiles as $chatFile) {
                                    $chatId = basename($chatFile, '.txt');
                                    $chatName = str_replace('chat_', '', $chatId);
                                    $chatParts = explode('_', $chatName);
                                    $productName = urldecode(end($chatParts));
                                    $chatContent = file_get_contents($chatFile);
                                    $chatData = json_decode($chatContent, true);
                                    $lastMessage = end($chatData);
                                    $preview = isset($lastMessage['message']) ? substr($lastMessage['message'], 0, 30) . '...' : 'Sin mensajes';
                                    
                                    echo "<div class='chat-item' onclick='openChat(\"$chatId\")'>";
                                    echo "<div class='avatar'>💬</div>";
                                    echo "<div class='chat-preview'>";
                                    echo "<strong>$productName</strong><br>";
                                    echo "<small>$preview</small>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                            }
                            ?>
                        </div>

                        <div id="chat-windows-container"></div>
                    </div>

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

        <script>
            function showSection(sectionId) {
                const sections = document.querySelectorAll('.section-content');
                sections.forEach(section => section.style.display = 'none');
                document.getElementById(sectionId).style.display = 'block';
            }

            function openChat(chatId) {
                let chatWindow = document.getElementById('chat-' + chatId);
                if (!chatWindow) {
                    chatWindow = document.createElement('div');
                    chatWindow.id = 'chat-' + chatId;
                    chatWindow.className = 'chat-window';
                    chatWindow.innerHTML = `
                        <div class="chat-header">
                            <span>Chat ${chatId}</span>
                            <button class="close-btn" onclick="closeChat('${chatId}')">&times;</button>
                        </div>
                        <div class="chat-messages" id="messages-${chatId}"></div>
                        <form onsubmit="sendMessage(event, '${chatId}')" class="chat-input">
                            <input type="text" id="input-${chatId}" placeholder="Type a message...">
                            <button type="submit">Send</button>
                        </form>
                    `;
                    document.getElementById('chat-windows-container').appendChild(chatWindow);
                }
                loadMessages(chatId);
                chatWindow.style.display = 'block';
                updateChatList(); // Actualizar la lista de chats después de abrir uno nuevo
            }


            function closeChat(chatId) {
                document.getElementById('chat-' + chatId).style.display = 'none';
            }

            async function loadMessages(chatId) {
                const response = await fetch('chat.php?chat=' + encodeURIComponent(chatId));
                const messages = await response.text();
                document.getElementById('messages-' + chatId).innerHTML = messages;
            }

            async function sendMessage(e, chatId) {
                e.preventDefault();
                const input = document.getElementById('input-' + chatId);
                const message = input.value.trim();
                if (message) {
                    const formData = new FormData();
                    formData.append('message', message);
                    formData.append('chat', chatId);
                    await fetch('chat.php', {
                        method: 'POST',
                        body: formData
                    });
                    input.value = '';
                    loadMessages(chatId);
                }
            }

            // Check if there's a chat to open from the URL
            const urlParams = new URLSearchParams(window.location.search);
            const openChatId = urlParams.get('open_chat');
            if (openChatId) {
                showSection('chats');
                openChat(openChatId);
            }

            function updateChatList() {
                fetch('get_chat_list.php')
                    .then(response => response.text())
                    .then(html => {
                        document.querySelector('.chat-list').innerHTML = html;
                    });
            }

            // Actualizar la lista de chats cada 10 segundos
            setInterval(updateChatList, 10000);

            // Actualizar la lista de chats cuando se muestra la sección de chats
            document.querySelector('[onclick="showSection(\'chats\')"]').addEventListener('click', updateChatList);
        </script>
    </body>
</html>