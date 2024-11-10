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

// Start session to get logged-in manager's username
session_start();
$loggedInUsername = $_SESSION['username'];

// Retrieve department of logged-in manager
$sql = "SELECT Department_Id FROM User WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$departmentId = $user['Department_Id'];

if ($departmentId) {
    // Fetch submitted modules for users in the same department
    $query = "SELECT usm.Submission_Id, usm.Status, u.First_Name AS User_Name, m.Name AS Module_Name
              FROM User_Submitted_Module usm
              JOIN User u ON usm.User_Id = u.User_Id
              JOIN Module m ON usm.Module_Id = m.Module_Id
              WHERE u.Department_Id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();

    $modules = [];
    while ($row = $result->fetch_assoc()) {
        $modules[] = $row;
    }

    // Return modules as JSON response
    echo json_encode(["success" => true, "modules" => $modules]);
} else {
    echo json_encode(["success" => false, "message" => "Department not found"]);
}

$stmt->close();
$conn->close();
?>
