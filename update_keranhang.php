<?php
session_start();

if (isset($_GET['action']) && isset($_GET['id']) && isset($_SESSION['keranjang'])) {
    $action = $_GET['action'];
    $product_id = $_GET['id'];

    // Cek jika produk ada di keranjang
    if (isset($_SESSION['keranjang'][$product_id])) {
        switch ($action) {
            case 'tambah':
                $_SESSION['keranjang'][$product_id]['jumlah']++;
                break;
            case 'kurang':
                $_SESSION['keranjang'][$product_id]['jumlah']--;
                // Jika jumlah jadi 0, hapus item dari keranjang
                if ($_SESSION['keranjang'][$product_id]['jumlah'] <= 0) {
                    unset($_SESSION['keranjang'][$product_id]);
                }
                break;
            case 'hapus':
                unset($_SESSION['keranjang'][$product_id]);
                break;
        }
    }
}

// Redirect kembali ke halaman keranjang
header('Location: keranjang.php');
exit();