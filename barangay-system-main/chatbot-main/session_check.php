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

// New function to check if user is rejected and block access
function check_user_status() {
    if (isset($_SESSION['user_id'])) {
        // Connect to DB
        $servername = "localhost";
        $username_db = "root";
        $password_db = "";
        $dbname = "barangay_db";

        try {
            $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
            $pdo = new PDO($dsn, $username_db, $password_db, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

        // Check user status
        $stmt = $pdo->prepare("SELECT status FROM users WHERE id = :id");
        $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->execute();
        $user = $stmt->fetch();

        if ($user && strtolower($user['status']) === 'rejected') {
            // Destroy session and redirect to login with error
            session_unset();
            session_destroy();
            header("Location: chatbot-main/login.php?error=access_denied");
            exit();
        }
    }
}
?>
