<?php
session_start();

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Barangay ChatBot</title>
    <link rel="stylesheet" href="css/chatbot.css">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</head>
<body>
    <div class="fullpage-chatbot">
      <div class="chat-header">
          <button class="back-btn" onclick="window.history.back()">← Back</button>
          <div class="header-title">Barangay ChatBot</div>
          <button onclick="startNewChat()">🗑 New Chat</button>
      </div>
        <div class="chat-body" id="chatBody">
            <div class="chat-message-wrapper bot">
                <img src="image/avatar.png" class="avatar" alt="Bot Avatar" />
                <div class="chat-message bot-message">
                    Hello! How can I assist you today? I'm the official chatbot of Barangay Bucandala 1, here to help you with services and information. Just let me know what you need!
                </div>
            </div>
        </div>

        <div class="chat-input">
            <input type="text" id="userInput" placeholder="Type your message..." autocomplete="off">
            <button onclick="sendMessage()">Send</button>
        </div>
    </div>

    <script src="js/chatbot.js"></script>
</body>
</html>
