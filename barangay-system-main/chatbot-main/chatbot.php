<?php
// chatbot.php
?>

<link rel="stylesheet" href="css/chatbot.css" />
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<!-- Chatbot Code -->
<div style="position: fixed; bottom: 20px; right: 20px;">
    <button onclick="toggleChatbot()" 
            style="background: #12c009; 
                   color: white; 
                   border: none; 
                   padding: 20px;
                   font-size: 20px;
                   border-radius: 50%; 
                   cursor: pointer;">
        💬
    </button>
</div>

<!-- Chatbot Container -->
<div class="chatbot-container" id="chatbot">
    <div class="chat-header" onclick="toggleChatbot()">Barangay ChatBot</div>
    <div style="text-align: right; padding: 5px 10px;">
        <button onclick="startNewChat()" style="background: transparent; color: #007bff; border: none; font-weight: bold; cursor: pointer;">🗑 New Chat</button>
    </div>        
    <div class="chat-body" id="chatBody"></div>
    <div class="chat-input">
        <input type="text" id="userInput" placeholder="Type your message..." autocomplete="off">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

<script src="js/chatbot.js"></script>
