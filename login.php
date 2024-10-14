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
    <link rel="stylesheet" href="css/login-2.css">
</head>
<body>
    <div class="container">
    <div class="login-form">
            <h2>SMK CITRA NEGARA</h2>
            <h3>Login to bersuara</h3>
            <form>
                <div class="input-group">
                    <label for="username">Username:</label>
                    <input type="text" id="username" placeholder="Enter your username">
                </div>
                <div class="input-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" placeholder="Enter your password">
                </div>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
            </div>
    </div>  
</body>
</html>

