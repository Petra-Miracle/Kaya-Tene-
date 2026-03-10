<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-about">
                <a href="#" class="logo">
                    <span>Yayasan</span> Kaya Tene
                </a>
                <p>Mewujudkan masyarakat mandiri, berdaya, dan mampu mengelola potensi lokal secara berkelanjutan
                    melalui gerakan nyata di NTT. "Seperti Kita, Seperti Perahu".</p>

                <style>
                    .social-links a {
                        width: 40px;
                        height: 40px;
                        background: rgba(255, 107, 0, 0.05);
                        border: 1px solid var(--glass-border);
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        color: var(--primary);
                        text-decoration: none;
                        transition: all 0.3s ease;
                    }

                    .social-links a:hover {
                        background: var(--primary);
                        color: white !important;
                        transform: translateY(-5px);
                        box-shadow: 0 5px 15px rgba(255, 107, 0, 0.4);
                    }
                </style>
                <div class="social-links" style="margin-top: 25px; display: flex; gap: 15px;">
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="footer-links">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="/Kaya Tene/index.php">Beranda</a></li>
                    <li><a href="/Kaya Tene/index.php#tentang">Tentang Kami</a></li>
                    <li><a href="/Kaya Tene/index.php#visi-misi">Visi & Misi</a></li>
                    <li><a href="/Kaya Tene/views/berita.php">Berita</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Legalitas</h4>
                <ul>
                    <li>KEMENKUMHAM RI</li>
                    <li>Akta Nomor 12</li>
                    <li>AHU 0004039. AH. 01. 04.</li>
                    <li>Tahun 2024</li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy;
                <?php echo date('Y'); ?> Yayasan Kaya Tene. All rights reserved.
            </p>
        </div>
    </div>
</footer>