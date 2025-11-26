<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "phpuser", "jerry@006", "mycloud");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$users = [
    ["username" => "doctor", "password" => "doc123", "role" => "doctor"],
    ["username" => "nurse", "password" => "nurse123", "role" => "nurse"],
    ["username" => "receptionist", "password" => "rec123", "role" => "receptionist"],
];

foreach ($users as $user) {
    $hashed = password_hash($user['password'], PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO losers (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user['username'], $hashed, $user['role']);
    $stmt->execute();
    echo "Inserted user: " . $user['username'] . "<br>";
    $stmt->close();
}

$conn->close();
?>
