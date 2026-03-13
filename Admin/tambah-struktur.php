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
    $nama = trim($_POST['nama']);
    $jabatan = trim($_POST['jabatan']);
    $tanggal = $_POST['tanggal'];

    // Default image if no upload
    $gambar = '';

    if (!empty($nama) && !empty($jabatan) && !empty($tanggal)) {
        // Handle file upload
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
            $file_name = $_FILES['gambar']['name'];
            $file_tmp = $_FILES['gambar']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_ext)) {
                // Create unique filename
                $new_filename = uniqid() . '.' . $file_ext;
                $upload_path = '../uploads/';

                // Create directory if not exists
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                if (move_uploaded_file($file_tmp, $upload_path . $new_filename)) {
                    $gambar = $new_filename;
                } else {
                    $error = "Gagal mengunggah gambar.";
                }
            } else {
                $error = "Format gambar tidak didukung. Gunakan JPG, PNG, atau WEBP.";
            }
        } else {
            $error = "Foto profil/gambar wajib diunggah.";
        }

        if (empty($error)) {
            // Insert into database
            $stmt = $conn->prepare("INSERT INTO Struktur_Organisasi (nama, jabatan, gambar, tanggal) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nama, $jabatan, $gambar, $tanggal);

            if ($stmt->execute()) {
                $success = "Anggota organisasi berhasil ditambahkan!";
                // Clear fields
                $nama = '';
                $jabatan = '';
                $tanggal = '';
            } else {
                $error = "Gagal menambahkan anggota organisasi: " . $conn->error;
            }
        }
    } else {
        $error = "Semua field (Nama, Jabatan, Tanggal) wajib diisi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Struktur Organisasi - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>

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
            object-fit: contain;  /* Best for portraits */
            background-color: rgba(0,0,0,0.5);
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

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
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
                    <h1>Tambah Anggota</h1>
                    <p class="text-muted" style="font-size: 1.1rem;">Tambahkan profil baru ke struktur organisasi yayasan.</p>
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
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" class="form-control"
                            placeholder="Contoh: John Doe S.E..."
                            value="<?= isset($nama) ? htmlspecialchars($nama) : '' ?>" required>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label for="jabatan" class="form-label">Jabatan</label>
                            <input type="text" id="jabatan" name="jabatan" class="form-control"
                                placeholder="Contoh: Ketua Yayasan..."
                                value="<?= isset($jabatan) ? htmlspecialchars($jabatan) : '' ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="tanggal" class="form-label">Tanggal Efektif/Bergabung</label>
                            <input type="date" id="tanggal" name="tanggal" class="form-control" style="cursor: pointer;"
                                value="<?= isset($tanggal) ? htmlspecialchars($tanggal) : '' ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="gambar" class="form-label">Foto Profil (Rasio Portrait Disarankan)</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="gambar" name="gambar" accept="image/jpeg, image/png, image/webp"
                                onchange="previewImage(event)" required>
                            <div class="file-upload-text" id="file-text">
                                <i class="fa-solid fa-user-tie"
                                    style="font-size: 2.5rem; display: block; margin-bottom: 10px; color: var(--primary);"></i>
                                Klik atau seret foto ke sini <br> <small>(Format JPG, PNG, WEBP)</small>
                            </div>
                            <img id="preview-img" alt="Preview Profil">
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Simpan Anggota</button>
                    <a href="struktur-organisasi.php"
                        style="color: var(--text-muted); text-decoration: none; margin-left: 20px;">Batal</a>
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
