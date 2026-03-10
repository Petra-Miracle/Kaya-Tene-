<nav class="navbar glass">
    <div class="container nav-container">
        <a href="index.php" class="logo">
            <?php
            // Use root-relative paths for the logo so it always loads regardless of where the file is included
            $img_path = '/Kaya Tene/Public/img/Logo_Yayasan-new.png';
            ?>
            <img src="<?php echo $img_path; ?>" alt="Logo Yayasan Kaya Tene"
                style="height: 45px; width: auto; display: block; transform: scale(1.8); transform-origin: left center; margin-right: 20px;">
            <span>Yayasan</span>&nbsp;Kaya Tene
        </a>

        <?php
        $homeLink = '/Kaya Tene/index.php';
        $tentangLink = '/Kaya Tene/index.php#tentang';
        $visiMisiLink = '/Kaya Tene/index.php#visi-misi';
        $beritaLink = '/Kaya Tene/views/berita.php';
        $galeriLink = '/Kaya Tene/views/semua-galeri.php';
        ?>
        <ul class="nav-links">
            <li><a href="<?php echo $homeLink; ?>">Beranda</a></li>
            <li><a href="<?php echo $tentangLink; ?>">Tentang Kami</a></li>
            <li><a href="<?php echo $visiMisiLink; ?>">Visi & Misi</a></li>
            <li><a href="<?php echo $beritaLink; ?>">Berita</a></li>
            <li><a href="<?php echo $galeriLink; ?>">Galeri</a></li>
            <li style="display: flex; align-items: center;">
                <button id="themeToggle" aria-label="Toggle Theme"
                    style="background: transparent; border: 1px solid var(--glass-border); color: var(--text-main); cursor: pointer; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; transition: all 0.3s ease;">
                    <i class="fa-solid fa-sun" id="themeIcon"></i>
                </button>
            </li>
        </ul>

        <button class="hamburger">
            <i class="fa-solid fa-bars"></i>
        </button>
    </div>
</nav>

<script>
    // Theme Toggle Logic
    const themeToggleBtn = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    const body = document.body;

    // Check localStorage for theme
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'light') {
        body.classList.add('light-mode');
        if (themeIcon) {
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        }
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', () => {
            body.classList.toggle('light-mode');
            if (body.classList.contains('light-mode')) {
                localStorage.setItem('theme', 'light');
                themeIcon.classList.remove('fa-sun');
                themeIcon.classList.add('fa-moon');
            } else {
                localStorage.setItem('theme', 'dark');
                themeIcon.classList.remove('fa-moon');
                themeIcon.classList.add('fa-sun');
            }
        });
    }

    // Navbar scroll effect
    window.addEventListener('scroll', function () {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Hamburger menu toggle for mobile
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            hamburger.innerHTML = navLinks.classList.contains('active') ? '<i class="fa-solid fa-xmark"></i>' : '<i class="fa-solid fa-bars"></i>';
        });

        // Close menu when a link is clicked
        const links = navLinks.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                hamburger.innerHTML = '<i class="fa-solid fa-bars"></i>';
            });
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
</script>