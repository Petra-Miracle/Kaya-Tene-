# ========================================
# STEP 2: FIX PERMISSION (Jalankan setelah MySQL restart)
# ========================================

Write-Host ""
Write-Host "=== FIX MARIADB PERMISSION - STEP 2 ===" -ForegroundColor Cyan
Write-Host ""

$mysqlBin = "C:\xamppp\mysql\bin\mysql.exe"

if (-not (Test-Path $mysqlBin)) {
    Write-Host "ERROR: MySQL tidak ditemukan di $mysqlBin" -ForegroundColor Red
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}

Write-Host "[4/6] MySQL ditemukan, memperbaiki permission..." -ForegroundColor Yellow
Write-Host ""

# Fix permission
$commands = @"
DROP USER IF EXISTS 'root'@'localhost';
CREATE USER 'root'@'localhost' IDENTIFIED BY '';
CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY '';
CREATE USER IF NOT EXISTS 'root'@'::1' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'::1' WITH GRANT OPTION;
FLUSH PRIVILEGES;
"@

& $mysqlBin -u root -e $commands

if ($LASTEXITCODE -eq 0) {
    Write-Host "[SUCCESS] Permission berhasil diperbaiki!" -ForegroundColor Green
    Write-Host ""
    
    # Verify
    Write-Host "Verifikasi user root:" -ForegroundColor Yellow
    & $mysqlBin -u root -e "SELECT User, Host FROM mysql.user WHERE User='root';"
    Write-Host ""
    
    Write-Host "[5/6] Mengembalikan konfigurasi normal..." -ForegroundColor Yellow
    
    # Remove skip-grant-tables
    $myini = Get-Content "C:\xamppp\mysql\bin\my.ini"
    $newContent = $myini | Where-Object { $_ -notmatch '^skip-grant-tables' }
    $newContent | Set-Content "C:\xamppp\mysql\bin\my.ini" -Force
    
    Write-Host "[6/6] skip-grant-tables dihapus dari my.ini" -ForegroundColor Green
    Write-Host ""
    Write-Host "========================================" -ForegroundColor Green
    Write-Host "SELESAI! Langkah terakhir:" -ForegroundColor Green
    Write-Host "========================================" -ForegroundColor Green
    Write-Host ""
    Write-Host "1. Buka XAMPP Control Panel" -ForegroundColor White
    Write-Host "2. STOP MySQL" -ForegroundColor White
    Write-Host "3. START MySQL lagi" -ForegroundColor White
    Write-Host "4. Refresh browser untuk test website" -ForegroundColor White
    Write-Host ""
    Write-Host "Website seharusnya sudah normal!" -ForegroundColor Cyan
    
} else {
    Write-Host "[ERROR] Gagal memperbaiki permission" -ForegroundColor Red
    Write-Host "Pastikan MySQL sudah di-restart dengan skip-grant-tables aktif" -ForegroundColor Yellow
}

Write-Host ""
Read-Host "Tekan Enter untuk keluar"
