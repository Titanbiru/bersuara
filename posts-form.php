<?php
// Sertakan file konfigurasi untuk koneksi database
include 'database/db.php';

// Mulai sesi untuk memeriksa login pengguna
session_start();
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada sesi pengguna, arahkan ke halaman login
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="css/posts-2.css">
</head>
<body>
    <nav class="navbar flex">
        <i class="bx bx-menu" id="sidebar-open"></i>
        <span><h1><b>Bersuara</b></h1></span>
        <span class="nav_image">
            <img src="CN.jpg" alt="logo_img" />
        </span>
    </nav>
    <div class="container">
        <h2>Create a Post</h2>
        <form method="POST" action="posts_process.php" enctype="multipart/form-data">
            <label for="content">Content:</label>
            <textarea id="text" name="text" rows="2" cols="30" required></textarea>
            
            <label>Create your Post</label>
            <input type="file" name="media" id="media" style="display:none;">
            <label for="media" class="media-button">Choose File</label>
            <br>
            <?php if (isset($_SESSION['login_error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_SESSION['login_error']); ?></p>
            <?php endif; ?>
            <button type="submit">Post</button>
        </form>
        <br>
        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>
</body>
</html>
