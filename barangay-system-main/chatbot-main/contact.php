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

  <!-- Top Header -->
  <div style="background-color: #0056b3; color: white; display: flex; justify-content: space-between; align-items: center; padding: 5px 20px; font-family: Arial, sans-serif; font-size: 14px;">
      <div>
        <strong>GOVPH</strong> | The Official Website of Barangay Bucandala 1, Imus Cavite
      </div>

        <span id="dateTimePH"></span>
        </div>
    </div>

  <!-- Marquee Announcements -->
  <div style="background-color: #12c009; color: black; font-weight: bold; height: 40px; overflow: hidden; display: flex; align-items: center;">
    <marquee behavior="scroll" direction="left" scrollamount="5" style="width: 100%;">
      🔔 Latest Announcement: Barangay Assembly on April 10, 2025 | Free Medical Check-up on April 15, 2025 | Stay Updated with Barangay Bucandala 1!
    </marquee>
  </div>

<!-- Navigation -->
<nav>
  <a href="index.php">Home</a>

  <div class="dropdown">
    <a href="#online-services-section" class="dropbtn">Services ▾</a>
    <div class="dropdown-content">
      
      <!-- Barangay Clearance submenu -->
      <div class="dropdown-submenu">
        <a href="#">Barangay Clearance ▸</a>
        <div class="submenu-content">
          <a href="barangay-clearance.php" title="Get your official barangay clearance for legal use">
            <i class="fas fa-file-alt"></i> Barangay Clearance
          </a>
          <a href="barangay-id.php" title="Apply for your official Barangay ID card">
            <i class="fas fa-id-card"></i> Barangay ID
          </a>
          <a href="construction-clearance.php" title="Clearance for building or construction activities">
            <i class="fas fa-tools"></i> Construction Clearance
          </a>
          <a href="business-permit.php" title="Request for business operation permits">
            <i class="fas fa-store"></i> Business Permit
          </a>
        </div>
      </div>

      <!-- Barangay Certification submenu -->
      <div class="dropdown-submenu">
        <a href="#"> Barangay Certification ▸</a>
        <div class="submenu-content">
          <a href="certificate-of-residency.php" title="Proof that you live in the barangay">
            <i class="fas fa-home"></i> Residency
          </a>
          <a href="certificate-of-indigency.php" title="Proof of financial need for assistance or benefits">
            <i class="fas fa-hand-holding-heart"></i> Indigency
          </a>
          <a href="certificate-of-good-moral.php" title="Good moral standing for legal or school requirements">
            <i class="fas fa-shield-alt"></i> Good Moral 
          </a>
          <a href="first-time-job-seeker.php" title="Certification for first-time job seekers">
            <i class="fas fa-briefcase"></i> First Time Job Seeker

          <a href="solo-parent.php" title="Certification for solo or single parents">
            <i class="fas fa-user-friends"></i> Solo Parent 
          </a>
          <a href="out-of-school-youth.php" title="Declared as out-of-school youth">
            <i class="fas fa-user-graduate"></i> Out of School Youth 
          </a>
          <a href="unemployment-certification.php" title="Certification that the person is unemployed">
            <i class="fas fa-user-times"></i> Unemployment 
          </a>
          <a href="no-income-certification.php" title="Declaration of no income">
            <i class="fas fa-ban"></i> No Income 
          </a>
          <a href="late-birth-registration.php" title="Support for late birth registration">
            <i class="fas fa-baby"></i> Late Birth Registration 
          </a>
          <a href="cohabitation-certification.php" title="Proof of cohabitation without marriage">
            <i class="fas fa-users"></i> Cohabitation 
          </a>
          <a href="non-residency-certification.php" title="Proof of not living in the barangay anymore">
            <i class="fas fa-map-marker-alt"></i> Non-Residency 
          </a>
          <a href="baptismal-certification.php" title="Permission for baptismal activity">
            <i class="fas fa-church"></i> Baptismal 
          </a>
        </div>
      </div>

    </div>
  </div>

  <a href="contact.php">About</a>
  <a href="faq.php">FAQs</a>
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

    <iframe src="chatbot.php"
    style="position: fixed; bottom: 10px; right: 10px; width: 340px; height: 800px; border: none; z-index: 999;"> 
    </iframe>

    <script src="js/contact.js"></script>

</body>
</html>