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

    if (
        empty($first_name) || empty($middle_name) || empty($last_name) || empty($date_of_birth) ||
        empty($civil_status) || empty($occupation) || empty($monthly_income) || empty($proof_of_residency) ||
        empty($gov_id) || empty($number_of_dependents) || empty($shipping_method)
    ) {
        $error_message = "Please fill in all required fields.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO certificate_of_indigency_requests 
                (first_name, middle_name, last_name, date_of_birth, civil_status, occupation, monthly_income, proof_of_residency, gov_id, spouse_name, number_of_dependents, shipping_method)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $first_name, $middle_name, $last_name, $date_of_birth, $civil_status, $occupation, $monthly_income, $proof_of_residency, $gov_id, $spouse_name, $number_of_dependents, $shipping_method
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
    <title>Certificate of Indigency Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../image/imus-logo.png">
    <link rel="stylesheet" href="../css/contact.css" />
</head>

<body>

    <!-- Header and Navigation -->
    <div style="background-color: #0056b3; color: white; display: flex; justify-content: space-between; align-items: center; padding: 5px 20px; font-family: Arial, sans-serif; font-size: 14px;">
        <div>
            <strong>GOVPH</strong> | The Official Website of Barangay Bucandala 1, Imus Cavite
        </div>
        <div style="display: flex; align-items: center; gap: 15px;">
            <a href="#" style="color: white;"><i class="fab fa-facebook-f"></i></a>
            <a href="#" style="color: white;"><i class="fab fa-youtube"></i></a>
            <a href="#" style="color: white;"><i class="fab fa-twitter"></i></a>
            <a href="tel:+464025614" style="color: white;"><i class="fas fa-phone-alt"></i></a>
            <span id="dateTimePH"></span>
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
    <div style="width: 100%; height: 300px; overflow: hidden; opacity: 0.6;">
    <img src="../image/duduy.jpg" alt="Cover Photo" style="width: 100%; height: 100%; object-fit: cover;">
    </div>

    <!-- Form Section -->
    <div class="container-fluid px-5 py-4">
        <h2 class="text-center mb-4">Certificate of Indigency Form</h2>

        <?php if ($success_message): ?>
            <div class="alert alert-success text-center"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

<form method="POST" action="certificate-of-indigency.php" id="myForm">
    <div class="form-row">
        <div class="form-group col-md-4">
            <label>First Name *</label>
            <input type="text" name="first_name" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['first_name'] ?? ($user_data['first_name'] ?? '')); ?>">
        </div>
        <div class="form-group col-md-4">
            <label>Middle Name *</label>
            <input type="text" name="middle_name" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['middle_name'] ?? ($user_data['middle_name'] ?? '')); ?>">
        </div>
        <div class="form-group col-md-4">
            <label>Last Name *</label>
            <input type="text" name="last_name" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['last_name'] ?? ($user_data['last_name'] ?? '')); ?>">
        </div>

        <div class="form-group col-md-6">
            <label>Date of Birth *</label>
            <input type="date" name="date_of_birth" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['date_of_birth'] ?? ($user_data['dob'] ?? '')); ?>">
        </div>

        <div class="form-group col-md-6">
            <label>Civil Status *</label>
            <select name="civil_status" class="form-control" required>
                <option value="">Select</option>
                <option value="single" <?php if ((!$success_message && (($_POST['civil_status'] ?? '') === 'single' || ($user_data['civil_status'] ?? '') === 'single')) || $success_message) echo 'selected'; ?>>Single</option>
                <option value="married" <?php if ((!$success_message && (($_POST['civil_status'] ?? '') === 'married' || ($user_data['civil_status'] ?? '') === 'married'))) echo 'selected'; ?>>Married</option>
                <option value="widowed" <?php if ((!$success_message && (($_POST['civil_status'] ?? '') === 'widowed' || ($user_data['civil_status'] ?? '') === 'widowed'))) echo 'selected'; ?>>Widowed</option>
                <option value="divorced" <?php if ((!$success_message && (($_POST['civil_status'] ?? '') === 'divorced' || ($user_data['civil_status'] ?? '') === 'divorced'))) echo 'selected'; ?>>Divorced</option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label>Occupation *</label>
            <input type="text" name="occupation" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['occupation'] ?? ''); ?>">
        </div>

        <div class="form-group col-md-6">
            <label>Monthly Income *</label>
            <input type="number" step="0.01" name="monthly_income" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['monthly_income'] ?? ''); ?>">
        </div>

        <div class="form-group col-md-6">
            <label>Proof of Residency *</label>
            <input type="text" name="proof_of_residency" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['proof_of_residency'] ?? ''); ?>">
        </div>

        <div class="form-group col-md-6">
            <label>Government-issued ID *</label>
            <input type="text" name="gov_id" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['gov_id'] ?? ''); ?>">
        </div>

        <div class="form-group col-md-6">
            <label>Spouse Name</label>
            <input type="text" name="spouse_name" class="form-control" value="<?php echo $success_message ? '' : htmlspecialchars($_POST['spouse_name'] ?? ''); ?>">
        </div>

        <div class="form-group col-md-6">
            <label>Number of Dependents *</label>
            <input type="number" name="number_of_dependents" class="form-control" required value="<?php echo $success_message ? '' : htmlspecialchars($_POST['number_of_dependents'] ?? ''); ?>">
        </div>

                <div class="form-group col-md-6">
                    <label>Shipping Method *</label>
                    <select name="shipping_method" class="form-control" required>
                        <option value="PICK UP" <?php if ($success_message) echo 'selected'; ?>>PICK UP (You can claim within 24 hours upon submission. Claimable from 10am-5pm)</option>
                    </select>
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

    <!-- Chatbot -->
    <iframe src="../chatbot.php"
        style="position: fixed; bottom: 10px; right: 10px; width: 340px; height: 800px; border: none; z-index: 999;">
    </iframe>
    
    <script src="../js/services.js"></script>

</body>

</html>