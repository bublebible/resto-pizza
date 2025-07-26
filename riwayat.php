<?php
// Pastikan session sudah dimulai di header.php
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'includes/navbar.php';

// Lindungi halaman: jika tidak login, tendang
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil semua pesanan milik user ini
$stmt = $koneksi->prepare("SELECT * FROM pesanan WHERE user_id = ? ORDER BY tanggal_pesanan DESC");
$stmt->execute([$user_id]);
$riwayat_pesanan = $stmt->fetchAll();

// Fungsi untuk badge status
function get_status_badge($status) {
    $badge_class = '';
    switch ($status) {
        case 'Baru': $badge_class = 'bg-primary'; break;
        case 'Diproses': $badge_class = 'bg-info text-dark'; break;
        case 'Selesai': $badge_class = 'bg-success'; break;
        case 'Dibatalkan': $badge_class = 'bg-danger'; break;
    }
    return "<span class='badge {$badge_class}'>{$status}</span>";
}
?>

<main class="container mt-5 pt-5">
    <h2 class="mb-4">Riwayat Pesanan Saya</h2>

    <?php if (empty($riwayat_pesanan)): ?>
        <div class="alert alert-info">Anda belum memiliki riwayat pesanan.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Kode Pesanan</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($riwayat_pesanan as $pesanan): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($pesanan['kode_pesanan']); ?></strong></td>
                        <td><?php echo date('d M Y, H:i', strtotime($pesanan['tanggal_pesanan'])); ?></td>
                        <td>Rp <?php echo number_format($pesanan['total_harga']); ?></td>
                        <td><?php echo get_status_badge($pesanan['status_pesanan']); ?></td>
                        <td class="text-center">
                            <?php if ($pesanan['status_pesanan'] == 'Baru'): ?>
                                <button class="btn btn-sm btn-success btn-bayar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#bayarModal"
                                    data-id="<?php echo $pesanan['id']; ?>"
                                    data-kode="<?php echo htmlspecialchars($pesanan['kode_pesanan']); ?>">
                                    <i class="bi bi-qr-code"></i> Bayar di Kasir
                                </button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title w-100" id="bayarModalLabel">Pembayaran di Kasir</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Tunjukkan kode ini kepada kasir untuk melanjutkan pembayaran.</p>
                <h3 class="kode-pesanan-modal mb-3">Memuat...</h3>
                
                <div id="qr-code-container" class="mb-3 d-flex justify-content-center">
                    </div>

                <div id="detail-item-modal">
                    </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/davidshimjs-qrcodejs@0.0.2/qrcode.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var bayarModal = document.getElementById('bayarModal');
    bayarModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var pesananId = button.dataset.id;
        var kodePesanan = button.dataset.kode;

        var modalTitle = bayarModal.querySelector('.modal-title');
        var kodePesananElem = bayarModal.querySelector('.kode-pesanan-modal');
        var qrContainer = document.getElementById('qr-code-container');
        var detailContainer = document.getElementById('detail-item-modal');
        
        // Reset tampilan modal
        modalTitle.textContent = 'Pembayaran untuk ' + kodePesanan;
        kodePesananElem.textContent = kodePesanan;
        qrContainer.innerHTML = ''; // Kosongkan QR code lama
        detailContainer.innerHTML = '<p>Memuat detail item...</p>';

        // Buat QR Code baru
        new QRCode(qrContainer, {
            text: kodePesanan,
            width: 200,
            height: 200,
        });

        // Ambil detail item menggunakan AJAX
        fetch('admin/api_get_order_details.php?id=' + pesananId)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    detailContainer.innerHTML = `<p class="text-danger">${data.error}</p>`;
                } else {
                    let html = '<ul class="list-group list-group-flush">';
                    data.items.forEach(item => {
                        html += `<li class="list-group-item d-flex justify-content-between">
                                    <span>${item.nama_menu}</span>
                                    <span>x${item.jumlah}</span>
                                 </li>`;
                    });
                    html += `<li class="list-group-item d-flex justify-content-between fw-bold">
                                <span>Total</span>
                                <span>Rp ${data.total_harga.toLocaleString('id-ID')}</span>
                             </li>`;
                    html += '</ul>';
                    detailContainer.innerHTML = html;
                }
            });
    });
});
</script>