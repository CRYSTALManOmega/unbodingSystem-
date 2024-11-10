<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$moduleId = $_POST['module_id'];
$currentStatus = $_POST['current_status'];
$newStatus = $currentStatus === 'active' ? 'inactive' : 'active';

$updateSql = "UPDATE Module SET Module_Status = ? WHERE Module_Id = ?";
$stmt = $conn->prepare($updateSql);
$stmt->bind_param("si", $newStatus, $moduleId);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Module status updated successfully"]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to update module status"]);
}

$stmt->close();
$conn->close();
?>
