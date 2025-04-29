document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('requestsOverTimeChart').getContext('2d');

  // Use dynamic data passed from PHP
  const data = {
    labels: requestsOverTimeData.labels,
    datasets: [
      {
        label: 'Barangay ID',
        data: requestsOverTimeData.barangay_id,
        fill: false,
        borderColor: 'rgba(255, 99, 132, 1)',
        backgroundColor: 'rgba(255, 99, 132, 0.2)',
        tension: 0.1
      },
      {
        label: 'Barangay Clearance',
        data: requestsOverTimeData.clearance,
        fill: false,
        borderColor: 'rgba(54, 162, 235, 1)',
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        tension: 0.1
      },
      {
        label: 'Certificate of Indigency',
        data: requestsOverTimeData.indigency,
        fill: false,
        borderColor: 'rgba(255, 206, 86, 1)',
        backgroundColor: 'rgba(255, 206, 86, 0.2)',
        tension: 0.1
      },
      {
        label: 'Certificate of Residency',
        data: requestsOverTimeData.residency,
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
          text: 'Requests Over Time'
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
