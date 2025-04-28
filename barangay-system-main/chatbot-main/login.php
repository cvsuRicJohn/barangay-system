<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
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

$login_error = "";
$register_error = "";
$register_success = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $login_error = "Username and password are required.";
    } else {
        // Check user credentials
        $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;

            // Redirect regular users to homepage or user dashboard
            header("Location: index.php");
            exit();
        } else {
            $login_error = "Invalid username or password.";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $address = trim($_POST['address']);
    $email = trim($_POST['email']);
    $usernameReg = trim($_POST['usernameReg']);
    $passwordReg = trim($_POST['passwordReg']);
    $confirmPassword = trim($_POST['confirmPassword']);

    if (empty($firstName) || empty($lastName) || empty($address) || empty($email) || empty($usernameReg) || empty($passwordReg) || empty($confirmPassword)) {
        $register_error = "All fields are required.";
    } elseif ($passwordReg !== $confirmPassword) {
        $register_error = "Passwords do not match.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$usernameReg, $email]);
        if ($stmt->fetch()) {
            $register_error = "Username or email already exists.";
        } else {
            $hashed_password = password_hash($passwordReg, PASSWORD_DEFAULT);
            $stmt_insert = $pdo->prepare("INSERT INTO users (first_name, last_name, address, email, username, password) VALUES (?, ?, ?, ?, ?, ?)");
            if ($stmt_insert->execute([$firstName, $lastName, $address, $email, $usernameReg, $hashed_password])) {
                $register_success = "Account created successfully! You can now login.";
            } else {
                $register_error = "Error creating account. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login | Barangay Bucandala 1</title>
    <link rel="icon" type="image/png" href="image/logo.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('image/landmark.JPG') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .logout-link {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: #dc3545;
            color: white !important;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            z-index: 1000;
        }
        .logout-link:hover {
            background-color: #c82333;
            color: white !important;
            text-decoration: none;
        }

        .login-container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .login-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-control {
            border-radius: 5px;
            padding: 10px;
        }

        .btn-primary {
            width: 100%;
            padding: 10px;
            background-color: #19b824;
            border: none;
            color: white;
            border-radius: 5px;
            font-size: 16px;
        }

        .btn-primary:hover {
            background-color: #17a823;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            text-align: center;
        }

        /* Position the admin login button container at top right */
        .admin-login-btn-container {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .admin-login-btn-container a {
            background-color: #dc3545; /* Bootstrap danger red */
            color: white !important;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
        }
        .admin-login-btn-container a:hover {
            background-color: #c82333;
            color: white !important;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="login-container" id="loginFormContainer" <?php if(isset($_POST['register'])) echo 'style="display:none;"'; ?>>
        <h2>Login</h2>
        <?php if ($login_error): ?>
            <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>
        <form id="loginForm" action="login.php" method="POST" novalidate>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn btn-primary" name="login">Login</button>
        </form>

        <div class="switch-btn">
            <button class="btn btn-link" id="switchToRegister">Don't have an account? Create one</button>
        </div>
        <div class="switch-btn admin-login-btn-container" style="margin-top: 10px;">
            <a href="../admin_login.php" class="btn btn-link">Admin Login</a>
        </div>
    </div>

    <div class="register-container" id="registerFormContainer" <?php if(!isset($_POST['register'])) echo 'style="display:none;"'; ?>>
        <h2>Create an Account</h2>
        <?php if ($register_error): ?>
            <div class="error"><?php echo htmlspecialchars($register_error); ?></div>
        <?php elseif ($register_success): ?>
            <div class="success"><?php echo htmlspecialchars($register_success); ?></div>
        <?php endif; ?>
    <form id="registerForm" action="login.php" method="POST" novalidate>
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" class="form-control" id="firstName" name="firstName" placeholder="Enter your first name" required value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : ''; ?>">
        </div>

        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Enter your last name" required value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : ''; ?>">
        </div>


        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" placeholder="Enter your address" required value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
        </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="usernameReg">Username</label>
                <input type="text" class="form-control" id="usernameReg" name="usernameReg" placeholder="Choose a username" required value="<?php echo isset($_POST['usernameReg']) ? htmlspecialchars($_POST['usernameReg']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="passwordReg">Password</label>
                <input type="password" class="form-control" id="passwordReg" name="passwordReg" placeholder="Choose a password" required>
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
            </div>

            <button type="submit" class="btn btn-primary" name="register">Create Account</button>
        </form>

        <div class="switch-btn">
            <button class="btn btn-link" id="switchToLogin">Already have an account? Login</button>
        </div>
    </div>

    <script>
        // Switch to registration form
        document.getElementById('switchToRegister').addEventListener('click', function() {
            document.getElementById('loginFormContainer').style.display = 'none';
            document.getElementById('registerFormContainer').style.display = 'block';
        });

        // Switch to login form
        document.getElementById('switchToLogin').addEventListener('click', function() {
            document.getElementById('registerFormContainer').style.display = 'none';
            document.getElementById('loginFormContainer').style.display = 'block';
        });
    </script>

</body>
</html>