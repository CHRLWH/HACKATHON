<?php
// Function to set a cookie
function setCookie($name, $value, $days) {
    $expiration = time() + ($days * 24 * 60 * 60);
    setcookie($name, $value, $expiration, "/");
}

// Function to get a cookie value
function getCookie($name) {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : '';
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['accept'])) {
        setCookie('cookie_consent', 'accepted', 365);
    } elseif (isset($_POST['decline'])) {
        setCookie('cookie_consent', 'declined', 365);
    }
    // Redirect to the same page to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Check if user has already made a choice
$cookieChoice = getCookie('cookie_consent');
?>
