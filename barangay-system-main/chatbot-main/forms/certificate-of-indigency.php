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
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $civil_status = trim($_POST['civil_status'] ?? '');
    $occupation = trim($_POST['occupation'] ?? '');
    $monthly_income = trim($_POST['monthly_income'] ?? '');
    $proof_of_residency = trim($_POST['proof_of_residency'] ?? '');
    $gov_id = trim($_POST['gov_id'] ?? '');
    $spouse_name = trim($_POST['spouse_name'] ?? '');
    $number_of_dependents = trim($_POST['number_of_dependents'] ?? '');
    $shipping_method = trim($_POST['shipping_method'] ?? '');
    $complete_address = trim($_POST['complete_address'] ?? '');
    $cost = 20; // fixed cost

    // Calculate age server-side
    function calculate_age($dob) {
        $dob_ts = strtotime($dob);
        if (!$dob_ts) return null;
        $today = new DateTime();
        $birthdate = new DateTime(date('Y-m-d', $dob_ts));
        $age = $today->diff($birthdate)->y;
        return $age;
    }
    $age = calculate_age($date_of_birth);

    // Validate required fields (age optional, but you can check if needed)
    if (
        empty($first_name) || empty($middle_name) || empty($last_name) || empty($date_of_birth) ||
        empty($civil_status) || empty($occupation) || empty($monthly_income) || empty($proof_of_residency) ||
        empty($gov_id) || empty($number_of_dependents) || empty($shipping_method) || empty($complete_address)
    ) {
        $error_message = "Please fill in all required fields.";
    } else {
        try {
            // Check if user_id column exists in the table
            $columnCheckStmt = $pdo->prepare("SHOW COLUMNS FROM certificate_of_indigency_requests LIKE 'user_id'");
            $columnCheckStmt->execute();
            $hasUserId = $columnCheckStmt->fetch();

            if ($hasUserId) {
                $stmt = $pdo->prepare("INSERT INTO certificate_of_indigency_requests 
                    (first_name, middle_name, last_name, date_of_birth, age, civil_status, occupation, monthly_income, proof_of_residency, gov_id, spouse_name, number_of_dependents, shipping_method, complete_address, cost, user_id)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $first_name, $middle_name, $last_name, $date_of_birth, $age, $civil_status, $occupation, $monthly_income, $proof_of_residency, $gov_id, $spouse_name, $number_of_dependents, $shipping_method, $complete_address, $cost, $_SESSION['user_id']
                ]);
            }
            // Redirect to avoid form resubmission on refresh
            header("Location: certificate-of-indigency.php?success=1");
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
    <title>Certificate of Indigency Form</title>
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
         <div class="header-banner">Certificate of Indigency Form</div>

    <!-- Form Section -->
    <div class="container-fluid px-5 py-4">
        <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
            <div class="alert alert-success text-center">Form successfully submitted!</div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

<form method="POST" action="certificate-of-indigency.php" id="myForm">
    <div class="form-row">
        <div class="form-group col-md-3">
            <label>First Name *</label>
            <input type="text" name="first_name" class="form-control" required readonly value="<?php echo $success_message ? '' : htmlspecialchars($_POST['first_name'] ?? ($user_data['first_name'] ?? '')); ?>">
        </div>
        <div class="form-group col-md-3">
            <label>Middle Name *</label>
            <input type="text" name="middle_name" class="form-control" required readonly value="<?php echo $success_message ? '' : htmlspecialchars($_POST['middle_name'] ?? ($user_data['middle_name'] ?? '')); ?>">
        </div>
        <div class="form-group col-md-3">
            <label>Last Name *</label>
            <input type="text" name="last_name" class="form-control" required readonly value="<?php echo $success_message ? '' : htmlspecialchars($_POST['last_name'] ?? ($user_data['last_name'] ?? '')); ?>">
        </div>

        <div class="form-group col-md-2">
            <label>Date of Birth *</label>
            <input type="date" id="dob" name="date_of_birth" class="form-control" required readonly
                value="<?php echo $success_message ? '' : htmlspecialchars($_POST['date_of_birth'] ?? ($user_data['dob'] ?? '')); ?>">
        </div>

        <div class="form-group col-md-1">
            <label>Age</label>
            <input type="text" id="age" name="age" class="form-control" readonly
                value="<?php echo $success_message ? '' : (isset($_POST['age']) ? htmlspecialchars($_POST['age']) : ''); ?>">
        </div>

            <div class="form-group col-md-4">
                <label>Complete Address *</label>
                <input type="text" name="complete_address" id="complete_address" class="form-control" required readonly value="<?php echo $success_message ? '' : htmlspecialchars($_POST['complete_address'] ?? ($user_data['complete_address'] ?? $user_data['address'] ?? '')); ?>">
            </div>
        
        <div class="form-group col-md-2">
            <label>Civil Status *</label>
            <select name="civil_status" class="form-control" required>
                <option value="">Select</option>
                <option value="single" <?php if ((!$success_message && (($_POST['civil_status'] ?? '') === 'single' || ($user_data['civil_status'] ?? '') === 'single')) || $success_message) echo 'selected'; ?>>Single</option>
                <option value="married" <?php if ((!$success_message && (($_POST['civil_status'] ?? '') === 'married' || ($user_data['civil_status'] ?? '') === 'married'))) echo 'selected'; ?>>Married</option>
                <option value="widowed" <?php if ((!$success_message && (($_POST['civil_status'] ?? '') === 'widowed' || ($user_data['civil_status'] ?? '') === 'widowed'))) echo 'selected'; ?>>Widowed</option>
                <option value="divorced" <?php if ((!$success_message && (($_POST['civil_status'] ?? '') === 'divorced' || ($user_data['civil_status'] ?? '') === 'divorced'))) echo 'selected'; ?>>Divorced</option>
            </select>
        </div>

        <div class="form-group col-md-3">
            <label>Occupation *</label>
            <input type="text" name="occupation" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['occupation'] ?? ''); ?>">
        </div>

        <div class="form-group col-md-3">
            <label>Monthly Income *</label>
            <input type="number" step="0.01" name="monthly_income" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['monthly_income'] ?? ''); ?>">
        </div>

        <div class="form-group col-md-6">
            <label>Proof of Residency *</label>
            <input type="text" name="proof_of_residency" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['proof_of_residency'] ?? ''); ?>">
        </div>

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
        <option value="Barangay ID" <?php echo (isset($_POST['gov_id']) && $_POST['gov_id'] == 'Barangay ID') ? 'selected' : ''; ?>>Barangay ID</option>
    </select>
</div>

        <div class="form-group col-md-6">
            <label>Spouse Name</label>
            <input type="text" name="spouse_name" class="form-control" value="<?php echo $success_message ? '' : htmlspecialchars($_POST['spouse_name'] ?? ''); ?>">
        </div>

        <div class="form-group col-md-2">
            <label>Number of Dependents *</label>
            <input type="number" name="number_of_dependents" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['number_of_dependents'] ?? ''); ?>">
        </div>

                <div class="form-group col-md-6">
                    <label>Shipping Method *</label>
                    <select name="shipping_method" class="form-control" required>
                        <option value="PICK UP" <?php if ($success_message) echo 'selected'; ?>>PICK UP (You can claim within 24 hours upon submission. Claimable from 10am-5pm)</option>
                    </select>
                </div>
                <div class="form-group col-md-1">
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