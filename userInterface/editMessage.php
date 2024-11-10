<?php
session_start();
$loggedInUsername = $_SESSION['username'];
$messageId = $_POST['message_id'];
$newMessageText = $_POST['new_message'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update the message text and mark it as edited
$editQuery = $conn->prepare("UPDATE Chat_Messages SET Message_Text = ?, Is_Edited = TRUE WHERE Message_Id = ?");
$editQuery->bind_param("si", $newMessageText, $messageId);
$editQuery->execute();

echo json_encode(["success" => true, "message" => "Message edited successfully"]);
$editQuery->close();
$conn->close();
?>
