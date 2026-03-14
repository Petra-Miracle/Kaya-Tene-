<?php
session_start();
require_once '../config/Connection.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_username'];

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("SELECT gambar FROM Struktur_Organisasi WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['gambar']) && file_exists("../uploads/" . $row['gambar'])) {
            unlink("../uploads/" . $row['gambar']);
        }
    }

    $stmt = $conn->prepare("DELETE FROM Struktur_Organisasi WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: struktur-organisasi.php?msg=deleted");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struktur Organisasi - Administrator</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="partials/Sidebar.css">
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

        .member-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--primary);
            box-shadow: 0 4px 10px rgba(255, 90, 0, 0.2);
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

        .btn-danger {
            background: rgba(255, 71, 87, 0.1);
            color: #ff4757;
            border: 1px solid rgba(255, 71, 87, 0.2);
        }

        .btn-edit:hover, .btn-danger:hover {
            transform: translateY(-3px);
            color: white;
        }

        .btn-edit:hover { background: var(--secondary); box-shadow: 0 10px 20px rgba(255, 183, 3, 0.2); }
        .btn-danger:hover { background: #ff4757; box-shadow: 0 10px 20px rgba(255, 71, 87, 0.2); }

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
            animation: slideInDown 0.5s ease;
        }
    </style>
</head>

<body>
    <?php require_once '../partials/Loader.php'; ?>

    <div class="admin-layout">
        <?php include 'partials/Sidebar.php'; ?>

        <main class="main-content">
            <div class="dashboard-header">
                <div class="header-title">
                    <h1>Struktur Organisasi</h1>
                    <p class="text-muted" style="font-size: 1.1rem;">Kelola data anggota dan jabatan struktur organisasi yayasan.</p>
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
                <div class="alert-success">Anggota berhasil dihapus secara permanen.</div>
            <?php endif; ?>

            <div class="table-container glass">
                <div class="table-header">
                    <h2 style="font-size: 1.5rem;">Daftar Anggota Organisasi</h2>
                    <a href="tambah-struktur.php" class="btn btn-primary btn-small" style="padding: 10px 20px;">+ Tambah Anggota</a>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT id, nama, jabatan, gambar, tanggal FROM Struktur_Organisasi ORDER BY created_at DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                $no = 1;
                                while ($row = $result->fetch_assoc()) {
                                    $img_thumb = !empty($row['gambar']) ? '/Kaya Tene/uploads/' . htmlspecialchars($row['gambar']) : 'https://via.placeholder.com/80x80?text=No+Foto';
                                    $tanggal = date('d M Y', strtotime($row['tanggal']));
                                    echo "<tr>
                                             <td>{$no}</td>
                                             <td><img src='{$img_thumb}' alt='member' class='member-thumb' onerror=\"this.src='https://ui-avatars.com/api/?name=" . urlencode($row['nama']) . "&background=random'\"></td>
                                             <td style='font-weight: 700; color: var(--text-main);'>" . htmlspecialchars($row['nama']) . "</td>
                                             <td><span class='badge-status' style='background: rgba(255, 90, 0, 0.1); color: var(--primary); padding: 5px 12px; border-radius: 8px; font-size: 0.8rem;'>" . htmlspecialchars($row['jabatan']) . "</span></td>
                                             <td>
                                                 <div class='action-btns'>
                                                     <a href='edit-struktur.php?id={$row['id']}' class='btn btn-small btn-edit'><i class='fa-solid fa-pen-to-square'></i></a>
                                                     <a href='struktur-organisasi.php?delete_id={$row['id']}' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data pengurus ini?');\" class='btn btn-small btn-danger'><i class='fa-solid fa-trash'></i></a>
                                                 </div>
                                             </td>
                                           </tr>";
                                    $no++;
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center; color: var(--text-muted); padding: 40px;'>Belum ada data struktur organisasi.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
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
