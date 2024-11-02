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
    <link rel="stylesheet" href="css/regis-4.css">
</head>
<body>
<nav class="navbar flex">
    <i class="bx bx-menu" id="sidebar-open"></i>
    <span><h1><b>Bersuara</b></h1></span>
    <span class="nav_image">
        <img src="cn bersuara.jpg" alt="logo_img" />
    </span>
</nav>
<div class="container">
        <h2>Register</h2>
        <form method="POST" action="register_process.php" onsubmit="return validateForm()">
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
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>
        <div class="error-message">
            <?php if (isset($_SESSION['register_error'])): ?>
                <p class="error-message"><?php echo htmlspecialchars($_SESSION['register_error']); ?></p>
                <?php unset($_SESSION['register_error']); ?>
            <?php endif; ?>
        </div>
        <button type="submit">Register</button>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </form>
</div>
</body>
</html>

