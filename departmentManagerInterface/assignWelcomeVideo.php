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

// Start session to get the logged-in manager's username
session_start();
$loggedInUsername = $_SESSION['username'];

// Get department ID of logged-in manager
$sql = "SELECT Department_Id FROM User WHERE Username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $loggedInUsername);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$departmentId = $user['Department_Id'];

if ($departmentId && isset($_FILES['video'])) {
    // Define the target directory for the video upload
    $targetDir = "uploads/welcome_videos/";
    $videoPath = $targetDir . basename($_FILES['video']['name']);

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['video']['tmp_name'], $videoPath)) {
        // Insert video path into the Department_Welcome_Video table
        $insertSql = "INSERT INTO Department_Welcome_Video (Department_Id, Video_Path) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("is", $departmentId, $videoPath);

        if ($insertStmt->execute()) {
            echo json_encode(["success" => true, "message" => "Welcome video assigned successfully!"]);
        } else {
            echo json_encode(["success" => false, "error" => $conn->error]);
        }

        $insertStmt->close();
    } else {
        echo json_encode(["success" => false, "error" => "Failed to upload video file"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Invalid department or file"]);
}

$stmt->close();
$conn->close();
?>
