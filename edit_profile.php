<?php
// Aktifkan pelaporan kesalahan (opsional, hanya untuk pengembangan)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan file konfigurasi untuk koneksi database
include 'database/db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT username, full_name, profile_picture, email, bio FROM users WHERE id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$user_id]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

// Proses form saat di-submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari formulir
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];
    $password = $_POST['password'] ?? null;

    // VALIDASI INPUT SERVER-SIDE
    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: edit_profile.php");
        exit();
    }

    // Validasi nama lengkap (tidak mengandung angka atau simbol)
    if (!preg_match("/^[a-zA-Z\s]+$/", $full_name)) {
        $_SESSION['error'] = "Full name can only contain letters and spaces";
        header("Location: edit_profile.php");
        exit();
    }

    // Validasi password (jika diubah)
    if (!empty($password) && strlen($password) < 6) {
        $_SESSION['error'] = "Password must be at least 6 characters";
        header("Location: edit_profile.php");
        exit();
    }

    // Validasi konfirmasi password (jika diubah)
    if (!empty($password) && $password !== $_POST['confirm_password']) {
        $_SESSION['error'] = "Passwords do not match";
        header("Location: edit_profile.php");
        exit();
    }

    // PROSES UPLOAD GAMBAR PROFIL DENGAN VALIDASI
    $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    $maxFileSize = 6 * 1024 * 1024; // 2MB

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileType = mime_content_type($_FILES['profile_picture']['tmp_name']);
        $fileSize = $_FILES['profile_picture']['size'];

        if (!in_array($fileType, $allowedFileTypes)) {
            $_SESSION['error'] = "Only JPG, JPEG, and PNG formats are allowed";
            header("Location: edit_profile.php");
            exit();
        }

        if ($fileSize > $maxFileSize) {
            $_SESSION['error'] = "File size must not exceed 2MB";
            header("Location: edit_profile.php");
            exit();
        }

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
    $update_query = "UPDATE users SET full_name = ?, email = ?, bio = ?, profile_picture = ? WHERE id = ?";
    $statement = $pdo->prepare($update_query);
    $statement->execute([$full_name, $email, $bio, $profile_picture, $user_id]);

    // Jika password diubah
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $update_password_query = "UPDATE users SET password = ? WHERE id = ?";
        $statement = $pdo->prepare($update_password_query);
        $statement->execute([$hashed_password, $user_id]);
    }

    // REGENERASI SESSION ID UNTUK KEAMANAN
    session_regenerate_id(true); 

    $_SESSION['update_success'] = "Profile updated successfully!";
    header("Location: edit_profile.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="css/edit_profile4.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="js/sidebar.js" defer></script>
</head>
<body>
    <?php include "layout/sidebar.php"?>
    <div class="container">
        <div class="content">
            <h1>Edit Profile</h1>
            <?php if (isset($_SESSION['update_success'])): ?>
                <p class="success-message"><?php echo $_SESSION['update_success']; unset($_SESSION['update_success']); ?></p>
            <?php endif; ?>

            <form method="POST" action="edit_profile.php" enctype="multipart/form-data">
                <label>Profile Picture</label><br>
                <div style="position: relative;">
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="display: none;">
                    <label for="profile_picture" class="profile-pic-button">Choose Profile Picture</label>
                </div><br>

                <label>Full Name</label>
                <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label>Bio</label>
                <textarea name="bio" rows="5"><?php echo htmlspecialchars($user['bio']); ?></textarea>

                <label>Password</label><br>
                <input type="password" name="password" id="password" required>

                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password">
                <br><br>

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>

    <script src="js/edit_profile.js"></script>
</body>
</html>
