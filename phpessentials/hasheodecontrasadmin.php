<?php
    require_once 'conexion.php'; // Asegúrate de que este archivo configure la variable $pdo correctamente

    try {
        // Seleccionar todos los administradores
        $stmt = $pdo->query("SELECT id, contrasena FROM administradores");
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($admins as $admin) {
            // Verifica si la contraseña ya está hasheada (opcional)
            // Por ejemplo, si la contraseña no empieza con "$2y$", la consideramos en texto plano
            if (strpos($admin['contrasena'], '$2y$') !== 0) {
                // Genera el hash de la contraseña
                $nuevoHash = password_hash($admin['contrasena'], PASSWORD_DEFAULT);

                // Actualiza la contraseña en la base de datos
                $update = $pdo->prepare("UPDATE administradores SET contrasena = :nuevoHash WHERE id = :id");
                $update->execute([
                    ':nuevoHash' => $nuevoHash,
                    ':id'        => $admin['id']
                ]);

                echo "Actualizada la contraseña del administrador con ID: " . $admin['id'] . "<br>";
            } else {
                echo "El administrador con ID: " . $admin['id'] . " ya tiene la contraseña hasheada.<br>";
            }
        }
    } catch (PDOException $e) {
        die("Error: " . $e->getMessage());
    }
?>