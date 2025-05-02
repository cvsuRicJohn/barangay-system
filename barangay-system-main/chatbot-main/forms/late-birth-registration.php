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

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $last_name = trim($_POST['last_name'] ?? '');
    $first_name = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $marital_status = trim($_POST['marital_status'] ?? '');
    $place_of_birth = trim($_POST['place_of_birth'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $fathers_name = trim($_POST['fathers_name'] ?? '');
    $mothers_name = trim($_POST['mothers_name'] ?? '');
    $years_in_barangay = trim($_POST['years_in_barangay'] ?? '');
    $purpose = trim($_POST['purpose'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $shipping_method = trim($_POST['shipping_method'] ?? '');

    if (
        empty($last_name) || empty($first_name) || empty($middle_name) || empty($address) ||
        empty($marital_status) || empty($place_of_birth) || empty($date_of_birth) || empty($fathers_name) ||
        empty($mothers_name) || empty($years_in_barangay) || empty($purpose) || empty($email) || empty($shipping_method)
    ) {
        $error_message = "Please fill in all required fields.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO late_birth_registration_requests 
                (last_name, first_name, middle_name, address, marital_status, place_of_birth, date_of_birth, fathers_name, mothers_name, years_in_barangay, purpose, email, shipping_method)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $last_name, $first_name, $middle_name, $address, $marital_status, $place_of_birth, $date_of_birth, $fathers_name, $mothers_name, $years_in_barangay, $purpose, $email, $shipping_method
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
    <title>Late Birth Registration Certificate Form</title>
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
<nav>
<a href="../index.php">Home</a>

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

  <a href="../contact.php">About</a>
  <a href="../faq.php">FAQs</a>
</nav>

    <!-- Cover Photo -->
    <div style="width: 100%; height: 300px; overflow: hidden; opacity: 0.6;">
    <img src="../image/duduy.jpg" alt="Cover Photo" style="width: 100%; height: 100%; object-fit: cover;">
    </div>

    <!-- Form Section -->
    <div class="container-fluid px-5 py-4">
        <h2 class="text-center mb-4">Late Birth Registration Certificate Form</h2>

        <?php if ($success_message): ?>
            <div class="alert alert-success text-center"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        
<!-- Late Birth Registration Certificate -->
<form method="POST" action="late-birth-registration.php" id="lateBirthForm">
    <div class="form-row">
        <div class="form-group col-md-4">
            <label>Last Name *</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>
        <div class="form-group col-md-4">
            <label>First Name *</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>
        <div class="form-group col-md-4">
            <label>Middle Name *</label>
            <input type="text" name="middle_name" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label>Address *</label>
            <input type="text" name="address" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label>Marital Status *</label>
            <input type="text" name="marital_status" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label>Place of Birth *</label>
            <input type="text" name="place_of_birth" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label>Date of Birth *</label>
            <input type="date" name="date_of_birth" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label>Father's Name *</label>
            <input type="text" name="fathers_name" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label>Mother's Name *</label>
            <input type="text" name="mothers_name" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label>Years in Barangay *</label>
            <input type="text" name="years_in_barangay" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label>Purpose *</label>
            <input type="text" name="purpose" class="form-control" required value="Late Registration of Birth Certificate">
        </div>
                <div class="form-group col-md-6">
                    <label>Email *</label>
                    <input type="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                </div>
                <div class="form-group col-md-6">
                    <label>Shipping Method *</label>
                    <select name="shipping_method" class="form-control" required>
                        <option value="PICK UP">PICK UP (You can claim within 24 hours upon submission. Claimable from 10am-5pm)</option>
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
    
    <script src="js/services.js"></script>

</body>

</html>