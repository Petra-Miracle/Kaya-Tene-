<!-- Gallery Modal -->
<div id="galleryModal" class="gallery-modal" onclick="if(event.target === this) closeGalleryModal()">
    <div class="gallery-modal-content glass">
        <span class="gallery-modal-close" onclick="closeGalleryModal()"><i class="fa-solid fa-xmark"></i></span>
        <img id="galleryModalImg" src="" alt="Gallery Image">
        <div class="gallery-modal-info">
            <h3 id="galleryModalTitle" style="color: var(--text-main); margin-bottom: 5px; font-size: 1.5rem;"></h3>
            <p id="galleryModalDate" style="color: var(--primary-light); font-size: 0.9rem; margin-bottom: 15px;"><i
                    class="fa-regular fa-calendar" style="margin-right: 5px;"></i><span></span></p>
            <p id="galleryModalDesc" style="color: var(--text-muted); line-height: 1.6; margin-bottom: 20px;"></p>
            <a id="galleryModalLink" href="#" class="btn btn-primary"
                style="display: inline-block; padding: 10px 20px;">Lihat Selengkapnya <i class="fa-solid fa-arrow-right"
                    style="margin-left: 5px;"></i></a>
        </div>
    </div>
</div>

<style>
    /* Modal Styles */
    .gallery-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(5px);
        z-index: 10000;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .gallery-modal.show {
        opacity: 1;
    }

    .gallery-modal-content {
        background: var(--bg-card);
        width: 90%;
        max-width: 800px;
        border-radius: 20px;
        overflow: hidden;
        position: relative;
        transform: translateY(20px) scale(0.95);
        transition: all 0.3s ease;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .gallery-modal.show .gallery-modal-content {
        transform: translateY(0) scale(1);
    }

    .gallery-modal-close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 1.5rem;
        color: white;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.5);
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background 0.3s;
        z-index: 10;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .gallery-modal-close:hover {
        background: var(--primary);
    }

    #galleryModalImg {
        width: 100%;
        object-fit: cover;
        max-height: 50vh;
        background: var(--bg-dark);
    }

    .gallery-modal-info {
        padding: 30px;
        overflow-y: auto;
    }

    /* Light Mode Adjustments */
    body.light-mode .gallery-modal {
        background: rgba(255, 255, 255, 0.8);
    }

    body.light-mode .gallery-modal-content {
        background: #ffffff;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    body.light-mode .gallery-modal-close {
        background: rgba(0, 0, 0, 0.1);
        color: #222;
        border-color: rgba(0, 0, 0, 0.1);
    }

    body.light-mode .gallery-modal-close:hover {
        background: var(--primary);
        color: white;
    }
</style>

<script>
    function openGalleryModal(element) {
        const id = element.getAttribute('data-id');
        const src = element.getAttribute('data-img');
        const title = element.getAttribute('data-title');
        const desc = element.getAttribute('data-desc');
        const date = element.getAttribute('data-date');

        document.getElementById('galleryModalImg').src = src;
        document.getElementById('galleryModalTitle').innerText = title;
        // We can use innerHTML for description if we want to support basic HTML like linebreaks
        // Using innerText for security, replacing newlines with <br> if needed, but innerText handles it visually.
        document.getElementById('galleryModalDesc').innerText = desc;
        document.querySelector('#galleryModalDate span').innerText = date;
        document.getElementById('galleryModalLink').href = '/Kaya Tene/views/detail-gallery.php?id=' + id;

        const modal = document.getElementById('galleryModal');
        modal.style.display = 'flex';

        // Small timeout to allow display flex to render before animating opacity
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);

        // Prevent body scrolling
        document.body.style.overflow = 'hidden';
    }

    function closeGalleryModal() {
        const modal = document.getElementById('galleryModal');
        modal.classList.remove('show');

        setTimeout(() => {
            modal.style.display = 'none';
            // Restore body scrolling
            document.body.style.overflow = '';
        }, 300); // Matches CSS transition duration
    }

    // Close modal on Escape key press
    document.addEventListener('keydown', function (event) {
        if (event.key === "Escape") {
            const modal = document.getElementById('galleryModal');
            if (modal && modal.classList.contains('show')) {
                closeGalleryModal();
            }
        }
    });
</script>