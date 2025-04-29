<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
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

// Validate entity and id parameters
$allowed_entities = [
    'users',
    'barangay_id_requests',
    'barangay_clearance',
    'certificate_of_indigency_requests',
    'certificate_of_residency_requests',
    'contact_inquiries'
];

if (!isset($_GET['entity']) || !in_array($_GET['entity'], $allowed_entities)) {
    die("Invalid entity.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid ID.");
}

$entity = $_GET['entity'];
$id = (int)$_GET['id'];

// Fetch record
try {
    $stmt = $pdo->prepare("SELECT * FROM $entity WHERE id = ?");
    $stmt->execute([$id]);
    $record = $stmt->fetch();
    if (!$record) {
        die("Record not found.");
    }
} catch (PDOException $e) {
    die("Error fetching record: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Build update query dynamically based on posted fields
    $fields = $_POST;
    unset($fields['submit']);
    $set_parts = [];
    $values = [];
    foreach ($fields as $key => $value) {
        $set_parts[] = "$key = ?";
        $values[] = $value;
    }
    $values[] = $id;

    $set_clause = implode(", ", $set_parts);
    $sql = "UPDATE $entity SET $set_clause WHERE id = ?";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        header("Location: admin_page.php?tab=" . urlencode($entity));
        exit();
    } catch (PDOException $e) {
        $error = "Error updating record: " . $e->getMessage();
    }
}

// Generate form fields dynamically based on record keys
function renderFormFields($record) {
    foreach ($record as $key => $value) {
        if ($key === 'id') {
            echo "<div class='form-group'>";
            echo "<label>ID</label>";
            echo "<input type='text' class='form-control' name='id' value='" . htmlspecialchars($value) . "' readonly>";
            echo "</div>";
            continue;
        }
        echo "<div class='form-group'>";
        echo "<label>" . ucfirst(str_replace('_', ' ', $key)) . "</label>";
        echo "<input type='text' class='form-control' name='" . htmlspecialchars($key) . "' value='" . htmlspecialchars($value) . "' required>";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Edit Record - <?php echo htmlspecialchars($entity); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
</head>
<body>
<div class="container mt-4">
    <h1>Edit Record - <?php echo htmlspecialchars($entity); ?></h1>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <?php renderFormFields($record); ?>
        <button type="submit" name="submit" class="btn btn-primary">Update</button>
        <a href="admin_page.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
