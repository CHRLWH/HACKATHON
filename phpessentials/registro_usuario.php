<?php
require_once 'conexion.php';

$registroExitoso = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action']) && $_POST['action'] === 'register') {
    $nombre = filter_var(trim($_POST['nombre']), FILTER_SANITIZE_STRING);
    $nie = filter_var(trim($_POST['nie']), FILTER_SANITIZE_STRING);
    $password = trim($_POST['password']);
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    if (!empty($nombre) && !empty($nie) && !empty($password) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $asunto = "Confirmación de registro";
        $mensaje = "Hola $nombre,\n\nGracias por registrarse. Nos pondremos en contacto con usted tras revisar su información.";
        $cabecera = "From: no-reply@hilan.com\r\nContent-Type: text/plain; charset=UTF-8";

        if (mail($email, $asunto, $mensaje, $cabecera)) {
            echo "<p style='color:blue;'>Registro completado! Nos pondremos en contacto tras revisar sus datos a través de: $email.</p>";
            $registroExitoso = true;
        } else {
            echo "<p style='color:red;'>Fallo en el envío de email! Vuelva a intentarlo.</p>";
        }
    } else {
        echo "<p style='color:red;'>Datos inválidos o incompletos en el registro.</p>";
    }
}
?>
