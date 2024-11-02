<?php
session_start();
include 'database/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$query = "SELECT username, full_name, email, bio, profile_picture FROM users WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

$query = "SELECT posts.id, posts.text, posts.created_at, posts.media 
        FROM posts 
        WHERE user_id = ? 
        ORDER BY created_at DESC";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

$query = $pdo->prepare("
    SELECT COUNT(likes.id) AS total_likes
    FROM posts
    JOIN likes ON posts.id = likes.post_id
    WHERE posts.user_id = :user_id
");
$query->execute(['user_id' => $user_id]);
$total_likes = $query->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="css/profile-6.css">
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
                <?php
                if (!empty($user['profile_picture'])) {
                    echo '<img src="uploads/' . htmlspecialchars($user['profile_picture']) . '" alt="Profile Picture">';
                } else {
                    if (!empty($user['full_name'])) {
                        $full_name_parts = explode(' ', htmlspecialchars($user['full_name']));
                        $initial = strtoupper($full_name_parts[0][0]); 
                    } else {
                        if (!empty($user['username'])) {
                            $initial = strtoupper($user['username'][0]);
                        } else {
                            $initial = 'U';
                        }
                    }
                    echo '<div class="profile-initial">' . $initial . '</div>';
                }
                ?>
            </div>
            <div class="profile-info">
                    <h2>
                        <?php 
                            echo htmlspecialchars(!empty($user['full_name']) ? $user['full_name'] : $user['username'] ?? 'No Name Set');
                        ?>
                    </h2>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username'] ?? 'No Username Set'); ?></p>
                    <p><strong>Bio:</strong> <?php echo htmlspecialchars($user['bio'] ?? 'No Bio Available'); ?></p>
                    <br>
                    <p>Likes: <?php echo $total_likes ? $total_likes : 0; ?></p>
                </div>
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
                    echo "<div class='post'>";
                    echo "<p>" . htmlspecialchars($post['text']) . "</p>";
                    
                    // Cek apakah ada media video
                    if (!empty($post['media'])) {
                        echo "<video width='320' height='240' controls>";
                        echo "<source src='uploads/" . htmlspecialchars($post['media']) . "' type='video/mp4'>";
                        echo "Your browser does not support the video tag.";
                        echo "</video>";
                    }
                    
                    echo "<span class='post-time'>" . htmlspecialchars($post['created_at']) . "</span>";
                    echo "</div>";
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
