<?php
    session_start();

    function getChatFilePath($chatId) {
        $chatDirectory = __DIR__ . '/chats/';
        if (!file_exists($chatDirectory)) {
            mkdir($chatDirectory, 0777, true);
        }
        return $chatDirectory . 'chat_' . $chatId . '.txt';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'], $_POST['chat'])) {
        $chatId = $_POST['chat'];
        $message = htmlspecialchars($_POST['message']);
        $newMessage = [
            'time' => date('H:i'),
            'user' => $_SESSION['user_id'], // Store user_id instead of 'Tú'
            'message' => $message,
            'session_id' => session_id()
        ];
        
        $file = getChatFilePath($chatId);
        $messages = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $messages[] = $newMessage;
        file_put_contents($file, json_encode($messages));
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['chat'])) {
        $chatId = $_GET['chat'];
        $file = getChatFilePath($chatId);
        $messages = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        
        foreach ($messages as $msg) {
            $isCurrentUser = $msg['user'] === $_SESSION['user_id'];
            $userClass = $isCurrentUser ? "current-user" : "other-user";
            $userName = $isCurrentUser ? "Tú" : "Otro Usuario";

            echo "<div class=\"bubble $userClass\">
                    <strong>$userName</strong> <small>{$msg['time']}</small><br>{$msg['message']}
                </div>";
        }
        exit;
    }
?>

