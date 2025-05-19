<?php
require_once('../session_check.php');
check_user_session();

// Make sure user is logged in
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

// Fetch logged-in user data for autofill
$user = [];
if (isset($_SESSION['user_id'])) {
    $stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt_user->execute([$_SESSION['user_id']]);
    $user = $stmt_user->fetch();
}

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $gov_id = trim($_POST['gov_id'] ?? '');
    $shipping_method = trim($_POST['shipping_method'] ?? '');
    $contact_number = $_POST['contact_number'] ?? '';
    $emergency_full_name = trim($_POST['emergency_full_name'] ?? '');
    $emergency_address = $_POST['emergency_address'] ?? '';
    $emergency_contact_number = trim($_POST['emergency_contact_number'] ?? '');

    if (
        empty($first_name) || empty($middle_name) || empty($last_name) || empty($address) ||
        empty($date_of_birth) || empty($gov_id) || empty($shipping_method) || empty($contact_number)
    ) {
        $error_message = "Please fill in all required fields.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO barangay_id_requests 
                (first_name, middle_name, last_name, address, date_of_birth, gov_id, shipping_method, submitted_at, contact_number, emergency_full_name, emergency_address, emergency_contact_number, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, ?)");
            $stmt->execute([
                $first_name,
                $middle_name,
                $last_name,
                $address,
                $date_of_birth,
                $gov_id,
                $shipping_method,
                $contact_number,
                $emergency_full_name,
                $emergency_address,
                $emergency_contact_number,
                $_SESSION['user_id']
            ]);
            $success_message = "Form successfully submitted!";
        } catch (PDOException $e) {
            $error_message = "Error submitting form: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Barangay ID Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../image/imus-logo.png">
    <link rel="stylesheet" href="../css/contact.css" />
</head>

<body>

    <!-- Header and Navigation -->
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
  <img src="../image/imus-logo.png" alt="Barangay Logo" class="logo-img">
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
  <a href="../index.php">Home</a>

  <div class="dropdown">
    <a href="#online-services-section" class="dropbtn">Services ▾</a>
    <div class="dropdown-content">
      <!-- Barangay Clearance -->
      <div class="dropdown-submenu">
        <a href="#">Barangay Clearance ▸</a>
        <div class="submenu-content">
          <a href="barangay-clearance.php"><i class="fas fa-file-alt"></i> Barangay Clearance</a>
          <a href="barangay-id.php"><i class="fas fa-id-card"></i> Barangay ID</a>
          <a href="construction-clearance.php"><i class="fas fa-tools"></i> Construction Clearance</a>
        </div>
      </div>

      <!-- Barangay Certification -->
      <div class="dropdown-submenu">
        <a href="#">Barangay Certification ▸</a>
        <div class="submenu-content">
        <a href="certificate-of-residency.php"><i class="fas fa-home"></i> Residency</a>
        <a href="certificate-of-indigency.php"><i class="fas fa-hand-holding-heart"></i> Indigency</a>
        <a href="certificate-of-good-moral.php"><i class="fas fa-shield-alt"></i> Good Moral</a>
        <a href="first-time-job-seeker.php"><i class="fas fa-briefcase"></i> First Time Job Seeker</a>
        <a href="solo-parent.php"><i class="fas fa-user-friends"></i> Solo Parent</a>
        <a href="out-of-school-youth.php"><i class="fas fa-user-graduate"></i> Out of School Youth</a>
        <a href="unemployment-certification.php"><i class="fas fa-user-times"></i> Unemployment</a>
        <a href="no-income-certification.php"><i class="fas fa-ban"></i> No Income</a>
        <a href="late-birth-registration.php"><i class="fas fa-baby"></i> Late Birth Registration</a>
        <a href="cohabitation-certification.php"><i class="fas fa-users"></i> Cohabitation</a>
        <a href="non-residency-certification.php"><i class="fas fa-map-marker-alt"></i> Non-Residency</a>
        <a href="baptismal-certification.php"><i class="fas fa-church"></i> Baptismal</a>
        </div>
      </div>
    </div>
  </div>

  <a href="../contact.php">About</a>
  <a href="../faq.php">FAQs</a>
  <a href="../profile.php"><i class="fas fa-user"></i> My Profile</a>
</nav>
</nav>

    <!-- Cover Photo -->
    <div class="header-banner">Barangay ID Form</div>

    <!-- Form Section -->
    <div class="container-fluid px-5 py-4">
        <?php if ($success_message): ?>
            <div class="alert alert-success text-center"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        <form method="POST" action="barangay-id.php" id="myForm">
            <div class="form-row">
                <!-- Personal Information -->
                <div class="form-group col-md-4">
                    <label>First Name *</label>
                    <input type="text" name="first_name" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['first_name'] ?? $user['first_name'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Middle Name *</label>
                    <input type="text" name="middle_name" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['middle_name'] ?? $user['middle_name'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['last_name'] ?? $user['last_name'] ?? ''); ?>">
                </div>

                <div class="form-group col-md-12">
                    <label>Address *</label>
                    <input type="text" name="address" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['address'] ?? $user['address'] ?? ''); ?>">
                </div>

                <div class="form-group col-md-6">
                    <label>Date of Birth *</label>
                    <input type="date" name="date_of_birth" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['date_of_birth'] ?? $user['dob'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Age *</label>
                    <input type="number" name="age" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['age'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Contact Number *</label>
                    <input type="text" name="contact_number" class="form-control" required value="<?php echo htmlspecialchars($_POST['contact_number'] ?? $user['contact_number'] ?? ''); ?>">
                </div>

                <!-- Government ID -->
                <div class="form-group col-md-6">
                    <label>Government-issued ID *</label>
                    <select name="gov_id" class="form-control" required>
                        <option value="">-- Select ID --</option>
                        <option value="Philippine Passport" <?php echo (isset($_POST['gov_id']) && $_POST['gov_id'] == 'Philippine Passport') ? 'selected' : ''; ?>>Philippine Passport</option>
                        <option value="Driver’s License" <?php echo (isset($_POST['gov_id']) && $_POST['gov_id'] == 'Driver’s License') ? 'selected' : ''; ?>>Driver’s License (LTO)</option>
                        <option value="PhilSys National ID" <?php echo (isset($_POST['gov_id']) && $_POST['gov_id'] == 'PhilSys National ID') ? 'selected' : ''; ?>>PhilSys National ID</option>
                        <option value="UMID" <?php echo (isset($_POST['gov_id']) && $_POST['gov_id'] == 'UMID') ? 'selected' : ''; ?>>UMID (SSS/GSIS)</option>
                        <option value="Voter’s ID" <?php echo (isset($_POST['gov_id']) && $_POST['gov_id'] == 'Voter’s ID') ? 'selected' : ''; ?>>Voter’s ID/Certificate</option>
                        <option value="Postal ID" <?php echo (isset($_POST['gov_id']) && $_POST['gov_id'] == 'Postal ID') ? 'selected' : ''; ?>>Postal ID</option>
                        <option value="PRC ID" <?php echo (isset($_POST['gov_id']) && $_POST['gov_id'] == 'PRC ID') ? 'selected' : ''; ?>>PRC ID</option>
                        <option value="PhilHealth ID" <?php echo (isset($_POST['gov_id']) && $_POST['gov_id'] == 'PhilHealth ID') ? 'selected' : ''; ?>>PhilHealth ID</option>
                        <option value="TIN ID" <?php echo (isset($_POST['gov_id']) && $_POST['gov_id'] == 'TIN ID') ? 'selected' : ''; ?>>TIN ID</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label>Shipping Method *</label>
                    <select name="shipping_method" class="form-control" required>
                        <option value="PICK UP">PICK UP (You can claim within 24 hours upon submission. Claimable from 10am-5pm)</option>
                    </select>
                </div>

                <!-- Emergency Contact Section -->
                <div class="form-group col-md-12 mt-4">
                    <h5>In Case of Emergency</h5>
                </div>
                <div class="form-group col-md-4">
                    <label>Full Name *</label>
                    <input type="text" name="emergency_full_name" class="form-control" required value="<?php echo htmlspecialchars($_POST['emergency_full_name'] ?? $user['emergency_full_name'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Address *</label>
                    <input type="text" name="emergency_address" class="form-control" required value="<?php echo htmlspecialchars($_POST['emergency_address'] ?? $user['emergency_address'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label>Contact Number *</label>
                    <input type="text" name="emergency_contact_number" class="form-control" required value="<?php echo htmlspecialchars($_POST['emergency_contact_number'] ?? $user['emergency_contact_number'] ?? ''); ?>">
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Submit</button>
            </div>
        </form>
    </div>
        <div class="container mt-5">
            <h5 class="text-center mb-4">Barangay ID Preview</h5>
            <div class="row justify-content-center">
                <!-- Front Side of ID -->
                <div class="col-md-6 text-center mb-4 mb-md-0">
                    <p class="fw-bold">Front Side</p>
                    <img src="../image/barangay_id.jpg" alt="Front ID" class="img-fluid border rounded shadow-sm" style="max-height: 430px;">
                </div>

                <!-- Back Side of ID -->
                <div class="col-md-6 text-center">
                    <p class="fw-bold">Back Side</p>
                    <img src="../image/barangay_id_back.jpg" alt="Back ID" class="img-fluid border rounded shadow-sm" style="max-height: 430px;">
                </div>
            </div>
        </div>

    <!-- Footer Section -->
    <div class="footer">
        <div class="footer-content">
            <img src="../image/imus-logo.png" alt="Barangay Logo" class="footer-logo">
            <div class="footer-text">
                <p>Copyright &copy; 2025 The Official Website of Barangay Bucandala 1, Imus Cavite. All Rights Reserved.</p>
                <p>Bucandala 1 Barangay Hall, Imus, Cavite, Philippines 4103.</p>
                <p>Call Us Today: +46 40 256 14</p>
            </div>
        </div>
    </div>

    <!-- Chatbot -->
    <iframe src="../chatbot.php" style="position: fixed; bottom: 10px; right: 10px; width: 340px; height: 800px; border: none; z-index: 999;"></iframe>
    <script src="../js/services.js"></script>
    <script>
        // Function to calculate age from birthdate string (YYYY-MM-DD)
        function calculateAge(birthDateString) {
            const today = new Date();
            const birthDate = new Date(birthDateString);
            let age = today.getFullYear() - birthDate.getFullYear();
            const m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        // Event listener to update age when date_of_birth changes
        document.addEventListener('DOMContentLoaded', function () {
            const birthDateInput = document.querySelector('input[name="date_of_birth"]');
            const ageInput = document.querySelector('input[name="age"]');

            if (birthDateInput && ageInput) {
                birthDateInput.addEventListener('change', function () {
                    const birthDateValue = birthDateInput.value;
                    if (birthDateValue) {
                        const age = calculateAge(birthDateValue);
                        if (!isNaN(age) && age >= 0) {
                            ageInput.value = age;
                        } else {
                            ageInput.value = '';
                        }
                    } else {
                        ageInput.value = '';
                    }
                });

                // Optionally, trigger change event on page load if date_of_birth has a value
                if (birthDateInput.value) {
                    const event = new Event('change');
                    birthDateInput.dispatchEvent(event);
                }
            }
        });
    </script>
</body>
</html>
