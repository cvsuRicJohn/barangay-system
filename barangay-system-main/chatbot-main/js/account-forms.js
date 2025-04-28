document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('form-modal');
    const modalCloseBtn = document.getElementById('modal-close');
    const formContainer = document.getElementById('form-container');

    function getAccountFormHtml(data) {
        return `
            <h2 class="text-center mb-4">Account Information</h2>
            <form id="account-info-form">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label>First Name</label>
                        <input type="text" class="form-control" value="${data.firstName || ''}" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Middle Name</label>
                        <input type="text" class="form-control" value="${data.middleName || ''}" readonly>
                    </div>
                    <div class="form-group col-md-4">
                        <label>Last Name</label>
                        <input type="text" class="form-control" value="${data.lastName || ''}" readonly>
                    </div>
                    <div class="form-group col-md-12">
                        <label>Address</label>
                        <input type="text" class="form-control" value="${data.address || ''}" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Contact Number</label>
                        <input type="text" class="form-control" value="${data.contactNumber || ''}" readonly>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Email</label>
                        <input type="email" class="form-control" value="${data.email || ''}" readonly>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <button type="button" id="edit-save-btn" class="btn btn-primary">Edit</button>
                </div>
            </form>
        `;
    }

    function openModalWithData(data) {
        formContainer.innerHTML = getAccountFormHtml(data);
        modal.style.display = 'flex';

        const editSaveBtn = document.getElementById('edit-save-btn');
        const form = document.getElementById('account-info-form');
        let isEditing = false;

        function setFormReadOnly(readOnly) {
            Array.from(form.elements).forEach(element => {
                if (element.tagName.toLowerCase() !== 'button') {
                    element.readOnly = readOnly;
                }
            });
            editSaveBtn.textContent = readOnly ? 'Edit' : 'Save';
        }

        editSaveBtn.addEventListener('click', function () {
            if (isEditing) {
                // Save logic here (e.g., send data to server)
                setFormReadOnly(true);
                isEditing = false;
                modal.style.display = 'none';
            } else {
                setFormReadOnly(false);
                isEditing = true;
            }
        });

        setFormReadOnly(true);
    }

    function closeModal() {
        modal.style.display = 'none';
        formContainer.innerHTML = '';
    }

    document.querySelectorAll('.view-btn').forEach(button => {
        button.addEventListener('click', function () {
            const tr = this.closest('tr');
            if (tr) {
                const data = JSON.parse(tr.getAttribute('data-account'));
                openModalWithData(data);
            }
        });
    });

    modalCloseBtn.addEventListener('click', closeModal);

    window.addEventListener('click', function (event) {
        if (event.target === modal) {
            closeModal();
        }
    });
});
