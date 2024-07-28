<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once 'config.php';

// Fetch categories
$categories = [];
$sql = "SELECT id, category_name FROM categories";
if($result = $conn->query($sql)){
    while($row = $result->fetch_assoc()){
        $categories[] = $row;
    }
}

// Fetch predefined activities based on selected category
$activities = [];
if(isset($_POST['category_id'])){
    $category_id = $_POST['category_id'];
    $sql = "SELECT id, activity_name FROM predefined_activities WHERE category_id = ?";
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $category_id);
        if($stmt->execute()){
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $activities[] = $row;
            }
        }
        $stmt->close();
    }
}

// Handle form submission
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_activities'])){
    $category_id = $_POST['category_id'];
    $selected_activities = $_POST['activities'];
    
    foreach($selected_activities as $activity_id){
        $sql = "INSERT INTO activities (user_id, activity_name, category, is_completed) 
                SELECT ?, activity_name, ?, FALSE 
                FROM predefined_activities 
                WHERE id = ?";
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("isi", $_SESSION["id"], $category_id, $activity_id);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("location: dashboard.php");
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Activities</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function fetchActivities() {
            var categoryId = document.getElementById("category").value;
            document.getElementById("categoryForm").submit();
        }
    </script>
</head>
<body>
    <div class="wrapper">
        <h2>Add Activities</h2>
        <form id="categoryForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Category</label>
                <select id="category" name="category_id" class="form-control" onchange="fetchActivities()">
                    <option value="">Select a category</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?php echo $category['id']; ?>" <?php echo isset($category_id) && $category_id == $category['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['category_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>

        <?php if(!empty($activities)): ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="category_id" value="<?php echo $category_id; ?>">
            <div class="form-group">
                <label>Activities</label>
                <?php foreach($activities as $activity): ?>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="activities[]" value="<?php echo $activity['id']; ?>">
                            <?php echo htmlspecialchars($activity['activity_name']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="form-group">
                <input type="submit" name="submit_activities" class="btn btn-primary" value="Add Activities">
            </div>
        </form>
        <?php endif; ?>
    </div>
</body>
</html>
