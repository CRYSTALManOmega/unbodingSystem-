<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$departmentName = $data['departmentName'];

// Check if department exists
$stmt = $conn->prepare("SELECT Department_Id FROM Department WHERE Name = ?");
$stmt->bind_param("s", $departmentName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "Department not found.";
} else {
    // Delete department
    $stmt = $conn->prepare("DELETE FROM Department WHERE Name = ?");
    $stmt->bind_param("s", $departmentName);
    if ($stmt->execute()) {
        echo "Department deleted successfully.";
    } else {
        echo "Error deleting department.";
    }
}
$stmt->close();
$conn->close();
?>
