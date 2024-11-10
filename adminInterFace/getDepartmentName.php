<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['Department_Id'])) {
    $departmentId = $_GET['Department_Id'];
    $stmt = $conn->prepare("SELECT Name FROM Department WHERE Department_Id = ?");
    $stmt->bind_param("i", $departmentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(["error" => "Department not found."]);
    }
    $stmt->close();
}
$conn->close();
?>
