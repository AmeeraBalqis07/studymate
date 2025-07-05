<?php
session_start();
include 'db.php';  // Include database connection

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to delete a material.");
}

if (isset($_POST['material_id'])) {
    $material_id = $_POST['material_id'];
    
    // Fetch the file path from the database before deletion
    $sql = "SELECT file_path FROM material_uploaded WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $material_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $material = $result->fetch_assoc();

    if ($material) {
        $file_path = $material['file_path'];

        // Delete the file from the server (if it exists)
        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // Delete the material from the database
        $delete_sql = "DELETE FROM material_uploaded WHERE id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $material_id);
        if ($delete_stmt->execute()) {
            // Redirect back after successful deletion
            if ($_SESSION['role'] == 'admin') {
                // If admin, redirect to admin_materialmonitoring
                header("Location: admin_materialmonitoring.php");
            } else {
                // If lecturer, redirect to lecturematerialsearch
                header("Location: lecturematerialsearch.php");
            }
            exit();
        } else {
            echo "Error: " . $delete_stmt->error;
        }
    }
}
?>
