<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_message'])) {
    if (!isset($conn)) {
        require_once 'config/Connection.php';
    }

    $nama = $conn->real_escape_string($_POST['nama']);
    $email = $conn->real_escape_string($_POST['email']);
    $subjek = $conn->real_escape_string($_POST['subjek']);
    $isi_pesan = $conn->real_escape_string($_POST['isi_pesan']);

    $query = "INSERT INTO Pesan (nama, email, subjek, isi_pesan) VALUES ('$nama', '$email', '$subjek', '$isi_pesan')";

    if ($conn->query($query)) {
        $msg_success = "Pesan Anda berhasil dikirim! Kami akan segera merespons.";
    } else {
        $msg_error = "Terjadi kesalahan: " . $conn->error;
    }
}
?>

<section class="section" id="kirim-pesan" style="background-color: var(--bg-darker); position: relative; overflow: hidden; padding: 100px 0;">
    <!-- Modern Gradient Background Orbs -->
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>

    <div class="container" style="position: relative; z-index: 2;">
        <div class="header-content" style="text-align: center; margin-bottom: 60px;">
            <div class="badge-premium" style="display: inline-block; margin-bottom: 15px;">
                <span>KONTAK & KOLABORASI</span>
            </div>
            <h2 class="section-title" style="font-size: 3.5rem; margin-bottom: 15px;">Mari Mulai <span class="text-gradient">Percakapan</span></h2>
            <p style="max-width: 650px; margin: 0 auto; color: var(--text-muted); font-size: 1.15rem; line-height: 1.7;">
                Tim kami siap mendengarkan ide, pertanyaan, atau masukan Anda. Kirimkan pesan dan mari bangun masa depan NTT yang lebih cerah bersama-sama.
            </p>
        </div>

        <?php if (isset($msg_success)): ?>
            <div class="alert-premium success-state">
                <div class="alert-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div class="alert-text">
                    <strong>Berhasil Terkirim!</strong>
                    <p><?= htmlspecialchars($msg_success) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <?php if (isset($msg_error)): ?>
            <div class="alert-premium error-state">
                <div class="alert-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
                <div class="alert-text">
                    <strong>Gagal Mengirim</strong>
                    <p><?= htmlspecialchars($msg_error) ?></p>
                </div>
            </div>
        <?php endif; ?>

        <div class="premium-contact-card">
            <div class="contact-grid-main">
                <!-- Sidebar Info -->
                <div class="contact-sidebar">
                    <div class="sidebar-inner">
                        <div class="contact-meta">
                            <h3 class="sidebar-title">Informasi Kontak</h3>
                            <p class="sidebar-desc">Isi formulir dan tim kami akan menghubungi Anda kembali dalam waktu kurang dari 24 jam.</p>
                        </div>

                        <div class="contact-methods">
                            <div class="method-item">
                                <div class="method-icon"><i class="fa-solid fa-phone-volume"></i></div>
                                <div class="method-details">
                                    <span>Hubungi Kami</span>
                                    <p>+62 821-xxxx-xxxx</p>
                                </div>
                            </div>
                            <div class="method-item">
                                <div class="method-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
                                <div class="method-details">
                                    <span>Tulis Email</span>
                                    <p>info@kayatene.org</p>
                                </div>
                            </div>
                            <div class="method-item">
                                <div class="method-icon"><i class="fa-solid fa-location-dot"></i></div>
                                <div class="method-details">
                                    <span>Kunjungi Kantor</span>
                                    <p>Kupang, NTT, Indonesia</p>
                                </div>
                            </div>
                        </div>

                        <div class="social-links-premium">
                            <a href="#" class="social-item"><i class="fa-brands fa-instagram"></i></a>
                            <a href="#" class="social-item"><i class="fa-brands fa-facebook"></i></a>
                            <a href="#" class="social-item"><i class="fa-brands fa-whatsapp"></i></a>
                            <a href="#" class="social-item"><i class="fa-brands fa-youtube"></i></a>
                        </div>
                    </div>
                    
                    <!-- Decorative Element -->
                    <div class="sidebar-decoration">
                        <div class="circle-deco"></div>
                    </div>
                </div>

                <!-- Form Area -->
                <div class="contact-main-form">
                    <form action="#kirim-pesan" method="POST" class="modern-form">
                        <div class="form-grid">
                            <div class="input-group">
                                <input type="text" id="nama" name="nama" class="modern-input" required placeholder=" ">
                                <label for="nama" class="modern-label">Nama Lengkap</label>
                                <span class="input-focus-line"></span>
                            </div>
                            <div class="input-group">
                                <input type="email" id="email" name="email" class="modern-input" required placeholder=" ">
                                <label for="email" class="modern-label">Alamat Email</label>
                                <span class="input-focus-line"></span>
                            </div>
                        </div>

                        <div class="input-group">
                            <input type="text" id="subjek" name="subjek" class="modern-input" required placeholder=" ">
                            <label for="subjek" class="modern-label">Subjek Pesan</label>
                            <span class="input-focus-line"></span>
                        </div>

                        <div class="input-group textarea-group">
                            <textarea id="isi_pesan" name="isi_pesan" class="modern-input" rows="6" required placeholder=" "></textarea>
                            <label for="isi_pesan" class="modern-label">Ceritakan detail pesan Anda...</label>
                            <span class="input-focus-line"></span>
                        </div>

                        <div class="form-bottom-actions">
                            <div class="privacy-note">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>Kerahasiaan data Anda terlindungi sepenuhnya oleh kebijakan privasi kami.</span>
                            </div>
                            <button type="submit" name="submit_message" class="premium-submit-btn">
                                <span>Kirim Pesan</span>
                                <div class="btn-icon-box">
                                    <i class="fa-solid fa-paper-plane"></i>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    /* Styling for the section */
    #kirim-pesan {
        background: var(--bg-darker);
    }

    /* Background Animation Orbs */
    .bg-orb {
        position: absolute;
        border-radius: 50%;
        filter: blur(120px);
        z-index: 1;
        opacity: 0.15;
        pointer-events: none;
    }

    .orb-1 {
        width: 400px;
        height: 400px;
        background: var(--primary);
        top: -100px;
        right: -100px;
        animation: rotateOrb 20s infinite linear;
    }

    .orb-2 {
        width: 350px;
        height: 350px;
        background: var(--secondary);
        bottom: -50px;
        left: -50px;
        animation: rotateOrb 25s infinite linear reverse;
    }

    .orb-3 {
        width: 300px;
        height: 300px;
        background: #00f2fe;
        top: 40%;
        left: 30%;
        opacity: 0.05;
    }

    @keyframes rotateOrb {
        from { transform: rotate(0deg) translate(50px) rotate(0deg); }
        to { transform: rotate(360deg) translate(50px) rotate(-360deg); }
    }

    /* Badge Style */
    .badge-premium {
        background: rgba(255, 90, 0, 0.1);
        border: 1px solid rgba(255, 90, 0, 0.2);
        padding: 8px 20px;
        border-radius: 100px;
    }

    .badge-premium span {
        font-size: 0.8rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: var(--primary);
        text-transform: uppercase;
    }

    /* Main Card */
    .premium-contact-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 32px;
        overflow: hidden;
        box-shadow: 0 40px 100px rgba(0, 0, 0, 0.3);
        margin: 0 auto;
        max-width: 1100px;
        animation: cardAppear 1s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    @keyframes cardAppear {
        from { opacity: 0; transform: translateY(40px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .contact-grid-main {
        display: grid;
        grid-template-columns: 380px 1fr;
    }

    /* Sidebar Styling */
    .contact-sidebar {
        background: linear-gradient(165deg, rgba(255, 90, 0, 0.05), rgba(0, 0, 0, 0.2));
        padding: 50px 40px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-right: 1px solid var(--glass-border);
    }

    .sidebar-title {
        font-size: 2rem;
        margin-bottom: 15px;
        color: var(--text-main);
    }

    .sidebar-desc {
        color: var(--text-muted);
        line-height: 1.6;
        margin-bottom: 40px;
    }

    .contact-methods {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .method-item {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .method-icon {
        width: 54px;
        height: 54px;
        background: rgba(255, 90, 0, 0.1);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        border-radius: 16px;
        border: 1px solid rgba(255, 90, 0, 0.1);
        transition: all 0.3s ease;
    }

    .method-item:hover .method-icon {
        background: var(--primary);
        color: white;
        transform: scale(1.1) rotate(-5deg);
        box-shadow: 0 10px 20px rgba(255, 90, 0, 0.3);
    }

    .method-details span {
        display: block;
        font-size: 0.85rem;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 2px;
    }

    .method-details p {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .social-links-premium {
        display: flex;
        gap: 15px;
        margin-top: 50px;
    }

    .social-item {
        width: 46px;
        height: 46px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--glass-border);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: var(--text-main);
        font-size: 1.2rem;
        text-decoration: none !important;
        line-height: 1;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .social-item i {
        display: block;
        line-height: 1;
    }

    .social-item:hover {
        background: var(--primary);
        color: white;
        transform: translateY(-5px);
        border-color: var(--primary);
    }

    .sidebar-decoration {
        position: absolute;
        bottom: -50px;
        right: -50px;
        width: 150px;
        height: 150px;
        opacity: 0.1;
        pointer-events: none;
    }

    .circle-deco {
        width: 100%;
        height: 100%;
        border: 20px solid var(--primary);
        border-radius: 50%;
    }

    /* Form Styling */
    .contact-main-form {
        padding: 60px 50px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .input-group {
        position: relative;
        margin-bottom: 30px;
    }

    .modern-input {
        width: 100%;
        padding: 15px 0;
        background: transparent;
        border: none;
        border-bottom: 2px solid var(--glass-border);
        color: var(--text-main);
        font-size: 1.1rem;
        transition: all 0.4s ease;
        outline: none;
    }

    .textarea-group {
        margin-bottom: 40px;
    }

    textarea.modern-input {
        resize: none;
        min-height: 120px;
    }

    .modern-label {
        position: absolute;
        left: 0;
        top: 15px;
        color: var(--text-muted);
        font-size: 1.1rem;
        pointer-events: none;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .input-focus-line {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background: linear-gradient(to right, var(--primary), var(--secondary));
        transition: width 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    .modern-input:focus ~ .modern-label,
    .modern-input:not(:placeholder-shown) ~ .modern-label {
        transform: translateY(-30px);
        font-size: 0.85rem;
        color: var(--primary);
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .modern-input:focus ~ .input-focus-line {
        width: 100%;
    }

    .modern-input:focus {
        border-bottom-color: transparent;
    }

    /* Action Section */
    .form-bottom-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 30px;
        margin-top: 20px;
    }

    .privacy-note {
        display: flex;
        align-items: center;
        gap: 12px;
        color: var(--text-muted);
        font-size: 0.9rem;
        max-width: 350px;
    }

    .privacy-note i {
        color: var(--primary);
        font-size: 1.1rem;
    }

    .premium-submit-btn {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        color: white;
        border: none;
        padding: 18px 35px;
        border-radius: 100px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 15px;
        transition: all 0.4s cubic-bezier(0.2, 1, 0.2, 1);
        box-shadow: 0 20px 40px rgba(255, 90, 0, 0.25);
        position: relative;
        overflow: hidden;
    }

    .btn-icon-box {
        width: 34px;
        height: 34px;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.4s ease;
    }

    .premium-submit-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 30px 50px rgba(255, 90, 0, 0.4);
    }

    .premium-submit-btn:hover .btn-icon-box {
        transform: translateX(5px) rotate(-15deg);
        background: white;
        color: var(--primary);
    }

    .premium-submit-btn::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.6s ease;
    }

    .premium-submit-btn:hover::after {
        left: 100%;
    }

    /* Alerts */
    .alert-premium {
        max-width: 1100px;
        margin: 0 auto 40px;
        padding: 25px 35px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        backdrop-filter: blur(10px);
        animation: slideDown 0.5s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .success-state {
        background: rgba(0, 184, 148, 0.1);
        border: 1px solid rgba(0, 184, 148, 0.2);
    }

    .error-state {
        background: rgba(255, 118, 117, 0.1);
        border: 1px solid rgba(255, 118, 117, 0.2);
    }

    .alert-icon {
        font-size: 1.8rem;
    }

    .success-state .alert-icon { color: #00b894; }
    .error-state .alert-icon { color: #ff7675; }

    .alert-text strong {
        display: block;
        font-size: 1.1rem;
        margin-bottom: 2px;
        color: var(--text-main);
    }

    .alert-text p {
        color: var(--text-muted);
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 1000px) {
        .contact-grid-main {
            grid-template-columns: 1fr;
        }

        .contact-sidebar {
            border-right: none;
            border-bottom: 1px solid var(--glass-border);
        }

        .sidebar-decoration {
            display: none;
        }
    }

    @media (max-width: 768px) {
        .section-title {
            font-size: 2.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .contact-main-form {
            padding: 40px 25px;
        }

        .form-bottom-actions {
            flex-direction: column-reverse;
            align-items: flex-start;
        }

        .premium-submit-btn {
            width: 100%;
            justify-content: center;
        }
    }

    /* Light Mode Tweak */
    body.light-mode #kirim-pesan {
        background: #f8f9fa;
    }

    body.light-mode .modern-input {
        color: #1a1a1a;
        border-bottom-color: rgba(0,0,0,0.1);
    }

    body.light-mode .modern-label {
        color: #666;
    }

    body.light-mode .premium-contact-card {
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.05);
    }

    body.light-mode .contact-sidebar {
        background: linear-gradient(165deg, rgba(255, 90, 0, 0.02), rgba(255, 255, 255, 0.2));
    }
</style>