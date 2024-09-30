<?php
// Sertakan file konfigurasi untuk koneksi database
include 'database/db.php';

// Mulai sesi untuk mendapatkan ID pengguna yang saat ini login
session_start();
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada sesi pengguna, arahkan ke halaman login
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna
$query = "SELECT username FROM users WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Ambil semua postingan
    $query = "SELECT id, password FROM users WHERE username = ?";
$statement = $pdo->prepare($query);
$statement->execute([$username]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" href="css/dashboard-1.css">
    </head>
    <body>

    <!-- <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1> -->

    <h2>Your Posts</h2>
    <?php if (isset($_SESSION['post_success'])): ?>
        <p><?php echo $_SESSION['post_success']; unset($_SESSION['post_success']); ?></p>
    <?php endif; ?>

    <div id="post-container">
        
    <div class="post">
        <?php
        foreach ($posts as $post): ?>
        <p><strong><?php 
                if (!empty($post['full_name'])) {
                    echo htmlspecialchars($post['full_name']); 
                } else {
                    echo htmlspecialchars($post['username']); 
                }
                ?></strong> - <?php echo htmlspecialchars($post['created_at']); ?></p>

        <p><?php echo htmlspecialchars($post['text']); ?></p>

        <?php if (!empty($post['media'])): ?>
                <?php
                $fileExtension = strtolower(pathinfo($post['media'], PATHINFO_EXTENSION));
                $filePath = "./uploads/" . htmlspecialchars($post['media']);
                
                if (file_exists($filePath)): 
                ?>
                    <?php if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src="<?php echo $filePath; ?>" alt="Image" style="max-width: 100%;">
                    <?php elseif (in_array($fileExtension, ['mp4', 'mov', 'avi'])): ?>
                        <video controls loop style="max-width: 100%;">
                            <source src="<?php echo $filePath; ?>" type="video/<?php echo $fileExtension; ?>">
                            Your browser does not support the video tag.
                        </video>
                    <?php endif; ?>
                <?php else: ?>
                    <p>File does not exist at: <?php echo $filePath; ?></p>
                <?php endif; ?>
            <?php endif; ?>
    </div>
<?php endforeach; ?>
    </div>
    <a href="edit_profile.php">Edit Profile</a>
    <p><a href="posts-form.php">Create a New Post</a></p>
    <p><a href="logout.php">Logout</a></p>

    <?php include "layout/footer.html"?>
</body>
</html>
