
<?php
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<body data-page-name="kontak">

    <?php require_once 'includes/navbar.php'; ?>

    <section class="about-section">
        <div class="about-pane" id="contact-content-pane">
            <div class="about-content">

                <h2 class="fade-in-element">Kenapa Pesan Lewat Website?</h2>
                <h3 class="fade-in-element">Karena Simpel Itu Keren.</h3>
                <p class="section-sub-text fade-in-element">Melalui website kami, Anda bisa:</p>

                <div class="features-list">
                    <div class="feature-item fade-in-element">
                        <div class="feature-icon"><i class="fas fa-pizza-slice"></i></div>
                        <div class="feature-text">
                            <h4>Jelajahi Menu Lengkap</h4>
                            <p>Lihat semua varian pizza kreatif kami dengan foto dan deskripsi yang jelas.</p>
                        </div>
                    </div>
                    <div class="feature-item fade-in-element">
                        <div class="feature-icon"><i class="fas fa-hand-pointer"></i></div>
                        <div class="feature-text">
                            <h4>Pesan Kapan Saja</h4>
                            <p>Dapur kami mungkin punya jam tutup, tapi website kami online 24/7.</p>
                        </div>
                    </div>
                    <div class="feature-item fade-in-element">
                        <div class="feature-icon"><i class="fas fa-id-card"></i></div>
                        <div class="feature-text">
                            <h4>Pembayaran Mudah & Aman</h4>
                            <p>Nikmati proses transaksi yang cepat dan aman tanpa perlu repot.</p>
                        </div>
                    </div>
                </div>

                <hr class="separator fade-in-element">

                <div class="social-links fade-in-element">
                    <a href="#" target="_blank" class="social-link-item">
                        <i class="fab fa-instagram"></i><span>pizza.kevvy</span>
                    </a>
                    <a href="#" target="_blank" class="social-link-item">
                        <i class="fab fa-facebook-f"></i><span>Pizza Kevvy</span>
                    </a>
                    <a href="https://wa.me/6281234567890" target="_blank" class="social-link-item">
                        <i class="fab fa-whatsapp"></i><span>0812-3456-7890</span>
                    </a>
                </div>

            </div>
        </div>

        <div class="about-pane fade-in-element" id="contact-image-pane"></div>
    </section>

    </body>
    <script>
    // 1. Logika untuk Animasi Masuk (Staggered Fade-in)
    document.addEventListener('DOMContentLoaded', function() {
        const elementsToFadeIn = document.querySelectorAll('.fade-in-element');
        
        // Loop melalui setiap elemen untuk diberi animasi
        elementsToFadeIn.forEach((el, index) => {
            // Beri jeda animasi berdasarkan urutan elemen (index)
            setTimeout(() => {
                el.classList.add('is-visible');
            }, 100 * (index + 1)); // elemen pertama delay 100ms, kedua 200ms, dst.
        });
    });

    // 2. Logika untuk Animasi Keluar (tetap sama)
    const transitionLinks = document.querySelectorAll('a.page-transition');
    const contentPane = document.querySelector('#contact-content-pane');
    const imagePane = document.querySelector('#contact-image-pane');
    transitionLinks.forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const destinationUrl = this.href;
            if (contentPane) contentPane.classList.add('slide-out-left');
            if (imagePane) imagePane.classList.add('slide-out-left');
            setTimeout(() => {
                window.location.href = destinationUrl;
            }, 700);
        });
    });
</script>
