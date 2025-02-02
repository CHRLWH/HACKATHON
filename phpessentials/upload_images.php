<?php
// upload_images.php

function uploadImages($files) {
    $uploadDir = 'uploads/';
    $imagenes = []; // Arreglo para almacenar las rutas de las imágenes

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true); 
    }

    foreach ($files['tmp_name'] as $key => $tmpName) {
        $fileName = basename($files['name'][$key]);
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($tmpName, $filePath)) {
            $imagenes[] = $filePath;
        }
    }

    // Asegurar que tengamos al menos 5 valores definidos en el arreglo
    for ($i = count($imagenes); $i < 5; $i++) {
        $imagenes[] = null; // Rellenar con `null` si faltan imágenes
    }

    return $imagenes;
}
?>
