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
    } elseif ($action === 'update_status') {
        $entity = $_GET['entity'] ?? '';
        $id = $_GET['id'] ?? '';
        $status = $_GET['status'] ?? '';

        if ($entity !== 'certificate_of_indigency_requests' && $entity !== 'users') {
            die("Invalid entity for status update.");
        }
        if (!is_numeric($id)) {
            die("Invalid ID for status update.");
        }
        if (!in_array($status, ['approved', 'rejected'])) {
            die("Invalid status value.");
        }

        try {
            if ($entity === 'certificate_of_indigency_requests') {
                $stmt = $pdo->prepare("UPDATE certificate_of_indigency_requests SET status = :status WHERE id = :id");
            } elseif ($entity === 'users') {
                $stmt = $pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
            }
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: admin_page.php?tab=$entity");
            exit();
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
    'unemployment_certification_requests' => fetchAll($pdo, 'unemployment_certification_requests')
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

<!-- Bootstrap 4 and FontAwesome for styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
<style>
  html, body {
    height: 100%;
  }
  body {
    overflow: hidden;
    background: #f1f5f9;
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  }
  #app {
    display: flex;
    height: 100vh;
  }
  #sidebar {
    width: 250px;
    background: linear-gradient(180deg, #4a90e2, #357abd);
    color: white;
    display: flex;
    flex-direction: column;
    padding-top: 1.5rem;
    user-select: none;
    overflow-y: auto;
  }
  #sidebar h2 {
    text-align: center;
    margin-bottom: 2rem;
    font-weight: 700;
    font-size: 1.6rem;
    letter-spacing: 1px;
    text-shadow: 0 1px 2px rgba(0,0,0,0.3);
  }
  #sidebar nav {
    flex-grow: 1;
  }
  #sidebar nav a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
    cursor: pointer;
    color: white;
    text-decoration: none;
    border-left: 4px solid transparent;
    transition: background-color 0.3s ease, border-color 0.3s ease;
  }
  #sidebar nav a.active, #sidebar nav a:hover {
    background-color: rgba(255,255,255,0.15);
    border-left-color: #ffca28;
  }
  #top-right-status {
    position: fixed;
    top: 10px;
    right: 10px;
    background-color: #4a90e2;
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
    font-weight: 600;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    z-index: 1000;
  }
  #sidebar nav a .badge {
    min-width: 24px;
    font-size: 0.75rem;
    padding: 0.25em 0.5em;
  }
  #logout-btn {
    margin: 1rem 1.5rem;
    background: #e3342f;
    border: none;
    padding: 0.6rem 1rem;
    color: white;
    font-weight: 600;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }
  #logout-btn:hover {
    background: #b1221c;
  }
  #main-content {
    flex-grow: 1;
    padding: 1.5rem 2rem;
    overflow-y: auto;
    background: white;
  }
  h3.page-title {
    font-weight: 700;
    margin-bottom: 1rem;
    color: #2c3e50;
  }
  /* Table styling */
  .entity-table {
    max-width: 100%;
    border-collapse: collapse;
    width: 100%;
  }
  .entity-table th,
  .entity-table td {
    padding: 0.65rem 1rem;
    border: 1px solid #d1d5db;
    vertical-align: middle;
    font-size: 0.9rem;
  }
  .entity-table thead th {
    background-color: #4a90e2;
    color: white;
    font-weight: 600;
  }
  .action-btns button, .action-btns a {
    margin-right: 0.35rem;
  }
  .action-btns a, .action-btns button {
    font-size: 0.85rem;
  }
  /* Scrollable table */
  .table-wrapper {
    max-height: 500px;
    overflow-y: auto;
    margin-bottom: 2rem;
    border: 1px solid #cbd5e1;
    border-radius: 0.5rem;
  }
  /* Tab contents */
  .tab-content-section {
    display: none;
  }
  .tab-content-section.active {
    display: block;
  }
  /* Responsive adjustments */
  @media(max-width: 768px) {
    #sidebar {
      width: 60px;
      padding-top: 1rem;
    }
    #sidebar h2 {
      display: none;
    }
    #sidebar nav a {
      justify-content: center;
      padding: 0.5rem;
      border-left: none;
      border-bottom: 4px solid transparent;
      flex-direction: column;
      font-size: 0;
    }
    #sidebar nav a span.badge {
      margin-top: 3px;
    }
    #sidebar nav a i {
      font-size: 1.3rem;
      color: white;
    }
    #main-content {
      padding: 1rem;
    }
  }
</style>

<script>
function confirmDelete(entity, id) {
    if (confirm('Are you sure you want to delete this ' + entity.replace(/_/g, ' ') + ' with ID ' + id + '?')) {
        window.location.href = 'admin_page.php?action=delete&entity=' + entity + '&id=' + id;
    }
}
// Tab navigation
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('#sidebar nav a');
    const contents = document.querySelectorAll('.tab-content-section');

    function setActiveTab(tabName) {
        // Show content section
        for (const content of contents) {
            content.classList.toggle('active', content.id === tabName);
        }
        // Highlight nav
        for (const tab of tabs) {
            tab.classList.toggle('active', tab.dataset.target === tabName);
        }
        // Update URL query param without reload
        if (history.pushState) {
          const newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabName;
          window.history.replaceState({path:newurl}, '', newurl);
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', e => {
            e.preventDefault();
            setActiveTab(tab.dataset.target);
        });
    });

    // Set initial active tab from PHP var or default dashboard
    setActiveTab(<?= json_encode($currentTab) ?>);
});
</script>
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
    </nav>
    <button id="logout-btn" onclick="if(confirm('Are you sure you want to logout?')) window.location='?action=logout';" aria-label="Logout from admin panel">Logout <i class="fas fa-sign-out-alt"></i></button>
  </aside>

  <main id="main-content" role="main" tabindex="0">
    <!-- Removed user approval form as per request -->

    <section id="dashboard" class="tab-content-section active" aria-labelledby="dashboard-header">
      <h3 id="dashboard-header" class="page-title">Dashboard Overview</h3>
      <div class="table-wrapper">
        <table class="entity-table" aria-describedby="dashboard-summary-desc">
          <caption id="dashboard-summary-desc">This table shows totals for each certification request and entities</caption>
          <thead>
            <tr>
              <th>Certification / Entity</th>
              <th>Total Requests / Items</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($counts as $entity => $total): ?>
              <tr>
                <td><?= e(str_replace('_', ' ', ucwords($entity))) ?></td>
                <td><?= e($total) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <h3 class="page-title">Recent Requests Overview</h3>
      <div class="table-wrapper">
        <table class="entity-table" aria-describedby="recent-requests-desc">
          <caption id="recent-requests-desc">This table shows the most recent requests from all entities</caption>
          <thead>
            <tr>
              <th>Entity</th>
              <th>ID</th>
              <th>Name</th>
              <th>Date Submitted</th>
            </tr>
          </thead>
          <tbody>
            <?php
            // Collect recent requests from all entities
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
            // Sort by date descending
            usort($recentRequests, function($a, $b) {
                return strtotime($b['date']) <=> strtotime($a['date']);
            });
            // Limit to 10 most recent
            $recentRequests = array_slice($recentRequests, 0, 10);
            foreach ($recentRequests as $request):
            ?>
            <tr>
              <td><?= e(str_replace('_', ' ', ucwords($request['entity']))) ?></td>
              <td><?= e($request['id']) ?></td>
              <td><?= e($request['name']) ?></td>
              <td><?= e($request['date']) ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
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
            'barangay_id_requests' // Added barangay_id_requests to remove email column
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
                'status' => 'Status',
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
                'date_of_birth' => 'Date of Birth',
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
                'gov_id' => 'Gov ID',
                'complete_address' => 'Complete Address',
                'proof_of_residency' => 'Proof of Residency',
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
        echo '<a href="edit.php?entity='.$entityEsc.'&id='.$idEsc.'" class="btn btn-sm btn-warning" title="Edit"><i class="fas fa-edit"></i></a> ';
        echo '<a href="admin_page.php?view='.$entityEsc.'&id='.$idEsc.'" class="btn btn-sm btn-primary" title="View"><i class="fas fa-eye"></i></a> ';
if (($entityKey === 'certificate_of_indigency_requests' || $entityKey === 'users') && isset($row['status']) && $row['status'] === 'pending') {
    echo '<a href="admin_page.php?action=update_status&entity='.$entityEsc.'&id='.$idEsc.'&status=approved" class="btn btn-sm btn-success" title="Approve" onclick="return confirm(\'Are you sure you want to approve this request?\');"><i class="fas fa-check"></i></a> ';
    echo '<a href="admin_page.php?action=update_status&entity='.$entityEsc.'&id='.$idEsc.'&status=rejected" class="btn btn-sm btn-danger" title="Reject" onclick="return confirm(\'Are you sure you want to reject this request?\');"><i class="fas fa-times"></i></a> ';
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
            echo '<a class="btn btn-primary mb-3" href="create.php?entity='.$entityEsc.'">Add New '.e(str_replace('_', ' ', ucwords($entityKey))).'</a>';
        }

        echo '</section>';
    }

    foreach ($entities as $entityKey => $items) {
        renderEntityTable($entityKey, $items);
    }
    ?>

  </main>
</div>

<!-- Bootstrap JS bundle -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
