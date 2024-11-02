<?php
$host = 'localhost';
$db = 'social-db_php';
$user = 'root';  // username default XAMPP
$pass = '';  // password default kosong

$conn = new mysqli($host, $user, $pass, $db);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
