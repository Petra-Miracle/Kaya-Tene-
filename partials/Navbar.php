<nav class="navbar" id="siteNavbar">
    <div class="nav-container">
        <!-- BRANDING -->
        <a href="/Kaya Tene/index.php" class="navbar-logo">
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
            $currentPath = strtolower($_SERVER['PHP_SELF']);
            $navItems = [
                'index' => ['label' => 'Beranda', 'url' => '/Kaya Tene/index.php'],
                'profil' => ['label' => 'Profil', 'url' => '#', 'dropdown' => [
                    ['label' => 'Tentang Kami', 'url' => '/Kaya Tene/index.php#tentang', 'icon' => 'fa-landmark'],
                    ['label' => 'Visi & Misi', 'url' => '/Kaya Tene/index.php#visi-misi', 'icon' => 'fa-bullseye'],
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

            // Detect active dropdown parent
            function isDropdownActive($dropdown, $currentPath) {
                foreach ($dropdown as $sub) {
                    $subPath = strtolower(urldecode($sub['url']));
                    if (strpos($currentPath, basename($subPath, '.php')) !== false && basename($subPath, '.php') !== 'index') {
                        return true;
                    }
                }
                return false;
            }
            ?>

            <ul class="nav-list" id="navLinks">
                <?php foreach ($navItems as $key => $item): ?>
                    <?php
                    $isActive = isset($item['dropdown'])
                        ? isDropdownActive($item['dropdown'], $currentPath)
                        : ($currentPage == $key || ($key == 'index' && ($currentPage == '' || $currentPage == 'index')));
                    ?>
                    <li class="<?php echo isset($item['dropdown']) ? 'has-dropdown' : ''; ?>">
                        <?php if (isset($item['dropdown'])): ?>
                            <a href="javascript:void(0)" class="nav-link dropdown-trigger <?php echo $isActive ? 'active' : ''; ?>" aria-expanded="false">
                                <?php echo $item['label']; ?>
                                <i class="fa-solid fa-chevron-down chevron-icon"></i>
                            </a>
                            <div class="dropdown-pane">
                                <?php foreach ($item['dropdown'] as $sub): ?>
                                    <?php
                                    $subPath = strtolower(urldecode($sub['url']));
                                    $subName = basename($subPath, '.php');
                                    $isSubActive = false;
                                    
                                    if (strpos($subPath, '#') !== false) {
                                        // Link with anchor, check if we are on the page
                                        $parts = explode('#', $subPath);
                                        if (strpos($currentPath, basename($parts[0])) !== false) {
                                            // Optional: check anchor in JS, but for PHP we just mark it active if page matches
                                            // Actually, usually we don't mark anchor links active unless we are at that scroll position
                                        }
                                    } else {
                                        $isSubActive = strpos($currentPath, $subName) !== false;
                                    }
                                    ?>
                                    <a href="<?php echo $sub['url']; ?>" class="dropdown-item <?php echo $isSubActive ? 'active' : ''; ?>">
                                        <i class="fa-solid <?php echo $sub['icon']; ?>"></i>
                                        <span><?php echo $sub['label']; ?></span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <a href="<?php echo $item['url']; ?>" class="nav-link <?php echo $isActive ? 'active' : ''; ?>">
                                <?php echo $item['label']; ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="/Kaya Tene/index.php#kontak" class="cta-btn">Hubungi Kami</a>
                </li>
            </ul>
        </div>

        <!-- ACTIONS -->
        <div class="nav-actions">
            <button class="icon-action" id="themeToggle" title="Ganti Tema">
                <i class="fa-solid fa-moon" id="themeIcon"></i>
            </button>
            <button class="hamburger" id="mobileToggle" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>

    <!-- SEARCH OVERLAY -->
    <div class="search-overlay" id="searchOverlay"></div>
</nav>

<!-- Mobile Menu Overlay Backdrop -->
<div class="mobile-overlay" id="mobileOverlay"></div>

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
        const mobileOverlay = document.getElementById('mobileOverlay');
        
        function toggleMobileMenu(forceClose = false) {
            if (!mobileToggle || !navLinks) return;
            
            const isOpening = forceClose ? false : !mobileToggle.classList.contains('active');
            
            if (isOpening) {
                mobileToggle.classList.add('active');
                navLinks.classList.add('active');
                body.classList.add('no-scroll');
                if (mobileOverlay) mobileOverlay.classList.add('active');
            } else {
                mobileToggle.classList.remove('active');
                navLinks.classList.remove('active');
                body.classList.remove('no-scroll');
                if (mobileOverlay) mobileOverlay.classList.remove('active');
            }
        }

        if (mobileToggle) {
            mobileToggle.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                toggleMobileMenu();
            });
        }

        if (mobileOverlay) {
            mobileOverlay.addEventListener('click', () => toggleMobileMenu(true));
        }

        // Close menu when clicking links (especially for anchors)
        navLinks?.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 992) {
                    toggleMobileMenu(true);
                }
            });
        });

        // 3. Mobile Dropdown Accordion
        document.querySelectorAll('.dropdown-trigger').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                if (window.innerWidth <= 992) {
                    e.preventDefault();
                    const parent = this.parentElement;
                    const isOpen = parent.classList.contains('active');

                    // Close other dropdowns
                    document.querySelectorAll('.has-dropdown').forEach(item => {
                        if (item !== parent) {
                            item.classList.remove('active');
                            item.querySelector('.dropdown-trigger')?.setAttribute('aria-expanded', 'false');
                        }
                    });

                    parent.classList.toggle('active', !isOpen);
                    this.setAttribute('aria-expanded', !isOpen ? 'true' : 'false');
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
        document.querySelectorAll('a').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const href = this.getAttribute('href');
                if (!href || href === '#' || href.startsWith('javascript:')) return;
                
                if (href.includes('#')) {
                    const urlParts = href.split('#');
                    const targetId = urlParts[1];
                    const targetPath = urlParts[0];
                    const targetElement = document.getElementById(targetId);
                    
                    if (targetElement) {
                        const currentPath = window.location.pathname;
                        // Check if we are already on the target page
                        const isSamePage = targetPath === '' || 
                                         currentPath.endsWith(targetPath) || 
                                         (targetPath.includes('index.php') && (currentPath.endsWith('/') || currentPath.endsWith('index.php')));

                        if (isSamePage) {
                            e.preventDefault();
                            closeMobileMenu();
                            
                            const offsetTop = targetElement.getBoundingClientRect().top + window.pageYOffset - 80;
                            window.scrollTo({
                                top: offsetTop,
                                behavior: 'smooth'
                            });
                            
                            // Update URL hash without jumping
                            history.pushState(null, null, '#' + targetId);
                        }
                    }
                }
            });
        });
    });
</script>