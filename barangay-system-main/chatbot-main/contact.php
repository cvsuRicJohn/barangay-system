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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Barangay Website - Contact</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <link rel="icon" type="image/png" href="image/imus-logo.png">
  <link rel="stylesheet" href="css/contact.css" />
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

<!-- Navigation -->
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
    <a href="index.php#online-services-section" class="dropbtn">Services ▾</a>
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

    
  <!-- Cover Photo -->
  <div style="width: 100%; height: 300px; overflow: hidden;">
    <img src="image/serbisyo.jpg" alt="Cover Photo" style="width: 100%; height: 100%; object-fit: cover;">
  </div>

  <div class="container my-5">
    <h1 class="text-center mb-5">Infrastructure Development Projects</h1>

    <!-- Project 1 -->
    <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="1000">
        <h3>Project 1: Community Water Pump Installation</h3>
        
        <div id="carouselProject1" class="carousel slide mx-auto" data-ride="carousel" data-interval="4000" style="max-width: 500px;">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="image/project1.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 1 Image 1">
                </div>
                <div class="carousel-item">
                    <img src="image/project2.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 1 Image 2">
                </div>
                <div class="carousel-item">
                    <img src="image/project3.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 1 Image 3">
                </div>
                <div class="carousel-item">
                    <img src="image/project4.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 1 Image 4">
                </div>
            </div>

            <a class="carousel-control-prev" href="#carouselProject1" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselProject1" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>

                        <!-- ✅ Description here -->
                        <div class="mt-4" data-aos="fade-up" data-aos-delay="200">
                            <p style="max-width: 1000px; margin: 0 auto; font-size: 16px; text-align: center;">
                                Community Water Pump Installation In our barangay, many families once spent hours each day collecting water from distant wells or unreliable sources. To solve this, we installed durable hand-operated water pumps in neighborhoods. These pumps require no electricity, are easy to maintain, and provide year-round access to clean water. Local volunteers helped install the pumps, and the barangay trained technicians to handle repairs.
                            </p>
                            <p></p>
                            <p style="text-align: center;">
                                Why This Matters Clean water is a basic right, not a privilege. These pumps save time for families, reduce child sickness, and provide basic needs for everyone in the  community. By keeping maintenance local, we also build community skills and pride.
                            </p>
                        </div>
                    </div>

            <!-- Project 2 -->
            <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="1000">
              <h3>Project 2: Community Solar Lights </h3>
              <div id="carouselProject2" class="carousel slide mx-auto" data-ride="carousel" data-interval="4000" style="max-width: 500px; ">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="image/project5.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 2 Image 1">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project6.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 2 Image 2">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project7.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 2 Image 3">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project8.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 2 Image 4">
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselProject2" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#carouselProject2" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
              </div>

                        <!-- ✅ Description here -->
                        <div class="mt-4" data-aos="fade-up" data-aos-delay="200">
                            <p style="max-width: 1000px; margin: 0 auto; font-size: 16px; text-align: center;">
                                Community Solar Lights Dark streets used to limit safety and community life after sunset. Our solar-powered streetlights, chosen by residents and installed by volunteers, now light up high-traffic areas near homes, and basketball courts. The lights charge by day and automatically turn on at night with zero electricity costs.</p>                       
                                <p></p>
                                <p style="text-align: center;">Lasting Changes Light fosters safety, community, and opportunity. Parents and children alike feel secure walking home, and local businesses can operate longer. Solar energy also cuts costs and protects our environment.</p>
                            </div>
                        </div>

            <!-- Project 3 -->
            <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="1000">
                <h3>Project 3: CCTV Installation Project</h3>
                <div id="carouselProject3" class="carousel slide mx-auto" data-ride="carousel" data-interval="4000" style="max-width: 500px; ">
                    <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="image/project9.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 3 Image 1">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project10.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 3 Image 2">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project11.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 3 Image 3">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project12.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 3 Image 4">
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselProject3" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#carouselProject3" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
              </div>

                        <!-- ✅ Description here -->
                        <div class="mt-4" data-aos="fade-up" data-aos-delay="200">
                            <p style="max-width: 1000px; margin: 0 auto; font-size: 16px; text-align: center;">
                                Strategic CCTV installations now monitor key areas across our barangay, providing 24/7 visibility of community spaces. The high-definition cameras deter criminal activity, help resolve traffic incidents, and allow quick response to emergencies. With centralized monitoring at the barangay hall, authorities can review footage to address safety concerns or suspicious activities reported by residents.</p>                       
                                <p></p>
                                <p style="text-align: center;">Visible security measures create safer streets for everyone. The cameras protect children walking to school and back home, help recover stolen property, and give families peace of mind. Transforming our community into a more secure place to live and work.</p>
                            </div>
                        </div>   
          
            <!-- Project 4 -->
            <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="1000">
                <h3>Project 4: Canal Lining</h3>
                <div id="carouselProject4" class="carousel slide mx-auto" data-ride="carousel" data-interval="4000" style="max-width: 500px; ">
                    <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="image/project13.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 4 Image 1">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project14.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 4 Image 2">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project15.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 4 Image 3">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project16.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 4 Image 4">
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselProject4" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#carouselProject4" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
              </div>
          
                        <!-- ✅ Description here -->
                        <div class="mt-4" data-aos="fade-up" data-aos-delay="200">
                            <p style="max-width: 1000px; margin: 0 auto; font-size: 16px; text-align: center;">
                                Our barangay's new canal lining initiative has upgraded our drainage systems with durable concrete channels, eliminating eroded dirt trenches that frequently clogged during heavy rains. The smooth, reinforced waterways now efficiently control floodwaters, prevent standing pools that attract mosquitoes, and protect roadside foundations from water damage. Maintenance teams can now easily clear debris thanks to the uniform structure, keeping our waterways flowing smoothly year-round.</p>                       
                                <p></p>
                                <p style="text-align: center;">Proper drainage is our first defense against floods and waterborne diseases. These improved canals mean faster runoff during storms, cleaner neighborhoods in the rainy season, and healthier families with reduced risk of dengue and leptospirosis.</p>
                            </div>
                        </div>  

            <!-- Project 5 -->
            <div class="text-center mb-5" data-aos="fade-up" data-aos-duration="1000">
              <h3>Project 5: Barangay Hall Construction</h3>
              <div id="carouselProject5" class="carousel slide mx-auto" data-ride="carousel" data-interval="4000" style="max-width: 500px; ">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img src="image/project17.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 5 Image 1">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project18.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 5 Image 2">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project19.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 5 Image 3">
                  </div>
                  <div class="carousel-item">
                    <img src="image/project20.jpg" class="d-block w-100" style="height: 400px; object-fit: cover;" alt="Project 5 Image 4">
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselProject5" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </a>
                <a class="carousel-control-next" href="#carouselProject5" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </a>
            </div>

                        <!-- ✅ Description here -->
                        <div class="mt-4" data-aos="fade-up" data-aos-delay="200">
                            <p style="max-width: 1000px; margin: 0 auto; font-size: 16px; text-align: center;">
                                We are proud to announce the completion of our new barangay hall, a transformative project made possible through the generous support and opportunity provided by our local government. This modern facility stands as a testament to our shared commitment to progress, offering spacious offices for efficient public service, a welcoming conference area for community gatherings, and improved accessibility for all residents. The upgraded hall not only enhances our daily operations but also serves as a proud landmark that reflects our barangay's growth and unity. We extend our deepest gratitude to the government for this invaluable opportunity to better serve our community, and we look forward to continuing our partnership in building a brighter future for all.</p>                       
                                <p></p>
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

  <!-- Chatbot Redirect Button -->
  <a href="chatbot.php" class="chatbot-redirect-btn">
    <div class="bot-icon">
      <img src="https://cdn-icons-png.flaticon.com/512/4712/4712027.png" alt="Bot Icon">
    </div>
    <div class="bot-text">
      <span class="bot-title">Barangay ChatBot</span>
      <span class="bot-status">
        <span class="dot"></span> Online
      </span>
    </div>
  </a>

    <script src="js/contact.js"></script>

</body>
</html>