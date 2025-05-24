<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db"); // Update user/pass if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 1;

$sql = "SELECT parent_name, address, child_name, purpose, shipping_method, submitted_at 
        FROM baptismal_certification_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    // Load Word template
    $template = new TemplateProcessor('baptismal-template.docx');

    // Format submitted_at
    $dateIssuedFormatted = '';
    if (!empty($data['submitted_at'])) {
        $dateIssuedFormatted = date('F j, Y', strtotime($data['submitted_at']));
    }

    // Replace placeholders
    $template->setValue('parent_name', htmlspecialchars($data['parent_name']));
    $template->setValue('address', htmlspecialchars($data['address']));
    $template->setValue('child_name', htmlspecialchars($data['child_name']));
    $template->setValue('purpose', htmlspecialchars($data['purpose']));
    $template->setValue('shipping_method', htmlspecialchars($data['shipping_method']));
    $template->setValue('date_issued', htmlspecialchars($dateIssuedFormatted)); // formatted nicely

    // Save and download the file
    $filename = 'Baptismal_Certification_' . $id . '.docx';
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
