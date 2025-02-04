<?php
    $servidor = 'localhost';
    $BBDD = 'hackaton';
    $usuario = 'root';
    $contra = '';

    try {
        $pdo = new PDO("mysql:host=$servidor;dbname=$BBDD;charset=utf8", $usuario, $contra);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Error en la BBDD: " . $e->getMessage());
    }
?>
