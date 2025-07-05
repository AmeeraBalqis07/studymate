<?php
session_start();
include 'db.php';  // Include the database connection file

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to post a comment.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_text = $_POST['comment_text'];
    $user_id = $_SESSION['user_id'];

    if (empty($comment_text)) {
        die("Comment cannot be empty.");
    }

    // Handle the file upload
    $attachment = null;  // Default to null if no file is uploaded
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $file_name = $_FILES['attachment']['name'];
        $file_tmp = $_FILES['attachment']['tmp_name'];
        $file_type = $_FILES['attachment']['type'];

        // Generate a unique file name and move the uploaded file to the server
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = uniqid() . '.' . $file_extension;
        $upload_path = 'uploads/' . $new_file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $attachment = $upload_path;  // Set attachment to the file path
        } else {
            die("Error uploading file.");
        }
    }

    // Sanitize the comment text to prevent unwanted HTML tags or script injections
    $comment_text = htmlspecialchars($comment_text, ENT_QUOTES, 'UTF-8');

    // Prepare the SQL query to insert the comment into the database
    $stmt = $conn->prepare("INSERT INTO comment (user_id, comment_text, attachment, timestamp) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $user_id, $comment_text, $attachment);

    if ($stmt->execute()) {
        // Redirect back to the comment & reply page after successfully posting the comment
        header("Location: discussionforum.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
