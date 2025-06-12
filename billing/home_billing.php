<?php
session_start();

if (isset($_SESSION['username']) && $_SESSION['user_type'] === 'billing') {
    include("home_billing.html");
} else {
    header("Location: login.php");
    exit();
}
?>
