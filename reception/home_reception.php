<?php
session_start();

if (isset($_SESSION['username']) && $_SESSION['user_type'] === 'reception') {
    include("home_reception.html");
} else {
    header("Location: login.php");
    exit();
}
?>
