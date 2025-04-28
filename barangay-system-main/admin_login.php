<?php
session_start();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            $login_error = "Username and password are required.";
        } else {
            // Check admin credentials
            $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ? AND is_admin = 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $username;
                header("Location: admin_page.php");
                exit();
            } else {
                $login_error = "Invalid admin username or password.";
            }
        }
    } elseif (isset($_POST['register'])) {
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        $usernameReg = trim($_POST['usernameReg']);
        $passwordReg = trim($_POST['passwordReg']);
        $confirmPassword = trim($_POST['confirmPassword']);

        if (empty($firstName) || empty($lastName) || empty($email) || empty($usernameReg) || empty($passwordReg) || empty($confirmPassword)) {
            $register_error = "All fields are required.";
        } elseif ($passwordReg !== $confirmPassword) {
            $register_error = "Passwords do not match.";
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND is_admin = 1");
            $stmt->execute([$usernameReg, $email]);
            if ($stmt->fetch()) {
                $register_error = "Username or email already exists.";
            } else {
                $hashed_password = password_hash($passwordReg, PASSWORD_DEFAULT);
                $stmt_insert = $pdo->prepare("INSERT INTO users (first_name, last_name, email, username, password, is_admin) VALUES (?, ?, ?, ?, ?, 1)");
                if ($stmt_insert->execute([$firstName, $lastName, $email, $usernameReg, $hashed_password])) {
                    $register_success = "Admin account created successfully! You can now login.";
                } else {
                    $register_error = "Error creating account. Please try again.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Login - Barangay Bucandala 1</title>
  <link rel="stylesheet" href="chatbot-main/css/contact.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: url('chatbot-main/image/landmark.JPG') no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-container {
      background-color: rgba(255, 255, 255, 0.9);
      padding: 30px;
      border-radius: 10px;
      width: 100%;
      max-width: 400px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #007bff;
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
  </style>
</head>
<body>
    <div class="login-container" id="loginFormContainer" <?php if(isset($_POST['register'])) echo 'style="display:none;"'; ?>>
      <h2>Admin Login</h2>
      <?php if ($login_error): ?>
        <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
      <?php endif; ?>
      <form action="admin_login.php" method="POST" novalidate>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" class="form-control" placeholder="Enter admin username" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary">Login</button>
      </form>
      <div class="switch-btn">
        <button class="btn btn-link" id="switchToRegister">Don't have an account? Register here</button>
      </div>
      <div class="switch-btn" style="margin-top: 10px;">
        <a href="chatbot-main/login.php" class="btn btn-link">Back to User Login</a>
      </div>
    </div>
  <div class="register-container" id="registerFormContainer" <?php if(!isset($_POST['register'])) echo 'style="display:none;"'; ?>>
    <h2>Admin Register</h2>
    <?php if ($register_error): ?>
      <div class="error"><?php echo htmlspecialchars($register_error); ?></div>
    <?php elseif ($register_success): ?>
      <div class="success"><?php echo htmlspecialchars($register_success); ?></div>
    <?php endif; ?>
    <form action="admin_login.php" method="POST" novalidate>
      <div class="form-group">
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" name="firstName" class="form-control" placeholder="Enter first name" required value="<?php echo isset($_POST['firstName']) ? htmlspecialchars($_POST['firstName']) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="lastName">Last Name</label>
        <input type="text" id="lastName" name="lastName" class="form-control" placeholder="Enter last name" required value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName']) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" class="form-control" placeholder="Enter email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="usernameReg">Username</label>
        <input type="text" id="usernameReg" name="usernameReg" class="form-control" placeholder="Choose a username" required value="<?php echo isset($_POST['usernameReg']) ? htmlspecialchars($_POST['usernameReg']) : ''; ?>">
      </div>
      <div class="form-group">
        <label for="passwordReg">Password</label>
        <input type="password" id="passwordReg" name="passwordReg" class="form-control" placeholder="Choose a password" required>
      </div>
      <div class="form-group">
        <label for="confirmPassword">Confirm Password</label>
        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control" placeholder="Confirm password" required>
      </div>
      <button type="submit" name="register" class="btn btn-primary">Register</button>
    </form>
    <div class="switch-btn">
      <button class="btn btn-link" id="switchToLogin">Already have an account? Login here</button>
    </div>
  </div>
  </body>
  <script>
    document.getElementById('switchToRegister').addEventListener('click', function() {
      document.getElementById('loginFormContainer').style.display = 'none';
      document.getElementById('registerFormContainer').style.display = 'block';
    });
    document.getElementById('switchToLogin').addEventListener('click', function() {
      document.getElementById('registerFormContainer').style.display = 'none';
      document.getElementById('loginFormContainer').style.display = 'block';
    });
</script>

<?php
// Add view buttons for each service and make them functional
// Assuming admin_page.php lists the services, we add view buttons there
// Here, we add a simple example of how to handle view requests

if (isset($_GET['view'])) {
    $service = $_GET['view'];

    // Sanitize input
    $allowed_services = [
        'barangay_id_requests',
        'barangay_clearance',
        'certificate_of_indigency_requests',
        'certificate_of_residency_requests'
    ];

    if (in_array($service, $allowed_services)) {
        // Fetch service details from database
        $stmt = $pdo->prepare("SELECT * FROM $service");
        $stmt->execute();
        $results = $stmt->fetchAll();

        echo "<h2>Viewing: " . htmlspecialchars($service) . "</h2>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        if (!empty($results)) {
            // Table headers
            echo "<tr>";
            foreach (array_keys($results[0]) as $header) {
                echo "<th>" . htmlspecialchars($header) . "</th>";
            }
            echo "</tr>";

            // Table rows
            foreach ($results as $row) {
                echo "<tr>";
                foreach ($row as $value) {
                    echo "<td>" . htmlspecialchars($value) . "</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td>No records found.</td></tr>";
        }
        echo "</table>";
        echo "<br><a href='admin_page.php'>Back to Admin Page</a>";
        exit();
    } else {
        echo "Invalid service selected.";
        exit();
    }
}
?>

</html>
