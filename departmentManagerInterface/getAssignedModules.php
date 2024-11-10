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

$loggedInUsername = $_SESSION['username'];
$sql = "SELECT Department_Id FROM User WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $departmentId = $user['Department_Id'];

    // Get assigned modules for the department
    $modulesSql = "SELECT Module_Id, Name, Description, Module_Status FROM Module WHERE Department_Id = ?";
    $modulesStmt = $conn->prepare($modulesSql);
    $modulesStmt->bind_param("i", $departmentId);
    $modulesStmt->execute();
    $modulesResult = $modulesStmt->get_result();

    $modules = [];
    while ($row = $modulesResult->fetch_assoc()) {
        $modules[] = $row;
    }

    echo json_encode(["success" => true, "modules" => $modules]);
} else {
    echo json_encode(["success" => false, "message" => "Department not found"]);
}

$stmt->close();
$conn->close();
?>
