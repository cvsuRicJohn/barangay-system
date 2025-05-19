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
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/profile.css" />
</head>
<body>
    
    <!-- Footer Actions -->
    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-primary me-2">🏠 Home</a>
        <a href="login.php?action=logout" class="btn btn-danger">🔓 Logout</a>
    </div>
<div class="container mt-5">
    <!-- Profile Card -->
    <div class="profile-card">
        <div class="d-flex justify-content-between align-items-center profile-header">
            <div class="d-flex align-items-center gap-3">
                <img src="<?= !empty($user['profile_pic']) ? 'uploads/' . htmlspecialchars($user['profile_pic']) : 'image/default-avatar.png'; ?>" class="profile-img" alt="Profile Picture">
                <div>
                    <h5 class="mb-1"><?= htmlspecialchars($user['first_name'] . ' ' . ($user['middle_name'] ?? '') . ' ' . $user['last_name']) ?></h5>
                    <div class="text-muted"><?= htmlspecialchars($user['role'] ?? 'User') ?></div>
                    <div class="text-muted small"><?= htmlspecialchars($user['address']) ?></div>
                </div>
            </div>
            <div class="text-end">
                <a href="edit_profile.php" class="btn btn-outline-secondary btn-sm edit-btn">✏️ Edit</a>
            </div>
        </div>
    </div>

    <!-- Personal Information -->
    <div class="profile-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="section-title">Personal Information</div>
            <a href="edit_profile.php" class="btn btn-outline-secondary btn-sm edit-btn">✏️ Edit</a>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2"><span class="info-label">Email address</span><br><span class="info-value"><?= htmlspecialchars($user['email']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Username</span><br><span class="info-value"><?= htmlspecialchars($user['username']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Date of Birth</span><br><span class="info-value"><?= htmlspecialchars($user['dob'] ?? '') ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Gender</span><br><span class="info-value"><?= htmlspecialchars($user['gender'] ?? '') ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Civil Status</span><br><span class="info-value"><?= htmlspecialchars($user['civil_status']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Gov’t ID Type</span><br><span class="info-value"><?= htmlspecialchars($user['government_id']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">ID Number</span><br><span class="info-value"><?= htmlspecialchars($user['id_number']) ?></span></div>
        </div>
    </div>

    <!-- Address & Emergency Contact -->
    <div class="profile-card">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="section-title">Address & Emergency Contact</div>
            <a href="edit_profile.php" class="btn btn-outline-secondary btn-sm edit-btn">✏️ Edit</a>
        </div>
        <div class="row">
            <div class="col-md-6 mb-2"><span class="info-label">Address</span><br><span class="info-value"><?= htmlspecialchars($user['address']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Emergency Contact Name</span><br><span class="info-value"><?= htmlspecialchars($user['emergency_contact_name']) ?></span></div>
            <div class="col-md-6 mb-2"><span class="info-label">Emergency Contact Number</span><br><span class="info-value"><?= htmlspecialchars($user['emergency_contact_number']) ?></span></div>
        </div>
    </div>
<?php
// Fetch form submission statuses for the user
$formTables = [
    'barangay_id_requests',
    'barangay_clearance',
    'certificate_of_indigency_requests',
    'certificate_of_residency_requests',
    'baptismal_certification_requests',
    'certificate_of_good_moral_requests',
    'cohabitation_certification_requests',
    'construction_clearance_requests',
    'first_time_job_seeker_requests',
    'late_birth_registration_requests',
    'non_residency_certification_requests',
    'no_income_certification_requests',
    'out_of_school_youth_requests',
    'solo_parent_requests',
    'unemployment_certification_requests'
];

$formStatuses = [];
foreach ($formTables as $table) {
    // Try to fetch submissions by user_id or username if user_id column is missing
    $columnCheckStmt = $pdo->prepare("SHOW COLUMNS FROM $table LIKE 'user_id'");
    $columnCheckStmt->execute();
    $hasUserId = $columnCheckStmt->fetch();

    if ($hasUserId) {
        $columnsStmt = $pdo->prepare("SHOW COLUMNS FROM $table");
        $columnsStmt->execute();
        $columns = $columnsStmt->fetchAll(PDO::FETCH_COLUMN);

        $statusColumn = in_array('status', $columns) ? 'status' : (in_array('request_status', $columns) ? 'request_status' : null);
        $submittedAtColumn = in_array('submitted_at', $columns) ? 'submitted_at' : (in_array('created_at', $columns) ? 'created_at' : null);

        if ($statusColumn && $submittedAtColumn) {
            $stmt = $pdo->prepare("SELECT id, $statusColumn AS status, $submittedAtColumn AS submitted_at FROM $table WHERE user_id = ? ORDER BY $submittedAtColumn DESC");
            $stmt->execute([$_SESSION['user_id']]);
            $results = $stmt->fetchAll();
            if (!empty($results)) {
                $formStatuses[$table] = $results;
            }
        }
    } else {
        // If no user_id column, try to fetch by username column if exists
        $columnCheckUsername = $pdo->prepare("SHOW COLUMNS FROM $table LIKE 'username'");
        $columnCheckUsername->execute();
        $hasUsername = $columnCheckUsername->fetch();

        if ($hasUsername) {
            // Determine status and submitted_at columns dynamically
            $columnsStmt = $pdo->prepare("SHOW COLUMNS FROM $table");
            $columnsStmt->execute();
            $columns = $columnsStmt->fetchAll(PDO::FETCH_COLUMN);

            $statusColumn = in_array('status', $columns) ? 'status' : (in_array('request_status', $columns) ? 'request_status' : null);
            $submittedAtColumn = in_array('submitted_at', $columns) ? 'submitted_at' : (in_array('created_at', $columns) ? 'created_at' : null);

            if ($statusColumn && $submittedAtColumn) {
                $stmt = $pdo->prepare("SELECT id, $statusColumn AS status, $submittedAtColumn AS submitted_at FROM $table WHERE username = ? ORDER BY $submittedAtColumn DESC");
                $stmt->execute([$user['username']]);
                $results = $stmt->fetchAll();
                if (!empty($results)) {
                    $formStatuses[$table] = $results;
                }
            }
        } else {
            // If no user_id or username column, try to fetch by full_name or name columns if exist
            $columnCheckName = $pdo->prepare("SHOW COLUMNS FROM $table LIKE 'full_name'");
            $columnCheckName->execute();
            $hasFullName = $columnCheckName->fetch();

            if (!$hasFullName) {
                $columnCheckName = $pdo->prepare("SHOW COLUMNS FROM $table LIKE 'name'");
                $columnCheckName->execute();
                $hasName = $columnCheckName->fetch();
            } else {
                $hasName = false;
            }

            if ($hasFullName || $hasName) {
                $nameColumn = $hasFullName ? 'full_name' : 'name';
                $fullName = trim($user['first_name'] . ' ' . ($user['middle_name'] ?? '') . ' ' . $user['last_name']);
                $stmt = $pdo->prepare("SELECT id, status, submitted_at FROM $table WHERE $nameColumn = ? ORDER BY submitted_at DESC");
                $stmt->execute([$fullName]);
                $results = $stmt->fetchAll();
                if (!empty($results)) {
                    $formStatuses[$table] = $results;
                }
            }
        }
    }
}
?>
<div class="container mt-4">
    <h3>Your Form Submission Status</h3>
    <?php foreach ($formStatuses as $formName => $submissions): ?>
        <h5><?php echo ucwords(str_replace('_', ' ', $formName)); ?></h5>
        <?php if (empty($submissions)): ?>
            <p>No submissions found.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($submissions as $submission): ?>
                    <li>
                        ID: <?php echo htmlspecialchars($submission['id']); ?> - Status: <?php echo htmlspecialchars(ucfirst($submission['status'] ?? 'Pending')); ?> - Submitted At: <?php echo htmlspecialchars($submission['submitted_at']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
</body>
</html>
