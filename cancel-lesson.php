<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "includes/config.php";

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['lesson_id'])) {
    // Check if lesson belongs to user and is more than 24 hours away
    $sql = "SELECT * FROM lesson_bookings 
            WHERE id = :lesson_id 
            AND student_id = :student_id 
            AND booking_date > DATE_ADD(NOW(), INTERVAL 24 HOUR)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":lesson_id", $_POST['lesson_id'], PDO::PARAM_INT);
    $stmt->bindParam(":student_id", $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();
    
    if($stmt->rowCount() > 0) {
        // Delete the booking
        $sql = "DELETE FROM lesson_bookings WHERE id = :lesson_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(":lesson_id", $_POST['lesson_id'], PDO::PARAM_INT);
        $stmt->execute();
        
        header("location: my-lessons.php?cancelled=1");
        exit();
    }
}

header("location: my-lessons.php?error=1");
exit(); 