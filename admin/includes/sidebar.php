<aside class="sidebar">
    <div class="sidebar-profile">
        <i class="bi bi-person-circle profile-icon"></i>
        <span class="profile-name">Admin</span>
    </div>

    <nav class="sidebar-nav">
        <ul>
            <li>
                <a href="index.php" class="<?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="menu.php" class="<?php echo ($currentPage == 'menu.php') ? 'active' : ''; ?>">
                    <i class="bi bi-journal-richtext"></i>
                    <span>Menu</span>
                </a>
            </li>
            <li>
                <a href="kategori.php" class="<?php echo ($currentPage == 'kategori.php') ? 'active' : ''; ?>">
                    <i class="bi bi-tags"></i>
                    <span>Kategori</span>
                </a>
            </li>
            <li>
                <a href="pesanan.php" class="<?php echo ($currentPage == 'pesanan.php') ? 'active' : ''; ?>">
                    <i class="bi bi-receipt"></i>
                    <span>Pesanan</span>
                </a>
            </li>
            <li>
                <a href="laporan.php" class="<?php echo ($currentPage == 'laporan.php') ? 'active' : ''; ?>">
                    <i class="bi bi-file-earmark-bar-graph"></i>
                    <span>Laporan</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>