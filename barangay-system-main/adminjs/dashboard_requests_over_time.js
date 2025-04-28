document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('requestsOverTimeChart').getContext('2d');

  // Placeholder data for requests over time
  const data = {
    labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
    datasets: [{
      label: 'Requests Over Time',
      data: [12, 19, 3, 5, 2, 3, 7],
      fill: false,
      borderColor: 'rgba(75, 192, 192, 1)',
      backgroundColor: 'rgba(75, 192, 192, 0.2)',
      tension: 0.1
    }]
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
          text: 'Requests Over Time (Placeholder)'
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
