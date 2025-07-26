<?php
session_start();
require_once 'config/database.php';

// Pastikan form disubmit dan keranjang tidak kosong
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['keranjang'])) {
    
    // Ambil data dari form
    $nama_pelanggan = trim($_POST['nama_pelanggan']);
    $nomor_meja = trim($_POST['nomor_meja']); // <-- AMBIL DATA NOMOR MEJA
    
    $keranjang = $_SESSION['keranjang'];
    $total_harga = 0;

    // Hitung total harga
    foreach ($keranjang as $item) {
        $total_harga += $item['harga'] * $item['jumlah'];
    }

    $user_id = $_SESSION['user_id'] ?? null;

    try {
        $koneksi->beginTransaction();

        // 1. Masukkan data ke tabel `pesanan`, TERMASUK NOMOR MEJA
        $stmt_pesanan = $koneksi->prepare(
            "INSERT INTO pesanan (user_id, nama_pelanggan, nomor_meja, total_harga, status_pesanan) VALUES (?, ?, ?, ?, 'Baru')"
        );
        $stmt_pesanan->execute([$user_id, $nama_pelanggan, $nomor_meja, $total_harga]);
        
        $pesanan_id = $koneksi->lastInsertId();
        $kode_pesanan = 'PIZZA-' . str_pad($pesanan_id, 3, '0', STR_PAD_LEFT);

        // Update kode pesanan
        $stmt_update_kode = $koneksi->prepare("UPDATE pesanan SET kode_pesanan = ? WHERE id = ?");
        $stmt_update_kode->execute([$kode_pesanan, $pesanan_id]);

        // 2. Masukkan detail pesanan
        $stmt_detail = $koneksi->prepare("INSERT INTO detail_pesanan (pesanan_id, menu_id, nama_menu, jumlah, subtotal) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($keranjang as $menu_id => $item) {
            $subtotal = $item['harga'] * $item['jumlah'];
            $stmt_detail->execute([$pesanan_id, $menu_id, $item['nama'], $item['jumlah'], $subtotal]);
        }

        $koneksi->commit();

        unset($_SESSION['keranjang']);

        $_SESSION['pesan_sukses'] = "Pesanan Anda dengan kode " . $kode_pesanan . " telah berhasil dibuat!";
        header("Location: index.php");
        exit();

    } catch (Exception $e) {
        $koneksi->rollBack();
        die("Terjadi error saat memproses pesanan: " . $e->getMessage());
    }

} else {
    header('Location: index.php');
    exit();
}
?>