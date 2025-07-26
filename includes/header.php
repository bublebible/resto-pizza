<?php
session_start(); // Pastikan session dimulai sebelum mengaksesnya
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Kevvy - Pesan Pizza Favoritmu</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>

<body>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Pilih elemen navbar Anda berdasarkan class-nya
        const navbar = document.querySelector('.navbar');

        // Tambahkan event listener untuk mendeteksi scroll
        window.addEventListener('scroll', () => {
            // Cek jika posisi scroll lebih dari 50px dari atas
            if (window.scrollY > 50) {
                // Jika ya, tambahkan class 'navbar-scrolled'
                navbar.classList.add('navbar-scrolled');
            } else {
                // Jika tidak (kembali ke atas), hapus class 'navbar-scrolled'
                navbar.classList.remove('navbar-scrolled');
            }
        });
    </script>
    <?php
    // Cek apakah ada session pesan sukses
    if (isset($_SESSION['pesan_sukses'])) {
        // Jika ada, tampilkan script SweetAlert
        echo "
    <script>
        Swal.fire({
            title: 'Pesanan Berhasil!',
            text: '" . addslashes($_SESSION['pesan_sukses']) . "',
            icon: 'success',
            confirmButtonText: 'Mantap!'
        });
    </script>
    ";

        // Hapus session setelah pesan ditampilkan agar tidak muncul lagi
        unset($_SESSION['pesan_sukses']);
    }
    ?>
</body>