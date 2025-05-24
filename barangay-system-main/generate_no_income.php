<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db"); // Adjust credentials if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 1;

$sql = "SELECT full_name, date_of_birth, civil_status, address, no_income_statement, purpose, shipping_method, submitted_at 
        FROM no_income_certification_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    // Load Word template
    $template = new TemplateProcessor('no-income-template.docx');

    // Calculate age from date_of_birth
    $dob = new DateTime($data['date_of_birth']);
    $now = new DateTime();
    $age = $now->diff($dob)->y;

    // Replace placeholders
    $template->setValue('full_name', htmlspecialchars($data['full_name']));
    $template->setValue('date_of_birth', htmlspecialchars($data['date_of_birth']));
    $template->setValue('age', htmlspecialchars($age));
    $template->setValue('civil_status', htmlspecialchars($data['civil_status']));
    $template->setValue('address', htmlspecialchars($data['address']));
    $template->setValue('no_income_statement', htmlspecialchars($data['no_income_statement']));
    $template->setValue('purpose', htmlspecialchars($data['purpose']));
    $template->setValue('shipping_method', htmlspecialchars($data['shipping_method']));
    $template->setValue('date_issued', date('F j, Y', strtotime($data['submitted_at'])));

    // Save and download the file
    $filename = 'No_Income_Certification_' . $id . '.docx';
    $template->saveAs($filename);

    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=" . basename($filename));
    header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    header("Content-Length: " . filesize($filename));
    readfile($filename);
    unlink($filename);
    exit;
} else {
    echo "No record found for ID = $id";
}
?>

