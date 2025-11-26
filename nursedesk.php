<?php
session_start();

// Redirect if not a nurse
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'nurse') {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "phpuser", "jerry@006", "mycloud");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT * FROM nurse WHERE uploaded_by = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$files = [];
while ($row = $result->fetch_assoc()) {
    $files[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Nurse Desk - MediVault</title>
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
            padding: 40px;
            color: #333;
            min-height: 100vh;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 24px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        h1 {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            text-align: center;
            margin: 0;
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
        
        h2 {
            color: var(--dark-color);
            margin-top: 0;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 32px;
            padding: 0 40px;
            border-bottom: 2px solid var(--border-color);
            padding-bottom: 16px;
            margin-top: 40px;
        }
        
        form {
            margin: 40px;
            padding: 40px;
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
            margin: 20px 0 8px 0;
            font-size: 14px;
        }
        
        input[type="file"],
        select {
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
        
        input[type="file"]:focus, 
        select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        input[type="submit"] {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            display: block;
            width: 100%;
            font-weight: 600;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
            margin-top: 24px;
        }
        
        input[type="submit"]:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 25px rgba(59, 130, 246, 0.3);
        }
        
        .file-list {
            margin: 40px;
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
        
        .welcome {
            text-align: center;
            font-size: 18px;
            color: rgba(31, 41, 55, 0.8);
            margin: 40px;
            padding: 30px;
            background: var(--light-color);
            border-radius: 16px;
            font-weight: 500;
        }
        
        @media (max-width: 768px) {
            body {
                padding: 20px;
            }
            
            .container {
                border-radius: 20px;
            }
            
            h1 {
                font-size: 2rem;
                padding: 40px 20px;
            }
            
            h2 {
                font-size: 1.5rem;
                padding: 0 24px;
                margin-top: 32px;
            }
            
            form, .file-list, .welcome {
                margin: 24px;
                padding: 24px;
            }
        }
        
        @media (max-width: 480px) {
            h1 {
                font-size: 1.75rem;
                padding: 32px 16px;
            }
            
            form, .file-list, .welcome {
                margin: 16px;
                padding: 20px;
            }
            
            .file-item {
                flex-direction: column;
                gap: 12px;
                text-align: center;
            }
        }
    </style>
</head>
<body>
  <div class="container">
    <h1><i class="fas fa-user-nurse"></i> Nurse Desk - MediVault</h1>
    <div class="welcome">Welcome, <?php echo htmlspecialchars($username); ?> 👩‍⚕️</div>

    <h2>Upload a File</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label for="fileToUpload">Select file:</label>
        <input type="file" name="fileToUpload" id="fileToUpload" required>

        <label for="category">Category:</label>
        <select name="category" id="category">
            <option value="general">General</option>
            <option value="confidential">Confidential</option>
            <option value="health-data">Health Data</option>
        </select>

        <input type="submit" value="Upload File">
    </form>

    <h2>Available Files</h2>
    <div class="file-list">
      <?php if (empty($files)): ?>
        <div class="file-item">No files uploaded yet.</div>
      <?php else: ?>
        <?php foreach ($files as $file): ?>
          <div class="file-item">
            <i class="fas fa-file-alt"></i> <?php echo htmlspecialchars($file['filename']); ?>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
