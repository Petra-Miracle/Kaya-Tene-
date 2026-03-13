<nav class="navbar" id="siteNavbar">
    <div class="nav-container">
        <!-- BRANDING -->
        <a href="index.php" class="navbar-logo">
            <?php $logo_path = '/Kaya Tene/Public/img/Logo_Yayasan-new.png'; ?>
            <img src="<?php echo $logo_path; ?>" alt="Kaya Tene Logo" class="logo-img">
            <div class="logo-text">
                <span class="text-primary">Yayasan</span>
                <span class="text-main">Kaya Tene</span>
            </div>
        </a>

        <!-- NAVIGATION -->
        <div class="nav-menu">
            <?php
            $currentPage = basename($_SERVER['PHP_SELF'], '.php');
            $navItems = [
                'index' => ['label' => 'Beranda', 'url' => 'index.php'],
                'profil' => ['label' => 'Profil', 'url' => '#', 'dropdown' => [
                    ['label' => 'Tentang Kami', 'url' => 'index.php#tentang', 'icon' => 'fa-landmark'],
                    ['label' => 'Visi & Misi', 'url' => 'index.php#visi-misi', 'icon' => 'fa-bullseye'],
                    ['label' => 'Struktur', 'url' => '/Kaya Tene/views/Struktur-Organisasi.php', 'icon' => 'fa-sitemap']
                ]],
                'program' => ['label' => 'Program', 'url' => '#', 'dropdown' => [
                    ['label' => 'Pendidikan', 'url' => '/Kaya Tene/views/Pendidikan.php', 'icon' => 'fa-graduation-cap'],
                    ['label' => 'Ekonomi', 'url' => '/Kaya Tene/views/Ekonomi.php', 'icon' => 'fa-chart-line'],
                    ['label' => 'Sosial', 'url' => '/Kaya Tene/views/Lingkungan%26Sosial.php', 'icon' => 'fa-handshake-angle'],
                    ['label' => 'Pangan', 'url' => '/Kaya Tene/views/Pertanian.php', 'icon' => 'fa-wheat-awn']
                ]],
                'berita' => ['label' => 'Berita', 'url' => '/Kaya Tene/views/berita.php'],
                'galeri' => ['label' => 'Galeri', 'url' => '/Kaya Tene/views/semua-galeri.php']
            ];
            ?>

            <ul class="nav-list" id="navLinks">
                <?php foreach ($navItems as $key => $item): ?>
                    <li class="<?php echo isset($item['dropdown']) ? 'has-dropdown' : ''; ?>">
                        <?php if (isset($item['dropdown'])): ?>
                            <a href="javascript:void(0)" class="nav-link dropdown-trigger">
                                <?php echo $item['label']; ?>
                                <i class="fa-solid fa-chevron-down chevron-icon"></i>
                            </a>
                            <div class="dropdown-pane">
                                <?php foreach ($item['dropdown'] as $sub): ?>
                                    <a href="<?php echo $sub['url']; ?>" class="dropdown-item">
                                        <i class="fa-solid <?php echo $sub['icon']; ?>"></i>
                                        <span><?php echo $sub['label']; ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo ($currentPage == $key || ($key == 'index' && $currentPage == '')) ? 'active' : ''; ?>">
                                <?php echo $item['label']; ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="index.php#kontak" class="cta-btn">Hubungi Kami</a>
                </li>
            </ul>
        </div>

        <!-- ACTIONS -->
        <div class="nav-actions">
            
            <button class="icon-action" id="themeToggle" title="Ganti Tema">
                <i class="fa-solid fa-moon" id="themeIcon"></i>
            </button>
            <button class="hamburger" id="mobileToggle">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <!-- SEARCH OVERLAY -->
    <div class="search-overlay" id="searchOverlay">
        
    </div>
</nav>

<script>
    // Simplified & Modern Navbar Logic
    document.addEventListener('DOMContentLoaded', () => {
        const navbar = document.getElementById('siteNavbar');
        const mobileToggle = document.getElementById('mobileToggle');
        const navLinks = document.getElementById('navLinks');
        const searchTrigger = document.getElementById('searchTrigger');
        const searchOverlay = document.getElementById('searchOverlay');
        const searchClose = document.getElementById('searchClose');
        const searchInput = document.getElementById('searchInput');
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const body = document.body;

        // 1. Scroll Effect
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // 2. Mobile Menu Toggle
        if (mobileToggle) {
            mobileToggle.addEventListener('click', () => {
                mobileToggle.classList.toggle('active');
                navLinks.classList.toggle('active');
                body.classList.toggle('no-scroll');
            });
        }

        // 3. Mobile Dropdown Accordion
        document.querySelectorAll('.dropdown-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    const isOpen = parent.classList.contains('active');
                    
                    // Close other dropdowns
                    document.querySelectorAll('.has-dropdown').forEach(item => {
                        if (item !== parent) item.classList.remove('active');
                    });

                    parent.classList.toggle('active', !isOpen);
                }
            });
        });

        // 4. Search Overlay
        if (searchTrigger) {
            searchTrigger.addEventListener('click', () => {
                searchOverlay.classList.add('active');
                setTimeout(() => searchInput.focus(), 300);
            });
        }
        if (searchClose) {
            searchClose.addEventListener('click', () => {
                searchOverlay.classList.remove('active');
            });
        }
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') searchOverlay.classList.remove('active');
        });

        // 5. Theme Toggle
        const currentTheme = localStorage.getItem('theme');
        if (currentTheme === 'light') {
            body.classList.add('light-mode');
            themeIcon.className = 'fa-solid fa-sun';
        }

        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                body.classList.toggle('light-mode');
                const isLight = body.classList.contains('light-mode');
                localStorage.setItem('theme', isLight ? 'light' : 'dark');
                themeIcon.className = isLight ? 'fa-solid fa-sun' : 'fa-solid fa-moon';
            });
        }

        // 6. Smooth Scroll for Anchors
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (href === '#') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    // Close mobile menu if open
                    mobileToggle.classList.remove('active');
                    navLinks.classList.remove('active');
                    body.classList.remove('no-scroll');

                    window.scrollTo({
                        top: target.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>