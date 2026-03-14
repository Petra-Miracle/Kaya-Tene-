<!DOCTYPE html>
<html>
<head>
    <title>Test Performa - Kaya Tene</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .metric {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            margin: 10px 0;
            background: #f9f9f9;
            border-radius: 5px;
            border-left: 4px solid #FF5A00;
        }
        .good { border-left-color: #4CAF50; }
        .warning { border-left-color: #FFC107; }
        .bad { border-left-color: #F44336; }
        h1 { color: #FF5A00; }
        h2 { color: #333; margin-top: 0; }
        .value { font-weight: bold; font-size: 1.2em; }
        .btn {
            background: #FF5A00;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover { background: #CC4400; }
        #results { display: none; }
    </style>
</head>
<body>
    <h1>🚀 Test Performa Website Kaya Tene</h1>
    
    <div class="card">
        <h2>Status Koneksi Database</h2>
        <?php
        $start = microtime(true);
        require_once 'config/Connection.php';
        $db_time = round((microtime(true) - $start) * 1000, 2);
        
        $status_class = $db_time < 50 ? 'good' : ($db_time < 100 ? 'warning' : 'bad');
        ?>
        <div class="metric <?php echo $status_class; ?>">
            <span>Waktu Koneksi Database:</span>
            <span class="value"><?php echo $db_time; ?> ms</span>
        </div>
        <?php if ($db_time < 50): ?>
            <p style="color: #4CAF50;">✓ Koneksi database sangat cepat!</p>
        <?php elseif ($db_time < 100): ?>
            <p style="color: #FFC107;">⚠ Koneksi database cukup cepat, tapi bisa lebih baik.</p>
        <?php else: ?>
            <p style="color: #F44336;">✗ Koneksi database lambat! Periksa MySQL di XAMPP.</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Test Query Database</h2>
        <?php
        // Test Carousel Query
        $start = microtime(true);
        $carousel_sql = "SELECT id, judul, deskripsi, gambar, btn_text, btn_link FROM Carousel ORDER BY id ASC";
        $carousel_result = $conn->query($carousel_sql);
        $carousel_time = round((microtime(true) - $start) * 1000, 2);
        $carousel_class = $carousel_time < 10 ? 'good' : ($carousel_time < 50 ? 'warning' : 'bad');
        
        // Test Berita Query
        $start = microtime(true);
        $berita_sql = "SELECT id, judul, isi, gambar, tanggal FROM Berita ORDER BY tanggal DESC LIMIT 3";
        $berita_result = $conn->query($berita_sql);
        $berita_time = round((microtime(true) - $start) * 1000, 2);
        $berita_class = $berita_time < 10 ? 'good' : ($berita_time < 50 ? 'warning' : 'bad');
        
        // Test Galeri Query
        $start = microtime(true);
        $galeri_sql = "SELECT id, judul, deskripsi, gambar, tanggal FROM Galeri ORDER BY id DESC LIMIT 6";
        $galeri_result = $conn->query($galeri_sql);
        $galeri_time = round((microtime(true) - $start) * 1000, 2);
        $galeri_class = $galeri_time < 10 ? 'good' : ($galeri_time < 50 ? 'warning' : 'bad');
        
        $total_query_time = $carousel_time + $berita_time + $galeri_time;
        $total_class = $total_query_time < 30 ? 'good' : ($total_query_time < 100 ? 'warning' : 'bad');
        ?>
        
        <div class="metric <?php echo $carousel_class; ?>">
            <span>Query Carousel:</span>
            <span class="value"><?php echo $carousel_time; ?> ms</span>
        </div>
        <div class="metric <?php echo $berita_class; ?>">
            <span>Query Berita (3 items):</span>
            <span class="value"><?php echo $berita_time; ?> ms</span>
        </div>
        <div class="metric <?php echo $galeri_class; ?>">
            <span>Query Galeri (6 items):</span>
            <span class="value"><?php echo $galeri_time; ?> ms</span>
        </div>
        <div class="metric <?php echo $total_class; ?>">
            <span><strong>Total Query Time:</strong></span>
            <span class="value"><?php echo $total_query_time; ?> ms</span>
        </div>
        
        <?php if ($total_query_time < 30): ?>
            <p style="color: #4CAF50;">✓ Query sangat optimal! Index bekerja dengan baik.</p>
        <?php elseif ($total_query_time < 100): ?>
            <p style="color: #FFC107;">⚠ Query cukup cepat. Pertimbangkan untuk menjalankan optimize_database.sql</p>
        <?php else: ?>
            <p style="color: #F44336;">✗ Query lambat! Jalankan optimize_database.sql SEGERA!</p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Informasi Database</h2>
        <?php
        // Get table sizes
        $tables = ['Carousel', 'Berita', 'Galeri'];
        foreach ($tables as $table) {
            $result = $conn->query("SELECT COUNT(*) as count FROM $table");
            $row = $result->fetch_assoc();
            echo "<div class='metric good'>";
            echo "<span>Jumlah data $table:</span>";
            echo "<span class='value'>{$row['count']} rows</span>";
            echo "</div>";
        }
        ?>
    </div>

    <div class="card">
        <h2>Rekomendasi</h2>
        <ul style="line-height: 2;">
            <?php if ($db_time > 50): ?>
                <li>🔴 <strong>Restart MySQL</strong> di XAMPP Control Panel</li>
            <?php endif; ?>
            
            <?php if ($total_query_time > 30): ?>
                <li>🔴 <strong>Jalankan optimize_database.sql</strong> di phpMyAdmin</li>
            <?php endif; ?>
            
            <li>✓ Clear browser cache (Ctrl + Shift + Delete)</li>
            <li>✓ Gunakan Chrome DevTools (F12) untuk monitoring Network</li>
            <li>✓ Compress gambar di folder uploads/ (target < 300KB per file)</li>
            
            <?php if ($db_time < 50 && $total_query_time < 30): ?>
                <li style="color: #4CAF50;">🎉 <strong>Semua optimasi sudah berjalan dengan baik!</strong></li>
            <?php endif; ?>
        </ul>
    </div>

    <div class="card">
        <h2>Quick Actions</h2>
        <a href="index.php" class="btn">🏠 Kembali ke Beranda</a>
        <a href="http://localhost/phpmyadmin" target="_blank" class="btn" style="background: #0066CC; margin-left: 10px;">📊 Buka phpMyAdmin</a>
    </div>

    <script>
        // Measure page load time
        window.addEventListener('load', function() {
            const loadTime = performance.timing.loadEventEnd - performance.timing.navigationStart;
            const domTime = performance.timing.domContentLoadedEventEnd - performance.timing.navigationStart;
            
            console.log('Page Load Time:', loadTime, 'ms');
            console.log('DOM Ready Time:', domTime, 'ms');
        });
    </script>
</body>
</html>
