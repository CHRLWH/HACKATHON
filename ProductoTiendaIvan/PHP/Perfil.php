<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crafter</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/css.css">
  <script src="js.js" defer></script>

</head>
<body>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    
    require_once '../../phpessentials/conexion.php';

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

    <div class="profile-container">
        <h1>Perfil de Usuario</h1>
        <div class="content">
            <nav class="sidebar">
                <ul>
                    <li class="nav-item" onclick="showSection('productos-subidos')">
                        Productos Subidos
                    </li>
                    <li class="nav-item" onclick="showSection('productos-comprados')">
                        Productos Comprados
                    </li>
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
                <div id="productos-subidos" class="section-content active">
                    <h2>Productos Subidos</h2>
                    <p>Aquí se mostrarán los productos que has subido.</p>
                </div>

                <div id="productos-comprados" class="section-content">
                    <h2>Productos Comprados</h2>
                    <p>Aquí se mostrarán los productos que estás comprando.</p>
                </div>

                
<div id="aniadir-objeto" class="section-content">
    <h2>Añadir un nuevo producto</h2>
    <style>
        .drop-zone {
            border: 2px dashed #c0392b;
            border-radius: 5px;
            padding: 20px;
            text-align: center;
            color: #c0392b;
            cursor: pointer;
            position: relative;
        }
        .drop-zone.dragover {
            background-color: #e74c3c;
            color: #fff;
        }
        .preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }
        .preview-container img {
            max-width: 100px;
            max-height: 100px;
            border-radius: 5px;
            object-fit: cover;
            border: 1px solid #ccc;
        }
    </style>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Formulario -->
                <form action="" method="POST" enctype="multipart/form-data" class="p-4 border rounded shadow-sm bg-white">
                    <div class="mb-3">
                        <label for="product-name" class="form-label fw-bold">Nombre del Producto</label>
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
                        <label class="form-label fw-bold">Imágenes del Producto</label>
                        <input type="file" id="product-image" name="product-image[]" class="form-control d-none" accept="image/*" multiple aria-label="Cargar imágenes">
                        <div class="drop-zone" id="drop-zone" aria-label="Zona de carga de imágenes">
                            Arrastra y suelta archivos aquí o haz clic para seleccionar.
                        </div>
                        <div class="preview-container" id="preview-container"></div>
                    </div>

                    <button type="submit" class="btn btn-danger w-100">Agregar Producto</button>
                </form>
            </div>
        </div>
    </div>
</div>


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
                                <div class="incoming">
                                    <div class="bubble">
                                        Hola, ¿sigue disponible el bolso?
                                    </div>
                                </div>

                                <div class="outgoing">
                                    <div class="bubble">
                                        Sí, aún lo tengo disponible.
                                    </div>
                                </div>
                            </div>
                        
                            <div class="bottom-bar">
                                <input type="text" id="message-input" placeholder="Escribe un mensaje...">
                                <button onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                  

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
<script src="../js/js.js"></script>

</body>
</html>