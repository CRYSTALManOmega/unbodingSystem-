<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode([]);
    exit;
}

// Retrieve all departments
$result = $conn->query("SELECT * FROM Department");
$departments = $result->fetch_all(MYSQLI_ASSOC);
echo json_encode($departments);

$conn->close();
?>
