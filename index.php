<?php
session_start();   // keep if you really need sessions
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to StudyMate</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>

<!-- -----------------------------------------------------------------
     Header
-------------------------------------------------------------------->
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

<!-- -----------------------------------------------------------------
     Welcome / hero
-------------------------------------------------------------------->
<section class="welcome">
    <div class="left-content">
        <h1>Welcome To StudyMate</h1>
        <p>—Your trusted partner in learning and growth, where knowledge meets innovation and success is just a step away!—</p>
    </div>

    <div class="right-content">
        <img src="image/studymate.png" alt="StudyMate Hero Image">
    </div>
</section>
<br> <br> <br>
<!-- -----------------------------------------------------------------
     Footer
-------------------------------------------------------------------->
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

</body>
</html>
