<?php
session_start();
include 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$comment_id = $_POST['comment_id'];
$reply_text = $_POST['reply_text'];

if (!empty($reply_text)) {
    $query = "INSERT INTO replies (comment_id, user_id, reply_text) VALUES (?, ?, ?)";
    $statement = $pdo->prepare($query);
    $statement->execute([$comment_id, $user_id, $reply_text]);

    header("Location: dashboard.php"); // Redirect back to dashboard after posting reply
} else {
    echo "Reply cannot be empty.";
}
?>
