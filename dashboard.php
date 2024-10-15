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
    $comment_query = "SELECT comments.comment_text, comments.created_at, users.username 
    FROM comments 
    JOIN users ON comments.user_id = users.id 
    WHERE comments.post_id = ?";
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
    <link rel="stylesheet" href="css/dashboard-3.css">
    <link flex href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <script src="js/script.js" defer></script>
</head>
<body>

<nav class="sidebar locked">
    <div class="logo_items flex">
        <span class="nav_image">
            <img src="CN.jpg" alt="logo_img" />
        </span>
        <span class="logo_name">Bersuara</span>
        <i class="bx bx-lock-alt" id="lock-icon" title="Unlock Sidebar"></i>
        <i class="bx bx-x" id="sidebar-close"></i>
    </div>

        <div class="menu_container">
            <div class="menu_items">
                <ul class="menu_item">
                <div class="menu_title flex">
                    <span class="title">Menu</span>
                    <span class="line"></span>
                </div>
                <li class="item">
                    <a href="dashboard.php" class="link flex">
                        <i class="bx bx-home-alt"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="item">
                    <a href="profile.php" class="link flex">
                    <i class='bx bxs-user-account'></i>
                        <span>Profile</span>
                    </a>
                </li>
                <li class="item">
                    <a href="posts-form.php" class="link flex">
                        <i class="bx bx-cloud-upload"></i>
                        <span>Upload New</span>
                    </a>
                </li>
                <li class="item">
                    <a href="logout.php" class="link flex">
                        <i class="bx bx-log-out"></i>
                        <span>Log out</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="sidebar_profile flex">
            <span class="nav_image">
                <?php if (!empty($user['profile_image'])): ?>
                    <img src="<?php echo './uploads/' . htmlspecialchars($user['profile_picture']); ?>" alt="Profile Image" />
                <?php else: ?>
                    <!-- Default placeholder with the first letter -->
                    <div class="profile-letter">
                        <?php 
                            // Get the first letter from full_name or username
                            $firstLetter = !empty($user['full_name']) ? $user['full_name'][0] : $user['username'][0]; 
                            echo strtoupper(htmlspecialchars($firstLetter)); 
                        ?>
                    </div>
                <?php endif; ?>
            </span>
            <div class="data_text">
                <?php 
                    if (!empty($user['full_name'])) {
                        echo htmlspecialchars($user['full_name']);
                    } else {
                        echo htmlspecialchars($user['username']);
                    } 
                ?>
            </div>
        </div>
    </div>
</nav>

    <!-- Navbar -->
    <nav class="navbar flex">
        <i class="bx bx-menu" id="sidebar-open"></i>
        <span><h1><b>Bersuara</b></h1></span>
        <span class="nav_image">
            <img src="CN.jpg" alt="logo_img" />
        </span>
    </nav>

    <br><br><br>

    stroy an

<div id="post-container">
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <!-- Nama dan waktu postingan -->
            <p><strong><?php 
                    if (!empty($post['full_name'])) {
                        echo htmlspecialchars($post['full_name']); 
                    } else {
                        echo htmlspecialchars($post['username']); 
                    }
                    ?></strong> - <?php echo htmlspecialchars($post['created_at']); ?></p>
            <!-- Media seperti gambar atau video -->
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

<br>

            <!-- Pindahkan deskripsi di sini, di bawah media -->
            <p><?php echo htmlspecialchars($post['text']); ?></p>

            <p>Likes: <?php echo htmlspecialchars($post['like_count']); ?> | Dislikes: <?php echo htmlspecialchars($post['dislike_count']); ?></p>
            <form method="POST" action="dashboard.php">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <button type="submit" name="action" value="like">
                    <i class='bx bxs-like'></i>
                </button>
                <button type="submit" name="action" value="dislike">
                <i class='bx bxs-dislike' ></i>
                </button>
            </form>

            <br>

            <div>
                <p>Comments:</p>
                <form method="POST" action="dashboard.php">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <input type="text" name="comment_text" placeholder="Add a comment...">
                    <button type="submit">
                        <i class='bx bxs-send' ></i>
                    </button>
                </form>
                <div class="comments">
                    <?php foreach ($comments[$post['id']] as $comment): ?>
                        <p>
                            <strong><?php echo htmlspecialchars($comment['username']); ?>:</strong>
                            <?php echo htmlspecialchars($comment['comment_text']); ?> 
                            - <?php echo htmlspecialchars($comment['created_at']); ?>
                        </p>
                    <?php endforeach; ?>
                </div>
            </div>

            <br>
            <button class="share-btn" data-url="post.php?id=<?php echo $post['id']; ?>">
            <i class='bx bxs-share'></i>
            </button>
        </div>
    <?php endforeach; ?>
</div>


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