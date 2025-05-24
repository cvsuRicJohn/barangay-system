<?php
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

// Connect to database
$conn = new mysqli("localhost", "root", "", "barangay_db"); // Adjust credentials if needed

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'] ?? 1;

$sql = "SELECT full_name, address, solo_since, child_count, children_names, shipping_method, submitted_at 
        FROM solo_parent_requests WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if ($data) {
    // Load Word template
    $template = new TemplateProcessor('solo-parent-template.docx');

    // Replace placeholders
    $template->setValue('full_name', htmlspecialchars($data['full_name']));
    $template->setValue('address', htmlspecialchars($data['address']));
    $template->setValue('solo_since', htmlspecialchars($data['solo_since']));
    $template->setValue('child_count', htmlspecialchars($data['child_count']));
    $template->setValue('children_names', nl2br(htmlspecialchars($data['children_names'])));
    $template->setValue('shipping_method', htmlspecialchars($data['shipping_method']));
    $template->setValue('date_issued', date('F j, Y', strtotime($data['submitted_at'])));

    // Save and download the file
    $filename = 'Solo_Parent_Certification_' . $id . '.docx';
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
