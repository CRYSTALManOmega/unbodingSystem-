<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employeeInput = $_POST['employeeInput'];
    $departmentInput = $_POST['departmentInput'];

    // Find manager by National ID or full name
    $stmt = $conn->prepare("SELECT User_Id, Type, Department_Id FROM User WHERE National_Id = ? OR CONCAT(First_Name, ' ', Last_Name) = ?");
    $stmt->bind_param("ss", $employeeInput, $employeeInput);
    $stmt->execute();
    $employee = $stmt->get_result()->fetch_assoc();

    if (!$employee || $employee['Type'] !== 'Manager') {
        echo json_encode(["success" => false, "message" => "User is not a manager in any department."]);
        exit;
    }

    // Find department by name and confirm it's the assigned department
    $stmt = $conn->prepare("SELECT Department_Id FROM Department WHERE Name = ?");
    $stmt->bind_param("s", $departmentInput);
    $stmt->execute();
    $department = $stmt->get_result()->fetch_assoc();

    if (!$department || $department['Department_Id'] != $employee['Department_Id']) {
        echo json_encode(["success" => false, "message" => "Manager is not assigned to the specified department."]);
        exit;
    }

    // Begin transaction for atomicity
    $conn->begin_transaction();

    try {
        // Unassign manager in User table
        $stmt = $conn->prepare("UPDATE User SET Department_Id = NULL, Type = 'Employee' WHERE User_Id = ?");
        $stmt->bind_param("i", $employee['User_Id']);
        $stmt->execute();

        // Remove manager in Department table
        $stmt = $conn->prepare("UPDATE Department SET Manager_Id = NULL WHERE Department_Id = ?");
        $stmt->bind_param("i", $department['Department_Id']);
        $stmt->execute();

        // Commit transaction
        $conn->commit();
        echo json_encode(["success" => true, "message" => "Manager unassigned from department successfully."]);
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $conn->rollback();
        echo json_encode(["success" => false, "message" => "Error unassigning manager: " . $e->getMessage()]);
    }
}

$conn->close();
?>
