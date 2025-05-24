<?php
require_once('session_check.php');
check_user_session();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay_db";

try {
    
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    // Fetch user data
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    if (!$user) {
        die("User not found.");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'first_name',
        'middle_name',
        'last_name',
        'address',
        'email',
        'username',
        'dob',
        'gender',
        'civil_status',
        'government_id',
        'id_number',
        'emergency_contact_name',
        'emergency_contact_number'
    ];

    $update_fields = [];
    $update_values = [];

    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $update_fields[] = "$field = ?";
            $update_values[] = $_POST[$field];
        }
    }

    if (!empty($update_fields)) {
        $update_values[] = $_SESSION['user_id'];
        $sql = "UPDATE users SET " . implode(", ", $update_fields) . " WHERE id = ?";
        try {
            $stmt = $pdo->prepare($sql);
            $stmt->execute($update_values);
            $success = "Profile updated successfully.";
            // header("Location: profile.php");
            // exit();
        } catch (PDOException $e) {
            $error = "Error updating profile: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Edit Profile | Barangay Bucandala 1</title>
    <?php if (isset($success)): ?>
        <meta http-equiv="refresh" content="2;url=profile.php" />
    <?php endif; ?>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
        }
        .edit-profile-container {
            max-width: 700px;
            margin: 40px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-group label {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container edit-profile-container">
    <h2>Edit Profile</h2>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <form method="post" novalidate>
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required value="<?= htmlspecialchars($user['first_name']) ?>">
        </div>
        <div class="form-group">
            <label for="middle_name">Middle Name</label>
            <input type="text" class="form-control" id="middle_name" name="middle_name" value="<?= htmlspecialchars($user['middle_name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required value="<?= htmlspecialchars($user['last_name']) ?>">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" required value="<?= htmlspecialchars($user['address']) ?>">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>">
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required value="<?= htmlspecialchars($user['username']) ?>">
        </div>
        <div class="form-group">
            <label for="dob">Date of Birth</label>
            <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($user['dob'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label for="gender">Gender</label>
            <select class="form-control" id="gender" name="gender">
                <option value="" <?= empty($user['gender']) ? 'selected' : '' ?>>Select Gender</option>
                <option value="Male" <?= (isset($user['gender']) && $user['gender'] === 'Male') ? 'selected' : '' ?>>Male</option>
                <option value="Female" <?= (isset($user['gender']) && $user['gender'] === 'Female') ? 'selected' : '' ?>>Female</option>
                <option value="Other" <?= (isset($user['gender']) && $user['gender'] === 'Other') ? 'selected' : '' ?>>Other</option>
            </select>
        </div>
        <div class="form-group">
            <label for="civil_status">Civil Status</label>
            <input type="text" class="form-control" id="civil_status" name="civil_status" value="<?= htmlspecialchars($user['civil_status']) ?>">
        </div>
        <div class="form-group">
            <label for="government_id">Government ID</label>
            <input type="text" class="form-control" id="government_id" name="government_id" value="<?= htmlspecialchars($user['government_id']) ?>">
        </div>
        <div class="form-group">
            <label for="id_number">ID Number</label>
            <input type="text" class="form-control" id="id_number" name="id_number" value="<?= htmlspecialchars($user['id_number']) ?>">
        </div>
        <div class="form-group">
            <label for="emergency_contact_name">Emergency Contact Name</label>
            <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name" value="<?= htmlspecialchars($user['emergency_contact_name']) ?>">
        </div>
        <div class="form-group">
            <label for="emergency_contact_number">Emergency Contact Number</label>
            <input type="text" class="form-control" id="emergency_contact_number" name="emergency_contact_number" value="<?= htmlspecialchars($user['emergency_contact_number']) ?>">
        </div>
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="profile.php" class="btn btn-secondary ml-2">Cancel</a>
    </form>
</div>
</body>
</html>
