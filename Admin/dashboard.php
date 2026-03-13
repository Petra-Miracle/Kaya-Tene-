<?php
session_start();
require_once '../config/Connection.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_username'];

// Handle delete news request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    // Optional: Fetch image name and delete from folder first
    $stmt = $conn->prepare("SELECT gambar FROM Berita WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['gambar']) && file_exists("../uploads/" . $row['gambar'])) {
            unlink("../uploads/" . $row['gambar']);
        }
    }

    // Delete from DB
    $stmt = $conn->prepare("DELETE FROM Berita WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: dashboard.php?msg=deleted");
    exit();
}

// Handle delete gallery request
if (isset($_GET['delete_galeri_id'])) {
    $delete_galeri_id = intval($_GET['delete_galeri_id']);
    // Fetch image name and delete from folder first
    $stmt = $conn->prepare("SELECT gambar FROM Galeri WHERE id = ?");
    $stmt->bind_param("i", $delete_galeri_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['gambar']) && file_exists("../uploads/" . $row['gambar'])) {
            unlink("../uploads/" . $row['gambar']);
        }
    }

    // Delete from DB
    $stmt = $conn->prepare("DELETE FROM Galeri WHERE id = ?");
    $stmt->bind_param("i", $delete_galeri_id);
    $stmt->execute();
    header("Location: dashboard.php?msg=galeri_deleted");
    exit();
}

// Handle delete program request
if (isset($_GET['delete_program_id'])) {
    $delete_program_id = intval($_GET['delete_program_id']);
    // Fetch image name and delete from folder first
    $stmt = $conn->prepare("SELECT gambar FROM Programs WHERE id = ?");
    $stmt->bind_param("i", $delete_program_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['gambar']) && file_exists("../uploads/" . $row['gambar'])) {
            unlink("../uploads/" . $row['gambar']);
        }
    }

    // Delete from DB
    $stmt = $conn->prepare("DELETE FROM Programs WHERE id = ?");
    $stmt->bind_param("i", $delete_program_id);
    $stmt->execute();
    header("Location: dashboard.php?msg=program_deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .stat-card {
            padding: 35px;
            border-radius: 24px;
            border-left: 6px solid var(--primary);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, rgba(255, 90, 0, 0.1), transparent);
            border-radius: 50%;
            z-index: 0;
        }

        .stat-title {
            color: var(--text-muted);
            font-size: 1.15rem;
            margin-bottom: 15px;
            font-weight: 500;
            position: relative;
            z-index: 1;
        }

        .stat-value {
            font-size: 3rem;
            font-weight: 800;
            color: var(--text-main);
            position: relative;
            z-index: 1;
        }

        .table-container {
            border-radius: 24px;
            padding: 40px;
            margin-bottom: 40px;
        }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        th,
        td {
            padding: 20px;
            text-align: left;
        }

        th {
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1.5px;
            border-bottom: 2px solid var(--glass-border);
        }

        td {
            color: var(--text-main);
            background: var(--bg-card);
            border-top: 1px solid var(--glass-highlight);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        td:first-child {
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
            border-left: 1px solid var(--glass-highlight);
        }

        td:last-child {
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
            border-right: 1px solid rgba(0, 0, 0, 0.2);
        }

        tr {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        tbody tr:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-sm);
        }

        tbody tr:hover td {
            background: rgba(255, 90, 0, 0.05);
        }

        .action-btns {
            display: flex;
            gap: 12px;
        }

        .btn-small {
            padding: 8px 18px;
            font-size: 0.9rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .btn-danger {
            background: rgba(220, 53, 69, 0.1);
            color: #ff4757;
            border: 1px solid rgba(220, 53, 69, 0.2);
        }

        .btn-danger:hover {
            background: #ff4757;
            color: white;
            box-shadow: 0 5px 15px rgba(255, 71, 87, 0.3);
            transform: translateY(-2px);
        }

        .btn-edit {
            background: rgba(255, 183, 3, 0.1);
            color: var(--secondary);
            /* #FFB703 */
            border: 1px solid rgba(255, 183, 3, 0.3);
        }

        .btn-edit:hover {
            background: var(--secondary);
            color: #fff;
            box-shadow: 0 5px 15px rgba(255, 183, 3, 0.3);
            transform: translateY(-2px);
        }

        .alert-success {
            padding: 16px 20px;
            border-radius: 16px;
            background: rgba(46, 213, 115, 0.1);
            color: #2ed573;
            border: 1px solid rgba(46, 213, 115, 0.2);
            margin-bottom: 30px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @media (max-width: 768px) {
            .stat-grid {
                grid-template-columns: 1fr !important;
                gap: 20px;
            }
        }
    </style>
</head>

<body>
    <?php require_once '../partials/Loader.php'; ?>

    <div class="admin-layout">
        <!-- Sidebar -->
        <?php include 'partials/Sidebar.php'; ?>

        <!-- Main Content -->
        <main class="main-content">
            <div class="dashboard-header">
                <div class="header-title">
                    <h1>Dashboard Admin</h1>
                    <p class="text-muted" style="font-size: 1.1rem;">Selamat datang kembali,
                        <span
                            style="color: var(--primary); font-weight: 600;"><?= htmlspecialchars($admin_name) ?>!</span>
                    </p>
                </div>

                <div class="admin-profile-wrapper">
                    <button class="theme-toggle-btn" id="adminThemeToggle" title="Toggle Light/Dark Mode">
                        <i class="fa-solid fa-moon" id="adminThemeIcon"></i>
                    </button>

                    <div class="admin-profile glass">
                        <div class="admin-avatar">
                            <?= strtoupper(substr($admin_name, 0, 1)) ?>
                        </div>
                        <span style="font-weight: 600; font-size: 1.1rem; color: var(--text-main);">
                            <?= htmlspecialchars($admin_name) ?>
                        </span>
                    </div>
                </div>
            </div>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                <div class="alert-success">Berita berhasil dihapus secara permanen.</div>
            <?php endif; ?>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'galeri_deleted'): ?>
                <div class="alert-success">Foto Galeri berhasil dihapus secara permanen.</div>
            <?php endif; ?>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'program_deleted'): ?>
                <div class="alert-success">Program berhasil dihapus secara permanen.</div>
            <?php endif; ?>

            <?php
            // Calculate total news
            $count_query = $conn->query("SELECT COUNT(id) as total FROM Berita");
            $total_news = $count_query->fetch_assoc()['total'];

            // Calculate total gallery
            $count_gallery_query = $conn->query("SELECT COUNT(id) as total FROM Galeri");
            $total_gallery = $count_gallery_query ? $count_gallery_query->fetch_assoc()['total'] : 0;

            // Calculate total pesan
            $count_pesan_query = $conn->query("SELECT COUNT(id) as total FROM Pesan");
            $total_pesan = $count_pesan_query ? $count_pesan_query->fetch_assoc()['total'] : 0;

            // Calculate total pesan belum dibaca
            $count_unread_query = $conn->query("SELECT COUNT(id) as total FROM Pesan WHERE status = 'belum dibaca'");
            $total_unread = $count_unread_query ? $count_unread_query->fetch_assoc()['total'] : 0;

            // Calculate total programs
            $count_programs_query = $conn->query("SELECT COUNT(id) as total FROM Programs");
            $total_programs = $count_programs_query ? $count_programs_query->fetch_assoc()['total'] : 0;
            ?>

            <div class="stat-grid">
                <div class="stat-card glass">
                    <div class="stat-title">Total Berita Dipublikasi</div>
                    <div class="stat-value">
                        <?= $total_news ?>
                    </div>
                </div>
                <div class="stat-card glass" style="border-left-color: #8c52ff;">
                    <div class="stat-title">Total Program</div>
                    <div class="stat-value" style="color: #8c52ff;">
                        <?= $total_programs ?>
                    </div>
                </div>
                <div class="stat-card glass" style="border-left-color: var(--secondary);">
                    <div class="stat-title">Total Foto Galeri</div>
                    <div class="stat-value">
                        <?= $total_gallery ?>
                    </div>
                </div>
                <div class="stat-card glass" style="border-left-color: #25D366;">
                    <div class="stat-title">Pesan Masuk (<?= $total_unread ?> Baru)</div>
                    <div class="stat-value" style="<?= $total_unread > 0 ? 'color: #25D366;' : '' ?>">
                        <?= $total_pesan ?>
                    </div>
                </div>
            </div>

            <!-- Quick Management Shortcuts -->
            <div class="header-title" style="margin: 40px 0 20px;">
                <h2 style="font-size: 1.80rem; font-weight: 700; color: var(--text-main);">Manajemen Konten Cepat</h2>
            </div>
            
            <div class="stat-grid" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-bottom: 40px;">
                <!-- Berita Shortcut -->
                <div class="stat-card glass" style="border-left: 5px solid var(--primary); padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                        <div class="stat-icon" style="background: rgba(255, 90, 0, 0.1); color: var(--primary);">
                            <i class="fa-solid fa-newspaper"></i>
                        </div>
                        <a href="tambah-berita.php" class="btn btn-small" style="background: var(--primary); color: white; border: none;">+ Tambah</a>
                    </div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Manajemen Berita</h3>
                    <p class="text-muted" style="margin-bottom: 20px;">Lihat, edit, atau hapus semua berita yang telah dipublikasikan.</p>
                    <a href="berita.php" class="btn glass" style="width: 100%; justify-content: center; color: var(--primary); font-weight: 700;">Ke Daftar Berita <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
                </div>

                <!-- Program Shortcut -->
                <div class="stat-card glass" style="border-left: 5px solid #8c52ff; padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                        <div class="stat-icon" style="background: rgba(140, 82, 255, 0.1); color: #8c52ff;">
                            <i class="fa-solid fa-hand-holding-heart"></i>
                        </div>
                        <a href="tambah-program.php" class="btn btn-small" style="background: #8c52ff; color: white; border: none;">+ Tambah</a>
                    </div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Manajemen Program</h3>
                    <p class="text-muted" style="margin-bottom: 20px;">Kelola daftar program kerja yayasan di semua kategori.</p>
                    <a href="program.php" class="btn glass" style="width: 100%; justify-content: center; color: #8c52ff; font-weight: 700;">Ke Daftar Program <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
                </div>

                <!-- Galeri Shortcut -->
                <div class="stat-card glass" style="border-left: 5px solid var(--secondary); padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                        <div class="stat-icon" style="background: rgba(255, 183, 3, 0.1); color: var(--secondary);">
                            <i class="fa-solid fa-images"></i>
                        </div>
                        <a href="tambah-galeri.php" class="btn btn-small" style="background: var(--secondary); color: white; border: none;">+ Upload</a>
                    </div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Manajemen Galeri</h3>
                    <p class="text-muted" style="margin-bottom: 20px;">Atur koleksi foto dokumentasi kegiatan dari lapangan.</p>
                    <a href="galeri.php" class="btn glass" style="width: 100%; justify-content: center; color: var(--secondary); font-weight: 700;">Ke Daftar Galeri <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
                </div>

                <!-- Pesan Shortcut -->
                <div class="stat-card glass" style="border-left: 5px solid #25D366; padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                        <div class="stat-icon" style="background: rgba(37, 211, 102, 0.1); color: #25D366;">
                            <i class="fa-solid fa-message"></i>
                        </div>
                        <?php if ($total_unread > 0): ?>
                            <span class="badge-status badge-unread" style="margin: 0;"><?= $total_unread ?> Baru</span>
                        <?php endif; ?>
                    </div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Pesan Masuk</h3>
                    <p class="text-muted" style="margin-bottom: 20px;">Baca dan tanggapi pesan atau keluhan dari pengunjung website.</p>
                    <a href="pesan.php" class="btn glass" style="width: 100%; justify-content: center; color: #25D366; font-weight: 700;">Buka Kotak Masuk <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
                </div>

                <!-- Carousel Shortcut -->
                <div class="stat-card glass" style="border-left: 5px solid #00d2ff; padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                        <div class="stat-icon" style="background: rgba(0, 210, 255, 0.1); color: #00d2ff;">
                            <i class="fa-solid fa-desktop"></i>
                        </div>
                        <a href="tambah-carousel.php" class="btn btn-small" style="background: #00d2ff; color: white; border: none;">+ Tambah</a>
                    </div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Banner Desktop</h3>
                    <p class="text-muted" style="margin-bottom: 20px;">Kelola slide gambar dan teks (carousel) di halaman beranda.</p>
                    <a href="carousel.php" class="btn glass" style="width: 100%; justify-content: center; color: #00d2ff; font-weight: 700;">Atur Carousel <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
                </div>

                <!-- Struktural Shortcut -->
                <div class="stat-card glass" style="border-left: 5px solid #ff4757; padding: 30px;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                        <div class="stat-icon" style="background: rgba(255, 71, 87, 0.1); color: #ff4757;">
                            <i class="fa-solid fa-sitemap"></i>
                        </div>
                        <a href="tambah-struktur.php" class="btn btn-small" style="background: #ff4757; color: white; border: none;">+ Tambah</a>
                    </div>
                    <h3 style="font-size: 1.25rem; margin-bottom: 10px;">Struktur Organisasi</h3>
                    <p class="text-muted" style="margin-bottom: 20px;">Perbarui daftar pengurus dan struktur hierarki yayasan.</p>
                    <a href="struktur-organisasi.php" class="btn glass" style="width: 100%; justify-content: center; color: #ff4757; font-weight: 700;">Kelola Pengurus <i class="fa-solid fa-arrow-right" style="margin-left: 8px;"></i></a>
                </div>
            </div>

        </main>
    </div>

    <script>
        // Admin Theme Toggle Logic
        const themeBtn = document.getElementById('adminThemeToggle');
        const themeIcon = document.getElementById('adminThemeIcon');
        const body = document.body;

        // Check localStorage for theme
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'light') {
            body.classList.add('light-mode');
            if (themeIcon) {
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        }

        if (themeBtn) {
            themeBtn.addEventListener('click', () => {
                body.classList.toggle('light-mode');
                if (body.classList.contains('light-mode')) {
                    localStorage.setItem('theme', 'light');
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                } else {
                    localStorage.setItem('theme', 'dark');
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                }
            });
        }
    </script>
</body>

</html>