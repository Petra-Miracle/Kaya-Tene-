-- ============================================
-- FIX MYSQL/MARIADB PERMISSION ERROR
-- ============================================
-- Jalankan script ini di phpMyAdmin untuk memperbaiki
-- error "Host is not allowed to connect"
-- ============================================

-- Cara 1: Update user root yang sudah ada
UPDATE mysql.user SET Host='%' WHERE User='root' AND Host='localhost';

-- Cara 2: Buat user root baru dengan akses dari semua host (backup)
CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY '';
CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '';

-- Berikan semua privilege
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;

-- Flush privileges agar perubahan diterapkan
FLUSH PRIVILEGES;

-- Tampilkan user yang ada untuk verifikasi
SELECT User, Host FROM mysql.user WHERE User='root';
