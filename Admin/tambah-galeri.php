<?php
session_start();
require_once '../config/Connection.php';

// Check if user is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul = trim($_POST['judul']);
    $deskripsi = $_POST['deskripsi'];
    $tanggal = date('Y-m-d'); // Current date

    $gambar = '';

    if (!empty($judul) && !empty($deskripsi)) {
        // Handle file upload
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
            $file_name = $_FILES['gambar']['name'];
            $file_tmp = $_FILES['gambar']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_ext)) {
                // Create unique filename
                $new_filename = uniqid() . '_galeri.' . $file_ext;
                $upload_path = '../uploads/';

                // Create directory if not exists
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                if (move_uploaded_file($file_tmp, $upload_path . $new_filename)) {
                    $gambar = $new_filename;
                } else {
                    $error = "Gagal mengunggah foto.";
                }
            } else {
                $error = "Format gambar tidak didukung. Gunakan JPG, PNG, atau WEBP.";
            }
        } else {
            $error = "Foto Galeri wajib diunggah.";
        }

        if (empty($error) && !empty($gambar)) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO Galeri (judul, deskripsi, gambar, tanggal) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $judul, $deskripsi, $gambar, $tanggal);

            if ($stmt->execute()) {
                $success = "Foto Galeri berhasil dipublikasikan!";
                // Clear fields
                $judul = '';
                $deskripsi = '';
            } else {
                $error = "Gagal mempublikasikan foto galeri: " . $conn->error;
            }
        }
    } else {
        $error = "Judul dan Deskripsi wajib diisi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Galeri - Yayasan Kaya Tene</title>
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

        .form-container {
            border-radius: 20px;
            padding: 40px;
            max-width: 800px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            color: var(--text-main);
            font-weight: 500;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 15px;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid var(--glass-border);
            color: var(--text-main);
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 200px;
            font-family: inherit;
        }

        /* File Upload Styling */
        .file-upload-wrapper {
            position: relative;
            width: 100%;
            height: 150px;
            border: 2px dashed var(--glass-border);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3);
            transition: all 0.3s;
            cursor: pointer;
            overflow: hidden;
        }

        .file-upload-wrapper:hover {
            border-color: var(--primary);
            background: rgba(255, 107, 0, 0.05);
        }

        .file-upload-wrapper input[type="file"] {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .file-upload-text {
            color: var(--text-muted);
            text-align: center;
            z-index: 1;
        }

        #preview-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
            z-index: 1;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .alert-error {
            background: rgba(220, 53, 69, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .btn-submit {
            padding: 15px 40px;
            font-size: 1.1rem;
            border-radius: 50px;
            margin-top: 10px;
            background: linear-gradient(45deg, var(--primary), var(--primary-dark));
            border: none;
            color: white;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(255, 107, 0, 0.4);
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
                padding: 20px;
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
                    <a href="dashboard.php">
                        <i class="fa-solid fa-chart-pie"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="tambah-berita.php">
                        <i class="fa-solid fa-pen-to-square"></i> <span>Tulis Berita</span>
                    </a>
                </li>
                <li>
                    <a href="tambah-galeri.php" class="active">
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
                    <h1>Tambah Galeri</h1>
                    <p class="text-muted" style="font-size: 1.1rem;">Buat dan publikasikan foto kegiatan, seperti
                        Agrokultur Kaya Tene.</p>
                </div>

                <div class="admin-profile-wrapper">
                    <button class="theme-toggle-btn" id="adminThemeToggle" title="Toggle Light/Dark Mode">
                        <i class="fa-solid fa-moon" id="adminThemeIcon"></i>
                    </button>
                    <!-- Simulated admin details since they might not be fetched on this page, but if session exists we can use it -->
                    <div class="admin-profile glass">
                        <div class="admin-avatar">
                            <?= isset($_SESSION['admin_username']) ? strtoupper(substr($_SESSION['admin_username'], 0, 1)) : 'A' ?>
                        </div>
                        <span style="font-weight: 600; font-size: 1.1rem; color: var(--text-main);">
                            <?= isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin' ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-container glass">

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="judul" class="form-label">Nama Kegiatan / Judul Foto</label>
                        <input type="text" id="judul" name="judul" class="form-control"
                            placeholder="Contoh: Agrokultur Kaya Tene..."
                            value="<?= isset($judul) ? htmlspecialchars($judul) : '' ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="gambar" class="form-label">Unggah Foto Galeri</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="gambar" name="gambar" accept="image/jpeg, image/png, image/webp"
                                onchange="previewImage(event)" required>
                            <div class="file-upload-text" id="file-text">
                                <i class="fa-solid fa-cloud-arrow-up"
                                    style="font-size: 2.5rem; display: block; margin-bottom: 10px; color: var(--primary);"></i>
                                Klik atau seret gambar ke sini <br> <small>(Format JPG, PNG, WEBP)</small>
                            </div>
                            <img id="preview-img" alt="Preview Image">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Deskripsi Kegiatan</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control"
                            placeholder="Ceritakan detail foto kegiatan di sini..."
                            required><?= isset($deskripsi) ? htmlspecialchars($deskripsi) : '' ?></textarea>
                    </div>

                    <button type="submit" class="btn-submit">Tambahkan ke Galeri</button>
                    <a href="dashboard.php"
                        style="color: var(--text-muted); text-decoration: none; margin-left: 20px;">Kembali</a>
                </form>
            </div>

        </main>
    </div>

    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview-img');
            const text = document.getElementById('file-text');

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    text.style.display = 'none';
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
                text.style.display = 'block';
            }
        }

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