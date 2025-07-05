<?php
session_start();   // keep if you really need sessions
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Help Support</title>
  <link rel="stylesheet" href="helpsupport.css" />
</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <div class="hamburger" onclick="toggleSidebar()" aria-label="Toggle Sidebar Menu">☰</div>
   <!-- Right section: logo + logout -->
    <div class="right-nav">
      <div class="logout"><a href="logout_action.php">Log Out</a></div>
      <div class="logo">
        <a href="<?php 
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'admin') {
                echo 'admindashboard.php'; 
            } elseif ($_SESSION['role'] == 'lecturer') {
                echo 'lecturerdashboard.php'; 
            } elseif ($_SESSION['role'] == 'student') {
                echo 'studentdashboard.php'; 
            } else {
                echo 'login.php'; // Default redirect if role is not found
            }
        } else {
            echo 'login.php'; // If no session, redirect to login
        }
    ?>" class="logo-container">
        <img src="image/logo.png" alt="StudyMate Logo" class="logo">
    </a>

      </div>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <p><a href="<?php 
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] == 'admin') {
            echo 'admindashboard.php'; 
        } elseif ($_SESSION['role'] == 'lecturer') {
            echo 'lecturerdashboard.php'; 
        } elseif ($_SESSION['role'] == 'student') {
            echo 'studentdashboard.php'; 
        } else {
            echo 'login.php'; // Default redirect if role is not found
        }
    } else {
        echo 'login.php'; // If no session, redirect to login
    }
?>"> DASHBOARD </a></p>

    <ul>
      <li><a href="profile.php">● Profile</li>
      <li><a href="<?php 
    if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] == 'student' || $_SESSION['role'] == 'lecturer') {
            echo 'announcementforum.php'; 
        } elseif ($_SESSION['role'] == 'admin') {
            echo 'admin_announcement.php';
        } else {
            echo 'login.php'; // Default redirect if role is not found
        }
    } else {
        echo 'login.php'; // If no session, redirect to login
    }
    ?>">● Announcement</a></li>

      <li><a href="<?php 
      if (isset($_SESSION['role'])) {
          if (in_array($_SESSION['role'], ['student', 'lecturer'])) {
              echo 'discussionforum.php'; 
          } elseif ($_SESSION['role'] == 'admin') {
              echo 'admin_discussion.php';
          } else {
              echo 'login.php'; // Default redirect if role is not found
          }
      } else {
          echo 'login.php'; // If no session, redirect to login
      }

    ?>">● Forum Discussion</a></li>

      <li><a href="<?php 
   if (isset($_SESSION['role'])) {
        if ($_SESSION['role'] == 'student') {
            echo 'studentmaterialsearch.php'; 
        } elseif ($_SESSION['role'] == 'lecturer') {
            echo 'lecturematerialsearch.php';
        } elseif ($_SESSION['role'] == 'admin') {
            echo 'admin_materialmonitoring.php';
        } else {
            echo 'login.php'; // Default redirect if role is not found
        }
    } else {
        echo 'login.php'; // If no session, redirect to login
    }
    ?>">● Material Search</a></li>

      <li><a href="helpsupport.php">● Help & Support</a></li>
    </ul>
  </div>


  <!-- Main Content -->
  <div class="main-content">
    <h1>Need Help?</h1>
    <h2>FAQs</h2>

    <div class="faq-container">
    <div class="faq">
        <button class="accordion">1. How do I create an account?</button>
        <div class="panel">
            <p>To create an account, visit the registration page, enter your personal details, and click "Sign Up." A confirmation email will be sent to you for verification.</p>
        </div>
    </div>
    <div class="faq">
        <button class="accordion">2. How do I reset my password?</button>
        <div class="panel">
            <p>Click on the "Forgot Password?" link on the login page, enter your email, and follow the instructions sent to your inbox to reset your password.</p>
        </div>
    </div>
    <div class="faq">
        <button class="accordion">3. How do I download the materials?</button>
        <div class="panel">
            <p>After logging in, go to the "Resources" section, select the material, and click the download button to save it to your device.</p>
        </div>
    </div>
</div>

    <!-- New Contact Information -->
    <p class="contact-info">Reach out to us at <a href="mailto:support@studymate.com">support@studymate.com</a> or call <a href="tel:+1234567890">123-456-7890</a>.</p>

    <!-- Reminder -->
    <p class="reminder">Reminder: Support Hours 9 a.m. - 11 p.m.</p>
  </div>

<script>
  // JavaScript to toggle sidebar visibility
  function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      const mainContent = document.querySelector('.main-content');

      sidebar.classList.toggle("active"); // Toggle sidebar visibility

      // Adjust content margin based on the sidebar state
      if (sidebar.classList.contains("active")) {
        mainContent.style.marginLeft = "0"; // Shift the content to the right when sidebar is closed
      } else {
        mainContent.style.marginLeft = "260px"; // Reset margin when sidebar is closed
      }
    }

  // JavaScript for FAQ dropdown accordion
  document.addEventListener("DOMContentLoaded", function () {
    const acc = document.getElementsByClassName("accordion");
    for (let i = 0; i < acc.length; i++) {
      acc[i].addEventListener("click", function () {
        this.classList.toggle("active");
        const panel = this.nextElementSibling;
        if (panel.style.display === "block") {
          panel.style.display = "none";
        } else {
          panel.style.display = "block";
        }
      });
    }
  });
</script>

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
                <li><a href="<?php 
        if (isset($_SESSION['role'])) {
            if ($_SESSION['role'] == 'admin') {
                echo 'admindashboard.php'; 
            } elseif ($_SESSION['role'] == 'lecturer') {
                echo 'lecturerdashboard.php'; 
            } elseif ($_SESSION['role'] == 'student') {
                echo 'studentdashboard.php'; 
            } else {
                echo 'login.php'; // Default redirect if role is not found
            }
        } else {
            echo 'login.php'; // If no session, redirect to login
        }
    ?>">Home</a></li>
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