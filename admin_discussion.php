<?php 
session_start();
include 'db.php';  // Include the database connection file

// Check if user is logged in
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Fetch all comments from the comment table
$sql = "SELECT c.comment_id, c.comment_text, c.timestamp, u.username AS commenter_name, c.user_id, c.attachment, u.profile_photo
        FROM comment c
        JOIN users u ON c.user_id = u.user_id
        ORDER BY c.timestamp DESC";
$result = $conn->query($sql);

if (!$result) {
    die("Error fetching comments: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Discussion</title>
  <link rel="stylesheet" href="admin_discussion.css" />
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
  <h1>DISCUSSION</h1>

    <!-- Add New Comment -->
  <?php if ($user_id) { ?>
    <div class="discussion-card">
    <h3>Add a Post</h3>
    <form action="submit_comment.php" method="POST" class="comment-form" enctype="multipart/form-data">
      <textarea name="comment_text" placeholder="Write a comment..." required></textarea>
      <input type="file" name="attachment" accept="image/*,application/pdf,video/*">
      <button type="submit" class="submit-btn">Post</button>
    </form>
  <?php } else { ?>
    <p>Please log in to add a comment.</p>
  <?php } ?>
</div>

  <!-- Display comments and replies -->
  <?php while ($row = $result->fetch_assoc()) { ?>
    <div class="discussion-card">
      <h2>Post</h2>
      <!-- Comment Box -->
      <div class="comment-card">
        <div class="comment-header">
          <div class="commenter-info">
            
            <!-- Display user's profile picture -->
            <?php
                $profile_photo_path = isset($row['profile_photo']) && !empty($row['profile_photo'])
                  ? 'uploads/' . $row['profile_photo']
                  : 'uploads/default_profile_picture.jpg';
                ?>
                <img src="<?php echo $profile_photo_path; ?>" alt="Replier Avatar" class="avatar">
            <strong><?php echo $row['commenter_name']; ?></strong>
          </div>
          <em class="comment-timestamp"><?php echo $row['timestamp']; ?></em>
        </div>

        <p class="comment-text"><?php echo $row['comment_text']; ?></p>

        <!-- Display attachment -->
        <?php if (isset($row['attachment']) && !empty($row['attachment'])) { ?>
          <div class="comment-attachment">
            <?php 
            $file_type = pathinfo($row['attachment'], PATHINFO_EXTENSION);
            if (in_array($file_type, ['jpg', 'jpeg', 'png', 'gif', 'bmp'])) { ?>
              <img src="<?php echo $row['attachment']; ?>" alt="Attachment" class="attachment-img">
            <?php } elseif ($file_type === 'pdf') { ?>
              <a href="<?php echo $row['attachment']; ?>" target="_blank" class="attachment-link">Download PDF</a>
            <?php } elseif (in_array($file_type, ['mp4', 'avi', 'mov'])) { ?>
              <video width="320" height="240" controls>
                <source src="<?php echo $row['attachment']; ?>" type="video/<?php echo $file_type; ?>">
                Your browser does not support the video tag.
              </video>
            <?php } ?>
          </div>
        <?php } ?>

        
            <!-- Admin Edit and Delete Buttons -->
            <?php if ($_SESSION['role'] == 'admin') { ?>
                <form action="delete_comment.php" method="POST" class="delete-form">
                    <input type="hidden" name="comment_id" value="<?php echo $row['comment_id']; ?>">
                    <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this comment?')">Delete</button>
                </form>
            <?php } ?>
        </div>

      <!-- Replies -->
      <div class="reply-section">
        <h2>Comment</h2>
        <?php
        $comment_id = $row['comment_id'];
       $reply_sql = "SELECT r.reply_text, r.timestamp, u.username AS replier_name, r.reply_id, r.user_id AS reply_user_id, r.attachment, u.profile_photo
              FROM replies r
              JOIN users u ON r.user_id = u.user_id
              WHERE r.comment_id = $comment_id
              ORDER BY r.timestamp ASC";

        $reply_result = $conn->query($reply_sql);

        while ($reply_row = $reply_result->fetch_assoc()) { ?>
          <div class="reply-card">
            <div class="reply-header">
              <div class="replier-info">
                <?php
                  $reply_photo_path = isset($reply_row['profile_photo']) && !empty($reply_row['profile_photo'])
                      ? 'uploads/' . $reply_row['profile_photo']
                      : 'uploads/default_profile_picture.jpg';
                  ?>
                  <img src="<?php echo $reply_photo_path; ?>" alt="Replier Avatar" class="avatar">

                <strong><?php echo $reply_row['replier_name']; ?></strong>
              </div>
              <em class="reply-timestamp"><?php echo $reply_row['timestamp']; ?></em>
            </div>
            <p class="reply-text"><?php echo $reply_row['reply_text']; ?></p>

                                    <!-- Admin Edit and Delete Buttons for Replies -->
                        <?php if ($_SESSION['role'] == 'admin') { ?>
                            <form action="delete_reply.php" method="POST" class="delete-form">
                                <input type="hidden" name="reply_id" value="<?php echo $reply_row['reply_id']; ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this reply?')">Delete</button>
                            </form>
                <?php } ?>
              </div>
            <?php } ?>
          </div>
        <?php } ?>

        <!-- Reply Form -->
        <?php if ($user_id) { ?>
          <form action="submit_reply.php" method="POST" enctype="multipart/form-data" class="reply-form">
            <input type="hidden" name="comment_id" value="<?php echo $comment_id; ?>">
            <textarea name="reply_text" placeholder="Write a reply..." required></textarea>
            <input type="file" name="attachment" accept="image/*,application/pdf,video/*">
            <button type="submit" class="reply-btn">Reply</button>
          </form>
        <?php } else { ?>
          <p>Please log in to reply.</p>
        <?php } ?>
      </div>
    </div>


<script>
    // JavaScript to toggle sidebar visibility
    function toggleSidebar () {
        const sidebar     = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');

        sidebar.classList.toggle('active');
        mainContent.classList.toggle('pushed', sidebar.classList.contains('active'));
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
                <li><a href="aboutus.php">About Us</a></li>
                <li><a href="contactus.php">Contact Us</a></li>
                <li><a href="helpsupport.php">FAQ</a></li>
            </ul>
        </div>
        
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
