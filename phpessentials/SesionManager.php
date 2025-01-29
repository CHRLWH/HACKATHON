<?php
// session_manager.php

class SessionManager {
    private $db;
    private $session_lifetime = 1800; // 30 minutes

    public function __construct() {
        session_start([
            'cookie_lifetime' => 86400,
            'cookie_secure' => true,
            'cookie_httponly' => true
        ]);

        $this->db = $this->connectDatabase();
        $this->checkSession();
    }

    private function connectDatabase() {
        $host = "localhost";
        $username = "root";
        $password = "";
        $dbname = "hackaton";

        try {
            $db = new PDO("mysql:host=$localhost;dbname=$hackaton", $username, $password);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch(PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    public function checkSession() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('../Login/LoginHtml/Login.php');
        }

        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $this->session_lifetime)) {
            $this->logout();
        }

        $_SESSION['LAST_ACTIVITY'] = time();

        if (!isset($_SESSION['CREATED'])) {
            $_SESSION['CREATED'] = time();
        } else if (time() - $_SESSION['CREATED'] > 300) {
            session_regenerate_id(true);
            $_SESSION['CREATED'] = time();
        }
    }

    public function logout() {
        $_SESSION = array();

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        session_destroy();
        $this->redirect('../Login/LoginHtml/Login.php');
    }

    private function redirect($location) {
        header("Location: $location");
        exit;
    }

    public function getCurrentUser() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    public function getDatabase() {
        return $this->db;
    }
}

// Usage
$sessionManager = new SessionManager();

// To check if user is logged in and session is valid
$sessionManager->checkSession();

// To get current user
$currentUser = $sessionManager->getCurrentUser();

// To logout
// $sessionManager->logout();