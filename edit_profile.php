<?php
session_start();
include 'database/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil informasi pengguna dari database
$user_id = $_SESSION['user_id'];
$query = "SELECT username, email, bio, full_name FROM users WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Proses jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $full_name = $_POST['full_name'];

    // Update data pengguna di database
    $update_query = "UPDATE users SET email = ?, bio = ?, full_name = ? WHERE id = ?";
    $statement = $pdo->prepare($update_query);
    $statement->execute([$email, $bio, $full_name, $user_id]);

    $_SESSION['update_success'] = "Profile updated successfully!";
    header("Location: edit_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
</head>
<body>
    <h1>Edit Profile</h1>

    <?php if (isset($_SESSION['update_success'])): ?>
        <p style="color: green;"><?php echo $_SESSION['update_success']; unset($_SESSION['update_success']); ?></p>
    <?php endif; ?>

    <form method="POST" action="edit_profile.php">
        <label>Full Name</label><br>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

        <label>Bio</label><br>
        <textarea name="bio" rows="5"><?php echo htmlspecialchars($user['bio']); ?></textarea><br><br>

        <button type="submit">Update Profile</button>
        <button type="submit">
        <a href="dashboard.php">Back to Dashboard</a>
        </button>
    </form>
</body>
</html>
