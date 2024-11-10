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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve data from POST request
    $userId = $_POST['User_Id'];
    $firstName = $_POST['First_Name'];
    $lastName = $_POST['Last_Name'];
    $email = $_POST['Email'];
    $username = $_POST['Username'];
    $password = !empty($_POST['Password']) ? password_hash($_POST['Password'], PASSWORD_DEFAULT) : null;
    $jobTitle = $_POST['Job_Title'];
    $companyName = $_POST['Company_Name'] ?? null;
    $branchName = $_POST['Branch_Name'] ?? null;
    $nationalId = $_POST['National_Id'];
    $birthDate = $_POST['BirthDate'];
    $type = $_POST['Type'];
    $updated = date("Y-m-d H:i:s");

    try {
        // SQL query to update the employee details, conditionally including password
        $sql = "UPDATE User SET 
                    First_Name = ?, 
                    Last_Name = ?, 
                    Email = ?, 
                    Username = ?, " .
                    ($password ? "Password = ?, " : "") . 
                    "Job_Title = ?, 
                    Company_Name = ?, 
                    Branch_Name = ?, 
                    National_Id = ?, 
                    BirthDate = ?, 
                    Type = ?, 
                    Updated = ? 
                WHERE User_Id = ?";

        // Prepare statement
        $stmt = $conn->prepare($sql);

        // Bind parameters conditionally based on password presence
        if ($password) {
            $stmt->bind_param("ssssssssssssi", $firstName, $lastName, $email, $username, $password, $jobTitle, $companyName, $branchName, $nationalId, $birthDate, $type, $updated, $userId);
        } else {
            $stmt->bind_param("ssssssssssi", $firstName, $lastName, $email, $username, $jobTitle, $companyName, $branchName, $nationalId, $birthDate, $type, $updated, $userId);
        }

        // Execute the query
        if ($stmt->execute()) {
            echo "Employee updated successfully.";
        } else {
            echo "Error updating employee: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } catch (Exception $e) {
        echo "An error occurred: " . $e->getMessage();
    }
}

// Close the database connection
$conn->close();
?>
