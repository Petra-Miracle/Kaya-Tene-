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

<section class="section" id="kirim-pesan"
    style="background-color: var(--bg-main); background-image: radial-gradient(circle at top right, rgba(255, 90, 0, 0.05), transparent 50%), radial-gradient(circle at bottom left, rgba(255, 183, 3, 0.05), transparent 50%); position: relative; overflow: hidden;">
    <!-- Abstract Shapes for Premium Feel -->
    <div
        style="position: absolute; top: -50px; left: -50px; width: 200px; height: 200px; background: rgba(255, 90, 0, 0.2); filter: blur(80px); border-radius: 50%; z-index: 0;">
    </div>
    <div
        style="position: absolute; bottom: -50px; right: -50px; width: 250px; height: 250px; background: rgba(255, 183, 3, 0.2); filter: blur(100px); border-radius: 50%; z-index: 0;">
    </div>

    <div class="container" style="position: relative; z-index: 1;">
        <div style="text-align: center; margin-bottom: 50px;">
            <span
                style="color: var(--primary); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; font-size: 0.9rem; display: block; margin-bottom: 10px;">Tetap
                Terhubung</span>
            <h2 class="section-title">Kirim <span class="text-gradient">Pesan</span> ke Kami</h2>
            <div style="max-width: 600px; margin: 0 auto; color: var(--text-muted); font-size: 1.1rem;">
                Punya pertanyaan, saran, tawaran program kemitraan atau ingin berkontribusi? Jangan ragu untuk
                mengirimkan pesan langsung kepada tim kami.
            </div>
        </div>

        <?php if (isset($msg_success)): ?>
            <div class="alert glass alert-animated-success" style="max-width: 1000px; margin: 0 auto 30px;">
                <p><i class="fa-solid fa-check-circle" style="margin-right: 8px;"></i> <?= htmlspecialchars($msg_success) ?>
                </p>
            </div>
        <?php endif; ?>

        <?php if (isset($msg_error)): ?>
            <div class="alert glass alert-animated-error" style="max-width: 1000px; margin: 0 auto 30px;">
                <p><i class="fa-solid fa-triangle-exclamation" style="margin-right: 8px;"></i>
                    <?= htmlspecialchars($msg_error) ?></p>
            </div>
        <?php endif; ?>

        <div class="contact-grid">
            <!-- Left Side: Visual / Info -->
            <div class="contact-info glass">
                <div class="contact-info-content">
                    <h3 style="font-size: 1.8rem; margin-bottom: 20px; color: var(--text-main);">Mari Bangun Sinergi
                    </h3>
                    <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 30px; font-size: 1.05rem;">
                        Komunikasi yang baik adalah kunci kolaborasi. Kami di Yayasan Kaya Tene selalu terbuka untuk
                        berdiskusi tentang peluang untuk membantu masyarakat pedesaan NTT dan memajukan kawasan timur
                        Indonesia.
                    </p>

                    <ul class="contact-list" style="list-style: none; padding: 0;">
                        <li>
                            <div class="icon-box"><i class="fa-solid fa-location-dot"></i></div>
                            <div>
                                <h4 style="color: var(--text-main); font-size: 1.1rem; margin-bottom: 5px;">Kantor Pusat
                                </h4>
                                <p style="color: var(--text-muted); font-size: 0.95rem;">Kupang, Nusa Tenggara Timur
                                    (NTT), Indonesia
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="icon-box" style="background: rgba(37, 211, 102, 0.1); color: #25D366;"><i
                                    class="fa-brands fa-whatsapp"></i></div>
                            <div>
                                <h4 style="color: var(--text-main); font-size: 1.1rem; margin-bottom: 5px;">Saluran
                                    Cepat</h4>
                                <p style="color: var(--text-muted); font-size: 0.95rem;">Respon dalam 24 Jam</p>
                            </div>
                        </li>
                        <li>
                            <div class="icon-box" style="background: rgba(255, 183, 3, 0.1); color: var(--secondary);">
                                <i class="fa-solid fa-envelope-open-text"></i>
                            </div>
                            <div>
                                <h4 style="color: var(--text-main); font-size: 1.1rem; margin-bottom: 5px;">
                                    Aksesibilitas</h4>
                                <p style="color: var(--text-muted); font-size: 0.95rem;">Pesan ini akan otomatis
                                    diteruskan ke tim
                                    pengurus utama.</p>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Premium Background Pattern Graphic for the left side -->
                <div class="contact-illustration">
                    <i class="fa-regular fa-paper-plane"></i>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="contact-form glass">
                <form action="#kirim-pesan" method="POST" class="message-form">
                    <div class="form-row">
                        <div class="input-container">
                            <i class="fa-regular fa-user input-icon"></i>
                            <input type="text" id="nama" name="nama" class="form-input" required placeholder=" ">
                            <label for="nama" class="form-label">Nama Lengkap</label>
                        </div>
                        <div class="input-container">
                            <i class="fa-regular fa-envelope input-icon"></i>
                            <input type="email" id="email" name="email" class="form-input" required placeholder=" ">
                            <label for="email" class="form-label">Alamat Email</label>
                        </div>
                    </div>

                    <div class="input-container">
                        <i class="fa-regular fa-lightbulb input-icon"></i>
                        <input type="text" id="subjek" name="subjek" class="form-input" required placeholder=" ">
                        <label for="subjek" class="form-label">Subjek Tanyaan / Pertanyaan</label>
                    </div>

                    <div class="input-container textarea-container">
                        <i class="fa-regular fa-comment-dots input-icon"></i>
                        <textarea id="isi_pesan" name="isi_pesan" class="form-input" rows="5" required
                            placeholder=" "></textarea>
                        <label for="isi_pesan" class="form-label">Detail Pesan Anda</label>
                    </div>

                    <div class="form-footer">
                        <p style="color: var(--text-muted); font-size: 0.85rem; max-width: 60%;">Data yang Anda berikan
                            aman bersama kami dan digunakan semata-mata untuk membalas pesan Anda.</p>
                        <button type="submit" name="submit_message" class="btn btn-primary"
                            style="padding: 16px 30px; font-size: 1.1rem; border-radius: 50px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); column-gap: 12px; border: none; cursor: pointer; position: relative; overflow: hidden; box-shadow: 0 10px 20px rgba(255, 90, 0, 0.2);">
                            <span style="position: relative; z-index: 1;">Kirim Pesan</span>
                            <i class="fa-solid fa-paper-plane" style="position: relative; z-index: 1;"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
    /* Premium Contact Layout Grid */
    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1.5fr;
        gap: 0;
        max-width: 1000px;
        margin: 0 auto;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.2);
    }

    .contact-info {
        background: var(--bg-card) !important;
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        padding: 50px 40px;
        position: relative;
        overflow: hidden;
        border-right: 1px solid var(--glass-border);
        border-radius: 24px 0 0 24px !important;
    }

    body.light-mode .contact-info {
        background: rgba(255, 255, 255, 0.8) !important;
    }

    .contact-info-content {
        position: relative;
        z-index: 2;
    }

    .contact-list li {
        display: flex;
        align-items: center;
        margin-bottom: 25px;
        gap: 20px;
    }

    .icon-box {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        background: rgba(255, 90, 0, 0.1);
        color: var(--primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .contact-illustration {
        position: absolute;
        bottom: -50px;
        right: -30px;
        font-size: 15rem;
        color: rgba(0, 0, 0, 0.03);
        transform: rotate(-15deg);
        z-index: 1;
        pointer-events: none;
    }

    /* Form Right Side */
    .contact-form {
        padding: 50px 40px;
        border-radius: 0 24px 24px 0 !important;
        background: var(--bg-card) !important;
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
    }

    body.light-mode .contact-form {
        background: rgba(255, 255, 255, 0.5) !important;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    /* Floating Label Inputs */
    .input-container {
        position: relative;
        margin-bottom: 25px;
        width: 100%;
    }

    .textarea-container {
        margin-bottom: 35px;
    }

    .input-icon {
        position: absolute;
        left: 20px;
        top: 20px;
        color: var(--text-muted);
        font-size: 1.1rem;
        transition: color 0.3s ease;
    }

    .textarea-container .input-icon {
        top: 22px;
    }

    .form-input {
        width: 100%;
        padding: 20px 20px 20px 55px;
        border-radius: 16px;
        background: rgba(0, 0, 0, 0.3);
        border: 2px solid transparent;
        color: var(--text-main);
        font-family: inherit;
        font-size: 1rem;
        transition: all 0.3s ease;
        outline: none;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    body.light-mode .form-input {
        background: rgba(255, 255, 255, 0.6);
        border: 1px solid var(--glass-border);
    }

    textarea.form-input {
        resize: vertical;
        min-height: 140px;
    }

    .form-label {
        position: absolute;
        left: 55px;
        top: 20px;
        color: var(--text-muted);
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        pointer-events: none;
        font-weight: 500;
    }

    /* Floating label magic */
    .form-input:focus,
    .form-input:not(:placeholder-shown) {
        border-color: rgba(255, 90, 0, 0.4);
        background: rgba(0, 0, 0, 0.4);
        box-shadow: 0 0 0 4px rgba(255, 90, 0, 0.05);
    }

    body.light-mode .form-input:focus,
    body.light-mode .form-input:not(:placeholder-shown) {
        background: rgba(255, 255, 255, 0.9);
    }

    .form-input:focus~.form-label,
    .form-input:not(:placeholder-shown)~.form-label {
        top: -10px;
        left: 15px;
        font-size: 0.85rem;
        padding: 0 8px;
        background: var(--bg-card);
        /* Fallback, typically blends with gradient */
        border-radius: 4px;
        color: var(--primary);
    }

    .form-input:focus~.input-icon {
        color: var(--primary);
    }

    /* Footer / Button */
    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-top: 20px;
    }

    .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transform: translateX(-100%);
        transition: transform 0.6s;
    }

    .btn-primary:hover::before {
        transform: translateX(100%);
    }

    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 25px rgba(255, 90, 0, 0.3);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    /* Alerts */
    .alert-animated-success,
    .alert-animated-error {
        animation: slideDownFade 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        padding: 18px 25px;
        border-radius: 16px;
        text-align: center;
    }

    .alert-animated-success {
        background: rgba(37, 211, 102, 0.1);
        border: 1px solid rgba(37, 211, 102, 0.3);
    }

    .alert-animated-success p {
        color: #25D366;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .alert-animated-error {
        background: rgba(231, 76, 60, 0.1);
        border: 1px solid rgba(231, 76, 60, 0.3);
    }

    .alert-animated-error p {
        color: #e74c3c;
        font-weight: 600;
        font-size: 1.1rem;
    }

    @keyframes slideDownFade {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media (max-width: 900px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }

        .contact-info {
            border-radius: 24px 24px 0 0 !important;
            border-right: none;
            border-bottom: 1px solid var(--glass-border);
            padding: 40px 30px;
        }

        .contact-form {
            border-radius: 0 0 24px 24px !important;
            padding: 40px 30px;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .form-footer {
            flex-direction: column-reverse;
            text-align: center;
        }

        .form-footer p {
            max-width: 100%;
            margin-top: 15px;
        }

        .form-footer button {
            width: 100%;
        }
    }
</style>