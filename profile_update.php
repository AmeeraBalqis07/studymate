<?php
session_start();
require_once 'config.php';  // Database connection setup

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "No user is logged in!";
    exit;
}

$uid = $_SESSION['user_id'];

// Read new values from the form
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = isset($_POST['contact_number']) ? trim($_POST['contact_number']) : '';  // Default to empty string if not set

// Debugging: check if the form data is being received
echo "Username: " . $username . "<br>";
echo "Email: " . $email . "<br>";
echo "Phone: " . $phone . "<br>";

// Check if the form is being submitted properly
if (empty($username) || empty($email) || empty($phone)) {
    die("Some fields are empty, please fill them in before submitting!");
}

// Optional profile-image upload
$photo = null;
if (!empty($_FILES['profile_image']['name']) && $_FILES['profile_image']['error'] === 0) {
    echo "File uploaded successfully!<br>";  // Check if the file is uploaded

    // Define the upload directory
    $uploadDir = 'uploads/';
    // Get the file extension
    $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
    $photo = "user_$uid.$ext";  // Name the file based on the user_id
    $uploadPath = $uploadDir . $photo;

    // Check if the uploads directory exists and is writable
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);  // Create directory if it doesn't exist
    }

    // Move the uploaded file to the desired directory
    if (!move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadPath)) {
        die("Error uploading file");
    }
}


// Build the UPDATE query
if ($photo) {
    $sql = "UPDATE users SET username=?, email=?, contact_number=?, profile_photo=? WHERE user_id=?";
    $data = [$username, $email, $phone, $photo, $uid];
} else {
    $sql = "UPDATE users SET username=?, email=?, contact_number=? WHERE user_id=?";
    $data = [$username, $email, $phone, $uid];
}

// Prepare and execute the query
$stmt = $pdo->prepare($sql);
if ($stmt->execute($data)) {
    // Redirect back to profile page with success message
    header('Location: profile.php?updated=1');
    exit;
} else {
    echo "Error updating profile!";
    print_r($stmt->errorInfo());  // Print error if query fails
}
?>
