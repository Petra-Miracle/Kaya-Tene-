<?php
session_start();
require_once '../config/Connection.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = $_SESSION['admin_username'];

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM Pesan WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: pesan.php?msg=deleted");
    exit();
}

// Handle read request
if (isset($_GET['read_id'])) {
    $read_id = intval($_GET['read_id']);
    $stmt = $conn->prepare("UPDATE Pesan SET status = 'sudah dibaca' WHERE id = ?");
    $stmt->bind_param("i", $read_id);
    $stmt->execute();
    header("Location: pesan.php?msg=read");
    exit();
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Publik - Administrator Yayasan Kaya Tene</title>
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
            padding: 24px 20px;
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
            vertical-align: middle;
            transition: all 0.3s ease;
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

        .action-btns {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .btn-small {
            height: 42px;
            padding: 0 20px;
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

        .btn-unread {
            background: rgba(37, 211, 102, 0.1);
            color: #25D366;
            border: 1px solid rgba(37, 211, 102, 0.2);
        }

        .btn-unread:hover {
            background: #25D366;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(37, 211, 102, 0.2);
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

        .badge-status {
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-unread {
            background: rgba(255, 183, 3, 0.2);
            color: var(--secondary);
            border: 1px solid var(--secondary);
        }

        .badge-read {
            background: rgba(46, 213, 115, 0.1);
            color: #2ed573;
            border: 1px solid #2ed573;
        }

        .message-content {
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
            font-size: 0.95rem;
            line-height: 1.5;
            white-space: pre-wrap;
        }

        body.light-mode .message-content {
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid var(--glass-border);
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
                    <h1>Pesan dari Publik</h1>
                    <p class="text-muted" style="font-size: 1.1rem;">Pantau dan kelola pesan yang dikirimkan oleh
                        pengunjung website.</p>
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
                <div class="alert-success">Pesan berhasil dihapus secara permanen.</div>
            <?php endif; ?>

            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'read'): ?>
                <div class="alert-success">Pesan telah ditandai sebagai sudah dibaca.</div>
            <?php endif; ?>

            <div class="table-container glass">
                <div class="table-header">
                    <h2 style="font-size: 1.5rem;">Daftar Pesan Masuk</h2>
                </div>

                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Pengirim</th>
                                <th>Kontak</th>
                                <th style="width: 40%">Pesan</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT id, nama, email, subjek, isi_pesan, status, tanggal FROM Pesan ORDER BY tanggal DESC";
                            $result = $conn->query($sql);

                            if ($result && $result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $tanggal = date('d M Y, H:i', strtotime($row['tanggal']));
                                    $statusClass = $row['status'] == 'belum dibaca' ? 'badge-unread' : 'badge-read';
                                    $subjek = !empty($row['subjek']) ? htmlspecialchars($row['subjek']) : 'Tanpa Subjek';

                                    echo "<tr style='" . ($row['status'] == 'belum dibaca' ? 'background: rgba(255, 183, 3, 0.05);' : '') . "'>
                                            <td>
                                                <div style='font-weight: 600; font-size: 1.1rem;'>" . htmlspecialchars($row['nama']) . "</div>
                                                <div style='color: var(--text-muted); font-size: 0.9rem; margin-top: 5px;'>{$tanggal}</div>
                                            </td>
                                            <td>
                                                <a href='mailto:" . htmlspecialchars($row['email']) . "' style='color: var(--primary); text-decoration: none;'>
                                                    " . htmlspecialchars($row['email']) . "
                                                </a>
                                            </td>
                                            <td>
                                                <div style='font-weight: 600; margin-bottom: 5px;'>{$subjek}</div>
                                                <div class='message-content'>" . htmlspecialchars($row['isi_pesan']) . "</div>
                                            </td>
                                            <td>
                                                <span class='badge-status {$statusClass}'>" . htmlspecialchars($row['status']) . "</span>
                                            </td>
                                            <td>
                                                <div class='action-btns'>";
                                    if ($row['status'] == 'belum dibaca') {
                                        echo "<a href='pesan.php?read_id={$row['id']}' class='btn btn-small btn-unread'><i class='fa-solid fa-check' style='margin-right: 5px;'></i> Tandai Dibaca</a>";
                                    }
                                    echo "
                                                    <a href='mailto:{$row['email']}?subject=Balasan: {$subjek}' class='btn btn-small glass' style='color: var(--text-main); border: 1px solid var(--glass-border);'><i class='fa-solid fa-reply' style='margin-right: 5px;'></i> Balas E-mail</a>
                                                    <a href='pesan.php?delete_id={$row['id']}' onclick=\"return confirm('Apakah Anda yakin ingin menghapus pesan ini?');\" class='btn btn-small btn-danger'><i class='fa-solid fa-trash'></i></a>
                                                </div>
                                            </td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' style='text-align: center; color: var(--text-muted); padding: 40px;'>Belum ada pesan masuk saat ini.</td></tr>";
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