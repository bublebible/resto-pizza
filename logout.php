<?php
session_start();
require_once 'config/database.php';

// Ambil user_id sebelum session dihancurkan
$user_id = $_SESSION['user_id'] ?? null;

if ($user_id) {
    // Hapus session_id dari database untuk user ini
    $stmt = $koneksi->prepare("UPDATE users SET session_id = NULL WHERE id = ?");
    $stmt->execute([$user_id]);
}

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Arahkan pengguna kembali ke halaman login
header("Location: login.php?status=logged_out");
exit();
?>