<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db"); // Update user/pass if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 1;

$sql = "SELECT first_name, middle_name, last_name, date_of_birth, civil_status, occupation, monthly_income, proof_of_residency, gov_id, spouse_name, number_of_dependents, submitted_at, shipping_method 
        FROM certificate_of_indigency_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    // Load Word template
    $template = new TemplateProcessor('indigency-template.docx');

    // Combine full name
    $fullName = trim($data['first_name'] . ' ' . $data['middle_name'] . ' ' . $data['last_name']);

    // Replace placeholders
    $template->setValue('name', htmlspecialchars($fullName));
    $template->setValue('date_of_birth', htmlspecialchars($data['date_of_birth']));
    $template->setValue('civil_status', htmlspecialchars($data['civil_status']));
    $template->setValue('occupation', htmlspecialchars($data['occupation']));
    $template->setValue('monthly_income', htmlspecialchars($data['monthly_income']));
    $template->setValue('proof_of_residency', htmlspecialchars($data['proof_of_residency']));
    $template->setValue('gov_id', htmlspecialchars($data['gov_id']));
    $template->setValue('spouse_name', htmlspecialchars($data['spouse_name']));
    $template->setValue('number_of_dependents', htmlspecialchars($data['number_of_dependents']));
    $template->setValue('date_issued', date('F j, Y', strtotime($data['submitted_at']))); // formatted nicely
    $template->setValue('shipping_method', htmlspecialchars($data['shipping_method']));

    // Save and download the file
    $filename = 'Certificate_of_Indigency_' . $id . '.docx';
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
