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
    $employeeInput = $_POST['employeeInput'];
    $departmentInput = $_POST['departmentInput'];

    // Prepare and execute statement to find the employee by National ID or name
    $stmt = $conn->prepare("SELECT User_Id, Department_Id FROM User WHERE National_Id = ? OR CONCAT(First_Name, ' ', Last_Name) = ?");
    $stmt->bind_param("ss", $employeeInput, $employeeInput);
    $stmt->execute();
    $employeeResult = $stmt->get_result();
    $employee = $employeeResult->fetch_assoc();
    $stmt->close();

    if (!$employee) {
        echo json_encode(["success" => false, "message" => "Employee not found."]);
        $conn->close();
        exit;
    }

    // Check if the employee is already assigned to a department
    if (!is_null($employee['Department_Id'])) {
        echo json_encode(["success" => false, "message" => "Employee is already assigned to a department."]);
        $conn->close();
        exit;
    }

    // Prepare and execute statement to find the department by name
    $stmt = $conn->prepare("SELECT Department_Id FROM Department WHERE Name = ?");
    $stmt->bind_param("s", $departmentInput);
    $stmt->execute();
    $departmentResult = $stmt->get_result();
    $department = $departmentResult->fetch_assoc();
    $stmt->close();

    if (!$department) {
        echo json_encode(["success" => false, "message" => "Department not found."]);
        $conn->close();
        exit;
    }

    // Update the employee's department
    $stmt = $conn->prepare("UPDATE User SET Department_Id = ? WHERE User_Id = ?");
    $stmt->bind_param("ii", $department['Department_Id'], $employee['User_Id']);
    
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Employee added to department successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add employee to department."]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
