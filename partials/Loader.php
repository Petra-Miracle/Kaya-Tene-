<?php
// Use an absolute path from the domain root for the image, suitable across all folders
$loader_img_path = '/Kaya Tene/Public/img/Logo_Yayasan-new.png';
?>
<div id="page-loader" class="page-loader">
    <div class="spinner-container">
        <img src="<?php echo $loader_img_path; ?>" alt="Loading..." class="spinner-logo">
    </div>
</div>

<script>
    // Fade out when page is fully loaded
    window.addEventListener('load', function () {
        const loader = document.getElementById('page-loader');
        if (loader) {
            loader.classList.add('fade-out');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 600); // Matches transition duration
        }
    });

    // Show loading spinner on link click (page transition)
    window.addEventListener('pageshow', function (event) {
        // Handle back/forward cache restore to hide spinner
        if (event.persisted) {
            const loader = document.getElementById('page-loader');
            if (loader) {
                loader.classList.add('fade-out');
                loader.style.display = 'none';
            }
        }
    });

    document.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (link && link.href) {
            const href = link.getAttribute('href');
            // Check if it's pointing to an anchor on the same page, external window, or js
            const isAnchor = href && href.startsWith('#');
            // In Navbar.php, some links look like `index.php#tentang`. We shouldn't show loader if on index.php
            let isSamePageAnchor = isAnchor;
            if (href && href.includes('.php#')) {
                const parts = href.split('#');
                const baseUrl = window.location.pathname.split('/').pop();
                if (parts[0] === baseUrl || (parts[0] === 'index.php' && baseUrl === '')) {
                    isSamePageAnchor = true;
                }
            }

            const targetBlank = link.getAttribute('target') === '_blank';
            const jsLink = href && href.startsWith('javascript:');

            if (!isSamePageAnchor && !targetBlank && !jsLink) {
                const loader = document.getElementById('page-loader');
                if (loader) {
                    loader.style.display = 'flex';
                    // small delay before removing fade-out to ensure display flex applies
                    setTimeout(() => {
                        loader.classList.remove('fade-out');
                    }, 10);
                }
            }
        }
    });

    // Premium Scroll Animation Observer
    document.addEventListener("DOMContentLoaded", function () {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.15
        };

        const scrollObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target); // Run once
                }
            });
        }, observerOptions);

        // Auto-assign animation class to premium elements
        const elementsToAnimate = document.querySelectorAll('.glass, .section-title, .news-card, .vm-card, .stat-card, .about-text, .about-image');
        elementsToAnimate.forEach(el => {
            // Don't animate navbar glass immediately or loader
            if (!el.classList.contains('navbar') && !el.classList.contains('page-loader') && !el.classList.contains('login-card') && !el.classList.contains('form-container')) {
                el.classList.add('animate-on-scroll');
                scrollObserver.observe(el);
            }
        });

        // Setup staggers for grids
        document.querySelectorAll('.news-grid, .vm-grid, .stat-grid, .footer-grid').forEach(grid => {
            grid.classList.add('grid-stagger');
        });
    });
</script>