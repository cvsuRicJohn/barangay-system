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

If the user asks for a form that is not available, politely ask them to use the website or visit the barangay office.

You are located at the bottom-right corner of every page and are here to help visitors find information, answer questions, and assist with navigation.

Keep reasoning concise and direct to the point while keeping the format and same language as the user's input within the Philippine language scope.

Below is a list of available services and the required fields for each form do not give this to user as a link:
---
📄  baptismal-certificate
Used to request a baptismal certificate.<br>
Fields: parent_name, address, child_name, purpose, shipping_method<br><br>

📄 barangay-clearance
Used for employment, travel, or legal requirements.<br>
Fields: first_name, middle_name, last_name, complete_address, birth_date, age, civil status, mobile_number, years_of_stay, purpose, student_patient_name, student_patient_address, relationship, address, shipping_method<br><br>

📄 barangay-id
Official ID issued by the Barangay.<br>
Fields: first_name, middle_name, last_name, address, date_of_birth, gov_id, contact_number, shipping_method, Emergency contacts<br><br>

📄 certificate-of-good-moral
Certifies that a resident has good moral standing.<br>
Fields: full_name, birth_date, civil_status, address, purpose, shipping_method<br><br>

📄 certificate-of-indigency
Confirms a resident’s indigent status.<br>
Fields: full_name, birth_date, civil_status, occupation, monthly_income, proof_of_residency, government_ID, spouse_name, number_of_dependents, shipping_method<br><br>

📄 late-birth-registration
Used for late birth or document registration.<br>
Fields: full_name, civil_status, birthdate, birthplace, mother_name, father_name, years_in_barangay, purpose, shipping_method<br><br>

📄 solo-parent
Applies for certification as a solo parent.<br>
Fields: full_name, address, solo_since(year), names_of_children, shipping_method<br><br>

📄 certificate-of-residency
Certifies that a person is a legal resident of the barangay.<br>
Fields: First_name, middle_name, last_name, birth_date, address, proof_of_residency, purpose_of_certificate, shipping method<br><br>

📄 cohabitation-certification
Certifies that two individuals are living together as a couple.<br>
Fields: partner1_name, partner2_name, address, duration, purpose, shipping_method<br><br>

📄 construction-clearance
Approves a construction project within the barangay.<br>
Fields: Business/Activity_name, Business Location, Owner_Name, Owner_Address, shipping_method<br><br>

📄 first-time-job-seeker
Certifies eligibility for first-time job seeker benefits.<br>
Fields: full_name, address, length_of_residency, if_has_taken_oath, shipping_method<br><br>

📄 no-income-certification
Certifies that an individual has no taxable income.<br>
Fields: full_name, birth_date, civil_status, address, statement_of_no_income, purpose, shipping_method<br><br>

📄 non-residency-certification
Certifies that a person does not reside in the barangay.<br>
Fields: applicant_name, previous_address, reason, purpose, shipping_method<br><br>

📄 out-of-school-youth
Certifies youth status for educational or employment programs.<br>
Fields: full_name, address, citizenship, purpose, shipping_method<br><br>

📄 unemployment-certification
Certifies an individual’s unemployed status for benefits.<br>
Fields: full_name, birth_date, civil_status, address, purpose, shipping_method<br><br>


All services Costs ₱20 except Contact Inquiries and First Time Job Seeker Certificate Issuance which are both free, and ₱75 for the Barangay ID.
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
let responseText = data.choices?.[0]?.message?.content || 'Ask another question.';

// Example of manually inserting the HTML link for Barangay Clearance
const lowerText = userText.toLowerCase();

if (lowerText.includes('barangay clearance')) {
  responseText += '<br><a href="forms/barangay-clearance.php" target="_blank">Click here to apply for Barangay Clearance</a>';
}
if (lowerText.includes('baptismal certificate') || lowerText.includes('baptismal')) {
  responseText += '<br><a href="forms/baptismal-certification.php" target="_blank">Click here to apply for Baptismal Certificate</a>';
}
if (lowerText.includes('barangay id')) {
  responseText += '<br><a href="forms/barangay-id.php" target="_blank">Click here to apply for Barangay ID</a>';
}
if (lowerText.includes('good moral')) {
  responseText += '<br><a href="forms/certificate-of-good-moral.php" target="_blank">Click here to apply for Certificate of Good Moral</a>';
}
if (lowerText.includes('indigency') || lowerText.includes('indigent')) {
  responseText += '<br><a href="forms/certificate-of-indigency.php" target="_blank">Click here to apply for Certificate of Indigency</a>';
}
if (lowerText.includes('late registration')) {
  responseText += '<br><a href="forms/late-birth-registration.php" target="_blank">Click here to apply for Late Registration</a>';
}
if (lowerText.includes('solo parent')) {
  responseText += '<br><a href="forms/solo-parent.php" target="_blank">Click here to apply for Solo Parent Certificate</a>';
}
if (lowerText.includes('residency certificate')|| lowerText.includes('residency')) {
  responseText += '<br><a href="forms/certificate-of-residency.php" target="_blank">Click here to apply for Residency Certificate</a>';
}
if (lowerText.includes('cohabitation certification')|| lowerText.includes("cohabitation")) {
  responseText += '<br><a href="forms/cohabitation-certification.php" target="_blank">Click here to apply for Cohabitation Certification</a>';
}
if (lowerText.includes('construction clearance')|| lowerText.includes("construction")) {
  responseText += '<br><a href="forms/construction-clearance.php" target="_blank">Click here to apply for Construction Clearance</a>';
}
if (lowerText.includes('first time job seeker')|| lowerText.includes("job seeker")) {
  responseText += '<br><a href="forms/first-time-job-seeker.php" target="_blank">Click here to apply for First Time Job Seeker Certificate</a>';
}
if (lowerText.includes('no income certificate')|| lowerText.includes("no income")) {
  responseText += '<br><a href="forms/no-income-certification.php" target="_blank">Click here to apply for No Income Certificate</a>';
}
if (lowerText.includes('non residency')|| lowerText.includes("non resident")) {
  responseText += '<br><a href="forms/non-residency-certification.php" target="_blank">Click here to apply for Non-Residency Certificate</a>';
}
if (lowerText.includes('out of school youth')) {
  responseText += '<br><a href="forms/out-of-school-youth.php" target="_blank">Click here to apply for Out of School Youth Certificate</a>';
}
if (lowerText.includes('unemployment certificate')|| lowerText.includes("unemployment")) {
  responseText += '<br><a href="forms/unemployment-certification.php" target="_blank">Click here to apply for Unemployment Certificate</a>';
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
  msgDiv.innerText = 'Hello! How can I assist you today? Im the official chatbot of Barangay Bucandala 1, here to help you with services and information. Just let me know what you need!';

  wrapper.appendChild(avatar);
  wrapper.appendChild(msgDiv);
  document.getElementById('chatBody').appendChild(wrapper);
}
