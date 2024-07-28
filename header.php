<?php
// header.php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pro Planet Person</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<nav>
    <a href="index.php">Home</a>
    <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
        <a href="dashboard.php">Dashboard</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    <?php endif; ?>
</nav>
