<?php
session_start();
include('database/db.php');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    die('Harus login terlebih dahulu');
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $comment_text = $_POST['comment'];
    $user_id = 1;  // Ganti dengan ID pengguna yang sedang login

    // Insert komentar ke dalam database
    $query = "INSERT INTO comments (post_id, user_id, comment_text) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$post_id, $user_id, $comment_text]);

    // Ambil data untuk respons
    $username = 'User';  // Ganti dengan nama pengguna yang sesuai
    $data = [
        'username' => $username,
        'comment' => $comment_text
    ];

    echo json_encode($data);
}
?>

<?php
session_start();
include('ddatabase/db.php');

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    die('Harus login terlebih dahulu');
}
// Ambil data dari POST
$post_id = $_POST['post_id'];
$comment_id = $_POST['reply_to_comment_id'];
$reply_text = $_POST['reply_text'];

// Validasi dan simpan data balasan ke database
$query = "INSERT INTO replies (post_id, comment_id, reply_text) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("iis", $post_id, $comment_id, $reply_text);
$stmt->execute();

// Ambil data balasan yang baru disimpan
$reply_id = $stmt->insert_id;
$query = "SELECT * FROM replies WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $reply_id);
$stmt->execute();
$result = $stmt->get_result();
$reply = $result->fetch_assoc();

// Siapkan data JSON untuk balasan baru
$response = [
    'username' => $reply['username'],
    'reply_text' => $reply['reply_text'],
    'created_at' => $reply['created_at'],
    'reply_to_full_name' => $reply['reply_to_full_name']
];

// Kembalikan data sebagai JSON
echo json_encode($response);
?>
