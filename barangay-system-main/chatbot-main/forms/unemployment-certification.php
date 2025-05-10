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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = trim($_POST['full_name'] ?? '');
    $age = trim($_POST['age'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $civil_status = trim($_POST['civil_status'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $purpose = trim($_POST['purpose'] ?? '');
    $shipping_method = trim($_POST['shipping_method'] ?? '');

    if (
        empty($full_name) || empty($age) || empty($birth_date) || empty($civil_status) || empty($address) || empty($purpose) || empty($shipping_method)
    ) {
        $error_message = "Please fill in all required fields.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO unemployment_certification_requests 
                (full_name, age, birth_date, civil_status, address, purpose, shipping_method)
                VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $full_name, $age, $birth_date, $civil_status, $address, $purpose, $shipping_method
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
    <title> Certificate of Unemployment Form</title>
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
        <h2 class="text-center mb-4"> Certificate of Unemployment  Form</h2>

        <?php if ($success_message): ?>
            <div class="alert alert-success text-center"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger text-center"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        

<form method="POST" action="unemployment-certification.php" id="unemployedForm">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Full Name *</label>
            <input type="text" name="full_name" class="form-control" required value="<?php echo htmlspecialchars($_POST['full_name'] ?? ($user_data ? trim($user_data['first_name'] . ' ' . ($user_data['middle_name'] ?? '') . ' ' . $user_data['last_name']) : '')); ?>">
        </div>
        <div class="form-group col-md-3">
            <label>Age *</label>
            <?php
                $age_value = '';
                if (isset($_POST['age'])) {
                    $age_value = $_POST['age'];
                } elseif (!empty($user_data['dob'])) {
                    $dob = new DateTime($user_data['dob']);
                    $today = new DateTime();
                    $age_value = $today->diff($dob)->y;
                }
            ?>
            <input type="number" name="age" class="form-control" required value="<?php echo htmlspecialchars($age_value); ?>">
        </div>
        <div class="form-group col-md-3">
            <label>Date of Birth *</label>
            <?php
                $dob_value = $_POST['birth_date'] ?? ($user_data['dob'] ?? '');
            ?>
            <input type="date" name="birth_date" class="form-control" required value="<?php echo htmlspecialchars($dob_value); ?>">
        </div>
        <div class="form-group col-md-6">
            <label>Civil Status *</label>
            <select name="civil_status" class="form-control" required>
                <option value="">-- Select --</option>
                <option value="Single" <?php echo (($_POST['civil_status'] ?? '') === 'Single') ? 'selected' : ''; ?>>Single</option>
                <option value="Married" <?php echo (($_POST['civil_status'] ?? '') === 'Married') ? 'selected' : ''; ?>>Married</option>
                <option value="Separated" <?php echo (($_POST['civil_status'] ?? '') === 'Separated') ? 'selected' : ''; ?>>Separated</option>
                <option value="Widow/Widower" <?php echo (($_POST['civil_status'] ?? '') === 'Widow/Widower') ? 'selected' : ''; ?>>Widow/Widower</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label>Address *</label>
            <input type="text" name="address" class="form-control" required value="<?php echo htmlspecialchars($_POST['address'] ?? ($user_data['address'] ?? '')); ?>">
        </div>
        <div class="form-group col-md-12">
            <label>Purpose of Certification *</label>
            <input type="text" name="purpose" class="form-control" required value="<?php echo htmlspecialchars($_POST['purpose'] ?? ''); ?>">
        </div>
        <div class="form-group col-md-6">
            <label>Shipping Method *</label>
            <select name="shipping_method" class="form-control" required>
                <option value="PICK UP">PICK UP (You can claim within 24 hours upon submission. Claimable from 10am–5pm)</option>
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

        // Event listener to update age when birth_date changes
        document.addEventListener('DOMContentLoaded', function () {
            const birthDateInput = document.querySelector('input[name="birth_date"]');
            const ageInput = document.querySelector('input[name="age"]');

            if (birthDateInput && ageInput) {
                function updateAge() {
                    const birthDateValue = birthDateInput.value;
                    console.log("Birthdate changed to:", birthDateValue);
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
                }
                birthDateInput.addEventListener('change', updateAge);
                birthDateInput.addEventListener('input', updateAge);

                // Optionally, trigger updateAge on page load if birth_date has a value
                if (birthDateInput.value) {
                    updateAge();
                }
            }
        });
    </script>

</body>

</html>
