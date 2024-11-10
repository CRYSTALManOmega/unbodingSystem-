<?php
// getManagerModules.php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
$loggedInUsername = $_SESSION['username'];

// Get the manager's User ID based on the logged-in username
$sql = "SELECT User_Id FROM User WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $managerId = $user['User_Id'];

    // Fetch modules created by the manager
    $sqlModules = "SELECT Module_Id, Name FROM Module WHERE Manager_Id = ?";
    $stmtModules = $conn->prepare($sqlModules);
    $stmtModules->bind_param("i", $managerId);
    $stmtModules->execute();
    $resultModules = $stmtModules->get_result();

    $modules = [];
    while ($row = $resultModules->fetch_assoc()) {
        $modules[] = $row;
    }

    echo json_encode(["success" => true, "modules" => $modules]);
} else {
    echo json_encode(["success" => false, "message" => "Manager not found"]);
}

$stmt->close();
$stmtModules->close();
$conn->close();
?>
