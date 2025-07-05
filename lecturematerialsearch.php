<?php
session_start();
include 'db.php';  // Include the database connection file

// Handle filter parameters and search query
$fromYear = isset($_POST['fromYear']) ? $_POST['fromYear'] : '';
$toYear = isset($_POST['toYear']) ? $_POST['toYear'] : '';
$materialTypes = isset($_POST['materialTypes']) ? $_POST['materialTypes'] : [];
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : ''; // Handle search query

// Prepare the SQL query to fetch the materials from the database
$sql = "SELECT * FROM material_uploaded WHERE 1";  // Default query to select all files

// Apply filters if provided
if ($fromYear) {
    $sql .= " AND YEAR(uploaded_date) >= $fromYear";
}
if ($toYear) {
    $sql .= " AND YEAR(uploaded_date) <= $toYear";
}
if (!empty($materialTypes)) {
    $sql .= " AND type IN ('" . implode("','", $materialTypes) . "')";
}

// Apply search query filter if provided
if ($searchQuery) {
    $searchQuery = $conn->real_escape_string($searchQuery);  // Prevent SQL injection
    $sql .= " AND (file_name LIKE '%$searchQuery%' OR type LIKE '%$searchQuery%')";
}

// Fetch data from the database
$result = $conn->query($sql);
$materials = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $materials[] = $row;
    }
} else {
    // If no materials match the filter, store a message to show
    $noResultsMessage = "No materials found matching your criteria.";
}

// Close the database connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Material Search</title>
  <link rel="stylesheet" href="lecturematerialsearch.css" />
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
        if ($_SESSION['role'] == 'student' || $_SESSION['role'] == 'lecturer') {
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


    <!-- Filter Section -->
  <div class="main-container">
    <div class="filter">
        <h3>Year</h3>
        <form action="lecturematerialsearch.php" method="POST">
          <label for="from">From:</label>
          <input type="number" id="from" name="fromYear" placeholder="YYYY" value="<?php echo $fromYear; ?>"><br>
          <label for="to">To:</label>
          <input type="number" id="to" name="toYear" placeholder="YYYY" value="<?php echo $toYear; ?>"><br>
          
          <h3>Material Type</h3>
          <input type="checkbox" name="materialTypes[]" value="Academic Blogs" <?php echo in_array('Academic Blogs', $materialTypes) ? 'checked' : ''; ?>>
          <label for="academicBlogs">Academic Blogs</label><br>
          <input type="checkbox" name="materialTypes[]" value="Articles" <?php echo in_array('Articles', $materialTypes) ? 'checked' : ''; ?>>
          <label for="articles">Articles</label><br>
          <input type="checkbox" name="materialTypes[]" value="Books" <?php echo in_array('Books', $materialTypes) ? 'checked' : ''; ?>>
          <label for="books">Books</label><br>
          <input type="checkbox" name="materialTypes[]" value="Journals" <?php echo in_array('Journals', $materialTypes) ? 'checked' : ''; ?>>
          <label for="journals">Journals</label><br>
          <input type="checkbox" name="materialTypes[]" value="Lecture Notes" <?php echo in_array('Lecture Notes', $materialTypes) ? 'checked' : ''; ?>>
          <label for="lectureNotes">Lecture Notes</label><br>
          <input type="checkbox" name="materialTypes[]" value="Past Exam" <?php echo in_array('Past Exam', $materialTypes) ? 'checked' : ''; ?>>
          <label for="pastExam">Past Exam</label><br>
          <input type="checkbox" name="materialTypes[]" value="Thesis" <?php echo in_array('Thesis', $materialTypes) ? 'checked' : ''; ?>>
          <label for="thesis">Thesis</label><br>
          
           <!-- Button Container -->
        <div class="button-container">
            <!-- GO Button -->
            <button type="submit" class="go-btn">GO</button>
        </div>
                <!-- Upload Button -->
         <div class="button-container">
            <button type="button" class="upload-btn" onclick="window.location.href='lecturematerialupload.php'">Upload New Material</button>
        </div>
</div>
</form> 

        <div class="content">
        <h2>MATERIAL SEARCH</h2>
        <!-- Search Bar -->
            <div class="search-bar">
                <form action="lecturematerialsearch.php" method="POST">
                    <input type="text" name="searchQuery" placeholder="Search materials..." value="<?php echo isset($_POST['searchQuery']) ? htmlspecialchars($_POST['searchQuery']) : ''; ?>">
                    <button type="submit" class="search-btn">
                        <img src="image/search_icon.png" alt="Search" />
                    </button>
                </form>
            </div>

        <div class="material-list">
            <?php
            if (isset($noResultsMessage)) {
                echo '<p>' . $noResultsMessage . '</p>';
            } else {
                if (!empty($materials)) {
                    foreach ($materials as $material) {
                        echo '<div class="material-item">';
                        echo '<p>' . $material['file_name'] . ' (' . $material['type'] . ' - ' . $material['uploaded_date'] . ')</p>';
                        echo '<div class="buttons">';
                        // Add Edit button
                        echo '<a href="edit_material.php?id=' . $material['id'] . '"><button class="edit-btn">EDIT</button></a>';
                        // Modify the "Download" button to allow downloading the file
                        echo '<a href="' . $material['file_path'] . '" download><button class="download-btn">DOWNLOAD</button></a>';
                        // Modify the "Delete" button to allow downloading the file
                        echo '<form action="delete_material.php" method="POST" style="display:inline-block;">
                        <input type="hidden" name="material_id" value="' . $material['id'] . '">
                        <button type="submit" class="delete-btn" onclick="return confirm(\'Are you sure you want to delete this material?\')">DELETE</button>
                    </form>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>No materials found matching your criteria.</p>';
                }
            }
            ?>
        </div>
    </div>
  </div>


  <script>
  // Toggle sidebar visibility
  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('active');
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