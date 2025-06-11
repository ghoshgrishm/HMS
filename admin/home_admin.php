<?php
session_start();

if (isset($_SESSION['username']) && $_SESSION['user_type'] === 'admin') {
    include("home_admin.html");
} else {
    header("Location: login.php");
    exit();
}
?>
