<?php
require_once '../config/Connection.php';

// Validate the ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    // Redirect if no valid ID provided
    header("Location: ../index.php#galeri");
    exit();
}

// Fetch single gallery item
$stmt = $conn->prepare("SELECT judul, deskripsi, gambar, tanggal FROM Galeri WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $galeri = $result->fetch_assoc();
} else {
    header("Location: ../index.php#galeri");
    exit();
}

$gambar = $galeri['gambar'] ? '/Kaya Tene/uploads/' . htmlspecialchars($galeri['gambar']) : 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
$tanggal = date('d F Y', strtotime($galeri['tanggal']));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($galeri['judul']) ?> - Galeri Yayasan Kaya Tene
    </title>
    <link rel="stylesheet" href="/Kaya Tene/css/style.css">
    <?php include '../partials/HeaderAssets.php'; ?>
    <style>
        .article-header {
            padding: 150px 0 50px;
        }

        .article-title {
            font-size: 3rem;
            margin-bottom: 20px;
            color: var(--text-main);
        }

        .article-meta {
            color: var(--primary-light);
            font-size: 1.1rem;
            margin-bottom: 40px;
            display: inline-block;
            background: var(--bg-card);
            padding: 10px 20px;
            border-radius: 50px;
            border: 1px solid var(--glass-border);
        }

        .article-image {
            width: 100%;
            max-height: 600px;
            object-fit: contain;
            background: var(--bg-dark);
            border-radius: 30px;
            margin-bottom: 50px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .article-content {
            font-size: 1.2rem;
            line-height: 1.8;
            color: var(--text-muted);
        }

        .article-content p {
            margin-bottom: 25px;
        }

        .back-btn {
            display: inline-block;
            margin-top: 50px;
            color: var(--text-main);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .back-btn:hover {
            color: var(--primary);
        }
    </style>
</head>

<body>
    <?php require_once '../partials/Loader.php'; ?>

    <?php include '../partials/Navbar.php'; ?>

    <article class="section" style="padding-top: 0;">
        <div class="container" style="max-width: 900px;">
            <div class="article-header text-center" style="text-align: center;">
                <h1 class="article-title">
                    <?= htmlspecialchars($galeri['judul']) ?>
                </h1>
                <div class="article-meta"><i class="fa-regular fa-calendar" style="margin-right: 8px;"></i>
                    <?= $tanggal ?>
                </div>
            </div>

            <img src="<?= $gambar ?>" alt="Galeri Image" class="article-image"
                onerror="this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'">

            <div class="article-content glass" style="padding: 50px; border-radius: 30px;">
                <h3 style="color: var(--text-main); margin-bottom: 20px;">Deskripsi Kegiatan</h3>
                <?= nl2br(htmlspecialchars($galeri['deskripsi'])) ?>

                <div style="margin-top: 40px; text-align: center;">
                    <a href="/Kaya Tene/index.php#galeri" class="back-btn"><i class="fa-solid fa-arrow-left"></i>
                        Kembali ke Galeri</a>
                </div>
            </div>
        </div>
    </article>

    <?php include '../partials/Footer.php'; ?>

</body>

</html>