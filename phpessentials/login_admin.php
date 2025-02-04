<?php
require_once 'conexion.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['admin_username'], $_POST['admin_password'])) {
    $admin_username = filter_var(trim($_POST['admin_username']), FILTER_SANITIZE_STRING);
    $admin_password = trim($_POST['admin_password']);

    if (!empty($admin_username) && !empty($admin_password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM administradores WHERE nombre = :admin_username");
            $stmt->bindParam(':admin_username', $admin_username, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($admin_password, $admin['contrasena'])) {
                    $_SESSION['admin_id'] = $admin['id'];
                    $_SESSION['admin_name'] = htmlspecialchars($admin['nombre'], ENT_QUOTES, 'UTF-8');

                    // Redirigir al panel de administración
                    header("Location: http://localhost/HACKATHON/administracion/adminPanel.php");
                    exit;
                } else {
                    echo "<script>alert('Contraseña incorrecta.');</script>";
                    echo "<script>window.location.href='login.php';</script>";
                    exit;
                }
            } else {
                echo "<script>alert('Usuario no encontrado.');</script>";
                echo "<script>window.location.href='login.php';</script>";
                exit;
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error en la base de datos.');</script>";
            echo "<script>window.location.href='login.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Faltan datos de login.');</script>";
        echo "<script>window.location.href='login.php';</script>";
        exit;
    }
}
?>