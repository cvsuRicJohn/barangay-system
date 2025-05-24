<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db"); // Adjust credentials if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 1;

$sql = "SELECT partner1_name, partner2_name, shared_address, cohabitation_duration, purpose, shipping_method, submitted_at 
        FROM cohabitation_certification_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    // Load Word template
    $template = new TemplateProcessor('cohabitation-template.docx');

    // Replace placeholders
    $template->setValue('partner1_name', htmlspecialchars($data['partner1_name']));
    $template->setValue('partner2_name', htmlspecialchars($data['partner2_name']));
    $template->setValue('shared_address', htmlspecialchars($data['shared_address']));
    $template->setValue('cohabitation_duration', htmlspecialchars($data['cohabitation_duration']));
    $template->setValue('purpose', htmlspecialchars($data['purpose']));
    $template->setValue('shipping_method', htmlspecialchars($data['shipping_method']));
    $template->setValue('date_issued', date('F j, Y', strtotime($data['submitted_at'])));

    // Save and download the file
    $filename = 'Cohabitation_Certification_' . $id . '.docx';
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
