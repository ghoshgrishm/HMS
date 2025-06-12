<?php
session_start();

if (isset($_SESSION['username']) && $_SESSION['user_type'] === 'nurse') {
    include("home_nurse.html");
} else {
    header("Location: login.php");
    exit();
}
?>
