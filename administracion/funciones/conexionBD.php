<?php
// Configuración de la conexión a la base de datos
$servername = "localhost";
$username = "usuariophpdam";
$password = "1234qwerty..";
$dbname = "hakaton";

try {
    // Usar PDO para manejar la base de datos
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Configurar atributos de PDO
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>

