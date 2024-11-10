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

$sql = "SELECT 
            User.User_Id, 
            User.First_Name, 
            User.Last_Name, 
            User.Email, 
            User.National_Id, 
            User.Job_Title, 
            User.Type, 
            Department.Name AS Department_Name 
        FROM 
            User 
        LEFT JOIN 
            Department ON User.Department_Id = Department.Department_Id";

$result = $conn->query($sql);
$profiles = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $profiles[] = $row;
    }
}

echo json_encode($profiles);

$conn->close();
?>
