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

    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $bio = $_POST['bio'];

    if (strpos($full_name, ' ') !== false) {
        $_SESSION['error'] = "Full Name tidak boleh mengandung spasi. Silakan gunakan simbol sebagai pengganti spasi.";
        header("Location: edit_profile.php");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Invalid email format";
        header("Location: edit_profile.php");
        exit();
    }

    // Proses pengeditan informasi umum (bio dan gambar profil)
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $allowedFileTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxFileSize = 6 * 1024 * 1024; // 6MB

        $fileType = mime_content_type($_FILES['profile_picture']['tmp_name']);
        $fileSize = $_FILES['profile_picture']['size'];

        if (!in_array($fileType, $allowedFileTypes)) {
            $_SESSION['error'] = "Only JPG, JPEG, and PNG formats are allowed";
            header("Location: edit_profile.php");
            exit();
        }

        if ($fileSize > $maxFileSize) {
            $_SESSION['error'] = "File size must not exceed 6MB";
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

    // Jika password diubah, memerlukan konfirmasi password
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        // Validasi konfirmasi password
        if ($password !== $confirm_password) {
            $_SESSION['error'] = "Passwords do not match";
            header("Location: edit_profile.php");
            exit();
        }

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
    <link rel="stylesheet" href="css/edit_profile3.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="js/sidebar.js" defer></script>
</head>
<body>
    <?php include "layout/sidebar.php"?>
    <div class="container">
        <div class="content">
            <h1>Edit Profile</h1>
            <form method="POST" action="edit_profile.php" enctype="multipart/form-data" id="edit-profile-form">
                <label>Profile Picture</label><br>
                <div class="profile-picture-section">
                    <label>Current Profile Picture</label>
                    <div class="current-profile-picture">
                        <?php if (!empty($user['profile_picture'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Current Profile Picture" id="current-profile-pic">
                        <?php else: ?>
                            <p>No profile picture uploaded</p>
                        <?php endif; ?>
                    </div>

                    <label>New Profile Picture Preview</label>
                    <div class="new-profile-picture-preview">
                        <img id="new-profile-pic-preview" src="#" alt="New Profile Picture Preview" style="display: none;">
                    </div>
                </div>
                <div style="position: relative;">
                    <input type="file" name="profile_picture" id="profile_picture" accept="image/*" style="display: none;">
                    <label for="profile_picture" class="profile-pic-button">Choose Profile Picture</label>
                </div><br>

                <span style="color:cyan;">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message"><?php echo $_SESSION['error']; ?></div>
                    <?php unset($_SESSION['error']); // Hapus setelah ditampilkan ?>
                <?php endif; ?>
    
                <?php if (isset($_SESSION['update_success'])): ?>
                    <div class="success-message"><?php echo $_SESSION['update_success']; ?></div>
                    <?php unset($_SESSION['update_success']); // Hapus setelah ditampilkan ?>
                <?php endif; ?>
                </span>

                <br>

                <label>Display Name</label>
                <input type="text" id="full-name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" placeholder="Display Name" required>

                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label>Bio</label>
                <textarea name="bio" rows="5"><?php echo htmlspecialchars($user['bio'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>

                <label>Password (leave empty to keep current password)</label><br>
                <input type="password" name="password" id="password">

                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" id="confirm_password">
                <br><br>

                <button type="submit">Update Profile</button>
            </form>
        </div>
    </div>
    <script>
    // Selecting the sidebar and buttons
    const sidebar = document.querySelector(".sidebar");
    const sidebarOpenBtn = document.querySelector("#sidebar-open");
    const sidebarCloseBtn = document.querySelector("#sidebar-close");
    const sidebarLockBtn = document.querySelector("#lock-icon");
    
    // Function to toggle the lock state of the sidebar
    const toggleLock = () => {
      sidebar.classList.toggle("locked");
      if (sidebar.classList.contains("locked")) {
        sidebar.classList.remove("hoverable");
        sidebarLockBtn.classList.replace("bx-lock-alt", "bx-lock-open-alt");
      } else {
        sidebar.classList.add("hoverable");
        sidebarLockBtn.classList.replace("bx-lock-open-alt", "bx-lock-alt");
      }
    };
    
    // Function to show and hide the sidebar
    const toggleSidebar = () => {
      sidebar.classList.toggle("close");
    };
    
    // Function to open the sidebar on hover (on mouseover)
    const openSidebarOnHover = () => {
      if (sidebar.classList.contains("locked")) {
        sidebar.classList.remove("close"); // Open the sidebar when hovered
      }
    };
    
    // Function to close the sidebar when the mouse leaves (on mouseout)
    const closeSidebarOnLeave = () => {
      if (sidebar.classList.contains("locked")) {
        sidebar.classList.add("close"); // Close the sidebar when mouse leaves
      }
    };
    
    // Initial check for window width when the page loads
    const checkSidebarLock = () => {
      if (window.innerWidth >= 800) {
        // For desktop: Sidebar should be locked and closed
        sidebar.classList.add("locked", "close"); // Sidebar closed and locked by default
        sidebar.classList.remove("hoverable");
        sidebarLockBtn.classList.replace("bx-lock-open-alt", "bx-lock-alt");
      } else {
        // For mobile: Sidebar should be locked and closed initially
        sidebar.classList.add("locked", "close"); // Sidebar closed and locked by default
        sidebar.classList.remove("hoverable");
        sidebarLockBtn.classList.replace("bx-lock-open-alt", "bx-lock-alt");
      }
    };
    
    // Run the function to check sidebar lock status
    checkSidebarLock();
    
    // Adding event listeners to buttons and sidebar for the corresponding actions
    sidebarLockBtn.addEventListener("click", toggleLock);
    sidebarOpenBtn.addEventListener("click", toggleSidebar);
    sidebarCloseBtn.addEventListener("click", toggleSidebar);
    
    // Adding hover behavior to the sidebar
    sidebar.addEventListener("mouseover", openSidebarOnHover);  // Open sidebar when mouse enters
    sidebar.addEventListener("mouseout", closeSidebarOnLeave);  // Close sidebar when mouse leaves
    
    // Ensure the sidebar state updates when window is resized
    window.addEventListener("resize", checkSidebarLock);
    
    // Make sure the sidebar is hidden when page loads if in mobile view
    document.addEventListener("DOMContentLoaded", checkSidebarLock);
    

    </script>
    <script src="js/edit_profile.js"></script>
</body>
</html>
