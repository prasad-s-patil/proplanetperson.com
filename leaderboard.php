<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once 'config.php';

$sql = "SELECT users.username, COUNT(activities.id) AS completed_activities, 
        CASE 
            WHEN COUNT(activities.id) >= 10 THEN 'Pro Planet Person' 
            ELSE '' 
        END AS status
        FROM activities 
        INNER JOIN users ON activities.user_id = users.id 
        WHERE activities.is_completed = 1 
        GROUP BY users.username 
        ORDER BY completed_activities DESC";
$result = $conn->query($sql);

$leaderboard = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $leaderboard[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leaderboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php">Dashboard</a>
        <a href="add_activity.php">Add Activities</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="wrapper">
        <div class="header">
            <h1>Pro Planet Person</h1>
        </div>
        <h2>Leaderboard</h2>
        <p>See how you rank among other Pro Planet Persons.</p>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Completed Activities</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($leaderboard as $entry): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($entry['username']); ?></td>
                        <td><?php echo htmlspecialchars($entry['completed_activities']); ?></td>
                        <td><?php echo htmlspecialchars($entry['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

