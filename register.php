<?php
include 'db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize inputs
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    // Basic server-side validation
    if (!$username || !$email || !$password || !$confirm_password) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username or email already exists.";
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);

            if ($stmt->execute()) {
                $message = "Registration successful! <a href='index.php'>Login here</a>.";
            } else {
                $message = "Error: " . $conn->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Register</title>
<link rel="stylesheet" href="style.css" />
<script src="script.js" defer></script>
</head>
<body>
<div class="container">
    <h2>Register</h2>

    <?php if ($message): ?>
      <p class="<?php echo (strpos($message, 'successful') !== false) ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
      </p>
    <?php endif; ?>

    <form name="registerForm" action="register.php" method="post" onsubmit="return validateRegisterForm()">
        <input type="text" name="username" placeholder="Username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" />
        <input type="email" name="email" placeholder="Email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" />
        <input type="password" name="password" placeholder="Password" />
        <input type="password" name="confirm_password" placeholder="Confirm Password" />
        <button type="submit">Register</button>
    </form>
    <p style="text-align:center; margin-top:15px;">Already have an account? <a href="index.php">Login here</a></p>
</div>
</body>
</html>
