<?php
require_once '../config/Connection.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Galeri - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="/Kaya Tene/css/style.css">
    <link rel="stylesheet" href="/Kaya Tene/css/berita.css">
    <?php include '../partials/HeaderAssets.php'; ?>
    <style>
        .gallery-item:hover img {
            transform: scale(1.1);
        }

        .gallery-item:hover .gallery-overlay {
            opacity: 1;
            background: linear-gradient(to top, rgba(255, 107, 0, 0.8), rgba(0, 0, 0, 0.2) 60%, transparent);
        }
    </style>
</head>

<body>
    <?php require_once '../partials/Loader.php'; ?>

    <?php include './../partials/Navbar.php'; ?>

    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Galeri <span class="text-gradient">Kegiatan</span></h1>
            <p style="color: var(--text-muted); font-size: 1.2rem;">Dokumentasi kegiatan agrokultur dan aktivitas
                lainnya yang telah kami jalankan bersama masyarakat.</p>
        </div>
    </div>

    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="gallery-grid"
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                <?php
                // Configuration for pagination
                $limit = 9;
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                // Query with pagination
                $sql = "SELECT id, judul, deskripsi, gambar, tanggal FROM Galeri ORDER BY id DESC LIMIT $limit OFFSET $offset";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($rowg = $result->fetch_assoc()) {
                        $img_src = '/Kaya Tene/uploads/' . htmlspecialchars($rowg['gambar']);
                        $title = htmlspecialchars($rowg['judul']);
                        $tanggal = date('d M Y', strtotime($rowg['tanggal']));

                        echo '
                        <div class="gallery-item glass" data-id="' . $rowg['id'] . '" data-img="' . $img_src . '" data-title="' . $title . '" data-desc="' . htmlspecialchars($rowg['deskripsi']) . '" data-date="' . $tanggal . '" onclick="openGalleryModal(this)" style="border-radius: 15px; overflow: hidden; position: relative; cursor: pointer;">
                            <img src="' . $img_src . '" alt="' . $title . '" style="width: 100%; height: 250px; object-fit: cover; display: block; transition: transform 0.5s ease;">
                            <div class="gallery-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); padding: 30px 20px 20px; text-align: left; transition: opacity 0.3s; opacity: 0.9;">
                                <h4 style="color: white; margin-bottom: 5px; font-size: 1.2rem;">' . $title . '</h4>
                                <p style="color: var(--primary-light); font-size: 0.9rem; margin: 0;"><i class="fa-regular fa-calendar" style="margin-right: 5px;"></i>' . $tanggal . '</p>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<h3 class="glass" style="grid-column: 1/-1; padding: 50px; text-align: center; border-radius: 20px;">Belum ada foto galeri.</h3>';
                }
                ?>
            </div>

            <?php
            // Pagination links
            $count_sql = "SELECT COUNT(id) as total FROM Galeri";
            $count_result = $conn->query($count_sql);
            if ($count_result) {
                $total_row = $count_result->fetch_assoc();
                $total_pages = ceil($total_row['total'] / $limit);

                if ($total_pages > 1) {
                    echo '<ul class="pagination">';
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active = $i == $page ? 'active' : '';
                        echo '<li><a href="semua-galeri.php?page=' . $i . '" class="page-link ' . $active . '">' . $i . '</a></li>';
                    }
                    echo '</ul>';
                }
            }
            ?>
        </div>
    </section>

    <?php require_once '../partials/GalleryModal.php'; ?>

    <?php include '../partials/Footer.php'; ?>

</body>

</html>