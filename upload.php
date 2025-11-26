<?php
session_start();

if (!isset($_SESSION['username']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'];
$allowed_roles = ['doctor', 'dharan', 'nurse', 'receptionist'];

if (!in_array($role, $allowed_roles)) {
    echo "Unauthorized role";
    exit;
}

// Upload directory
$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $conn = new mysqli("localhost", "phpuser", "jerry@006", "mycloud");
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    $filename = basename($_FILES["fileToUpload"]["name"]);
    $filepath = $target_file;
    $category = $_POST["category"];
    $uploaded_by = $_SESSION["username"];

    $valid_tables = ['doctor', 'nurse', 'receptionist', 'dharan'];
    if (!in_array($role, $valid_tables)) {
        die("Invalid role table");
    }

    $stmt = $conn->prepare("INSERT INTO $role (filename, filepath, category, uploaded_by) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $filename, $filepath, $category, $uploaded_by);
    $stmt->execute();

    $stmt->close();
    $conn->close();

    echo "The file " . htmlspecialchars($filename) . " has been uploaded.";
    // Optionally redirect:
    // header("Location: dashboard.php");
} else {
    echo "Sorry, there was an error uploading your file.";
}
?>
