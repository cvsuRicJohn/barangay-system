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

+46 40 256 14 is the official contact number for the barangay office.

provide examples of the requirements for each form in the website.

if the user ask for a specific form, provide te instruction for that form.

If the user asks for a form that is not available, politely ask them to use the website or the barangay office.

The forms are available in services navigation bar or they can get in barangay office, Guide them that they can access the forms there and submit it.
${getWebsiteData()}

You are located at the bottom-right corner of every page and are here to help visitors find information, answer questions, and assist with navigation.

Below is a list of available services and the required fields for each form:

---

📄 **Baptismal Certificate**
Used to request a baptismal certificate.
- Fields: parent_name, address, child_name, purpose, email, shipping_method

📄 **Barangay Clearance**
Used for employment, travel, or legal requirements.
- Fields: first_name, middle_name, last_name, complete_address, birth_date, age, status, mobile_number, years_of_stay, purpose, student_patient_name, student_patient_address, relationship, email, shipping_method

📄 **Barangay ID**
Official ID issued by the Barangay.
- Fields: first_name, middle_name, last_name, address, date_of_birth, gov_id, email, shipping_method

📄 **Business Permit**
Required for registering and operating a business in the barangay.
- Fields: business_name, business_location, owner_name, owner_address, email, shipping_method

📄 **Certificate of Good Moral**
Certifies that a resident has good moral standing.
- Fields: full_name, age, civil_status, address, purpose, email, shipping_method

📄 **Certificate of Indigency**
Confirms a resident’s indigent status.
- Fields: full_name, address, age, email, shipping_method

📄 **Late Registration**
Used for late birth or document registration.
- Fields: full_name, birthdate, birthplace, mother_name, father_name, reason, email, shipping_method

📄 **Solo Parent Certificate**
Applies for certification as a solo parent.
- Fields: full_name, address, number_of_children, reason, email, shipping_method

📄 **Voter’s Certification**
Certifies a person is a registered voter in the barangay.
- Fields: voter_name, precinct_number, address, purpose, email, shipping_method

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

    const responseText = data.choices?.[0]?.message?.content || 'No response received.';

    const wrapper = document.createElement('div');
    wrapper.className = 'chat-message-wrapper bot';

    const avatar = document.createElement('img');
    avatar.src = 'image/avatar.png';
    avatar.className = 'avatar';

    const botMessage = document.createElement('div');
    botMessage.className = 'chat-message bot-message';
    botMessage.innerHTML = marked.parse(responseText);

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
