<?php
session_start();

if (isset($_SESSION['username']) && $_SESSION['user_type'] === 'lab') {
    include("home_lab.html");
} else {
    header("Location: login.php");
    exit();
}
?>
