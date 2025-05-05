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

    <div class="container content">
        <h2 class="text-center">Frequently Asked Questions</h2>
        <div class="faq-container">
            <div class="row">
                <!-- Left Column -->
                <div class="col-md-6">
                    <div class="accordion" id="faqAccordionLeft">
                        <!-- FAQ 1 -->
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        + Anu-ano ang kailangan sa pagkuha ng Barangay I.D.?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#faqAccordionLeft">
                                <div class="card-body">
                                    Kailangan mong magdala ng valid ID at proof of residence tulad ng utility bill o barangay certificate.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 2 -->
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        + Paano kumuha ng Barangay Indigency?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#faqAccordionLeft">
                                <div class="card-body">
                                    Magdala ng valid ID at certificate mula sa Barangay Kapitan na nagpapatunay ng iyong indigency status.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 3 -->
                        <div class="card">
                            <div class="card-header" id="headingThree">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        + Ano ang kailangan sa pagkuha ng Barangay Clearance?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#faqAccordionLeft">
                                <div class="card-body">
                                    Magdala ng valid ID, barangay certificate, at bayaran ang kaukulang fee.
                                </div>
                            </div>
                        </div>

                        <!-- FAQ 4 -->
                        <div class="card">
                            <div class="card-header" id="headingFour">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        + Saan maaaring mag-apply ng Barangay Business Permit?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#faqAccordionLeft">
                                <div class="card-body">
                                    Ang aplikasyon ay maaaring gawin sa Barangay Hall. Dalhin ang necessary business documents.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="col-md-6">
                    <div class="accordion" id="faqAccordionRight">
                        <!-- FAQ 5 -->
                        <div class="card">
                            <div class="card-header" id="headingFive">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                        + Kailan ang check-up ng mga buntis sa Barangay Health Center?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#faqAccordionRight">
                                <div class="card-body">
                                    Ang check-up ay tuwing Lunes at Huwebes ng umaga. Tumawag sa health center para sa eksaktong iskedyul.
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header" id="headingSix">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        + Anu-ano ang Requirements para sa Solo Parent I.D.? 
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#faqAccordionRight">
                                <div class="card-body">
                                    <strong>Ang mga sumusunod ay ang kwalipikado para sa SOLO PARENT I.D.</strong>
                                    <ul>
                                        <li>Biyuda</li>
                                        <li>Hiwalay sa asawa</li>
                                        <li>Nawalang bisa o Annulled ang kasal</li>
                                        <li>Inabandona ng asawa o ng kinakasama</li>
                                        <li>Sinumang indibidwal na tumatayo bilang head of the family bunga ng pag-abandona, pagkawala, matagal na pagkawalay ng magulang o ng solo parent</li>
                                        <li>Biktima ng panggagahasa</li>
                                        <li>Asawa ng nakakulong at/o nahatulang mabilanggo</li>
                                        <li>Hindi sapat ang mental na kapasidad</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- FAQ 6 -->
                        <div class="card">
                            <div class="card-header" id="headingSeven">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                        + May bayad po ba ang Barangay I.D. at mga clearance?
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#faqAccordionRight">
                                <div class="card-body">
                                    Oo, may bayad depende sa dokumentong kukunin.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
