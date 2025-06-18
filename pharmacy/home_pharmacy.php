<?php
session_start();

if (isset($_SESSION['username']) && $_SESSION['user_type'] === 'pharmacy') {
    include("home_pharmacy.html");
} else {
    header("Location: login.php");
    exit();
}
?>
