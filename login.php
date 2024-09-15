<?php
// Mulai sesi
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login-1.css">
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="login_process.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <?php if (isset($_SESSION['login_error'])): ?>
        <p class="error-message"><?php echo htmlspecialchars($_SESSION['login_error']); ?></p>
        
        <?php endif; ?>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
</body>
</html>

