<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed."]));
}

// Retrieve the data from the POST request
$userName = $_POST['username'];
$userPassword = $_POST['password'];

// Query to check the user in the User table
$stmt = $conn->prepare("SELECT Username, Password, Type FROM User WHERE Username = ?");
$stmt->bind_param("s", $userName);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result) {
    if ($userPassword === $result['Password']) {
        $_SESSION['username'] = $userName;
        
        // Determine redirect path based on user type
        switch ($result['Type']) {
            case 'employee':
            case 'student':
                echo json_encode(["status" => "success", "redirect" => "C:/unbordingSystem/userInterface/userInterface.html"]);
                break;
            case 'manager':
                echo json_encode(["status" => "success", "redirect" => "C:/unbordingSystem/departmentManagerInterface/departmentManagerInterface.html"]);
                break;
            case 'admin':
                echo json_encode(["status" => "success", "redirect" => "C:/unbordingSystem/adminInterFace/adminInterface.html"]);
                break;
            default:
                echo json_encode(["status" => "error", "message" => "User type is invalid."]);
                break;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid password."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not found."]);
}

$stmt->close();
$conn->close();
?>
