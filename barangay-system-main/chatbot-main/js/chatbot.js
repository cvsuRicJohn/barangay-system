/* ===========================
   Load Saved Chat Messages on Page Load
=========================== */
document.addEventListener('DOMContentLoaded', () => {
  const savedMessages = JSON.parse(localStorage.getItem('chatMessages')) || [];
  const chatBody = document.getElementById('chatBody');

  savedMessages.forEach(msg => {
    if (msg.sender === 'bot') {
      const wrapper = document.createElement('div');
      wrapper.className = 'chat-message-wrapper bot';

      const avatar = document.createElement('img');
      avatar.src = 'image/avatar.png';
      avatar.className = 'avatar';

      const msgDiv = document.createElement('div');
      msgDiv.className = `chat-message bot-message`;
      msgDiv.innerHTML = msg.text;

      wrapper.appendChild(avatar);
      wrapper.appendChild(msgDiv);
      chatBody.appendChild(wrapper);
    } else {
      const msgDiv = document.createElement('div');
      msgDiv.className = `chat-message ${msg.sender}-message`;
      msgDiv.innerHTML = msg.text;
      chatBody.appendChild(msgDiv);
    }
  });

  // Delay scroll to bottom until DOM fully rendered
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      chatBody.scrollTop = chatBody.scrollHeight;
    });
  });
});

/* ===========================
   Chatbot UI Toggle
=========================== */
function toggleChatbot() {
  const chatbot = document.getElementById('chatbot');
  const chatBody = document.getElementById('chatBody');
  const isOpening = chatbot.style.display === 'none';

  chatbot.style.display = isOpening ? 'flex' : 'none';

  // Scroll to bottom only when opening
  if (isOpening) {
    requestAnimationFrame(() => {
      chatBody.scrollTop = chatBody.scrollHeight;
    });
  }
}

/* ===========================
   Extract Website Data
=========================== */
function getWebsiteData() {
  const headers = Array.from(document.querySelectorAll('h1, h2, h3')).map(el => el.innerText.trim()).join('\n');
  const paragraphs = Array.from(document.querySelectorAll('p')).map(el => el.innerText.trim()).join('\n');
  const links = Array.from(document.querySelectorAll('a')).map(el => `${el.innerText.trim()} (${el.href})`).join('\n');

  return `Website Content:\n\nHeaders:\n${headers}\n\nParagraphs:\n${paragraphs}\n\nLinks:\n${links}`;
}



/* ===========================
   Save Message to localStorage
=========================== */
function saveMessageToStorage(sender, text) {
  const messages = JSON.parse(localStorage.getItem('chatMessages')) || [];
  messages.push({ sender, text });
  localStorage.setItem('chatMessages', JSON.stringify(messages));
}

/* ===========================
   Send Message to Bot
=========================== */
async function sendMessage() {
  const inputField = document.getElementById('userInput');
  const chatBody = document.getElementById('chatBody');
  const userText = inputField.value.trim();
  if (!userText) return;

  const userMessage = document.createElement('div');
  userMessage.className = 'chat-message user-message';
  userMessage.textContent = userText;
  chatBody.appendChild(userMessage);
  saveMessageToStorage('user', userText);
  inputField.value = '';
  chatBody.scrollTop = chatBody.scrollHeight;

  const typingIndicator = document.createElement('div');
  typingIndicator.className = 'chat-message bot-message typing-indicator';
  typingIndicator.textContent = 'Bot is typing...';
  chatBody.appendChild(typingIndicator);
  chatBody.scrollTop = chatBody.scrollHeight;

  try {
    const savedMessages = JSON.parse(localStorage.getItem('chatMessages')) || [];

    const messages = [
      {
        role: "system",
        content: `You are the official chatbot assistant of Barangay Bucandala 1.

You must only understand and reply in the languages and dialects commonly spoken in the Philippines, including:
- Filipino (Tagalog)
- English
- Cebuano
- Ilocano
- Hiligaynon
- Kapampangan
- Bicolano
- Waray
- Chavacano
- and other Philippine regional languages

If the user uses a foreign language (like Chinese, Japanese, Korean, Spanish, etc.), politely ask them to use English or a local Philippine dialect.

This website allows residents to inquire and apply in the services tab to access the forms for official barangay documents online. After submitting any application, users must pick up the document in person at the barangay office. Documents are available for pickup **24 hours after online submission and not accepting delivery process**, and the **barangay office is open from 8:00 AM to 5:00 PM**.

Always be polite, helpful, and clear. Respond in the same Philippine language the user used.

📞 +46 40 256 14 is the official contact number for the barangay office.

Provide examples of the requirements for each form in the website.

If the user asks for a specific form, provide the instructions and link for that form.

If the user asks for a form that is not available, politely ask them to use the website or visit the barangay office.

The forms are available in the services section or by direct links such as Barangay Clearance. When a user asks for a specific document, respond with the requirements and a clickable link to the corresponding form inside the forms/ folder

You are located at the bottom-right corner of every page and are here to help visitors find information, answer questions, and assist with navigation.

Below is a list of available services and the required fields for each form do not give this to user as a link:
Provide the proper link for each form use only the "Click here to apply" for specific forms.
---
📄 <a href="forms/baptismal-certificate.php" target="_blank"><strong>Baptismal Certificate</strong></a><br>
Used to request a baptismal certificate.<br>
Fields: parent_name, address, child_name, purpose, email, shipping_method<br><br>

📄 <a href="forms/barangay-clearance.php" target="_blank"><strong>Barangay Clearance</strong></a><br>
Used for employment, travel, or legal requirements.<br>
Fields: first_name, middle_name, last_name, complete_address, birth_date, age, status, mobile_number, years_of_stay, purpose, student_patient_name, student_patient_address, relationship, email, shipping_method<br><br>

📄 <a href="forms/barangay-id.php" target="_blank"><strong>Barangay ID</strong></a><br>
Official ID issued by the Barangay.<br>
Fields: first_name, middle_name, last_name, address, date_of_birth, gov_id, email, shipping_method<br><br>

📄 <a href="forms/good-moral.php" target="_blank"><strong>Certificate of Good Moral</strong></a><br>
Certifies that a resident has good moral standing.<br>
Fields: full_name, age, civil_status, address, purpose, email, shipping_method<br><br>

📄 <a href="forms/indigency.php" target="_blank"><strong>Certificate of Indigency</strong></a><br>
Confirms a resident’s indigent status.<br>
Fields: full_name, address, age, email, shipping_method<br><br>

📄 <a href="forms/late-registration.php" target="_blank"><strong>Late Registration</strong></a><br>
Used for late birth or document registration.<br>
Fields: full_name, birthdate, birthplace, mother_name, father_name, reason, email, shipping_method<br><br>

📄 <a href="forms/solo-parent.php" target="_blank"><strong>Solo Parent Certificate</strong></a><br>
Applies for certification as a solo parent.<br>
Fields: full_name, address, number_of_children, reason, email, shipping_method<br><br>

📄 <a href="forms/voters-certification.php" target="_blank"><strong>Voter’s Certification</strong></a><br>
Certifies a person is a registered voter in the barangay.<br>
Fields: voter_name, precinct_number, address, purpose, email, shipping_method<br><br>


---

Always answer politely, clearly, and in the same language the user uses. Be helpful, multilingual, and guide users step-by-step through the application process when needed.`
      }
    ];

    savedMessages.forEach(msg => {
      messages.push({
        role: msg.sender === 'user' ? 'user' : 'assistant',
        content: msg.text.replace(/<[^>]+>/g, '')
      });
    });

    messages.push({ role: "user", content: userText });

    const response = await fetch("https://openrouter.ai/api/v1/chat/completions", {
      method: "POST",
      headers: {
        "Authorization": "Bearer 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",
        "HTTP-Referer": "https://www.multilingualchatbot",
        "X-Title": "multilingualchatbot",
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        model: "deepseek/deepseek-r1:free",
        messages: messages
      })
    });

    const data = await response.json();
    chatBody.removeChild(typingIndicator);

// Modify the bot's response to include the HTML link for Barangay Clearance
let responseText = data.choices?.[0]?.message?.content || 'No response received.';

// Example of manually inserting the HTML link for Barangay Clearance
const lowerText = userText.toLowerCase();

if (lowerText.includes('barangay clearance')) {
  responseText += '<br><a href="forms/barangay-clearance.php" target="_blank">Click here to apply for Barangay Clearance</a>';
}
if (lowerText.includes('baptismal certificate')) {
  responseText += '<br><a href="forms/baptismal-certificate.php" target="_blank">Click here to apply for Baptismal Certificate</a>';
}
if (lowerText.includes('barangay id')) {
  responseText += '<br><a href="forms/barangay-id.php" target="_blank">Click here to apply for Barangay ID</a>';
}
if (lowerText.includes('good moral')) {
  responseText += '<br><a href="forms/good-moral.php" target="_blank">Click here to apply for Certificate of Good Moral</a>';
}
if (lowerText.includes('indigency')) {
  responseText += '<br><a href="forms/certificate-of-indigency.php" target="_blank">Click here to apply for Certificate of Indigency</a>';
}
if (lowerText.includes('late registration')) {
  responseText += '<br><a href="forms/late-registration.php" target="_blank">Click here to apply for Late Registration</a>';
}
if (lowerText.includes('solo parent')) {
  responseText += '<br><a href="forms/solo-parent.php" target="_blank">Click here to apply for Solo Parent Certificate</a>';
}
if (lowerText.includes("voter's certification") || lowerText.includes("voters certification")) {
  responseText += '<br><a href="forms/voters-certification.php" target="_blank">Click here to apply for Voter’s Certification</a>';
}


const wrapper = document.createElement('div');
wrapper.className = 'chat-message-wrapper bot';

const avatar = document.createElement('img');
avatar.src = 'image/avatar.png';
avatar.className = 'avatar';

const botMessage = document.createElement('div');
botMessage.className = 'chat-message bot-message';
botMessage.innerHTML = marked.parse(responseText);  // Ensure the responseText now includes HTML

wrapper.appendChild(avatar);
wrapper.appendChild(botMessage);
chatBody.appendChild(wrapper);


    saveMessageToStorage('bot', marked.parse(responseText));
    chatBody.scrollTop = chatBody.scrollHeight;

  } catch (error) {
    chatBody.removeChild(typingIndicator);
    const errorMessage = document.createElement('div');
    errorMessage.className = 'chat-message bot-message';
    errorMessage.textContent = 'Error: ' + error.message;
    chatBody.appendChild(errorMessage);
  }
}

/* ===========================
   Handle Enter Key for Sending
=========================== */
document.getElementById('userInput').addEventListener('keypress', function (e) {
  if (e.key === 'Enter') {
    sendMessage();
  }
});

/* ===========================
   Start a New Chat
=========================== */
function startNewChat() {
  localStorage.removeItem('chatMessages');
  document.getElementById('chatBody').innerHTML = '';

  const wrapper = document.createElement('div');
  wrapper.className = 'chat-message-wrapper bot';

  const avatar = document.createElement('img');
  avatar.src = 'image/avatar.png';
  avatar.className = 'avatar';

  const msgDiv = document.createElement('div');
  msgDiv.className = `chat-message bot-message`;
  msgDiv.innerText = 'Hello! How can I assist you today?';

  wrapper.appendChild(avatar);
  wrapper.appendChild(msgDiv);
  document.getElementById('chatBody').appendChild(wrapper);
}
