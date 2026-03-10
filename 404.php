<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Halaman Tidak Ditemukan - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .error-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 100px 20px;
            text-align: center;
        }

        .error-card {
            max-width: 600px;
            padding: 60px 40px;
            border-radius: 30px;
            border-top: 4px solid var(--primary);
        }

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 20px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: pulseLogo 3s infinite;
        }

        .error-title {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: var(--text-main);
        }

        .error-desc {
            color: var(--text-muted);
            font-size: 1.2rem;
            margin-bottom: 40px;
            line-height: 1.8;
        }
    </style>
</head>

<body>
    <?php require_once 'partials/Loader.php'; ?>
    <?php include 'partials/Navbar.php'; ?>

    <div class="error-container">
        <div class="error-card glass animate-on-scroll">
            <div class="error-code">404</div>
            <h1 class="error-title">Ups! Halaman Hilang...</h1>
            <p class="error-desc">
                Maaf, halaman yang Anda cari mungkin telah ditarik, diubah namanya,
                atau memang tidak pernah ada. Mari kita kembali ke jalur yang benar.
            </p>
            <a href="index.php" class="btn btn-primary"
                style="padding: 15px 40px; font-size: 1.1rem; display: inline-flex; align-items: center;">
                <i class="fa-solid fa-house" style="margin-right: 10px;"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

</body>

</html>