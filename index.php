<?php
session_start();
include 'db.php';

$message = "";

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (!$username || !$password) {
        $message = "Please enter both username/email and password.";
    } else {
        // Check user by username or email
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $user, $hashed_password);
            $stmt->fetch();

            // Verify password
            if (password_verify($password, $hashed_password)) {
                // Set session variables
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $user;

                header("Location: dashboard.php");
                exit();
            } else {
                $message = "Incorrect password.";
            }
        } else {
            $message = "User not found.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Login</title>
<link rel="stylesheet" href="style.css" />
<script src="script.js" defer></script>
</head>
<body>
<div class="container">
    <h2>Login</h2>

    <?php if ($message): ?>
      <p class="error"><?php echo $message; ?></p>
    <?php endif; ?>

    <form name="loginForm" action="index.php" method="post" onsubmit="return validateLoginForm()">
        <input type="text" name="username" placeholder="Username or Email" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />
        <input type="password" name="password" placeholder="Password" />
        <button type="submit">Login</button>
    </form>

    <p style="text-align:center; margin-top:15px;">Don't have an account? <a href="register.php">Register here</a></p>
</div>
</body>
</html>
