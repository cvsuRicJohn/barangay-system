<?php
require_once 'chatbot-main/session_check.php';

check_admin_session();

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
$baptismal_certification_requests = fetchAll($pdo, 'baptismal_certification_requests');
$certificate_of_good_moral_requests = fetchAll($pdo, 'certificate_of_good_moral_requests');
$cohabitation_certification_requests = fetchAll($pdo, 'cohabitation_certification_requests');
$construction_clearance_requests = fetchAll($pdo, 'construction_clearance_requests');
$first_time_job_seeker_requests = fetchAll($pdo, 'first_time_job_seeker_requests');
$late_birth_registration_requests = fetchAll($pdo, 'late_birth_registration_requests');
$non_residency_certification_requests = fetchAll($pdo, 'non_residency_certification_requests');
$no_income_certification_requests = fetchAll($pdo, 'no_income_certification_requests');
$out_of_school_youth_requests = fetchAll($pdo, 'out_of_school_youth_requests');
$solo_parent_requests = fetchAll($pdo, 'solo_parent_requests');
$unemployment_certification_requests = fetchAll($pdo, 'unemployment_certification_requests');

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
    'baptismal_certification_requests' => countRows($pdo, 'baptismal_certification_requests'),
    'certificate_of_good_moral_requests' => countRows($pdo, 'certificate_of_good_moral_requests'),
    'cohabitation_certification_requests' => countRows($pdo, 'cohabitation_certification_requests'),
    'construction_clearance_requests' => countRows($pdo, 'construction_clearance_requests'),
    'first_time_job_seeker_requests' => countRows($pdo, 'first_time_job_seeker_requests'),
    'late_birth_registration_requests' => countRows($pdo, 'late_birth_registration_requests'),
    'non_residency_certification_requests' => countRows($pdo, 'non_residency_certification_requests'),
    'no_income_certification_requests' => countRows($pdo, 'no_income_certification_requests'),
    'out_of_school_youth_requests' => countRows($pdo, 'out_of_school_youth_requests'),
    'solo_parent_requests' => countRows($pdo, 'solo_parent_requests'),
    'unemployment_certification_requests' => countRows($pdo, 'unemployment_certification_requests'),
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
            UNION ALL
            SELECT name AS first_name, 'Contact Inquiry' AS type, created_at AS date_requested FROM contact_inquiries
            UNION ALL
            SELECT parent_name AS first_name, 'Baptismal Certification' AS type, submitted_at AS date_requested FROM baptismal_certification_requests
            UNION ALL
            SELECT full_name AS first_name, 'Certificate of Good Moral' AS type, submitted_at AS date_requested FROM certificate_of_good_moral_requests
            UNION ALL
            SELECT partner1_name AS first_name, 'Cohabitation Certification' AS type, submitted_at AS date_requested FROM cohabitation_certification_requests
            UNION ALL
            SELECT business_name AS first_name, 'Construction Clearance' AS type, submitted_at AS date_requested FROM construction_clearance_requests
            UNION ALL
            SELECT full_name AS first_name, 'First Time Job Seeker' AS type, submitted_at AS date_requested FROM first_time_job_seeker_requests
            UNION ALL
            SELECT last_name AS first_name, 'Late Birth Registration' AS type, submitted_at AS date_requested FROM late_birth_registration_requests
            UNION ALL
            SELECT full_name AS first_name, 'Non Residency Certification' AS type, submitted_at AS date_requested FROM non_residency_certification_requests
            UNION ALL
            SELECT full_name AS first_name, 'No Income Certification' AS type, submitted_at AS date_requested FROM no_income_certification_requests
            UNION ALL
            SELECT full_name AS first_name, 'Out of School Youth' AS type, submitted_at AS date_requested FROM out_of_school_youth_requests
            UNION ALL
            SELECT full_name AS first_name, 'Solo Parent' AS type, submitted_at AS date_requested FROM solo_parent_requests
            UNION ALL
            SELECT full_name AS first_name, 'Unemployment Certification' AS type, submitted_at AS date_requested FROM unemployment_certification_requests
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
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="baptismal_certification_requests">Baptismal Certification Requests <span class="badge badge-primary"><?php echo $counts['baptismal_certification_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="certificate_of_good_moral_requests">Certificate of Good Moral Requests <span class="badge badge-success"><?php echo $counts['certificate_of_good_moral_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="cohabitation_certification_requests">Cohabitation Certification Requests <span class="badge badge-warning"><?php echo $counts['cohabitation_certification_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="construction_clearance_requests">Construction Clearance Requests <span class="badge badge-info"><?php echo $counts['construction_clearance_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="first_time_job_seeker_requests">First Time Job Seeker Requests <span class="badge badge-secondary"><?php echo $counts['first_time_job_seeker_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="late_birth_registration_requests">Late Birth Registration Requests <span class="badge badge-info"><?php echo $counts['late_birth_registration_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="non_residency_certification_requests">Non Residency Certification Requests <span class="badge badge-primary"><?php echo $counts['non_residency_certification_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="no_income_certification_requests">No Income Certification Requests <span class="badge badge-success"><?php echo $counts['no_income_certification_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="out_of_school_youth_requests">Out of School Youth Requests <span class="badge badge-warning"><?php echo $counts['out_of_school_youth_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="solo_parent_requests">Solo Parent Requests <span class="badge badge-info"><?php echo $counts['solo_parent_requests']; ?></span></a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link" data-target="unemployment_certification_requests">Unemployment Certification Requests <span class="badge badge-secondary"><?php echo $counts['unemployment_certification_requests']; ?></span></a>
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
          'labels' => [
              'Barangay ID', 'Barangay Clearance', 'Certificate of Indigency', 'Certificate of Residency',
              'Contact Inquiries', 'Baptismal Certification', 'Certificate of Good Moral', 'Cohabitation Certification',
              'Construction Clearance', 'First Time Job Seeker', 'Late Birth Registration', 'Non Residency Certification',
              'No Income Certification', 'Out of School Youth', 'Solo Parent', 'Unemployment Certification'
          ],
          'data' => [
              $counts['barangay_id_requests'],
              $counts['barangay_clearance'],
              $counts['certificate_of_indigency_requests'],
              $counts['certificate_of_residency_requests'],
              $counts['contact_inquiries'],
              $counts['baptismal_certification_requests'],
              $counts['certificate_of_good_moral_requests'],
              $counts['cohabitation_certification_requests'],
              $counts['construction_clearance_requests'],
              $counts['first_time_job_seeker_requests'],
              $counts['late_birth_registration_requests'],
              $counts['non_residency_certification_requests'],
              $counts['no_income_certification_requests'],
              $counts['out_of_school_youth_requests'],
              $counts['solo_parent_requests'],
              $counts['unemployment_certification_requests']
          ]
      ];

      // Prepare data for requests over time chart
      // For simplicity, we will prepare monthly counts for the last 6 months for each service
      $months = [];
      $total_requests_counts = [];

      for ($i = 5; $i >= 0; $i--) {
          $month = date('Y-m', strtotime("-$i months"));
          $months[] = date('F Y', strtotime("-$i months"));

          $total_count = 0;

          // Sum counts for all services for the month
          $tables = [
              'barangay_id_requests' => 'created_at',
              'barangay_clearance' => 'created_at',
              'certificate_of_indigency_requests' => 'created_at',
              'certificate_of_residency_requests' => 'created_at',
              'contact_inquiries' => 'created_at',
              'baptismal_certification_requests' => 'submitted_at',
              'certificate_of_good_moral_requests' => 'submitted_at',
              'cohabitation_certification_requests' => 'submitted_at',
              'construction_clearance_requests' => 'submitted_at',
              'first_time_job_seeker_requests' => 'submitted_at',
              'late_birth_registration_requests' => 'submitted_at',
              'non_residency_certification_requests' => 'submitted_at',
              'no_income_certification_requests' => 'submitted_at',
              'out_of_school_youth_requests' => 'submitted_at',
              'solo_parent_requests' => 'submitted_at',
              'unemployment_certification_requests' => 'submitted_at'
          ];

          foreach ($tables as $table => $date_field) {
              $stmt = $pdo->prepare("SELECT COUNT(*) FROM $table WHERE DATE_FORMAT($date_field, '%Y-%m') = :month");
              $stmt->execute(['month' => $month]);
              $total_count += (int)$stmt->fetchColumn();
          }

          $total_requests_counts[] = $total_count;
      }

      $requestsOverTimeData = [
          'labels' => $months,
          'total_requests' => $total_requests_counts
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
              <td>Contact Inquiries</td>
              <td><?php echo $counts['contact_inquiries']; ?></td>
            </tr>
            <tr>
              <td>Baptismal Certification Requests</td>
              <td><?php echo $counts['baptismal_certification_requests']; ?></td>
            </tr>
            <tr>
              <td>Certificate of Good Moral Requests</td>
              <td><?php echo $counts['certificate_of_good_moral_requests']; ?></td>
            </tr>
            <tr>
              <td>Cohabitation Certification Requests</td>
              <td><?php echo $counts['cohabitation_certification_requests']; ?></td>
            </tr>
            <tr>
              <td>Construction Clearance Requests</td>
              <td><?php echo $counts['construction_clearance_requests']; ?></td>
            </tr>
            <tr>
              <td>First Time Job Seeker Requests</td>
              <td><?php echo $counts['first_time_job_seeker_requests']; ?></td>
            </tr>
            <tr>
              <td>Late Birth Registration Requests</td>
              <td><?php echo $counts['late_birth_registration_requests']; ?></td>
            </tr>
            <tr>
              <td>Non Residency Certification Requests</td>
              <td><?php echo $counts['non_residency_certification_requests']; ?></td>
            </tr>
            <tr>
              <td>No Income Certification Requests</td>
              <td><?php echo $counts['no_income_certification_requests']; ?></td>
            </tr>
            <tr>
              <td>Out of School Youth Requests</td>
              <td><?php echo $counts['out_of_school_youth_requests']; ?></td>
            </tr>
            <tr>
              <td>Solo Parent Requests</td>
              <td><?php echo $counts['solo_parent_requests']; ?></td>
            </tr>
            <tr>
              <td>Unemployment Certification Requests</td>
              <td><?php echo $counts['unemployment_certification_requests']; ?></td>
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

<!-- New content sections for additional tables -->

<section id="baptismal_certification_requests" class="content-section">
  <h2>Baptismal Certification Requests</h2>
  <a href="create.php?entity=baptismal_certification_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Parent Name</th>
          <th>Address</th>
          <th>Child Name</th>
          <th>Purpose</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($baptismal_certification_requests)): ?>
          <?php foreach ($baptismal_certification_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['parent_name']); ?></td>
              <td><?php echo htmlspecialchars($req['address']); ?></td>
              <td><?php echo htmlspecialchars($req['child_name']); ?></td>
              <td><?php echo htmlspecialchars($req['purpose']); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=baptismal_certification_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=baptismal_certification_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('baptismal_certification_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="9">No Baptismal Certification requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section id="certificate_of_good_moral_requests" class="content-section">
  <h2>Certificate of Good Moral Requests</h2>
  <a href="create.php?entity=certificate_of_good_moral_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Age</th>
          <th>Civil Status</th>
          <th>Address</th>
          <th>Purpose</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($certificate_of_good_moral_requests)): ?>
          <?php foreach ($certificate_of_good_moral_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['full_name']); ?></td>
              <td><?php echo htmlspecialchars($req['age']); ?></td>
              <td><?php echo htmlspecialchars($req['civil_status']); ?></td>
              <td><?php echo htmlspecialchars($req['address']); ?></td>
              <td><?php echo htmlspecialchars($req['purpose']); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=certificate_of_good_moral_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=certificate_of_good_moral_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('certificate_of_good_moral_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="10">No Certificate of Good Moral requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section id="cohabitation_certification_requests" class="content-section">
  <h2>Cohabitation Certification Requests</h2>
  <a href="create.php?entity=cohabitation_certification_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Partner 1 Name</th>
          <th>Partner 2 Name</th>
          <th>Shared Address</th>
          <th>Cohabitation Duration</th>
          <th>Purpose</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($cohabitation_certification_requests)): ?>
          <?php foreach ($cohabitation_certification_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['partner1_name']); ?></td>
              <td><?php echo htmlspecialchars($req['partner2_name']); ?></td>
              <td><?php echo htmlspecialchars($req['shared_address']); ?></td>
              <td><?php echo htmlspecialchars($req['cohabitation_duration']); ?></td>
              <td><?php echo htmlspecialchars($req['purpose']); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=cohabitation_certification_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=cohabitation_certification_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('cohabitation_certification_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="10">No Cohabitation Certification requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section id="construction_clearance_requests" class="content-section">
  <h2>Construction Clearance Requests</h2>
  <a href="create.php?entity=construction_clearance_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Business Name</th>
          <th>Business Location</th>
          <th>Owner Name</th>
          <th>Owner Address</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($construction_clearance_requests)): ?>
          <?php foreach ($construction_clearance_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['business_name']); ?></td>
              <td><?php echo htmlspecialchars($req['business_location']); ?></td>
              <td><?php echo htmlspecialchars($req['owner_name']); ?></td>
              <td><?php echo htmlspecialchars($req['owner_address']); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=construction_clearance_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=construction_clearance_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('construction_clearance_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="9">No Construction Clearance requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section id="first_time_job_seeker_requests" class="content-section">
  <h2>First Time Job Seeker Requests</h2>
  <a href="create.php?entity=first_time_job_seeker_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Address</th>
          <th>Residency Length</th>
          <th>Oath Acknowledged</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($first_time_job_seeker_requests)): ?>
          <?php foreach ($first_time_job_seeker_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['full_name']); ?></td>
              <td><?php echo htmlspecialchars($req['address']); ?></td>
              <td><?php echo htmlspecialchars($req['residency_length']); ?></td>
              <td><?php echo htmlspecialchars($req['oath_acknowledged']); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=first_time_job_seeker_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=first_time_job_seeker_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('first_time_job_seeker_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="9">No First Time Job Seeker requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section id="late_birth_registration_requests" class="content-section">
  <h2>Late Birth Registration Requests</h2>
  <a href="create.php?entity=late_birth_registration_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Last Name</th>
          <th>First Name</th>
          <th>Middle Name</th>
          <th>Address</th>
          <th>Marital Status</th>
          <th>Place of Birth</th>
          <th>Date of Birth</th>
          <th>Fathers Name</th>
          <th>Mothers Name</th>
          <th>Years in Barangay</th>
          <th>Purpose</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($late_birth_registration_requests)): ?>
          <?php foreach ($late_birth_registration_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['last_name']); ?></td>
              <td><?php echo htmlspecialchars($req['first_name']); ?></td>
              <td><?php echo htmlspecialchars($req['middle_name']); ?></td>
              <td><?php echo htmlspecialchars($req['address']); ?></td>
              <td><?php echo htmlspecialchars($req['marital_status']); ?></td>
              <td><?php echo htmlspecialchars($req['place_of_birth']); ?></td>
              <td><?php $dt = new DateTime($req['date_of_birth']); echo htmlspecialchars($dt->format('F j, Y')); ?></td>
              <td><?php echo htmlspecialchars($req['fathers_name']); ?></td>
              <td><?php echo htmlspecialchars($req['mothers_name']); ?></td>
              <td><?php echo htmlspecialchars($req['years_in_barangay']); ?></td>
              <td><?php echo htmlspecialchars($req['purpose']); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=late_birth_registration_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=late_birth_registration_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('late_birth_registration_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="16">No Late Birth Registration requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section id="non_residency_certification_requests" class="content-section">
  <h2>Non Residency Certification Requests</h2>
  <a href="create.php?entity=non_residency_certification_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Previous Address</th>
          <th>Purpose</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($non_residency_certification_requests)): ?>
          <?php foreach ($non_residency_certification_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['full_name']); ?></td>
              <td><?php echo htmlspecialchars($req['previous_address']); ?></td>
              <td><?php echo htmlspecialchars($req['purpose']); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=non_residency_certification_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=non_residency_certification_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('non_residency_certification_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8">No Non Residency Certification requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section id="no_income_certification_requests" class="content-section">
  <h2>No Income Certification Requests</h2>
  <a href="create.php?entity=no_income_certification_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Date of Birth</th>
          <th>Civil Status</th>
          <th>Address</th>
          <th>No Income Statement</th>
          <th>Purpose</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($no_income_certification_requests)): ?>
          <?php foreach ($no_income_certification_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['full_name']); ?></td>
              <td><?php $dt = new DateTime($req['date_of_birth']); echo htmlspecialchars($dt->format('F j, Y')); ?></td>
              <td><?php echo htmlspecialchars($req['civil_status']); ?></td>
              <td><?php echo htmlspecialchars($req['address']); ?></td>
              <td><?php echo nl2br(htmlspecialchars($req['no_income_statement'])); ?></td>
              <td><?php echo htmlspecialchars($req['purpose']); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=no_income_certification_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=no_income_certification_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('no_income_certification_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="11">No No Income Certification requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section id="out_of_school_youth_requests" class="content-section">
  <h2>Out of School Youth Requests</h2>
  <a href="create.php?entity=out_of_school_youth_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Address</th>
          <th>Citizenship</th>
          <th>Purpose</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($out_of_school_youth_requests)): ?>
          <?php foreach ($out_of_school_youth_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['full_name']); ?></td>
              <td><?php echo htmlspecialchars($req['address']); ?></td>
              <td><?php echo htmlspecialchars($req['citizenship']); ?></td>
              <td><?php echo htmlspecialchars($req['purpose']); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=out_of_school_youth_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=out_of_school_youth_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('out_of_school_youth_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="9">No Out of School Youth requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section id="solo_parent_requests" class="content-section">
  <h2>Solo Parent Requests</h2>
  <a href="create.php?entity=solo_parent_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Address</th>
          <th>Solo Since</th>
          <th>Child Count</th>
          <th>Children Names</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($solo_parent_requests)): ?>
          <?php foreach ($solo_parent_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['full_name']); ?></td>
              <td><?php echo htmlspecialchars($req['address']); ?></td>
              <td><?php echo htmlspecialchars($req['solo_since']); ?></td>
              <td><?php echo htmlspecialchars($req['child_count']); ?></td>
              <td><?php echo nl2br(htmlspecialchars($req['children_names'])); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=solo_parent_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=solo_parent_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('solo_parent_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="10">No Solo Parent requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<section id="unemployment_certification_requests" class="content-section">
  <h2>Unemployment Certification Requests</h2>
  <a href="create.php?entity=unemployment_certification_requests" class="btn btn-primary mb-3">Add New</a>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead class="thead-dark">
        <tr>
          <th>ID</th>
          <th>Full Name</th>
          <th>Age</th>
          <th>Birth Date</th>
          <th>Civil Status</th>
          <th>Address</th>
          <th>Purpose</th>
          <th>Email</th>
          <th>Shipping Method</th>
          <th>Submitted At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($unemployment_certification_requests)): ?>
          <?php foreach ($unemployment_certification_requests as $req): ?>
            <tr>
              <td><?php echo htmlspecialchars($req['id']); ?></td>
              <td><?php echo htmlspecialchars($req['full_name']); ?></td>
              <td><?php echo htmlspecialchars($req['age']); ?></td>
              <td><?php $dt = new DateTime($req['birth_date']); echo htmlspecialchars($dt->format('F j, Y')); ?></td>
              <td><?php echo htmlspecialchars($req['civil_status']); ?></td>
              <td><?php echo htmlspecialchars($req['address']); ?></td>
              <td><?php echo htmlspecialchars($req['purpose']); ?></td>
              <td><?php echo htmlspecialchars($req['email']); ?></td>
              <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
              <td><?php $dt = new DateTime($req['submitted_at']); echo htmlspecialchars($dt->format('F j, Y, g:i a')); ?></td>
              <td>
                <a href="edit.php?entity=unemployment_certification_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                <a href="admin_page.php?view=unemployment_certification_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                <button onclick="confirmDelete('unemployment_certification_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="11">No Unemployment Certification requests found.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</section>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="adminjs/dashboard_pie_chart.js"></script>
<script src="adminjs/dashboard_requests_over_time.js?v=1"></script>
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
