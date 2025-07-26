<?php
// WAJIB: Ini harus menjadi baris pertama, tanpa ada spasi atau teks lain di atasnya.
session_start();

// Tentukan mode halaman berdasarkan parameter URL
$is_register_mode = isset($_GET['action']) && $_GET['action'] === 'register';

// Tentukan class untuk body, agar CSS bisa mengubah layout
$page_class = $is_register_mode ? 'register-mode' : 'login-mode';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $is_register_mode ? 'Daftar Akun Baru' : 'Masuk ke Akun'; ?> - Pizza Kevvy</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="./assets/css/style_auth.css">
    
    

    <?php 
    // Panggil koneksi database
    require_once 'config/database.php'; 
    ?>
</head>
<body class="<?php echo $page_class; ?>">

    <div class="pizza-bg pizza-left">
        <img src="./assets/images/pizza_slice.png" alt="Pizza background" style="margin-left: -50%;">
    </div>
    <div class="pizza-bg pizza-right">
        <img src="./assets/images/pizza_slice.png" alt="Pizza background" style="margin-left: 50%; transform: rotate(180deg);">
    </div>


    <div class="auth-container">
        <div class="form-box">
            <div class="logo-container">
                <img src="./assets/images/kevy.png" alt="Logo Pizza Kevvy">
            </div>

            <?php if ($is_register_mode): ?>
                <h1>Daftar</h1>
                <form action="proses_register.php" method="POST">
                    <div class="input-group">
                        <label for="username">USERNAME</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="input-group">
                        <label for="email">E-MAIL</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="input-group">
                        <label for="password">PASSWORD</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn-submit">Daftar</button>
                </form>
                <div class="form-footer">
                    <p>Sudah mempunyai akun? <a href="login.php">Log in disini</a></p>
                </div>
            <?php else: ?>
                <h1>Masuk</h1>
                <form action="proses_login.php" method="POST">
                    <div class="input-group">
                        <label for="username">USERNAME</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div class="input-group">
                        <label for="password">PASSWORD</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn-submit">Masuk</button>
                </form>
                <div class="form-footer">
                    <p>Belum memiliki akun? <a href="login.php?action=register">Daftar disini</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
if (isset($_GET['error']) && $_GET['error'] == 'already_logged_in') {
    echo "
    <script>
        Swal.fire({
            title: 'Login Gagal',
            text: 'Akun ini sedang digunakan di perangkat lain. Silakan logout terlebih dahulu.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
    </script>
    ";
}
?>
<?php
// KODE PEMICU SWEETALERT (diletakkan di akhir sebelum body ditutup)
// Cek apakah ada session pesan error registrasi
if (isset($_SESSION['error_register'])) {
    echo "
    <script>
        Swal.fire({
            title: 'Registrasi Gagal',
            text: '" . addslashes($_SESSION['error_register']) . "',
            icon: 'error',
            confirmButtonText: 'Coba Lagi'
        });
    </script>
    ";
    unset($_SESSION['error_register']); // Hapus session setelah ditampilkan
}

// Cek juga untuk pesan sukses registrasi
if (isset($_SESSION['pesan_sukses'])) {
    echo "
    <script>
        Swal.fire({
            title: 'Berhasil!',
            text: '" . addslashes($_SESSION['pesan_sukses']) . "',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
    ";
    unset($_SESSION['pesan_sukses']); // Hapus session setelah ditampilkan
}
?>
</body>
</html>