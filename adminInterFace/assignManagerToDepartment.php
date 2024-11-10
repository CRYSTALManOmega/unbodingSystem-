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

    // Find the employee by National ID or name
    $stmt = $conn->prepare("SELECT User_Id, Department_Id, Type FROM User WHERE National_Id = ? OR CONCAT(First_Name, ' ', Last_Name) = ?");
    $stmt->bind_param("ss", $employeeInput, $employeeInput);
    $stmt->execute();
    $employee = $stmt->get_result()->fetch_assoc();

    if (!$employee) {
        echo json_encode(["success" => false, "message" => "Employee not found."]);
        exit;
    }

    // Check if the employee is already assigned to a department or is a manager
    if ($employee['Department_Id'] && $employee['Department_Id'] != $departmentInput) {
        echo json_encode(["success" => false, "message" => "Employee is already assigned to a different department."]);
        exit;
    } elseif ($employee['Type'] === 'Manager') {
        echo json_encode(["success" => false, "message" => "Employee is already a manager."]);
        exit;
    }

    // Find the department by name and check if it already has a manager
    $stmt = $conn->prepare("SELECT Department_Id, Manager_Id FROM Department WHERE Name = ?");
    $stmt->bind_param("s", $departmentInput);
    $stmt->execute();
    $department = $stmt->get_result()->fetch_assoc();

    if (!$department) {
        echo json_encode(["success" => false, "message" => "Department not found."]);
        exit;
    } elseif ($department['Manager_Id']) {
        echo json_encode(["success" => false, "message" => "This department already has a manager."]);
        exit;
    }

    // Begin transaction to ensure atomicity
    $conn->begin_transaction();

    try {
        // Assign employee as manager in the User table
        $stmt = $conn->prepare("UPDATE User SET Department_Id = ?, Type = 'Manager' WHERE User_Id = ?");
        $stmt->bind_param("ii", $department['Department_Id'], $employee['User_Id']);
        $stmt->execute();

        // Set the manager in the Department table
        $stmt = $conn->prepare("UPDATE Department SET Manager_Id = ? WHERE Department_Id = ?");
        $stmt->bind_param("ii", $employee['User_Id'], $department['Department_Id']);
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        echo json_encode(["success" => true, "message" => "Employee assigned as manager to the department successfully."]);
    } catch (Exception $e) {
        // Rollback transaction in case of an error
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Failed to assign employee as manager."]);
    }
}

// Close the connection
$conn->close();
?>
