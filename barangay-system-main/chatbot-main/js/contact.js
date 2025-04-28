// Date and time in PH
function updateDateTimePH() {
  const options = {
      timeZone: 'Asia/Manila',
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: 'numeric',
      minute: '2-digit',
      second: '2-digit',
      hour12: true
  };
  const dateTimePH = new Date().toLocaleString('en-PH', options);
  document.getElementById('dateTimePH').textContent = dateTimePH;
}

setInterval(updateDateTimePH, 1000);
updateDateTimePH();

// Initialize AOS
AOS.init({
  duration: 800,
  offset: 100,
  once: true
});

// Navigation scroll effect
window.addEventListener("scroll", function () {
  const nav = document.querySelector("nav");
  nav.classList.toggle("scrolled", window.scrollY > 50);
});

