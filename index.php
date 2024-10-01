<?php
// Aktifkan pelaporan kesalahan (opsional, hanya untuk pengembangan)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan file konfigurasi untuk koneksi database
include 'database/db.php';

// Ambil semua postingan dari database
$query = "SELECT posts.id, posts.text, posts.created_at, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC";
$statement = $pdo->prepare($query);
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

// Proses formulir untuk membuat postingan baru
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan pengguna sudah login
    session_start();
    if (!isset($_SESSION['user_id'])) {
        die("Please log in to post.");
    }

    // Ambil data dari formulir
    $text = $_POST['text'];
    $user_id = $_SESSION['user_id'];

    // Sisipkan postingan baru ke database
    $query = "INSERT INTO posts (user_id, text) VALUES (?, ?)";
    $statement = $pdo->prepare($query);
    $statement->execute([$user_id, $text]);

    // Arahkan kembali ke halaman utama setelah berhasil
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media</title>
    <link rel="stylesheet" href="css/index-2.css">
</head>
<body>
<<<<<<< HEAD
    <div class="container">
        <h1>Welcome to Social Media</h1>
            <p>Please</p>
            <div class="login_and_register">
                <a href="login.php">Login</a>
                <p>or</p>
                <a href="register.php">Register</a> to Bersuara
            </div>
            <?php endif; ?>
        </div>
    </body>
=======
    <div class="card">
            <h1>Social Media Posts</h1>
            <img src="citra.png" alt="image.description" width="200" style="display: block; margin: 0 auto;">
            <p>Web bersuara, atau aplikasi web yang menggunakan suara, memiliki berbagai kegunaan yang bermanfaat.</p>
            
        <?php if (isset($_SESSION['user_id'])): ?>
        <div class="container">
            <h1></h1>
            <?php else: ?>
                <div class="login_and_register">
            <p>Please <a href="login.php">log in</a> or <a href="register.php">register</a> to Bersuara.</p></div>
            <?php endif; ?>
        </div>
    </div>  
</body>
>>>>>>> 2ed4663fd480a6b2548ac39791d2d6c9fe056c07
</html>
