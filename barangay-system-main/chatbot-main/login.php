<?php
session_start();

// Database connection
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

$login_error = "";

// Handle login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $login_error = "Username and password are required.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password, is_admin FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]); // allow login via username OR email
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            if (!empty($user['is_admin']) && $user['is_admin'] == 1) {
                $_SESSION['admin_id'] = $user['id'];
                header("Location: ../admin_page.php");
                exit();
            }

            header("Location: index.php");
            exit();
        } else {
            $login_error = "Invalid username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login | Barangay Bucandala 1</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="image/logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css" />
</head>
<body>
    <div class="container-fluid">
        <div class="row vh-100 align-items-center justify-content-center">
            <div class="col-md-6 text-center text-md-left px-5 mb-5 mb-md-0">
                <h1 class="facebook-logo mb-3">Welcome to Official Website of Barangay Bucandala 1</h1>
                <p class="lead">Barangay Bucandala 1 is determined to address everything that hinders its way to be the best.</p>
            </div>
            <div class="col-md-4">
                <div class="login-card p-4 shadow-sm">
                    <?php if ($login_error): ?>
                        <div class="alert alert-danger"><?php echo htmlspecialchars($login_error); ?></div>
                    <?php endif; ?>
                    <form action="login.php" method="POST">
                        <div class="form-group">
                            <input type="text" class="form-control mb-3" name="username" placeholder="Email or username" required>
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control mb-3" name="password" placeholder="Password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mb-3" name="login">Log In</button>
                        <div class="text-center mb-3">
                            <a href="#" class="text-primary">Forgot password?</a>
                        </div>
                        <hr />
                        <div class="text-center">
                            <a href="register.php" class="btn btn-success btn-block">Create new account</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
