<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db"); // Update user/pass if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get clearance record by ID
$id = $_GET['id'] ?? 1;

$sql = "SELECT first_name, middle_name, last_name, complete_address, age, civil_status, 
        student_patient_name, purpose, submitted_at 
        FROM barangay_clearance WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    // Load Word template
    $template = new TemplateProcessor('clearance-template.docx');

    // Combine full name
    $fullName = $data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name'];

    // Replace placeholders
    $template->setValue('name', htmlspecialchars($fullName));
    $template->setValue('age', htmlspecialchars($data['age']));
    $template->setValue('civil_status', htmlspecialchars($data['civil_status']));
    $template->setValue('Spouse', htmlspecialchars($data['student_patient_name']));
    $template->setValue('address', htmlspecialchars($data['complete_address']));
    $template->setValue('purpose', htmlspecialchars($data['purpose']));
    $template->setValue('date_issued', date('F j, Y', strtotime($data['submitted_at']))); // formatted nicely

    // Save and download the file
    $filename = 'Barangay_Clearance_' . $id . '.docx';
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
