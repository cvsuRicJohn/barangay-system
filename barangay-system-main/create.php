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

// Validate entity parameter
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

$entity = $_GET['entity'];

// Fetch columns for the entity table
try {
    $stmt = $pdo->prepare("DESCRIBE $entity");
    $stmt->execute();
    $columns = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching table columns: " . $e->getMessage());
}

session_start();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = $_POST;
    unset($fields['submit']);

    // Remove id field if present (auto-increment)
    if (array_key_exists('id', $fields)) {
        unset($fields['id']);
    }

    // Fetch column types for the entity to identify date fields
    $columnTypes = [];
    foreach ($columns as $column) {
        $columnTypes[$column['Field']] = $column['Type'];
    }

    // Set user_id from session if column exists and session user_id is set
    if (array_key_exists('user_id', $columnTypes) && isset($_SESSION['user_id'])) {
        $fields['user_id'] = $_SESSION['user_id'];
    }

    // Format date fields to 'Y-m-d' or 'Y-m-d H:i:s' as needed
    foreach ($fields as $key => $value) {
        if (isset($columnTypes[$key])) {
            $type = $columnTypes[$key];
            if (strpos($type, 'date') !== false) {
                // Try to parse date and format
                $date = date_create($value);
                if ($date) {
                    if (strpos($type, 'datetime') !== false) {
                        $fields[$key] = $date->format('Y-m-d H:i:s');
                    } else {
                        $fields[$key] = $date->format('Y-m-d');
                    }
                } else {
                    // Invalid date, set to null or handle error
                    $fields[$key] = null;
                }
            }
        }
    }

    $keys = array_keys($fields);
    $placeholders = array_fill(0, count($keys), '?');
    $values = array_values($fields);

    $sql = "INSERT INTO $entity (" . implode(", ", $keys) . ") VALUES (" . implode(", ", $placeholders) . ")";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        header("Location: admin_page.php?tab=" . urlencode($entity));
        exit();
    } catch (PDOException $e) {
        $error = "Error inserting record: " . $e->getMessage();
    }
}

// Generate form fields dynamically based on columns
function renderFormFields($columns) {
    foreach ($columns as $column) {
        $field = $column['Field'];
        $type = $column['Type'];
        $nullable = $column['Null'] === 'YES';
        $extra = $column['Extra'];

        // Skip auto-increment id field
        if ($extra === 'auto_increment') {
            continue;
        }

        echo "<div class='form-group'>";
        echo "<label>" . ucfirst(str_replace('_', ' ', $field)) . "</label>";

        // Determine input type based on column type
        $inputType = 'text';
        if (strpos($type, 'int') !== false) {
            $inputType = 'number';
        } elseif (strpos($type, 'date') !== false) {
            $inputType = 'date';
        } elseif (strpos($type, 'text') !== false) {
            echo "<textarea class='form-control' name='" . htmlspecialchars($field) . "' " . ($nullable ? '' : 'required') . "></textarea>";
            echo "</div>";
            continue;
        }

        echo "<input type='$inputType' class='form-control' name='" . htmlspecialchars($field) . "' " . ($nullable ? '' : 'required') . ">";
        echo "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Create New Record - <?php echo htmlspecialchars($entity); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
</head>
<body>
<div class="container mt-4">
    <h1>Create New Record - <?php echo htmlspecialchars($entity); ?></h1>
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post">
        <?php renderFormFields($columns); ?>
        <button type="submit" name="submit" class="btn btn-primary">Create</button>
        <a href="admin_page.php?tab=<?php echo urlencode($entity); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
