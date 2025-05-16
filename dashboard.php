<?php
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Dashboard</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<nav>
  <a href="logout.php">Logout</a>
</nav>

<div class="container">
  <h2>Dashboard</h2>
  <p class="welcome">Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!</p>
  <p>This is your dashboard. You can add more features here.</p>
</div>
</body>
</html>
