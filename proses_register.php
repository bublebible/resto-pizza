<?php
// WAJIB: Mulai session di baris paling pertama agar bisa menggunakan $_SESSION
session_start();

// Panggil file koneksi database
require_once 'config/database.php';

// Pastikan form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gunakan trim() untuk membersihkan spasi yang tidak disengaja
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validasi input sederhana
    if (empty($username) || empty($email) || empty($password)) {
        die("Semua field harus diisi!");
    }

    // HASH PASSWORD! Ini sangat penting untuk keamanan.
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // 1. Cek dulu apakah EMAIL sudah ada
        $stmt = $koneksi->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            // Jika email sudah ada, set pesan error di session dan arahkan kembali
            $_SESSION['error_register'] = 'Email yang Anda masukkan sudah terdaftar.';
            header("Location: login.php?action=register");
            exit();
        }

        // 2. Cek dulu apakah USERNAME sudah ada
        $stmt = $koneksi->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            // Jika username sudah ada, set pesan error di session dan arahkan kembali
            $_SESSION['error_register'] = 'Username tidak tersedia, silakan gunakan username lain.';
            header("Location: login.php?action=register");
            exit();
        }
        
        // Jika semua aman, masukkan data baru ke tabel 'users'
        $stmt = $koneksi->prepare("INSERT INTO users (username, email, password, role_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $email, $hashed_password, 2]);

        // Jika berhasil, arahkan ke halaman login dengan pesan sukses
        $_SESSION['pesan_sukses'] = "Registrasi berhasil! Silakan login dengan akun Anda.";
        header("Location: login.php");
        exit();

    } catch (PDOException $e) {
        // Tangani error database
        die("Error saat registrasi: " . $e->getMessage());
    }
} else {
    // Jika halaman diakses langsung, arahkan kembali ke form
    header("Location: login.php?action=register");
    exit();
}