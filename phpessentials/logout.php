<?php
session_start();

$_SESSION = array();

if (ini_get("session.use_cookies")) {
    $parametro = session_get_cookie_params();
    setcookie(session_name(), '', time() - 100000,
        $parametro["path"], $parametro["domain"],
        $parametro["secure"], $parametro["httponly"]
    );
}

session_destroy();

header("Location: http://localhost/HACKATHON/Login/LoginHtml/Login.php");
exit();
?>