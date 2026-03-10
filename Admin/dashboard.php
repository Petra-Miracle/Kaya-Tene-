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
    // Optional: Fetch image name and delete from folder first
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
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 280px;
            background: var(--bg-dark);
            border-right: 1px solid var(--glass-border);
            padding: 30px 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            z-index: 100;
            box-shadow: var(--shadow-sm);
        }

        .main-content {
            flex-grow: 1;
            margin-left: 280px;
            padding: 40px;
            background: var(--bg-darker);
            min-height: 100vh;
        }

        .admin-logo {
            font-size: 1.6rem;
            margin-bottom: 50px;
            display: block;
            text-align: center;
            font-weight: 800;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-menu li {
            margin-bottom: 15px;
        }

        .nav-menu a {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 16px;
            transition: all 0.4s cubic-bezier(0.2, 0.8, 0.2, 1);
            font-weight: 600;
        }

        .nav-menu a:hover,
        .nav-menu a.active {
            background: linear-gradient(135deg, rgba(255, 90, 0, 0.15), rgba(255, 183, 3, 0.15));
            color: var(--primary);
            box-shadow: 0 4px 15px rgba(255, 90, 0, 0.05);
            transform: translateX(5px);
        }

        .nav-menu a i {
            margin-right: 15px;
            font-size: 1.3rem;
            width: 20px;
            text-align: center;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 50px;
        }

        .header-title h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 5px;
            background: linear-gradient(135deg, var(--text-main), var(--text-muted));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .admin-profile-wrapper {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-profile {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 8px 25px 8px 8px;
            border-radius: 50px;
            cursor: pointer;
        }

        .admin-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 1.2rem;
            box-shadow: var(--shadow-sm);
        }

        .theme-toggle-btn {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            border: 1px solid var(--glass-border);
            background: var(--bg-card);
            color: var(--text-main);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: var(--shadow-sm);
        }

        .theme-toggle-btn:hover {
            transform: scale(1.1);
            color: var(--primary);
            border-color: var(--primary);
        }

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

        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                padding: 30px 10px;
            }

            .admin-logo span {
                display: none;
            }

            .admin-logo {
                font-size: 1rem;
            }

            .nav-menu a span {
                display: none;
            }

            .nav-menu a {
                padding: 15px;
                justify-content: center;
            }

            .nav-menu a i {
                margin-right: 0;
            }

            .main-content {
                margin-left: 80px;
            }

            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 20px;
            }

            .admin-profile-wrapper {
                width: 100%;
                justify-content: space-between;
            }
        }
    </style>
</head>

<body>
    <?php require_once '../partials/Loader.php'; ?>

    <div class="admin-layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <a href="../index.php" class="logo admin-logo"
                style="display: flex; flex-direction: column; align-items: center; gap: 5px;">
                <img src="../Public/img/Logo_Yayasan-new.png" alt="Logo"
                    style="height: 50px; transform: scale(1.5); margin-bottom: 10px;">
                <div><span style="color: var(--primary);">Kaya</span>Tene</div>
            </a>

            <ul class="nav-menu">
                <li>
                    <a href="dashboard.php" class="active">
                        <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="tambah-berita.php">
                        <i class="fa-solid fa-pen-to-square"></i> <span>Tulis Berita</span>
                    </a>
                </li>
                <li>
                    <a href="tambah-galeri.php">
                        <i class="fa-solid fa-images"></i> <span>Tambah Galeri</span>
                    </a>
                </li>
                <li>
                    <a href="../index.php" target="_blank">
                        <i class="fa-solid fa-globe"></i> <span>Lihat Website</span>
                    </a>
                </li>
                <li style="margin-top: 50px;">
                    <a href="logout.php" style="color: #ff6b6b;">
                        <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="dashboard-header">
                <div class="header-title">
                    <h1>Dashboard Admin</h1>
                    <p class="text-muted" style="font-size: 1.1rem;">Selamat datang kembali,
                        <span style="color: var(--primary); font-weight: 600;"><?= htmlspecialchars($admin_name) ?>!</span>
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

            <?php
            // Calculate total news
            $count_query = $conn->query("SELECT COUNT(id) as total FROM Berita");
            $total_news = $count_query->fetch_assoc()['total'];

            // Calculate total gallery
            $count_gallery_query = $conn->query("SELECT COUNT(id) as total FROM Galeri");
            $total_gallery = $count_gallery_query ? $count_gallery_query->fetch_assoc()['total'] : 0;
            ?>

            <div class="stat-grid">
                <div class="stat-card glass">
                    <div class="stat-title">Total Berita Dipublikasi</div>
                    <div class="stat-value">
                        <?= $total_news ?>
                    </div>
                </div>
                <div class="stat-card glass" style="border-left-color: var(--secondary);">
                    <div class="stat-title">Total Foto Galeri</div>
                    <div class="stat-value">
                        <?= $total_gallery ?>
                    </div>
                </div>
            </div>

            <div class="table-container glass">
                <div class="table-header">
                    <h2 style="font-size: 1.5rem;">Daftar Berita Terbaru</h2>
                    <a href="tambah-berita.php" class="btn btn-primary btn-small" style="padding: 10px 20px;">+ Buat
                        Berita Baru</a>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul Berita</th>
                                <th>Tanggal Publikasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT id, judul, tanggal FROM Berita ORDER BY tanggal DESC LIMIT 5";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $tanggal = date('d M Y, H:i', strtotime($row['tanggal']));
                                    echo "<tr>
                                            <td>{$no}</td>
                                            <td style='font-weight: 500;'>" . htmlspecialchars($row['judul']) . "</td>
                                            <td style='color: var(--text-muted);'>{$tanggal}</td>
                                            <td>
                                                <div class='action-btns'>
                                                    <a href='../views/detail-berita.php?id={$row['id']}' target='_blank' class='btn btn-small glass' style='color: var(--text-main); border: 1px solid var(--glass-border);'>Lihat</a>
                                                    <a href='dashboard.php?delete_id={$row['id']}' onclick=\"return confirm('Apakah Anda yakin ingin menghapus berita ini secara permanen?');\" class='btn btn-small btn-danger'>Hapus</a>
                                                </div>
                                            </td>
                                          </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='4' style='text-align: center; color: var(--text-muted); padding: 40px;'>Belum ada berita yang diterbitkan.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table-container glass">
                <div class="table-header">
                    <h2 style="font-size: 1.5rem;">Daftar Foto Galeri</h2>
                    <a href="tambah-galeri.php" class="btn btn-primary btn-small"
                        style="background: linear-gradient(135deg, var(--secondary), var(--primary)); padding: 10px 20px;">+
                        Tambah
                        Foto Galeri</a>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Judul Kegiatan</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_galeri = "SELECT id, gambar, judul, tanggal FROM Galeri ORDER BY id DESC LIMIT 5";
                            $res_galeri = $conn->query($sql_galeri);

                            if ($res_galeri && $res_galeri->num_rows > 0) {
                                $no = 1;
                                while ($rowg = $res_galeri->fetch_assoc()) {
                                    $tanggal_g = date('d M Y', strtotime($rowg['tanggal']));
                                    $img_thumb = '/Kaya Tene/uploads/' . htmlspecialchars($rowg['gambar']);
                                    echo "<tr>
                                            <td>{$no}</td>
                                            <td><img src='{$img_thumb}' alt='thumb' style='width: 60px; height: 40px; object-fit: cover; border-radius: 5px;'></td>
                                            <td style='font-weight: 500;'>" . htmlspecialchars($rowg['judul']) . "</td>
                                            <td style='color: var(--text-muted);'>{$tanggal_g}</td>
                                            <td>
                                                <div class='action-btns'>
                                                    <a href='../views/detail-gallery.php?id={$rowg['id']}' target='_blank' class='btn btn-small glass' style='color: var(--text-main); border: 1px solid var(--glass-border);'>Lihat</a>
                                                    <a href='dashboard.php?delete_galeri_id={$rowg['id']}' onclick=\"return confirm('Apakah Anda yakin ingin menghapus galeri ini secara permanen?');\" class='btn btn-small btn-danger'>Hapus</a>
                                                </div>
                                            </td>
                                          </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center; color: var(--text-muted); padding: 40px;'>Belum ada galeri yang diunggah.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
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