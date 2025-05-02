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
