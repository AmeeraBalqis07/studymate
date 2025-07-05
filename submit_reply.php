<?php
session_start();
include 'db.php';  // Include the database connection file

if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to post a reply.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $comment_id = $_POST['comment_id'];  // Get the comment ID to reply to
    $reply_text = $_POST['reply_text'];  // Get the reply text
    $user_id = $_SESSION['user_id'];    // Get the user ID

    // Handle the file upload for replies
    $attachment = "";  // Set the default value as empty string (no attachment)
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] == 0) {
        $file_name = $_FILES['attachment']['name'];
        $file_tmp = $_FILES['attachment']['tmp_name'];
        $file_type = $_FILES['attachment']['type'];

        // Generate a unique file name and move the uploaded file to the server
        $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
        $new_file_name = uniqid() . '.' . $file_extension;
        $upload_path = 'uploads/' . $new_file_name;

        if (move_uploaded_file($file_tmp, $upload_path)) {
            $attachment = $upload_path;  // Save the file path
        } else {
            die("Error uploading file.");
        }
    }

    // Sanitize the reply text to prevent unwanted HTML tags or script injections
    $reply_text = htmlspecialchars($reply_text, ENT_QUOTES, 'UTF-8');

    // Insert the reply into the database
    $stmt = $conn->prepare("INSERT INTO replies (comment_id, user_id, reply_text, attachment, timestamp) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("iiss", $comment_id, $user_id, $reply_text, $attachment);

    if ($stmt->execute()) {
        // Redirect back to the comment & reply page after successfully posting the reply
        header("Location: discussionforum.php");
        exit();
    } else {
        echo "Error posting reply: " . $conn->error;
    }
}
?>
