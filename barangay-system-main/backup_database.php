<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$host = "localhost";
$user = "root";
$password = "";
$database = "barangay_db";

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

$tables = [];
$result = $conn->query("SHOW TABLES");
while ($row = $result->fetch_row()) {
    $tables[] = $row[0];
}

if (empty($tables)) {
    die("⚠️ No tables found in the database.");
}

$sqlScript = "";
foreach ($tables as $table) {
    $res = $conn->query("SHOW CREATE TABLE `$table`");
    $row = $res->fetch_row();
    $sqlScript .= "\n\n" . $row[1] . ";\n\n";

    $res = $conn->query("SELECT * FROM `$table`");
    while ($data = $res->fetch_assoc()) {
        $values = array_map(function ($val) use ($conn) {
            return "'" . $conn->real_escape_string($val) . "'";
        }, array_values($data));
        $sqlScript .= "INSERT INTO `$table` VALUES (" . implode(", ", $values) . ");\n";
    }
}

// Save to folder
$folderPath = __DIR__ . '/backup_database/';
if (!file_exists($folderPath)) {
    mkdir($folderPath, 0777, true);
}

$backupFile = $folderPath . 'backup_' . date("Y-m-d-H-i-s") . '.sql';
file_put_contents($backupFile, $sqlScript);

// Download the file
header('Content-Type: application/sql');
header('Content-Disposition: attachment; filename="' . basename($backupFile) . '"');
header('Content-Length: ' . filesize($backupFile));
readfile($backupFile);
header("Location: admin_page.php?status=success");
exit;
