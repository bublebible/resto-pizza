<?php 
// Panggil header untuk memulai session dan proteksi halaman
require_once 'includes/header.php'; 
require_once 'config/database.php';
// --- PENGAMBILAN DATA UNTUK DASHBOARD ---
try {
    // 1. Hitung jumlah total customers (role_id = 2)
    $stmt_cust = $koneksi->query("SELECT COUNT(id) FROM users WHERE role_id = 2");
    $jumlah_customer = $stmt_cust->fetchColumn();

    // 2. Hitung jumlah pesanan baru yang masuk HARI INI
    $stmt_pesanan = $koneksi->query("SELECT COUNT(id) FROM pesanan WHERE status_pesanan = 'Baru' AND DATE(tanggal_pesanan) = CURDATE()");
    $jumlah_pesanan_baru = $stmt_pesanan->fetchColumn();

    // 3. Hitung jumlah total item menu yang aktif
    $stmt_menu = $koneksi->query("SELECT COUNT(id) FROM menu");
    $jumlah_menu = $stmt_menu->fetchColumn();

} catch (PDOException $e) {
    // Jika terjadi error, set nilai default agar tidak merusak halaman
    $jumlah_customer = 0;
    $jumlah_pesanan_baru = 0;
    $jumlah_menu = 0;
    // Opsional: tampilkan pesan error saat development
    // die("Error: " . $e->getMessage());
}
?>

<?php require_once 'includes/sidebar.php'; ?>

<div class="main-content">
    <?php require_once 'includes/navbar.php'; ?>
    
    <main class="content">
        <div class="page-header">Beranda</div>
        
        <div class="row">
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="card-value"><?php echo $jumlah_customer; ?></h3>
                                <p class="card-title">Customers</p>
                            </div>
                            <i class="bi bi-people-fill card-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="card-value"><?php echo $jumlah_pesanan_baru; ?></h3>
                                <p class="card-title">Pesanan Baru Hari Ini</p>
                            </div>
                            <i class="bi bi-receipt-cutoff card-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="card-value"><?php echo $jumlah_menu; ?></h3>
                                <p class="card-title">Total Item Menu</p>
                            </div>
                            <i class="bi bi-journal-text card-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="welcome-message">
            <p>Selamat bekerja, layani pelanggan dengan sepenuh hati</p>
        </div>

    </main>
</div>

<?php require_once 'includes/footer.php'; ?>