<header class="top-navbar">
    <div class="d-flex align-items-center">
        <button class="sidebar-toggler me-3">
            <i class="bi bi-list"></i>
        </button>
        <h1 class="page-title">Dashboard</h1>
    </div>
    
    <div class="d-flex align-items-center">
        <div class="dropdown notification-dropdown me-3">
            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownNotif" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-bell-fill fs-5"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notif-badge" style="display: none;">
                    0
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="dropdownNotif" id="notif-list">
                <li><a class="dropdown-item text-center" href="#">Memuat...</a></li>
            </ul>
        </div>
        <div class="dropdown user-dropdown">
            <a href="#" class="d-block link-dark text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="assets/images/logo.png" alt="Logo" width="32" height="32" class="rounded-circle">
            </a>
            <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="dropdownUser">
                <li><a class="dropdown-item" href="#">Profil</a></li>
                <li><a class="dropdown-item" href="#">Pengaturan</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</header>