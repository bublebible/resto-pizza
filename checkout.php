<?php
// Pastikan session sudah dimulai di header.php
require_once 'includes/header.php';
require_once 'config/database.php';
require_once 'includes/navbar.php';

// Lindungi halaman: jika keranjang kosong, tendang kembali
if (empty($_SESSION['keranjang'])) {
    header('Location: keranjang.php');
    exit();
}
?>

<main class="container mt-5 pt-5">
    <h2 class="mb-4">Checkout</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Pemesan</h5>
                    <form action="proses_pesanan.php" method="POST">
                        <div class="mb-3">
                            <label for="nama_pelanggan" class="form-label">Nama Lengkap Anda</label>
                            <input type="text" class="form-control" name="nama_pelanggan" id="nama_pelanggan" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Jenis Pesanan</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_pesanan" id="makan_ditempat" value="Makan di Tempat" checked>
                                <label class="form-check-label" for="makan_ditempat">
                                    Makan di Tempat
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="jenis_pesanan" id="take_away" value="Take Away">
                                <label class="form-check-label" for="take_away">
                                    Take Away
                                </label>
                            </div>
                        </div>

                        <div class="mb-3" id="kolom-nomor-meja">
                            <label for="nomor_meja" class="form-label">Nomor Meja</label>
                            <input type="text" class="form-control" name="nomor_meja" id="nomor_meja" placeholder="Contoh: 12" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">Buat Pesanan Sekarang</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioMakanDitempat = document.getElementById('makan_ditempat');
    const radioTakeAway = document.getElementById('take_away');
    const kolomNomorMeja = document.getElementById('kolom-nomor-meja');
    const inputNomorMeja = document.getElementById('nomor_meja');

    function toggleNomorMeja() {
        if (radioMakanDitempat.checked) {
            kolomNomorMeja.style.display = 'block'; // Tampilkan
            inputNomorMeja.required = true;         // Wajib diisi
        } else {
            kolomNomorMeja.style.display = 'none';  // Sembunyikan
            inputNomorMeja.required = false;        // Tidak wajib diisi
            inputNomorMeja.value = '';              // Kosongkan nilainya
        }
    }

    // Panggil fungsi saat halaman pertama kali dimuat
    toggleNomorMeja();

    // Tambahkan event listener untuk setiap perubahan pada radio button
    radioMakanDitempat.addEventListener('change', toggleNomorMeja);
    radioTakeAway.addEventListener('change', toggleNomorMeja);
});
</script>