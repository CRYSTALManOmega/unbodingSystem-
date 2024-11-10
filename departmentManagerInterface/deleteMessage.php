<?php
session_start();
$loggedInUsername = $_SESSION['username'];
$messageId = $_POST['message_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mark message as deleted and clear content
$deleteQuery = $conn->prepare("UPDATE Chat_Messages SET Is_Deleted = TRUE, Message_Text = NULL WHERE Message_Id = ?");
$deleteQuery->bind_param("i", $messageId);
$deleteQuery->execute();

echo json_encode(["success" => true, "message" => "Message deleted successfully"]);
$deleteQuery->close();
$conn->close();
?>
