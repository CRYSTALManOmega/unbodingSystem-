<?php
$conn = new mysqli("localhost", "root", "", "company_database");

$submissionId = $_POST['submission_id'];
$status = $_POST['status'];

$sql = "UPDATE User_Submitted_Modules SET Status = ? WHERE Submission_Id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $submissionId);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Submission status updated successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update submission status.']);
}

$stmt->close();
$conn->close();
?>
