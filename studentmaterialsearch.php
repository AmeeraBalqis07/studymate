<?php
// studentmaterialsearch.php
session_start();

// Check if user is logged in and has the correct role
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    // Redirect to login page if not a student or not logged in
    header("Location: login.php");
    exit();
}

// Include database connection file
include('db.php');

// Get the search parameters from the form (if available)
$fromYear = isset($_POST['fromYear']) ? $_POST['fromYear'] : '';
$toYear = isset($_POST['toYear']) ? $_POST['toYear'] : '';
$materialTypes = isset($_POST['materialTypes']) ? $_POST['materialTypes'] : [];
$searchQuery = isset($_POST['searchQuery']) ? $_POST['searchQuery'] : ''; // Handle search query

$query = "SELECT * FROM material_uploaded WHERE 1=1";

// Apply filters to the query
if ($fromYear) {
    $query .= " AND year >= $fromYear";
}

if ($toYear) {
    $query .= " AND year <= $toYear";
}

if (count($materialTypes) > 0) {
    $types = implode("', '", $materialTypes);
    $query .= " AND type IN ('$types')";
}

// Apply search filter if a search query is entered
if ($searchQuery) {
    $searchQuery = $conn->real_escape_string($searchQuery); // Prevent SQL injection
    $query .= " AND (file_name LIKE '%$searchQuery%' OR type LIKE '%$searchQuery%')";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Material Search</title>
  <link rel="stylesheet" href="studentmaterialsearch.css" />
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

    <!-- Filter Section -->
 <!-- Main Content -->
  <div class="main-container">
    <div class="filter">
        <h3>Year</h3>
        <form method="POST" action="studentmaterialsearch.php">
            <label for="from">From:</label>
            <input type="number" id="from" name="fromYear" placeholder="YYYY" value="<?php echo $fromYear; ?>"><br>
            <label for="to">To:</label>
            <input type="number" id="to" name="toYear" placeholder="YYYY" value="<?php echo $toYear; ?>"><br>
            <h3>Material Type</h3>
            <input type="checkbox" name="materialTypes[]" value="Academic Blogs" <?php echo in_array('Academic Blogs', $materialTypes) ? 'checked' : ''; ?>><label for="academicBlogs">Academic Blogs</label><br>
            <input type="checkbox" name="materialTypes[]" value="Articles" <?php echo in_array('Articles', $materialTypes) ? 'checked' : ''; ?>><label for="articles">Articles</label><br>
            <input type="checkbox" name="materialTypes[]" value="Books" <?php echo in_array('Books', $materialTypes) ? 'checked' : ''; ?>><label for="books">Books</label><br>
            <input type="checkbox" name="materialTypes[]" value="Journals" <?php echo in_array('Journals', $materialTypes) ? 'checked' : ''; ?>><label for="journals">Journals</label><br>
            <input type="checkbox" name="materialTypes[]" value="Lecture Notes" <?php echo in_array('Lecture Notes', $materialTypes) ? 'checked' : ''; ?>><label for="lectureNotes">Lecture Notes</label><br>
            <input type="checkbox" name="materialTypes[]" value="Past Exam" <?php echo in_array('Past Exam', $materialTypes) ? 'checked' : ''; ?>><label for="pastExam">Past Exam</label><br>
            <input type="checkbox" name="materialTypes[]" value="Thesis" <?php echo in_array('Thesis', $materialTypes) ? 'checked' : ''; ?>><label for="thesis">Thesis</label><br>
            <button type="submit" class="go-btn">GO</button>
        </form>
    </div>

      <div class="content">   
      <h2>MATERIAL SEARCH</h2>
      <!-- Search Bar -->
<div class="search-bar">
    <form method="POST" action="studentmaterialsearch.php">
        <input type="text" name="searchQuery" placeholder="Search materials..." value="<?php echo isset($_POST['searchQuery']) ? htmlspecialchars($_POST['searchQuery']) : ''; ?>">
        <button type="submit" class="search-btn">
            <img src="image/search_icon.png" alt="Search" />
        </button>
    </form>
</div>

        <div class="material-list">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo '<div class="material-item">
                            <p>' . $row['file_name'] . ' (' . $row['type'] . ', ' . $row['year'] . ')</p>
                            <a href="' . $row['file_path'] . '" download>
                                <button class="download-btn">DOWNLOAD</button>
                            </a>
                          </div>';
                }
            } else {
                echo '<p>No materials found for the selected filters.</p>';
            }
            ?>
        </div>
    </div> 
  </div>
<br>


  <script>
    // JavaScript to toggle sidebar visibility
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      const mainContent = document.querySelector('.main-content');

      sidebar.classList.toggle("active"); // Toggle sidebar visibility
    }

// Function to handle download action using Blob
function downloadFile(filename, content) {
  const blob = new Blob([content], { type: 'application/pdf' }); // Adjust MIME type
  const url = URL.createObjectURL(blob);

  const link = document.createElement('a');
  link.href = url;
  link.download = filename; // Set the download filename
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);

  // Cleanup the URL object after download
  URL.revokeObjectURL(url);
}

// Function to handle the "GO" button click and filter materials
document.addEventListener('DOMContentLoaded', function() {
  const goBtn = document.querySelector('.go-btn');
  
  goBtn.addEventListener('click', function() {
    const fromYear = document.getElementById('from').value;
    const toYear = document.getElementById('to').value;

    const materialTypes = [];
    if (document.getElementById('academicBlogs').checked) materialTypes.push('Academic Blogs');
    if (document.getElementById('articles').checked) materialTypes.push('Articles');
    if (document.getElementById('books').checked) materialTypes.push('Books');
    if (document.getElementById('journals').checked) materialTypes.push('Journals');
    if (document.getElementById('lectureNotes').checked) materialTypes.push('Lecture Notes');
    if (document.getElementById('pastExam').checked) materialTypes.push('Past Exam');
    if (document.getElementById('thesis').checked) materialTypes.push('Thesis');

    console.log('From Year: ', fromYear);
    console.log('To Year: ', toYear);
    console.log('Selected Material Types: ', materialTypes);

    // You can filter materials here based on the selected filters
    filterMaterials(fromYear, toYear, materialTypes);
  });
});

// Function to filter materials and update the material list
function filterMaterials(fromYear, toYear, materialTypes) {
  const materialList = document.querySelector('.material-list');
  materialList.innerHTML = ''; // Clear current list

  // Example materials (replace this with dynamic data from your server/database)
  const allMaterials = [
    { name: 'Material 1', year: 2021, type: 'Books' },
    { name: 'Material 2', year: 2023, type: 'Articles' },
    { name: 'Material 3', year: 2020, type: 'Lecture Notes' },
    { name: 'Material 4', year: 2022, type: 'Thesis' },
    { name: 'Material 5', year: 2021, type: 'Academic Blogs' },
  ];

  const filteredMaterials = allMaterials.filter(material => {
    const isInYearRange = (fromYear <= material.year && material.year <= toYear) || (!fromYear && !toYear);
    const isInMaterialType = materialTypes.length === 0 || materialTypes.includes(material.type);
    return isInYearRange && isInMaterialType;
  });

  // Add filtered materials to the list
  filteredMaterials.forEach(material => {
    const materialItem = document.createElement('div');
    materialItem.classList.add('material-item');
    materialItem.innerHTML = `
      <p>${material.name}</p>
      <button class="download-btn" onclick="downloadFile('${material.name}.pdf', 'This is the content for ${material.name}')">DOWNLOAD</button>
    `;
    materialList.appendChild(materialItem);
  });
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