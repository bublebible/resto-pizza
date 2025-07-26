<?php
// Selalu mulai session di awal
session_start();

require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        // 1. Cari user dan verifikasi password terlebih dahulu
        $stmt = $koneksi->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Jika username dan password BENAR, lanjutkan ke pengecekan session

            // 2. CEK APAKAH SUDAH ADA SESSION ID DI DATABASE
            if (!empty($user['session_id'])) {
                // Jika kolom session_id tidak kosong, berarti akun sedang digunakan
                header("Location: login.php?error=already_logged_in");
                exit();
            }

            // 3. JIKA AKUN TIDAK SEDANG DIGUNAKAN, LANJUTKAN LOGIN
            // Regenerasi ID session untuk keamanan
            session_regenerate_id(true); 
            $new_session_id = session_id();

            // Simpan informasi user ke dalam session PHP
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role_id'] = $user['role_id'];

            // 4. SIMPAN ID SESSION BARU KE DATABASE
            $stmt_update = $koneksi->prepare("UPDATE users SET session_id = ? WHERE id = ?");
            $stmt_update->execute([$new_session_id, $user['id']]);

            // Arahkan ke halaman yang sesuai berdasarkan role
            if ($user['role_id'] == 1) {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit();

        } else {
            // Jika username atau password salah
            header("Location: login.php?error=invalid_credentials");
            exit();
        }
    } catch (PDOException $e) {
        die("Error saat login: " . $e->getMessage());
    }
} else {
    header("Location: login.php");
    exit();
}