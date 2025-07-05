<?php
session_start();   // keep if you really need sessions
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to StudyMate</title>
    <link rel="stylesheet" href="aboutus.css"> <!-- Linking to external CSS file -->
</head>
<body>

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

    <!-- About Section -->
    <section class="about-section">
        <img src="image/aboutusbg.jpg" alt="aboutus" class="about-section">
        <div class="container">
            <h1>ABOUT US</h1>
            <div class="content-box">
                <p>Welcome to StudyMate, the all-in-one platform designed to simplify the way academic resources are shared and accessed. At StudyMate, we understand the challenges of finding, organizing, and managing educational materials, and we're here to make the process effortless.</p>
                <br>
                <p>Our platform is built with the goal of creating a seamless experience for anyone in need of academic resources. Whether you're looking for past exam papers, lecture notes, research articles, or other materials, StudyMate offers a user-friendly space where everything you need is just a few clicks away.</p>
                <br>
                <p>We are dedicated to enhancing the learning experience by providing an intuitive, organized, and secure platform that fosters collaboration and knowledge-sharing. With features like easy search functionality, efficient file uploads, and engaging discussion forums, StudyMate brings together people from all academic backgrounds to learn, share, and grow.</p>
                <br>
                <p>Join us in revolutionizing the way educational resources are accessed and managed. With StudyMate, education is more accessible, efficient, and connected than ever before.</p>
            </div>
        </div>
    </section>
<br> <br>
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