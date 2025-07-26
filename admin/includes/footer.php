</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function fetchNotifications() {
        fetch('api_get_notif.php')
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notif-badge');
                const notifList = document.getElementById('notif-list');

                // Update badge counter
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'block';
                } else {
                    badge.style.display = 'none';
                }

                // Update notification list
                notifList.innerHTML = ''; // Kosongkan daftar
                if (data.notifications.length > 0) {
                    data.notifications.forEach(notif => {
                        const li = document.createElement('li');
                        li.innerHTML = `
                        <a class="dropdown-item" href="pesanan.php">
                            <div class="notif-text">Pesanan Baru #${notif.id}</div>
                            <div class="notif-time">dari ${notif.nama_pelanggan}</div>
                        </a>
                    `;
                        notifList.appendChild(li);
                    });
                    notifList.innerHTML += '<li><hr class="dropdown-divider"></li>';
                    notifList.innerHTML += '<li><a class="dropdown-item text-center" href="pesanan.php">Lihat Semua Pesanan</a></li>';
                } else {
                    notifList.innerHTML = '<li><a class="dropdown-item text-center" href="#">Tidak ada notifikasi baru</a></li>';
                }
            })
            .catch(error => console.error('Gagal mengambil notifikasi:', error));
    }

    // Panggil fungsi saat halaman pertama kali dimuat
    document.addEventListener('DOMContentLoaded', fetchNotifications);

    // Panggil fungsi setiap 30 detik untuk memeriksa notifikasi baru
    setInterval(fetchNotifications, 30000);
</script>
</body>

</html>