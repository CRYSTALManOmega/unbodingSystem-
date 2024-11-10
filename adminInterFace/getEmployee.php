<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Validate and retrieve User_Id
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['User_Id']) && is_numeric($_GET['User_Id'])) {
    $userId = intval($_GET['User_Id']);  // Ensure User_Id is an integer

    // Prepare and execute the statement
    $sql = "SELECT * FROM User WHERE User_Id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);  // Use integer type binding
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if employee exists and return JSON response
    if ($result && $result->num_rows > 0) {
        echo json_encode($result->fetch_assoc(), JSON_PRETTY_PRINT);
    } else {
        echo json_encode(["error" => "Employee not found."]);
    }

    // Close resources
    $stmt->close();
} else {
    echo json_encode(["error" => "Invalid or missing User_Id."]);
}

$conn->close();
?>
