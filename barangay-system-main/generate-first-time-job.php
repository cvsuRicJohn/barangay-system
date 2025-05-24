<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db"); // Adjust credentials if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 1;

$sql = "SELECT full_name, address, residency_length, oath_acknowledged, shipping_method, submitted_at 
        FROM first_time_job_seeker_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    // Load Word template
    $template = new TemplateProcessor('first-time-job-template.docx');

    // Format date
    $dateIssuedFormatted = date('F j, Y', strtotime($data['submitted_at']));

    // Replace placeholders
    $template->setValue('full_name', htmlspecialchars($data['full_name']));
    $template->setValue('address', htmlspecialchars($data['address']));
    $template->setValue('residency_length', htmlspecialchars($data['residency_length']));
    $template->setValue('date_issued', htmlspecialchars($dateIssuedFormatted));

    // Save and download the file
    $filename = 'First_Time_Job_Seeker_Certificate_' . $id . '.docx';
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
