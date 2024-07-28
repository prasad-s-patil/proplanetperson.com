<?php
require_once 'config.php';

// Fetch all eco-friendly suggestions from the database
$suggestions = [];
$sql = "SELECT * FROM eco_suggestions";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $suggestions[] = $row['suggestion_text'];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pro Planet Person - Suggestions</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
   

    <div class="wrapper">
        <h1>How to Become a Pro Planet Person</h1>
        <p>Follow these rules to become more eco-friendly:</p>
        
        <ul>
            <?php foreach ($suggestions as $suggestion): ?>
                <li><?php echo htmlspecialchars($suggestion); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

   
</body>
</html>
