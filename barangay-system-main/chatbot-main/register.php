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
    $dependents = isset($_POST['dependents']) ? trim($_POST['dependents']) : null;
    $firstName = trim($_POST['firstName']);
    $middleName = trim($_POST['middleName']);
    $lastName = trim($_POST['lastName']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $usernameReg = trim($_POST['usernameReg']);
    $passwordReg = trim($_POST['passwordReg']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $dob = trim(string: $_POST['dob']);
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
        $emailExists = false;
        $usernameExists = false;

        $stmt = $pdo->prepare("SELECT username, email FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$usernameReg, $email]);

        while ($row = $stmt->fetch()) {
            if ($row['username'] === $usernameReg) {
                $usernameExists = true;
            }
            if ($row['email'] === $email) {
                $emailExists = true;
            }
        }

        if ($emailExists && $usernameExists) {
            $register_error = "Both email and username already exist.";
        } elseif ($emailExists) {
            $register_error = "Email already exists.";
        } elseif ($usernameExists) {
            $register_error = "Username already exists.";
        } else {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }

            $targetFile = $targetDir . basename($profilePic);

            if (move_uploaded_file($_FILES["profilePic"]["tmp_name"], $targetFile) || empty($profilePic)) {
                $hashed_password = password_hash($passwordReg, PASSWORD_DEFAULT);
                $stmt_insert = $pdo->prepare("INSERT INTO users (first_name, middle_name, last_name, address, email, username, password, dob, gender, civil_status, government_id, id_number, emergency_contact_name, emergency_contact_number, profile_pic, dependents, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
                if ($stmt_insert->execute([$firstName, $middleName, $lastName, $address, $email, $usernameReg, $hashed_password, $dob, $gender, $civilStatus, $governmentId, $idNumber, $emergencyContactName, $emergencyContactNumber, $profilePic, $dependents])) {
                    $register_success = "Account created successfully! You can now login.";
                    echo '<script>
                        setTimeout(function() {
                            window.location.href = "login.php";
                        }, 2000);
                    </script>';
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
            background-size: cover;
            background-attachment: fixed;
            min-height: 100vh;
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
            overflow-y: auto;
            max-height: 100vh;
        }

        .register-card h3 {
            margin-bottom: 5px;
            text-align: center;
        }

        .form-control {
            border-radius: 8px;
            margin-bottom: 5px;
            font-size: 15px;
        }

        .form-row {
            margin-bottom: 1rem;
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

            <!-- Personal Information -->
            <div class="form-row mb-3">
                <div class="col-md-4">
                    <input type="text" name="firstName" class="form-control" placeholder="First Name" required value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : ''; ?>">
                </div>
                <div class="col-md-4">
                    <input type="text" name="middleName" class="form-control" placeholder="Middle Name" required value="<?php echo isset($_POST['middleName']) ? htmlspecialchars($_POST['middleName']) : ''; ?>">
                </div>
                <div class="col-md-4">
                    <input type="text" name="lastName" class="form-control" placeholder="Last Name" required value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : ''; ?>">
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-4">
                    <input type="date" name="dob" class="form-control" required value="<?php echo isset($_POST['dob']) ? htmlspecialchars($_POST['dob']) : ''; ?>">
                </div>
                <div class="col-md-8">
                    <input type="text" name="address" class="form-control" placeholder="Address" required value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
                </div>
            </div>

            <!-- Account Details -->
            <div class="form-row mb-3">
                <div class="col-md-6">
                    <input type="email" id="email" name="email" class="form-control <?php echo ($register_error === "Email already exists." || $register_error === "Both email and username already exist.") ? 'is-invalid' : ''; ?>" placeholder="Email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <?php if ($register_error === "Email already exists." || $register_error === "Both email and username already exist."): ?>
                        <small class="text-danger">Email already exists.</small>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <input type="text" name="usernameReg" class="form-control <?php echo ($register_error === "Username already exists." || $register_error === "Both email and username already exist.") ? 'is-invalid' : ''; ?>" placeholder="Username" required value="<?php echo isset($_POST['usernameReg']) ? htmlspecialchars($_POST['usernameReg']) : ''; ?>">
                    <?php if ($register_error === "Username already exists." || $register_error === "Both email and username already exist."): ?>
                        <small class="text-danger">Username already exists.</small>
                    <?php endif; ?>
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-4">
                    <input type="password" name="passwordReg" class="form-control" placeholder="Password" required>
                </div>
                <div class="col-md-4">
                    <input type="password" name="confirmPassword" class="form-control" placeholder="Confirm Password" required>
                </div>
                <div class="col-md-4">
                    <select name="gender" class="form-control" required>
                        <option value="">Gender</option>
                        <option value="Male" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (isset($_POST['gender']) && $_POST['gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-4">
                    <select name="civilStatus" class="form-control" required>
                        <option value="">Civil Status</option>
                        <?php
                        $statuses = ['Single', 'Married', 'Widowed'];
                        foreach ($statuses as $status) {
                            $selected = (isset($_POST['civilStatus']) && $_POST['civilStatus'] === $status) ? 'selected' : '';
                            echo "<option value=\"$status\" $selected>$status</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="governmentId" class="form-control" required>
                        <option value="">Government ID</option>
                        <?php
                        $ids = ['Philippine National ID', 'UMID', 'Driver’s License', 'Passport', 'Voter’s ID', 'PhilHealth ID', 'Postal ID', 'Senior Citizen ID', 'PWD ID', 'SSS ID', 'Pag-IBIG Loyalty Card'];
                        foreach ($ids as $id) {
                            $selected = (isset($_POST['governmentId']) && $_POST['governmentId'] === $id) ? 'selected' : '';
                            echo "<option value=\"$id\" $selected>$id</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <input type="text" name="idNumber" class="form-control" placeholder="ID Number" required value="<?php echo isset($_POST['idNumber']) ? htmlspecialchars($_POST['idNumber']) : ''; ?>">
                </div>
            </div>

            <div class="form-row mb-3">
                <div class="col-md-6">
                    <input type="text" name="emergencyContactName" class="form-control" placeholder="Emergency Contact Name" required value="<?php echo isset($_POST['emergencyContactName']) ? htmlspecialchars($_POST['emergencyContactName']) : ''; ?>">
                </div>
                <div class="col-md-6">
                    <input type="text" name="emergencyContactNumber" class="form-control" placeholder="Emergency Contact Number" required value="<?php echo isset($_POST['emergencyContactNumber']) ? htmlspecialchars($_POST['emergencyContactNumber']) : ''; ?>">
                </div>
            </div>

            <div class="form-group">
    <label for="dependents">Dependents (Optional)</label>
    <textarea name="dependents" class="form-control" rows="2" placeholder="Enter names of dependents, if any"><?php echo isset($_POST['dependents']) ? htmlspecialchars($_POST['dependents']) : ''; ?></textarea>
</div>


            <div class="form-group mt-3">
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

</body>
</html>
