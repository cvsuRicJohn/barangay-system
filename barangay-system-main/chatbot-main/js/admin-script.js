document.addEventListener('DOMContentLoaded', function () {
    const burgerMenu = document.getElementById('burger-menu');
    const sidebar = document.getElementById('sidebar');
    const datetimeElem = document.getElementById('datetime');
    const dateTextElem = document.getElementById('date-text');
    const loadingOverlay = document.getElementById('loading-overlay');
    const topbar = document.querySelector('.topbar');
    const subbar = document.querySelector('.subbar');
    const contentWrapper = document.querySelector('.content-wrapper');

    document.body.classList.add('loaded');

    if (sidebar) sidebar.classList.add('collapsed');
    if (topbar) topbar.style.left = '50px';
    if (subbar) subbar.style.left = '50px';
    if (contentWrapper) contentWrapper.style.marginLeft = '50px';

    burgerMenu.addEventListener('click', function () {
        if (sidebar.classList.contains('collapsed')) {
            sidebar.classList.remove('collapsed');
            sidebar.classList.add('open');
            if (topbar) topbar.style.left = '250px';
            if (subbar) subbar.style.left = '250px';
            if (contentWrapper) contentWrapper.style.marginLeft = '250px';
        } else {
            sidebar.classList.remove('open');
            sidebar.classList.add('collapsed');
            if (topbar) topbar.style.left = '50px';
            if (subbar) subbar.style.left = '50px';
            if (contentWrapper) contentWrapper.style.marginLeft = '50px';
        }
    });

    const logoutLink = document.getElementById('logout');
    if (logoutLink) {
        logoutLink.addEventListener('click', function (e) {
            e.preventDefault();
            alert('Logging out...');
        });
    }

    window.addEventListener('beforeunload', function () {
        if (sidebar) {
            sidebar.classList.remove('open');
            sidebar.classList.add('collapsed');
        }
        if (topbar) topbar.style.left = '50px';
        if (subbar) subbar.style.left = '50px';
        if (contentWrapper) contentWrapper.style.marginLeft = '50px';
    });

    document.querySelectorAll('a[href]').forEach(link => {
        link.addEventListener('click', function (e) {
            const href = this.getAttribute('href');
            if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                e.preventDefault();
                loadingOverlay.style.display = 'flex';
                setTimeout(() => {
                    window.location.href = href;
                }, 300);
            }
        });
    });

    // Account modal and form handling
    const accountModal = document.getElementById('account-modal');
    const modalCloseBtn = document.getElementById('modal-close');
    const accountForm = document.getElementById('account-form');
    const editSaveBtn = document.getElementById('edit-save-btn');

    let isEditing = false;

    function setFormReadOnly(readOnly) {
        Array.from(accountForm.elements).forEach(element => {
            if (element.tagName.toLowerCase() !== 'button') {
                element.readOnly = readOnly;
            }
        });
        editSaveBtn.textContent = readOnly ? 'Edit' : 'Save';
    }

    function openModalWithData(data) {
        accountForm.firstName.value = data.firstName || '';
        accountForm.middleName.value = data.middleName || '';
        accountForm.lastName.value = data.lastName || '';
        accountForm.address.value = data.address || '';
        accountForm.contactNumber.value = data.contactNumber || '';
        accountForm.email.value = data.email || '';
        setFormReadOnly(true);
        isEditing = false;
        accountModal.style.display = 'flex';
    }

    function closeModal() {
        accountModal.style.display = 'none';
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

    editSaveBtn.addEventListener('click', function () {
        if (isEditing) {
            // Save logic here (e.g., send data to server)
            setFormReadOnly(true);
            isEditing = false;
            closeModal();
        } else {
            setFormReadOnly(false);
            isEditing = true;
        }
    });

    window.addEventListener('click', function (event) {
        if (event.target === accountModal) {
            closeModal();
        }
    });

    function updateDateTime() {
        const now = new Date();
        const optionsDate = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' };
        const optionsTime = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
        if (dateTextElem) dateTextElem.textContent = now.toLocaleDateString('en-US', optionsDate);
        let timeNode = null;
        if (datetimeElem) {
            for (let i = 0; i < datetimeElem.childNodes.length; i++) {
                const node = datetimeElem.childNodes[i];
                if (node.nodeType === Node.TEXT_NODE && node.textContent.trim() !== '') {
                    timeNode = node;
                    break;
                }
            }
            if (timeNode) {
                timeNode.textContent = ' ' + now.toLocaleTimeString('en-US', optionsTime);
            } else {
                datetimeElem.appendChild(document.createTextNode(' ' + now.toLocaleTimeString('en-US', optionsTime)));
            }
        }
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
});
