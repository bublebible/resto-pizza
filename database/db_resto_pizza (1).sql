-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 24 Jul 2025 pada 13.15
-- Versi server: 8.0.30
-- Versi PHP: 8.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_resto_pizza`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id` int UNSIGNED NOT NULL,
  `pesanan_id` int UNSIGNED NOT NULL,
  `menu_id` int UNSIGNED NOT NULL,
  `nama_menu` varchar(150) NOT NULL,
  `jumlah` int NOT NULL,
  `subtotal` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id`, `pesanan_id`, `menu_id`, `nama_menu`, `jumlah`, `subtotal`) VALUES
(1, 1, 5, 'Pizza Jamur', 1, 120000),
(2, 2, 5, 'Pizza Jamur', 1, 120000),
(3, 3, 1, 'Pizza Jamur', 1, 1500000),
(4, 4, 1, 'Pizza Jamur', 1, 1500000),
(8, 8, 5, 'Pizza Jamur', 1, 120000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` int UNSIGNED NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`) VALUES
(1, 'Makanan'),
(2, 'Minuman');

-- --------------------------------------------------------

--
-- Struktur dari tabel `menu`
--

CREATE TABLE `menu` (
  `id` int UNSIGNED NOT NULL,
  `kategori_id` int UNSIGNED NOT NULL,
  `nama_menu` varchar(150) NOT NULL,
  `harga` int NOT NULL,
  `deskripsi` text,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `menu`
--

INSERT INTO `menu` (`id`, `kategori_id`, `nama_menu`, `harga`, `deskripsi`, `gambar`) VALUES
(1, 1, 'Pizza Jamur', 1500000, 'ini pizza enak asli', '68653197cfa1b-image.png'),
(3, 1, 'Pizza Jamur', 120000, 'coba', '686536682b43d-Group 6.png'),
(4, 1, 'Pizza Jamur', 120000, 'coba', '6865366bd1288-Group 6.png'),
(5, 1, 'Pizza Jamur', 120000, 'coba', '6865366ebfa4e-Group 6.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED DEFAULT NULL,
  `kode_pesanan` varchar(20) DEFAULT NULL,
  `nama_pelanggan` varchar(150) NOT NULL,
  `nomor_meja` varchar(50) DEFAULT NULL,
  `total_harga` int NOT NULL,
  `status_pesanan` enum('Baru','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Baru',
  `tanggal_pesanan` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id`, `user_id`, `kode_pesanan`, `nama_pelanggan`, `nomor_meja`, `total_harga`, `status_pesanan`, `tanggal_pesanan`) VALUES
(1, NULL, NULL, 'budi', NULL, 120000, 'Selesai', '2025-07-02 14:17:22'),
(2, NULL, NULL, 'fina', NULL, 120000, 'Baru', '2025-07-07 12:35:29'),
(3, 5, NULL, 'fina', NULL, 1500000, 'Baru', '2025-07-07 12:40:22'),
(4, 5, 'PIZZA-004', 'fina', NULL, 1500000, 'Diproses', '2025-07-07 12:49:55'),
(8, 2, 'PIZZA-008', 'fina', '12', 120000, 'Baru', '2025-07-21 12:13:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pizzas`
--

CREATE TABLE `pizzas` (
  `id` int NOT NULL,
  `nama_pizza` varchar(100) NOT NULL,
  `deskripsi` text NOT NULL,
  `harga` int NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `pizzas`
--

INSERT INTO `pizzas` (`id`, `nama_pizza`, `deskripsi`, `harga`, `gambar`) VALUES
(1, 'Meat Lover Pizza', 'Pizza dengan topping daging yang berlimpah dan renyahnya pinggiran, cocok untuk menemani acaramu.', 85000, 'meat-lover.png'),
(2, 'Chicken BBQ Pizza', 'Potongan ayam lezat dengan saus BBQ khas yang meresap sempurna di setiap gigitan.', 95000, 'chicken-bbq.png'),
(3, 'Tuna Delight Pizza', 'Perpaduan unik ikan tuna segar dengan lelehan keju mozarella premium yang menggugah selera.', 75000, 'tuna-delight.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` tinyint UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'customer');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `role_id` tinyint UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role_id`, `username`, `email`, `password`, `session_id`, `created_at`, `updated_at`) VALUES
(2, 1, 'admin', 'admin@pizzakevvy.com', '$2y$10$n.OITQZYQelYYlMU.oYEz.2x1/AcSDKqqN...zEH/4/gCXQ5RLxDG', NULL, '2025-07-01 10:14:02', '2025-07-01 10:48:09'),
(3, 2, 'budi', 'budi.sanjaya@example.com', '$2y$10$FvK.iXG5O3aF9L8r/yU0oO4c6V3r.G.N1Pz.Q5.qL5oD7k3.R8iCq', NULL, '2025-07-01 10:14:02', '2025-07-01 10:14:02'),
(4, 2, 'admin1', 'admin1@example.com', '$2y$10$btJzhvGbYJxX6qp.3Ck8E.WjJ9BTFIF8RQJXpIR1wMqGV9WN7h9i.', NULL, '2025-07-01 10:19:45', '2025-07-01 10:19:45'),
(5, 2, 'fina', 'fina@example.com', '$2y$10$ASb5hTpXwjFrSVycfTCuOeBYMQPbqfar5BadeocPovjFyiIbhV33.', NULL, '2025-07-07 12:35:05', '2025-07-07 12:35:05');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_pesanan_id_foreign` (`pesanan_id`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_kategori_id_foreign` (`kategori_id`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_pesanan` (`kode_pesanan`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `pizzas`
--
ALTER TABLE `pizzas`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `menu`
--
ALTER TABLE `menu`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `pizzas`
--
ALTER TABLE `pizzas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` tinyint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_id_foreign` FOREIGN KEY (`pesanan_id`) REFERENCES `pesanan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `menu_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
