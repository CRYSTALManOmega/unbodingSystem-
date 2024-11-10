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

// Use POST for delete action
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['User_Id'])) {
    $userId = (int)$_POST['User_Id'];  // Cast to integer for safety

    if ($userId > 0) {  // Validate that a positive integer ID is provided
        $sql = "DELETE FROM User WHERE User_Id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            echo "Employee deleted successfully.";
        } else {
            echo "Error deleting employee: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Invalid User ID provided.";
    }
} else {
    echo "User ID is required for deletion.";
}

$conn->close();
?>
