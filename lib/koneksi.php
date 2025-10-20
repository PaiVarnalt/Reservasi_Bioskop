<?php
// Konfigurasi database
$host = "localhost";
$dbname = "db_bioskop";
$username = "root";
$password = "";

try {
    // Buat koneksi PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set mode error ke exception biar mudah debugging
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // (Opsional) tampilkan pesan sukses saat testing
    // echo "Koneksi berhasil!";
} catch (PDOException $e) {
    // Kalau gagal konek
    die("Koneksi gagal: " . $e->getMessage());
}
?>
