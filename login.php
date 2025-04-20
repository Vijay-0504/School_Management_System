<?php
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple authentication (replace with secure logic)
    if ($username == 'admin' && $password == 'admin123') {
        $_SESSION['user'] = 'admin';
        header("Location: admin/home.php");
    } else {
        echo "Invalid credentials!";
    }
}
?>