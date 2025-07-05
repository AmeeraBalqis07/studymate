<?php
// Start the session to store user info if login is successful
session_start();

// Include the database connection (make sure the path is correct)
include('db.php');  // Make sure db_connection.php is in the correct folder

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture the form data
    $emailUsername = trim($_POST['email-username']);
    $password = $_POST['password'];

    // Validate input data (basic example)
    if (empty($emailUsername) || empty($password)) {
        $_SESSION['error'] = 'Please enter both email/username and password';
        header('Location: login.php');
        exit;
    }

    // Sanitize and escape the input to prevent SQL injection
    $emailUsername = mysqli_real_escape_string($conn, $emailUsername);
    $password = mysqli_real_escape_string($conn, $password);

    // Query to check if the user exists (use 'user_id' as primary key)
    $query = "SELECT user_id, email, username, password, role FROM users WHERE email = '$emailUsername' OR username = '$emailUsername'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        // User found, fetch the user details
        $user = mysqli_fetch_assoc($result);

        // Verify the password (assuming passwords are hashed in the database)
        if (password_verify($password, $user['password'])) {
            // Password is correct, store user session data
            $_SESSION['user_id'] = $user['user_id'];  // Correct column name
            $_SESSION['email'] = $user['email'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];  // Store role in session

            // Prevent the login page from being cached
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Redirect to the appropriate dashboard based on user role
            if ($_SESSION['role'] == 'admin') {
                header('Location: admindashboard.php');
            } elseif ($_SESSION['role'] == 'lecturer') {
                header('Location: lecturerdashboard.php');
            } elseif ($_SESSION['role'] == 'student') {
                header('Location: studentdashboard.php');
            } else {
                // Default fallback if role is not recognized
                $_SESSION['error'] = 'Role not recognized';
                header('Location: login.php');
            }
            exit;
        } else {
            // Incorrect password
            $_SESSION['error'] = 'Invalid password';
            header('Location: login.php');
            exit;
        }
    } else {
        // User not found
        $_SESSION['error'] = 'No user found with that email/username';
        header('Location: login.php');
        exit;
    }
} else {
    // Redirect back to the login page if the request method is not POST
    header('Location: login.php');
    exit;
}
?>
