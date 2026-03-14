<footer class="footer">
    <div class="container">
        <div class="footer-simple-grid">
            <!-- Brand & Bio -->
            <div class="footer-col-main">
                <a href="/Kaya Tene/index.php" class="footer-logo">
                    <img src="/Kaya Tene/Public/img/Logo_Yayasan-new.png" alt="Logo Kaya Tene" class="footer-logo-img">
                    <div class="logo-text">
                        <span class="text-primary">Yayasan</span>
                        <span class="text-main">Kaya Tene</span>
                    </div>
                </a>
                <p class="footer-simple-bio">
                    Mewujudkan masyarakat mandiri dan berdaya melalui pengelolaan potensi lokal secara berkelanjutan di NTT.
                </p>
                <div class="footer-social-links">
                    <a href="https://www.instagram.com/kaya_tene" target="_blank" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.facebook.com/share/1DW9tEnBFh/" target="_blank" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="https://www.youtube.com/@YayasanKayaTene" target="_blank" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="https://www.tiktok.com/@kayatenekupang" target="_blank" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col-nav">
                <h4 class="footer-simple-title">Navigasi</h4>
                <ul class="footer-simple-links">
                    <li><a href="/Kaya Tene/index.php">Beranda</a></li>
                    <li><a href="/Kaya Tene/views/Struktur-Organisasi.php">Struktur</a></li>
                    <li><a href="/Kaya Tene/views/berita.php">Berita</a></li>
                    <li><a href="/Kaya Tene/views/semua-galeri.php">Galeri</a></li>
                </ul>
            </div>

            <!-- Contact & Legal -->
            <div class="footer-col-contact">
                <h4 class="footer-simple-title">Hubungi Kami</h4>
                <ul class="footer-simple-contact">
                    <li><i class="fa-solid fa-location-dot"></i> Kupang, Nusa Tenggara Timur</li>
                    <li><i class="fa-solid fa-envelope"></i> yayasankayatene@gmail.com</li>
                    <li><i class="fa-brands fa-whatsapp"></i> +62 823-4106-7389</li>
                </ul>
                <div class="legal-info-simple">
                    KEMENKUMHAM AHU-0004039. AH. 01. 04.
                </div>
            </div>
        </div>

        <div class="footer-simple-bottom">
            <p>&copy; <?php echo date('Y'); ?> <strong>Yayasan Kaya Tene</strong>. All rights reserved.</p>
            <button id="simpleBackToTop" class="btn-back-top"><i class="fa-solid fa-arrow-up"></i></button>
        </div>
    </div>
</footer>

<script>
    const simpleBackToTop = document.getElementById('simpleBackToTop');
    if (simpleBackToTop) {
        window.addEventListener('scroll', () => {
            simpleBackToTop.classList.toggle('active', window.pageYOffset > 400);
        });
        simpleBackToTop.onclick = () => window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>