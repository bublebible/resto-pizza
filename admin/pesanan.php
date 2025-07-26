<?php
require_once 'includes/header.php';
require_once 'config/database.php';

$pesan = '';

// Proses update status jika ada data yang dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $pesanan_id = $_POST['pesanan_id'];
    $status_baru = $_POST['status_pesanan'];

    $stmt = $koneksi->prepare("UPDATE pesanan SET status_pesanan = ? WHERE id = ?");
    if ($stmt->execute([$status_baru, $pesanan_id])) {
        $pesan = "Status pesanan #{$pesanan_id} berhasil diperbarui.";
    } else {
        $pesan = "Gagal memperbarui status pesanan.";
    }
}

// Ambil semua data pesanan untuk ditampilkan
$semua_pesanan = $koneksi->query("SELECT * FROM pesanan ORDER BY tanggal_pesanan DESC")->fetchAll();

// Fungsi untuk badge status
function get_status_badge($status)
{
    $badge_class = '';
    switch ($status) {
        case 'Baru':
            $badge_class = 'bg-primary';
            break;
        case 'Diproses':
            $badge_class = 'bg-info text-dark';
            break;
        case 'Selesai':
            $badge_class = 'bg-success';
            break;
        case 'Dibatalkan':
            $badge_class = 'bg-danger';
            break;
    }
    return "<span class='badge {$badge_class}'>{$status}</span>";
}
?>

<?php require_once 'includes/sidebar.php'; ?>

<div class="main-content">
    <?php require_once 'includes/navbar.php'; ?>

    <main class="content">
        <div class="page-header">
            <span>Data Pesanan</span>
        </div>

        <?php if ($pesan): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $pesan; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Nama Pelanggan</th>
                                <th>No. Meja</th>
                                <th>Tanggal</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($semua_pesanan as $pesanan): ?>
                                <tr>
                                    <td>#<?php echo htmlspecialchars($pesanan['kode_pesanan'] ?? $pesanan['id']); ?></td>
                                    <td><?php echo htmlspecialchars($pesanan['nama_pelanggan']); ?></td>
                                    <td><strong><?php echo empty($pesanan['nomor_meja']) ? 'Take Away' : htmlspecialchars($pesanan['nomor_meja']); ?></strong></td>
                                    <td><?php echo date('d M Y, H:i', strtotime($pesanan['tanggal_pesanan'])); ?></td>
                                    <td>Rp <?php echo number_format($pesanan['total_harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo get_status_badge($pesanan['status_pesanan']); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-info btn-detail"
                                            data-bs-toggle="modal"
                                            data-bs-target="#pesananModal"
                                            data-id="<?php echo $pesanan['id']; ?>">
                                            <i class="bi bi-eye"></i> Detail
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal fade" id="pesananModal" tabindex="-1" aria-labelledby="pesananModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pesananModalLabel">Detail Pesanan #</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="detail-pesanan-content">
                    <p class="text-center">Memuat detail pesanan...</p>
                </div>
                <hr>
                <h5>Ubah Status Pesanan</h5>
                <form id="form-update-status" action="pesanan.php" method="POST">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="pesanan_id" id="pesanan-id-input">
                    <div class="input-group">
                        <select class="form-select" name="status_pesanan" id="status-pesanan-select">
                            <option value="Baru">Baru</option>
                            <option value="Diproses">Diproses</option>
                            <option value="Selesai">Selesai</option>
                            <option value="Dibatalkan">Dibatalkan</option>
                        </select>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var pesananModal = document.getElementById('pesananModal');
        pesananModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var pesananId = button.dataset.id;

            var modalTitle = pesananModal.querySelector('.modal-title');
            var detailContent = pesananModal.querySelector('#detail-pesanan-content');
            var pesananIdInput = pesananModal.querySelector('#pesanan-id-input');

            modalTitle.textContent = 'Detail Pesanan #' + pesananId;
            pesananIdInput.value = pesananId;
            detailContent.innerHTML = '<p class="text-center">Memuat detail pesanan...</p>';

            // Gunakan fetch API (AJAX) untuk mengambil detail pesanan
            fetch('api_get_order_details.php?id=' + pesananId)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        detailContent.innerHTML = '<p class="text-danger">' + data.error + '</p>';
                    } else {
                        let html = `
                        <p><strong>Nama Pelanggan:</strong> ${data.nama_pelanggan}</p>
                        <p><strong>Nomor Meja:</strong> <span class="fw-bold fs-5 text-danger">${data.nomor_meja}</span></p>
                        <p><strong>Tanggal:</strong> ${data.tanggal_pesanan}</p>
                        <p><strong>Status Saat Ini:</strong> ${data.status_pesanan_badge}</p>
                        <h6>Item yang Dipesan:</h6>
                        <table class="table table-bordered">
                            <thead><tr><th>Menu</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
                            <tbody>`;
                        data.items.forEach(item => {
                            html += `<tr>
                                    <td>${item.nama_menu}</td>
                                    <td>${item.jumlah}</td>
                                    <td>Rp ${item.subtotal.toLocaleString('id-ID')}</td>
                                 </tr>`;
                        });
                        html += `</tbody>
                             <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end">Total Harga:</th>
                                    <th>Rp ${data.total_harga.toLocaleString('id-ID')}</th>
                                </tr>
                             </tfoot>
                        </table>`;
                        detailContent.innerHTML = html;

                        // Set selected option di dropdown status
                        document.getElementById('status-pesanan-select').value = data.status_pesanan;
                    }
                })
                .catch(error => {
                    detailContent.innerHTML = '<p class="text-danger">Gagal memuat data. Silakan coba lagi.</p>';
                    console.error('Error:', error);
                });
        });
    });
</script>