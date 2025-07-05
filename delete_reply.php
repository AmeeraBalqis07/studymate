<?php
session_start();
include 'db.php';  // Include the database connection file

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete a reply.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reply_id = $_POST['reply_id'];

    // Ensure the logged-in user is the one who posted the reply
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT user_id FROM replies WHERE reply_id = ?");
    $stmt->bind_param("i", $reply_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['user_id'] == $user_id) {
        // Delete the reply
        $stmt = $conn->prepare("DELETE FROM replies WHERE reply_id = ?");
        $stmt->bind_param("i", $reply_id);
        $stmt->execute();

        // Redirect back to the comment & reply page
        header("Location: discussionforum.php");
        exit();
    } else {
        echo "You are not authorized to delete this reply.";
    }
}
?>
