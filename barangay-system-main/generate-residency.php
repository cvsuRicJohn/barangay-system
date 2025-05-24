<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db"); // Update user/pass if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 1;

$sql = "SELECT first_name, middle_name, last_name, date_of_birth, age, civil_status, complete_address, purpose, shipping_method, submitted_at 
        FROM certificate_of_residency_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    // Load Word template
    $template = new TemplateProcessor('residency-template.docx');

    // Combine full name
    $fullName = trim($data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name']);

    // Replace placeholders
    $template->setValue('name', htmlspecialchars($fullName));
    $template->setValue('date_of_birth', htmlspecialchars($data['date_of_birth']));
    $template->setValue('age', htmlspecialchars($data['age'] ?? ''));
    $template->setValue('civil_status', htmlspecialchars($data['civil_status'] ?? ''));
    $template->setValue('complete_address', htmlspecialchars($data['complete_address']));
    $template->setValue('purpose', htmlspecialchars($data['purpose']));
    $template->setValue('shipping_method', htmlspecialchars($data['shipping_method']));
    $template->setValue('date_issued', date('F j, Y', strtotime($data['submitted_at']))); // formatted nicely

    // Save and download the file
    $filename = 'Certificate_of_Residency_' . $id . '.docx';
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
