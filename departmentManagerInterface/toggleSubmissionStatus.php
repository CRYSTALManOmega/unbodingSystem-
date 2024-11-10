<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the submission ID and current status from the request
$submissionId = $_POST['submission_id'];
$currentStatus = $_POST['current_status'];
$newStatus = $currentStatus === 'complete' ? 'incomplete' : 'complete';

// Update the status of the submission
$query = "UPDATE User_Submitted_Module SET Status = ? WHERE Submission_Id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $newStatus, $submissionId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Submission status updated to $newStatus"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update submission status"]);
}

$stmt->close();
$conn->close();
?>
