<?php
session_start();

function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function isDoctor() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'doctor';
}
function isNurse() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'nurse';
}

function isReceptionist() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'receptionist';
}
?>
