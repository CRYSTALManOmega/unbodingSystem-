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

    // Retrieve User's Department_Id
    $userStmt = $conn->prepare("SELECT Department_Id FROM User WHERE Username = ?");
    $userStmt->bind_param("s", $username);
    $userStmt->execute();
    $userResult = $userStmt->get_result()->fetch_assoc();
    $departmentId = $userResult['Department_Id'];

    // Fetch Department Welcome Video
    $videoStmt = $conn->prepare("SELECT Video_Path FROM Department_Welcome_Video WHERE Department_Id = ?");
    $videoStmt->bind_param("i", $departmentId);
    $videoStmt->execute();
    $videoResult = $videoStmt->get_result()->fetch_assoc();

    // Fetch Task Updates (Modules assigned to the department)
    $modulesStmt = $conn->prepare("SELECT Name, Date_Assigned FROM Module WHERE Department_Id = ?");
    $modulesStmt->bind_param("i", $departmentId);
    $modulesStmt->execute();
    $modulesResult = $modulesStmt->get_result();

    $modules = [];
    while ($module = $modulesResult->fetch_assoc()) {
        $modules[] = [
            "name" => $module["Name"],
            "assignedDate" => $module["Date_Assigned"]
        ];
    }

    echo json_encode([
        "videoPath" => $videoResult['Video_Path'],
        "modules" => $modules
    ]);

    $userStmt->close();
    $videoStmt->close();
    $modulesStmt->close();
} else {
    echo json_encode(["error" => "User not logged in"]);
}

$conn->close();
?>
