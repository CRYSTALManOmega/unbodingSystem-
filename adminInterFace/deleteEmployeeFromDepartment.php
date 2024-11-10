<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $employeeInput = $_POST['employeeInput'] ?? '';
    $departmentInput = $_POST['departmentInput'] ?? '';

    // Ensure required fields are provided
    if (empty($employeeInput) || empty($departmentInput)) {
        echo json_encode(["success" => false, "message" => "Both Employee and Department inputs are required."]);
        $conn->close();
        exit;
    }

    // Find the employee by National ID or full name
    $stmt = $conn->prepare("SELECT User_Id, Department_Id FROM User WHERE National_Id = ? OR CONCAT(First_Name, ' ', Last_Name) = ?");
    $stmt->bind_param("ss", $employeeInput, $employeeInput);
    $stmt->execute();
    $employee = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$employee) {
        echo json_encode(["success" => false, "message" => "Employee not found."]);
        $conn->close();
        exit;
    }

    // Find the department by name
    $stmt = $conn->prepare("SELECT Department_Id FROM Department WHERE Name = ?");
    $stmt->bind_param("s", $departmentInput);
    $stmt->execute();
    $department = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$department) {
        echo json_encode(["success" => false, "message" => "Department not found."]);
        $conn->close();
        exit;
    }

    // Check if the employee is in the specified department
    if ($employee['Department_Id'] != $department['Department_Id']) {
        echo json_encode(["success" => false, "message" => "Employee is not assigned to this department."]);
        $conn->close();
        exit;
    }

    // Remove the employee from the department by setting Department_Id to NULL
    $stmt = $conn->prepare("UPDATE User SET Department_Id = NULL WHERE User_Id = ?");
    $stmt->bind_param("i", $employee['User_Id']);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Employee removed from department successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to remove employee from department."]);
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
