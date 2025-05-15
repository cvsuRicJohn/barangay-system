<?php
session_start();

// Make sure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ | Barangay Bucandala 1</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="image/imus-logo.png">
    <link rel="stylesheet" href="css/faq.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
    
<div class="header-bar">
  <div class="header-container">
    
    <!-- Left section -->
    <div class="left-section">GOVPH<span>| The Official Website of Barangay Bucandala 1, Imus, Cavite</span></div>

    <!-- Social Media Icons -->
    <div class="social-icons">
      <a href="https://www.facebook.com/profile.php?id=100085126650282"><i class="fab fa-facebook-f"></i></a>
      <a href="#"><i class="fab fa-youtube"></i></a>
      <a href="#"><i class="fab fa-twitter"></i></a>
      <a href="#"><i class="fas fa-phone-alt"></i></a>
    </div>

    <!-- Right section: Philippine Time -->
    <div class="time-section">
      <div>Philippine Standard Time:</div>
      <div id="dateTimePH">Loading time...</div>
    </div>

  </div>
</div>

    <nav style="display: flex; align-items: center; padding: 10px;">
  <!-- Logo and Title -->
  <div class="logo-container keep-on-mobile">
  <img src="image/imus-logo.png" alt="Barangay Logo" class="logo-img">
  <div class="logo-text">
    <span class="barangay-name">Bucandala 1</span><br>
    <span class="barangay-location">Imus, Cavite</span>
  </div>
</div>
<!-- Hamburger Button (mobile only) -->
<button id="hamburgerBtn" class="hamburger-btn">
  <i class="fas fa-bars"></i>
</button>

<!-- MAIN NAVIGATION -->
<nav class="main-nav" id="mainNav">
  <a href="index.php">Home</a>

  <div class="dropdown">
    <a href="#online-services-section" class="dropbtn">Services ▾</a>
    <div class="dropdown-content">
      <!-- Barangay Clearance -->
      <div class="dropdown-submenu">
        <a href="#">Barangay Clearance ▸</a>
        <div class="submenu-content">
          <a href="forms/barangay-clearance.php"><i class="fas fa-file-alt"></i> Barangay Clearance</a>
          <a href="forms/barangay-id.php"><i class="fas fa-id-card"></i> Barangay ID</a>
          <a href="forms/construction-clearance.php"><i class="fas fa-tools"></i> Construction Clearance</a>
        </div>
      </div>

      <!-- Barangay Certification -->
      <div class="dropdown-submenu">
        <a href="#">Barangay Certification ▸</a>
        <div class="submenu-content">
        <a href="forms/certificate-of-residency.php"><i class="fas fa-home"></i> Residency</a>
        <a href="forms/certificate-of-indigency.php"><i class="fas fa-hand-holding-heart"></i> Indigency</a>
        <a href="forms/certificate-of-good-moral.php"><i class="fas fa-shield-alt"></i> Good Moral</a>
        <a href="forms/first-time-job-seeker.php"><i class="fas fa-briefcase"></i> First Time Job Seeker</a>
        <a href="forms/solo-parent.php"><i class="fas fa-user-friends"></i> Solo Parent</a>
        <a href="forms/out-of-school-youth.php"><i class="fas fa-user-graduate"></i> Out of School Youth</a>
        <a href="forms/unemployment-certification.php"><i class="fas fa-user-times"></i> Unemployment</a>
        <a href="forms/no-income-certification.php"><i class="fas fa-ban"></i> No Income</a>
        <a href="forms/late-birth-registration.php"><i class="fas fa-baby"></i> Late Birth Registration</a>
        <a href="forms/cohabitation-certification.php"><i class="fas fa-users"></i> Cohabitation</a>
        <a href="forms/non-residency-certification.php"><i class="fas fa-map-marker-alt"></i> Non-Residency</a>
        <a href="forms/baptismal-certification.php"><i class="fas fa-church"></i> Baptismal</a>
        </div>
      </div>
    </div>
  </div>

  <a href="contact.php">About</a>
  <a href="faq.php">FAQs</a>
  <a href="profile.php"><i class="fas fa-user"></i> My Profile</a>
</nav>
</nav>

<?php include '../db_conn.php'; ?>

<div class="container content">
    <h2 class="text-center">Frequently Asked Questions</h2>
    <div class="faq-container">
        <div class="row">
            <?php
            $sides = ['left', 'right'];
            foreach ($sides as $side):
                $result = $conn->query("SELECT * FROM faqs WHERE column_side = '$side' ORDER BY position ASC");
            ?>
            <div class="col-md-6">
                <div class="accordion" id="faqAccordion<?= ucfirst($side) ?>">
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="card">
                        <div class="card-header" id="heading<?= $row['id'] ?>">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?= $row['id'] ?>" aria-expanded="false" aria-controls="collapse<?= $row['id'] ?>">
                                    + <?= htmlspecialchars($row['question']) ?>
                                </button>
                            </h2>
                        </div>
                        <div id="collapse<?= $row['id'] ?>" class="collapse" aria-labelledby="heading<?= $row['id'] ?>" data-parent="#faqAccordion<?= ucfirst($side) ?>">
                            <div class="card-body">
                                <?= nl2br(htmlspecialchars($row['answer'])) ?>
                            </div>
                        </div>
                    </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>


    <div class="footer">
        <div class="footer-content">
          <img src="image/imus-logo.png" alt="Barangay Logo" class="footer-logo">
          <div class="footer-text">
            <p>Copyright &copy; 2025 The Official Website of Barangay Bucandala 1, Imus Cavite. All Rights Reserved.</p>
            <p>Bucandala 1 Barangay Hall, Imus, Cavite, Philippines 4103.</p>
            <p>Call Us Today: +46 40 256 14</p>
          </div>
        </div>
    </div>

    <iframe src="chatbot.php"
    style="position: fixed; bottom: 10px; right: 10px; width: 340px; height: 800px; border: none; z-index: 999;"> 
    </iframe>

    <script src="js/index.js"></script>

</body>
</html>
