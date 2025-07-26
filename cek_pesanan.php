<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// Inisialisasi variabel
$view_class = 'initial-view';
$status_pesanan = '';
$nomor_pesanan_input = '';

// Cek jika form telah disubmit menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $view_class = 'status-view';
    $nomor_pesanan_input = trim(htmlspecialchars($_POST['nomor_pesanan'] ?? ''));

    // --- KONEKSI DATABASE AKTUAL ---
    if (!empty($nomor_pesanan_input)) {
        try {
            // Siapkan query untuk mencari pesanan berdasarkan kode_pesanan
            $stmt = $koneksi->prepare("SELECT * FROM pesanan WHERE kode_pesanan = ?");
            $stmt->execute([$nomor_pesanan_input]);
            $pesanan = $stmt->fetch();

            if ($pesanan) {
                // Jika pesanan ditemukan, buat pesan status dinamis
                $status = htmlspecialchars($pesanan['status_pesanan']);
                $kode = htmlspecialchars($pesanan['kode_pesanan']);
                $status_pesanan = "Status pesanan Anda #{$kode} saat ini adalah: <strong>{$status}</strong>.";

                // Opsional: Tambahkan deskripsi lebih lanjut berdasarkan status
                switch ($status) {
                    case 'Diproses':
                        $status_pesanan .= "<br>Pesanan sedang disiapkan oleh koki terbaik kami.";
                        break;
                    case 'Selesai':
                        $status_pesanan .= "<br>Pesanan Anda telah selesai dan siap diantar. Selamat menikmati!";
                        break;
                    case 'Dibatalkan':
                        $status_pesanan .= "<br>Pesanan ini telah dibatalkan.";
                        break;
                }
            } else {
                // Jika pesanan tidak ditemukan
                $status_pesanan = "Maaf, nomor pesanan '{$nomor_pesanan_input}' tidak ditemukan. Mohon periksa kembali.";
            }
        } catch (PDOException $e) {
            $status_pesanan = "Terjadi kesalahan pada sistem. Silakan coba lagi nanti.";
        }
    } else {
        $status_pesanan = "Mohon masukkan nomor pesanan Anda.";
    }
    // --- AKHIR KONEKSI ---
}
?>
<link rel="stylesheet" href="assets/css/cek-pesanan-style.css">

<body class="cek-pesanan-body">
    <div class="cek_container <?php echo $view_class; ?>">
        <img src="./assets/images/pizzeria.png" alt="Pizzeria Girl" class="mascot pizzeria-girl">
        <img src="./assets/images/pizzeria_motor.png" alt="Delivery Girl" class="mascot delivery-girl">
        
        <div class="form-wrapper">
            <h1>Cek Status Pesanan Anda Disini</h1>
            <form action="cek_pesanan.php" method="POST">
                <label for="nomor_pesanan">Masukkan nomor pesanan anda:</label>
                <div class="input-group">
                    <input type="text" id="nomor_pesanan" name="nomor_pesanan" placeholder="Contoh: PIZZA-001" required>
                    <button type="submit">Cek</button>
                </div>
            </form>
        </div>

        <div class="status-wrapper">
            <div class="status-header">
                <h1>Status Pesanan</h1>
                <a href="cek_pesanan.php" class="btn-kembali">Kembali</a>
            </div>
            <div class="status-content">
                <p class="status-text">
                    <?php echo $status_pesanan; // Pesan status sekarang dinamis ?>
                </p>
            </div>
        </div>
    </div>
</body>
</html>