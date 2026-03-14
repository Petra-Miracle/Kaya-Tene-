<?php
// Enable output buffering for faster page load
ob_start();

// Database configuration
$username = 'root';
$password = '';
$database = 'kaya_tene';
$port = 3306;

// Force TCP/IP connection instead of socket (fixes MariaDB localhost issue)
$conn = null;
$last_error = '';

// Try connecting with explicit port (TCP/IP instead of socket)
$conn = @new mysqli('127.0.0.1', $username, $password, $database, $port);

// Check if connection was successful
if ($conn->connect_error) {
    $last_error = $conn->connect_error;
    
    // Display helpful error message
    die("
    <!DOCTYPE html>
    <html>
    <head>
        <title>Database Connection Error</title>
        <style>
            body { font-family: Arial; padding: 50px; background: #f5f5f5; }
            .error-box { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); max-width: 800px; margin: 0 auto; border-left: 5px solid #ff5a00; }
            h2 { color: #ff5a00; margin-top: 0; }
            .code { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; font-family: monospace; overflow-x: auto; }
            ol { line-height: 2; }
            .btn { display: inline-block; margin: 10px 10px 0 0; padding: 12px 24px; background: #ff5a00; color: white; text-decoration: none; border-radius: 5px; }
            .btn:hover { background: #cc4400; }
            .warning { background: #fff3cd; padding: 15px; border-radius: 5px; border-left: 4px solid #ffc107; margin: 20px 0; }
        </style>
    </head>
    <body>
        <div class='error-box'>
            <h2>❌ Database Connection Error</h2>
            <p><strong>Error Message:</strong></p>
            <div class='code'>{$last_error}</div>
            
            <div class='warning'>
                <strong>⚠️ PENTING:</strong> Masalah ini terjadi karena user 'root' tidak memiliki permission untuk connect dari localhost.
            </div>
            
            <h3>🔧 Solusi Cepat (Pilih salah satu):</h3>
            
            <h4>CARA 1: Otomatis via PowerShell</h4>
            <ol>
                <li>Klik kanan pada file <strong>fix_mariadb_permission.ps1</strong></li>
                <li>Pilih <strong>Run with PowerShell</strong></li>
                <li>Ikuti instruksi yang muncul</li>
                <li>Refresh halaman ini</li>
            </ol>
            
            <h4>CARA 2: Manual via phpMyAdmin</h4>
            <ol>
                <li>Buka <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a></li>
                <li>Jika bisa masuk, klik tab <strong>SQL</strong></li>
                <li>Paste script dari file <strong>fix_mysql_permission.sql</strong></li>
                <li>Klik <strong>Go</strong></li>
                <li>Refresh halaman ini</li>
            </ol>
            
            <h4>CARA 3: Via XAMPP Shell</h4>
            <ol>
                <li>Buka <strong>XAMPP Control Panel</strong></li>
                <li>Pastikan MySQL berjalan (lampu hijau)</li>
                <li>Klik tombol <strong>Shell</strong></li>
                <li>Ketik: <code>mysql -u root</code></li>
                <li>Jika masuk, ketik command ini:</li>
            </ol>
            <div class='code'>
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' IDENTIFIED BY '' WITH GRANT OPTION;<br>
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' IDENTIFIED BY '' WITH GRANT OPTION;<br>
FLUSH PRIVILEGES;<br>
EXIT;
            </div>
            
            <h3>📖 Panduan Lengkap:</h3>
            <p>Buka file <strong>FIX_MARIADB_MANUAL.md</strong> untuk panduan step-by-step lengkap dengan screenshot.</p>
            
            <a href='http://localhost/phpmyadmin' target='_blank' class='btn'>Buka phpMyAdmin</a>
            <a href='FIX_MARIADB_MANUAL.md' target='_blank' class='btn' style='background: #28a745;'>Lihat Panduan Lengkap</a>
        </div>
    </body>
    </html>
    ");
}

// Set charset to utf8mb4 for better performance
if (!$conn->set_charset("utf8mb4")) {
    error_log("Error loading character set utf8mb4: " . $conn->error);
}
?>