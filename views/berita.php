<?php
require_once '../config/Connection.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Terkini - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="/Kaya Tene/css/style.css">
    <link rel="stylesheet" href="/Kaya Tene/css/berita.css">
    <?php include '../partials/HeaderAssets.php'; ?>
</head>

<body>
    <?php require_once '../partials/Loader.php'; ?>

    <?php include './../partials/Navbar.php'; ?>

    <div class="page-header">
        <div class="container">
            <h1 class="page-title">Berita & <span class="text-gradient">Kegiatan</span></h1>
            <p style="color: var(--text-muted); font-size: 1.2rem;">Kumpulan informasi terbaru, kegiatan, dan program
                yang telah kami jalankan bersama masyarakat.</p>
        </div>
    </div>

    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="news-grid">
                <?php
                // Configuration for pagination
                $limit = 6;
                $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                $offset = ($page - 1) * $limit;

                // Query with pagination
                $sql = "SELECT id, judul, isi, gambar, tanggal FROM Berita ORDER BY tanggal DESC LIMIT $limit OFFSET $offset";
                $result = $conn->query($sql);

                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $excerpt = strlen($row['isi']) > 150 ? substr($row['isi'], 0, 150) . '...' : $row['isi'];
                        $gambar = $row['gambar'] ? '/Kaya Tene/uploads/' . htmlspecialchars($row['gambar']) : 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
                        $tanggal = date('d F Y', strtotime($row['tanggal']));

                        echo '
                        <div class="news-card glass">
                            <img src="' . $gambar . '" alt="Berita" class="news-img" onerror="this.src=\'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80\'">
                            <div class="news-content">
                                <div class="news-date">' . $tanggal . '</div>
                                <h4 class="news-title">' . htmlspecialchars($row['judul']) . '</h4>
                                <p class="news-excerpt">' . htmlspecialchars($excerpt) . '</p>
                                <a href="detail-berita.php?id=' . $row['id'] . '" class="news-link">Baca Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<h3 class="glass" style="grid-column: 1/-1; padding: 50px; text-align: center; border-radius: 20px;">Belum ada berita.</h3>';
                }
                ?>
            </div>

            <?php
            // Pagination links
            $count_sql = "SELECT COUNT(id) as total FROM Berita";
            $count_result = $conn->query($count_sql);
            if ($count_result) {
                $total_row = $count_result->fetch_assoc();
                $total_pages = ceil($total_row['total'] / $limit);

                if ($total_pages > 1) {
                    echo '<ul class="pagination">';
                    for ($i = 1; $i <= $total_pages; $i++) {
                        $active = $i == $page ? 'active' : '';
                        echo '<li><a href="berita.php?page=' . $i . '" class="page-link ' . $active . '">' . $i . '</a></li>';
                    }
                    echo '</ul>';
                }
            }
            ?>
        </div>
    </section>

    <?php include '../partials/Footer.php'; ?>

</body>

</html>