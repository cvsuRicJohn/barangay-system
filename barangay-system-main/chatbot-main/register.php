<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: login.php");
    exit();
}

// DB Connection
$servername = "localhost";
$username_db = "root";
$password_db = "";
$dbname = "barangay_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username_db, $password_db, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$register_error = "";
$register_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $firstName = trim($_POST['firstName']);
    $middleName = trim($_POST['middleName']);
    $lastName = trim($_POST['lastName']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $usernameReg = trim($_POST['usernameReg']);
    $passwordReg = trim($_POST['passwordReg']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $civilStatus = trim($_POST['civilStatus']);
    $governmentId = trim($_POST['governmentId']);
    $idNumber = trim($_POST['idNumber']);
    $emergencyContactName = trim($_POST['emergencyContactName']);
    $emergencyContactNumber = trim($_POST['emergencyContactNumber']);
    $profilePic = $_FILES['profilePic']['name'];

    if (empty($firstName) || empty($lastName) || empty($address) || empty($email) || empty($usernameReg) || empty($passwordReg) || empty($confirmPassword)) {
        $register_error = "All fields are required.";
    } elseif (!preg_match('/^(?=.*[A-Z]).{8,}$/', $passwordReg)) {
        $register_error = "Password must be at least 8 characters long and contain at least one uppercase letter.";
    } elseif ($passwordReg !== $confirmPassword) {
        $register_error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$usernameReg, $email]);
        if ($stmt->fetch()) {
            $register_error = "Username or email already exists.";
        } else {
            $targetDir = "uploads/";
            // Create the uploads directory if it doesn't exist
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $targetFile = $targetDir . basename($profilePic);
            if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $targetFile)) {
                $hashed_password = password_hash($passwordReg, PASSWORD_DEFAULT);
                $stmt_insert = $pdo->prepare("INSERT INTO users (first_name, middle_name, last_name, address, email, username, password, dob, gender, civil_status, government_id, id_number, emergency_contact_name, emergency_contact_number, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt_insert->execute([$firstName, $middleName, $lastName, $address, $email, $usernameReg, $hashed_password, $dob, $gender, $civilStatus, $governmentId, $idNumber, $emergencyContactName, $emergencyContactNumber, $profilePic])) {
                    $register_success = "Account created successfully! You can now login.";
                } else {
                    $register_error = "Error creating account. Please try again.";
                }
            } else {
                $register_error = "Failed to upload profile picture.";
            }
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Register | Barangay Bucandala 1</title>
    <link rel="icon" type="image/png" href="image/logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .main-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .left-panel {
            width: 50%;
            background: url('image/landmark.JPG') no-repeat center center;
            background-size: cover;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .left-panel img {
            width: 100px;
            margin-bottom: 20px;
        }

        .right-panel {
            width: 50%;
            padding: 40px;
            background-color: #fff;
        }

        .register-card h3 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-control {
            border-radius: 8px;
            margin-bottom: 15px;
            font-size: 15px;
        }

        .btn-block {
            border-radius: 8px;
            padding: 10px;
        }

        .alert {
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }

            .left-panel {
                display: none;
            }

            .right-panel {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="main-container">
    <div class="left-panel d-none d-md-flex">
        <div class="text-center">
            <img src="image/logo.png" alt="Barangay Logo" />
            <h2>Barangay Bucandala 1</h2>
            <p>Online Registration System</p>
        </div>
    </div>

    <div class="right-panel">
        <div class="register-card">
            <h3>Create Your Account</h3>

            <?php if ($register_error): ?>
                <div class="alert alert-danger text-center"><?php echo htmlspecialchars($register_error); ?></div>
            <?php elseif ($register_success): ?>
                <div class="alert alert-success text-center"><?php echo htmlspecialchars($register_success); ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php" enctype="multipart/form-data">
                <div class="form-row">
                    <div class="col-md-4"><input type="text" name="firstName" class="form-control" placeholder="First Name" required></div>
                    <div class="col-md-4"><input type="text" name="middleName" class="form-control" placeholder="Middle Name" required></div>
                    <div class="col-md-4"><input type="text" name="lastName" class="form-control" placeholder="Last Name" required></div>
                </div>

                <input type="date" name="dob" class="form-control" required>
                <input type="text" name="address" class="form-control" placeholder="Address" required>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
                <input type="text" name="usernameReg" class="form-control" placeholder="Username" required>

                <div class="form-row">
                    <div class="col-md-6"><input type="password" name="passwordReg" class="form-control" placeholder="Password" required></div>
                    <div class="col-md-6"><input type="password" name="confirmPassword" class="form-control" placeholder="Confirm Password" required></div>
                </div>

                <select name="gender" class="form-control" required>
                    <option value="">Select Gender</option>
                    <option>Male</option>
                    <option>Female</option>
                </select>

                <select name="civilStatus" class="form-control" required>
                    <option value="">Civil Status</option>
                    <option>Single</option>
                    <option>Married</option>
                    <option>Widowed</option>
                </select>

                <input type="text" name="governmentId" class="form-control" placeholder="Government ID" required>
                <input type="text" name="idNumber" class="form-control" placeholder="ID Number" required>

                <div class="form-row">
                    <div class="col-md-6"><input type="text" name="emergencyContactName" class="form-control" placeholder="Emergency Contact Name" required></div>
                    <div class="col-md-6"><input type="text" name="emergencyContactNumber" class="form-control" placeholder="Emergency Contact Number" required></div>
                </div>

                <div class="form-group">
                    <label for="profilePic">Profile Picture (optional)</label>
                    <input type="file" name="profilePic" class="form-control-file">
                </div>

                <button type="submit" name="register" class="btn btn-success btn-block">Register</button>

                <div class="text-center mt-3">
                    <small>Already have an account? <a href="login.php">Login here</a></small>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
