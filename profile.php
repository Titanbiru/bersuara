<?php
session_start();
include 'database/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$query = "SELECT username, full_name, email, bio, profile_picture FROM users WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile-4.css">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="js/profile.js" defer></script>
    <script src="js/sidebar.js" defer></script>
</head>
<body>
    <?php include 'layout/sidebar.php'?>

    <div class="profile-container">
        <div class="content">
            <h1>Your Profile</h1>
            <div class="profile-details">
            <div class="profile-pic">
                <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']) ? htmlspecialchars($user['profile_picture']) : 'default-profile.png'; ?>" alt="Profile Picture">
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['full_name']) ?: 'No Name Set'; ?></h2>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio']) ?: 'No Bio Available'; ?></p>
            </div>
        </div>

        <div class="user-posts">
            <h2>Your Posts</h2>
            <!-- Query untuk menampilkan postingan pengguna -->
            <?php
            $post_query = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
            $post_statement = $pdo->prepare($post_query);
            $post_statement->execute([$user_id]);
            $posts = $post_statement->fetchAll(PDO::FETCH_ASSOC);
            
            if ($posts) {
                foreach ($posts as $post) {
                    echo "<div class='post'>
                    <p>" . htmlspecialchars($post['text']) . "</p>
                            <span class='post-time'>" . htmlspecialchars($post['created_at']) . "</span>
                        </div>";
                }
            } else {
                echo "<p>You haven't made any posts yet.</p>";
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>
