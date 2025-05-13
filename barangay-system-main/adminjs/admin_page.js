function confirmDelete(entity, id) {
    if (confirm('Are you sure you want to delete this ' + entity.replace(/_/g, ' ') + ' with ID ' + id + '?')) {
        window.location.href = 'admin_page.php?action=delete&entity=' + entity + '&id=' + id;
    }
}
// Tab navigation
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('#sidebar nav a');
    const contents = document.querySelectorAll('.tab-content-section');

    function setActiveTab(tabName) {
        // Show content section
        for (const content of contents) {
            content.classList.toggle('active', content.id === tabName);
        }
        // Highlight nav
        for (const tab of tabs) {
            tab.classList.toggle('active', tab.dataset.target === tabName);
        }
        // Update URL query param without reload
        if (history.pushState) {
          const newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabName;
          window.history.replaceState({path:newurl}, '', newurl);
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault();
            setActiveTab(tab.dataset.target);
        });
    });

    // Set initial active tab from PHP var or default dashboard
document.addEventListener('DOMContentLoaded', () => {
    const currentTab = document.body.dataset.currentTab || 'dashboard';
    setActiveTab(currentTab);
});
});


// admin_page_debug//

document.addEventListener('DOMContentLoaded', function () {
  const burgerButton = document.getElementById('burgerMenuButton');
  const sidebar = document.getElementById('sidebarMenu');

  burgerButton.addEventListener('click', function () {
    // Toggle the Bootstrap collapse 'show' class on sidebar
    if (sidebar.classList.contains('show')) {
      sidebar.classList.remove('show');
    } else {
      sidebar.classList.add('show');
    }
  });
});


// DASHBOARD //

document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('requestsPieChart').getContext('2d');

  // Use dynamic pie chart data passed from PHP
  const data = {
    labels: pieChartData.labels,
    datasets: [{
      label: 'Requests Count',
      data: pieChartData.data,
      backgroundColor: [
        'rgba(255, 99, 132, 0.7)',
        'rgba(54, 162, 235, 0.7)',
        'rgba(255, 206, 86, 0.7)',
        'rgba(75, 192, 192, 0.7)'
      ],
      borderColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)'
      ],
      borderWidth: 1
    }]
  };

  const config = {
    type: 'pie',
    data: data,
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'bottom',
        },
        title: {
          display: true,
          text: 'Requests Distribution by Type'
        }
      }
    },
  };

  new Chart(ctx, config);
});



// DASHBOARD_REQUEST_OVER_TIME //

document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('requestsOverTimeChart').getContext('2d');

  // Use dynamic data passed from PHP
  const data = {
    labels: requestsOverTimeData.labels,
    datasets: [
      {
        label: 'Total Requests',
        data: requestsOverTimeData.total_requests,
        fill: false,
        borderColor: 'rgba(75, 192, 192, 1)',
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        tension: 0.1
      }
    ]
  };

  const config = {
    type: 'line',
    data: data,
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        title: {
          display: true,
          text: 'Total Requests Over Time'
        }
      },
      scales: {
        y: {
          beginAtZero: true
        }
      }
    },
  };

  new Chart(ctx, config);
});



// JavaScript to make tables collapsible //
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener to all collapsible headers
    const collapsibleHeaders = document.querySelectorAll('.collapsible-header');
    
    collapsibleHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const rows = header.closest('table').querySelectorAll('.collapsible-row');
            rows.forEach(row => {
                row.style.display = row.style.display === 'none' ? '' : 'none';  // Toggle visibility
            });
        });
    });
});
