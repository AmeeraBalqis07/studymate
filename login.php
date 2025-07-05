<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - StudyMate</title>
    <link rel="stylesheet" href="login.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"> <!-- FontAwesome for the eye icon -->
</head>
<body>

<!-- ============================================================== 
     Header  
================================================================= -->
<header>
    <!-- left nav -->
    <div class="header-left">
        <a href="aboutus.php" class="nav-link">About Us</a>
        <a href="contactus.php" class="nav-link">Contact Us</a>
    </div>

    <!-- right nav + logo -->
    <div class="header-right">
        <a href="login.php"  class="nav-link">Login</a>
        <a href="signup.php" class="nav-link">Signup</a>

        <a href="index.php" class="logo-container">
        <img src="image/logo.png" alt="StudyMate Logo" class="logo">
        </a>
    </div>
</header>

<!-- ============================================================== 
     Login form
================================================================= -->
<section class="login">
    <div class="container">
        <div class="login-form">
            <h1>Log&nbsp;In</h1>

            <!-- Display Error Message if login fails -->
            <?php
            if (isset($_SESSION['error'])) {
                echo "<p class='error'>" . $_SESSION['error'] . "</p>";
                unset($_SESSION['error']); // Clear error after displaying
            }
            ?>

            <form action="login_action.php" method="POST">
                <div class="form-group">
                    <label for="email-username">Email/Username</label>
                    <input type="text" id="email-username" name="email-username" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <!-- Password field with eye icon -->
                    <div class="password-container">
                        <input type="password" id="password" name="password" required>
                        <span toggle="#password" class="fa fa-eye-slash" id="eye-icon"></span>  <!-- Eye icon -->
                    </div>
                </div>

                <button type="submit" class="btn-login">Login</button>
            </form>
            <br><br>
            <p>Forgot your password? <a href="reset.php">Reset&nbsp;it&nbsp;here</a></p>
            <br><p>Don't have an account? <a href="signup.php">Sign&nbsp;Up</a></p>
        </div>
    </div>
</section>

<!-- ============================================================== 
     Footer  
================================================================= -->
<footer class="footer">
    <div class="footer-container">
        <!-- about -->
        <div class="footer-about">
            <h3>About StudyMate</h3>
            <p>Your trusted partner in learning and growth, where knowledge meets innovation.</p>
        </div>

        <!-- quick links -->
        <div class="footer-links">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="helpsupport.php">FAQ</a></li>
            </ul>
        </div>

        <!-- social -->
        <div class="footer-social">
            <h3>Follow Us</h3>
            <ul>
                <li><a href="https://facebook.com"  target="_blank">Facebook</a></li>
                <li><a href="https://twitter.com"   target="_blank">Twitter</a></li>
                <li><a href="https://instagram.com" target="_blank">Instagram</a></li>
                <li><a href="https://linkedin.com"  target="_blank">LinkedIn</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 StudyMate. All Rights Reserved.</p>
    </div>
</footer>

<!-- JavaScript for toggling password visibility -->
<script>
    const eyeIcon = document.getElementById("eye-icon");
    const passwordField = document.getElementById("password");

    eyeIcon.addEventListener("click", function() {
        if (passwordField.type === "password") {
            passwordField.type = "text";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        } else {
            passwordField.type = "password";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        }
    });
</script>

</body>
</html>