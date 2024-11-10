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

// Retrieve posted data
$data = json_decode(file_get_contents("php://input"), true);
$moduleName = $data['moduleName'];
$moduleDescription = $data['moduleDescription'];

// Start a session and get the logged-in username
session_start();
$loggedInUsername = $_SESSION['username'];

// Retrieve Department_Id and Manager_Id using the username
$sql = "SELECT Department_Id, User_Id AS Manager_Id FROM User WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $departmentId = $user['Department_Id'];
    $managerId = $user['Manager_Id'];
    $moduleStatus = 'active';
    $assigned = date("Y-m-d H:i:s");

    // Insert new module into the Module table
    $insertSql = "INSERT INTO Module (Name, Description, Manager_Id, Department_Id, Module_Status, assigned) VALUES (?, ?, ?, ?, ?, ?)";
    $insertStmt = $conn->prepare($insertSql);
    $insertStmt->bind_param("ssisss", $moduleName, $moduleDescription, $managerId, $departmentId, $moduleStatus, $assigned);

    if ($insertStmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "User not found"]);
}

// Close connections
$stmt->close();
$insertStmt->close();
$conn->close();
?>
