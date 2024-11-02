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
    <link rel="stylesheet" href="css/login-3.css">
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
        <div class="login-form">
            <h2>SMK CITRA NEGARA</h2>
            <h3>Login to Bersuara</h3>
            <form method="POST" action="login_process.php">
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div> 
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="error">
                    <?php if (isset($_SESSION['login_error'])): ?>
                        <p class="error-message"><?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?></p>
                        <?php endif ?>
                    </div>
                    <button type="submit" name="">Login</button>
                    <p>Don't have an account? <a href="register.php">Register here</a></p>
            </form>
            </div>
    </div>  
</body>
</html>

