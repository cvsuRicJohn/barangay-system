document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('form-modal');
    const modalCloseBtn = document.getElementById('modal-close');
    const formContainer = document.getElementById('form-container');

    function getFormHtml(serviceName) {
        switch(serviceName) {
            case 'Barangay Clearance':
                return `
                    <h2 class="text-center mb-4">Barangay Clearance Form</h2>
                    <form id="barangay-clearance-form">
                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>First Name *</label>
                                <input type="text" class="form-control" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Middle Name *</label>
                                <input type="text" class="form-control" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Last Name *</label>
                                <input type="text" class="form-control" required readonly>
                            </div>
 
                            <div class="form-group col-md-12">
                                <label>Complete Address *</label>
                                <input type="text" class="form-control" required readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Birth Date *</label>
                                <input type="date" class="form-control" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Age *</label>
                                <input type="number" class="form-control" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Status *</label>
                                <input type="text" class="form-control" required readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label for="mobile-number">Mobile Number *</label>
                                <input type="tel" class="form-control" id="mobile-number" name="mobile-number" pattern="[0-9]{11}" required placeholder="Enter your 11-digit mobile number" inputmode="numeric" maxlength="11" oninput="this.value = this.value.replace(/\\D/g, '')" readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Years of Stay</label>
                                <input type="text" class="form-control" readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Purpose *</label>
                                <input type="text" class="form-control" required readonly>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Name of Student / Patient *</label>
                                <input type="text" class="form-control" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Address *</label>
                                <input type="text" class="form-control" required readonly>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Relationship *</label>
                                <input type="text" class="form-control" required readonly>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Email *</label>
                                <input type="email" class="form-control" required readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Shipping Method *</label>
                                <select class="form-control" required disabled>
                                    <option>PICK UP (You can claim within 24 hours upon submission. Claimable from 10am-5pm)</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary px-5" disabled>Submit</button>
                        </div>
                    </form>
                `;
            case 'Certificate of No Objection':
                return `
                    <h2>Certificate of No Objection Form</h2>
                    <form id="no-objection-form">
                        <label for="applicantName">Applicant Name:</label>
                        <input type="text" id="applicantName" name="applicantName" required>

                        <label for="propertyAddress">Property Address:</label>
                        <input type="text" id="propertyAddress" name="propertyAddress" required>

                        <label for="reason">Reason:</label>
                        <textarea id="reason" name="reason" rows="3" required></textarea>

                        <button type="submit" class="submit-btn">Submit</button>
                    </form>
                `;
            case 'Certificate of Indigency':
                return `
                    <h2>Certificate of Indigency Form</h2>
                    <form id="indigency-form">
                        <label for="residentName">Resident Name:</label>
                        <input type="text" id="residentName" name="residentName" required>

                        <label for="income">Monthly Income:</label>
                        <input type="number" id="income" name="income" required>

                        <label for="familyMembers">Number of Family Members:</label>
                        <input type="number" id="familyMembers" name="familyMembers" required>

                        <button type="submit" class="submit-btn">Submit</button>
                    </form>
                `;
            default:
                return `<p>No form available for this service.</p>`;
        }
    }

    function openModal(serviceName) {
        formContainer.innerHTML = getFormHtml(serviceName);
        modal.style.display = 'flex';

        // Add submit event listener to the form
        const form = formContainer.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                alert(serviceName + ' form submitted!');
                closeModal();
            });
        }
    }

    function closeModal() {
        modal.style.display = 'none';
        formContainer.innerHTML = '';
    }

    // Attach click event listeners to all view buttons
    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function() {
            const serviceName = this.getAttribute('data-service');
            openModal(serviceName);
        });
    });

    // Close modal when clicking the close button
    modalCloseBtn.addEventListener('click', closeModal);

    // Close modal when clicking outside the modal content
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
});
