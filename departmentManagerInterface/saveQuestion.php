<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "company_database";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check required fields
if (isset($_POST['moduleSelect'], $_POST['questionText'], $_POST['questionType'])) {
    $module_id = $_POST['moduleSelect'];
    $question_text = $_POST['questionText'];
    $question_type = $_POST['questionType'];
    $question_id = null;

    // Insert the question into the Question table
    $stmt = $conn->prepare("INSERT INTO Question (Module_Id, title, Type) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $module_id, $question_text, $question_type);
    if ($stmt->execute()) {
        $question_id = $stmt->insert_id;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to insert question']);
        exit;
    }
    $stmt->close();

    // Process file uploads if any
    if (!empty($_FILES['files']['name'][0])) {
        foreach ($_FILES['files']['tmp_name'] as $key => $tmp_name) {
            $file_name = $_FILES['files']['name'][$key];
            $file_tmp = $_FILES['files']['tmp_name'][$key];
            $file_path = "uploads/files/" . basename($file_name);

            if (move_uploaded_file($file_tmp, $file_path)) {
                $stmt_file = $conn->prepare("INSERT INTO Question_Attachment (Question_Id, File_Path, File_Type) VALUES (?, ?, 'file')");
                $stmt_file->bind_param("is", $question_id, $file_path);
                $stmt_file->execute();
                $stmt_file->close();
            }
        }
    }

    // Process video uploads if any
    if (!empty($_FILES['videos']['name'][0])) {
        foreach ($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
            $video_name = $_FILES['videos']['name'][$key];
            $video_tmp = $_FILES['videos']['tmp_name'][$key];
            $video_path = "uploads/videos/" . basename($video_name);

            if (move_uploaded_file($video_tmp, $video_path)) {
                $stmt_video = $conn->prepare("INSERT INTO Question_Attachment (Question_Id, File_Path, File_Type) VALUES (?, ?, 'video')");
                $stmt_video->bind_param("is", $question_id, $video_path);
                $stmt_video->execute();
                $stmt_video->close();
            }
        }
    }

    echo json_encode(['success' => true, 'message' => 'Question added successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Required fields missing']);
}

$conn->close();
?>
