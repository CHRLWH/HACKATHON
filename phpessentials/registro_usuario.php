<?php
require_once 'conexion.php';  // Asegúrate de que la ruta sea correcta

$registroExitoso = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'register') {
    // Obtener y sanitizar los datos del formulario
    $nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);
    $correo = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $codigo_CAM = 1; // Asignar un valor predeterminado o obtener de alguna variable
    $fecha_inscripcion = date("Y-m-d"); // Fecha de inscripción actual
    $idCAM = 1; // Asignar un valor predeterminado o obtener de alguna variable
    $id_ubicacion = 1; // Asignar un valor predeterminado o obtener de alguna variable

    // Validar los datos
    if (!empty($nombre) && !empty($correo) && !empty($password) && filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        
        // Encriptar la contraseña
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Consulta SQL para insertar los datos del usuario
        $sql = "INSERT INTO usuario (codigo_CAM, fecha_inscripcion, idCAM, id_ubicacion, correo, Nombre, usu_validado) 
                VALUES (:codigo_CAM, :fecha_inscripcion, :idCAM, :id_ubicacion, :correo, :nombre, :usu_validado)";

        try {
            // Preparar la consulta
            $stmt = $pdo->prepare($sql);

            // Bind de parámetros
            $usu_validado = 0; // Establecer el valor de 'usu_validado' como 0 (no validado)
            $stmt->bindParam(':codigo_CAM', $codigo_CAM, PDO::PARAM_INT);
            $stmt->bindParam(':fecha_inscripcion', $fecha_inscripcion);
            $stmt->bindParam(':idCAM', $idCAM, PDO::PARAM_INT);
            $stmt->bindParam(':id_ubicacion', $id_ubicacion, PDO::PARAM_INT);
            $stmt->bindParam(':correo', $correo);
            $stmt->bindParam(':nombre', $nombre);
            $stmt->bindParam(':usu_validado', $usu_validado, PDO::PARAM_INT);

            // Ejecutar la consulta
            $stmt->execute();

            // Mostrar mensaje de éxito
            echo "<p style='color:blue;'>Registro completado! Los datos han sido insertados correctamente.</p>";
            $registroExitoso = true;
        } catch (PDOException $e) {
            echo "<p style='color:red;'>Error al registrar el usuario en la base de datos: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Datos inválidos o incompletos en el registro.</p>";
    }
}
?>
