<?php
require_once 'conexion.php'; // Conexión a la base de datos
require_once 'sesion.php';    // Verifica o inicia la sesión

$adminLoginFallido = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['admin_username'], $_POST['admin_password'])) {
    $admin_username = filter_var(trim($_POST['admin_username']), FILTER_SANITIZE_STRING);
    $admin_password = trim($_POST['admin_password']);

    if (!empty($admin_username) && !empty($admin_password)) {
        try {
            // Preparar la consulta con PDO
            $stmt = $pdo->prepare("SELECT * FROM administradores WHERE nombre = :admin_username");

            // Vincula los parámetros
            $stmt->bindParam(':admin_username', $admin_username, PDO::PARAM_STR);

            // Ejecutar la consulta
            $stmt->execute();

            // Verificar si se encontró el administrador
            if ($stmt->rowCount() > 0) {
                // Obtener el administrador
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar la contraseña (usando password_verify si la contraseña está hasheada)
                if (password_verify($admin_password, $admin['contrasena'])) {
                    // Guardar datos del administrador en la sesión
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_name'] = htmlspecialchars($admin['nombre'], ENT_QUOTES, 'UTF-8');

                    // Redirigir al panel de administración
                    header("Location: http://localhost/HACKATHON/administracion/adminPanel.php");
                    exit;
                } else {
                    $adminLoginFallido = true; // Contraseña incorrecta
                }
            } else {
                $adminLoginFallido = true; // Usuario no encontrado
            }
        } catch (PDOException $e) {
            // Si hay un error, se maneja la excepción
            die("Error en la consulta: " . $e->getMessage());
        }
    } else {
        $adminLoginFallido = true; // Faltan datos de login
    }
}
?>
