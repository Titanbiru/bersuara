<?php
// Aktifkan pelaporan kesalahan (opsional, hanya untuk pengembangan)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan file konfigurasi untuk koneksi database
include 'database/db.php';

// Mulai sesi
session_start();

// Ambil data dari formulir pendaftaran
$username = $_POST['username'];
$password = $_POST['password'];


$confirm_password = $_POST['confirm_password'];

// Validasi data
// if (empty($username) || empty($password) || empty($email) || empty($confirm_password)) {
//     $_SESSION['register_error'] = "All fields are required.";
//     header("Location: register.php");
//     exit();
// }

// if ($password !== $confirm_password) {
//     $_SESSION['register_error'] = "Passwords do not match.";
//     header("Location: register.php");
//     exit();
// }

// Periksa apakah username sudah ada
$query = "SELECT id FROM users WHERE username = ?";
$statement = $pdo->prepare($query);
$statement->execute([$username]);
if ($statement->fetch(PDO::FETCH_ASSOC)) {
    $_SESSION['register_error'] = "Username already taken.";
    header("Location: register.php");
    exit();
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Sisipkan pengguna baru ke database
$query = "INSERT INTO users (username, password) VALUES (?, ?)";
$statement = $pdo->prepare($query);

if ($statement->execute([$username, $hashed_password])) {
    $_SESSION['register_success'] = "Registration successful! You can now log in.";
    header("Location: dashboard.php");
    exit();
} else {
    $_SESSION['register_error'] = "Registration failed. Please try again.";
    header("Location: register.php");
    exit();
}
?>
