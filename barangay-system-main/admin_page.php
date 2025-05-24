<?php
require_once 'chatbot-main/session_check.php';

check_admin_session();
check_user_status(); // Add this to block rejected users from accessing admin page

// Handle logout and backup
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'logout') {
        session_destroy();
        header("Location: chatbot-main/login.php");
        exit();
    } elseif ($_GET['action'] === 'backup') {
        // PHP-based database backup logic without using exec or mysqldump
        try {
            $tables = [];
            $stmt = $pdo->query("SHOW TABLES");
            while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            $sqlDump = "";
            foreach ($tables as $table) {
                // Get CREATE TABLE statement
                $createStmt = $pdo->query("SHOW CREATE TABLE `$table`")->fetch(PDO::FETCH_ASSOC);
                $sqlDump .= "\n\n" . $createStmt['Create Table'] . ";\n\n";

                // Get table data
                $rows = $pdo->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($rows as $row) {
                    $columns = array_map(function($col) { return "`$col`"; }, array_keys($row));
                    $values = array_map(function($val) use ($pdo) {
                        if ($val === null) return "NULL";
                        return $pdo->quote($val);
                    }, array_values($row));
                    $sqlDump .= "INSERT INTO `$table` (" . implode(", ", $columns) . ") VALUES (" . implode(", ", $values) . ");\n";
                }
            }

            $backupFile = 'backup_barangay_db_' . date('Y-m-d_H-i-s') . '.sql';

            // Save to file
            file_put_contents($backupFile, $sqlDump);

            // Send the backup file as download
            if (file_exists($backupFile)) {
                header('Content-Description: File Transfer');
                header('Content-Type: application/sql');
                header('Content-Disposition: attachment; filename=' . basename($backupFile));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($backupFile));
                flush();
                readfile($backupFile);
                // Delete the backup file after download
                unlink($backupFile);
                exit();
            } else {
                die("Backup file not found.");
            }
        } catch (Exception $e) {
            die("Error creating database backup: " . $e->getMessage());
        }
    }
}

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

// Handle delete action and status update
if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action === 'delete') {
        $entity = $_GET['entity'] ?? '';
        $id = $_GET['id'] ?? '';

        if (!is_numeric($id)) {
            die("Invalid ID for deletion.");
        }

        $valid_entities = [
            'barangay_id_requests',
            'barangay_clearance',
            'certificate_of_indigency_requests',
            'certificate_of_residency_requests',
            'users',
            'contact_inquiries',
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
            'unemployment_certification_requests',
            'transactions'
        ];

        if (!in_array($entity, $valid_entities)) {
            die("Invalid entity for deletion.");
        }

        try {
            $stmt = $pdo->prepare("DELETE FROM $entity WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: admin_page.php?tab=$entity");
            exit();
        } catch (PDOException $e) {
            die("Error deleting record: " . $e->getMessage());
        }
    } elseif ($action === 'update_status') {
        $entity = $_GET['entity'] ?? '';
        $id = $_GET['id'] ?? '';
        $status = $_GET['status'] ?? '';

        $allowedStatusEntities = [
            'certificate_of_indigency_requests',
            'users',
            'barangay_clearance',
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
            'unemployment_certification_requests',
            'barangay_id_requests',
            'contact_inquiries'
        ];

        if (!in_array($entity, $allowedStatusEntities)) {
            die("Invalid entity for status update.");
        }
        if (!is_numeric($id)) {
            die("Invalid ID for status update.");
        }
        if (!in_array($status, ['approved', 'rejected', 'pending'])) {
            die("Invalid status value.");
        }

        try {
            if (in_array($entity, $allowedStatusEntities)) {
                $stmt = $pdo->prepare("UPDATE $entity SET status = :status WHERE id = :id");
            }
            // If status is empty or null on insert, default to 'pending'
            if (empty($status)) {
                $status = 'pending';
            }
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            // If status is approved, insert a record into transactions table
            if ($status === 'approved') {
                // Define costs for each entity type
                $entityCosts = [
                    'barangay_id_requests' => 75.00,
                    'barangay_clearance' => 20.00,
                    'certificate_of_indigency_requests' => 20.00,
                    'certificate_of_residency_requests' => 20.00,
                    'baptismal_certification_requests' => 20.00,
                    'certificate_of_good_moral_requests' => 20.00,
                    'cohabitation_certification_requests' => 40.00,
                    'construction_clearance_requests' => 20.00,
                    'first_time_job_seeker_requests' => 0.00,
                    'late_birth_registration_requests' => 20.00,
                    'non_residency_certification_requests' => 20.00,
                    'no_income_certification_requests' => 20.00,
                    'out_of_school_youth_requests' => 20.00,
                    'solo_parent_requests' => 20.00,
                    'unemployment_certification_requests' => 20.00,
                    
                ];

                // Fetch user info from the entity table for the given id
                $userStmt = $pdo->prepare("SELECT * FROM $entity WHERE id = :id");
                $userStmt->bindParam(':id', $id, PDO::PARAM_INT);
                $userStmt->execute();
                $record = $userStmt->fetch(PDO::FETCH_ASSOC);

                if ($record) {
                    // Determine user and amount fields for transaction
                    // Use 'first_name' and 'last_name' concatenated if available, else 'user' field if exists
                    $userName = '';
                    if (isset($record['first_name']) && isset($record['last_name'])) {
                        $userName = $record['first_name'] . ' ' . $record['last_name'];
                    } elseif (isset($record['user'])) {
                        $userName = $record['user'];
                    } elseif (isset($record['full_name'])) {
                        $userName = $record['full_name'];
                    } else {
                        $userName = 'Unknown User';
                    }

                    // Get amount from entityCosts mapping or default to 0.00
                    $amount = $entityCosts[$entity] ?? 0.00;

            // Insert into transactions table without form_type and form_id to avoid error
            $insertStmt = $pdo->prepare("INSERT INTO transactions (user, amount, date, status) VALUES (:user, :amount, NOW(), 'completed')");
            $insertStmt->bindParam(':user', $userName, PDO::PARAM_STR);
            $insertStmt->bindParam(':amount', $amount);
            $insertStmt->execute();
                }
            }

            // Instead of redirecting immediately, reload the page without exiting to preserve session
            header("Location: admin_page.php?tab=$entity");
            // Do not call exit() here to prevent session loss
        } catch (PDOException $e) {
            die("Error updating status: " . $e->getMessage());
        }
    }
}

// Fetch data for all entities with a helper
function fetchAll($pdo, $table) {
    try {
        // Include submitted_at column explicitly for barangay_id_requests, certificate_of_indigency_requests, and certificate_of_residency_requests
        if ($table === 'barangay_id_requests' || $table === 'certificate_of_indigency_requests' || $table === 'certificate_of_residency_requests') {
            $stmt = $pdo->query("SELECT *, submitted_at FROM $table ORDER BY id DESC");
        } else {
            $stmt = $pdo->query("SELECT * FROM $table ORDER BY id DESC");
        }
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Error fetching $table: " . $e->getMessage());
    }
}

// Preload all datasets once
$entities = [
    'barangay_id_requests' => fetchAll($pdo, 'barangay_id_requests'),
    'barangay_clearance' => fetchAll($pdo, 'barangay_clearance'),
    'certificate_of_indigency_requests' => fetchAll($pdo, 'certificate_of_indigency_requests'),
    'certificate_of_residency_requests' => fetchAll($pdo, 'certificate_of_residency_requests'),
    'users' => fetchAll($pdo, 'users'),
    'contact_inquiries' => fetchAll($pdo, 'contact_inquiries'),
    'baptismal_certification_requests' => fetchAll($pdo, 'baptismal_certification_requests'),
    'certificate_of_good_moral_requests' => fetchAll($pdo, 'certificate_of_good_moral_requests'),
    'cohabitation_certification_requests' => fetchAll($pdo, 'cohabitation_certification_requests'),
    'construction_clearance_requests' => fetchAll($pdo, 'construction_clearance_requests'),
    'first_time_job_seeker_requests' => fetchAll($pdo, 'first_time_job_seeker_requests'),
    'late_birth_registration_requests' => fetchAll($pdo, 'late_birth_registration_requests'),
    'non_residency_certification_requests' => fetchAll($pdo, 'non_residency_certification_requests'),
    'no_income_certification_requests' => fetchAll($pdo, 'no_income_certification_requests'),
    'out_of_school_youth_requests' => fetchAll($pdo, 'out_of_school_youth_requests'),
    'solo_parent_requests' => fetchAll($pdo, 'solo_parent_requests'),
    'unemployment_certification_requests' => fetchAll($pdo, 'unemployment_certification_requests'),
    'transactions' => fetchAll($pdo, 'transactions')
];

// Counts for sidebar badges
function countRows($pdo, $table) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        die("Error counting $table: " . $e->getMessage());
    }
}
$counts = [];
foreach (array_keys($entities) as $entityKey) {
    $counts[$entityKey] = countRows($pdo, $entityKey);
}

// Helper function to safely escape
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// Determine current tab to show (default dashboard)
$currentTab = $_GET['tab'] ?? 'dashboard';

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Management System - Full Certification Management</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Bootstrap 4 and FontAwesome for styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
<link rel="stylesheet" href="admincss/admin_page.css" />

</head>
<body>
<div id="app" class="d-flex">
  <aside id="sidebar" role="navigation" aria-label="Main navigation">
    <h2>Barangay Bucandala 1 </h2>
    <nav>
      <a href="#" data-target="dashboard" class="active" aria-current="page"><span><i class="fas fa-tachometer-alt"></i> Dashboard</span></a>
      <a href="#" data-target="users">Users <span class="badge badge-secondary"><?= $counts['users'] ?></span></a>
      <a href="#" data-target="barangay_id_requests">Barangay ID <span class="badge badge-primary"><?= $counts['barangay_id_requests'] ?></span></a>
      <a href="#" data-target="barangay_clearance">Barangay Clearance <span class="badge badge-success"><?= $counts['barangay_clearance'] ?></span></a>
      <a href="#" data-target="certificate_of_indigency_requests">Certificate of Indigency <span class="badge badge-warning"><?= $counts['certificate_of_indigency_requests'] ?></span></a>
      <a href="#" data-target="certificate_of_residency_requests">Certificate of Residency <span class="badge badge-info"><?= $counts['certificate_of_residency_requests'] ?></span></a>
      <a href="#" data-target="contact_inquiries">Contact Inquiries <span class="badge badge-info"><?= $counts['contact_inquiries'] ?></span></a>
      <a href="#" data-target="baptismal_certification_requests">Baptismal Certification <span class="badge badge-primary"><?= $counts['baptismal_certification_requests'] ?></span></a>
      <a href="#" data-target="certificate_of_good_moral_requests">Certificate of Good Moral <span class="badge badge-success"><?= $counts['certificate_of_good_moral_requests'] ?></span></a>
      <a href="#" data-target="cohabitation_certification_requests">Cohabitation Certification <span class="badge badge-warning"><?= $counts['cohabitation_certification_requests'] ?></span></a>
      <a href="#" data-target="construction_clearance_requests">Construction Clearance <span class="badge badge-info"><?= $counts['construction_clearance_requests'] ?></span></a>
      <a href="#" data-target="first_time_job_seeker_requests">First Time Job Seeker <span class="badge badge-secondary"><?= $counts['first_time_job_seeker_requests'] ?></span></a>
      <a href="#" data-target="late_birth_registration_requests">Late Birth Registration <span class="badge badge-info"><?= $counts['late_birth_registration_requests'] ?></span></a>
      <a href="#" data-target="non_residency_certification_requests">Non Residency Certification <span class="badge badge-primary"><?= $counts['non_residency_certification_requests'] ?></span></a>
      <a href="#" data-target="no_income_certification_requests">No Income Certification <span class="badge badge-success"><?= $counts['no_income_certification_requests'] ?></span></a>
      <a href="#" data-target="out_of_school_youth_requests">Out Of School Youth <span class="badge badge-warning"><?= $counts['out_of_school_youth_requests'] ?></span></a>
      <a href="#" data-target="solo_parent_requests">Solo Parent <span class="badge badge-info"><?= $counts['solo_parent_requests'] ?></span></a>
    <a href="#" data-target="unemployment_certification_requests">Unemployment Certification <span class="badge badge-secondary"><?= $counts['unemployment_certification_requests'] ?></span></a>
    <a href="#" data-target="transactions">Transaction Records <span class="badge badge-info"><?= $counts['transactions'] ?? 0 ?></span></a>

  </nav>
  <button id="logout-btn" onclick="if(confirm('Are you sure you want to logout?')) window.location='?action=logout';" aria-label="Logout from admin panel">Logout <i class="fas fa-sign-out-alt"></i></button>
  </aside>

  <main id="main-content" role="main" tabindex="0">
<button id="hamburger-toggle" class="hamburger">&#9776;</button>
<button class="modern-btn" onclick="window.location.href='chatbot-main/index.php'">
  Go to Homepage
</button>
<a href="backup_database.php" 
   onclick="return confirm('Are you sure you want to create a backup of the database?');" 
   class="btn btn-primary">
   Backup Database
</a>
  <section id="dashboard" class="tab-content-section active" aria-labelledby="dashboard-header">
  <h3 id="dashboard-header" class="page-title">Dashboard Overview</h3>

<div class="chart-container">
    <div class="pie-chart-box">
        <canvas id="pieChart"></canvas>
    </div>
    <div class="line-chart-box">
        <canvas id="lineChart"></canvas>
    </div>
</div>

  <!-- Table 1 -->
  <div class="table-wrapper">
    <table class="entity-table" aria-describedby="dashboard-summary-desc">
      <caption id="dashboard-summary-desc">This table shows totals for each certification request and entities</caption>
      <thead>
        <tr>
          <th class="collapsible-header">Certification / Entity</th>
          <th>Total Requests / Items</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($counts as $entity => $total): ?>
          <tr class="collapsible-row">
            <td><?= e(str_replace('_', ' ', ucwords($entity))) ?></td>
            <td><?= e($total) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Table 2 -->
  <h3 class="page-title">Recent Requests Overview</h3>
  <div class="table-wrapper">
    <table class="entity-table" aria-describedby="recent-requests-desc">
      <caption id="recent-requests-desc">This table shows the most recent requests from all entities</caption>
      <thead>
        <tr>
          <th class="collapsible-header">Entity</th>
          <th>ID</th>
          <th>Name</th>
          <th>Date Submitted</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $recentRequests = [];
        foreach ($entities as $entityKey => $items) {
            foreach ($items as $item) {
                $recentRequests[] = [
                    'entity' => $entityKey,
                    'id' => $item['id'] ?? '',
                    'name' => $item['first_name'] ?? ($item['full_name'] ?? ($item['name'] ?? '')),
                    'date' => $item['submitted_at'] ?? ($item['created_at'] ?? '')
                ];
            }
        }
        usort($recentRequests, function($a, $b) {
            return strtotime($b['date']) <=> strtotime($a['date']);
        });
        $recentRequests = array_slice($recentRequests, 0, 10);
        foreach ($recentRequests as $request):
        ?>
        <tr class="collapsible-row">
          <td><?= e(str_replace('_', ' ', ucwords($request['entity']))) ?></td>
          <td><?= e($request['id']) ?></td>
          <td><?= e($request['name']) ?></td>
          <td><?= e($request['date']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- Transaction Records on Dashboard -->
  <h3 class="page-title">Transaction Records</h3>
  <form id="transaction-records-form" method="post" action="#">
    <div class="table-wrapper">
      <table class="entity-table" aria-describedby="transactions-desc">
        <caption id="transactions-desc">List of transaction records</caption>
        <thead>
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php
          if (!empty($entities['transactions'])) {
              foreach ($entities['transactions'] as $transaction) {
                  echo '<tr>';
                  echo '<td>' . e($transaction['id'] ?? '') . '</td>';
                  echo '<td>' . e($transaction['user'] ?? '') . '</td>';
                  echo '<td>' . e($transaction['amount'] ?? '') . '</td>';
                  echo '<td>' . e($transaction['date'] ?? '') . '</td>';
                  echo '<td>' . e($transaction['status'] ?? '') . '</td>';
                  echo '</tr>';
              }
          } else {
              echo '<tr><td colspan="5" style="text-align:center; font-style: italic;">No transaction records found.</td></tr>';
          }
          ?>
        </tbody>
      </table>
    </div>
  </form>
    <!-- EDITABLE FAQS -->

<?php include 'db_conn.php'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2>Edit FAQs</h2>

            <form action="update_faq.php" method="post">
                <?php
                $result = $conn->query("SELECT * FROM faqs ORDER BY column_side, position ASC");
                while($row = $result->fetch_assoc()):
                    $id = $row['id'];
                    $question = htmlspecialchars($row['question']);
                    $answer = htmlspecialchars($row['answer']);
                    $side = $row['column_side'];
                    $position = $row['position'];
                ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <input type="hidden" name="faq_id[]" value="<?= $id ?>">

                        <!-- Question -->
                        <div class="form-group">
                            <label>Question:</label>
                            <input type="text" name="question[]" class="form-control" value="<?= $question ?>">
                        </div>

                        <!-- Answer -->
                        <div class="form-group">
                            <label>Answer:</label>
                            <textarea name="answer[]" class="form-control" rows="3"><?= $answer ?></textarea>
                        </div>

                        <!-- Column Side + Position side-by-side -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Column Side:</label>
                                <select name="column_side[]" class="form-control">
                                    <option value="left" <?= $side == 'left' ? 'selected' : '' ?>>Left</option>
                                    <option value="right" <?= $side == 'right' ? 'selected' : '' ?>>Right</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Position:</label>
                                <input type="number" name="position[]" class="form-control" value="<?= $position ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>

    <hr>

<h3>Add New FAQ</h3>
<form action="add_faq.php" method="post">
    <?php foreach ([
        ['label' => 'Question', 'name' => 'question', 'type' => 'text'],
        ['label' => 'Answer', 'name' => 'answer', 'type' => 'textarea']
    ] as $field): ?>
        <div class="form-group">
            <label><?= $field['label'] ?>:</label>
            <?php if ($field['type'] === 'textarea'): ?>
                <textarea name="<?= $field['name'] ?>" class="form-control" rows="3" required></textarea>
            <?php else: ?>
                <input type="<?= $field['type'] ?>" name="<?= $field['name'] ?>" class="form-control" required>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <!-- Position and Column Side side by side -->
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Position:</label>
            <input type="number" name="position" class="form-control" required>
        </div>
        <div class="form-group col-md-6">
            <label>Column Side:</label>
            <select name="column_side" class="form-control">
                <option value="left">Left</option>
                <option value="right">Right</option>
            </select>
        </div>
    </div>

    <button type="submit" class="btn btn-success">Add FAQ</button>
</form>
</div>
</section>

    <?php
    // Helper function to output entities' tables dynamically
    function renderEntityTable($entityKey, $items) {
        // Define certification entities for email removal
        static $certificationEntities = [
            'certificate_of_indigency_requests',
            'barangay_clearance',
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
            'unemployment_certification_requests',
            'barangay_id_requests' 
        ];

        // Column layouts for each entity - define key columns and headers for illustration
        $columnsMap = [
            'barangay_id_requests' => [
                'id' => 'ID',
                'first_name' => 'First Name',
                'middle_name' => 'Middle Name',
                'last_name' => 'Last Name',
                'address' => 'Address',
                'date_of_birth' => 'Date of Birth',
                'gov_id' => 'Gov ID',
                'submitted_at' => 'Submitted At',
                'email' => 'Email',
                'shipping_method' => 'Shipping Method'
            ],
            'barangay_clearance' => [
                'id' => 'ID',
                'first_name' => 'First Name',
                'middle_name' => 'Middle Name',
                'last_name' => 'Last Name',
                'complete_address' => 'Complete Address',
                'birth_date' => 'Birth Date',
                'age' => 'Age',
                'civil_status' => 'Civil Status',
                'mobile_number' => 'Mobile Number',
                'years_of_stay' => 'Years of Stay',
                'purpose' => 'Purpose',
                'submitted_at' => 'Submitted At',
                'shipping_method' => 'Shipping Method'
            ],
'certificate_of_indigency_requests' => [
                'id' => 'ID',
                'first_name' => 'First Name',
                'middle_name' => 'Middle Name',
                'last_name' => 'Last Name',
                'complete_address' => 'Complete Address',
                'date_of_birth' => 'Date of Birth',
                'age' => 'Age',
                'civil_status' => 'Civil Status',
                'occupation' => 'Occupation',
                'monthly_income' => 'Monthly Income',
                'proof_of_residency' => 'Proof of Residency',
                'gov_id' => 'Gov ID',
                'spouse_name' => 'Spouse Name',
                'number_of_dependents' => 'Dependents',
                'submitted_at' => 'Submitted At',
                'shipping_method' => 'Shipping Method'
            ],
            'certificate_of_residency_requests' => [
                    'id' => 'ID',
                    'first_name' => 'First Name',
                    'middle_name' => 'Middle Name',
                    'last_name' => 'Last Name',
                    'date_of_birth' => 'Date of Birth',
                    'age' => 'Age',
                    'civil_status' => 'Civil Status',
                    'complete_address' => 'Complete Address',
                    'submitted_at' => 'Submitted At',
                    'purpose' => 'Purpose',
                    'shipping_method' => 'Shipping Method'
                ],
            'users' => [
                'id' => 'ID',
                'first_name' => 'First Name',
                'last_name' => 'Last Name',
                'address' => 'Address',
                'email' => 'Email',
                'username' => 'Username',
                'created_at' => 'Created At',
                'status' => 'Status'
            ],
            'contact_inquiries' => [
                'id' => 'ID',
                'name' => 'Name',
                'email' => 'Email',
                'subject' => 'Subject',
                'message' => 'Message',
                'created_at' => 'Date Submitted'
            ],
            'baptismal_certification_requests' => [
                'id' => 'ID',
                'parent_name' => 'Parent Name',
                'address' => 'Address',
                'child_name' => 'Child Name',
                'purpose' => 'Purpose',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ],
            'certificate_of_good_moral_requests' => [
                'id' => 'ID',
                'full_name' => 'Full Name',
                'age' => 'Age',
                'civil_status' => 'Civil Status',
                'address' => 'Address',
                'purpose' => 'Purpose',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ],
            'cohabitation_certification_requests' => [
                'id' => 'ID',
                'partner1_name' => 'Partner 1 Name',
                'partner2_name' => 'Partner 2 Name',
                'shared_address' => 'Shared Address',
                'cohabitation_duration' => 'Duration',
                'purpose' => 'Purpose',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ],
            'construction_clearance_requests' => [
                'id' => 'ID',
                'business_name' => 'Business Name',
                'business_location' => 'Business Location',
                'owner_name' => 'Owner Name',
                'owner_address' => 'Owner Address',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ],
            'first_time_job_seeker_requests' => [
                'id' => 'ID',
                'full_name' => 'Full Name',
                'address' => 'Address',
                'residency_length' => 'Residency Length',
                'oath_acknowledged' => 'Oath Acknowledged',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ],
'late_birth_registration_requests' => [
                'id' => 'ID',
                'last_name' => 'Last Name',
                'first_name' => 'First Name',
                'middle_name' => 'Middle Name',
                'address' => 'Address',
                'marital_status' => 'Marital Status',
                'place_of_birth' => 'Place of Birth',
                'date_of_birth' => 'Date of Birth',
                'age' => 'Age',
                'fathers_name' => 'Father\'s Name',
                'mothers_name' => 'Mother\'s Name',
                'years_in_barangay' => 'Years in Barangay',
                'purpose' => 'Purpose',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ],
            'non_residency_certification_requests' => [
                'id' => 'ID',
                'full_name' => 'Full Name',
                'previous_address' => 'Previous Address',
                'purpose' => 'Purpose',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ],
            'no_income_certification_requests' => [
                'id' => 'ID',
                'full_name' => 'Full Name',
                'date_of_birth' => 'Date of Birth',
                'civil_status' => 'Civil Status',
                'address' => 'Address',
                'no_income_statement' => 'No Income Statement',
                'purpose' => 'Purpose',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ],
            'out_of_school_youth_requests' => [
                'id' => 'ID',
                'full_name' => 'Full Name',
                'address' => 'Address',
                'citizenship' => 'Citizenship',
                'purpose' => 'Purpose',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ],
            'solo_parent_requests' => [
                'id' => 'ID',
                'full_name' => 'Full Name',
                'address' => 'Address',
                'solo_since' => 'Solo Since',
                'child_count' => 'Child Count',
                'children_names' => 'Children Names',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ],
            'unemployment_certification_requests' => [
                'id' => 'ID',
                'full_name' => 'Full Name',
                'age' => 'Age',
                'birth_date' => 'Birth Date',
                'civil_status' => 'Civil Status',
                'address' => 'Address',
                'purpose' => 'Purpose',
                'shipping_method' => 'Shipping Method',
                'submitted_at' => 'Submitted At'
            ]
        ];

        // Get columns or fallback to keys of first item
        if (isset($columnsMap[$entityKey])) {
            $columns = $columnsMap[$entityKey];
        } elseif (count($items) > 0) {
            $columns = array_combine(array_keys($items[0]), array_keys($items[0]));
        } else {
            $columns = [];
        }

        echo '<section id="'.e($entityKey).'" class="tab-content-section">';
        // Display entity name
        $niceName = ucwords(str_replace('_', ' ', $entityKey));
        echo "<h3 class='page-title'>".e($niceName)."</h3>";

        echo '<div class="table-wrapper">';
        echo '<table class="entity-table" aria-describedby="'.e($entityKey).'-desc">';
        echo '<caption id="'.e($entityKey).'-desc">List of '.e($niceName).'</caption>';
        // Table headers
        echo '<thead><tr>';
        foreach ($columns as $colName) {
            // Skip email column for all certification entities as per user request
            if ($colName === 'Email' && in_array($entityKey, $certificationEntities)) {
                continue;
            }
            echo '<th>'.e($colName).'</th>';
        }
        echo '<th>Actions</th>';
        echo '</tr></thead><tbody>';
        if (empty($items)) {
            echo '<tr><td colspan="'.(count($columns)+1).'" style="text-align:center; font-style: italic;">No records found.</td></tr>';
        } else {
            foreach ($items as $row) {
                echo '<tr>';
foreach ($columns as $key => $colHeader) {
    // Adjust key for associative arrays (could be numeric keyed)
    $keyToUse = is_int($key) ? $colHeader : $key;
    // Skip email column for all certification entities as per user request
    if ($keyToUse === 'email' && in_array($entityKey, $certificationEntities)) {
        continue;
    }
    // Fix for barangay_clearance: map civil_status column instead of status for civil status display
    if ($entityKey === 'barangay_clearance' && $keyToUse === 'status') {
        $keyToUse = 'civil_status';
    }
    $val = isset($row[$keyToUse]) ? $row[$keyToUse] : '';

    // For certification entities, map status values to user-friendly labels
    $certificationEntitiesWithStatus = [
        'certificate_of_indigency_requests',
        'barangay_clearance',
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

    if ($keyToUse === 'status' && in_array($entityKey, $certificationEntitiesWithStatus)) {
        switch (strtolower($val)) {
            case 'approved':
                $val = 'Done';
                break;
            case 'pending':
                $val = 'In Progress';
                break;
            case 'rejected':
                $val = 'Not Yet';
                break;
            default:
                $val = ucfirst($val);
                break;
        }
    }

    // Format date fields
    if (strpos($keyToUse, 'date') !== false || strpos($keyToUse, 'created_at') !== false || strpos($keyToUse, 'submitted_at') !== false || strpos($keyToUse, 'birth_date') !== false) {
        if ($val) {
            try {
                $dt = new DateTime($val);
                $val = $dt->format('F j, Y, g:i a');
            } catch (Exception $ex) {
                // ignore format error
            }
        }
    }
    echo '<td>'.nl2br(e($val)).'</td>';
}
                $idEsc = isset($row['id']) ? e($row['id']) : '';
                $entityEsc = e($entityKey);
        echo '<td class="action-btns">';
echo '<a href="edit.php?entity='.$entityEsc.'&id='.$idEsc.'&tab='.$entityEsc.'" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a> ';
        echo '<a href="admin_page.php?view='.$entityEsc.'&id='.$idEsc.'" class="btn btn-sm btn-primary" title="View"><i class="fas fa-eye"></i></a> ';
        if ($entityKey === 'barangay_clearance') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate_clearance.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Clearance Document" target="_blank"><i class="fas fa-file-word"></i> Generate Clearance</a> ';
    }
    
}
        if ($entityKey === 'out_of_school_youth_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate_school_youth.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate School Youth Document" target="_blank"><i class="fas fa-file-word"></i> Generate School Youth</a> ';
}
    }   
        if ($entityKey === 'certificate_of_residency_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate-residency.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Residency Document" target="_blank"><i class="fas fa-file-word"></i> Generate Residency</a> ';
    }
}
        if ($entityKey === 'certificate_of_indigency_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate-indigency.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Indigency Document" target="_blank"><i class="fas fa-file-word"></i> Generate Indigency</a> ';
    }
}
        if ($entityKey === 'certificate_of_good_moral_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate-good-moral.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Good Moral Document" target="_blank"><i class="fas fa-file-word"></i> Generate Good Moral</a> ';
    }
}
        if ($entityKey === 'baptismal_certification_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate_baptismal.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Baptismal Certification Document" target="_blank"><i class="fas fa-file-word"></i> Generate Baptismal Certification</a> ';
    }
}
        if ($entityKey === 'first_time_job_seeker_requests') {
        echo '<a href="generate-first-time-job.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate First Time Job Seeker Document" target="_blank"><i class="fas fa-file-word"></i> Generate First Time Job Seeker</a> ';
}
        if ($entityKey === 'construction_clearance_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate_construction.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Construction Clearance Document" target="_blank"><i class="fas fa-file-word"></i> Generate Construction Clearance</a> ';
    }
}
        if ($entityKey === 'cohabitation_certification_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate_cohabilation.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Cohabitation Certification Document" target="_blank"><i class="fas fa-file-word"></i> Generate Cohabitation</a> ';
    }
}
        if ($entityKey === 'solo_parent_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate-solo-parent.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Solo Parent Document" target="_blank"><i class="fas fa-file-word"></i> Generate Solo Parent</a> ';
    }
}
        if ($entityKey === 'non_residency_certification_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate_non-residency.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Non Residency Document" target="_blank"><i class="fas fa-file-word"></i> Generate Non Residency</a> ';
    }
}
        if ($entityKey === 'unemployment_certification_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate_unemployed.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Unemployment Certification Document" target="_blank"><i class="fas fa-file-word"></i> Generate Unemployment Certification</a> ';
    }
}
        if ($entityKey === 'no_income_certification_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate_no_income.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate No Income Certification Document" target="_blank"><i class="fas fa-file-word"></i> Generate No Income Certification</a> ';
    }
}
        if ($entityKey === 'late_birth_registration_requests') {
    if (strtolower($row['status']) === 'approved') {
        echo '<a href="generate_late_registration.php?id=' . $idEsc . '" class="btn btn-sm btn-info" title="Generate Late Birth Registration Document" target="_blank"><i class="fas fa-file-word"></i> Generate Late Birth Registration</a> ';
    }
}
        if (in_array($entityKey, [
            'certificate_of_indigency_requests',
            'users',
            'barangay_clearance',
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
            'unemployment_certification_requests',
            'barangay_id_requests',
            'contact_inquiries'
        ]) && isset($row['status'])) {
        echo '<div class="btn-group">';
        // Use Bootstrap button colors for status
        $btnClass = 'btn-secondary';
        switch (strtolower($row['status'])) {
            case 'approved':
                $btnClass = 'btn-success';
                break;
            case 'pending':
                $btnClass = 'btn-warning';
                break;
            case 'rejected':
                $btnClass = 'btn-danger';
                break;
        }
        echo '<button type="button" class="btn btn-sm ' . $btnClass . ' dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
        echo ucfirst($row['status']);
        echo '</button>';
        echo '<div class="dropdown-menu">';
        $statuses = ['approved' => 'Done', 'pending' => 'In Progress', 'rejected' => 'Not Yet'];
        foreach ($statuses as $statusKey => $statusLabel) {
            if ($row['status'] !== $statusKey) {
                echo '<a class="dropdown-item" href="admin_page.php?action=update_status&entity='.$entityEsc.'&id='.$idEsc.'&status='.$statusKey.'" onclick="return confirm(\'Are you sure you want to change status to '.$statusLabel.'?\');">'.$statusLabel.'</a>';
            }
        }
        echo '</div>';
        echo '</div>';
    }

        echo '<button onclick="confirmDelete(\''.$entityEsc.'\', '.$idEsc.')" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>';
        echo '</td>';

                echo '</tr>';
            }
        }
        echo '</tbody></table></div>';

        // Link to add new record for the entity, only for certain entities
        $entitiesWithCreate = [
            'users',
            'barangay_id_requests',
            'barangay_clearance',
            'certificate_of_indigency_requests',
            'certificate_of_residency_requests',
            'contact_inquiries',
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
        if (in_array($entityKey, $entitiesWithCreate)) {
            echo '<a class="btn btn-primary mb-3" href="create.php?entity='. $entityKey .'">Add New '.e(str: str_replace('_', ' ', ucwords($entityKey))).'</a>';
        }

        echo '</section>';
    }

    foreach ($entities as $entityKey => $items) {
        renderEntityTable($entityKey, $items);
    }
?>
<section id="transactions" class="tab-content-section">
  <h3 class="page-title">Transaction Records</h3>
  <div class="table-wrapper">
    <table class="entity-table" aria-describedby="transactions-desc">
      <caption id="transactions-desc">List of transaction records</caption>
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Amount</th>
          <th>Date</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
          <?php
          if (!empty($entities['transactions'])) {
              foreach ($entities['transactions'] as $transaction) {
                  echo '<tr>';
                  echo '<td>' . e($transaction['id'] ?? '') . '</td>';
                  echo '<td>' . e($transaction['user'] ?? '') . '</td>';
                  echo '<td>' . e($transaction['amount'] ?? '') . '</td>';
                  echo '<td>' . e($transaction['date'] ?? '') . '</td>';
                  // Show form type and form id if available
                  $formInfo = '';
                  if (!empty($transaction['form_type']) && !empty($transaction['form_id'])) {
                      $formInfo = e(str_replace('_', ' ', ucwords($transaction['form_type']))) . ' #' . e($transaction['form_id']);
                  }
                  echo '<td>' . e($transaction['status'] ?? '') . ($formInfo ? ' (' . $formInfo . ')' : '') . '</td>';
                  echo '</tr>';
              }
          } else {
              echo '<tr><td colspan="5" style="text-align:center; font-style: italic;">No transaction records found.</td></tr>';
          }
          ?>
      </tbody>
    </table>
  </div>
</section>
<?php
    ?>

  </main>
</div>

<!-- Bootstrap JS bundle -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="adminjs/admin_page.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const pieCtx = document.getElementById('pieChart').getContext('2d');

  const pieChart = new Chart(pieCtx, {
    type: 'pie',
    data: {
      labels: <?= json_encode(array_map(function($key) {
          return ucwords(str_replace('_', ' ', $key));
      }, array_keys($counts))) ?>,
      datasets: [{
        data: <?= json_encode(array_values($counts)) ?>,
        backgroundColor: [
          '#FF6384', '#36A2EB', '#FFCE56', '#8E44AD', '#2ECC71', '#E74C3C',
          '#3498DB', '#F1C40F', '#1ABC9C', '#9B59B6', '#34495E', '#95A5A6',
          '#F39C12', '#D35400', '#7F8C8D', '#27AE60', '#2980B9'
        ]
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { position: 'bottom' },
        title: {
          display: true,
          text: 'Request Distribution by Entity'
        }
      }
    }
  });
</script>
<script>
const ctxLine = document.getElementById('lineChart').getContext('2d');

// Example labels and data (replace with dynamic PHP data if needed)
const lineLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];
const lineData = [5, 8, 3, 10, 6];

const lineChart = new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: lineLabels,
        datasets: [{
            label: 'Monthly Requests',
            data: lineData,
            fill: false,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: true,
                text: 'Request Trends'
            }
        }
    }
});
</script>
<script>
  document.getElementById('hamburger-toggle').addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('active');
  });
</script>
</body>
</html>
