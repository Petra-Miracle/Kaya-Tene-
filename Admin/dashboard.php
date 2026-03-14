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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .stat-card {
            padding: 30px;
            border-radius: 28px;
            position: relative;
            overflow: hidden;
            border: 1px solid var(--glass-border);
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            background: var(--bg-card);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            border-color: var(--primary);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, rgba(255, 90, 0, 0.1), transparent);
            border-radius: 50%;
            z-index: 0;
            transition: all 0.4s ease;
        }

        .stat-card:hover::after {
            transform: scale(1.5);
            background: linear-gradient(135deg, rgba(255, 90, 0, 0.2), transparent);
        }

        .stat-title {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            position: relative;
            z-index: 1;
        }

        .stat-value {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--text-main);
            position: relative;
            z-index: 1;
            letter-spacing: -2px;
            line-height: 1;
        }

        .stat-icon-bg {
            position: absolute;
            bottom: -20px;
            right: -10px;
            font-size: 5rem;
            color: var(--text-main);
            opacity: 0.03;
            transform: rotate(-15deg);
        }

        .table-container {
            background: var(--bg-card);
            border: 1px solid var(--glass-border);
            border-radius: 28px;
            padding: 35px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
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

        th {
            color: var(--text-muted);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 2px;
            padding: 10px 20px;
            opacity: 0.6;
        }

        td {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.02);
            padding: 18px 20px;
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
        }

        body.light-mode td {
            background: rgba(0, 0, 0, 0.01);
        }

        td:first-child {
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
            border-left: 1px solid var(--glass-border);
        }

        td:last-child {
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
            border-right: 1px solid var(--glass-border);
        }

        tr:hover td {
            background: rgba(255, 90, 0, 0.05);
            border-color: rgba(255, 90, 0, 0.2);
        }

        .alert-success {
            padding: 20px 25px;
            border-radius: 20px;
            background: rgba(46, 213, 115, 0.1);
            color: #2ed573;
            border: 1px solid rgba(46, 213, 115, 0.2);
            margin-bottom: 40px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 15px;
            backdrop-filter: blur(10px);
            animation: slideInDown 0.5s ease;
        }

        @keyframes slideInDown {
            from { transform: translateY(-20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .shortcut-card {
            border-left: 6px solid var(--primary);
        }
        
        .shortcut-card.purple { border-left-color: #8c52ff; }
        .shortcut-card.yellow { border-left-color: var(--secondary); }
        .shortcut-card.green { border-left-color: #25D366; }
        .shortcut-card.blue { border-left-color: #00d2ff; }
        .shortcut-card.red { border-left-color: #ff4757; }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .btn-quick {
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            text-align: center;
            text-decoration: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            margin-top: 15px;
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
                <div class="stat-card glass shortcut-card">
                    <div class="stat-title">Total Berita</div>
                    <div class="stat-value"><?= $total_news ?></div>
                    <i class="fa-solid fa-newspaper stat-icon-bg"></i>
                </div>
                <div class="stat-card glass shortcut-card purple">
                    <div class="stat-title">Total Program</div>
                    <div class="stat-value"><?= $total_programs ?></div>
                    <i class="fa-solid fa-hand-holding-heart stat-icon-bg"></i>
                </div>
                <div class="stat-card glass shortcut-card yellow">
                    <div class="stat-title">Galeri Foto</div>
                    <div class="stat-value"><?= $total_gallery ?></div>
                    <i class="fa-solid fa-images stat-icon-bg"></i>
                </div>
                <div class="stat-card glass shortcut-card green">
                    <div class="stat-title">Pesan Masuk</div>
                    <div class="stat-value"><?= $total_pesan ?></div>
                    <i class="fa-solid fa-message stat-icon-bg"></i>
                </div>
            </div>

            <!-- Quick Management Shortcuts -->
            <div class="header-title" style="margin: 60px 0 30px;">
                <h2 style="font-size: 2.2rem; font-weight: 800; color: var(--text-main); letter-spacing: -1px;">Manajemen Cepat</h2>
            </div>
            
            <div class="stat-grid">
                <!-- Berita Shortcut -->
                <div class="stat-card glass shortcut-card">
                    <div class="stat-icon" style="background: rgba(255, 90, 0, 0.1); color: var(--primary);">
                        <i class="fa-solid fa-newspaper"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 10px;">Berita Terbaru</h3>
                    <p class="text-muted" style="font-size: 0.95rem; line-height: 1.6; margin-bottom: 10px;">Update informasi terkini seputar kegiatan Yayasan.</p>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <a href="tambah-berita.php" class="btn btn-small" style="background: var(--primary); color: white; border: none; flex: 1;">+ Tambah</a>
                        <a href="berita.php" class="btn btn-small glass" style="flex: 1;"><i class="fa-solid fa-list"></i></a>
                    </div>
                </div>

                <!-- Program Shortcut -->
                <div class="stat-card glass shortcut-card purple">
                    <div class="stat-icon" style="background: rgba(140, 82, 255, 0.1); color: #8c52ff;">
                        <i class="fa-solid fa-hand-holding-heart"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 10px;">Program Kerja</h3>
                    <p class="text-muted" style="font-size: 0.95rem; line-height: 1.6; margin-bottom: 10px;">Kelola program pemberdayaan dan sosial yayasan.</p>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <a href="tambah-program.php" class="btn btn-small" style="background: #8c52ff; color: white; border: none; flex: 1;">+ Tambah</a>
                        <a href="program.php" class="btn btn-small glass" style="flex: 1;"><i class="fa-solid fa-list"></i></a>
                    </div>
                </div>

                <!-- Galeri Shortcut -->
                <div class="stat-card glass shortcut-card yellow">
                    <div class="stat-icon" style="background: rgba(255, 183, 3, 0.1); color: var(--secondary);">
                        <i class="fa-solid fa-images"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 10px;">Galeri Foto</h3>
                    <p class="text-muted" style="font-size: 0.95rem; line-height: 1.6; margin-bottom: 10px;">Dokumentasi visual setiap kegiatan di lapangan.</p>
                    <div style="display: flex; gap: 10px; margin-top: 20px;">
                        <a href="tambah-galeri.php" class="btn btn-small" style="background: var(--secondary); color: white; border: none; flex: 1;">+ Upload</a>
                        <a href="galeri.php" class="btn btn-small glass" style="flex: 1;"><i class="fa-solid fa-list"></i></a>
                    </div>
                </div>

                <!-- Pesan Shortcut -->
                <div class="stat-card glass shortcut-card green">
                    <div class="stat-icon" style="background: rgba(37, 211, 102, 0.1); color: #25D366;">
                        <i class="fa-solid fa-message"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 10px;">Pesan Masuk</h3>
                    <p class="text-muted" style="font-size: 0.95rem; line-height: 1.6; margin-bottom: 10px;">Tanggapi aspirasi dan kontak dari pengunjung.</p>
                    <a href="pesan.php" class="btn-quick glass" style="color: #25D366; border: 1px solid rgba(37, 211, 102, 0.2);">
                        Buka Kotak Masuk <span class="badge" style="background: #25D366; color: white; padding: 2px 8px; border-radius: 6px; font-size: 0.75rem; margin-left: 5px;"><?= $total_unread ?> Baru</span>
                    </a>
                </div>

                <!-- Carousel Shortcut -->
                <div class="stat-card glass shortcut-card blue">
                    <div class="stat-icon" style="background: rgba(0, 210, 255, 0.1); color: #00d2ff;">
                        <i class="fa-solid fa-desktop"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 10px;">Banner Beranda</h3>
                    <p class="text-muted" style="font-size: 0.95rem; line-height: 1.6; margin-bottom: 10px;">Atur visual slide utama di halaman depan website.</p>
                    <a href="carousel.php" class="btn-quick glass" style="color: #00d2ff; border: 1px solid rgba(0, 210, 255, 0.2);">Kelola Carousel <i class="fa-solid fa-arrow-right"></i></a>
                </div>

                <!-- Struktural Shortcut -->
                <div class="stat-card glass shortcut-card red">
                    <div class="stat-icon" style="background: rgba(255, 71, 87, 0.1); color: #ff4757;">
                        <i class="fa-solid fa-sitemap"></i>
                    </div>
                    <h3 style="font-size: 1.4rem; font-weight: 700; margin-bottom: 10px;">Struktural</h3>
                    <p class="text-muted" style="font-size: 0.95rem; line-height: 1.6; margin-bottom: 10px;">Perbarui data pengurus dan struktur organisasi.</p>
                    <a href="struktur-organisasi.php" class="btn-quick glass" style="color: #ff4757; border: 1px solid rgba(255, 71, 87, 0.2);">Atur Pengurus <i class="fa-solid fa-users"></i></a>
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