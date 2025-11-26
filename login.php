<?php
session_start();


$error = ""; // Initialize error

// Handle login when form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = "localhost";
    $db = "mycloud";
    $user = "phpuser";
    $pass = "jerry@006";

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT username, password, role FROM losers WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // SECURE password check using password_verify (ensure DB uses hashed passwords)
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = strtolower(trim($row['role']));

            // Redirect by role
            switch ($_SESSION['role']) {
                case 'doctor':
                    header("Location: doctordesk.php");
                    break;
                case 'nurse':
                    header("Location: nursedesk.php");
                    break;
                case 'receptionist':
                    header("Location: recptionistdesk.php");
                    break;
                case 'dharan':
                    header("Location: index.php");
                    break;
                default:
                    $error = "Unauthorized role.";
                    session_destroy();
            }
            exit;
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "User not found.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>MediVault Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-wrapper {
      display: grid;
      grid-template-columns: 1fr 1fr;
      width: 100%;
      max-width: 900px;
      min-height: 500px;
      background: white;
      border-radius: 24px;
      overflow: hidden;
      box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    }

    .branding-panel {
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: flex-start;
      position: relative;
      overflow: hidden;
    }

    .branding-panel::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
      opacity: 0.4;
    }

    .brand-content {
      position: relative;
      z-index: 2;
    }

    .logo-section {
      margin-bottom: 40px;
    }

    .logo-icon {
      width: 80px;
      height: 80px;
      background: rgba(255, 255, 255, 0.15);
      border-radius: 20px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 24px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .logo-icon i {
      font-size: 36px;
      color: white;
    }

    .brand-title {
      font-size: 36px;
      font-weight: 800;
      color: white;
      margin-bottom: 12px;
      letter-spacing: -1px;
    }

    .brand-description {
      color: rgba(255, 255, 255, 0.9);
      font-size: 18px;
      line-height: 1.6;
      margin-bottom: 32px;
    }

    .feature-list {
      list-style: none;
    }

    .feature-item {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-bottom: 16px;
      color: rgba(255, 255, 255, 0.9);
      font-size: 15px;
    }

    .feature-item i {
      color: rgba(255, 255, 255, 0.8);
      font-size: 14px;
    }

    .form-panel {
      padding: 60px 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .form-header {
      margin-bottom: 40px;
    }

    .form-title {
      font-size: 28px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 8px;
    }

    .form-subtitle {
      color: #6b7280;
      font-size: 16px;
    }

    .form-content {
      margin-bottom: 32px;
    }

    .input-group {
      margin-bottom: 24px;
    }

    .input-label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: #374151;
      margin-bottom: 8px;
    }

    .input-field {
      width: 100%;
      height: 52px;
      padding: 0 16px;
      border: 2px solid #e5e7eb;
      border-radius: 12px;
      font-size: 16px;
      color: #1f2937;
      background: #f9fafb;
      transition: all 0.2s ease;
      outline: none;
    }

    .input-field:focus {
      border-color: #3b82f6;
      background: white;
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .input-field::placeholder {
      color: #9ca3af;
    }

    .submit-button {
      width: 100%;
      height: 52px;
      background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
      border: none;
      border-radius: 12px;
      color: white;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      margin-bottom: 24px;
    }

    .submit-button:hover {
      transform: translateY(-1px);
      box-shadow: 0 12px 25px rgba(59, 130, 246, 0.3);
    }

    .error-alert {
      background: #fef2f2;
      border: 1px solid #fecaca;
      border-radius: 8px;
      padding: 16px;
      margin-bottom: 24px;
      color: #dc2626;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .form-footer {
      text-align: center;
      padding-top: 24px;
      border-top: 1px solid #f3f4f6;
    }

    .help-text {
      color: #6b7280;
      font-size: 14px;
      margin-bottom: 12px;
    }

    .help-link {
      color: #3b82f6;
      text-decoration: none;
      font-weight: 500;
      font-size: 14px;
    }

    .help-link:hover {
      color: #1d4ed8;
    }

    .security-info {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 16px;
      margin-top: 20px;
      flex-wrap: wrap;
    }

    .security-badge {
      background: #eff6ff;
      color: #1e40af;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    @media (max-width: 768px) {
      .login-wrapper {
        grid-template-columns: 1fr;
        max-width: 400px;
      }
      
      .branding-panel {
        padding: 40px 30px;
        text-align: center;
      }
      
      .brand-content {
        align-items: center;
      }
      
      .form-panel {
        padding: 40px 30px;
      }
      
      .brand-title {
        font-size: 28px;
      }
      
      .form-title {
        font-size: 24px;
      }
    }

    @media (max-width: 480px) {
      body {
        padding: 16px;
      }
      
      .branding-panel {
        padding: 32px 24px;
      }
      
      .form-panel {
        padding: 32px 24px;
      }
    }
  </style>
</head>

<body>
  <div class="login-wrapper">
    <div class="branding-panel">
      <div class="brand-content">
        <div class="logo-section">
          <div class="logo-icon">
            <i class="fas fa-heartbeat"></i>
          </div>
          <h1 class="brand-title">MediVault</h1>
          <p class="brand-description">
            Secure healthcare data management platform trusted by medical professionals worldwide.
          </p>
        </div>
        
        <ul class="feature-list">
          <li class="feature-item">
            <i class="fas fa-shield-alt"></i>
            <span>Role-Based Access Management</span>
          </li>
          <li class="feature-item">
            <i class="fas fa-clock"></i>
            <span>24/7 system availability</span>
          </li>
          <li class="feature-item">
            <i class="fas fa-users-cog"></i>
            <span>Zero-Trust Access Network</span>
          </li>
          <li class="feature-item">
            <i class="fas fa-chart-line"></i>
            <span>Offline and local system</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="form-panel">
      <div class="form-header">
        <h2 class="form-title">Sign In</h2>
        <p class="form-subtitle">Access your secure healthcare dashboard</p>
      </div>

      <?php if (!empty($error)) echo "<div class='error-alert'><i class='fas fa-exclamation-triangle'></i>$error</div>"; ?>

      <form method="post" class="form-content">
        <div class="input-group">
          <label for="username" class="input-label">Username</label>
          <input 
            type="text" 
            id="username" 
            name="username" 
            placeholder="Enter your username" 
            required 
            class="input-field"
            autocomplete="username">
        </div>

        <div class="input-group">
          <label for="password" class="input-label">Password</label>
          <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="Enter your password" 
            required 
            class="input-field"
            autocomplete="current-password">
        </div>

        <button type="submit" class="submit-button">
          Sign In to Dashboard
        </button>
      </form>

      <div class="form-footer">
        <p class="help-text">Having trouble accessing your account?</p>
        <a href="#" class="help-link">Contact IT Support</a>
        
       
      </div>
    </div>
  </div>
</body>
</html>
