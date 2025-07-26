<?php
header('Content-Type: application/json');
// Path disesuaikan karena file ini ada di dalam folder /admin
require_once __DIR__ . '/../config/database.php';

// Cek apakah ID pesanan ada
if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID Pesanan tidak ditemukan.']);
    exit;
}

$pesanan_id = $_GET['id'];

// Fungsi untuk membuat badge status
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

try {
    // Ambil data pesanan utama
    $stmt_pesanan = $koneksi->prepare("SELECT * FROM pesanan WHERE id = ?");
    $stmt_pesanan->execute([$pesanan_id]);
    $pesanan = $stmt_pesanan->fetch();

    if (!$pesanan) {
        echo json_encode(['error' => 'Data pesanan tidak ditemukan.']);
        exit;
    }

    // Ambil item-item di dalam pesanan
    $stmt_detail = $koneksi->prepare("SELECT * FROM detail_pesanan WHERE pesanan_id = ?");
    $stmt_detail->execute([$pesanan_id]);
    $items = $stmt_detail->fetchAll();

    // Format data untuk dikirim sebagai JSON
    $response = [
        'nama_pelanggan' => htmlspecialchars($pesanan['nama_pelanggan']),
        'nomor_meja' => htmlspecialchars($pesanan['nomor_meja']),
        'tanggal_pesanan' => date('d M Y, H:i', strtotime($pesanan['tanggal_pesanan'])),
        'total_harga' => (int)$pesanan['total_harga'],
        'status_pesanan' => $pesanan['status_pesanan'],
        'status_pesanan_badge' => get_status_badge($pesanan['status_pesanan']),
        'items' => $items
    ];

    echo json_encode($response);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>