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
