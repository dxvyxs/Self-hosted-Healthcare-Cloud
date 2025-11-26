<?php
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'receptionist') {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Receptionist Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden;
            padding: 30px;
        }
        
        h1 {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
            margin: -30px -30px 30px -30px;
            font-size: 2.5rem;
            font-weight: 800;
            letter-spacing: -1px;
            padding: 50px 30px;
            position: relative;
            overflow: hidden;
        }
        
        h1::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            opacity: 0.3;
        }
        
        h1 * {
            position: relative;
            z-index: 2;
        }
        
        .welcome {
            text-align: center;
            margin-bottom: 30px;
            font-size: 18px;
            color: var(--secondary-color);
            padding: 25px;
            background: var(--light-color);
            border-radius: 16px;
            font-weight: 500;
            border: 2px solid var(--border-color);
        }
        
        form {
            margin-bottom: 30px;
            padding: 30px;
            background: var(--light-color);
            border-radius: 20px;
            border: 2px solid var(--border-color);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        form::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        }
        
        form:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-sm);
            transform: translateY(-2px);
        }
        
        label {
            font-weight: 600;
            color: #374151;
            display: block;
            margin: 12px 0 6px 0;
            font-size: 14px;
        }
        
        input[type="file"],
        select {
            width: 100%;
            height: 48px;
            padding: 10px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 16px;
            color: var(--dark-color);
            background: white;
            transition: all 0.2s ease;
            outline: none;
            font-family: 'Inter', sans-serif;
        }
        
        input[type="file"]:focus, 
        select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        input[type="submit"] {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            margin-top: 20px;
        }
        
        input[type="submit"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 25px rgba(59, 130, 246, 0.3);
        }
        
        .file-list {
            margin-top: 20px;
        }
        
        .file-item {
            background: white;
            padding: 20px;
            border: 2px solid var(--border-color);
            border-radius: 16px;
            margin-bottom: 16px;
            transition: all 0.2s ease;
            font-size: 16px;
        }
        
        .file-item:hover {
            border-color: var(--primary-color);
            box-shadow: var(--shadow-sm);
            transform: translateY(-1px);
        }
        
        .file-item:last-child {
            margin-bottom: 0;
        }
        
        .file-item i {
            margin-right: 12px;
            color: var(--primary-color);
            font-size: 18px;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 16px;
            }
            
            .container {
                border-radius: 20px;
                padding: 20px;
            }
            
            h1 {
                font-size: 2rem;
                padding: 40px 20px;
                margin: -20px -20px 25px -20px;
            }
            
            form {
                padding: 24px;
            }
        }
        
        @media (max-width: 480px) {
            h1 {
                font-size: 1.75rem;
                padding: 32px 16px;
            }
            
            form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
  <h1>Receptionist Dashboard</h1>
  <div class="welcome">Welcome, <?php echo htmlspecialchars($username); ?>!</div>

  <h2>Upload a File</h2>
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <label for="fileToUpload"><i class="fas fa-file-upload"></i> Choose File</label>
    <input type="file" name="fileToUpload" id="fileToUpload" required>

    <label for="category"><i class="fas fa-tags"></i> Category</label>
    <select name="category" id="category">
      <option value="general">General</option>
      <option value="confidential">Confidential</option>
      <option value="health-data">Health Data</option>
    </select>

    <input type="submit" value="Upload File">
  </form>

  <h2>Uploaded Files</h2>
  <div class="file-list">
    <?php
    $conn = new mysqli("localhost", "phpuser", "jerry@006", "mycloud");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM receptionist WHERE uploaded_by = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        echo '<div class="file-item"><i class="fas fa-file-medical"></i> ' . htmlspecialchars($row['filename']) . '</div>';
    }

    $stmt->close();
    $conn->close();
    ?>
  </div>
</div>

</body>
</html>
