<?php
session_start();
// Ruta al archivo donde se almacenan los mensajes
$file = 'messages.txt';

// Manejo de mensajes nuevos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = htmlspecialchars($_POST['message']); // Sanitizar el mensaje
    $newMessage = [
        'time' => date('H:i'),
        'user' => 'Tú', // Cambiar según sea necesario
        'message' => $message,
        'session_id' => session_id() // Agregar ID de sesión
    ];
    $messages = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $messages[] = $newMessage;
    file_put_contents($file, json_encode($messages));
    exit;
}

// Recuperación de mensajes
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $messages = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    foreach ($messages as $msg) {
        $isCurrentUser = $msg['session_id'] === session_id();
        $userClass = $isCurrentUser ? "current-user" : "other-user";
        $userName = $isCurrentUser ? "Tú" : "Otro Usuario";

        echo "<div class=\"bubble $userClass\">
                <strong>$userName</strong> <small>{$msg['time']}</small><br>{$msg['message']}
              </div>";
    }
    exit;
}
?>
