<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    error_log("Database connection failed: " . $conn->connect_error);
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $userStmt = $conn->prepare("SELECT Department_Id FROM User WHERE Username = ?");
    $userStmt->bind_param("s", $username);
    $userStmt->execute();
    $userResult = $userStmt->get_result()->fetch_assoc();
    $departmentId = $userResult['Department_Id'];

    $modulesStmt = $conn->prepare("SELECT Module_Id AS id, Name AS title, Description AS description FROM Module WHERE Department_Id = ? AND Module_Status = 'activated'");
    $modulesStmt->bind_param("i", $departmentId);
    $modulesStmt->execute();
    $modulesResult = $modulesStmt->get_result();

    $modules = [];
    while ($module = $modulesResult->fetch_assoc()) {
        $modules[] = $module;
    }

    echo json_encode(["modules" => $modules]);

    $userStmt->close();
    $modulesStmt->close();
} else {
    echo json_encode(["error" => "User not logged in"]);
}

$conn->close();
?>
