<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pro Planet Person</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="wrapper">
        <h2>Welcome to Pro Planet Person</h2>
        <p>Join our community and make a positive impact on the planet. Track your eco-friendly activities, earn rewards, and connect with like-minded individuals.</p>
        <img src="path/to/your/image.jpg" alt="Pro Planet Person">
    </div>

    <?php include 'footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            setTimeout(function() {
                document.getElementById("splash-screen").style.display = "none";
            }, 3000); // Adjust the timeout as needed (3000ms = 3 seconds)
        });
    </script>
</body>
</html>
