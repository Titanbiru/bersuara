<?php
session_start();
include 'database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan pengguna sudah login
    if (!isset($_SESSION['user_id'])) {
        die("Please log in to post.");
    }
    
    $text = $_POST['text'];
    $user_id = $_SESSION['user_id'];
    $media = null;

    // Cek apakah file diupload
    if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['media']['tmp_name'];
        $fileName = $_FILES['media']['name'];
        $fileSize = $_FILES['media']['size'];
        $fileType = $_FILES['media']['type'];
        $filePath = $_FILES['media'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        
        // Batasi ukuran file, misalnya maksimal 2MB (2 * 1024 * 1024 byte)
        $maxFileSize = 120 * 1024 * 1024;
        if ($fileSize > $maxFileSize) {
            die("File is too large. Maximum size allowed is 120MB.");
        }

        // Tentukan direktori upload
        $uploadFileDir = './uploads/';
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        // Tentukan file path untuk menyimpan file
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $dest_path = $uploadFileDir . $newFileName;

        // Pindahkan file ke direktori uploads
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $media = $newFileName;
            echo "File uploaded successfully " . $dest_path;  // Tambahkan debugging untuk memastikan unggahan sukses
        } else {
            die("Failed to upload file.");
        }
        
    }

    // Simpan data postingan ke database
    $query = "INSERT INTO posts (user_id, text, media) VALUES (?, ?, ?)";
    $statement = $pdo->prepare($query);
    $statement->execute([$user_id, $text, $media]);

    // Arahkan kembali ke halaman utama setelah berhasil
    $_SESSION['post_success'] = "Your post has been successfully uploaded!";
    header("Location: dashboard.php");
    exit();
}
?>
