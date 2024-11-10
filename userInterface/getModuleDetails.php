<?php
$moduleId = $_GET['moduleId'];
$conn = new mysqli("localhost", "root", "", "company_database");

$moduleStmt = $conn->prepare("SELECT Name AS title, Description AS description FROM Module WHERE Module_Id = ?");
$moduleStmt->bind_param("i", $moduleId);
$moduleStmt->execute();
$module = $moduleStmt->get_result()->fetch_assoc();

$questionsStmt = $conn->prepare("SELECT Question_Id AS id, Title FROM Question WHERE Module_Id = ?");
$questionsStmt->bind_param("i", $moduleId);
$questionsStmt->execute();
$questionsResult = $questionsStmt->get_result();

$questions = [];
while ($question = $questionsResult->fetch_assoc()) {
    $filesStmt = $conn->prepare("SELECT File_Name AS name, File_Path AS path FROM Question_Attachment WHERE Question_Id = ?");
    $filesStmt->bind_param("i", $question['id']);
    $filesStmt->execute();
    $filesResult = $filesStmt->get_result();

    $files = [];
    while ($file = $filesResult->fetch_assoc()) {
        $files[] = $file;
    }
    
    $questions[] = [
        "id" => $question['id'],
        "title" => $question['Title'],
        "files" => $files
    ];
}

echo json_encode(["title" => $module['title'], "description" => $module['description'], "questions" => $questions]);

$conn->close();
?>
