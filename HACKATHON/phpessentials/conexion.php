<?php
$nombreDeServidor = "localhost";
$usuario = 'adminphp';
$password = '2002';
$baseDeDatos = 'hackaton';

$conexion = new mysqli($nombreDeServidor, $usuario, $password, $baseDeDatos);

if ($conexion->connect_error) {
    die("Connection failed: " . $conexion->connect_error);
}

$loginFallido = false;
?>