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

    // Validate id is numeric
    if (!is_numeric($id)) {
        die("Invalid ID for deletion.");
    }

// Map entity to table name
$valid_entities = [
    'barangay_id_requests' => 'barangay_id_requests',
    'barangay_clearance' => 'barangay_clearance',
    'certificate_of_indigency_requests' => 'certificate_of_indigency_requests',
    'certificate_of_residency_requests' => 'certificate_of_residency_requests',
    'users' => 'users',
    'contact_inquiries' => 'contact_inquiries'
];

    if (!array_key_exists($entity, $valid_entities)) {
        die("Invalid entity for deletion.");
    }

    $table = $valid_entities[$entity];

    try {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        // Redirect back to admin_page.php with tab set to entity to show updated list
        header("Location: admin_page.php?tab=$entity");
        exit();
    } catch (PDOException $e) {
        die("Error deleting record: " . $e->getMessage());
    }
}

/* Prepare data for dashboard charts */

// Fetch all Barangay ID requests
try {
    $stmt = $pdo->query("SELECT * FROM barangay_id_requests ORDER BY id DESC");
    $requests = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching barangay ID requests: " . $e->getMessage());
}

// Fetch all Barangay Clearance requests
try {
    $stmt = $pdo->query("SELECT * FROM barangay_clearance ORDER BY id DESC");
    $clearance_requests = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching barangay clearance requests: " . $e->getMessage());
}

/* Prepare data for dashboard charts */

// Fetch all Certificate of Indigency requests
try {
    $stmt = $pdo->query("SELECT * FROM certificate_of_indigency_requests ORDER BY id DESC");
    $indigency_requests = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching certificate of indigency requests: " . $e->getMessage());
}

// Fetch all Certificate of Residency requests
try {
    $stmt = $pdo->query("SELECT * FROM certificate_of_residency_requests ORDER BY id DESC");
    $residency_requests = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching certificate of residency requests: " . $e->getMessage());
}

/* Prepare data for dashboard charts */

// Fetch all users for user management
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
}

// Fetch all contact inquiries
try {
    $stmt = $pdo->query("SELECT * FROM contact_inquiries ORDER BY id DESC");
    $contact_inquiries = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching contact inquiries: " . $e->getMessage());
}

// Prepare data for pie chart: counts of each request type
try {
    $stmt = $pdo->query("SELECT 
        (SELECT COUNT(*) FROM barangay_id_requests) AS barangay_id_count,
        (SELECT COUNT(*) FROM barangay_clearance) AS clearance_count,
        (SELECT COUNT(*) FROM certificate_of_indigency_requests) AS indigency_count,
        (SELECT COUNT(*) FROM certificate_of_residency_requests) AS residency_count
    ");
    $counts = $stmt->fetch();
} catch (PDOException $e) {
    die("Error fetching counts for pie chart: " . $e->getMessage());
}

// Prepare data for requests over time chart (last 6 months)
try {
    $months = [];
    $barangay_id_data = [];
    $clearance_data = [];
    $indigency_data = [];
    $residency_data = [];

    for ($i = 5; $i >= 0; $i--) {
        $month = date('Y-m', strtotime("-$i months"));
        $months[] = date('M Y', strtotime($month));

        // Barangay ID requests count for month
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM barangay_id_requests WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
        $stmt->execute([$month]);
        $barangay_id_data[] = (int)$stmt->fetchColumn();

        // Clearance requests count for month
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM barangay_clearance WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
        $stmt->execute([$month]);
        $clearance_data[] = (int)$stmt->fetchColumn();

        // Indigency requests count for month
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM certificate_of_indigency_requests WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
        $stmt->execute([$month]);
        $indigency_data[] = (int)$stmt->fetchColumn();

        // Residency requests count for month
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM certificate_of_residency_requests WHERE DATE_FORMAT(created_at, '%Y-%m') = ?");
        $stmt->execute([$month]);
        $residency_data[] = (int)$stmt->fetchColumn();
    }
} catch (PDOException $e) {
    die("Error fetching data for requests over time chart: " . $e->getMessage());
}

// Prepare recent requests for dashboard (latest 7 requests from all request tables)
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
  <link rel="stylesheet" href="chatbot-main/css/contact.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" />
  <link rel="stylesheet" href="admincss/admin_page.css" />
  <script>
    function confirmDelete(entity, id) {
      if (confirm('Are you sure you want to delete this ' + entity + ' with ID ' + id + '?')) {
        window.location.href = 'admin_page.php?action=delete&entity=' + entity + '&id=' + id;
      }
    }

    // Pass PHP data to JS for charts
    const pieChartData = <?php echo json_encode([
        'labels' => ['Barangay ID', 'Barangay Clearance', 'Certificate of Indigency', 'Certificate of Residency'],
        'data' => [
            (int)$counts['barangay_id_count'],
            (int)$counts['clearance_count'],
            (int)$counts['indigency_count'],
            (int)$counts['residency_count']
        ]
    ]); ?>;

    const requestsOverTimeData = <?php echo json_encode([
        'labels' => $months,
        'barangay_id' => $barangay_id_data,
        'clearance' => $clearance_data,
        'indigency' => $indigency_data,
        'residency' => $residency_data
    ]); ?>;

    const recentRequests = <?php echo json_encode($recent_requests ?? []); ?>;
  </script>
</head>
<body class="sidebar-collapsed">

  <!-- Header -->
  <div class="d-flex align-items-center mb-3">
    <button id="burgerMenuBtnMain" class="btn btn-primary mr-3" type="button" aria-label="Toggle sidebar" style="font-size: 1.5rem; padding: 0.25rem 0.5rem;">
      <i class="fas fa-bars"></i>
    </button>
    <h1 class="mb-0">Admin Panel - Service Management</h1>
  </div>

  <!-- Sidebar backdrop -->
  <div id="sidebarBackdrop" style="display:none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 1040;"></div>

  <div class="container-fluid mt-4">
    <div class="row">
      <!-- Sidebar -->
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar d-flex flex-column">
        <div class="d-flex justify-content-start p-2">
          <button id="burgerMenuBtnSidebar" class="btn btn-primary" type="button" aria-label="Toggle sidebar" style="font-size: 1.5rem; padding: 0.25rem 0.5rem;">
            <i class="fas fa-bars"></i>
          </button>
        </div>
        <div class="sidebar-sticky pt-3 flex-grow-1">
          <h5 class="sidebar-heading px-3 mb-3">Dashboard</h5>
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" href="#" data-target="dashboard">
                <span data-feather="home"></span>
                Overview
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-target="barangay_id_requests">
                Barangay ID Requests <span class="badge badge-primary"><?php echo count($requests); ?></span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-target="barangay_clearance">
                Barangay Clearance Requests <span class="badge badge-success"><?php echo count($clearance_requests); ?></span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-target="certificate_of_indigency_requests">
                Certificate of Indigency Requests <span class="badge badge-warning"><?php echo count($indigency_requests); ?></span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#" data-target="certificate_of_residency_requests">
                Certificate of Residency Requests <span class="badge badge-info"><?php echo count($residency_requests); ?></span>
              </a>
            </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-target="users">
              Users <span class="badge badge-secondary"><?php echo count($users); ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#" data-target="contact_inquiries">
              Contact Inquiries <span class="badge badge-info"><?php echo count($contact_inquiries); ?></span>
            </a>
          </li>
        </ul>
        </div>
        <div class="logout-link px-3 py-3 border-top">
          <a href="admin_page.php?action=logout" onclick="return confirm('Are you sure you want to log out?');" class="text-danger font-weight-bold">Logout</a>
        </div>
      </nav>

      <!-- Main content -->
      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
        <div id="dashboard" class="content-section">
          <h2>Dashboard Overview</h2>
            <!-- Charts Row - Will stack vertically on mobile -->
          <div class="row mb-4">
            <!-- Pie Chart -->
            <div class="col-12 col-lg-4 mb-4">
              <div class="card shadow-lg h-100 dashboard-card">
                <div class="card-body">
                  <canvas id="requestsPieChart" style="width:100%; height:150px;"></canvas>
                </div>
              </div>
            </div>
            
            <!-- Line Chart -->
            <div class="col-12 col-lg-4 mb-4">
              <div class="card shadow-lg h-100 dashboard-card">
                <div class="card-body">
                  <canvas id="requestsOverTimeChart" style="width:100%; height:220px;"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="row" style="align-items: flex-start;">
          <!-- Recent Requests -->
          <div class="col-12 col-lg-8 mb-4">
            <div class="card shadow-lg h-100 dashboard-card recent-requests-container">
              <div class="card-body">
                <h4 class="mb-3" style="font-weight: 700; color: #333;">Recent Requests</h4>
                <div class="table-responsive">
                  <table class="table table-striped table-hover table-bordered recent-requests-table">
                    <thead class="thead-dark">
                      <tr>
                        <th>Name</th>
                        <th>Type of Request</th>
                        <th>Date Requested</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        if (is_array($recent_requests) && !empty($recent_requests)) {
                          foreach ($recent_requests as $req) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($req['first_name']) . "</td>";
                            echo "<td>" . htmlspecialchars($req['type']) . "</td>";
                            $date = new DateTime($req['date_requested']);
                            echo "<td>" . htmlspecialchars($date->format('F j, Y, g:i a')) . "</td>";
                            echo "</tr>";
                          }
                        } else {
                          echo "<tr><td colspan='3'>No recent requests found.</td></tr>";
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          
          <div class="col-12 col-lg-4 mb-4">
            <div class="blocks-container" style="display: grid; max-width: 500px; gap: 15px; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); padding-top: 0; margin-left: 0px;">
              <div class="card shadow-lg text-white bg-primary dashboard-card" style="font-size: 0.85rem; height: 150px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="padding: 10px;">
                  <i class="fas fa-id-card fa-3x mb-3"></i>
                  <h5 class="card-title" style="font-size: 1.0rem;">Barangay ID Requests</h5>
                  <p class="card-text display-5" style="font-size: 1.50rem;"><?php echo count($requests); ?></p>
                </div>
              </div>
              <div class="card shadow-lg text-white bg-success dashboard-card" style="font-size: 0.85rem; height: 150px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="padding: 10px;">
                  <i class="fas fa-file-alt fa-3x mb-3"></i>
                  <h5 class="card-title" style="font-size: 1.0rem;">Barangay Clearance Requests</h5>
                  <p class="card-text display-5" style="font-size: 1.50rem;"><?php echo count($clearance_requests); ?></p>
                </div>
              </div>
              <div class="card shadow-lg text-white bg-warning dashboard-card" style="font-size: 0.85rem; height: 150px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center" style="padding: 10px;">
                  <i class="fas fa-certificate fa-3x mb-3"></i>
                  <h5 class="card-title" style="font-size: 1.0rem;">Certificate of Indigency Requests</h5>
                  <p class="card-text display-5" style="font-size: 1.50rem;"><?php echo count($indigency_requests); ?></p>
                </div>
              </div>
              <div class="card shadow-lg text-white bg-info dashboard-card" style="font-size: 0.85rem; height: 150px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center" style="padding: 10px;">
                  <i class="fas fa-home fa-3x mb-3"></i>
                  <h5 class="card-title" style="font-size: 1.0rem;">Certificate of Residency Requests</h5>
                  <p class="card-text display-5" style="font-size: 1.50rem;"><?php echo count($residency_requests); ?></p>
                </div>
              </div>
              <div class="card shadow-lg text-white bg-secondary dashboard-card" style="font-size: 0.85rem; height: 150px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="padding: 10px;">
                  <i class="fas fa-users fa-3x mb-3"></i>
                  <h5 class="card-title" style="font-size: 1.0rem;">Users</h5>
                  <p class="card-text display-5" style="font-size: 1.50rem;"><?php echo count($users); ?></p>
                </div>
              </div>
              <div class="card shadow-lg text-white bg-info dashboard-card" style="font-size: 0.85rem; height: 150px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="padding: 10px;">
                  <i class="fas fa-envelope fa-3x mb-3"></i>
                  <h5 class="card-title" style="font-size: 1.0rem;">Contact Inquiries</h5>
                  <p class="card-text display-5" style="font-size: 1.50rem;"><?php echo count($contact_inquiries); ?></p>
                </div>
              </div>
              <div class="card shadow-lg text-white bg-dark dashboard-card" style="font-size: 0.85rem; height: 150px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="padding: 10px;">
                  <i class="fas fa-comments fa-3x mb-3"></i>
                  <h5 class="card-title" style="font-size: 1.0rem;">First Time Jobseeker</h5>
                  <p class="card-text display-5" style="font-size: 1.50rem;">0</p>
                </div>
              </div>
              <div class="card shadow-lg text-white bg-secondary dashboard-card" style="font-size: 0.85rem; height: 150px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center" style="padding: 10px;">
                  <i class="fas fa-chart-line fa-3x mb-3"></i>
                  <h5 class="card-title" style="font-size: 1.0rem;">Women And Children Assistance</h5>
                  <p class="card-text display-5" style="font-size: 1.50rem;">0</p>
                </div>
              </div>
              <div class="card shadow-lg text-white bg-primary dashboard-card" style="font-size: 0.85rem; height: 150px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="padding: 10px;">
                  <i class="fas fa-users-cog fa-3x mb-3"></i>
                  <h5 class="card-title" style="font-size: 1.0rem;">Placeholder 3</h5>
                  <p class="card-text display-5" style="font-size: 1.75rem;">0</p>
                </div>
              </div>
              <div class="card shadow-lg text-white bg-success dashboard-card" style="font-size: 0.85rem; height: 150px;">
                <div class="card-body d-flex flex-column justify-content-center align-items-center" style="padding: 10px;">
                  <i class="fas fa-bell fa-3x mb-3"></i>
                  <h5 class="card-title" style="font-size: 1.0rem;">Placeholder 4</h5>
                  <p class="card-text display-5" style="font-size: 1.50rem;">0</p>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        

        <!-- Detailed sections -->
        <div id="barangay_id_requests" class="content-section" style="display:none;">
          <h2>Barangay ID Requests</h2>
          <a href="create.php?entity=barangay_id_requests" class="btn btn-primary mb-3">Add New</a>
          <?php if (count($requests) === 0): ?>
            <p>No Barangay ID requests found.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered">
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
                  <?php foreach ($requests as $req): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($req['id']); ?></td>
                      <td><?php echo htmlspecialchars($req['first_name']); ?></td>
                      <td><?php echo htmlspecialchars($req['middle_name']); ?></td>
                      <td><?php echo htmlspecialchars($req['last_name']); ?></td>
                      <td><?php echo htmlspecialchars($req['address']); ?></td>
                      <td><?php $date = new DateTime($req['date_of_birth']); echo htmlspecialchars($date->format('F j, Y')); ?></td>
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
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <div id="barangay_clearance" class="content-section" style="display:none;">
          <h2>Barangay Clearance Requests</h2>
          <a href="create.php?entity=barangay_clearance" class="btn btn-primary mb-3">Add New</a>
          <?php if (count($clearance_requests) === 0): ?>
            <p>No Barangay Clearance requests found.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered">
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
                    <th>STUDENT/<br>PATIENT<br>NAME</th>
                    <th>STUDENT/<br>PATIENT<br>ADDRESS</th>
                    <th>Relationship</th>
                    <th>Email</th>
                    <th>Shipping Method</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($clearance_requests as $req): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($req['id']); ?></td>
                      <td><?php echo htmlspecialchars($req['first_name']); ?></td>
                      <td><?php echo htmlspecialchars($req['middle_name']); ?></td>
                      <td><?php echo htmlspecialchars($req['last_name']); ?></td>
                      <td><?php echo htmlspecialchars($req['complete_address']); ?></td>
                      <td><?php $date = new DateTime($req['birth_date']); echo htmlspecialchars($date->format('F j, Y')); ?></td>
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
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <div id="certificate_of_indigency_requests" class="content-section" style="display:none;">
          <h2>Certificate of Indigency Requests</h2>
          <a href="create.php?entity=certificate_of_indigency_requests" class="btn btn-primary mb-3">Add New</a>
          <?php if (count($indigency_requests) === 0): ?>
            <p>No Certificate of Indigency requests found.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered">
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
                  <?php foreach ($indigency_requests as $req): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($req['id']); ?></td>
                      <td><?php echo htmlspecialchars($req['first_name']); ?></td>
                      <td><?php echo htmlspecialchars($req['middle_name']); ?></td>
                      <td><?php echo htmlspecialchars($req['last_name']); ?></td>
                      <td><?php $date = new DateTime($req['date_of_birth']); echo htmlspecialchars($date->format('F j, Y')); ?></td>
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
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <div id="certificate_of_residency_requests" class="content-section" style="display:none;">
          <h2>Certificate of Residency Requests</h2>
          <a href="create.php?entity=certificate_of_residency_requests" class="btn btn-primary mb-3">Add New</a>
          <?php if (count($residency_requests) === 0): ?>
            <p>No Certificate of Residency requests found.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered">
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
                  <?php foreach ($residency_requests as $req): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($req['id']); ?></td>
                      <td><?php echo htmlspecialchars($req['first_name']); ?></td>
                      <td><?php echo htmlspecialchars($req['middle_name']); ?></td>
                      <td><?php echo htmlspecialchars($req['last_name']); ?></td>
                      <td><?php $date = new DateTime($req['date_of_birth']); echo htmlspecialchars($date->format('F j, Y')); ?></td>
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
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <div id="users" class="content-section" style="display:none;">
          <h2>User Management</h2>
          <a href="create.php?entity=users" class="btn btn-primary mb-3">Add New User</a>
          <?php if (count($users) === 0): ?>
            <p>No users found.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered">
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
                  <?php foreach ($users as $user): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($user['id']); ?></td>
                      <td><?php echo htmlspecialchars($user['first_name']); ?></td>
                      <td><?php echo htmlspecialchars($user['last_name']); ?></td>
                      <td><?php echo htmlspecialchars($user['address']); ?></td>
                      <td><?php echo htmlspecialchars($user['email']); ?></td>
                      <td><?php echo htmlspecialchars($user['username']); ?></td>
                      <td><?php $date = new DateTime($user['created_at']); echo htmlspecialchars($date->format('F j, Y, g:i a')); ?></td>
                      <td>
                        <a href="edit.php?entity=users&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                        <a href="admin_page.php?view=users&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                        <button onclick="confirmDelete('users', <?php echo $user['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>

        <div id="contact_inquiries" class="content-section" style="display:none;">
          <h2>Contact Inquiries</h2>
          <a href="create.php?entity=contact_inquiries" class="btn btn-primary mb-3">Add New Contact Inquiry</a>
          <?php if (count($contact_inquiries) === 0): ?>
            <p>No contact inquiries found.</p>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-striped table-hover table-bordered">
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
                  <?php foreach ($contact_inquiries as $inquiry): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($inquiry['id']); ?></td>
                      <td><?php echo htmlspecialchars($inquiry['name']); ?></td>
                      <td><?php echo htmlspecialchars($inquiry['email']); ?></td>
                      <td><?php echo htmlspecialchars($inquiry['subject']); ?></td>
                      <td><?php echo nl2br(htmlspecialchars($inquiry['message'])); ?></td>
                      <td><?php echo htmlspecialchars($inquiry['created_at']); ?></td>
                      <td>
                        <a href="edit.php?entity=contact_inquiries&id=<?php echo $inquiry['id']; ?>" class="btn btn-sm btn-warning action-btn">Edit</a>
                        <a href="admin_page.php?view=contact_inquiries&id=<?php echo $inquiry['id']; ?>" class="btn btn-sm btn-primary action-btn">View</a>
                        <button onclick="confirmDelete('contact_inquiries', <?php echo $inquiry['id']; ?>)" class="btn btn-sm btn-danger action-btn">Delete</button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </main>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="adminjs/dashboard_pie_chart.js"></script>
  <script src="adminjs/dashboard_requests_over_time.js"></script>
  <script>
    function confirmDelete(entity, id) {
      if (confirm('Are you sure you want to delete this ' + entity + ' with ID ' + id + '?')) {
        window.location.href = 'admin_page.php?action=delete&entity=' + entity + '&id=' + id;
      }
    }

    function onDashboardSelect(value) {
      if (value) {
        $('.content-section').hide();
        $('#' + value).show();
        $('.nav-link').removeClass('active');
        $('.nav-link[data-target="' + value + '"]').addClass('active');
        // Also update the dropdown to reflect the current selection
        $('#dashboardDropdown').val(value);
      }
    }

    $(document).ready(function() {
      function showSection(target) {
        console.log('showSection called with target:', target);
        $('.content-section').hide();
        $('#' + target).show();
        $('.nav-link').removeClass('active');
        $('.nav-link[data-target="' + target + '"]').addClass('active');

        // If charts exist, trigger resize to fix display issues on tab switch
        if (typeof requestsPieChart !== 'undefined' && typeof requestsPieChart.resize === 'function') {
          requestsPieChart.resize();
        }
        if (typeof requestsOverTimeChart !== 'undefined' && typeof requestsOverTimeChart.resize === 'function') {
          requestsOverTimeChart.resize();
        }
      }

      // Handle sidebar navigation clicks
      $('.nav-link').click(function(e) {
        e.preventDefault();
        var target = $(this).data('target');
        console.log('nav-link clicked, target:', target);
        showSection(target);
        // Update dropdown selection if on dashboard page
        if (target === 'dashboard') {
          $('#dashboardDropdown').val('');
        } else {
          $('#dashboardDropdown').val(target);
        }
      });

      // Show section based on URL parameter 'tab' or default to dashboard
      function getParameterByName(name) {
        const url = window.location.href;
        name = name.replace(/[\[\]]/g, '\\$&');
        const regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)');
        const results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
      }

      var activeTab = getParameterByName('tab');
      if (activeTab && $('.nav-link[data-target="' + activeTab + '"]').length) {
        showSection(activeTab);
        // Update dropdown if dashboard is active
        if (activeTab !== 'dashboard') {
          $('#dashboardDropdown').val(activeTab);
        }
      } else {
        showSection('dashboard');
        $('#dashboardDropdown').val('');
      }

      // Sidebar toggle functions
      function openSidebar() {
        $('body').removeClass('sidebar-collapsed');
        $('#sidebarBackdrop').show();
      }
      function closeSidebar() {
        $('body').addClass('sidebar-collapsed');
        $('#sidebarBackdrop').hide();
      }

      // Toggle sidebar on burger menu click
      $('#burgerMenuBtnMain, #burgerMenuBtnSidebar').click(function() {
        if ($('body').hasClass('sidebar-collapsed')) {
          openSidebar();
        } else {
          closeSidebar();
        }
      });

      // Close sidebar when clicking outside of it
      $('#sidebarBackdrop').click(function() {
        closeSidebar();
      });
    });
  </script>
<?php
if (isset($_GET['view']) && isset($_GET['id'])) {
    $entity = $_GET['view'];
    $id = $_GET['id'];

    $valid_entities = [
        'barangay_id_requests' => 'barangay_id_requests',
        'barangay_clearance' => 'barangay_clearance',
        'certificate_of_indigency_requests' => 'certificate_of_indigency_requests',
        'certificate_of_residency_requests' => 'certificate_of_residency_requests',
        'users' => 'users',
        'contact_inquiries' => 'contact_inquiries'
    ];

    if (array_key_exists($entity, $valid_entities) && is_numeric($id)) {
        $table = $valid_entities[$entity];
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        $record = $stmt->fetch();

        if ($record) {
            echo "<div class='container mt-4'>";
            echo "<div class='card shadow-lg p-4' style='max-width: 800px; margin: 0 auto;'>";
            echo "<h2 class='mb-4 text-center'>View Details - " . htmlspecialchars($entity) . "</h2>";
            echo "<div class='row'>";
            foreach ($record as $key => $value) {
                $label = htmlspecialchars(ucwords(str_replace('_', ' ', $key)));
                $val = nl2br(htmlspecialchars($value));
                // If the field is an image URL or filename, display as image
                $isImage = false;
                $lowerKey = strtolower($key);
                $lowerVal = strtolower($value);
                $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                foreach ($imageExtensions as $ext) {
                    if (str_ends_with($lowerVal, ".$ext")) {
                        $isImage = true;
                        break;
                    }
                }
                echo "<div class='col-md-6 mb-3'>";
                echo "<label class='font-weight-bold d-block mb-1'>" . $label . "</label>";
                if ($isImage) {
                    // Display image with max width and height
                    $imgSrc = htmlspecialchars($value);
                    echo "<img src='$imgSrc' alt='$label' class='img-fluid rounded border' style='max-height: 200px; object-fit: contain;'>";
                } else {
                    echo "<div class='p-2 border rounded bg-light text-dark' style='min-height: 30px;'>" . $val . "</div>";
                }
                echo "</div>";
            }
            echo "</div>";
            echo "<div class='text-center mt-4'>";
            echo "<a href='admin_page.php?tab=$entity' class='btn btn-secondary mr-3'>Back</a>";
            echo "<a href='edit.php?entity=$entity&id=$id' class='btn btn-primary'>Edit</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            exit();
        } else {
            echo "<p>Record not found.</p>";
            echo "<a href='admin_page.php' class='btn btn-secondary'>Back to Admin Page</a>";
            exit();
        }
    } else {
        echo "<p>Invalid request.</p>";
        echo "<a href='admin_page.php' class='btn btn-secondary'>Back to Admin Page</a>";
        exit();
    }
}
?>
</body>
</html>