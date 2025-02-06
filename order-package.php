<?php
// Initialize the session
session_start();

// Check if user is logged in
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: register.php?redirect=packages.php");
    exit;
}

// Include config file
require_once "includes/config.php";

// Redirect to confirmation page
if(isset($_POST['order_package']) && isset($_POST['package_id'])) {
    $package_id = $_POST['package_id'];
    header("Location: confirm-order.php?package_id=" . $package_id);
    exit();
}

header("Location: packages.php?error=1");
exit(); 