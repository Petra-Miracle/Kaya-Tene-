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
    // Failsafe: forcibly hide loader after a short timeout if it gets stuck
    const forceHideTimeout = setTimeout(() => {
        hideLoader();
    }, 1500); // 1.5 seconds is usually enough for a modern feel

    function hideLoader() {
        clearTimeout(forceHideTimeout);
        const loader = document.getElementById('page-loader');
        if (loader && loader.style.display !== 'none') {
            loader.classList.add('fade-out');
            setTimeout(() => {
                loader.style.display = 'none';
            }, 400);
        }
    }

    // Hide when DOM is ready (interactive)
    document.addEventListener('DOMContentLoaded', hideLoader);

    // Fade out when page is fully loaded (images, etc.)
    window.addEventListener('load', hideLoader);

    // Show loading spinner on link click (page transition)
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            hideLoader();
        }
    });

    document.addEventListener('click', function (e) {
        const link = e.target.closest('a');
        if (link && link.href) {
            const href = link.getAttribute('href');
            if (!href) return;

            // Better same-page anchor detection
            const currentPath = window.location.pathname;
            const linkUrl = new URL(link.href, window.location.origin);
            const isSamePage = linkUrl.pathname === currentPath || 
                               (linkUrl.pathname === '/' && currentPath.endsWith('index.php')) ||
                               (currentPath === '/' && linkUrl.pathname.endsWith('index.php'));
            const isAnchor = linkUrl.hash !== '';
            
            const isSamePageAnchor = isSamePage && isAnchor;
            const targetBlank = link.getAttribute('target') === '_blank';
            const jsLink = href.startsWith('javascript:');

            if (!isSamePageAnchor && !targetBlank && !jsLink && !href.includes('mailto:')) {
                const loader = document.getElementById('page-loader');
                if (loader) {
                    // Safety check: don't show for very fast clicks or specific elements
                    loader.style.display = 'flex';
                    setTimeout(() => {
                        loader.classList.remove('fade-out');
                    }, 10);
                    
                    // Auto-hide after 3s if navigation failed/is slow
                    setTimeout(hideLoader, 3000);
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