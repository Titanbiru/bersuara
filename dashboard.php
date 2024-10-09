<?php
// Sertakan file konfigurasi untuk koneksi database
include 'database/db.php';

// Mulai sesi untuk mendapatkan ID pengguna yang saat ini login
session_start();
if (!isset($_SESSION['user_id'])) {
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
$query = "SELECT posts.id, posts.text, posts.created_at, users.username, users.full_name, posts.media, 
                 COALESCE(likes.like_count, 0) AS like_count,
                 COALESCE(dislikes.dislike_count, 0) AS dislike_count
          FROM posts 
          JOIN users ON posts.user_id = users.id 
          LEFT JOIN (SELECT post_id, COUNT(*) AS like_count FROM likes GROUP BY post_id) AS likes 
          ON posts.id = likes.post_id 
          LEFT JOIN (SELECT post_id, COUNT(*) AS dislike_count FROM dislikes GROUP BY post_id) AS dislikes 
          ON posts.id = dislikes.post_id
          ORDER BY posts.created_at DESC";

$statement = $pdo->prepare($query);
$statement->execute();
$posts = $statement->fetchAll(PDO::FETCH_ASSOC);

// Proses jika komentar disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    $comment_text = $_POST['comment_text'];
    $post_id = $_POST['post_id'];

    $comment_query = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)";
    $comment_statement = $pdo->prepare($comment_query);
    $comment_statement->execute([$post_id, $user_id, $comment_text]);

    // Redirect untuk menghindari pengiriman ulang form
    header("Location: dashboard.php");
    exit();
}

// Proses jika Like atau Dislike diklik
if (isset($_POST['action'])) {
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];

    if ($action == 'like') {
        $like_query = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
        $like_statement = $pdo->prepare($like_query);
        $like_statement->execute([$user_id, $post_id]);
    } elseif ($action == 'dislike') {
        $dislike_query = "INSERT INTO dislikes (user_id, post_id) VALUES (?, ?)";
        $dislike_statement = $pdo->prepare($dislike_query);
        $dislike_statement->execute([$user_id, $post_id]);
    }
    
    // Redirect untuk menghindari pengiriman ulang form
    header("Location: dashboard.php");
    exit();
}

// Ambil komentar untuk setiap postingan
$comments = [];
foreach ($posts as $post) {
    $comment_query = "SELECT comment_text, created_at FROM comments WHERE post_id = ?";
    $comment_statement = $pdo->prepare($comment_query);
    $comment_statement->execute([$post['id']]);
    $comments[$post['id']] = $comment_statement->fetchAll(PDO::FETCH_ASSOC);
}

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

<h1>Welcome, 
<?php 
    if (!empty($user['full_name'])) {
        echo htmlspecialchars($user['full_name']);
    } else {
        echo htmlspecialchars($user['username']);
    } 
?>! You are logged in.
</h1>

<h2>Your Posts</h2>
<div id="post-container">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <p><strong><?php 
                    if (!empty($post['full_name'])) {
                        echo htmlspecialchars($post['full_name']); 
                    } else {
                        echo htmlspecialchars($post['username']); 
                    }
                    ?></strong> - <?php echo htmlspecialchars($post['created_at']); ?></p>

            <p><?php echo htmlspecialchars($post['text']); ?></p>
            <?php if (!empty($post['media'])) {
                $filePath = "./uploads/" . htmlspecialchars($post['media']);
                
                if (file_exists($filePath)) {
                    $fileExtension = strtolower(pathinfo($post['media'], PATHINFO_EXTENSION));
                    
                    if (in_array($fileExtension, ['mp4', 'mov', 'avi', 'mkv'])) {
                        echo '<video controls loop style="max-width: 100%;">';
                        echo '<source src="' . $filePath . '" type="video/' . $fileExtension . '">';
                        echo 'Your browser does not support the video tag.';
                        echo '</video>';
                    } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        echo '<img src="' . $filePath . '" alt="Uploaded Image" style="max-width: 100%; height: auto;">';
                    } else {
                        echo "File media tidak didukung.";
                    }
                } else {
                    echo "<p>File tidak ditemukan.</p>";
                }
            }
            ?>
            <p>Likes: <?php echo htmlspecialchars($post['like_count']); ?> | Dislikes: <?php echo htmlspecialchars($post['dislike_count']); ?></p>
            <form method="POST" action="dashboard.php">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <button type="submit" name="action" value="like">Like</button>
                <button type="submit" name="action" value="dislike">Dislike</button>
            </form>

            <div>
                <p>Comments:</p>
                <form method="POST" action="dashboard.php">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <input type="text" name="comment_text" placeholder="Add a comment...">
                    <button type="submit">Submit</button>
                </form>
                <div class="comments">
                    <?php foreach ($comments[$post['id']] as $comment): ?>
                        <p><?php echo htmlspecialchars($comment['comment_text']); ?> - <?php echo htmlspecialchars($comment['created_at']); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
            <button class="share-btn" data-url="post.php?id=<?php echo $post['id']; ?>">Share</button>
        </div>
    <?php endforeach; ?>
</div>

<a href="edit_profile.php">Edit Profile</a>
<p><a href="posts-form.php">Create a New Post</a></p>
<p><a href="logout.php">Logout</a></p>

<?php include "layout/footer.html" ?>
<script>
    // Script untuk share button
    document.querySelectorAll('.share-btn').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            navigator.clipboard.writeText(url).then(() => {
                alert('Post link copied to clipboard!');
            });
        });
    });
</script>
</body>
</html>
