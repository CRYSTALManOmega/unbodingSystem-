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

// Start session and retrieve the logged-in manager's username
session_start();
$loggedInUsername = $_SESSION['username'];

// Retrieve Department_Id using the manager's username
$sql = "SELECT Department_Id FROM User WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $departmentId = $user['Department_Id'];
    $moduleId = $_POST['module_id']; // Selected module ID from the form

    // Get all users in the department and assign the module to them
    $usersSql = "SELECT User_Id FROM User WHERE Department_Id = ?";
    $usersStmt = $conn->prepare($usersSql);
    $usersStmt->bind_param("i", $departmentId);
    $usersStmt->execute();
    $usersResult = $usersStmt->get_result();

    $allAssigned = true;
    while ($userRow = $usersResult->fetch_assoc()) {
        $userId = $userRow['User_Id'];

        // Insert assignment, ignoring duplicates
        $assignStmt = $conn->prepare("INSERT IGNORE INTO Assigned_User (User_Id, Module_Id) VALUES (?, ?)");
        $assignStmt->bind_param("ii", $userId, $moduleId);

        if (!$assignStmt->execute()) {
            $allAssigned = false;
            break;
        }
    }

    // Check if all assignments were successful
    if ($allAssigned) {
        echo json_encode(["success" => true, "message" => "Module assigned to all department users"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to assign module to all users"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Department not found"]);
}

// Close statements and connection
$stmt->close();
$usersStmt->close();
$conn->close();
?>
