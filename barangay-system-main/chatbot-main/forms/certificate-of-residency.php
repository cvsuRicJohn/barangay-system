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


$success_message = "";
$error_message = "";

// Fetch logged-in user data for autofill
$user = [];
if (isset($_SESSION['user_id'])) {
    $stmt_user = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt_user->execute([$_SESSION['user_id']]);
    $user = $stmt_user->fetch();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $complete_address = trim($_POST['complete_address'] ?? '');
    $civil_status = trim($_POST['civil_status'] ?? '');
    $purpose = trim($_POST['purpose'] ?? '');
    $shipping_method = trim($_POST['shipping_method'] ?? '');
    $cost = 20; // Fixed cost
    $user_id = $_SESSION['user_id'] ?? null;

    // Calculate age from date of birth
    $age = '';
    if (!empty($date_of_birth)) {
        $dob = new DateTime($date_of_birth);
        $today = new DateTime('today');
        $age = $dob->diff($today)->y;
    }

    // Check required fields
    if (
        empty($first_name) || empty($middle_name) || empty($last_name) ||
        empty($date_of_birth) || empty($complete_address) || empty($civil_status) ||
        empty($purpose) || empty($shipping_method)
    ) {
        $error_message = "Please fill in all required fields.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO certificate_of_residency_requests 
                (first_name, middle_name, last_name, date_of_birth, age, complete_address, civil_status, purpose, shipping_method, cost, user_id)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $first_name,
                $middle_name,
                $last_name,
                $date_of_birth,
                $age,
                $complete_address,
                $civil_status,
                $purpose,
                $shipping_method,
                $cost,
                $user_id
            ]);

            // Redirect to avoid form resubmission
            header("Location: certificate-of-residency.php?success=1");
            exit();
        } catch (PDOException $e) {
            $error_message = "Error submitting form: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Keep existing head content from certificate-of-residency.html -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Barangay Certificate of Residency</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="../image/png" href="../image/imus-logo.png">
    <link rel="stylesheet" href="../css/contact.css" />
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
    <div class="header-banner">Barangay Certificate of Residency Form</div>

    <div class="container-fluid px-5 py-4">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success text-center">Form successfully submitted!</div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" action="certificate-of-residency.php" id="myForm">
            <div class="form-row">
                <!-- Name fields -->
                <div class="form-group col-md-3">
                    <label>First Name *</label>
                    <input type="text" name="first_name" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['first_name'] ?? $user['first_name'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Middle Name *</label>
                    <input type="text" name="middle_name" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['middle_name'] ?? $user['middle_name'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-3">
                    <label>Last Name *</label>
                    <input type="text" name="last_name" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['last_name'] ?? $user['last_name'] ?? ''); ?>">
                </div>

                <!-- DOB and Auto Age -->
                <div class="form-group col-md-2">
                    <label>Date of Birth *</label>
                    <input type="date" id="dob" name="date_of_birth" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['date_of_birth'] ?? $user['dob'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-1">
                    <label>Age</label>
                    <input type="text" id="age" name="age" class="form-control" readonly>
                </div>

                <div class="form-group col-md-6">
                    <label>Complete Address *</label>
                    <input type="text" name="complete_address" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['complete_address'] ?? $user['address'] ?? ''); ?>">
                </div>

                <!-- Civil Status -->
                <div class="form-group col-md-2">
                    <label>Civil Status *</label>
                    <select name="civil_status" class="form-control" required>
                        <option value="">-- Select --</option>
                        <option value="Single" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] === 'Single') ? 'selected' : ''; ?>>Single</option>
                        <option value="Married" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] === 'Married') ? 'selected' : ''; ?>>Married</option>
                        <option value="Widowed" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] === 'Widowed') ? 'selected' : ''; ?>>Widowed</option>
                        <option value="Separated" <?php echo (isset($_POST['civil_status']) && $_POST['civil_status'] === 'Separated') ? 'selected' : ''; ?>>Separated</option>
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label>Purpose of Certificate *</label>
                    <input type="text" name="purpose" class="form-control" required value="<?php echo htmlspecialchars($_POST['purpose'] ?? ''); ?>">
                </div>

                <div class="form-group col-md-5">
                    <label>Shipping Method *</label>
                    <select name="shipping_method" class="form-control" required>
                        <option value="PICK UP">PICK UP (You can claim within 24 hours upon submission. Claimable from 10am-5pm)</option>
                    </select>
                </div>
                <div class="form-group col-md-2">
                    <label>Cost</label>
                    <input type="text" class="form-control" readonly value="₱20.00">
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn btn-primary px-5">Submit</button>
            </div>
        </form>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <div class="footer-content">
            <img src="../image/imus-logo.png" alt="Barangay Logo" class="footer-logo">
            <div class="footer-text">
                <p>&copy; 2025 The Official Website of Barangay Bucandala 1, Imus Cavite. All Rights Reserved.</p>
                <p>Bucandala 1 Barangay Hall, Imus, Cavite, Philippines 4103.</p>
                <p>Call Us Today: +46 40 256 14</p>
            </div>
        </div>
    </div>

    <script src="../js/services.js"></script>
</body>
</html>