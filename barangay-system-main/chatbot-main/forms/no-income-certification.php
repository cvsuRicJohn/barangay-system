<?php
session_start();
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

// Fetch user data for autofill
$user_data = null;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user_data = $stmt->fetch();
    } catch (PDOException $e) {
        $user_data = null;
    }
}

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $civil_status = trim($_POST['civil_status'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $no_income_statement = trim($_POST['no_income_statement'] ?? '');
    $purpose = trim($_POST['purpose'] ?? '');
    $shipping_method = trim($_POST['shipping_method'] ?? '');
    $cost = 20; // fixed cost for all except first-time job seeker

    // Prevent double submission on page refresh by redirecting after successful POST
    if (
        empty($full_name) || empty($date_of_birth) || empty($civil_status) || empty($address) ||
        empty($no_income_statement) || empty($purpose) || empty($shipping_method)
    ) {
        $error_message = "Please fill in all required fields.";
    } else {
        try {
            // Check if user_id column exists in the table
            $columnCheckStmt = $pdo->prepare("SHOW COLUMNS FROM no_income_certification_requests LIKE 'user_id'");
            $columnCheckStmt->execute();
            $hasUserId = $columnCheckStmt->fetch();

            if ($hasUserId) {
                $stmt = $pdo->prepare("INSERT INTO no_income_certification_requests 
                    (full_name, date_of_birth, civil_status, address, no_income_statement, purpose, shipping_method, cost, user_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $full_name, $date_of_birth, $civil_status, $address, $no_income_statement, $purpose, $shipping_method, $cost, $_SESSION['user_id']
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO no_income_certification_requests 
                    (full_name, date_of_birth, civil_status, address, no_income_statement, purpose, shipping_method, cost)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $full_name, $date_of_birth, $civil_status, $address, $no_income_statement, $purpose, $shipping_method, $cost
                ]);
            }
            // Redirect to avoid form resubmission on refresh
            header("Location: no-income-certification.php?success=1");
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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>No Income Certificate Form</title>
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
    <div class="header-banner">No Income Certificate Form</div>
    
    <!-- Form Section -->
    <div class="container-fluid px-5 py-4">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success text-center">Form successfully submitted!</div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        
<!-- 12. No Income Certificate -->
<form method="POST" action="no-income-certification.php" id="noIncomeForm">
    <div class="form-row">
        <div class="form-group col-md-4">
            <label>Full Name *</label>
            <input type="text" name="full_name" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['full_name'] ?? ($user_data ? trim($user_data['first_name'] . ' ' . ($user_data['middle_name'] ?? '') . ' ' . $user_data['last_name']) : '')); ?>">
        </div>

        <div class="form-group col-md-4">
            <label>Address *</label>
            <input type="text" name="address" class="form-control" required readonly value="<?php echo htmlspecialchars($_POST['address'] ?? ($user_data['address'] ?? '')); ?>">
        </div>

        <div class="form-group col-md-2">
            <label>Date of Birth *</label>
            <input type="date" id="dob" name="date_of_birth" class="form-control" required readonly
                value="<?php echo $success_message ? '' : htmlspecialchars($_POST['date_of_birth'] ?? ($user_data['dob'] ?? '')); ?>">
        </div>

        <div class="form-group col-md-2">
            <label>Age</label>
            <input type="text" id="age" name="age" class="form-control" readonly
                value="<?php echo $success_message ? '' : (isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''); ?>">
        </div>
        
        <div class="form-group col-md-2">
            <label>Civil Status *</label>
            <input type="text" name="civil_status" class="form-control" required value="<?php echo htmlspecialchars($_POST['civil_status'] ?? ($user_data['civil_status'] ?? '')); ?>">
        </div>

        <div class="form-group col-md-5">
            <label>Statement of having no income *</label>
            <input type="text" name="no_income_statement" class="form-control" required value="<?php echo htmlspecialchars($_POST['no_income_statement'] ?? ''); ?>">
        </div>

        <div class="form-group col-md-5">
            <label>Purpose *</label>
            <input type="text" name="purpose" class="form-control" required value="<?php echo htmlspecialchars($_POST['purpose'] ?? ''); ?>">
        </div>
                <div class="form-group col-md-6">
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

        <!-- Success Modal -->
        <div id="successModal" class="modal" style="display: none;">
            <div class="modal-content">
                <span class="close-btn" onclick="closeModal()">&times;</span>
                <h3>Form successfully submitted!</h3>
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

    <script src="../js/services.js"></script>

</body>

</html>