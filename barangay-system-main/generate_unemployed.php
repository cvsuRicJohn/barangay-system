<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db"); // Update user/pass if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 1;

$sql = "SELECT full_name, age, birth_date, civil_status, address, purpose, shipping_method, submitted_at 
        FROM unemployment_certification_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    // Load Word template
    $template = new TemplateProcessor('Unemployed-template.docx');
    // Use full name directly
    $fullName = trim($data['full_name']);

    // Format birth_date and submitted_at
    $birthDateFormatted = '';
    if (!empty($data['birth_date'])) {
        $birthDateFormatted = date('F j, Y', strtotime($data['birth_date']));
    }
    $dateIssuedFormatted = '';
    if (!empty($data['submitted_at'])) {
        $dateIssuedFormatted = date('F j, Y', strtotime($data['submitted_at']));
    }

    // Replace placeholders
    $template->setValue('name', htmlspecialchars($data['full_name']));
    $template->setValue('age', htmlspecialchars($data['age']));
    $template->setValue('birth_date', htmlspecialchars($birthDateFormatted));
    $template->setValue('civil_status', htmlspecialchars($data['civil_status']));
    $template->setValue('address', htmlspecialchars($data['address']));
    $template->setValue('purpose', htmlspecialchars($data['purpose']));
    $template->setValue('shipping_method', htmlspecialchars($data['shipping_method']));
    $template->setValue('date_issued', htmlspecialchars($dateIssuedFormatted)); // formatted nicely

    // Save and download the file
    $filename = 'Unemployment_Certification_' . $id . '.docx';
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
