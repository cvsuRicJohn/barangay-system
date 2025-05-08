<?php
include('session_check.php');  // Include the session check

// Check if the user is logged in
check_user_session();  // This ensures only logged-in users can access this page

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
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

// Handle contact form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Basic validation
    if ($name === '' || $email === '' || $subject === '' || $message === '') {
        $contact_error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $contact_error = "Please enter a valid email address.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO contact_inquiries (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())");
            $stmt->execute([$name, $email, $subject, $message]);
            $contact_success = "Thank you for contacting us. We will get back to you soon.";
        } catch (PDOException $e) {
            $contact_error = "Failed to send your message. Please try again later. Error: " . $e->getMessage();
        }
    }
}
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="css/index.css" />
  <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
</head>
<body>


<div class="header-bar">
  <div class="header-container">
    
    <!-- Left section -->
    <div class="left-section">
      GOVPH
      <span>| The Official Website of Barangay Bucandala 1, Imus, Cavite</span>
    </div>

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
        <img src="image/cap.jpg" alt="Barangay Bucandala 1 Team" class="about-image">
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
          <a href="forms/barangay-clearance.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/4ps.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-tasks fa-2x mb-2"></i>
              <h5>Clearance</h5>
            </div>
          </a>
        </div>
  
        <!-- Good Moral -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="forms/certificate-of-good-moral.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-shield fa-2x mb-2"></i>
              <h5>Good Moral</h5>
            </div>
          </a>
        </div>
  
        <!-- Identification -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="forms/barangay-id.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/identification.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-id-card fa-2x mb-2"></i>
              <h5>Identification</h5>
            </div>
          </a>
        </div>
  
        <!-- Indigency -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="forms/certificate-of-indigency.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/indigency.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-child fa-2x mb-2"></i>
              <h5>Indigency</h5>
            </div>
          </a>
        </div>
  
        <!-- Solo Parent -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="forms/solo-parent.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/loan.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-user-friends fa-2x mb-2"></i>
              <h5>Solo Parent</h5>
            </div>
          </a>
        </div>
  
        <!-- Non Residency -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="forms/non-residency-certification.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/medical.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-map-marker-alt fa-2x mb-2"></i>
              <h5>Non Residency</h5>
            </div>
          </a>
        </div>
  
        <!-- Residency -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="100">
          <a href="forms/certificate-of-residency.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/residency.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-home fa-2x mb-2"></i>
              <h5>Residency</h5>
            </div>
          </a>
        </div>
  
        <!-- Job Seeker -->
        <div class="col-md-3 col-sm-6 mb-4" data-aos="fade-up" data-aos-delay="200">
          <a href="forms/first-time-job-seeker.php" style="text-decoration: none;">
            <div class="service-box" style="background: url('image/other.jpg') center/cover; padding: 60px 20px; border-radius: 10px; color: white;">
              <i class="fas fa-briefcase fa-2x mb-2"></i>
              <h5>Job Seeker</h5>
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

        <!--  GAD SECTION -->
  <section id="gad" class="gad-section" data-aos="fade-up" data-aos-duration="1000">
  <h2>Gender and Development (GAD)</h2>
  <p>As part of our commitment to inclusive and data-driven governance, we present the gender distribution in Bucandala I:</p>

  <div class="gender-stats">
    <div class="stat-box male">
      <h3>Male Population</h3>
      <p>5,867</p>
    </div>
    <div class="stat-box female">
      <h3>Female Population</h3>
      <p>6,356</p>
    </div>
  </div>

  <p>Total Population: <strong>12,223</strong></p>
  <p>This gender profile helps guide our GAD programs and budget allocations, ensuring all genders are represented and supported in barangay services and initiatives.</p>
</section>

      <!-- Contact Form -->
  <section class="contact-section"data-aos="fade-up" data-aos-duration="1000">
    <div class="contact-form">
      <h2>For Inquiries and Concerns, Contact Us</h2>
      <form method="POST" action="">
        <div class="form-row">
          <input type="text" name="name" placeholder="Name" class="form-input" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
          <input type="email" name="email" placeholder="Email" class="form-input" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>
        <input type="text" name="subject" placeholder="Subject" class="form-input full-width" required value="<?php echo htmlspecialchars($_POST['subject'] ?? ''); ?>">
        <textarea name="message" placeholder="Message" class="form-textarea" required><?php echo htmlspecialchars($_POST['message'] ?? ''); ?></textarea>
        <button type="submit" name="contact_submit" class="submit-btn">Send Message</button>
      </form>
      <?php if (!empty($contact_error)): ?>
        <p style="color: red; font-weight: bold;"><?php echo htmlspecialchars($contact_error); ?></p>
      <?php elseif (!empty($contact_success)): ?>
        <p style="color: green; font-weight: bold;"><?php echo htmlspecialchars($contact_success); ?></p>
      <?php endif; ?>
    </div>
  
    <!-- Contact Info -->
    <div class="contact-info-block">
      <h2 class="contact-highlight">Contact Information</h2>
      <h4><strong>Barangay Bucandala 1, Imus, Cavite</strong></h4>
      <p>📍 Barangay Hall, Barangay Bucandala 1, Imus, Cavite Philippines 4103</p>
      <p>✉️ <a href="mailto:barangaybucandala1@gov.ph">barangaybucana1@gmail.com</a></p>
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
  <iframe src="chatbot.php"
    style="position: fixed; bottom: 10px; right: 10px; width: 340px; height: 800px; border: none; z-index: 999;">
  </iframe>

  <script src="js/index.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  
</body>
</html>
