/* ===========================
   Content Switcher (Vision, Mission, Goal)
=========================== */
function showContent(type) {
  let content = "";

  switch (type) {
    case "vision":
      content = "A globally competitive LGU Office that empowers Imuseños to achieve economic vitality and quality of life.";
      break;
    case "mission":
      content = `The City of Imus Cooperative, Livelihood & Entrepreneurial Development Office shall:

Foster the creation and growth of cooperatives and entrepreneurs towards socio-economic development by increasing economic opportunities and benefits for Imuseños.
Improve the livelihood of Imuseños through the creation of conducive business opportunities.
Provide professional, quality and responsive technical and financial assistance to clients in developing viable and responsive businesses.`;
      break;
    case "goal":
      content = "Our goal is to improve community welfare, enhance infrastructure, and foster sustainable growth.";
      break;
  }

  document.getElementById("content-text").textContent = content;
}

/* ===========================
   Initialize AOS Animation
=========================== */
document.addEventListener("DOMContentLoaded", function () {
  AOS.init();
});

/* ===========================
   Live Date & Time (Philippines)
=========================== */
function updateDateTimePH() {
  const options = {
    timeZone: "Asia/Manila",
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "numeric",
    minute: "2-digit",
    second: "2-digit",
    hour12: true
  };

  const dateTimePH = new Date().toLocaleString("en-PH", options);
  document.getElementById("dateTimePH").textContent = dateTimePH;
}

// Update every second
setInterval(updateDateTimePH, 1000);
updateDateTimePH();

/* ===========================
   Sticky Nav on Scroll
=========================== */
window.addEventListener("scroll", function () {
  const nav = document.querySelector("nav");
  nav.classList.toggle("scrolled", window.scrollY > 50);
});

const sidebar = document.getElementById('sidebar');
const overlay = document.getElementById('sidebarOverlay');
const openBtn = document.getElementById('openSidebar');
const closeBtn = document.getElementById('closeSidebar');

// Open the sidebar
openBtn.addEventListener('click', () => {
  sidebar.classList.add('active');
  overlay.classList.add('active');
  openBtn.classList.add('hide'); // Hide the menu button when the sidebar is open
});

// Close the sidebar
closeBtn.addEventListener('click', () => {
  sidebar.classList.remove('active');
  overlay.classList.remove('active');
  openBtn.classList.remove('hide'); // Show the menu button when the sidebar is closed
});

// Close the sidebar when clicking the overlay
overlay.addEventListener('click', () => {
  sidebar.classList.remove('active');
  overlay.classList.remove('active');
  openBtn.classList.remove('hide'); // Show the menu button when the sidebar is closed
});


