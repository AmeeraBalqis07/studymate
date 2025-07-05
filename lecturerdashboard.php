<?php
session_start();   // keep if you really need sessions
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard</title>
  <link rel="stylesheet" href="lecturerdashboard.css" />
</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <div class="hamburger" onclick="toggleSidebar()" aria-label="Toggle Sidebar Menu">☰</div>
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
    <h1>STUDY TOOLS</h1>
    <div class="discussion-box">
      <div>
        <a href="https://quizlet.com/" target="_blank"><img src="image/quizlet.png" alt="Quizlet Logo"></a>
        <h3>1. Quizlet</h3>
      </div>
      <div>
        <a href="https://www.canva.com/" target="_blank"><img src="image/canva.png" alt="Canva Logo"></a>
        <h3>2. Canva</h3>
      </div>
      <div>
        <a href="https://evernote.com/" target="_blank"><img src="image/evernote.png" alt="Evernote Logo"></a>
        <h3>3. Evernote</h3>
      </div>
      <div>
        <a href="https://www.grammarly.com/" target="_blank"><img src="image/grammarly.jpg" alt="Grammarly Logo"></a>
        <h3>4. Grammarly</h3>
      </div>
      <div>
        <a href="https://www.mendeley.com/" target="_blank"><img src="image/mendeley.jpg" alt="Mendeley Logo"></a>
        <h3>5. Mendeley</h3>
      </div>
    </div>

    <h1>RECOMMENDATIONS</h1>
    <div class="discussion-box">
      <div>
        <a href="https://scholar.google.com/" target="_blank"><img src="image/google-scholar.png" alt="Quizlet Logo"></a>
        <h3>1. Google Scholar</h3>
      </div>
      <div>
        <a href="https://www.emerald.com/insight/" target="_blank"><img src="image/emerald.png" alt="Canva Logo"></a>
        <h3>2. Emerald Insight</h3>
      </div>
      <div>
        <a href="https://ieeexplore.ieee.org/Xplore/home.jsp" target="_blank"><img src="image/ieee.png" alt="Evernote Logo"></a>
        <h3>3. IEEE Explore</h3>
      </div>
      <div>
        <a href="https://www.sciencedirect.com/" target="_blank"><img src="image/science-direct.png" alt="Grammarly Logo"></a>
        <h3>4. Science Direct</h3>
      </div>
    </div>
  </div>

  <script>
    // JavaScript to toggle sidebar visibility
      function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.querySelector('.main-content');
    const discussionBoxes = document.querySelectorAll('.discussion-box');

    sidebar.classList.toggle("active");

    if (sidebar.classList.contains("active")) {
      mainContent.style.marginLeft = "10px";
      discussionBoxes.forEach(box => box.classList.add("sidebar-closed"));
    } else {
      mainContent.style.marginLeft = "270px";
      discussionBoxes.forEach(box => box.classList.remove("sidebar-closed"));
    }
  }
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