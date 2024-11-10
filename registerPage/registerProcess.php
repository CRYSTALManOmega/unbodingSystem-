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

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO User (Department_Id, Type, Email, Username, Password, Job_Title, BirthDate, First_Name, Last_Name, Company_Name, Branch_Name, National_Id, Created, Updated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
$stmt->bind_param("ssssssssssss", $department_id, $type, $email, $username, $password, $job_title, $birthdate, $first_name, $last_name, $company_name, $branch_name, $national_id);

// Collect form data
$department_id = $_POST['department_id'];
$type = $_POST['type'];
$email = $_POST['email'];
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
$job_title = $_POST['job_title'];
$birthdate = $_POST['birthdate'];
$first_name = $_POST['first_name'];
$last_name = $_POST['last_name'];
$company_name = $_POST['company_name'];
$branch_name = $_POST['branch_name'];
$national_id = $_POST['national_id'];

$response = [];

if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['message'] = "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
