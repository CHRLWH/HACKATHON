<?php
require_once 'conexion.php'; // Conexión a la base de datos
require_once 'sesion.php';    // Verifica o inicia la sesión

$loginFallido = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['codigo_CAM'], $_POST['correo'])) {
    $codigo_CAM = filter_var(trim($_POST['codigo_CAM']), FILTER_SANITIZE_NUMBER_INT);
    $correo = filter_var(trim($_POST['correo']), FILTER_SANITIZE_EMAIL);

    if (!empty($codigo_CAM) && !empty($correo)) {
        try {
            // Preparar la consulta con PDO
            $stmt = $pdo->prepare("SELECT * FROM usuario WHERE codigo_CAM = :codigo_CAM AND correo = :correo");
            
            // Vincula los parámetros
            $stmt->bindParam(':codigo_CAM', $codigo_CAM, PDO::PARAM_INT);
            $stmt->bindParam(':correo', $correo, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si se encontró un usuario
            if ($stmt->rowCount() > 0) {
                // Obtener el usuario
                $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

                // Guardar datos del usuario en la sesión
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_name'] = htmlspecialchars($usuario['Nombre'], ENT_QUOTES, 'UTF-8');

                // Redirigir al usuario a la página de inicio
                header("Location: http://localhost/HACKATHON/Tienda/PHP/TiendaV2.php");
                exit;
            } else {
                $loginFallido = true;
            }
        } catch (PDOException $e) {
            // Si hay un error, se maneja la excepción (aunque esto es poco probable)
            die("Error en la consulta: " . $e->getMessage());
        }
    } else {
        $loginFallido = true;
    }
}
?>

