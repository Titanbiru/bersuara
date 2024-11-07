<?php
// Sertakan file konfigurasi untuk koneksi database
include 'database/db.php';

// Mulai sesi untuk mendapatkan ID pengguna yang saat ini login
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['user_id'];

// Ambil data pengguna beserta gambar profil
$query = "SELECT username, full_name, profile_picture FROM users WHERE id = ?";
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
// Proses jika Like atau Dislike diklik
if (isset($_POST['action'])) {
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];

    if ($action == 'like') {
        // Cek apakah pengguna sudah memberi like
        $check_like_query = "SELECT * FROM likes WHERE user_id = ? AND post_id = ?";
        $check_like_statement = $pdo->prepare($check_like_query);
        $check_like_statement->execute([$user_id, $post_id]);
        $like_exists = $check_like_statement->fetch(PDO::FETCH_ASSOC);

        if ($like_exists) {
            // Jika like sudah ada, hapus like (undo)
            $delete_like_query = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
            $delete_like_statement = $pdo->prepare($delete_like_query);
            $delete_like_statement->execute([$user_id, $post_id]);
        } else {
            // Jika belum like, tambahkan like
            $like_query = "INSERT INTO likes (user_id, post_id) VALUES (?, ?)";
            $like_statement = $pdo->prepare($like_query);
            $like_statement->execute([$user_id, $post_id]);

            // Hapus dislike jika ada (jika pengguna sebelumnya dislike)
            $delete_dislike_query = "DELETE FROM dislikes WHERE user_id = ? AND post_id = ?";
            $delete_dislike_statement = $pdo->prepare($delete_dislike_query);
            $delete_dislike_statement->execute([$user_id, $post_id]);
        }
    } elseif ($action == 'dislike') {
        // Cek apakah pengguna sudah memberi dislike
        $check_dislike_query = "SELECT * FROM dislikes WHERE user_id = ? AND post_id = ?";
        $check_dislike_statement = $pdo->prepare($check_dislike_query);
        $check_dislike_statement->execute([$user_id, $post_id]);
        $dislike_exists = $check_dislike_statement->fetch(PDO::FETCH_ASSOC);

        if ($dislike_exists) {
            // Jika dislike sudah ada, hapus dislike (undo)
            $delete_dislike_query = "DELETE FROM dislikes WHERE user_id = ? AND post_id = ?";
            $delete_dislike_statement = $pdo->prepare($delete_dislike_query);
            $delete_dislike_statement->execute([$user_id, $post_id]);
        } else {
            // Jika belum dislike, tambahkan dislike
            $dislike_query = "INSERT INTO dislikes (user_id, post_id) VALUES (?, ?)";
            $dislike_statement = $pdo->prepare($dislike_query);
            $dislike_statement->execute([$user_id, $post_id]);

            // Hapus like jika ada (jika pengguna sebelumnya like)
            $delete_like_query = "DELETE FROM likes WHERE user_id = ? AND post_id = ?";
            $delete_like_statement = $pdo->prepare($delete_like_query);
            $delete_like_statement->execute([$user_id, $post_id]);
        }
    }

    // Redirect untuk menghindari pengiriman ulang form
    header("Location: dashboard.php");
    exit();
}

$comments = [];
foreach ($posts as $post) {
    $post_id = $post['id'];
    $comments_query = "SELECT comments.comments_id, comments.comment_text, comments.created_at, users.username, users.full_name, comments.reply_to_comment_id
    FROM comments 
    JOIN users ON comments.user_id = users.id 
    WHERE comments.post_id = ? AND comments.reply_to_comment_id IS NULL";
    $comments_statement = $pdo->prepare($comments_query);
    $comments_statement->execute([$post_id]);
    $comments[$post_id] = $comments_statement->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch replies for comments
$replies = [];
foreach ($comments as $post_id => $commentArray) {
    foreach ($commentArray as $comment) {
        $comment_id = $comment['comments_id'];
        $replies_query = "SELECT comments.comments_id, comments.comment_text, comments.created_at, users.username, users.full_name 
            FROM comments 
            JOIN users ON comments.user_id = users.id 
            WHERE comments.reply_to_comment_id = ?";
        $replies_statement = $pdo->prepare($replies_query);
        $replies_statement->execute([$comment_id]);
        $replies[$comment_id] = $replies_statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Bagian mengirim reply
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_text'])) {
    $reply_text = $_POST['reply_text'];
    $comment_id = $_POST['reply_to_comment_id']; // Mengambil ID komentar yang sedang dibalas
    $post_id = $_POST['post_id']; // Ambil ID post yang dituju
    
    // Simpan reply sebagai komentar dengan mengaitkan dengan ID komentar yang dibalas
    $reply_query = "INSERT INTO comments (post_id, user_id, comment_text, reply_to_comment_id) VALUES (?, ?, ?, ?)";
    $reply_statement = $pdo->prepare($reply_query);
    $reply_statement->execute([$post_id, $user_id, $reply_text, $comment_id]);
    
    // Redirect setelah reply untuk menghindari form resubmission
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard-1.css">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="js/reply.js" defer></script> 
</head>
 <body> 
 <nav>
  <input type="checkbox" id="sidebar-active">
  <label for="sidebar-active" class="open-sidebar-button">
    <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z"/></svg>
  </label>
  <label id="overlay" for="sidebar-active"></label>
  <div class="links-container">
    <label for="sidebar-active" class="close-sidebar-button">
      <svg xmlns="http://www.w3.org/2000/svg" height="32" viewBox="0 -960 960 960" width="32"><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
    </label>
    <a class="home-link" href="dashboard.php">Home</a>

<div class="dropdown-select">
    <label for="profile-options"></label>
    <select id="profile-options" onchange="navigateToPage()">
        <option value="#">Profile</option>
        <option value="profile.php">View Profile</option>
        <option value="edit_profile.php">Edit Profile</option>
        <option value="logout.php">Logout</option>
    </select>
</div>

<!-- JavaScript untuk navigasi -->
<script>
function navigateToPage() {
    const selectedValue = document.getElementById("profile-options").value;
    if (selectedValue !== "#") { // Cek jika option bukan default
        window.location.href = selectedValue;
    }
}
</script>
    <a href="posts-form.php">New Post</a> 
  </div>
</nav>
  <br><br><br><br>
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
            <!-- Media seperti gambar atau video -->
             <br>
            <?php if (!empty($post['media'])) {
                $filePath = "./uploads/" . htmlspecialchars($post['media']);
                
                if (file_exists($filePath)) {
                    $fileExtension = strtolower(pathinfo($post['media'], PATHINFO_EXTENSION));
                    
                    if (in_array($fileExtension, ['mp4', 'mov', 'avi', 'mkv'])) {
                        echo '<video controls loop style="max-width: 100%;">';
                        echo '<source src="' . $filePath . '" type="video/' . $fileExtension . '">';
                        echo 'Your browser does not support the video tag.';
                        echo '</video>';
                        echo '<br>';
                    } elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])) {
                        echo '<img src="' . $filePath . '" alt="Uploaded Image" style="max-width: 100%; height: auto;">';
                        echo '<br>';
                    } elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg'])) { 
                        echo '<audio controls>';
                        echo '<source src="' . $filePath . '" type="audio/' . $fileExtension . '">';
                        echo 'Your browser does not support the audio tag.';
                        echo '</audio>';
                        echo '<br>';
                    } else {
                        echo "<p>File media tidak didukung.</p>";
                    }
                } else {
                    echo "<p>File tidak ditemukan.</p>";
                }
            }
            ?>
            <br>
            <p><?php echo htmlspecialchars($post['text']); ?></p>
            <br>
            <p>Likes: <?php echo htmlspecialchars($post['like_count']); ?> | Dislikes: <?php echo htmlspecialchars($post['dislike_count']); ?></p>
            <form method="POST" action="dashboard.php">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <button type="submit" name="action" value="like">
                    <i class='bx bxs-like'></i>
                </button>
                <button type="submit" name="action" value="dislike">
                <i class='bx bxs-dislike' ></i>
                </button>
                <button class="share-btn" id="shareButton" data-url="post.php?id=<?php echo $post['id']; ?>">
                <i class='bx bxs-share'></i>
                </button>
            </form>
            <br>
        <!-- Form untuk menambah komentar -->
        <div class="comments">
            <h4>Comments:</h4>
            <form method="POST" action="">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <input type="text" name="comment_text" placeholder="Add a comment..." required>
                <button type="submit">Comment</button>
            </form>
            <div class="comments">
                <?php foreach ($comments[$post['id']] as $comment): ?>
                    <div class="comment">
                        <strong><?php echo !empty($comment['full_name']) ? $comment['full_name'] : $comment['username']; ?>:</strong>
                        <p><?php echo $comment['comment_text']; ?></p>
                        <small><?php echo $comment['created_at']; ?></small>
                        
                        <button class="reply-btn" data-comment-id="<?php echo $comment['comments_id']; ?>">Reply</button>
                        <div class="replies">
                            <?php if (isset($replies[$comment['comments_id']])): ?>
                                <?php foreach ($replies[$comment['comments_id']] as $reply): ?>
                                    <div class="reply">
                                        <blockquote>
                                            <strong>
                                                <?php echo !empty($reply['full_name']) ? $reply['full_name'] : $reply['username']; ?> >>
                                                <?php echo !empty($comment['full_name']) ? $comment['full_name'] : $comment['username']; ?>
                                            </strong>
                                            <p><?php echo $reply['comment_text']; ?></p>
                                            <small><?php echo $reply['created_at']; ?></small>
                                        </blockquote>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        
                        <div class="reply_form" id="reply-form-<?php echo $comment['comments_id']; ?>" style="display:none;">
                            <form method="POST" action="">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <input type="hidden" name="reply_to_comment_id" value="<?php echo $comment['comments_id']; ?>">
                                <input type="text" name="reply_text" placeholder="Reply to <?php echo $comment['full_name']; ?>" required>
                                <button type="submit" class="reply-btn">Reply</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include "layout/footer.html" ?>

</body>
</html>