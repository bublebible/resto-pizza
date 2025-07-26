<!-- session start -->
<?php
// Mendapatkan nama file halaman saat ini, contoh: "index.php" atau "keranjang.php"
$currentPage = basename($_SERVER['PHP_SELF']);
?>


<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">Pizza Kevvy</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>" href="index.php">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'keranjang.php') ? 'active' : ''; ?>" href="keranjang.php">Keranjang</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'tentang-kami.php') ? 'active' : ''; ?>" href="tentang-kami.php">Tentang Kami</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'kontak.php') ? 'active' : ''; ?>" href="kontak.php">Kontak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'cek_pesanan.php') ? 'active' : ''; ?>" href="cek_pesanan.php">Cek Pesanan</a>
                </li>
                <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($currentPage == 'riwayat.php') ? 'active' : ''; ?>" href="riwayat.php">Riwayat Pesanan</a>
                </li>
                <?php endif; ?>
            </ul>
            <div class="d-flex align-items-center">

                <?php
                // Cek apakah session user_id ADA dan TIDAK KOSONG
                if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
                    // ✔️ KONDISI: PENGGUNA SUDAH LOGIN
                    // Tampilkan sapaan dan ikon logout.
                ?>

                    <span class="text-white me-3">
                        👋 Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>

                    <a href="logout.php" class="text-white fs-4" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                    </a>

                <?php
                } else {
                    // ❌ KONDISI: PENGGUNA BELUM LOGIN
                    // Tampilkan tombol yang mengarah ke halaman login.
                ?>

                    <a href="login.php" class="btn btn-warning fw-bold">Login</a>

                <?php
                }
                ?>

            </div>
        </div>
    </div>
</nav>