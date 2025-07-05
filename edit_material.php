<?php
session_start();
include 'db.php';  // Include the database connection file

// Check if the material ID is passed in the URL
if (isset($_GET['id'])) {
    $materialId = $_GET['id'];

    // Fetch the material details from the database
    $stmt = $conn->prepare("SELECT * FROM material_uploaded WHERE id = ?");
    $stmt->bind_param("i", $materialId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $material = $result->fetch_assoc();
    } else {
        echo "Material not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

// Handle form submission to update material
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $newFileName = $_POST['fileName'];
    $newMaterialType = $_POST['materialType'];
    $newYear = $_POST['year'];

    // Update material details in the database
    $stmt = $conn->prepare("UPDATE material_uploaded SET file_name = ?, type = ?, year = ? WHERE id = ?");
    $stmt->bind_param("ssii", $newFileName, $newMaterialType, $newYear, $materialId);

    if ($stmt->execute()) {
        $_SESSION['upload_message'] = "Material updated successfully!";
        header("Location: lecturematerialsearch.php");
        exit;
    } else {
        echo "Error updating material.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Material</title>
  <link rel="stylesheet" href="lecturematerialsearch.css" /> <!-- Use your existing CSS -->
  <style>
    /* Sidebar styles */
    .sidebar {
        font-size: 23px;
        width: 250px;
        background-color: #f4f4f4;
        padding: 20px;
        position: fixed;
        top: 70px;
        bottom: 0;
        left: 0;
        z-index: 999;
        transform: translateX(-100%); 
        transition: transform 0.3s ease;
    }

    .sidebar.active {
        transform: translateX(0); /* Show when active */
    }

    .sidebar ul {
        list-style: none;
        padding: 0;
    }

    .sidebar ul li {
        margin-bottom: 20px;
    }

    .sidebar p {
        padding-left: 25%;
        padding-bottom: 10%;
        padding-top: 10%;
        text-decoration: none;
        color: #333;
        font-weight: bold;
    }

    .sidebar ul li a {
        text-decoration: none;
        color: #333;
        font-weight: bold;
    }

    .sidebar ul li a:hover {
        color: #3b75d2;
    }

    .hamburger {
        font-size: 30px;
        cursor: pointer;
        color: white;
    }

    /* Content layout */
    .content-wrapper {
        display: flex;
        justify-content: center;
        padding: 20px;
        transition: margin-left 0.3s ease;
    }

    .content {
        width: 100%;
        max-width: 800px;
    }

    .form-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .form-container label {
        display: block;
        margin-bottom: 8px;
    }

    .form-container input, .form-container select {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .form-container button {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .form-container button:hover {
        background-color: #218838;
    }

  </style>
</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <div class="hamburger" onclick="toggleSidebar()" aria-label="Toggle Sidebar Menu">☰</div>
    <div class="right-nav">
      <div class="logout"><a href="logout_action.php">Log Out</a></div>
      <div class="logo">
        <a href="dashboard.php"><img src="image/logo.png" alt="StudyMate Logo" class="logo"></a>
      </div>
    </div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <p><a href="dashboard.php">DASHBOARD</a></p>
    <ul>
        <li><a href="profile.php">● Profile</a></li>
        <li><a href="announcementforum.php">● Announcement</a></li>
        <li><a href="discussionforum.php">● Forum Discussion</a></li>
        <li><a href="lecturematerialsearch.php">● Material Search</a></li>
        <li><a href="helpsupport.php">● Help & Support</a></li>
    </ul>
  </div>

  <!-- Content Wrapper -->
  <div class="content-wrapper" id="content-wrapper">
    <div class="content">
      <h2>Edit Material</h2>
    
      <div class="form-container">
        <form action="edit_material.php?id=<?php echo $material['id']; ?>" method="POST">
            <label for="fileName">File Name:</label>
            <input type="text" name="fileName" value="<?php echo $material['file_name']; ?>" required><br><br>
            
            <label for="materialType">Material Type:</label>
            <select name="materialType" required>
                <option value="Academic Blogs" <?php echo $material['type'] == 'Academic Blogs' ? 'selected' : ''; ?>>Academic Blogs</option>
                <option value="Articles" <?php echo $material['type'] == 'Articles' ? 'selected' : ''; ?>>Articles</option>
                <option value="Books" <?php echo $material['type'] == 'Books' ? 'selected' : ''; ?>>Books</option>
                <option value="Journals" <?php echo $material['type'] == 'Journals' ? 'selected' : ''; ?>>Journals</option>
                <option value="Lecture Notes" <?php echo $material['type'] == 'Lecture Notes' ? 'selected' : ''; ?>>Lecture Notes</option>
                <option value="Past Exam" <?php echo $material['type'] == 'Past Exam' ? 'selected' : ''; ?>>Past Exam</option>
                <option value="Thesis" <?php echo $material['type'] == 'Thesis' ? 'selected' : ''; ?>>Thesis</option>
            </select><br><br>

            <label for="year">Year:</label>
            <input type="number" name="year" value="<?php echo $material['year']; ?>" required><br><br>
            
            <button type="submit">Update</button>
        </form>
      </div>
    </div>
  </div>

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

  <!-- JavaScript to toggle sidebar visibility -->
  <script>
    function toggleSidebar() {
      const sidebar = document.getElementById("sidebar");
      const contentWrapper = document.getElementById("content-wrapper");
      sidebar.classList.toggle("active");
      contentWrapper.style.marginLeft = sidebar.classList.contains("active") ? "250px" : "0"; // Shift content when sidebar is open
    }
  </script>

</body>
</html>
