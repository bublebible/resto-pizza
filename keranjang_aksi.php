<?php
session_start();

// Inisialisasi keranjang jika belum ada
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Bagian ini untuk menangani aksi dari halaman keranjang (+, -, hapus)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action'])) {
    $id = $_GET['id'];
    
    switch ($_GET['action']) {
        case 'tambah':
            if (isset($_SESSION['keranjang'][$id])) {
                $_SESSION['keranjang'][$id]['jumlah']++;
            }
            break;
        case 'kurang':
            if (isset($_SESSION['keranjang'][$id]) && $_SESSION['keranjang'][$id]['jumlah'] > 1) {
                $_SESSION['keranjang'][$id]['jumlah']--;
            } else {
                // Jika jumlahnya 1 lalu dikurangi, hapus itemnya
                unset($_SESSION['keranjang'][$id]);
            }
            break;
        case 'hapus':
            unset($_SESSION['keranjang'][$id]);
            break;
    }
    
    // Setelah melakukan aksi, kembalikan ke halaman keranjang
    header('Location: keranjang.php');
    exit();
}


// Bagian ini untuk menangani penambahan item baru dari halaman menu
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menu_id'])) {
    require_once 'config/database.php';
    $menu_id = $_POST['menu_id'];

    if (isset($_SESSION['keranjang'][$menu_id])) {
        $_SESSION['keranjang'][$menu_id]['jumlah']++;
    } else {
        $stmt = $koneksi->prepare("SELECT * FROM menu WHERE id = ?");
        $stmt->execute([$menu_id]);
        $menu = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($menu) {
            $_SESSION['keranjang'][$menu_id] = [
                "nama"   => $menu['nama_menu'],
                "harga"  => $menu['harga'],
                "gambar" => $menu['gambar'],
                "jumlah" => 1
            ];
        }
    }
    
    // Setelah menambahkan, arahkan ke halaman keranjang
    header('Location: keranjang.php');
    exit();
}

// Jika tidak ada aksi yang cocok, kembalikan ke halaman utama
header('Location: index.php');
exit();