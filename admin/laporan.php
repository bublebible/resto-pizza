<?php
require_once 'includes/header.php';
require_once 'config/database.php';

// Tentukan rentang tanggal
// Defaultnya adalah hari ini
$tanggal_mulai = $_GET['tanggal_mulai'] ?? date('Y-m-d');
$tanggal_akhir = $_GET['tanggal_akhir'] ?? date('Y-m-d');

// Tambahkan jam ke tanggal akhir agar mencakup seluruh hari itu
$tanggal_akhir_query = $tanggal_akhir . ' 23:59:59';

// Ambil data laporan dari database berdasarkan rentang tanggal dan status 'Selesai'
// Ringkasan (Total Pendapatan dan Jumlah Transaksi)
$stmt_ringkasan = $koneksi->prepare("
    SELECT 
        SUM(total_harga) as total_pendapatan, 
        COUNT(id) as jumlah_transaksi 
    FROM pesanan 
    WHERE status_pesanan = 'Selesai' AND tanggal_pesanan BETWEEN ? AND ?
");
$stmt_ringkasan->execute([$tanggal_mulai, $tanggal_akhir_query]);
$ringkasan = $stmt_ringkasan->fetch();

// Detail Transaksi
$stmt_detail = $koneksi->prepare("
    SELECT * FROM pesanan 
    WHERE status_pesanan = 'Selesai' AND tanggal_pesanan BETWEEN ? AND ? 
    ORDER BY tanggal_pesanan DESC
");
$stmt_detail->execute([$tanggal_mulai, $tanggal_akhir_query]);
$detail_transaksi = $stmt_detail->fetchAll();
?>

<?php require_once 'includes/sidebar.php'; ?>

<div class="main-content">
    <?php require_once 'includes/navbar.php'; ?>
    
    <main class="content">
        <div class="page-header">
            <span>Laporan Pendapatan</span>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Filter Laporan</h5>
                <form action="laporan.php" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo $tanggal_mulai; ?>">
                    </div>
                    <div class="col-md-5">
                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                        <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo $tanggal_akhir; ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="summary-card bg-success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="card-value">Rp <?php echo number_format($ringkasan['total_pendapatan'] ?? 0, 0, ',', '.'); ?></h3>
                                <p class="card-title">Total Pendapatan</p>
                            </div>
                            <i class="bi bi-cash-stack card-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="summary-card bg-info">
                    <div class="card-body text-dark">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="card-value"><?php echo $ringkasan['jumlah_transaksi'] ?? 0; ?></h3>
                                <p class="card-title">Jumlah Transaksi Selesai</p>
                            </div>
                            <i class="bi bi-graph-up card-icon"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Detail Transaksi Selesai</h5>
                <a href="export_excel.php?tanggal_mulai=<?php echo $tanggal_mulai; ?>&tanggal_akhir=<?php echo $tanggal_akhir; ?>" class="btn btn-success">
                    <i class="bi bi-file-earmark-excel"></i> Export ke Excel
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Tanggal</th>
                                <th>Nama Pelanggan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detail_transaksi as $transaksi): ?>
                            <tr>
                                <td>#<?php echo $transaksi['id']; ?></td>
                                <td><?php echo date('d M Y, H:i', strtotime($transaksi['tanggal_pesanan'])); ?></td>
                                <td><?php echo htmlspecialchars($transaksi['nama_pelanggan']); ?></td>
                                <td>Rp <?php echo number_format($transaksi['total_harga'], 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<?php require_once 'includes/footer.php'; ?>