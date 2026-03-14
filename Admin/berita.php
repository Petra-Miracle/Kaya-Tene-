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
    // Fetch image name and delete from folder first
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
    header("Location: berita.php?msg=deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Berita - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
            margin-bottom: 35px;
        }

        .table-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -1px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 12px;
        }

        th {
            color: var(--text-muted);
            font-weight: 800;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 2px;
            padding: 0 20px 10px;
            opacity: 0.6;
        }

        td {
            color: var(--text-main);
            background: rgba(255, 255, 255, 0.02);
            padding: 20px;
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
            vertical-align: middle;
        }

        body.light-mode td { background: rgba(0, 0, 0, 0.01); }

        td:first-child {
            border-top-left-radius: 18px;
            border-bottom-left-radius: 18px;
            border-left: 1px solid var(--glass-border);
        }

        td:last-child {
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
            border-right: 1px solid var(--glass-border);
        }

        tr:hover td {
            background: rgba(255, 90, 0, 0.05);
            border-color: rgba(255, 90, 0, 0.2);
        }

        .news-thumb {
            width: 70px;
            height: 46px;
            object-fit: cover;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            border: 2px solid var(--glass-border);
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .btn-small {
            height: 40px;
            padding: 0 18px;
            font-size: 0.88rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            gap: 8px;
        }

        .btn-edit {
            background: rgba(255, 183, 3, 0.1);
            color: var(--secondary);
            border: 1px solid rgba(255, 183, 3, 0.2);
        }

        .btn-edit:hover {
            background: var(--secondary);
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 183, 3, 0.2);
        }

        .btn-danger {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
            border: 1px solid rgba(255, 71, 87, 0.2);
        }

        .btn-danger:hover {
            background: #ff4757;
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 71, 87, 0.2);
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
                    <h1>Manajemen Berita</h1>
                    <p class="text-muted" style="font-size: 1.1rem;">Kelola semua artikel dan update terbaru dari Yayasan Kaya Tene.</p>
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

            <div class="table-container glass">
                <div class="table-header">
                    <h2 style="font-size: 1.5rem;">Daftar Semua Berita</h2>
                    <a href="tambah-berita.php" class="btn btn-primary btn-small" style="padding: 10px 20px;">+ Buat Berita Baru</a>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Judul Berita</th>
                                <th>Tanggal Publikasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT id, judul, gambar, tanggal FROM Berita ORDER BY tanggal DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $tanggal = date('d M Y, H:i', strtotime($row['tanggal']));
                                    $img_thumb = !empty($row['gambar']) ? '/Kaya Tene/uploads/' . htmlspecialchars($row['gambar']) : 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80';
                                    
                                    echo "<tr>
                                            <td>{$no}</td>
                                            <td><img src='{$img_thumb}' alt='thumb' class='news-thumb' onerror=\"this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80'\"></td>
                                            <td style='font-weight: 700; color: var(--text-main);'>" . htmlspecialchars($row['judul']) . "</td>
                                            <td style='color: var(--text-muted); font-weight: 500;'>{$tanggal}</td>
                                            <td>
                                                <div class='action-btns'>
                                                    <a href='../views/detail-berita.php?id={$row['id']}' target='_blank' class='btn btn-small glass' style='color: var(--text-muted);'><i class='fa-regular fa-eye'></i></a>
                                                    <a href='edit-berita.php?id={$row['id']}' class='btn btn-small btn-edit'><i class='fa-solid fa-pen-to-square'></i></a>
                                                    <a href='berita.php?delete_id={$row['id']}' onclick=\"return confirm('Apakah Anda yakin ingin menghapus berita ini secara permanen?');\" class='btn btn-small btn-danger'><i class='fa-solid fa-trash'></i></a>
                                                </div>
                                            </td>
                                          </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center; color: var(--text-muted); padding: 40px;'>Belum ada berita yang diterbitkan.</td></tr>";
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