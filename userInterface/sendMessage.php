<?php
session_start();
$loggedInUsername = $_SESSION['username'];
$messageText = $_POST['message'];
$file = $_FILES['file'] ?? null;
$video = $_FILES['video'] ?? null;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve User and Department Information
$userQuery = $conn->prepare("SELECT User_Id, Department_Id FROM User WHERE Username = ?");
$userQuery->bind_param("s", $loggedInUsername);
$userQuery->execute();
$userResult = $userQuery->get_result()->fetch_assoc();
$userId = $userResult['User_Id'];
$departmentId = $userResult['Department_Id'];

$conn->begin_transaction();
try {
    // Insert message record
    $messageQuery = $conn->prepare("INSERT INTO Chat_Messages (User_Id, Department_Id, Message_Text) VALUES (?, ?, ?)");
    $messageQuery->bind_param("iis", $userId, $departmentId, $messageText);
    $messageQuery->execute();
    $messageId = $messageQuery->insert_id;

    // Upload file if exists
    if ($file && $file['error'] === UPLOAD_ERR_OK) {
        $filePath = 'uploads/files/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $filePath);
        
        $fileQuery = $conn->prepare("INSERT INTO Chat_Files (Message_Id, File_Name, File_Path, File_Type) VALUES (?, ?, ?, 'file')");
        $fileQuery->bind_param("iss", $messageId, $file['name'], $filePath);
        $fileQuery->execute();
    }

    // Upload video if exists
    if ($video && $video['error'] === UPLOAD_ERR_OK) {
        $videoPath = 'uploads/videos/' . basename($video['name']);
        move_uploaded_file($video['tmp_name'], $videoPath);
        
        $videoQuery = $conn->prepare("INSERT INTO Chat_Files (Message_Id, File_Name, File_Path, File_Type) VALUES (?, ?, ?, 'video')");
        $videoQuery->bind_param("iss", $messageId, $video['name'], $videoPath);
        $videoQuery->execute();
    }

    $conn->commit();
    echo json_encode(["success" => true, "message" => "Message sent successfully"]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

$messageQuery->close();
$conn->close();
?>
