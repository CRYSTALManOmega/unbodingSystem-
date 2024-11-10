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

$departmentId = $_POST['departmentId'];
$name = $_POST['updateDepartmentName'];
$description = $_POST['updateDescription'] ?? null;
$location = $_POST['updateLocation'] ?? null;

// Check if the new name is already used by another department
$stmt = $conn->prepare("SELECT Department_Id FROM Department WHERE Name = ? AND Department_Id != ?");
$stmt->bind_param("si", $name, $departmentId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Another department with this name already exists.";
} else {
    // Update department
    $stmt = $conn->prepare("UPDATE Department SET Name = ?, Description = ?, Location = ? WHERE Department_Id = ?");
    $stmt->bind_param("sssi", $name, $description, $location, $departmentId);
    if ($stmt->execute()) {
        echo "Department updated successfully.";
    } else {
        echo "Error updating department.";
    }
}
$stmt->close();
$conn->close();
?>
