<?php
session_start();
include 'database/db.php';

if (!isset($_SESSION['user_id'])) {
    die("Please log in first.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = $_POST['text'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO posts (user_id, text) VALUES (?, ?)");
    $stmt->execute([$user_id, $text]);

    echo "Post created successfully!";
}
?>
<form method="POST">
    <textarea name="text" placeholder="What's on your mind?" required></textarea>
    <button type="submit">Post</button>
</form>
