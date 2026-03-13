<?php
session_start();
require_once '../config/Connection.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_username'];

// Handle delete program request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    // Fetch image name and delete from folder first
    $stmt = $conn->prepare("SELECT gambar FROM Programs WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['gambar']) && file_exists("../uploads/" . $row['gambar'])) {
            unlink("../uploads/" . $row['gambar']);
        }
    }

    // Delete from DB
    $stmt = $conn->prepare("DELETE FROM Programs WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: program.php?msg=deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Program - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
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

        .badge-category {
            color: var(--primary);
            background: rgba(255, 90, 0, 0.1);
            padding: 5px 12px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 700;
            border: 1px solid rgba(255, 90, 0, 0.2);
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
                    <h1>Manajemen Program</h1>
                    <p class="text-muted" style="font-size: 1.1rem;">Kelola program-program unggulan dari Yayasan Kaya Tene.</p>
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
                <div class="alert-success">Program berhasil dihapus secara permanen.</div>
            <?php endif; ?>

            <div class="table-container glass">
                <div class="table-header">
                    <h2 style="font-size: 1.5rem;">Daftar Program Kerja</h2>
                    <a href="tambah-program.php" class="btn btn-primary btn-small" style="padding: 10px 20px; background: linear-gradient(135deg, #8c52ff, #5e17eb); border: none;">+ Tambah Program Baru</a>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Judul Program</th>
                                <th>Kategori</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT id, judul, gambar, kategori, tanggal FROM Programs ORDER BY id DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $tanggal = date('d M Y', strtotime($row['tanggal']));
                                    $img_thumb = !empty($row['gambar']) ? '/Kaya Tene/uploads/' . htmlspecialchars($row['gambar']) : 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80';
                                    
                                    $preview_link = '#';
                                    if ($row['kategori'] === 'Pendidikan') $preview_link = '/Kaya Tene/views/Pendidikan.php';
                                    if ($row['kategori'] === 'Ekonomi') $preview_link = '/Kaya Tene/views/Ekonomi.php';
                                    if ($row['kategori'] === 'Sosial, Budaya & Publikasi') $preview_link = '/Kaya Tene/views/Lingkungan&Sosial.php';
                                    if ($row['kategori'] === 'Pertanian, Peternakan & Perikanan') $preview_link = '/Kaya Tene/views/Pertanian.php';

                                    echo "<tr>
                                            <td>{$no}</td>
                                            <td><img src='{$img_thumb}' alt='thumb' style='width: 60px; height: 40px; object-fit: cover; border-radius: 5px;' onerror=\"this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80'\"></td>
                                            <td style='font-weight: 500;'>" . htmlspecialchars($row['judul']) . "</td>
                                            <td><span class='badge-category'>" . htmlspecialchars($row['kategori']) . "</span></td>
                                            <td style='color: var(--text-muted);'>{$tanggal}</td>
                                            <td>
                                                <div class='action-btns'>
                                                    <a href='{$preview_link}' target='_blank' class='btn btn-small glass' style='color: var(--text-main); border: 1px solid var(--glass-border);'>Lihat</a>
                                                    <a href='edit-program.php?id={$row['id']}' class='btn btn-small btn-edit'>Edit</a>
                                                    <a href='program.php?delete_id={$row['id']}' onclick=\"return confirm('Apakah Anda yakin ingin menghapus program ini secara permanen?');\" class='btn btn-small btn-danger'>Hapus</a>
                                                </div>
                                            </td>
                                          </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='6' style='text-align: center; color: var(--text-muted); padding: 40px;'>Belum ada program yang ditambahkan.</td></tr>";
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
