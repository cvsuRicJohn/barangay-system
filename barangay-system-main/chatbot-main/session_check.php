<?php
session_start();

function check_user_session() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
}

function check_admin_session() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: chatbot-main/login.php");
        exit();
    }
}
?>
