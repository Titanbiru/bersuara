<?php
// Aktifkan pelaporan kesalahan (opsional, hanya untuk pengembangan)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan file konfigurasi untuk koneksi database
include 'database/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "Session not set!";  // Debugging session
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT username, full_name, profile_picture FROM users WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found!";  // Debugging query result
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Inside</title>
</head>
<style>
    /* Global Styles */
body {
    font-family: "Poppins", sans-serif;
    background-color: #f4eef3bc;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Center content */
.center-container {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #ffffff;
}

.command{
    width: 300px;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #68507B;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Header */
h1 {
    font-size: 32px;
    font-weight: 500;
    color: #333;
    margin-bottom: 20px;
}

/* Subheading */
h2 {
    font-size: 24px;
    font-weight: 400;
    color: #555;
    margin-bottom: 40px;
    text-align: center;
}

/* Button Styles */
button {
    width: 100%;
    height: 40px;
    background-color: #68507B;
    color: #fff;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

button:hover {
    background-color: #46315c;
}

a {
    text-decoration: none;
    color: #fff;
}

a:hover {
    color: #68507B;
}

.flex {
    display: flex;
    align-items: center;
}

.navbar {
    max-width: 500px;
    width: 100%;
    position: fixed;
    top: 0;
    left: 50%;
    transform: translateX(-50%);
    background: #fff;
    padding: 10px 20px;
    border: 1px solid black;
    border-radius: 0 0 8px 8px;
    justify-content: space-between;
    z-index: 3;
}
.navbar img {
    height: 40px;
    width: 40px;
    margin-left: 20px;
}
</style>
<body>
<nav class="navbar flex">
    <i class="bx bx-menu" id="sidebar-open"></i>
    <span><h1><b>Bersuara</b></h1></span>
    <span class="nav_image">
        <img src="CN.jpg" alt="logo_img" />
    </span>
</nav>
    <div class="center-container">
        <span class="command">  
        <h1>Welcome, 
        <?php 
            if (!empty($user['full_name'])) {
                echo htmlspecialchars($user['full_name']);
            } else {
                echo htmlspecialchars($user['username']);
            } 
        ?>! You are logged in.
        </h1>
        <br><br>
        <a href="dashboard.php"> 
            <button>
                NEXT
            </button>
        </a>
    </span>
</div>
</body>
</html>
