<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection parameters
$servername = "localhost";
$username_db = "root"; // Adjust if needed
$password_db = "";     // Adjust if needed
$dbname = "barangay_db";

try {
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username_db, $password_db, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// You can add dynamic content fetching here if needed
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
  <title>Bucandala 1 | Official Website of Barangay Bucandala 1</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link rel="icon" type="image/png" href="image/imus-logo.png">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/index.css" />
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</head>
<body>

  <div style="background-color: #0056b3; color: white; display: flex; justify-content: space-between; align-items: center; padding: 5px 20px; font-family: Arial, sans-serif; font-size: 14px;">
      <div>
        <strong>GOVPH</strong> | The Official Website of Barangay Bucandala 1, Imus Cavite
      </div>

        <span id="dateTimePH"></span>
        <a href="index.php?action=logout" class="logout-link" onclick="return confirm('Are you sure you want to log out?');">Logout</a>
        </div>
    </div>



<div style="background-color: #12c009; color: black; font-weight: bold; height: 40px; overflow: hidden; display: flex; align-items: center;">
    <marquee behavior="scroll" direction="left" scrollamount="5" style="width: 100%;">
      🔔 Latest Announcement: Barangay Assembly on April 10, 2025 | Free Medical Check-up on April 15, 2025 | Stay Updated with Barangay Bucandala 1!
    </marquee>
  </div>
  
  <nav>
  <a href="index.php">Home</a>
  <div class="dropdown">
    <a href="#online-services-section" class="dropbtn">Services ▾</a>
    <div class="dropdown-content">
      <div class="dropdown-submenu">
        <a href="#">Barangay Certification ></a>
        <div class="submenu-content">
          <a href="barangay-clearance.php">Barangay Clearance</a>
          <a href="certificate-of-indigency.php">Certificate of Indigency</a>
          <a href="certificate-of-residency.php">Certificate of Residency</a>
          <a href="barangay-id.php">Barangay ID</a>
        </div>
      </div>
    </div>
  </div>
  <a href="contact.php">About</a>
  <a href="faq.html">FAQs</a>
</nav>

  <div class="hero-section">
    <img src="image/imus-logo.png" alt="" class="hero-image">
    <img src="image/logo.png" alt="" class="hero">
    <div class="text-overlay">
      <h4>Discover The District</h4>
      <h1 class="main-heading"><strong>Barangay Bucandala 1</strong></h1>
      <p>Cavite, get your opportunity to move forward together.</p>
      <a href="https://www.facebook.com/profile.php?id=100085126650282" class="learn-more">Visit Our Facebook Page →</a>
    </div>
  </div>

  <section class="about-section" data-aos="fade-up" data-aos-duration="1000">
    <div class="container-about">
      <div class="about-left">
        <img src="image/team.jpg" alt="Barangay Bucandala 1 Team" class="about-image">
      </div>
      <div class="about-right">
        <h3>About us</h3>
        <h1>If you change your city,<br>you're changing the world.</h1>
        <p>Barangay Bucandala 1 is determined to address everything that hinders its way to be the best.</p>

        <div class="about-buttons">
          <button class="about-btn" onclick="showContent('mission')">Our Mission</button>
          <button class="about-btn" onclick="showContent('vision')">Our Vision</button>
          <button class="about-btn" onclick="showContent('goal')">Our Goal</button>
        </div>

        <p class="about-description" id="content-text">
          A Barangay that is God-centered, competent, orderly, honest, peaceful, credible, gender responsive and abides the Code of Conduct.
        </p>

        <div class="contact-info">
          <p><strong>Call To Ask Any Questions</strong></p>
          <p class="contact-number">+46 40 256 14</p>
          <p><strong>Barangay Captain</strong></p>
          <p class="captain-name" style="font-size: 30px;" >SANTIAGUEL, FERDINAND APOLINAR</p>
        </div>
      </div>
    </div>
  </section>
  
  <section id="online-services-section" class="online-services-section" style="background-color: #222; color: white; padding: 50px 0;" data-aos="fade-up" data-aos-duration="1000">
    <div class="container text-center">
      <h2 class="font-weight-bold">Online Services</h2>
      <p class="mb-4">Barangay Certification: Identification</p>
      <p style="font-size: 14px;">Effortless Access, One Click Away: Barangay Bucandala 1 Launches Online Services for Seamless Barangay Certification</p>
      <div class="row mt-5">
  
        <!-- Clearance -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="barangay-clearance.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/4ps.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-tasks fa-2x mb-2"></i>
              <h5>Barangay Clearance</h5>
            </div>
          </a>
        </div>
  
        <!-- Common Law -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="common-law-form.html" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/common-law.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-balance-scale fa-2x mb-2"></i>
              <h5>Common Law</h5>
            </div>
          </a>
        </div>
  
        <!-- Identification -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="barangay-id.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/identification.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-id-card fa-2x mb-2"></i>
              <h5>Identification</h5>
            </div>
          </a>
        </div>
  
        <!-- Indigency -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="certificate-of-indigency.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/indigency.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-child fa-2x mb-2"></i>
              <h5>Indigency</h5>
            </div>
          </a>
        </div>
  
        <!-- Loan -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="loan-form.html" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/loan.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-hand-holding-usd fa-2x mb-2"></i>
              <h5>Loan</h5>
            </div>
          </a>
        </div>
  
        <!-- Medical Assistance -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="medical-form.html" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/medical.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-briefcase-medical fa-2x mb-2"></i>
              <h5>Medical Assistance</h5>
            </div>
          </a>
        </div>
  
        <!-- Residency -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="certificate-of-residency.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/residency.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-home fa-2x mb-2"></i>
              <h5>Certificate of Residency</h5>
            </div>
          </a>
        </div>
  
        <!-- Other Purposes -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="200">
          <a href="other-form.html" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/other.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-th-list fa-2x mb-2"></i>
              <h5>Other Purposes</h5>
            </div>
          </a>
        </div>
  
      </div>
    </div>
  </section>
  
  
  <section class="map-section"data-aos="fade-up" data-aos-duration="1000">
    <h2 class="map-title"><strong>Barangay Bucandala 1 MAP</strong></h2>
    <div class="map-container">
      <iframe 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3864.3418477936393!2d120.93009625999409!3d14.40744677920732!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d3b116ec03eb%3A0x4097af86012cb153!2sBarangay%20Hall%20Bucandala%20I!5e0!3m2!1sen!2sph!4v1745208603455!5m2!1sen!2sph" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  </section>

      <!-- Contact Form -->
  <section class="contact-section"data-aos="fade-up" data-aos-duration="1000">
    <div class="contact-form">
      <h2>For Inquiries and Concerns, Contact Us</h2>
      <form>
        <div class="form-row">
          <input type="text" placeholder="Name" class="form-input" required>
          <input type="email" placeholder="Email" class="form-input" required>
        </div>
        <input type="text" placeholder="Subject" class="form-input full-width" required>
        <textarea placeholder="Message" class="form-textarea" required></textarea>
        <button type="submit" class="submit-btn">Send Message</button>
      </form>
    </div>
  
    <!-- Contact Info -->
    <div class="contact-info-block">
      <h2 class="contact-highlight">Contact Information</h2>
      <h4><strong>Barangay Bucandala 1, Imus, Cavite</strong></h4>
      <p>📍 Barangay Hall, Barangay Bucandala 1, Imus, Cavite Philippines 4103</p>
      <p>✉️ <a href="mailto:secretariat@barangay17cagayandeoro.ph">barangaybucana1@gmail.com</a></p>
    </div>
  </section>
  
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
  
  <!-- Chatbot iframe -->
  <iframe src="chatbot.html"
    style="position: fixed; bottom: 10px; right: 10px; width: 340px; height: 800px; border: none; z-index: 999;">
  </iframe>

  <script src="js/index.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  
</body>
</html>
