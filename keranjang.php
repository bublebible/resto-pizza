<?php
// WAJIB: Mulai session di baris paling pertama

// Panggil file konfigurasi dan komponen halaman
require_once 'config/database.php';
require_once 'includes/header.php'; // Pastikan header.php tidak memiliki styling yang bentrok

// Ambil data keranjang dari session, atau buat array kosong jika tidak ada
$keranjang = $_SESSION['keranjang'] ?? [];
?>

<link rel="stylesheet" href="assets/css/keranjang-style.css">

<body class="cart-page-body">

    <?php require_once 'includes/navbar.php'; ?>

    <div class="cart-page-container">
        <h1 class="cart-main-title">Keranjang Anda</h1>
        
        <?php if (empty($keranjang)) : ?>
            <div class="cart-empty">
                <i class="bi bi-cart-x-fill empty-icon"></i>
                <p>Keranjang anda kosong, yuk pesan sekarang!</p>
                <a href="semua_menu.php" class="btn-pesan-sekarang">Pesan Sekarang</a>
            </div>
        <?php else : ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="cart-items-list">
                        <?php 
                            $total_harga = 0;
                            foreach ($keranjang as $menu_id => $item) : 
                            $subtotal = $item['harga'] * $item['jumlah'];
                            $total_harga += $subtotal;
                        ?>
                        <div class="cart-item">
                            <img src="uploads/<?php echo htmlspecialchars($item['gambar']); ?>" alt="<?php echo htmlspecialchars($item['nama']); ?>" class="cart-item-image">
                            <div class="cart-item-details">
                                <div class="cart-item-name"><?php echo htmlspecialchars($item['nama']); ?></div>
                                <div class="cart-item-price">Rp <?php echo number_format($item['harga']); ?></div>
                            </div>
                            <div class="cart-item-quantity">
                                <a href="keranjang_aksi.php?action=kurang&id=<?php echo $menu_id; ?>" class="btn-qty">-</a>
                                <input type="text" value="<?php echo $item['jumlah']; ?>" readonly>
                                <a href="keranjang_aksi.php?action=tambah&id=<?php echo $menu_id; ?>" class="btn-qty">+</a>
                            </div>
                            <div class="cart-item-subtotal">Rp <?php echo number_format($subtotal); ?></div>
                            <a href="keranjang_aksi.php?action=hapus&id=<?php echo $menu_id; ?>" class="cart-item-remove" title="Hapus item">&times;</a>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary">
                        <h4 class="summary-title">Ringkasan Pesanan</h4>
                        <div class="summary-item">
                            <span>Subtotal</span>
                            <span>Rp <?php echo number_format($total_harga); ?></span>
                        </div>
                        <div class="summary-item">
                            <span>Biaya Layanan</span>
                            <span>Rp 0</span>
                        </div>
                        <hr>
                        <div class="summary-total">
                            <span>Total</span>
                            <span>Rp <?php echo number_format($total_harga); ?></span>
                        </div>
                        <a href="checkout.php" class="btn btn-checkout w-100 mt-3">Lanjutkan ke Checkout</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

<?php require_once 'includes/footer.php'; ?>
</body>