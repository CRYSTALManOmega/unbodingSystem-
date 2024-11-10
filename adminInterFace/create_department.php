<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['departmentName'];
    $description = $_POST['description'] ?? null;
    $location = $_POST['location'] ?? null;

    // Check if department with the same name already exists
    $stmt = $conn->prepare("SELECT Department_Id FROM Department WHERE Name = ?");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        echo "Department with this name already exists.";
    } else {
        // Insert new department
        $stmt = $conn->prepare("INSERT INTO Department (Name, Description, Location) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $description, $location);
        if ($stmt->execute()) {
            echo "Department created successfully.";
        } else {
            echo "Error creating department.";
        }
    }
    $stmt->close();
}
$conn->close();
?>
