<?php
session_start();
include('db.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $emailUsername = mysqli_real_escape_string($conn, $_POST['email-username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirmPassword = mysqli_real_escape_string($conn, $_POST['confirm-password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $faculty = isset($_POST['faculty']) ? mysqli_real_escape_string($conn, $_POST['faculty']) : null;
    $contact_number = isset($_POST['contact_number']) ? mysqli_real_escape_string($conn, $_POST['contact_number']) : null;
    $gender = isset($_POST['gender']) ? mysqli_real_escape_string($conn, $_POST['gender']) : null;
    $date_of_birth = isset($_POST['date_of_birth']) ? mysqli_real_escape_string($conn, $_POST['date_of_birth']) : null;
    $profile_photo = $_FILES['profile_photo']['name'];

    // Validate password confirmation
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = 'Passwords do not match!';
        header('Location: signup.php');
        exit;
    }

    // Hash the password before storing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Handle file upload (optional profile photo)
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($profile_photo);
    if ($profile_photo) {
        move_uploaded_file($_FILES["profile_photo"]["tmp_name"], $target_file);
    }

    // Insert user data into the database (without the `user_id` column)
    $query = "INSERT INTO users (full_name, username, password, email, role, faculty, profile_photo, contact_number, gender, date_of_birth) 
              VALUES ('$full_name', '$emailUsername', '$hashedPassword', '$emailUsername', '$role', '$faculty', '$target_file', '$contact_number', '$gender', '$date_of_birth')";

    if (mysqli_query($conn, $query)) {
        // Redirect to login page on successful signup
        $_SESSION['success'] = 'Signup successful! Please log in.';
        header('Location: login.php');
        exit;
    } else {
        // Error occurred
        $_SESSION['error'] = 'Error: ' . mysqli_error($conn);
        header('Location: signup.php');
        exit;
    }
}
?>
