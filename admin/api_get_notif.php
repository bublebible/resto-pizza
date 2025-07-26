<?php
// Atur header di paling atas untuk memastikan outputnya selalu JSON
header('Content-Type: application/json');

// PERBAIKAN: Menggunakan __DIR__ untuk path yang lebih aman dan absolut
// __DIR__ akan mengambil path folder saat ini (yaitu /admin), lalu /../ akan naik satu level.
require_once __DIR__ . '/../config/database.php';

try {
    // 1. Hitung jumlah total pesanan dengan status 'Baru'
    $stmt_count = $koneksi->query("SELECT COUNT(id) FROM pesanan WHERE status_pesanan = 'Baru'");
    $count = $stmt_count->fetchColumn();

    // 2. Ambil 5 pesanan terbaru dengan status 'Baru' untuk ditampilkan di dropdown
    $stmt_notif = $koneksi->query("
        SELECT id, kode_pesanan, nama_pelanggan, tanggal_pesanan 
        FROM pesanan 
        WHERE status_pesanan = 'Baru' 
        ORDER BY tanggal_pesanan DESC 
        LIMIT 5
    ");
    $notifications = $stmt_notif->fetchAll(PDO::FETCH_ASSOC);

    // 3. Kembalikan data dalam format JSON yang benar
    echo json_encode([
        'count' => (int)$count,
        'notifications' => $notifications
    ]);

} catch (PDOException $e) {
    // Jika terjadi error, kirim pesan error dalam format JSON
    http_response_code(500); // Server error
    echo json_encode(['error' => 'Koneksi atau query database gagal.']);
}
?>