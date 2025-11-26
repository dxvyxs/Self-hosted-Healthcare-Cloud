<?php
session_start();

if (!isset($_SESSION['role'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] === 'doctor') {
    header("Location: doctordesk.php");
    exit;
} elseif ($_SESSION['role'] === 'nurse') {
    header("Location: nursedesk.php");
    exit;
} elseif ($_SESSION['role'] === 'receptionist') {
    header("Location: receptionistdesk.php");
    exit;
} elseif ($_SESSION['role'] !== 'admin') {
    echo "Unauthorized role.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cloud - Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #2c3e50;
            margin-top: 0;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        h2 {
            color: #3498db;
            margin-top: 30px;
        }
        .upload-form {
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 6px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        input[type="file"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        .file-section {
            margin-top: 30px;
        }
        .file-dropdown {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            margin-top: 10px;
        }
        .user-info {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            text-align: right;
        }
        .logout-link {
            color: #e74c3c;
            text-decoration: none;
        }
        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome Admin</h1>

        <div class="upload-form">
            <h2>Upload a File</h2>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="fileToUpload">Select file to upload:</label>
                    <input type="file" name="fileToUpload" id="fileToUpload" required>
                </div>

                <div class="form-group">
                    <label for="category">Category:</label>
                    <select name="category" id="category">
                        <option value="general">General</option>
                        <option value="confidential">Confidential</option>
                        <option value="health-data">Health Data</option>
                    </select>
                </div>

                <input type="submit" value="Upload File">
            </form>
        </div>

        <div class="file-section">
            <h2>Available Files</h2>
            <select class="file-dropdown" size="5">
                <?php
                $conn = new mysqli("localhost", "phpuser", "jerry@006", "mycloud");
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $result = $conn->query("SELECT * FROM files ORDER BY filename ASC");

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['filename']) . '">' . 
                             htmlspecialchars($row['filename']) . ' (' . htmlspecialchars($row['category']) . ')</option>';
                    }
                } else {
                    echo '<option>No files available</option>';
                }
                $conn->close();
                ?>
            </select>
        </div>

        <div class="user-info">
            Welcome, <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Guest'; ?> | 
            <a href="logout.php" class="logout-link">Logout</a>
        </div>
    </div>
</body>
</html>
