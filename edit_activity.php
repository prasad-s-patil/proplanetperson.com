<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once 'config.php';

$activity_name = $category = $deadline = "";
$activity_err = $category_err = $deadline_err = "";

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $activity_id = trim($_GET["id"]);

    $sql = "SELECT * FROM activities WHERE id = ? AND user_id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("ii", $activity_id, $_SESSION["id"]);
        if($stmt->execute()){
            $result = $stmt->get_result();
            if($result->num_rows == 1){
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $activity_name = $row["activity_name"];
                $category = $row["category"];
                $deadline = $row["deadline"];
            } else{
                echo "No record found.";
            }
        } else{
            echo "Something went wrong. Please try again later.";
        }
        $stmt->close();
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $activity_id = $_POST["id"];

    if(empty(trim($_POST["activity_name"]))){
        $activity_err = "Please enter an activity.";
    } else{
        $activity_name = trim($_POST["activity_name"]);
    }

    if(empty(trim($_POST["category"]))){
        $category_err = "Please select a category.";
    } else{
        $category = trim($_POST["category"]);
    }

    if(empty(trim($_POST["deadline"]))){
        $deadline_err = "Please select a deadline.";
    } else{
        $deadline = trim($_POST["deadline"]);
    }

    if(empty($activity_err) && empty($category_err) && empty($deadline_err)){
        $sql = "UPDATE activities SET activity_name = ?, category = ?, deadline = ? WHERE id = ? AND user_id = ?";

        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("sssii", $param_activity_name, $param_category, $param_deadline, $activity_id, $_SESSION["id"]);
            $param_activity_name = $activity_name;
            $param_category = $category;
            $param_deadline = $deadline;

            if($stmt->execute()){
                header("location: dashboard.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Activity</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="wrapper">
        <h2>Edit Activity</h2>
        <p>Please update the activity details.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($activity_err)) ? 'has-error' : ''; ?>">
                <label>Activity Name</label>
                <input type="text" name="activity_name" class="form-control" value="<?php echo $activity_name; ?>">
                <span class="help-block"><?php echo $activity_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($category_err)) ? 'has-error' : ''; ?>">
                <label>Category</label>
                <input type="text" name="category" class="form-control" value="<?php echo $category; ?>">
                <span class="help-block"><?php echo $category_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($deadline_err)) ? 'has-error' : ''; ?>">
                <label>Deadline</label>
                <input type="date" name="deadline" class="form-control" value="<?php echo $deadline; ?>">
                <span class="help-block"><?php echo $deadline_err; ?></span>
            </div>
            <input type="hidden" name="id" value="<?php echo $activity_id; ?>"/>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="dashboard.php" class="btn btn-default">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
