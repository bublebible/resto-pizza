<?php
// Panggil semua file yang dibutuhkan di awal
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// Ambil semua data menu dari database, digabungkan dengan kategori
try {
    $stmt = $koneksi->query("
        SELECT menu.*, kategori.nama_kategori 
        FROM menu 
        JOIN kategori ON menu.kategori_id = kategori.id 
        ORDER BY menu.id DESC
    ");
    $semua_menu = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $semua_menu = [];
}
?>

<link rel="stylesheet" href="assets/css/semua-menu.css"> <main class="semua-menu-page">
    <section class="promo-banner">
        <div class="promo-content">
            <h2>Promo Hari Ini <span class="subtitle">/kuota terbatas</span></h2>
            <p class="promo-subtext">Pesan sekarang! Jangan sampai kehabisan pizza nomer 1 di Jogja :D</p>
            <div class="promo-paket">
                <span>Paket hemat 1 : Meat Lover Pizza (Beli 1 dapat 2)</span>
            </div>
            <div class="promo-paket">
                <span>Paket hemat 2 : Large Tuna Delight Pizza + 3 Minuman</span>
            </div>
            <div class="promo-harga">Rp 188.000,00</div>
        </div>
        <div class="promo-image">
            <img src="https://i.ibb.co/6yV4h2j/pizza1.png" alt="Promo Pizza">
        </div>
    </section>

    <section class="kategori-filter">
        <div class="filter-box" data-filter="Makanan">
            <div class="filter-icon">🍕</div>
            <p>Makanan</p>
        </div>
        <div class="filter-box" data-filter="Minuman">
            <div class="filter-icon">🍹</div>
            <p>Minuman</p>
        </div>
    </section>

    <section class="menu-display">
        <div class="menu-grid">
            <?php foreach ($semua_menu as $item) : ?>
                <div class="menu-card" data-kategori="<?php echo htmlspecialchars($item['nama_kategori']); ?>">
                    <div class="card-image-container">
                        <img src="uploads/<?php echo htmlspecialchars($item['gambar']); ?>" alt="<?php echo htmlspecialchars($item['nama_menu']); ?>">
                    </div>
                    <div class="card-content">
                        <h3 class="card-title"><?php echo htmlspecialchars($item['nama_menu']); ?></h3>
                        <p class="card-description"><?php echo htmlspecialchars($item['deskripsi']); ?></p>
                        <div class="card-footer">
                            <p class="card-price">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                            <form action="tambah-ke-keranjang.php" method="post">
                                <input type="hidden" name="menu_id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn btn-card">Order</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <div class="kembali-container">
        <a href="index.php" class="btn-kembali">Kembali</a>
    </div>

</main>

<?php require_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBoxes = document.querySelectorAll('.filter-box');
    const menuCards = document.querySelectorAll('.menu-card');
    const makananFilterBox = document.querySelector('.filter-box[data-filter="Makanan"]');

    function filterMenu(selectedKategori) {
        // Tandai filter box yang aktif
        filterBoxes.forEach(box => {
            if (box.dataset.filter === selectedKategori) {
                box.classList.add('active');
            } else {
                box.classList.remove('active');
            }
        });

        // Tampilkan atau sembunyikan kartu menu
        menuCards.forEach(card => {
            if (card.dataset.kategori === selectedKategori) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Event listener untuk setiap kotak filter
    filterBoxes.forEach(box => {
        box.addEventListener('click', function() {
            const selectedKategori = this.dataset.filter;
            filterMenu(selectedKategori);
        });
    });

    // Set filter default ke "Makanan" saat halaman dimuat
    if (makananFilterBox) {
        filterMenu('Makanan');
    }
});
</script>