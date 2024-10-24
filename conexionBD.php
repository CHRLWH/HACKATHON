<?php
require_once 'Login.php';

try {
    $conn = new PDO("mysql:host=$host;hackaton=$hackaton", $username, $password);
    echo "Conexion a base de datos correcta.";
} catch (PDOException $pe) {
    die("No se pudo conectar a base de datos :" . $pe->getMessage());
}