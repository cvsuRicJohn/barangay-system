<!-- sidebar.php -->
<!-- Sidebar Overlay -->
<link rel="stylesheet" href="css/sidebar.css" />
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<!-- Sidebar -->
<div id="sidebar" class="sidebar">
  <div class="sidebar-header">
    <span>Menu</span>
    <button id="closeSidebar" class="btn text-white"><i class="fas fa-times"></i></button>
  </div>
  <ul>
    <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
    <li><a href="profile.php"><i class="fas fa-user"></i> My Profile</a></li>
    <li><a href="contact.php"><i class="fas fa-info-circle"></i> About</a></li>
    <li><a href="services.php"><i class="fas fa-headset"></i> Services</a></li>
    <li><a href="faq.php"><i class="fas fa-question-circle"></i> FAQs</a></li>
    <li><a href="index.php?action=logout" onclick="return confirm('Are you sure you want to log out?');"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
  </ul>
</div>

<!-- Toggle Button -->
<button id="openSidebar" class="toggle-btn">
  <i class="fas fa-bars"></i>
</button>
