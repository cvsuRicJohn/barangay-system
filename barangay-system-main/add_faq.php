<?php
include 'db_conn.php';

$question = $_POST['question'];
$answer = $_POST['answer'];
$column = $_POST['column_side'];
$position = $_POST['position'];

$stmt = $conn->prepare("INSERT INTO faqs (question, answer, column_side, position) VALUES (?, ?, ?, ?)");
$stmt->bind_param("sssi", $question, $answer, $column, $position);
$stmt->execute();
$stmt->close();

header("Location: admin_page.php");
exit();
