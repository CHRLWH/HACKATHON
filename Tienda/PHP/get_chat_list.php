<?php
    $chatDirectory = __DIR__ . '/chats/';

    if (!file_exists($chatDirectory)) {
        mkdir($chatDirectory, 0777, true);
    }

    $chatFiles = glob($chatDirectory . 'chat_*.txt');

    if (empty($chatFiles)) {
        echo "<p>No hay chats activos.</p>";
    } else {
        foreach ($chatFiles as $chatFile) {
            $chatId = basename($chatFile, '.txt');
            $chatName = str_replace('chat_', '', $chatId);
            $chatParts = explode('_', $chatName);
            $productName = urldecode(end($chatParts));
            $chatContent = file_get_contents($chatFile);
            $chatData = json_decode($chatContent, true);
            $lastMessage = end($chatData);
            $preview = isset($lastMessage['message']) ? substr($lastMessage['message'], 0, 30) . '...' : 'Sin mensajes';
            
            echo "<div class='chat-item' onclick='openChat(\"$chatId\")'>";
            echo "<div class='avatar'>💬</div>";
            echo "<div class='chat-preview'>";
            echo "<strong>$productName</strong><br>";
            echo "<small>$preview</small>";
            echo "</div>";
            echo "</div>";
        }
    }
?>