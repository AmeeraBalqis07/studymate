<?php
session_start();
include 'db.php';  // Include the database connection file

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted and the file is uploaded
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['fileInput'])) {
    // Get file details
    $fileName = $_FILES['fileInput']['name'];
    $fileTmpName = $_FILES['fileInput']['tmp_name'];
    $fileSize = $_FILES['fileInput']['size'];
    $fileError = $_FILES['fileInput']['error'];
    $fileType = $_FILES['fileInput']['type'];

    // Check for upload errors
    if ($fileError !== 0) {
        echo 'File upload error: ' . $fileError;
        exit;
    }

    // Check if the file is empty (no file selected)
    if (empty($fileName)) {
        echo "No file selected. Please select a file.";
        exit;
    }

    // Generate a unique name for the file using timestamp and random string
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    $newFileName = uniqid('file_') . '_' . time() . '.' . $fileExtension;

    // Set the directory where files will be uploaded
    $uploadDirectory = 'material_uploaded/';
    $fileDestination = $uploadDirectory . $newFileName;

    // Create the directory if it doesn't exist
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    // Move the uploaded file to the new directory with the new name
    if (move_uploaded_file($fileTmpName, $fileDestination)) {
        // Store file info in the database
        $userId = $_SESSION['user_id'];  // Get the user ID from session (assuming it's set)

        // Insert file details into the database (updated table name)
        $stmt = $conn->prepare("INSERT INTO material_uploaded (file_name, file_path, uploaded_by, uploaded_date, type, year) VALUES (?, ?, ?, NOW(), ?, ?)");
        $stmt->bind_param("ssisi", $newFileName, $fileDestination, $userId, $_POST['materialType'], $_POST['year']);

        if ($stmt->execute()) {
            // Set a session variable to indicate the upload was successful
            $_SESSION['upload_message'] = "File uploaded successfully!";
        } else {
            echo "Error uploading file to database: " . $stmt->error;
        }
    } else {
        echo "Error moving file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Material Upload</title>
  <link rel="stylesheet" href="lecturematerialupload.css" />
  <style>
    /* Pop-up notification style */
    .popup-notification {
        position: fixed;
        top: 10px;
        right: 10px;
        background-color: #28a745;
        color: white;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        display: none;
        z-index: 9999;
    }

    /* Image Preview */
    .image-preview {
        margin-top: 10px;
        max-width: 100%;
        max-height: 200px;
        display: block;
    }

    .file-name {
        font-size: 16px;
        margin-top: 10px;
    }
  </style>
  <script>
    // Show a notification if the session has a success message
    window.onload = function() {
      <?php if (isset($_SESSION['upload_message'])): ?>
        // Show the pop-up notification
        const notification = document.createElement('div');
        notification.classList.add('popup-notification');
        notification.innerHTML = '<?php echo $_SESSION['upload_message']; ?>';
        document.body.appendChild(notification);

        // Show the notification
        notification.style.display = 'block';

        // Hide the notification after 5 seconds
        setTimeout(function() {
          notification.style.display = 'none';
        }, 5000);

        // Clear the session message
        <?php unset($_SESSION['upload_message']); ?>
      <?php endif; ?>
    }

    // Display file name or image preview
    function previewFile() {
      const fileInput = document.getElementById('fileInput');
      const fileNameDisplay = document.getElementById('fileName');
      const previewImage = document.getElementById('previewImage');
      
      const file = fileInput.files[0];

      // Display the file name
      fileNameDisplay.textContent = file.name;

      // If the file is an image, show a preview
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
          previewImage.src = e.target.result;
          previewImage.style.display = 'block';
        };
        
        reader.readAsDataURL(file);
      } else {
        previewImage.style.display = 'none';
      }
    }
  </script>
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
      <li><a href="announcementforum.php">● Announcement</a></li>
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
            } elseif ($_SESSION['role'] == 'lecturer') {
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

  <!-- Upload Section -->
  <div class="main-container">
    <div class="upload-container">
      <h2>Upload your file</h2>
      <form action="lecturematerialupload.php" method="POST" enctype="multipart/form-data">
        <div class="upload-box">
          <input type="file" name="fileInput" id="fileInput" class="file-input" onchange="previewFile()" />
          <label for="fileInput" class="upload-box-content">
            <span class="cloud-icon">☁️</span>
            <p>Drag & Drop your files or <span class="browse">Browse</span></p>
          </label>
        </div>

        <div id="fileName" class="file-name"></div>
        <img id="previewImage" class="image-preview" src="" alt="Image Preview" style="display: none;" />
        
        <label for="materialType">Material Type:</label>
        <select name="materialType" id="materialType">
          <option value="Academic Blogs">Academic Blogs</option>
          <option value="Articles">Articles</option>
          <option value="Books">Books</option>
          <option value="Journals">Journals</option>
          <option value="Lecture Notes">Lecture Notes</option>
          <option value="Past Exam">Past Exam</option>
          <option value="Thesis">Thesis</option>
        </select>
<br>
        <label for="year">Year:</label>
        <input type="number" name="year" id="year" placeholder="YYYY" required>

        <button type="submit" class="upload-btn">Upload</button>
      </form>
      <p class="file-info">Supported formats: PNG, JPG, PDF. Maximum size: 25MB</p>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      sidebar.classList.toggle("active");
    }
  </script>

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
