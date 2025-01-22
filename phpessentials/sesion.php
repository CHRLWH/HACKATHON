<?php

session_start([
    'cookie_lifetime' => 60, // Session cookie lasts for 1 day (86400 seconds)
  ]);
  
  require_once '../../phpessentials/conexion.php';
  // Extend session lifetime on each request
  if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // Session has been inactive for more than 30 minutes
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
  } else {
    $_SESSION['LAST_ACTIVITY'] = time(); // Update last activity timestamp
  }
  
  // Regenerate session ID periodically for security (e.g., every 5 minutes)
  if (!isset($_SESSION['CREATED'])) {
    $_SESSION['CREATED'] = time();
  } elseif (time() - $_SESSION['CREATED'] > 300) {
    // Regenerate session ID every 5 minutes
    session_regenerate_id(true);
    $_SESSION['CREATED'] = time();
  }
?>