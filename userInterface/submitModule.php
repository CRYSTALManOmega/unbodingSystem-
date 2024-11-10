<?php
session_start();
$data = json_decode(file_get_contents("php://input"), true);
$moduleId = $data['moduleId'];
$answers = $data['answers'];
$username = $_SESSION['username'];
$conn = new mysqli("localhost", "root", "", "company_database");

$userStmt = $conn->prepare("SELECT User_Id FROM User WHERE Username = ?");
$userStmt->bind_param("s", $username);
$userStmt->execute();
$userId = $userStmt->get_result()->fetch_assoc()['User_Id'];

foreach ($answers as $answer) {
    $answerStmt = $conn->prepare("INSERT INTO User_Answer (Module_Id, Question_Id, User_Id, Answer_Text) VALUES (?, ?, ?, ?)");
    $answerStmt->bind_param("iiis", $moduleId, $answer['questionId'], $userId, $answer['answer']);
    $answerStmt->execute();
}

$submissionStmt = $conn->prepare("INSERT INTO User_Submitted_Module (User_Id, Module_Id, Status, Submitted_On) VALUES (?, ?, 'submitted', NOW())");
$submissionStmt->bind_param("ii", $userId, $moduleId);
$submissionStmt->execute();

echo json_encode(["success" => true]);

$conn->close();
?>
