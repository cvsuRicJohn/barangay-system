<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: chatbot-main/login.php");
    exit();
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: chatbot-main/login.php");
    exit();
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

// Handle delete action
if (isset($_GET['action']) && $_GET['action'] === 'delete') {
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
        'contact_inquiries'
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
}

// Fetch data for all entities
function fetchAll($pdo, $table) {
    try {
        $stmt = $pdo->query("SELECT * FROM $table ORDER BY id DESC");
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        die("Error fetching $table: " . $e->getMessage());
    }
}

$barangay_id_requests = fetchAll($pdo, 'barangay_id_requests');
$barangay_clearance = fetchAll($pdo, 'barangay_clearance');
$certificate_of_indigency_requests = fetchAll($pdo, 'certificate_of_indigency_requests');
$certificate_of_residency_requests = fetchAll($pdo, 'certificate_of_residency_requests');
$users = fetchAll($pdo, 'users');
$contact_inquiries = fetchAll($pdo, 'contact_inquiries');

// Prepare counts for dashboard summary
function countRows($pdo, $table) {
    try {
        $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
        return (int)$stmt->fetchColumn();
    } catch (PDOException $e) {
        die("Error counting $table: " . $e->getMessage());
    }
}

$counts = [
    'barangay_id_requests' => countRows($pdo, 'barangay_id_requests'),
    'barangay_clearance' => countRows($pdo, 'barangay_clearance'),
    'certificate_of_indigency_requests' => countRows($pdo, 'certificate_of_indigency_requests'),
    'certificate_of_residency_requests' => countRows($pdo, 'certificate_of_residency_requests'),
    'users' => countRows($pdo, 'users'),
    'contact_inquiries' => countRows($pdo, 'contact_inquiries'),
];

// Prepare recent requests (latest 7 from all request tables)
try {
    $stmt = $pdo->query("
        SELECT first_name, 'Barangay ID' AS type, created_at AS date_requested FROM barangay_id_requests
        UNION ALL
        SELECT first_name, 'Barangay Clearance' AS type, created_at AS date_requested FROM barangay_clearance
        UNION ALL
        SELECT first_name, 'Certificate of Indigency' AS type, created_at AS date_requested FROM certificate_of_indigency_requests
        UNION ALL
        SELECT first_name, 'Certificate of Residency' AS type, created_at AS date_requested FROM certificate_of_residency_requests
        ORDER BY date_requested DESC
        LIMIT 7
    ");
    $recent_requests = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching recent requests: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin Panel - Service Management</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
<link rel="stylesheet" href="admincss/admin_page.css" />
<style>
  body.sidebar-collapsed #sidebarMenu {
    width: 60px;
  }
  #sidebarMenu {
    transition: width 0.3s;
  }
  .content-section {
    display: none;
  }
  .content-section.active {
    display: block;
  }
  .nav-link.active {
    font-weight: bold;
    background-color: #e9ecef;
  }
  .action-btn {
    margin-right: 5px;
  }
</style>
<script>
function confirmDelete(entity, id) {
    if (confirm('Are you sure you want to delete this ' + entity + ' with ID ' + id + '?')) {
        window.location.href = 'admin_page.php?action=delete&entity=' + entity + '&id=' + id;
    }
}
</script>
</head>
<body class="sidebar-collapsed">

<div id="sidebarBackdrop" style="display:none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 1040;"></div>

<!-- Sidebar -->
<nav id="sidebarMenu" class="bg-light border-right" style="width: 250px; min-height: 100vh; position: fixed; z-index: 1050; overflow-y: auto; left: 0; top: 0;">
  <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
    <h5 class="mb-0">Admin Panel</h5>
    <button id="burgerMenuBtnSidebar" class="btn btn-outline-primary" type="button" aria-label="Toggle sidebar">
      <i class="fas fa-bars"></i>
    </button>
  </div>
  <ul class="nav flex-column pt-3">
    <li class="nav-item">
      <a href="#" class="nav-link active" data-target="dashboard">Dashboard Overview</a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="barangay_id_requests">Barangay ID Requests <span class="badge badge-primary"><?php echo $counts['barangay_id_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="barangay_clearance">Barangay Clearance Requests <span class="badge badge-success"><?php echo $counts['barangay_clearance']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="certificate_of_indigency_requests">Certificate of Indigency Requests <span class="badge badge-warning"><?php echo $counts['certificate_of_indigency_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="certificate_of_residency_requests">Certificate of Residency Requests <span class="badge badge-info"><?php echo $counts['certificate_of_residency_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="users">Users <span class="badge badge-secondary"><?php echo $counts['users']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="contact_inquiries">Contact Inquiries <span class="badge badge-info"><?php echo $counts['contact_inquiries']; ?></span></a>
    </li>
    <li class="nav-item mt-3">
      <a href="admin_page.php?action=logout" class="btn btn-danger btn-block" onclick="return confirm('Are you sure you want to log out?');">Logout</a>
    </li>
  </ul>
</nav>

<!-- Main content -->
  <main id="mainContent" class="flex-grow-1 p-4" style="margin-left: 60px; padding-top: 56px; position: relative;">
    <!-- Burger menu button on main layer -->
    <button id="burgerMenuBtnMain" class="btn btn-outline-primary" style="position: fixed; top: 10px; left: 10px; z-index: 1100; display: block;">
      <i class="fas fa-bars"></i>
    </button>

    <!-- Dashboard Overview -->
    <section id="dashboard" class="content-section active">
      <h2>Dashboard Overview</h2>

      <!-- Charts -->
      <?php
      // Prepare data for pie chart
      $pieChartData = [
          'labels' => ['Barangay ID', 'Barangay Clearance', 'Certificate of Indigency', 'Certificate of Residency'],
          'data' => [
              $counts['barangay_id_requests'],
              $counts['barangay_clearance'],
              $counts['certificate_of_indigency_requests'],
              $counts['certificate_of_residency_requests']
          ]
      ];

      // Prepare data for requests over time chart
      // For simplicity, we will prepare monthly counts for the last 6 months for each service
      $months = [];
      $barangay_id_counts = [];
      $clearance_counts = [];
      $indigency_counts = [];
      $residency_counts = [];

      for ($i = 5; $i >= 0; $i--) {
          $month = date('Y-m', strtotime("-$i months"));
          $months[] = date('F Y', strtotime("-$i months"));

          // Query counts per month for each service
          $stmt = $pdo->prepare("SELECT COUNT(*) FROM barangay_id_requests WHERE DATE_FORMAT(created_at, '%Y-%m') = :month");
          $stmt->execute(['month' => $month]);
          $barangay_id_counts[] = (int)$stmt->fetchColumn();

          $stmt = $pdo->prepare("SELECT COUNT(*) FROM barangay_clearance WHERE DATE_FORMAT(created_at, '%Y-%m') = :month");
          $stmt->execute(['month' => $month]);
          $clearance_counts[] = (int)$stmt->fetchColumn();

          $stmt = $pdo->prepare("SELECT COUNT(*) FROM certificate_of_indigency_requests WHERE DATE_FORMAT(created_at, '%Y-%m') = :month");
          $stmt->execute(['month' => $month]);
          $indigency_counts[] = (int)$stmt->fetchColumn();

          $stmt = $pdo->prepare("SELECT COUNT(*) FROM certificate_of_residency_requests WHERE DATE_FORMAT(created_at, '%Y-%m') = :month");
          $stmt->execute(['month' => $month]);
          $residency_counts[] = (int)$stmt->fetchColumn();
      }

      $requestsOverTimeData = [
          'labels' => $months,
          'barangay_id' => $barangay_id_counts,
          'clearance' => $clearance_counts,
          'indigency' => $indigency_counts,
          'residency' => $residency_counts
      ];
      ?>

      <script>
        // Pass PHP data to JavaScript
        const pieChartData = <?php echo json_encode($pieChartData); ?>;
        const requestsOverTimeData = <?php echo json_encode($requestsOverTimeData); ?>;
      </script>

<div class="d-flex justify-content-left flex-wrap" style="margin-bottom: 1.5rem; gap: 8px;">
  <div class="mb-2">
    <div class="card shadow-sm" style="height: 400px; width: 450px; max-width: 100%;">
      <div class="card-body" style="height: 100%;">
        <canvas id="requestsPieChart" style="width:100%; height:100%;"></canvas>
      </div>
    </div>
  </div>
  <div class="mb-2">
    <div class="card shadow-sm" style="height: 400px; width: 450px; max-width: 100%;">
      <div class="card-body" style="height: 100%;">
        <canvas id="requestsOverTimeChart" style="width:100%; height:100%;"></canvas>
      </div>
    </div>
  </div>
</div>

<style>
  /* Mobile adjustments (applies below 768px) */
  @media (max-width: 768px) {
    .card {
      height: 350px !important; /* Reduce height on mobile */
      width: 100% !important;   /* Full width on mobile */
    }
}
</style>

      <!-- Dashboard summary with service totals -->
      <div class="table-responsive mb-4" style="max-width: 910px;">
        <table class="table table-bordered table-striped">
          <thead class="thead-dark">
            <tr>
              <th>Service</th>
              <th>Total Requests</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Barangay ID Requests</td>
              <td><?php echo $counts['barangay_id_requests']; ?></td>
            </tr>
            <tr>
              <td>Barangay Clearance Requests</td>
              <td><?php echo $counts['barangay_clearance']; ?></td>
            </tr>
            <tr>
              <td>Certificate of Indigency Requests</td>
              <td><?php echo $counts['certificate_of_indigency_requests']; ?></td>
            </tr>
            <tr>
              <td>Certificate of Residency Requests</td>
              <td><?php echo $counts['certificate_of_residency_requests']; ?></td>
            </tr>
            <tr>
              <td>Service 5 (Placeholder)</td>
              <td>0</td>
            </tr>
            <tr>
              <td>Service 6 (Placeholder)</td>
              <td>0</td>
            </tr>
            <tr>
              <td>Service 7 (Placeholder)</td>
              <td>0</td>
            </tr>
            <tr>
              <td>Service 8 (Placeholder)</td>
              <td>0</td>
            </tr>
            <tr>
              <td>Service 9 (Placeholder)</td>
              <td>0</td>
            </tr>
            <tr>
              <td>Service 10 (Placeholder)</td>
              <td>0</td>
            </tr>
            <tr>
              <td>Service 11 (Placeholder)</td>
              <td>0</td>
            </tr>
            <tr>
              <td>Service 12 (Placeholder)</td>
              <td>0</td>
            </tr>
            <tr>
              <td>Service 13 (Placeholder)</td>
              <td>0</td>
            </tr>
            <tr>
              <td>Service 14 (Placeholder)</td>
              <td>0</td>
            </tr>
          </tbody>
        </table>
      </div>

      <h4>Recent Requests</h4>
<div class="table-responsive mb-4" style="max-height: 300px; max-width: 915px;">
  <table class="table table-striped table-bordered">
    <thead class="thead-dark">
      <tr>
        <th>Name</th>
        <th>Type of Request</th>
        <th>Date Requested</th>
      </tr>
    </thead>
    <tbody>
      <?php if (!empty($recent_requests)): ?>
        <?php 
          // Limit to 7 items
          $limited_requests = array_slice($recent_requests, 0, 7);
          foreach ($limited_requests as $req): 
        ?>
          <tr>
            <td><?php echo htmlspecialchars($req['first_name']); ?></td>
            <td><?php echo htmlspecialchars($req['type']); ?></td>
            <td><?php $date = new DateTime($req['date_requested']); echo htmlspecialchars($date->format('F j, Y, g:i a')); ?></td>
          </tr>
        <?php endforeach; ?>
      <?php else: ?>
        <tr><td colspan="3">No recent requests found.</td></tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>
    </section>
  

    <!-- Function to generate table rows for any entity -->
    <?php
    function renderTableRows($data, $columns) {
        foreach ($data as $row) {
            echo "<tr>";
            foreach ($columns as $col) {
                if (isset($row[$col])) {
                    $val = htmlspecialchars($row[$col]);
                    // Format date fields
                    if (strpos($col, 'date') !== false || strpos($col, 'created_at') !== false) {
                        $dt = new DateTime($row[$col]);
                        $val = htmlspecialchars($dt->format('F j, Y'));
                    }
                    echo "<td>$val</td>";
                } else {
                    echo "<td></td>";
                }
            }
            // Actions column with Edit, View, Delete
            echo "<td>";
            echo "<a href='edit.php?entity={$row['entity']}&id={$row['id']}' class='btn btn-sm btn-warning action-btn'>Edit</a>";
            echo "<a href='admin_page.php?view={$row['entity']}&id={$row['id']}' class='btn btn-sm btn-primary action-btn'>View</a>";
            echo "<button onclick=\"confirmDelete('{$row['entity']}', {$row['id']})\" class='btn btn-sm btn-danger action-btn'>Delete</button>";
            echo "</td>";
            echo "</tr>";
        }
    }
    ?>

    <!-- Tables for each entity -->
    <section id="barangay_id_requests" class="content-section">
      <h2>Barangay ID Requests</h2>
      <a href="create.php?entity=barangay_id_requests" class="btn btn-primary mb-3">Add New</a>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Middle Name</th>
              <th>Last Name</th>
              <th>Address</th>
              <th>Date of Birth</th>
              <th>Government ID</th>
              <th>Email</th>
              <th>Shipping Method</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($barangay_id_requests)): ?>
              <?php foreach ($barangay_id_requests as $req): ?>
                <tr>
                  <td><?php echo htmlspecialchars($req['id']); ?></td>
                  <td><?php echo htmlspecialchars($req['first_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['middle_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['last_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['address']); ?></td>
                  <td><?php $dt = new DateTime($req['date_of_birth']); echo htmlspecialchars($dt->format('F j, Y')); ?></td>
                  <td><?php echo htmlspecialchars($req['gov_id']); ?></td>
                  <td><?php echo htmlspecialchars($req['email']); ?></td>
                  <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
                  <td>
                    <a href="edit.php?entity=barangay_id_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                    <a href="admin_page.php?view=barangay_id_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                    <button onclick="confirmDelete('barangay_id_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="10">No Barangay ID requests found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section id="barangay_clearance" class="content-section">
      <h2>Barangay Clearance Requests</h2>
      <a href="create.php?entity=barangay_clearance" class="btn btn-primary mb-3">Add New</a>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Middle Name</th>
              <th>Last Name</th>
              <th>Complete Address</th>
              <th>Birth Date</th>
              <th>Age</th>
              <th>Status</th>
              <th>Mobile Number</th>
              <th>Years of Stay</th>
              <th>Purpose</th>
              <th>Student/Patient Name</th>
              <th>Student/Patient Address</th>
              <th>Relationship</th>
              <th>Email</th>
              <th>Shipping Method</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($barangay_clearance)): ?>
              <?php foreach ($barangay_clearance as $req): ?>
                <tr>
                  <td><?php echo htmlspecialchars($req['id']); ?></td>
                  <td><?php echo htmlspecialchars($req['first_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['middle_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['last_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['complete_address']); ?></td>
                  <td><?php $dt = new DateTime($req['birth_date']); echo htmlspecialchars($dt->format('F j, Y')); ?></td>
                  <td><?php echo htmlspecialchars($req['age']); ?></td>
                  <td><?php echo htmlspecialchars($req['status']); ?></td>
                  <td><?php echo htmlspecialchars($req['mobile_number']); ?></td>
                  <td><?php echo htmlspecialchars($req['years_of_stay']); ?></td>
                  <td><?php echo htmlspecialchars($req['purpose']); ?></td>
                  <td><?php echo htmlspecialchars($req['student_patient_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['student_patient_address']); ?></td>
                  <td><?php echo htmlspecialchars($req['relationship']); ?></td>
                  <td><?php echo htmlspecialchars($req['email']); ?></td>
                  <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
                  <td>
                    <a href="edit.php?entity=barangay_clearance&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                    <a href="admin_page.php?view=barangay_clearance&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                    <button onclick="confirmDelete('barangay_clearance', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="17">No Barangay Clearance requests found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section id="certificate_of_indigency_requests" class="content-section">
      <h2>Certificate of Indigency Requests</h2>
      <a href="create.php?entity=certificate_of_indigency_requests" class="btn btn-primary mb-3">Add New</a>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Middle Name</th>
              <th>Last Name</th>
              <th>Date of Birth</th>
              <th>Civil Status</th>
              <th>Occupation</th>
              <th>Monthly Income</th>
              <th>Proof of Residency</th>
              <th>Government ID</th>
              <th>Spouse Name</th>
              <th>Number of Dependents</th>
              <th>Email</th>
              <th>Shipping Method</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($certificate_of_indigency_requests)): ?>
              <?php foreach ($certificate_of_indigency_requests as $req): ?>
                <tr>
                  <td><?php echo htmlspecialchars($req['id']); ?></td>
                  <td><?php echo htmlspecialchars($req['first_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['middle_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['last_name']); ?></td>
                  <td><?php $dt = new DateTime($req['date_of_birth']); echo htmlspecialchars($dt->format('F j, Y')); ?></td>
                  <td><?php echo htmlspecialchars($req['civil_status']); ?></td>
                  <td><?php echo htmlspecialchars($req['occupation']); ?></td>
                  <td><?php echo htmlspecialchars($req['monthly_income']); ?></td>
                  <td><?php echo htmlspecialchars($req['proof_of_residency']); ?></td>
                  <td><?php echo htmlspecialchars($req['gov_id']); ?></td>
                  <td><?php echo htmlspecialchars($req['spouse_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['number_of_dependents']); ?></td>
                  <td><?php echo htmlspecialchars($req['email']); ?></td>
                  <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
                  <td>
                    <a href="edit.php?entity=certificate_of_indigency_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                    <a href="admin_page.php?view=certificate_of_indigency_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                    <button onclick="confirmDelete('certificate_of_indigency_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="15">No Certificate of Indigency requests found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section id="certificate_of_residency_requests" class="content-section">
      <h2>Certificate of Residency Requests</h2>
      <a href="create.php?entity=certificate_of_residency_requests" class="btn btn-primary mb-3">Add New</a>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Middle Name</th>
              <th>Last Name</th>
              <th>Date of Birth</th>
              <th>Government ID</th>
              <th>Complete Address</th>
              <th>Proof of Residency</th>
              <th>Purpose</th>
              <th>Email</th>
              <th>Shipping Method</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($certificate_of_residency_requests)): ?>
              <?php foreach ($certificate_of_residency_requests as $req): ?>
                <tr>
                  <td><?php echo htmlspecialchars($req['id']); ?></td>
                  <td><?php echo htmlspecialchars($req['first_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['middle_name']); ?></td>
                  <td><?php echo htmlspecialchars($req['last_name']); ?></td>
                  <td><?php $dt = new DateTime($req['date_of_birth']); echo htmlspecialchars($dt->format('F j, Y')); ?></td>
                  <td><?php echo htmlspecialchars($req['gov_id']); ?></td>
                  <td><?php echo htmlspecialchars($req['complete_address']); ?></td>
                  <td><?php echo htmlspecialchars($req['proof_of_residency']); ?></td>
                  <td><?php echo htmlspecialchars($req['purpose']); ?></td>
                  <td><?php echo htmlspecialchars($req['email']); ?></td>
                  <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
                  <td>
                    <a href="edit.php?entity=certificate_of_residency_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                    <a href="admin_page.php?view=certificate_of_residency_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                    <button onclick="confirmDelete('certificate_of_residency_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="12">No Certificate of Residency requests found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section id="users" class="content-section">
      <h2>User Management</h2>
      <a href="create.php?entity=users" class="btn btn-primary mb-3">Add New User</a>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Address</th>
              <th>Email</th>
              <th>Username</th>
              <th>Created At</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($users)): ?>
              <?php foreach ($users as $user): ?>
                <tr>
                  <td><?php echo htmlspecialchars($user['id']); ?></td>
                  <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                  <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                  <td><?php echo htmlspecialchars($user['address']); ?></td>
                  <td><?php echo htmlspecialchars($user['email']); ?></td>
                  <td><?php echo htmlspecialchars($user['username']); ?></td>
                  <td><?php $dt = new DateTime($user['created_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
                  <td>
                    <a href="edit.php?entity=users&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                    <a href="admin_page.php?view=users&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                    <button onclick="confirmDelete('users', <?php echo $user['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="8">No users found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <section id="contact_inquiries" class="content-section">
      <h2>Contact Inquiries</h2>
      <a href="create.php?entity=contact_inquiries" class="btn btn-primary mb-3">Add New Contact Inquiry</a>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead class="thead-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Subject</th>
              <th>Message</th>
              <th>Date Submitted</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($contact_inquiries)): ?>
              <?php foreach ($contact_inquiries as $inquiry): ?>
                <tr>
                  <td><?php echo htmlspecialchars($inquiry['id']); ?></td>
                  <td><?php echo htmlspecialchars($inquiry['name']); ?></td>
                  <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
                  <td><?php echo htmlspecialchars($inquiry['subject']); ?></td>
                  <td><?php echo nl2br(htmlspecialchars($inquiry['message'])); ?></td>
                  <td><?php $dt = new DateTime($inquiry['created_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
                  <td>
                    <a href="edit.php?entity=contact_inquiries&id=<?php echo $inquiry['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                    <a href="admin_page.php?view=contact_inquiries&id=<?php echo $inquiry['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                    <button onclick="confirmDelete('contact_inquiries', <?php echo $inquiry['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr><td colspan="7">No contact inquiries found.</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="adminjs/dashboard_pie_chart.js"></script>
<script src="adminjs/dashboard_requests_over_time.js"></script>
<script>
$(document).ready(function() {
    // Sidebar toggle
    function openSidebar() {
        $('body').removeClass('sidebar-collapsed');
        $('#sidebarBackdrop').show();
    }
    function closeSidebar() {
        $('body').addClass('sidebar-collapsed');
        $('#sidebarBackdrop').hide();
    }

    $('#burgerMenuBtnSidebar, #burgerMenuBtnMain').click(function() {
        if ($('body').hasClass('sidebar-collapsed')) {
            openSidebar();
        } else {
            closeSidebar();
        }
        updateLayout();
    });

    $('#sidebarBackdrop').click(function() {
        closeSidebar();
        updateLayout();
    });

    // Tab navigation
    $('.nav-link').click(function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        if (!target) return;

        $('.nav-link').removeClass('active');
        $(this).addClass('active');

        $('.content-section').removeClass('active');
        $('#' + target).addClass('active');
    });

    // Adjust main content margin and burger button visibility based on sidebar state
    function updateLayout() {
        if ($('body').hasClass('sidebar-collapsed')) {
            $('#mainContent').css('margin-left', '60px');
            $('#burgerMenuBtnMain').show();
        } else {
            $('#mainContent').css('margin-left', '250px');
            $('#burgerMenuBtnMain').hide();
        }
    }

    // Initial layout update on page load
    updateLayout();
});
</script>
</body>
</html>
