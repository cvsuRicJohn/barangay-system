<?php
require_once 'db_conn.php';

$pdo = null;
if (isset($GLOBALS['pdo'])) {
    $pdo = $GLOBALS['pdo'];
} else {
    // Try to create a new mysqli connection if not available globally
    $servername = "localhost";
    $username_db = "root"; // Adjust if needed
    $password_db = "";     // Adjust if needed
    $dbname = "barangay_db";

    $conn = new mysqli($servername, $username_db, $password_db, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Create a PDO instance from mysqli connection for compatibility
    $dsn = "mysql:host=$servername;dbname=$dbname;charset=utf8mb4";
    try {
        $pdo = new PDO($dsn, $username_db, $password_db, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing ID.");
}

$id = intval($_GET['id']);

try {
    $stmt = $pdo->prepare("SELECT * FROM late_birth_registration_requests WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$record) {
        die("Record not found.");
    }

    // Load PHPWord library
    require_once 'vendor/autoload.php';

    $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('late-registration-template.docx');

    // Map database fields to template placeholders
    $templateProcessor->setValue('last_name', htmlspecialchars($record['last_name']));
    $templateProcessor->setValue('first_name', htmlspecialchars($record['first_name']));
    $templateProcessor->setValue('middle_name', htmlspecialchars($record['middle_name']));
    $templateProcessor->setValue('address', htmlspecialchars($record['address']));
    $templateProcessor->setValue('marital_status', htmlspecialchars($record['marital_status']));
    $templateProcessor->setValue('place_of_birth', htmlspecialchars($record['place_of_birth']));
    $templateProcessor->setValue('date_of_birth', htmlspecialchars($record['date_of_birth']));
    $templateProcessor->setValue('age', htmlspecialchars($record['age']));
    $templateProcessor->setValue('fathers_name', htmlspecialchars($record['fathers_name']));
    $templateProcessor->setValue('mothers_name', htmlspecialchars($record['mothers_name']));
    $templateProcessor->setValue('years_in_barangay', htmlspecialchars($record['years_in_barangay']));
    $templateProcessor->setValue('purpose', htmlspecialchars($record['purpose']));

    // Generate file name
    $fileName = 'Late_Birth_Registration_' . $record['last_name'] . '_' . $record['first_name'] . '.docx';

    // Set headers for download
    header("Content-Description: File Transfer");
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');

    // Output the document
    $templateProcessor->saveAs('php://output');
    exit();

} catch (Exception $e) {
    die("Error generating document: " . $e->getMessage());
}
?>
