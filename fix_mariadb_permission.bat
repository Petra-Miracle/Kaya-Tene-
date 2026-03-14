@echo off
echo ========================================
echo FIX MARIADB PERMISSION - KAYA TENE
echo ========================================
echo.

REM Cari lokasi MySQL XAMPP
set MYSQL_BIN=C:\xampp\mysql\bin\mysql.exe

if not exist "%MYSQL_BIN%" (
    set MYSQL_BIN=C:\xamppp\mysql\bin\mysql.exe
)

if not exist "%MYSQL_BIN%" (
    echo ERROR: MySQL tidak ditemukan!
    echo Lokasi dicoba: C:\xampp\mysql\bin\mysql.exe
    echo                C:\xamppp\mysql\bin\mysql.exe
    echo.
    echo Silakan edit file ini dan sesuaikan path MySQL Anda.
    pause
    exit /b 1
)

echo [1/4] MySQL ditemukan di: %MYSQL_BIN%
echo [2/4] Menghapus user root yang bermasalah...

"%MYSQL_BIN%" -u root -e "DROP USER IF EXISTS 'root'@'localhost';" 2>nul

echo [3/4] Membuat ulang user root dengan permission yang benar...

"%MYSQL_BIN%" -u root -e "CREATE USER IF NOT EXISTS 'root'@'localhost' IDENTIFIED BY '';"
"%MYSQL_BIN%" -u root -e "CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY '';"
"%MYSQL_BIN%" -u root -e "CREATE USER IF NOT EXISTS 'root'@'::1' IDENTIFIED BY '';"

echo [4/4] Memberikan semua privileges...

"%MYSQL_BIN%" -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;"
"%MYSQL_BIN%" -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;"
"%MYSQL_BIN%" -u root -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'::1' WITH GRANT OPTION;"
"%MYSQL_BIN%" -u root -e "FLUSH PRIVILEGES;"

echo.
echo ========================================
echo SELESAI!
echo ========================================
echo.
echo User root sekarang bisa akses dari:
"%MYSQL_BIN%" -u root -e "SELECT User, Host FROM mysql.user WHERE User='root';"
echo.
echo Silakan refresh browser Anda!
echo.
pause
