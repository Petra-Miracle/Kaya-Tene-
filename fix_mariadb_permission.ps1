# ========================================
# FIX MARIADB PERMISSION - PowerShell Script
# ========================================

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "FIX MARIADB PERMISSION - KAYA TENE" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Cari lokasi MySQL
$mysqlPaths = @(
    "C:\xampp\mysql\bin\mysql.exe",
    "C:\xamppp\mysql\bin\mysql.exe"
)

$mysqlBin = $null
foreach ($path in $mysqlPaths) {
    if (Test-Path $path) {
        $mysqlBin = $path
        break
    }
}

if (-not $mysqlBin) {
    Write-Host "ERROR: MySQL tidak ditemukan!" -ForegroundColor Red
    Write-Host "Lokasi yang dicoba:" -ForegroundColor Yellow
    $mysqlPaths | ForEach-Object { Write-Host "  - $_" -ForegroundColor Yellow }
    Write-Host ""
    Read-Host "Tekan Enter untuk keluar"
    exit 1
}

Write-Host "[1/5] MySQL ditemukan: $mysqlBin" -ForegroundColor Green
Write-Host "[2/5] Menghapus user root yang bermasalah..." -ForegroundColor Yellow

# Drop existing problematic users
& $mysqlBin -u root -e "DROP USER IF EXISTS 'root'@'localhost';" 2>$null

Write-Host "[3/5] Membuat ulang user root..." -ForegroundColor Yellow

# Create users
& $mysqlBin -u root -e "CREATE USER IF NOT EXISTS 'root'@'localhost' IDENTIFIED BY '';"
& $mysqlBin -u root -e "CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY '';"
& $mysqlBin -u root -e "CREATE USER IF NOT EXISTS 'root'@'::1' IDENTIFIED BY '';"

Write-Host "[4/5] Memberikan all privileges..." -ForegroundColor Yellow

# Grant privileges
& $mysqlBin -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;"
& $mysqlBin -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;"
& $mysqlBin -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'::1' WITH GRANT OPTION;"
& $mysqlBin -u root -e "FLUSH PRIVILEGES;"

Write-Host "[5/5] Verifikasi user yang dibuat..." -ForegroundColor Yellow
Write-Host ""

# Show created users
& $mysqlBin -u root -e "SELECT User, Host FROM mysql.user WHERE User='root';"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "SELESAI! Permission sudah diperbaiki!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Silakan refresh browser Anda!" -ForegroundColor Cyan
Write-Host ""

Read-Host "Tekan Enter untuk keluar"
