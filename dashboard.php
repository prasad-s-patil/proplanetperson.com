<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once 'config.php';





$user_id = $_SESSION["id"];
$sql = "SELECT * FROM activities WHERE user_id = ? ORDER BY is_completed, category, activity_name";

if($stmt = $conn->prepare($sql)){
    $stmt->bind_param("i", $param_user_id);
    $param_user_id = $user_id;
    $stmt->execute();
    $result = $stmt->get_result();
    $activities = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $activity_id = $_POST['activity_id'];
    $sql = "UPDATE activities SET is_completed = 1 WHERE id = ? AND user_id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("ii", $activity_id, $user_id);
        $stmt->execute();
        $stmt->close();

        $sql = "INSERT INTO leaderboard (user_id, completed_activities) VALUES (?, 1) ON DUPLICATE KEY UPDATE completed_activities = completed_activities + 1";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $stmt->close();
        }

        header("location: dashboard.php");
    }
}



$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <header>
        <div class="container">
            <div id="branding">
                <h1>Pro Planet</h1>
            </div>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="suggestion.php">Suggestions</a></li>
                    <li><a href="leaderboard.php">Leaderboard</a></li>
                    <li><a href="logout.php">Sign Out</a></li>
                </ul>
            </nav>
        </div>
    </header>

        <div class="page-header">
            <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to your dashboard.</h1>
        </div>
        <p>
            <a href="add_activity.php" class="btn btn-primary">Add New Activities</a>
            <a href="leaderboard.php" class="btn btn-primary">View Leaderboard</a>
        </p>
        <div>
            <h2>Your Activities</h2>
            <ul class="activities-list">
                <?php foreach($activities as $activity): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($activity['activity_name']); ?></strong>
                        <br>
                        Category: <?php echo htmlspecialchars($activity['category']); ?>
                        <br>
                        <?php if($activity['is_completed']): ?>
                            <span class="completed">Completed</span>
                        <?php else: ?>
                            <form method="post" style="display: inline;">
                                <input type="hidden" name="activity_id" value="<?php echo $activity['id']; ?>">
                                <input type="submit" value="Complete" class="btn btn-success">
                            </form>
                            <a href="edit_activity.php?id=<?php echo $activity['id']; ?>" class="btn btn-primary">Edit</a>
                            <a href="delete_activity.php?id=<?php echo $activity['id']; ?>" class="btn btn-danger">Delete</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</body>
</html>
