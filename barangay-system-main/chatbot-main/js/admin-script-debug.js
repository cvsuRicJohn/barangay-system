document.addEventListener('DOMContentLoaded', function () {
    console.log('admin-script-debug.js loaded and DOMContentLoaded fired');

    // Add 'loaded' class to body to enable CSS transitions
    document.body.classList.add('loaded');

    const datetimeElem = document.getElementById('datetime');
    const dateTextElem = document.getElementById('date-text');

    if (!datetimeElem) {
        console.error('datetime element not found');
    }
    if (!dateTextElem) {
        console.error('date-text element not found');
    }

    function updateDateTime() {
        const now = new Date();
        const optionsDate = { 
            weekday: 'short', year: 'numeric', month: 'short', day: 'numeric'
        };
        const optionsTime = {
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        };
        if (dateTextElem) {
            dateTextElem.textContent = now.toLocaleDateString('en-US', optionsDate);
        }
        // Update or append time text node
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
