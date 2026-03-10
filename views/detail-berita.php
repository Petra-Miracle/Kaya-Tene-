<?php
require_once '../config/Connection.php';

// Validate the ID
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
} else {
    // Redirect if no valid ID provided
    header("Location: berita.php");
    exit();
}

// Fetch news single
$stmt = $conn->prepare("SELECT judul, isi, gambar, tanggal FROM Berita WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $berita = $result->fetch_assoc();
} else {
    header("Location: berita.php");
    exit();
}

$gambar = $berita['gambar'] ? '/Kaya Tene/uploads/' . htmlspecialchars($berita['gambar']) : 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80';
$tanggal = date('d F Y', strtotime($berita['tanggal']));
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= htmlspecialchars($berita['judul']) ?> - Yayasan Kaya Tene
    </title>
    <link rel="stylesheet" href="/Kaya Tene/css/style.css">
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
            object-fit: cover;
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
                    <?= htmlspecialchars($berita['judul']) ?>
                </h1>
                <div class="article-meta">Diterbitkan pada:
                    <?= $tanggal ?>
                </div>
            </div>

            <img src="<?= $gambar ?>" alt="Berita Image" class="article-image"
                onerror="this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80'">

            <div class="article-content glass" style="padding: 50px; border-radius: 30px;">
                <?= nl2br(htmlspecialchars($berita['isi'])) ?>

                <div style="margin-top: 40px; text-align: center;">
                    <a href="berita.php" class="back-btn"><i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
                        Berita</a>
                </div>
            </div>
        </div>
    </article>

    <?php include '../partials/Footer.php'; ?>

</body>

</html>