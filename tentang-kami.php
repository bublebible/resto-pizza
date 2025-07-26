<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<body data-page-name="tentang-kami">

    <?php require_once 'includes/navbar.php'; ?>

    <section class="about-section">
        <div class="about-pane" id="about-image-pane"></div>

        <div class="about-pane" id="about-content-pane">
            <div class="about-content fade-in-element">
                <h2>Tentang Kami</h2>
                <h3>Kisah & Filosofi Kami</h3>
                <p>
                    Area 14 Pizza Co. resmi menyalakan oven pertamanya pada tahun 2025. Didirikan oleh sekumpulan anak muda yang tumbuh di era digital, kami percaya bahwa pizza bukan hanya makanan, tapi sebuah kanvas untuk berekspresi. Kami bosan dengan pilihan yang itu-itu saja. Kami ingin menciptakan pizza yang berani, otentik, dan tentunya, sangat lezat—pizza yang benar-benar mewakili generasi kami.
                </p>
                <p>
                    Di dapur kami yang berlokasi di Jalan Parangtritis KM 14, kami tidak mengenal kompromi. Setiap adonan kami olah dengan teknik modern untuk menghasilkan tekstur yang sempurna: renyah di luar, namun lembut di dalam. Saus kami racik dari nol menggunakan bahan-bahan segar pilihan, dan kami tidak takut untuk bereksperimen dengan kombinasi topping yang unik dan tak terduga, di samping tetap menghormati resep-resep klasik.
                </p>
            </div>
        </div>
    </section>
<script>
    // Animasi Masuk (Fade-in saat halaman dimuat)
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelector('.fade-in-element')?.classList.add('is-visible');
    });

    // Animasi Keluar (Saat meninggalkan halaman ini)
    document.querySelectorAll('a.page-transition').forEach(link => {
        link.addEventListener('click', event => {
            event.preventDefault();
            const destinationUrl = link.href;

            // Target elemen di halaman INI
            const imagePane = document.querySelector('#about-image-pane');
            const contentPane = document.querySelector('#about-content-pane');

            // KEDUA ELEMEN MELUNCUR KE KANAN
            if(imagePane) imagePane.classList.add('slide-out-right');
            if(contentPane) contentPane.classList.add('slide-out-right');

            setTimeout(() => { window.location.href = destinationUrl; }, 700);
        });
    });
</script>
</body>