<?php
// Panggil semua file yang dibutuhkan di awal
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<main>
    <?php
        // --- Variabel untuk konten Hero Section ---
        $headline1 = "Pilih Pizza Favoritmu,";
        $headline2 = "PILIH DAN ORDER SEKARANG!";
        $subheadline = "Hot & Fresh Pizza, Nomer 1 di Jogja";
        $tombol_utama_teks = "Pesan sekarang";
        $tombol_utama_link = "#menu"; // Diarahkan langsung ke menu
        $tombol_kedua_teks = "Lihat semua menu";
        $tombol_kedua_link = "menu.php"; // Halaman baru untuk semua menu
    ?>

    <section class="hero-section">
        <div class="hero-content" data-aos="fade-up" data-aos-delay="200">
            <h1>
                <?php echo $headline1; ?>
                <span class="highlight"><?php echo $headline2; ?></span>
            </h1>
            <p class="subheadline"><?php echo $subheadline; ?></p>
            <div class="hero-buttons">
                <a href="<?php echo $tombol_utama_link; ?>" class="btn btn-primary"><?php echo $tombol_utama_teks; ?></a>
                <a href="<?php echo $tombol_kedua_link; ?>" class="btn btn-secondary"><?php echo $tombol_kedua_teks; ?></a>
            </div>
        </div>
    </section>

    <section id="menu" class="menu-section">

        <?php
            // --- PENGAMBILAN DATA MENU DARI DATABASE ---
            // Ambil 3 menu terbaru dari database
            try {
                $stmt = $koneksi->query("
                    SELECT * FROM menu 
                    ORDER BY id DESC 
                    LIMIT 3
                ");
                $menu_kami = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $menu_kami = []; // Jika error, set menu menjadi array kosong
                // die("Error mengambil data menu: " . $e->getMessage());
            }
        ?>

        <div class="blob blob1"></div>
        <div class="blob blob2"></div>

        <h2 class="section-title" data-aos="fade-down">Menu Favorit Kami</h2>

        <div class="menu-grid">
            <?php 
                $delay = 0; 
                foreach ($menu_kami as $item) : 
            ?>
            <div class="menu-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
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
            <?php 
                $delay += 100;
                endforeach; 
            ?>
        </div>

    </section>
</main>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 800, // Durasi animasi dalam milidetik
        once: true,    // Animasi hanya terjadi sekali
    });
</script>

<?php
require_once 'includes/footer.php';
?>