<?php
// Panggil header yang sudah ada session_start() dan proteksi halaman
require_once 'includes/header.php';
require_once 'config/database.php';

// --- LOGIKA CRUD KATEGORI ---
$pesan = '';

// Proses form jika ada data yang dikirim (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $nama_kategori = trim($_POST['nama_kategori']);
    $id = $_POST['id'] ?? null;

    if ($action == 'create') {
        $stmt = $koneksi->prepare("INSERT INTO kategori (nama_kategori) VALUES (?)");
        $stmt->execute([$nama_kategori]);
        $pesan = 'Kategori berhasil ditambahkan.';
    } elseif ($action == 'update') {
        $stmt = $koneksi->prepare("UPDATE kategori SET nama_kategori=? WHERE id=?");
        $stmt->execute([$nama_kategori, $id]);
        $pesan = 'Kategori berhasil diperbarui.';
    }
}

// Proses delete jika ada parameter action=delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $koneksi->prepare("DELETE FROM kategori WHERE id=?");
        $stmt->execute([$_GET['id']]);
        $pesan = 'Kategori berhasil dihapus.';
    } catch (PDOException $e) {
        // Tangani error jika kategori masih digunakan di tabel menu (karena foreign key)
        if ($e->getCode() == '23000') {
             $pesan = 'Error: Kategori tidak dapat dihapus karena masih digunakan oleh beberapa menu.';
        } else {
            $pesan = 'Error: ' . $e->getMessage();
        }
    }
}

// Ambil semua data kategori untuk ditampilkan
$kategori_list = $koneksi->query("SELECT * FROM kategori ORDER BY id DESC")->fetchAll();
?>

<?php require_once 'includes/sidebar.php'; ?>

<div class="main-content">
    <?php require_once 'includes/navbar.php'; ?>
    
    <main class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <span>Data Kategori</span>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kategoriModal" id="btn-tambah-kategori">
                <i class="bi bi-plus-circle"></i> Tambah Kategori
            </button>
        </div>

        <?php if ($pesan): ?>
        <div class="alert <?php echo (strpos($pesan, 'Error') !== false) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
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
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kategori_list as $index => $item): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($item['nama_kategori']); ?></td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-warning btn-edit" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#kategoriModal"
                                        data-id="<?php echo $item['id']; ?>"
                                        data-nama_kategori="<?php echo htmlspecialchars($item['nama_kategori']); ?>">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </button>
                                    <a href="kategori.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Menghapus kategori akan menghapus semua menu di dalamnya. Apakah Anda yakin?')">
                                        <i class="bi bi-trash3"></i> Hapus
                                    </a>
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

<div class="modal fade" id="kategoriModal" tabindex="-1" aria-labelledby="kategoriModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kategoriModalLabel">Form Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="kategori.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id" id="kategori-id">
                    <input type="hidden" name="action" id="kategori-action" value="create">

                    <div class="mb-3">
                        <label for="nama_kategori" class="form-label">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" name="nama_kategori" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var kategoriModal = document.getElementById('kategoriModal');
    kategoriModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Tombol yang memicu modal
        var modalTitle = kategoriModal.querySelector('.modal-title');
        
        // Ambil elemen form
        var form = kategoriModal.querySelector('form');
        var actionInput = form.querySelector('#kategori-action');
        var idInput = form.querySelector('#kategori-id');
        var namaInput = form.querySelector('#nama_kategori');

        // Cek apakah tombol "Tambah" atau "Edit" yang diklik
        if (button.id === 'btn-tambah-kategori') {
            modalTitle.textContent = 'Tambah Kategori Baru';
            form.reset(); // Kosongkan form
            actionInput.value = 'create';
            idInput.value = '';
        } else {
            modalTitle.textContent = 'Edit Kategori';
            actionInput.value = 'update';
            
            // Isi form dengan data dari tombol edit
            idInput.value = button.dataset.id;
            namaInput.value = button.dataset.nama_kategori;
        }
    });
});
</script>