<!DOCTYPE html>
<html>
<head>
    <title>My Cloud</title>
</head>
<body>
    <h1>Welcome to receptionistdesk</h1>

    <h2>Upload a File</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label>Select file to upload:</label><br>
        <input type="file" name="fileToUpload" required><br><br>

        <label>Category:</label><br>
        <select name="category">
            <option value="general">General</option>
            <option value="confidential">Confidential</option>
            <option value="health-data">Health Data</option>
        </select><br><br>

        <input type="submit" value="Upload File">
    </form>

    <h2>Available Files</h2>
    <?php
    $conn = new mysqli("localhost", "phpuser", "jerry@006", "mycloud");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_SESSION['username'];
    $result = $conn->query("SELECT * FROM files WHERE uploaded_by = '$username'");

    while ($row = $result->fetch_assoc()) {
      echo $row['filename'] . "<br>";
    }
    $conn->close();
    ?>
</body>
</html>
