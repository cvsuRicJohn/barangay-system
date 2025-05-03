<?php
session_start();

// Function to check if the user is logged in
function check_user_session() {
    if (!isset($_SESSION['user_id'])) {
        // Redirect to login page if user is not logged in
        header("Location: login.php");
        exit();
    }
}

// Function to check if the admin is logged in
function check_admin_session() {
    if (!isset($_SESSION['admin_id'])) {
        // Redirect to admin login page if admin is not logged in
        header("Location: chatbot-main/login.php");
        exit();
    }
}

// Optional: Implement session timeout functionality (e.g., 30 minutes)
function check_session_timeout($timeout = 1800) {  // Default timeout: 30 minutes
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        // Session has expired; destroy the session and redirect to login page
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
    $_SESSION['last_activity'] = time(); // Update last activity timestamp
}
?>
