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
    <link rel="stylesheet" href="css/posts-1.css">
</head>
<body>
    <h2>Create a Post</h2>
    <form method="POST" action="posts_process.php" enctype="multipart/form-data">
        <label for="content">Content:</label>
        <textarea id="text" name="text" rows="2" cols="30" required></textarea>

        <label>Upload Image/Video</label>
        <input type="file" name="media"><br>
        <?php if (isset($_SESSION['login_error'])): ?>
            <p class="error"><?php echo htmlspecialchars($_SESSION['login_error']); ?></p>
        <?php endif; ?>
        <button type="submit">Post</button>
    </form>
    <br>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</body>
</html>
