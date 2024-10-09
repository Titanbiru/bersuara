<?php
// Aktifkan pelaporan kesalahan (opsional, hanya untuk pengembangan)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Sertakan file konfigurasi untuk koneksi database
include 'database/db.php';

// Mulai sesi
session_start();

// Ambil data dari formulir login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi input form
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $_SESSION['login_error'] = "All fields are required.";
        header("Location: login.php");
        exit();
    }

// Ambil data dari formulir login
$username = $_POST['username'];
$password = $_POST['password'];

// Ambil data pengguna dari database
$query = "SELECT id, password FROM users WHERE username = ?";
$statement = $pdo->prepare($query);
$statement->execute([$username]);
$user = $statement->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
        // Mulai sesi dan simpan ID pengguna
        $_SESSION['user_id'] = $user['id'];

        // Arahkan pengguna ke dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Jika login gagal, simpan pesan error di sesi
        $_SESSION['login_error'] = "Invalid Username or Password";

        // Arahkan kembali ke halaman login
        header("Location: login.php");
        exit();
    }
} else {
    // Jika bukan metode POST, kembali ke login page
    header("Location: login.php");
    exit();
}
?>
