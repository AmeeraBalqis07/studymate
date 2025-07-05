<?php
session_start();   // keep if you really need sessions
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Announcement</title>
  <link rel="stylesheet" href="announcementforum.css" />
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
      <li><a href="helpsupport.php">● Help Support</a></li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="main-content">
        <div class="slide-text-container">
          <h1 class="slide-text">ATTENTION!</h1>
          <div class="slideshow-container">
            <div class="discussion-box">
              <h2>&#128226; New Material</h2>
              <p>StudyMate just launched a new material on 'Past Exams.' Start learning today! &#128512;</p>
            </div>
            <div class="discussion-box">
              <h2>&#128226; System Maintenance</h2>
              <p>⚠️ Scheduled maintenance this Sunday from 5 PM - 7 PM. Plan ahead! ⚠️</p>
            </div>
          </div>
        </div>
      </div>

  <script>
    // JavaScript to toggle sidebar visibility
    function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.querySelector('.main-content');

  sidebar.classList.toggle("active");

  if (sidebar.classList.contains("active")) {
    mainContent.style.marginLeft = "0";
  } else {
    mainContent.style.marginLeft = "260px";
  }}
    
    // Slideshow logic
  let currentSlide = 0;
  const slides = document.querySelectorAll('.discussion-box');

  function showSlide(index) {
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
  }

  window.addEventListener('DOMContentLoaded', () => {
    if (slides.length > 0) {
      showSlide(currentSlide);
      setInterval(nextSlide, 4000);
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
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="helpsupport.php">FAQ</a></li>
            </ul>
        </div>

        <!-- social -->
        <div class="footer-social">
            <h3>Follow Us</h3>
            <ul>
                <li><a href="https://facebook.com" target="_blank">Facebook</a></li>
                <li><a href="https://twitter.com" target="_blank">Twitter</a></li>
                <li><a href="https://instagram.com" target="_blank">Instagram</a></li>
                <li><a href="https://linkedin.com" target="_blank">LinkedIn</a></li>
            </ul>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2025 StudyMate. All Rights Reserved.</p>
    </div>
</footer>

</body>
</html>