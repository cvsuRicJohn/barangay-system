<?php
session_start();

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>


<link rel="stylesheet" href="css/chatbot.css" />
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<!-- Chatbot Button -->
<div style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
  <div onclick="toggleChatbot()" style="
      display: flex;
      align-items: center;
      background: white;
      border-radius: 999px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
      padding: 10px 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      border: 1px solid #e0e0e0;
      ">
    
    <!-- Bot Icon -->
    <div style="
        width: 40px;
        height: 40px;
        background: #007bff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
      ">
      <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png" 
           alt="Bot Icon" 
           style="width: 24px; height: 24px;">
    </div>

    <!-- Text -->
    <div style="display: flex; flex-direction: column;">
      <span style="font-weight: 600; color: #333;">Barangay ChatBot</span>
      <span style="font-size: 12px; color: #28a745; display: flex; align-items: center;">
        <span style="width: 8px; height: 8px; background: #28a745; border-radius: 50%; margin-right: 5px;"></span>
        Online
      </span>
    </div>
  </div>
</div>

<!-- Chatbot Container -->
<div class="chatbot-container" id="chatbot">
    <div class="chat-header" onclick="toggleChatbot()">Barangay ChatBot</div>
    
    <div style="text-align: right; padding: 5px 10px;">
        <button onclick="startNewChat()" 
                style="background: transparent; color: #007bff; border: none; font-weight: bold; cursor: pointer;">
            🗑 New Chat
        </button>
    </div>        

    <div class="chat-body" id="chatBody">
        <!-- Sample Bot Message with Avatar -->
        <!-- Remove this block if dynamically generating from JS -->
        <div class="chat-message-wrapper bot">
            <img src="image/avatar.png" class="avatar" alt="Bot Avatar" />
            <div class="chat-message bot-message">Hello! How can I assist you today? Im the official chatbot of Barangay Bucandala 1, here to help you with services and information. Just let me know what you need!</div>
        </div>
    </div>

    <div class="chat-input">
        <input type="text" id="userInput" placeholder="Type your message..." autocomplete="off">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

<script src="js/chatbot.js"></script>
