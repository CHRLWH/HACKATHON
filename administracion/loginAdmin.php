<?php
session_start(); // Para manejar sesiones
require_once 'funciones/conexionBD.php'; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['usuario']) && isset($_POST['password'])) {
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM administradores WHERE usuario = ?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bindParam(1, $usuario, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && $password == $row['contrasena']) {
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_usuario'] = $row['usuario'];
                header("Location: adminPanel.php");
                exit();
            } else {
                $error = "Contraseña incorrecta o usuario no encontrado.";
            }
        
        } else {
            echo "Error al preparar la consulta.";
        }
    } else {
        $error = "Por favor, complete todos los campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administrador</title>
    <link rel="stylesheet" href="css/styleLoginAdmin.css"> 
</head>
<body>
    <div class="container">
        <h2>Login Administrador</h2>
        <form method="POST" action="">
            <input type="text" name="usuario" placeholder="Usuario" required><br>
            <input type="password" name="password" placeholder="Contraseña" required><br>
            <button type="submit">Iniciar sesión</button>
        </form>

        <?php if (isset($error)) { ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>
    </div>
</body>
</html>



