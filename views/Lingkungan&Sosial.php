<?php
require_once '../config/Connection.php';

$kategori = 'Sosial, Budaya & Publikasi';
$sql = "SELECT judul, deskripsi, gambar, tanggal FROM Programs WHERE kategori = ? ORDER BY tanggal DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kategori);
$stmt->execute();
$program_result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Lingkungan & Sosial - Yayasan Kaya Tene</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .page-header {
            padding: 180px 0 100px;
            background: linear-gradient(rgba(5, 5, 6, 0.7), rgba(5, 5, 6, 0.95)), url('../Public/img/loving-the-planet.jpg') center/cover no-repeat;
            text-align: center;
            position: relative;
        }

        .header-content {
            position: relative;
            z-index: 2;
            animation: fadeInUp 1s cubic-bezier(0.2, 0.8, 0.2, 1);
        }

        .page-title {
            font-size: 4rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 20px;
            text-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
        }

        .page-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.85);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .program-details {
            padding: 80px 0;
            margin-top: -60px;
            position: relative;
            z-index: 10;
        }

        .detail-card {
            padding: 50px;
            border-radius: 30px;
            background: var(--bg-card);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border-top: 5px solid #2ecc71;
            /* Green tone for environment */
            box-shadow: var(--shadow-lg);
        }

        .detail-card h3 {
            font-size: 2.5rem;
            margin-bottom: 30px;
            color: var(--text-main);
        }

        .detail-card p {
            font-size: 1.15rem;
            color: var(--text-muted);
            line-height: 1.8;
            margin-bottom: 25px;
        }

        .initiative-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }

        .initiative-item {
            padding: 30px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--glass-border);
            text-align: center;
            transition: transform 0.4s ease, background 0.4s ease;
        }

        body.light-mode .initiative-item {
            background: rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .initiative-item:hover {
            transform: translateY(-10px);
            background: rgba(46, 204, 113, 0.05);
            /* Highlight using green tone */
            border-color: rgba(46, 204, 113, 0.2);
        }

        .initiative-icon {
            font-size: 2.5rem;
            color: #2ecc71;
            margin-bottom: 20px;
        }

        .initiative-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 15px;
            color: var(--text-main);
        }
    </style>
</head>

<body>
    <?php require_once '../partials/Loader.php'; ?>

    <?php include '../partials/Navbar.php'; ?>

    <header class="page-header">
        <div class="container header-content">
            <h1 class="page-title">Program <span class="text-gradient"
                    style="background: linear-gradient(to right, #2ecc71, #27ae60); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Lingkungan
                    & Sosial</span></h1>
            <p class="page-subtitle">Menumbuhkan kesadaran pelestarian lingkungan hidup dan advokasi empati kemanuasian
                berskala desa.</p>
        </div>
    </header>

    <section class="program-details container">
        <div class="detail-card glass">
            <h3>Alam Lestari, <span class="text-gradient"
                    style="background: linear-gradient(to right, #2ecc71, #27ae60); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Sosial
                    Mandiri</span></h3>
            <p>
                Slogan "Seperti Perahu" atau Kaya Tene tidak cukup di atas air jika samuderanya tercemar. Yayasan secara
                aktif melakukan kampanye dan pelatihan pemanfaatan ruang publik bebas emisi serta pemberdayaan nilai
                sampah menjadi sirkular ekonomi terpadu.
            </p>
            <p>
                Dalam aspek humaniora sosial kemasyarakatan, advokasi ketidakadilan sosial, penanganan gizi spesifik
                berbasis alam lokal, serta pertukaran ide lintas generasi juga kami wadahi sepenuhnya.
            </p>

            <h3 style="margin-top: 60px; font-size: 2rem; border-bottom: 2px solid var(--glass-border); padding-bottom: 20px;">
                Daftar Program Terlaksana
            </h3>

            <div class="initiative-grid">
                <?php
                if ($program_result && $program_result->num_rows > 0) {
                    while ($prog = $program_result->fetch_assoc()) {
                        $img_path = !empty($prog['gambar']) ? '/Kaya Tene/uploads/' . htmlspecialchars($prog['gambar']) : 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80';
                        $tanggal = date('d F Y', strtotime($prog['tanggal']));
                        echo '
                        <div class="initiative-item" style="padding: 0; overflow: hidden; display: flex; flex-direction: column;">
                            <img src="' . $img_path . '" alt="Program Image" style="width: 100%; height: 200px; object-fit: cover; border-bottom: 1px solid var(--glass-border);" onerror="this.src=\'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80\'">
                            <div style="padding: 30px; flex: 1; display: flex; flex-direction: column; text-align: left;">
                                <div style="color: #2ecc71; font-size: 0.85rem; font-weight: 600; margin-bottom: 10px;"><i class="fa-regular fa-calendar" style="margin-right: 5px;"></i> ' . $tanggal . '</div>
                                <div class="initiative-title" style="text-align: left;">' . htmlspecialchars($prog['judul']) . '</div>
                                <p style="font-size: 1rem; margin-bottom: 0; line-height: 1.6;">' . nl2br(htmlspecialchars($prog['deskripsi'])) . '</p>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<div style="grid-column: 1/-1; text-align: center; padding: 40px; color: var(--text-muted); font-size: 1.1rem; border: 1px dashed var(--glass-border); border-radius: 20px;">Belum ada program yang ditambahkan pada kategori ini.</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <?php include '../partials/Footer.php'; ?>

</body>

</html>