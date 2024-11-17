<?php
// Sertakan file konfigurasi untuk koneksi database
include 'database/db.php';

// Mulai sesi
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_id = $_SESSION['user_id'];

// Ambil data pengguna
$query = "SELECT username, full_name, profile_picture FROM users WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Cek apakah parameter post_id ada di URL
if (!isset($_GET['post_id'])) {
    echo "Post ID tidak ditemukan.";
    exit();
}

$post_id = $_GET['post_id'];

// Ambil postingan berdasarkan ID
$query = "SELECT posts.id, posts.text, posts.created_at, users.username, users.full_name, posts.media,
                COALESCE(likes.like_count, 0) AS like_count,
                COALESCE(dislikes.dislike_count, 0) AS dislike_count
            FROM posts
            JOIN users ON posts.user_id = users.id
            LEFT JOIN (SELECT post_id, COUNT(*) AS like_count FROM likes GROUP BY post_id) AS likes
            ON posts.id = likes.post_id
            LEFT JOIN (SELECT post_id, COUNT(*) AS dislike_count FROM dislikes GROUP BY post_id) AS dislikes
            ON posts.id = dislikes.post_id
            WHERE posts.id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$post_id]);
$post = $statement->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo "Post tidak ditemukan.";
    exit();
}


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
    header("Location: sharePost.php?post_id=" . $post_id);
    exit();
}

// Proses jika komentar disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_text'])) {
    $comment_text = $_POST['comment_text'];
    $comment_query = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)";
    $comment_statement = $pdo->prepare($comment_query);
    $comment_statement->execute([$post_id, $user_id, $comment_text]);
    header("Location: sharePost.php?post_id=$post_id");
    exit();
}

// Proses jika reply disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_text'])) {
    $reply_text = $_POST['reply_text'];
    $reply_to_comment_id = $_POST['reply_to_comment_id'];
    $reply_query = "INSERT INTO comments (post_id, user_id, comment_text, reply_to_comment_id) VALUES (?, ?, ?, ?)";
    $reply_statement = $pdo->prepare($reply_query);
    $reply_statement->execute([$post_id, $user_id, $reply_text, $reply_to_comment_id]);
    header("Location: sharePost.php?post_id=$post_id");
    exit();
}

// Ambil komentar dan balasannya
$comments_query = "SELECT comments.comments_id, comments.comment_text, comments.created_at, users.username, users.full_name, comments.reply_to_comment_id
                    FROM comments 
                    JOIN users ON comments.user_id = users.id 
                    WHERE comments.post_id = ? AND comments.reply_to_comment_id IS NULL
                    ORDER BY comments.created_at ASC";
$comments_statement = $pdo->prepare($comments_query);
$comments_statement->execute([$post_id]);
$comments = $comments_statement->fetchAll(PDO::FETCH_ASSOC);

$replies = [];
foreach ($comments as $comment) {
    $comment_id = $comment['comments_id'];
    $replies_query = "SELECT comments.comments_id, comments.comment_text, comments.created_at, users.username, users.full_name
                        FROM comments
                        JOIN users ON comments.user_id = users.id
                        WHERE comments.reply_to_comment_id = ?";
    $replies_statement = $pdo->prepare($replies_query);
    $replies_statement->execute([$comment_id]);
    $replies[$comment_id] = $replies_statement->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['post_id']) && isset($_POST['action'])) {
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];

    // Memperbarui jumlah like atau dislike
    if ($action == 'like') {
        $query = "UPDATE posts SET like_count = like_count + 1 WHERE id = '$post_id'";
    } elseif ($action == 'dislike') {
        $query = "UPDATE posts SET dislike_count = dislike_count + 1 WHERE id = '$post_id'";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        // Mengambil jumlah like dan dislike yang baru
        $query = "SELECT like_count, dislike_count FROM posts WHERE id = '$post_id'";
        $result = mysqli_query($conn, $query);
        $post = mysqli_fetch_assoc($result);

        // Mengirimkan data jumlah like dan dislike yang baru
        echo json_encode([
            'likeCount' => $post['like_count'],
            'dislikeCount' => $post['dislike_count']
        ]);
    } else {
        echo json_encode(['error' => 'Failed to update like/dislike']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Post</title>
    <link rel="stylesheet" href="css/dashboard-1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/share.css">
    
</head>
<body>
<nav class="sidebar locked">
    <div class="logo_items flex">
        <span class="nav_image">
            <img src="CN.jpg" alt="logo_img" />
        </span>
        <span class="logo_name">Bersuara</span>
        <i class="bx bx-x" id="sidebar-close" title="Unlock Sidebar"></i>
        <i class="bx bx-lock-alt" id="lock-icon"></i>
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
            </ul>
        </div>

        <div class="sidebar_profile flex">
            <span class="nav_image">
                <?php if (!empty($user['profile_picture'])): ?>
                    <!-- Display the uploaded profile picture -->
                    <img src="<?php echo './uploads/' . htmlspecialchars($user['profile_picture']); ?>" alt="Profile Image" />
                <?php else: ?>
                    <!-- Default placeholder with the first letter of the name -->
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

<nav class="navbar flex">
    <i class="bx bx-menu" id="sidebar-open"></i>
    <span><h1><b>Bersuara</b></h1></span>
    <span class="nav_image">
        <img src="cn bersuara.jpg" alt="logo_img" />
    </span>
</nav>

    <br><br><br>

<div id="post-container">
    <!-- Loop through posts -->
    <div class="post">
        <p><strong><?php 
                    if (!empty($post['full_name'])) {
                        echo htmlspecialchars($post['full_name']); 
                    } else {
                        echo htmlspecialchars($post['username']); 
                    }
                    ?></strong> - <?php echo htmlspecialchars($post['created_at']); ?></p>
        
        <!-- Media Display -->
        <?php if (!empty($post['media'])):
                $filePath = "./uploads/" . htmlspecialchars($post['media']);
                if (file_exists($filePath)):
                    $fileExtension = strtolower(pathinfo($post['media'], PATHINFO_EXTENSION));
                    if (in_array($fileExtension, ['mp4', 'mov', 'avi', 'mkv'])): ?>
                        <video controls loop style="max-width: 100%;">
                            <source src="<?php echo $filePath; ?>" type="video/<?php echo $fileExtension; ?>">
                            Your browser does not support the video tag.
                        </video>
                    <?php elseif (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <img src="<?php echo $filePath; ?>" alt="Uploaded Image" style="max-width: 100%; height: auto;">
                    <?php elseif (in_array($fileExtension, ['mp3', 'wav', 'ogg'])): ?>
                        <audio controls loop style="max-width: 100%;" controls>
                            <source src="<?php echo $filePath; ?>" type="audio/<?php echo $fileExtension; ?>">
                            Your browser does not support the audio tag.
                        </audio>
                    <?php else: ?>
                        <p>File media tidak didukung.</p>
                    <?php endif;
                else: ?>
                    <p>File tidak ditemukan.</p>
                <?php endif; 
            endif; ?>

        <p><?php echo nl2br(htmlspecialchars($post['text'])); ?></p>

        <!-- Like/Dislike Section -->
        <p>
            <span class="like-count"><i class='bx bx-like'></i> <?php echo htmlspecialchars($post['like_count']); ?></span> |
            <span class="dislike-count"><i class='bx bx-dislike'></i> <?php echo htmlspecialchars($post['dislike_count']); ?></span>
        </p>

        <!-- Like/Dislike Buttons -->
        <form method="POST" action="sharePost.php?post_id=<?php echo $post['id']; ?>">
            <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
            <button type="submit" name="action" class="like-btn" value="like"><i class='bx bxs-like'></i></button>
            <button type="submit" name="action" class="dislike-btn" value="dislike"><i class='bx bxs-dislike'></i></button>
            <button class="share-btn" data-title="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" 
                data-text="<?php echo htmlspecialchars($post['text'] ?? ''); ?>" 
                data-url="sharePost.php?post_id=<?php echo $post['id']; ?>"> 
                <i class='bx bxs-share'></i> 
            </button>
        </form>

        <!-- Comments Section -->
        <div class="comments">
            <h4>Comments:</h4>
            <form method="POST" class="comment-form">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <input type="text" name="comment_text" placeholder="Add a comment..." required>
                <button type="submit">Comment</button>
            </form>
            <!-- Display Comments -->
            <div class="comments">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <!-- Display Comment -->
                        <strong><?php echo !empty($comment['full_name']) ? $comment['full_name'] : $comment['username']; ?>:</strong>
                        <p><?php echo nl2br(htmlspecialchars($comment['comment_text'])); ?></p>
                        <small><?php echo htmlspecialchars($comment['created_at']); ?></small>

                        <!-- Reply Button -->
                        <button class="reply-btn" data-comment-id="<?php echo $comment['comments_id']; ?>" onclick="toggleReplyForm(<?php echo $comment['comments_id']; ?>)">Reply</button>

                        <!-- Display Replies -->
                        <div class="replies">
                            <?php if (isset($replies[$comment['comments_id']])): ?>
                                <?php foreach ($replies[$comment['comments_id']] as $reply): ?>
                                    <div class="reply">
                                        <blockquote>
                                            <strong>
                                                <?php echo !empty($reply['full_name']) ? $reply['full_name'] : $reply['username']; ?> >>
                                                <?php echo !empty($comment['full_name']) ? $comment['full_name'] : $comment['username']; ?>
                                            </strong>
                                            <p><?php echo nl2br(htmlspecialchars($reply['comment_text'])); ?></p>
                                            <small><?php echo htmlspecialchars($reply['created_at']); ?></small>
                                        </blockquote>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- Reply Form (Hidden by default) -->
                        <div class="reply_form" id="reply-form-<?php echo $comment['comments_id']; ?>" style="display:none;">
                            <form method="POST">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <input type="hidden" name="reply_to_comment_id" value="<?php echo $comment['comments_id']; ?>">
                                <input type="text" name="reply_text" placeholder="Reply to <?php echo $comment['full_name']; ?>" required>
                                <button type="submit" class="-submit-reply-btn">Reply</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<script>
    // Fungsi Share Button
    document.querySelectorAll('.share-btn').forEach(button => {
    button.addEventListener('click', () => {
        const title = button.getAttribute('data-title');
        const text = button.getAttribute('data-text');
        const url = button.getAttribute('data-url');
        
        if (navigator.share) {
        navigator.share({
            title: title,
            text: text,
            url: url
        }).then(() => {
            console.log('Successfully shared');
        }).catch(error => {
            console.error('Error sharing', error);
        });
        } else {
        alert("Browser ini tidak mendukung fitur berbagi.");
        }
    });
    });

    
    // Menampilkan dan menyembunyikan form balasan saat tombol "Reply" diklik
    function toggleReplyForm(commentId) {
        var form = document.getElementById('reply-form-' + commentId);
        if (form.style.display === "none" || form.style.display === "") {
            form.style.display = "block";  // Menampilkan form balasan
        } else {
            form.style.display = "none";  // Menyembunyikan form balasan
        }
    }
    
    // Mengirim komentar baru via AJAX
    $(document).ready(function() {
    // Event listener untuk tombol komentar
    $('.comment-form').submit(function(e) {
        e.preventDefault();  // Mencegah reload halaman saat mengirim form

        var postId = $(this).closest('.post').data('post-id');
        var commentText = $(this).find('textarea').val();

        $.ajax({
            url: 'process-cmd.php', // PHP yang meng-handle komentar
            type: 'POST',
            data: { post_id: postId, comment: commentText },
            success: function(response) {
                var data = JSON.parse(response);

                // Menambahkan komentar baru ke bagian komentar
                $('.comments').prepend('<p>' + data.username + ': ' + data.comment + '</p>');
                
                // Kosongkan textarea setelah kirim
                $(this).find('textarea').val('');
                
                // Ubah URL tanpa mereload halaman
                window.history.pushState(null, '', 'sharePost.php?post_id=' + postId);
            }
        });
    })});

    // Menangani form balasan saat disubmit
    function toggleReplyForm(commentId) {
        var replyForm = document.getElementById('reply-form-' + commentId);
        replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
    }

    // Menangani form submit dengan AJAX
    $(document).ready(function() {
        $('form[id^="form-reply-"]').submit(function(e) {
            e.preventDefault(); // Mencegah form submit biasa (reload halaman)

            var form = $(this);
            var postId = form.find('input[name="post_id"]').val();
            var commentId = form.find('input[name="reply_to_comment_id"]').val();
            var replyText = form.find('input[name="reply_text"]').val();

            // Kirim data balasan menggunakan AJAX
            $.ajax({
                url: 'process-cmd-rply.php',  // URL untuk memproses balasan
                type: 'POST',
                data: {
                    post_id: postId,
                    reply_to_comment_id: commentId,
                    reply_text: replyText
                },
                success: function(response) {
                    var data = JSON.parse(response); // Parse JSON response

                    // Menambahkan balasan baru di bagian bawah komentar yang sesuai
                    $('#reply-form-' + commentId).siblings('.replies').prepend(
                        '<div class="reply">' +
                        '<blockquote>' +
                        '<strong>' + data.username + ' >> ' + data.reply_to_full_name + '</strong>' +
                        '<p>' + data.reply_text + '</p>' +
                        '<small>' + data.created_at + '</small>' +
                        '</blockquote>' +
                        '</div>'
                    );

                    // Kosongkan field input dan sembunyikan form setelah reply dikirim
                    form.find('input[name="reply_text"]').val('');
                    $('#reply-form-' + commentId).hide();

                    // Menjaga URL tetap sama tanpa reload
                    history.pushState(null, null, location.href);  // Menjaga URL tetap stabil
                },
                error: function() {
                    alert("Terjadi kesalahan saat mengirim balasan.");
                }
            });
        });
    });

</script>
</body>
</html>
