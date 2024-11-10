<?php
session_start();
include('db_connection.php');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    die('Harus login terlebih dahulu');
}

$post_id = $_POST['post_id'];
$comment = $_POST['comment'];
$user_id = $_SESSION['user_id'];

// Masukkan komentar ke database
$query = "INSERT INTO comments (post_id, user_id, comment) VALUES ('$post_id', '$user_id', '$comment')";
mysqli_query($conn, $query);

// Ambil username dari user yang memberikan komentar
$query = "SELECT username FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$username = mysqli_fetch_assoc($result)['username'];

// Kembalikan data komentar yang baru disubmit
echo json_encode(['username' => $username, 'comment' => $comment]);
?>

<?php
session_start();
include('db_connection.php');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    die('Harus login terlebih dahulu');
}

$comment_id = $_POST['comment_id'];
$reply = $_POST['reply'];
$user_id = $_SESSION['user_id'];

// Masukkan balasan ke database
$query = "INSERT INTO replies (comment_id, user_id, reply) VALUES ('$comment_id', '$user_id', '$reply')";
mysqli_query($conn, $query);

// Ambil username dari user yang memberikan balasan
$query = "SELECT username FROM users WHERE user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$username = mysqli_fetch_assoc($result)['username'];

// Kembalikan data balasan yang baru disubmit
echo json_encode(['username' => $username, 'reply' => $reply]);
?>
