<?php
// Konfigurasi database
$host = 'localhost';
$dbname = 'agenda_bulungan';
$username = 'root';
$password = '';

try {
    // Koneksi menggunakan PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Set error mode ke exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Jika koneksi gagal
    die("Koneksi database gagal: " . $e->getMessage());
}
?>
