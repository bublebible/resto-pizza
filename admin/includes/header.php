<?php
// WAJIB: Mulai session di baris paling pertama
session_start();

// Lindungi Halaman Admin: Cek apakah user sudah login dan apakah rolenya admin (role_id = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    // Jika tidak, tendang ke halaman login dengan pesan error
    header("Location: ../login.php?error=unauthorized");
    exit();
}

// Dapatkan nama halaman saat ini untuk menandai menu aktif
$currentPage = basename($_SERVER['PHP_SELF']);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Pizza Kevvy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/admin-style.css">
</head>
<body>
    <div class="admin-wrapper">