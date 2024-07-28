<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once 'config.php';

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $activity_id = trim($_GET["id"]);

    $sql = "DELETE FROM activities WHERE id = ? AND user_id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("ii", $activity_id, $_SESSION["id"]);

        if($stmt->execute()){
            header("location: dashboard.php");
        } else{
            echo "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
} else{
    echo "No valid activity ID.";
}

$conn->close();
?>
