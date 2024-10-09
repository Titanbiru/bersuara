<?php
session_start();
include 'database/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database untuk ditampilkan di form
$query = "SELECT full_name, email, bio, profile_picture FROM users WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Proses jika formulir disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $full_name = $_POST['full_name'];
    $password = $_POST['password'] ?? null;

    // Ubah profil gambar jika diupload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileName = $_FILES['profile_picture']['name'];
        $fileTmpPath = $_FILES['profile_picture']['tmp_name'];
        $uploadFileDir = './uploads/';
        $dest_path = $uploadFileDir . $fileName;

        move_uploaded_file($fileTmpPath, $dest_path);
        $profile_picture = $fileName;
    } else {
        $profile_picture = $user['profile_picture']; // Jika tidak ada gambar baru, gunakan gambar lama
    }

    // Update data pengguna di database
    $update_query = "UPDATE users SET email = ?, bio = ?, full_name = ?, profile_picture = ? WHERE id = ?";
    $statement = $pdo->prepare($update_query);
    $statement->execute([$email, $bio, $full_name, $profile_picture, $user_id]);

    // Ubah password jika diberikan
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_password_query = "UPDATE users SET password = ? WHERE id = ?";
        $statement = $pdo->prepare($update_password_query);
        $statement->execute([$hashed_password, $user_id]);
    }

    $_SESSION['update_success'] = "Profile updated successfully!";
    header("Location: edit_profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/edit_profile4.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

    <div class="container">
        <h1>Edit Profile</h1>

        <?php if (isset($_SESSION['update_success'])): ?>
            <p class="success-message"><?php echo $_SESSION['update_success']; unset($_SESSION['update_success']); ?></p>
        <?php endif; ?>

        <form method="POST" action="edit_profile.php" enctype="multipart/form-data">
        <label>Profile Picture</label><br>
        <div style="position: relative;">
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="display: none;">
            <label for="profile_picture" class="profile-pic-button">Choose Profile Picture</label>
        </div>
        <br>

            <label>Full Name</label>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label>Bio</label>
            <textarea name="bio" rows="5"><?php echo htmlspecialchars($user['bio']); ?></textarea>

            <label>Password</label><br>
            <div style="position: relative;">
                <input type="password" name="password" id="password" required>
                <input type="checkbox" style="color: white;" onclick="myFunction()">Show Password
            </div><br><br>
            <button type="submit">Update Profile</button>
            <button>
                <a href="dashboard.php">Back to Dashboard</a>
            </button>
        </form>
    </div>
        <script>
            function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
            }
        </script>
</body>
</html>
