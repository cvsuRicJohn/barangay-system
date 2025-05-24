// Update the date and time in PH timezone
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

// Change navigation bar style when scrolling
window.addEventListener("scroll", function () {
    const nav = document.querySelector("nav");
    nav.classList.toggle("scrolled", window.scrollY > 50);
});



const hamburgerBtn = document.getElementById('hamburgerBtn');
const mainNav = document.getElementById('mainNav');

hamburgerBtn.addEventListener('click', () => {
  mainNav.classList.toggle('show');
});



//age
        function calculateAge(birthDateStr) {
        const birthDate = new Date(birthDateStr);
        const today = new Date();

        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        return age;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const birthDateInput = document.getElementById('birthDate');
        const ageInput = document.getElementById('age');

        function updateAge() {
            if (birthDateInput.value) {
                const age = calculateAge(birthDateInput.value);
                ageInput.value = age;
            }
        }

        birthDateInput.addEventListener('change', updateAge);

        // Run on load in case DOB is pre-filled (like in edit mode)
        updateAge();
    });


        document.addEventListener('DOMContentLoaded', function () {
        const dobInput = document.getElementById('dob');
        const ageInput = document.getElementById('age');

        if (dobInput && ageInput) {
            const dobValue = dobInput.value;
            if (dobValue) {
                const dob = new Date(dobValue);
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const m = today.getMonth() - dob.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                ageInput.value = age;
            }
        }
    });
