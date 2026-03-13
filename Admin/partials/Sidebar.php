<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!-- Include CSS for Sidebar -->
<link rel="stylesheet" href="partials/Sidebar.css">

<!-- Mobile Admin Header -->
<div class="mobile-admin-header">
    <a href="../index.php" class="mobile-logo-wrap">
        <img src="../Public/img/Logo_Yayasan-new.png" alt="Logo">
        <span><span style="color: var(--primary);">Kaya</span>Tene</span>
    </a>
    <button class="hamburger-admin" id="hamburgerAdmin">
        <i class="fa-solid fa-bars-staggered"></i>
    </button>
</div>

<!-- Overlay for mobile sidebar -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="adminSidebar">
    <button class="close-sidebar" id="closeSidebar"><i class="fa-solid fa-xmark"></i></button>

    <a href="dashboard.php" class="admin-logo">
        <img src="../Public/img/Logo_Yayasan-new.png" alt="Logo">
        <div><span style="color: var(--primary);">Kaya</span>Tene</div>
    </a>

    <div class="nav-group-label">Menu Utama</div>
    <ul class="nav-menu">
        <li>
            <a href="dashboard.php" class="<?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="pesan.php" class="<?php echo $current_page == 'pesan.php' ? 'active' : ''; ?>">
                <i class="fa-solid fa-envelope"></i> <span>Pesan Publik</span>
            </a>
        </li>
    </ul>

    <div class="nav-group-label">Manajemen Konten</div>
    <ul class="nav-menu">
        <li>
            <a href="berita.php" class="<?php echo in_array($current_page, ['berita.php', 'tambah-berita.php', 'edit-berita.php']) ? 'active' : ''; ?>">
                <i class="fa-solid fa-newspaper"></i> <span>Daftar Berita</span>
            </a>
        </li>
        <li>
            <a href="program.php" class="<?php echo in_array($current_page, ['program.php', 'tambah-program.php', 'edit-program.php']) ? 'active' : ''; ?>">
                <i class="fa-solid fa-hand-holding-heart"></i> <span>Daftar Program</span>
            </a>
        </li>
        <li>
            <a href="galeri.php" class="<?php echo in_array($current_page, ['galeri.php', 'tambah-galeri.php', 'edit-galeri.php']) ? 'active' : ''; ?>">
                <i class="fa-solid fa-images"></i> <span>Daftar Galeri</span>
            </a>
        </li>
        <li>
            <a href="carousel.php" class="<?php echo in_array($current_page, ['carousel.php', 'tambah-carousel.php', 'edit-carousel.php']) ? 'active' : ''; ?>">
                <i class="fa-solid fa-desktop"></i> <span>Carousel</span>
            </a>
        </li>
        <li>
            <a href="struktur-organisasi.php" class="<?php echo in_array($current_page, ['struktur-organisasi.php', 'tambah-struktur.php', 'edit-struktur.php']) ? 'active' : ''; ?>">
                <i class="fa-solid fa-sitemap"></i> <span>Struktural</span>
            </a>
        </li>
    </ul>

    <div class="nav-group-label">Sistem</div>
    <ul class="nav-menu">
        <li>
            <a href="../index.php" target="_blank">
                <i class="fa-solid fa-globe"></i> <span>Lihat Website</span>
            </a>
        </li>
        <li style="margin-top: 10px;">
            <a href="logout.php" style="color: #ff6b6b;" onmouseover="this.style.background='rgba(255,107,107,0.1)'" onmouseout="this.style.background='none'">
                <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
            </a>
        </li>
    </ul>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const hamburger = document.getElementById('hamburgerAdmin');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const closeBtn = document.getElementById('closeSidebar');

        function toggleSidebar() {
            if (sidebar) sidebar.classList.toggle('active');
            if (overlay) overlay.classList.toggle('active');
        }

        if (hamburger) hamburger.addEventListener('click', toggleSidebar);
        if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);

        // Close sidebar on window resize if visible
        window.addEventListener('resize', () => {
            if (window.innerWidth > 1200) {
                if (sidebar) sidebar.classList.remove('active');
                if (overlay) overlay.classList.remove('active');
            }
        });
    });
</script>
