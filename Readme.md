# 🍕 Website Pemesanan Pizza "Pizza Kevvy"

Selamat datang di repositori Pizza Kevvy! Ini adalah proyek website pemesanan pizza fungsional dan modern yang dibangun dengan fokus pada pengalaman pengguna yang dinamis dan interaktif. Proyek ini menggabungkan backend PHP untuk fungsionalitas inti dengan animasi CSS3 dan JavaScript untuk menciptakan tampilan yang menarik.

![Screenshot Halaman Utama](https://github.com/user-attachments/assets/e48a26b2-c244-4be1-859a-192d0f37dbb5)



---

## ✨ Fitur Utama

-   **Desain Modern & Responsif**: Tampilan yang diadaptasi dari desain Figma, dapat menyesuaikan diri dengan berbagai ukuran layar, dari desktop hingga mobile.
-   **Halaman Dinamis**: Konten untuk halaman menu, "Tentang Kami", dan "Kontak" dibuat dinamis dan mudah dikelola.
-   **Animasi Scroll Profesional**: Implementasi *Animate On Scroll* (AOS) untuk memunculkan elemen secara elegan saat pengguna melakukan scroll.
-   **Navbar "Flying Glass"**: Navbar yang awalnya menyatu dengan halaman akan berubah menjadi efek kaca transparan yang "mengambang" saat halaman di-scroll.
-   **Transisi Halaman Unik**: Efek animasi "slide" dan "fade" saat berpindah antar halaman utama (Beranda, Tentang Kami, Kontak) untuk pengalaman navigasi yang sinematik.
-   **Sistem Keranjang Belanja**:
    -   Fungsionalitas tambah, kurang, dan hapus item.
    -   Menggunakan **PHP Session** untuk menyimpan data keranjang secara persisten.
    -   Tampilan berbeda untuk kondisi keranjang kosong dan berisi.
-   **Halaman Cek Status**: Halaman interaktif dengan animasi dua langkah untuk memeriksa status pesanan.

---

## 💻 Teknologi yang Digunakan

-   **Frontend**:
    -   HTML5
    -   CSS3 (dengan Flexbox & Grid)
    -   JavaScript (ES6)
-   **Backend**:
    -   PHP (untuk logika server-side dan session management)
-   **Library & Tools**:
    -   **AOS (Animate On Scroll)**: Untuk animasi saat scroll.
    -   **Font Awesome**: Untuk ikon-ikon yang digunakan di seluruh website.
    -   **Google Fonts**: Untuk tipografi yang sesuai dengan desain.

---

## 📁 Struktur Folder

Struktur folder proyek ini dirancang agar mudah dikelola dan dikembangkan lebih lanjut.

```
/pizza-kevvy/
├── includes/               # File-file yang di-include berulang kali
│   ├── header.php
│   └── navbar.php
├── config/                 # Konfigurasi database
│   └── database.php
├── assets/                 # (Direkomendasikan) Untuk menyimpan aset
│   ├── css/
│   │   └── style.css
│   └── images/
│       ├── pizza1.png
│       └── ...
├── index.php               # Halaman Beranda
├── tentang-kami.php
├── kontak.php
├── keranjang.php
├── cek-status.php
├── tambah-ke-keranjang.php # Skrip proses (tidak ada tampilan)
├── update-keranjang.php    # Skrip proses (tidak ada tampilan)
└── README.md               # Dokumentasi proyek ini
```

---

## 🚀 Instalasi dan Setup

Untuk menjalankan proyek ini di lingkungan lokal Anda, ikuti langkah-langkah berikut:

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/](https://github.com/)[username-anda]/pizza-kevvy.git
    ```
2.  **Server Lokal**
    -   Pindahkan folder proyek `pizza-kevvy` ke dalam direktori `htdocs` (untuk XAMPP) atau `www` (untuk WAMP).
    -   Jalankan Apache Web Server dan MySQL dari control panel server lokal Anda.
3.  **Database**
    -   Buka `phpMyAdmin` (`http://localhost/phpmyadmin`).
    -   Buat database baru.
    -   Impor file `.sql` (jika ada) ke dalam database yang baru dibuat.
    -   Sesuaikan detail koneksi database (nama host, username, password, nama db) di dalam file `config/database.php`.
4.  **Jalankan Proyek**
    -   Buka browser Anda dan akses proyek melalui `http://localhost/pizza-kevvy/`.

---

## 👨‍💻 Kontributor

Dibuat dan dikembangkan oleh **Friena Sellisya Saputri**.

Terima kasih telah mengunjungi proyek ini!
