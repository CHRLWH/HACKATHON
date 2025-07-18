<?php

require_once '../../phpessentials/sesion.php';
require_once '../../phpessentials/conexion.php'; // Asegúrate de incluir la conexión a la base de datos

$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$nie = isset($_POST['nie']) ? trim($_POST['nie']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';

if (empty($nombre) || empty($nie) || empty($password) || empty($email)) {
    echo "<p style='color:red;'>Please fill in all fields for registration.</p>";
} else {
    // Encriptar la contraseña antes de almacenarla
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insertar en la base de datos
    $sql = "INSERT INTO usuario (nombre, nie, password, correo, usu_validado) VALUES (?, ?, ?, ?, 0)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssss", $nombre, $nie, $hashed_password, $email);
        if ($stmt->execute()) {
            $subject = "Registration Confirmation";
            $message = "Hello $nombre,\n\nThank you for registering. Your NIE is: $nie.\n\nPlease keep this information safe.";
            $headers = "From: no-reply@hilan.com";

            if (mail($email, $subject, $message, $headers)) {
                echo "<p style='color:blue;'>Registration successful! A confirmation email has been sent to $email.</p>";
            } else {
                echo "<p style='color:red;'>Failed to send the confirmation email. Please try again.</p>";
            }
        } else {
            echo "<p style='color:red;'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red;'>Database error: " . $conn->error . "</p>";
    }

    // Cerrar la conexión
    $conn->close();
}
?>
