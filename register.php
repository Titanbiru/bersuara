<?php
// Mulai sesi
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="css/regis-2.css">
</head>
<body class="body">
    <div class="container">
    <h2>Register</h2>
    
    <form method="POST" action="register_process.php">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        </div>
        <?php if (isset($_SESSION['register_error'])): ?>
            <p class="error-message"><?php echo htmlspecialchars($_SESSION['register_error']); ?></p>
        <?php endif; ?>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</body>
</html>

