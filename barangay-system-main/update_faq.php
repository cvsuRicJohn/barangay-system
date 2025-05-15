<?php
include 'db_conn.php';

$ids = $_POST['faq_id'];
$questions = $_POST['question'];
$answers = $_POST['answer'];
$columns = $_POST['column_side'];
$positions = $_POST['position'];

for ($i = 0; $i < count($ids); $i++) {
    $id = $ids[$i];
    $question = $questions[$i];
    $answer = $answers[$i];
    $column = $columns[$i];
    $position = $positions[$i];

    $stmt = $conn->prepare("UPDATE faqs SET question=?, answer=?, column_side=?, position=? WHERE id=?");
    $stmt->bind_param("sssii", $question, $answer, $column, $position, $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: admin_page.php");
exit();
