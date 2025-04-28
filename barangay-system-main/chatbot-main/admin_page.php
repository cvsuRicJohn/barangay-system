<?php
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header("Location: admin_login.php");
    exit();
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
        'users' => 'users'
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

// Fetch all users for user management
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
    $users = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching users: " . $e->getMessage());
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
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      padding: 20px;
      background-color: #f0f4f8;
      color: #333;
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
      color: #2c3e50;
      font-weight: 700;
      font-size: 2.5rem;
    }
    .logout-link {
      display: block;
      text-align: right;
      margin-bottom: 20px;
    }
    .logout-link a {
      color: #e74c3c;
      font-weight: 600;
      text-decoration: none;
      font-size: 1rem;
      transition: color 0.3s ease;
    }
    .logout-link a:hover {
      color: #c0392b;
      text-decoration: underline;
    }
    .nav-tabs {
      margin-bottom: 20px;
      border-bottom: 2px solid #3498db;
    }
    .nav-link {
      color: #3498db;
      font-weight: 600;
      font-size: 1rem;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
    .nav-link.active {
      background-color: #3498db;
      color: white !important;
      border-radius: 5px 5px 0 0;
    }
    table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0 10px;
      background: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border-radius: 8px;
      overflow: hidden;
    }
    th, td {
      padding: 15px 20px;
      border-bottom: none;
      text-align: left;
      vertical-align: middle;
    }
    th {
      background-color: #2980b9;
      color: white;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }
    tbody tr {
      background-color: #ffffff;
      transition: background-color 0.3s ease;
      border-radius: 8px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    tbody tr:hover {
      background-color: #ecf6fc;
      box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-primary {
      background-color: #3498db;
      border: none;
      font-weight: 600;
      padding: 8px 16px;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }
    .btn-primary:hover {
      background-color: #2980b9;
    }
    .btn-warning {
      background-color: #f39c12;
      border: none;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 5px;
      color: white;
      transition: background-color 0.3s ease;
    }
    .btn-warning:hover {
      background-color: #d68910;
      color: white;
    }
    .btn-danger {
      background-color: #e74c3c;
      border: none;
      font-weight: 600;
      padding: 6px 12px;
      border-radius: 5px;
      color: white;
      transition: background-color 0.3s ease;
    }
    .btn-danger:hover {
      background-color: #c0392b;
      color: white;
    }
    .btn-secondary {
      background-color: #95a5a6;
      border: none;
      font-weight: 600;
      padding: 8px 16px;
      border-radius: 5px;
      color: white;
      transition: background-color 0.3s ease;
    }
    .btn-secondary:hover {
      background-color: #7f8c8d;
      color: white;
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
<body>
  <div class="logout-link">
    <a href="admin_page.php?action=logout" onclick="return confirm('Are you sure you want to log out?');">Logout</a>
  </div>

  <h1>Admin Panel - Service Management</h1>

  <div class="container-fluid mt-4">
    <div class="row">
      <!-- Sidebar -->
      <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
        <div class="sidebar-sticky pt-3">
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
          </ul>
        </div>
      </nav>

      <!-- Main content -->
      <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
        <div id="dashboard" class="content-section">
          <h2>Dashboard Overview</h2>
          <div class="row">
            <div class="col-md-4 mb-4">
              <div class="card shadow-lg text-white bg-primary h-100 dashboard-card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                  <i class="fas fa-id-card fa-3x mb-3"></i>
                  <h5 class="card-title">Barangay ID Requests</h5>
                  <p class="card-text display-4"><?php echo count($requests); ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card shadow-lg text-white bg-success h-100 dashboard-card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                  <i class="fas fa-file-alt fa-3x mb-3"></i>
                  <h5 class="card-title">Barangay Clearance Requests</h5>
                  <p class="card-text display-4"><?php echo count($clearance_requests); ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card shadow-lg text-white bg-warning h-100 dashboard-card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                  <i class="fas fa-certificate fa-3x mb-3"></i>
                  <h5 class="card-title">Certificate of Indigency Requests</h5>
                  <p class="card-text display-4"><?php echo count($indigency_requests); ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card shadow-lg text-white bg-info h-100 dashboard-card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                  <i class="fas fa-home fa-3x mb-3"></i>
                  <h5 class="card-title">Certificate of Residency Requests</h5>
                  <p class="card-text display-4"><?php echo count($residency_requests); ?></p>
                </div>
              </div>
            </div>
            <div class="col-md-4 mb-4">
              <div class="card shadow-lg text-white bg-secondary h-100 dashboard-card">
                <div class="card-body d-flex flex-column justify-content-center align-items-center">
                  <i class="fas fa-users fa-3x mb-3"></i>
                  <h5 class="card-title">Users</h5>
                  <p class="card-text display-4"><?php echo count($users); ?></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
        <style>
          .dashboard-card {
            border-radius: 15px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
          }
          .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.3);
          }
          .dashboard-card i {
            color: rgba(255, 255, 255, 0.85);
          }
        </style>

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
                      <td><?php echo htmlspecialchars($req['date_of_birth']); ?></td>
                      <td><?php echo htmlspecialchars($req['gov_id']); ?></td>
                      <td><?php echo htmlspecialchars($req['email']); ?></td>
                      <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
                      <td>
                        <a href="edit.php?entity=barangay_id_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="admin_page.php?view=barangay_id_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary">View</a>
                        <button onclick="confirmDelete('barangay_id_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger">Delete</button>
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
                    <th>Student/Patient Name</th>
                    <th>Student/Patient Address</th>
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
                      <td><?php echo htmlspecialchars($req['birth_date']); ?></td>
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
                        <a href="edit.php?entity=barangay_clearance&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="admin_page.php?view=barangay_clearance&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary">View</a>
                        <button onclick="confirmDelete('barangay_clearance', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger">Delete</button>
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
                      <td><?php echo htmlspecialchars($req['date_of_birth']); ?></td>
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
                        <a href="edit.php?entity=certificate_of_indigency_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="admin_page.php?view=certificate_of_indigency_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary">View</a>
                        <button onclick="confirmDelete('certificate_of_indigency_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger">Delete</button>
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
                      <td><?php echo htmlspecialchars($req['date_of_birth']); ?></td>
                      <td><?php echo htmlspecialchars($req['gov_id']); ?></td>
                      <td><?php echo htmlspecialchars($req['complete_address']); ?></td>
                      <td><?php echo htmlspecialchars($req['proof_of_residency']); ?></td>
                      <td><?php echo htmlspecialchars($req['purpose']); ?></td>
                      <td><?php echo htmlspecialchars($req['email']); ?></td>
                      <td><?php echo htmlspecialchars($req['shipping_method']); ?></td>
                      <td>
                        <a href="edit.php?entity=certificate_of_residency_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="admin_page.php?view=certificate_of_residency_requests&id=<?php echo $req['id']; ?>" class="btn btn-sm btn-primary">View</a>
                        <button onclick="confirmDelete('certificate_of_residency_requests', <?php echo $req['id']; ?>)" class="btn btn-sm btn-danger">Delete</button>
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
                      <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                      <td>
                        <a href="edit.php?entity=users&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="admin_page.php?view=users&id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">View</a>
                        <button onclick="confirmDelete('users', <?php echo $user['id']; ?>)" class="btn btn-sm btn-danger">Delete</button>
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
        $('.content-section').hide();
        $('#' + target).show();
        $('.nav-link').removeClass('active');
        $('.nav-link[data-target="' + target + '"]').addClass('active');
      }

      // Handle sidebar navigation clicks
      $('.nav-link').click(function(e) {
        e.preventDefault();
        var target = $(this).data('target');
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
    });
  </script>
<?php
if (isset($_GET['view']) && isset($_GET['id'])) {
    $entity = $_GET['view'];
    $id = $_GET['id'];

    $valid_entities = [
        'barangay_id_requests',
        'barangay_clearance',
        'certificate_of_indigency_requests',
        'certificate_of_residency_requests',
        'users'
    ];

    if (in_array($entity, $valid_entities) && is_numeric($id)) {
        $stmt = $pdo->prepare("SELECT * FROM $entity WHERE id = ?");
        $stmt->execute([$id]);
        $record = $stmt->fetch();

        if ($record) {
            echo "<div style='padding: 30px; background: #fff; border-radius: 10px; margin: 20px auto; max-width: 700px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-family: Georgia, serif;'>";
            echo "<h2 style='text-align: center; margin-bottom: 30px; font-weight: 700; font-size: 2rem; color: #2c3e50;'>View Details - " . htmlspecialchars($entity) . "</h2>";
            echo "<div style='display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; background-color: #fafafa; padding: 20px; border-radius: 8px; border: 1px solid #ccc;'>";
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
                echo "<div style='flex: 1 1 45%; min-width: 300px;'>";
                echo "<label style='display: block; font-weight: 700; font-size: 1.1rem; color: #34495e; margin-bottom: 6px;'>" . $label . "</label>";
                if ($isImage) {
                    // Display image with max width and height
                    $imgSrc = htmlspecialchars($value);
                    echo "<img src='$imgSrc' alt='$label' style='max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #ddd; object-fit: contain;'>";
                } else {
                    echo "<div style='padding: 10px 15px; background-color: #fff; border: 1px solid #ddd; border-radius: 5px; font-size: 1rem; color: #555; min-height: 30px;'>" . $val . "</div>";
                }
                echo "</div>";
            }
            echo "</div>";
            echo "<div style='text-align: center; margin-top: 30px;'>";
            echo "<a href='admin_page.php?tab=$entity' class='btn btn-secondary mr-3' style='padding: 10px 20px; font-size: 1rem;'>Back</a>";
            echo "<a href='edit.php?entity=$entity&id=$id' class='btn btn-primary' style='padding: 10px 20px; font-size: 1rem;'>Edit</a>";
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
