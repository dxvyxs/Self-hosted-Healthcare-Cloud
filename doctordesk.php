<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'doctor') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
$conn = new mysqli("localhost", "phpuser", "jerry@006", "mycloud");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->prepare("SELECT * FROM doctor WHERE uploaded_by = ? ORDER BY upload_date DESC");
$result->bind_param("s", $username);
$result->execute();
$files = $result->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>DoctorDesk - Secure File Storage</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
    
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    :root {
        --primary-color: #3b82f6;
        --primary-dark: #1d4ed8;
        --secondary-color: #6b7280;
        --success-color: #15803d;
        --danger-color: #dc2626;
        --light-color: #f9fafb;
        --dark-color: #1f2937;
        --border-color: #e5e7eb;
        --shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
        --shadow-sm: 0 8px 25px rgba(59, 130, 246, 0.1);
    }
    
    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        margin: 0;
        padding: 20px;
        color: #333;
        min-height: 100vh;
    }
    
    .container {
        max-width: 1200px;
        margin: 0 auto;
        background: white;
        border-radius: 24px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }
    
    header {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        text-align: center;
        padding: 60px 50px;
        margin-bottom: 0;
        border-bottom: none;
        position: relative;
        overflow: hidden;
    }
    
    header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
        opacity: 0.3;
    }
    
    header * {
        position: relative;
        z-index: 2;
    }
    
    h1 {
        color: white;
        margin: 0;
        font-size: 3rem;
        font-weight: 800;
        letter-spacing: -1px;
        margin-bottom: 12px;
    }
    
    header p {
        color: rgba(255, 255, 255, 0.9);
        font-size: 18px;
        font-weight: 500;
    }
    
    h2 {
        color: var(--dark-color);
        margin-top: 0;
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 32px;
    }
    
    .card {
        background: var(--light-color);
        border-radius: 20px;
        padding: 50px;
        margin: 40px;
        box-shadow: none;
        border: 2px solid var(--border-color);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }
    
    .card:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-sm);
        transform: translateY(-2px);
    }
    
    .form-group {
        margin-bottom: 24px;
    }
    
    label {
        font-weight: 600;
        display: block;
        margin-bottom: 8px;
        color: #374151;
        font-size: 14px;
    }
    
    input[type="file"], select {
        width: 100%;
        height: 52px;
        padding: 0 16px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 16px;
        color: var(--dark-color);
        background: white;
        transition: all 0.2s ease;
        outline: none;
        font-family: 'Inter', sans-serif;
    }
    
    input[type="file"]:focus, select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .btn {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        border: none;
        padding: 16px 24px;
        border-radius: 12px;
        font-size: 16px;
        cursor: pointer;
        display: inline-block;
        font-weight: 600;
        transition: all 0.2s ease;
        text-decoration: none;
        font-family: 'Inter', sans-serif;
    }
    
    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 12px 25px rgba(59, 130, 246, 0.3);
    }
    
    .btn-block {
        display: block;
        width: 100%;
    }
    
    .file-list {
        margin-top: 0;
    }
    
    .file-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        background: white;
        border: 2px solid var(--border-color);
        border-radius: 16px;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }
    
    .file-item:hover {
        border-color: var(--primary-color);
        box-shadow: var(--shadow-sm);
        transform: translateY(-1px);
    }
    
    .file-item:last-child {
        margin-bottom: 0;
    }
    
    .file-name {
        font-weight: 600;
        color: var(--dark-color);
        font-size: 16px;
    }
    
    .file-category {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .category-general {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #dbeafe;
    }
    
    .category-confidential {
        background: #fef2f2;
        color: var(--danger-color);
        border: 1px solid #fecaca;
    }
    
    .category-health-data {
        background: #f0fdf4;
        color: var(--success-color);
        border: 1px solid #bbf7d0;
    }
    
    .empty-state {
        text-align: center;
        padding: 60px 40px;
        color: var(--secondary-color);
    }
    
    .empty-state::before {
        content: '📁';
        font-size: 64px;
        display: block;
        margin-bottom: 24px;
        opacity: 0.5;
    }
    
    @media (max-width: 768px) {
        body {
            padding: 16px;
        }
        
        .container {
            margin: 0;
            border-radius: 20px;
        }
        
        header {
            padding: 40px 24px;
        }
        
        h1 {
            font-size: 2.5rem;
        }
        
        .card {
            margin: 24px;
            padding: 32px;
        }
        
        h2 {
            font-size: 1.5rem;
        }
        
        .file-item {
            flex-direction: column;
            gap: 16px;
            text-align: center;
        }
    }
    
    @media (max-width: 480px) {
        header {
            padding: 32px 20px;
        }
        
        h1 {
            font-size: 2rem;
        }
        
        .card {
            margin: 16px;
            padding: 24px;
        }
    }
</style>
</head>
<body>
<div class="container">
  <header>
    <h1>Welcome to DoctorDesk</h1>
    <p>Secure file storage for medical professionals</p>
  </header>

  <div class="card">
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
      <button type="submit" class="btn btn-block">Upload File</button>
    </form>
  </div>

  <div class="card">
    <h2>Available Files</h2>
    <div class="file-list">
      <?php
      if ($files && $files->num_rows > 0) {
          while ($row = $files->fetch_assoc()) {
              $category = $row['category'] ?? 'general';
              $categoryClass = 'category-' . str_replace(' ', '-', strtolower($category));
              $filename = htmlspecialchars($row['filename']);
              $filePath = 'uploads/' . $filename;

              echo '<div class="file-item">';
              echo '<span class="file-name">' . $filename . '</span>';
              echo '<span class="file-category ' . $categoryClass . '">' . htmlspecialchars($category) . '</span>';
              echo '<a href="' . $filePath . '" download class="btn">Download</a>';
              echo '</div>';
          }
      } else {
          echo '<div class="empty-state">';
          echo '<i>📁</i>';
          echo '<p>No files available. Upload your first file to get started.</p>';
          echo '</div>';
      }

      $result->close();
      $conn->close();
      ?>
    </div>
  </div>
</div>
</body>
</html>
