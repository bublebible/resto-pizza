<?php
// Panggil header yang sudah ada session_start() dan proteksi halaman
require_once 'includes/header.php';
require_once 'config/database.php';

// --- LOGIKA CRUD DI SINI ---
$pesan = '';

// Proses form jika ada data yang dikirim (POST)
// Proses form jika ada data yang dikirim (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $nama_menu = trim($_POST['nama_menu']);
    $kategori_id = $_POST['kategori_id'];
    $harga = trim($_POST['harga']);
    $deskripsi = trim($_POST['deskripsi']);
    $id = $_POST['id'] ?? null;
    $gambar_lama = $_POST['gambar_lama'] ?? null;

    // --- VALIDASI INPUT ---
    $errors = [];
    if (empty($nama_menu)) {
        $errors[] = "Nama menu harus diisi.";
    }
    if (empty($kategori_id)) {
        $errors[] = "Kategori harus dipilih.";
    }
    if (empty($harga)) {
        $errors[] = "Harga harus diisi.";
    }

    // Validasi gambar hanya wajib saat membuat menu baru (create)
    if ($action == 'create' && (!isset($_FILES['gambar']) || $_FILES['gambar']['error'] != 0)) {
        $errors[] = "Gambar menu harus diunggah.";
    }

    // Jika tidak ada error, lanjutkan proses
    if (empty($errors)) {
        // Logika upload gambar
        $gambar = $gambar_lama;
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            $target_dir = "../uploads/"; // Pastikan folder 'uploads' ada di direktori utama
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
            $nama_file = uniqid() . '-' . basename($_FILES["gambar"]["name"]);
            $target_file = $target_dir . $nama_file;
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                // Hapus gambar lama jika ada dan ini adalah proses update
                if ($action == 'update' && $gambar_lama && file_exists($target_dir . $gambar_lama)) {
                    unlink($target_dir . $gambar_lama);
                }
                $gambar = $nama_file;
            }
        }

        if ($action == 'create') {
            $stmt = $koneksi->prepare("INSERT INTO menu (nama_menu, kategori_id, harga, deskripsi, gambar) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nama_menu, $kategori_id, $harga, $deskripsi, $gambar]);
            $pesan = 'Menu berhasil ditambahkan.';
        } elseif ($action == 'update') {
            $stmt = $koneksi->prepare("UPDATE menu SET nama_menu=?, kategori_id=?, harga=?, deskripsi=?, gambar=? WHERE id=?");
            $stmt->execute([$nama_menu, $kategori_id, $harga, $deskripsi, $gambar, $id]);
            $pesan = 'Menu berhasil diperbarui.';
        }
    } else {
        // Jika ada error, gabungkan semua pesan menjadi satu
        $pesan = implode('<br>', $errors);
    }
}

// Proses delete jika ada parameter action=delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    // Ambil nama gambar untuk dihapus dari folder
    $stmt = $koneksi->prepare("SELECT gambar FROM menu WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $menu = $stmt->fetch();
    if ($menu && $menu['gambar']) {
        unlink("../uploads/" . $menu['gambar']);
    }
    // Hapus data dari database
    $stmt = $koneksi->prepare("DELETE FROM menu WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $pesan = 'Menu berhasil dihapus.';
}

// Ambil semua data menu untuk ditampilkan, digabungkan dengan kategori
$menu_items = $koneksi->query("
    SELECT menu.*, kategori.nama_kategori 
    FROM menu 
    JOIN kategori ON menu.kategori_id = kategori.id 
    ORDER BY menu.id DESC
")->fetchAll();

// Ambil semua data kategori untuk dropdown form
$kategori_list = $koneksi->query("SELECT * FROM kategori ORDER BY nama_kategori")->fetchAll();
?>

<?php require_once 'includes/sidebar.php'; ?>

<div class="main-content">
    <?php require_once 'includes/navbar.php'; ?>

    <main class="content">
        <div class="page-header d-flex justify-content-between align-items-center">
            <span>Data Menu</span>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#menuModal" id="btn-tambah-menu">
                <i class="bi bi-plus-circle"></i> Tambah Menu
            </button>
        </div>

        <?php if ($pesan): ?>
            <div class="alert <?php echo !empty($errors) ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show" role="alert">
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
                                <th>Gambar</th>
                                <th>Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($menu_items as $index => $item): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td>
                                        <img src="../uploads/<?php echo htmlspecialchars($item['gambar']); ?>" alt="<?php echo htmlspecialchars($item['nama_menu']); ?>" class="img-thumbnail" width="80">
                                    </td>
                                    <td><?php echo htmlspecialchars($item['nama_menu']); ?></td>
                                    <td><?php echo htmlspecialchars($item['nama_kategori']); ?></td>
                                    <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-warning btn-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#menuModal"
                                            data-id="<?php echo $item['id']; ?>"
                                            data-nama_menu="<?php echo htmlspecialchars($item['nama_menu']); ?>"
                                            data-kategori_id="<?php echo $item['kategori_id']; ?>"
                                            data-harga="<?php echo $item['harga']; ?>"
                                            data-deskripsi="<?php echo htmlspecialchars($item['deskripsi']); ?>"
                                            data-gambar="<?php echo htmlspecialchars($item['gambar']); ?>">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>
                                        <a href="menu.php?action=delete&id=<?php echo $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus menu ini?')">
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

<div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="menuModalLabel">Form Menu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="menu.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="menu-id">
                    <input type="hidden" name="action" id="menu-action" value="create">
                    <input type="hidden" name="gambar_lama" id="gambar-lama">

                    <div class="mb-3">
                        <label for="nama_menu" class="form-label">Nama Menu</label>
                        <input type="text" class="form-control" id="nama_menu" name="nama_menu" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori_id" name="kategori_id" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori_list as $kategori): ?>
                                <option value="<?php echo $kategori['id']; ?>"><?php echo htmlspecialchars($kategori['nama_kategori']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="harga" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar Menu</label>
                        <input class="form-control" type="file" id="gambar" name="gambar">
                        <small id="gambar-info" class="form-text text-muted"></small>
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
        var menuModal = document.getElementById('menuModal');
        menuModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget; // Tombol yang memicu modal
            var modalTitle = menuModal.querySelector('.modal-title');

            // Ambil elemen form
            var form = menuModal.querySelector('form');
            var actionInput = form.querySelector('#menu-action');
            var idInput = form.querySelector('#menu-id');
            var namaInput = form.querySelector('#nama_menu');
            var kategoriInput = form.querySelector('#kategori_id');
            var hargaInput = form.querySelector('#harga');
            var deskripsiInput = form.querySelector('#deskripsi');
            var gambarLamaInput = form.querySelector('#gambar-lama');
            var gambarInfo = form.querySelector('#gambar-info');

            // Cek apakah tombol "Tambah" atau "Edit" yang diklik
            if (button.id === 'btn-tambah-menu') {
                modalTitle.textContent = 'Tambah Menu Baru';
                form.reset(); // Kosongkan form
                actionInput.value = 'create';
                idInput.value = '';
                gambarLamaInput.value = '';
                gambarInfo.textContent = 'Pilih file gambar baru.';
            } else {
                modalTitle.textContent = 'Edit Menu';
                actionInput.value = 'update';

                // Isi form dengan data dari tombol edit
                idInput.value = button.dataset.id;
                namaInput.value = button.dataset.nama_menu;
                kategoriInput.value = button.dataset.kategori_id;
                hargaInput.value = button.dataset.harga;
                deskripsiInput.value = button.dataset.deskripsi;
                gambarLamaInput.value = button.dataset.gambar;
                gambarInfo.textContent = 'Kosongkan jika tidak ingin mengubah gambar.';
            }
        });
    });
</script>