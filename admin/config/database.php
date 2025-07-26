<?php
// Pengaturan Database
$db_host = 'localhost';
$db_name = 'db_resto_pizza';
$db_user = 'root';
$db_pass = ''; // Kosongkan jika tidak ada password

// Opsi untuk koneksi PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Ini akan memperbaiki error sebelumnya
    PDO::ATTR_EMULATE_PREPARES   => false,
];

// Membuat koneksi ke database menggunakan PDO
try {
    $koneksi = new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass, $options);
} catch (PDOException $e) {
    // Hentikan eksekusi dan tampilkan pesan error jika koneksi gagal
    die("ERROR: Tidak bisa terhubung ke database. " . $e->getMessage());
}
