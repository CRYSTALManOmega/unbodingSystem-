<?php
session_start();
$loggedInUsername = $_SESSION['username'];

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

// Fetch messages specific to the department
$messagesQuery = $conn->prepare("
    SELECT Chat_Messages.*, User.First_Name, User.Last_Name, User.Type 
    FROM Chat_Messages 
    JOIN User ON Chat_Messages.User_Id = User.User_Id 
    WHERE Chat_Messages.Department_Id = ? AND Chat_Messages.Is_Deleted = FALSE 
    ORDER BY Timestamp ASC");
$messagesQuery->bind_param("i", $departmentId);
$messagesQuery->execute();
$messagesResult = $messagesQuery->get_result();

$messages = [];
while ($row = $messagesResult->fetch_assoc()) {
    $messages[] = $row;
}
header('Content-Type: application/json');
echo json_encode(["success" => true, "messages" => $messages]);

$userQuery->close();
$messagesQuery->close();
$conn->close();
?>
