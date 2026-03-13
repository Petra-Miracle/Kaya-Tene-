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
    $deskripsi = trim($_POST['deskripsi']);
    $btn_text = !empty(trim($_POST['btn_text'])) ? trim($_POST['btn_text']) : 'Profil kami';
    $btn_link = !empty(trim($_POST['btn_link'])) ? trim($_POST['btn_link']) : '#tentang';
    $tanggal = date('Y-m-d'); // Default to current date for timestamp needs
    $gambar = '';

    if (!empty($judul) && !empty($deskripsi)) {
        // Handle file upload
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
            $file_name = $_FILES['gambar']['name'];
            $file_tmp = $_FILES['gambar']['tmp_name'];
            $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (in_array($file_ext, $allowed_ext)) {
                $new_filename = uniqid() . '_carousel.' . $file_ext;
                $upload_path = '../uploads/';

                // Create folder if not exists
                if (!is_dir($upload_path)) {
                    mkdir($upload_path, 0777, true);
                }

                if (move_uploaded_file($file_tmp, $upload_path . $new_filename)) {
                    $gambar = $new_filename;

                    // Insert into database
                    $stmt = $conn->prepare("INSERT INTO Carousel (judul, deskripsi, btn_text, btn_link, gambar, tanggal) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssss", $judul, $deskripsi, $btn_text, $btn_link, $gambar, $tanggal);

                    if ($stmt->execute()) {
                        $success = "Banner Carousel berhasil ditambahkan!";
                    } else {
                        $error = "Gagal menyimpan banner: " . $conn->error;
                    }
                } else {
                    $error = "Gagal mengunggah foto banner.";
                }
            } else {
                $error = "Format gambar tidak didukung. Gunakan JPG, PNG, WEBP, atau GIF.";
            }
        } else {
            $error = "Anda harus mengunggah file foto/banner.";
        }
    } else {
        $error = "Judul dan Deskripsi banner wajib diisi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Carousel - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="partials/Sidebar.css">
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

        textarea.form-control {
            resize: vertical;
            min-height: 150px;
            font-family: inherit;
        }

        /* File Upload Styling */
        .file-upload-wrapper {
            position: relative;
            width: 100%;
            height: 200px; /* Made higher for banners */
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
                    <h1>Tambah Carousel Baru</h1>
                    <p class="text-muted" style="font-size: 1.1rem;">Upload slide banner yang akan muncul secara bergerak di halaman beranda.</p>
                </div>

                <div class="admin-profile-wrapper">
                    <button class="theme-toggle-btn" id="adminThemeToggle" title="Toggle Light/Dark Mode">
                        <i class="fa-solid fa-moon" id="adminThemeIcon"></i>
                    </button>
                    <!-- Simulated admin details -->
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
                        <label for="judul" class="form-label">Judul Caption Banner (Max 250 Karakter)</label>
                        <input type="text" id="judul" name="judul" class="form-control"
                            placeholder="Misal: Pendidikan Karakter..." required>
                    </div>

                    <div class="form-group">
                        <label for="gambar" class="form-label">Unggah Foto Slide</label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="gambar" name="gambar" accept="image/jpeg, image/png, image/webp" required
                                onchange="previewImage(event)">
                            <div class="file-upload-text" id="file-text">
                                <i class="fa-solid fa-image"
                                    style="font-size: 2.5rem; display: block; margin-bottom: 10px; color: var(--primary);"></i>
                                Klik atau seret foto latar/banner ke sini <br> <small>(Resolusi terbaik 1920x1080 disarankan)</small>
                            </div>
                            <img id="preview-img" alt="Preview Image">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="deskripsi" class="form-label">Teks Deskripsi Pendek</label>
                        <textarea id="deskripsi" name="deskripsi" class="form-control"
                            placeholder="Meningkatkan partisipasi komunitas dalam kegiatan kami..."
                            required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="btn_link" class="form-label">Pilih Tujuan Tombol (Arahkan Halaman)</label>
                        <select id="btn_link" name="btn_link" class="form-control" onchange="document.getElementById('btn_text').value = this.options[this.selectedIndex].text" required>
                            <option value="" disabled selected>-- Pilih Daftar Halaman --</option>
                            <option value="/Kaya Tene/index.php">Beranda (Home)</option>
                            <option value="/Kaya Tene/index.php#tentang">Tentang Kami</option>
                            <option value="/Kaya Tene/index.php#visi-misi">Visi & Misi</option>
                            <option value="/Kaya Tene/views/berita.php">Berita Terkini</option>
                            <option value="/Kaya Tene/views/Pendidikan.php">Program: Pendidikan</option>
                            <option value="/Kaya Tene/views/Ekonomi.php">Program: Ekonomi</option>
                            <option value="/Kaya Tene/views/Lingkungan%26Sosial.php">Program: Lingkungan & Sosial</option>
                            <option value="/Kaya Tene/views/semua-galeri.php">Galeri Dokumentasi</option>
                        </select>
                        <!-- Hidden input to store the text automatically -->
                        <input type="hidden" id="btn_text" name="btn_text" value="">
                    </div>

                    <button type="submit" class="btn-submit">Ubah Sekarang</button>
                    <a href="carousel.php"
                        style="color: var(--text-muted); text-decoration: none; margin-left: 20px;">Kembali ke Daftar</a>
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
