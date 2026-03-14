<section class="section" id="galeri"
    style="background-color: var(--bg-card); border-top: 1px solid var(--glass-border);">
    <div class="container">
        <h2 class="section-title">Galeri <span class="text-gradient">Kegiatan</span></h2>
        <p style="text-align: center; color: var(--text-muted); margin-top: -30px; margin-bottom: 40px;">Dokumentasi
            kegiatan agrokultur dan aktivitas lainnya.</p>

        <div class="gallery-grid"
            style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
            <?php
            // Fetch latest gallery photos - optimized query with specific columns only
            $sql_galeri = "SELECT id, judul, deskripsi, gambar, tanggal FROM Galeri ORDER BY id DESC LIMIT 6";
            $res_galeri = $conn->query($sql_galeri);

            if ($res_galeri && $res_galeri->num_rows > 0) {
                while ($rowg = $res_galeri->fetch_assoc()) {
                    $img_src = '/Kaya Tene/uploads/' . htmlspecialchars($rowg['gambar']);
                    $title = htmlspecialchars($rowg['judul']);
                    $tanggal = date('d M Y', strtotime($rowg['tanggal']));
                    echo '
                    <div class="gallery-item glass" data-id="' . $rowg['id'] . '" data-img="' . $img_src . '" data-title="' . $title . '" data-desc="' . htmlspecialchars($rowg['deskripsi']) . '" data-date="' . $tanggal . '" onclick="openGalleryModal(this)" style="border-radius: 15px; overflow: hidden; position: relative; cursor: pointer;">
                        <img src="' . $img_src . '" alt="' . $title . '" loading="lazy" style="width: 100%; height: 250px; object-fit: cover; display: block; transition: transform 0.5s ease;">
                        <div class="gallery-overlay" style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); padding: 30px 20px 20px; text-align: left; transition: opacity 0.3s; opacity: 0.9;">
                            <h4 style="color: white; margin-bottom: 5px; font-size: 1.2rem;">' . $title . '</h4>
                            <p style="color: var(--primary-light); font-size: 0.9rem; margin: 0;"><i class="fa-regular fa-calendar" style="margin-right: 5px;"></i>' . $tanggal . '</p>
                        </div>
                    </div>';
                }
            } else {
                echo '
                <div class="glass" style="grid-column: 1/-1; padding: 40px; text-align: center; border-radius: 20px;">
                    <h3 class="text-muted">Belum ada foto galeri.</h3>
                    <p>Aktivitas Agrokultur dan lainnya akan ditampilkan di sini.</p>
                </div>';
            }
            ?>
        </div>

        <style>
            .gallery-item:hover img {
                transform: scale(1.1);
            }

            .gallery-item:hover .gallery-overlay {
                opacity: 1;
                background: linear-gradient(to top, rgba(255, 107, 0, 0.8), rgba(0, 0, 0, 0.2) 60%, transparent);
            }
        </style>

        <div style="text-align: center; margin-top: 50px;">
            <a href="/Kaya Tene/views/semua-galeri.php" class="btn btn-primary" style="padding: 12px 40px;">Lihat Semua
                Galeri</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../partials/GalleryModal.php'; ?>