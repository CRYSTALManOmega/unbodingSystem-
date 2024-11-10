<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(null);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$departmentName = $data['departmentName'];

// Retrieve department details
$stmt = $conn->prepare("SELECT * FROM Department WHERE Name = ?");
$stmt->bind_param("s", $departmentName);
$stmt->execute();
$result = $stmt->get_result();
$department = $result->fetch_assoc();
echo json_encode($department);

$stmt->close();
$conn->close();
?>
