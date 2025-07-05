<?php 
session_start();
require_once 'config.php';  // Database connection setup

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$uid = $_SESSION['user_id'];

// Fetch user data from the database based on the session user_id
$stmt = $pdo->prepare("SELECT username, email, contact_number, profile_photo, role FROM users WHERE user_id = ?");
$stmt->execute([$uid]);
$user = $stmt->fetch();

if (!$user) {
    // If no user is found, redirect to login
    header('Location: login.php');
    exit;
}

// Set the profile data
$username = $user['username'];
$email = !empty($user['email']) ? $user['email'] : '-';  // Default value if email is NULL
$phone = !empty($user['contact_number']) ? $user['contact_number'] : '-';  // Default value if phone is NULL
$profilePicture = !empty($user['profile_photo']) ? $user['profile_photo'] : 'image/default_profile_picture.jpg';  // Default image if no profile photo
$role = $user['role'];  // This will hold 'admin', 'lecturer', or 'student'
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profile - StudyMate</title>
  <link rel="stylesheet" href="profile.css" />
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

  <!-- Profile -->
  <main class="profile-page">
  <h1>My Profile</h1>

  <form action="profile_update.php" method="POST" enctype="multipart/form-data" class="profile-form">

    <div class="profile-picture">
    <img src="uploads/<?php echo $profilePicture; ?>" alt="Profile Picture" />
    <input type="file" name="profile_image" accept="image/*" />
</div>

    <div class="form-group">
      <label>Username</label>
      <input type="text" name="username" value="<?php echo $username; ?>" required />
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" name="email" value="<?php echo $email; ?>" required />
    </div>

    <div class="form-group">
      <label>Contact Number</label>
      <input type="text" name="contact_number" value="<?php echo $phone; ?>" required />
    </div>

    <!-- Display additional fields based on role -->
    <?php if ($role == 'lecturer'): ?>
      <div class="form-group">
        <label>Faculty</label>
        <input type="text" name="faculty" value="Faculty of Engineering" /> <!-- Example, replace with real value -->
      </div>
    <?php endif; ?>

    <div class="form-group">
      <label>Password</label>
      <input type="password" value="********" readonly />
      <a href="reset.php" class="reset-link">Reset Password</a>
    </div>

    <button type="submit" class="btn-save">Save Changes</button>
  </form>
</main>

  <script>
    // JavaScript to toggle sidebar visibility
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      const mainContent = document.querySelector('.main-content');
      
      sidebar.classList.toggle("active"); // Toggle sidebar visibility
    }
  </script>

<!-- Footer -->
<footer class="footer">
    <div class="footer-container">
        <div class="footer-about">
            <h3>About StudyMate</h3>
            <p>Your trusted partner in learning and growth, where knowledge meets innovation.</p>
        </div>

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
