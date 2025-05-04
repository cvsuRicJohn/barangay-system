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

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile | Barangay Bucandala 1</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f6fa;
        }
        .cover-photo {
            width: 100%;
            height: 250px;
            background: url('image/cover.jpg') center center no-repeat;
            background-size: cover;
            position: relative;
        }
        .profile-pic {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 5px solid #fff;
            position: absolute;
            bottom: -70px;
            left: 30px;
            background: #fff;
            object-fit: cover;
        }
        .profile-header {
            padding: 80px 30px 30px;
            background-color: #fff;
            border-radius: 8px;
            margin-top: 80px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .info-group {
            margin-top: 20px;
        }
        .info-title {
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="cover-photo">
    <img src="<?= !empty($user['profile_pic']) ? 'uploads/' . htmlspecialchars($user['profile_pic']) : 'image/default-avatar.png'; ?>" class="profile-pic" alt="Profile Picture">
</div>

<div class="container">
    <div class="profile-header">
        <div class="row">
            <div class="col-md-8">
<h3><?= htmlspecialchars($user['first_name'] . ' ' . ($user['middle_name'] ?? '') . ' ' . $user['last_name']) ?></h3>
                <div class="info-group">
                    <p><span class="info-title">Address:</span> <?= htmlspecialchars($user['address']) ?></p>
                    <p><span class="info-title">Email:</span> <?= htmlspecialchars($user['email']) ?></p>
                    <p><span class="info-title">Username:</span> <?= htmlspecialchars($user['username']) ?></p>
                    <p><span class="info-title">Role:</span> <?= !empty($user['is_admin']) && $user['is_admin'] == 1 ? 'Admin' : 'User' ?></p>
<p><span class="info-title">Date of Birth:</span> <?= htmlspecialchars($user['dob'] ?? '') ?></p>
<p><span class="info-title">Gender:</span> <?= htmlspecialchars($user['gender'] ?? '') ?></p>
                    <p><span class="info-title">Civil Status:</span> <?= htmlspecialchars($user['civil_status']) ?></p>
                    <p><span class="info-title">Government ID:</span> <?= htmlspecialchars($user['government_id']) ?></p>
                    <p><span class="info-title">ID Number:</span> <?= htmlspecialchars($user['id_number']) ?></p>
                    <p><span class="info-title">Emergency Contact Name:</span> <?= htmlspecialchars($user['emergency_contact_name']) ?></p>
                    <p><span class="info-title">Emergency Contact Number:</span> <?= htmlspecialchars($user['emergency_contact_number']) ?></p>
                </div>
            </div>
<div class="col-md-4 text-right">
    <a href="index.php" class="btn btn-primary ml-2">Home</a>
    <a href="edit_profile.php" class="btn btn-warning ml-2">Edit</a>
    <a href="login.php?action=logout" class="btn btn-danger ml-2">Logout</a>
</div>
        </div>
    </div>
</div>

</body>
</html>
