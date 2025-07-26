<?php
// Selalu mulai session di baris paling pertama
session_start();

// Panggil file koneksi database
require_once 'config/database.php';

// Pastikan skrip ini diakses melalui metode POST dan ada menu_id yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'])) {
    
    // Inisialisasi keranjang jika belum ada
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Ambil ID menu dari form yang disubmit
    $menu_id = $_POST['menu_id'];

    // Cek apakah item sudah ada di dalam keranjang
    if (isset($_SESSION['keranjang'][$menu_id])) {
        // Jika sudah ada, cukup tambahkan jumlahnya (quantity) sebesar 1
        $_SESSION['keranjang'][$menu_id]['jumlah']++;
    } else {
        // Jika item belum ada, ambil datanya dari database
        try {
            $stmt = $koneksi->prepare("SELECT * FROM menu WHERE id = ?");
            $stmt->execute([$menu_id]);
            $menu = $stmt->fetch(PDO::FETCH_ASSOC);

            // Jika menu ditemukan di database
            if ($menu) {
                // Tambahkan item baru ke dalam keranjang dengan jumlah awal 1
                $_SESSION['keranjang'][$menu_id] = [
                    "nama"      => $menu['nama_menu'],
                    "harga"     => $menu['harga'],
                    "gambar"    => $menu['gambar'],
                    "jumlah"    => 1
                ];
            }
        } catch (PDOException $e) {
            // Tangani error jika query gagal (opsional)
            die("Error: tidak dapat mengambil data menu. " . $e->getMessage());
        }
    }

    // Setelah item ditambahkan/diperbarui, arahkan pengguna ke halaman keranjang
    header("Location: keranjang.php");
    exit();

} else {
    // Jika halaman ini diakses langsung tanpa data POST, kembalikan ke halaman utama
    header("Location: index.php");
    exit();
}
?>