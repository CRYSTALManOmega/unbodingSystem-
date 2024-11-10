<?php
// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database"; // Use your actual database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the username from the request
$user = $_GET['username'];

// SQL query to fetch user information
$sql = "SELECT * FROM User WHERE Username='$user'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output user data as JSON
    $userData = $result->fetch_assoc();
    echo json_encode($userData);
} else {
    echo json_encode(null);
}

$conn->close();
?>
