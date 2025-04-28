document.addEventListener('DOMContentLoaded', function () {
  const ctx = document.getElementById('requestsPieChart').getContext('2d');

  // Placeholder pie chart data
  const data = {
    labels: [
      'Category A',
      'Category B',
      'Category C',
      'Category D'
    ],
    datasets: [{
      label: 'Placeholder Data',
      data: [10, 20, 30, 40],
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
          text: 'Placeholder Pie Chart'
        }
      }
    },
  };

  new Chart(ctx, config);
});
