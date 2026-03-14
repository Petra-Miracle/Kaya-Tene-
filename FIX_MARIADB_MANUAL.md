# ========================================
# PANDUAN MANUAL FIX MARIADB PERMISSION
# ========================================

## MASALAH:
Host 'localhost' is not allowed to connect to this MariaDB server

## SOLUSI LENGKAP (Ikuti step by step):

### STEP 1: Stop MySQL
1. Buka **XAMPP Control Panel**
2. Klik tombol **Stop** di baris MySQL
3. Tunggu sampai benar-benar stop (lampu merah)

### STEP 2: Edit Konfigurasi MySQL
1. Di XAMPP Control Panel, klik **Config** (di baris MySQL)
2. Pilih **my.ini**
3. Cari section `[mysqld]` (biasanya di baris 10-20)
4. Tambahkan baris ini TEPAT di bawah `[mysqld]`:
   ```
   skip-grant-tables
   ```
5. **Save** file (Ctrl+S)
6. **Tutup** Notepad

### STEP 3: Start MySQL dengan Skip Grant Tables
1. Kembali ke XAMPP Control Panel
2. Klik **Start** di baris MySQL
3. MySQL akan start tanpa permission check

### STEP 4: Fix Permission via phpMyAdmin
1. Buka browser: http://localhost/phpmyadmin
2. Seharusnya bisa masuk tanpa error
3. Klik tab **SQL** di atas
4. Copy-paste script ini:

```sql
-- Hapus user root yang bermasalah
DROP USER IF EXISTS 'root'@'localhost';

-- Buat ulang user root
CREATE USER 'root'@'localhost' IDENTIFIED BY '';
CREATE USER IF NOT EXISTS 'root'@'127.0.0.1' IDENTIFIED BY '';
CREATE USER IF NOT EXISTS 'root'@'::1' IDENTIFIED BY '';

-- Berikan semua privileges
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;
GRANT ALL PRIVILEGES ON *.* TO 'root'@'::1' WITH GRANT OPTION;

-- Terapkan perubahan
FLUSH PRIVILEGES;

-- Cek hasilnya
SELECT User, Host FROM mysql.user WHERE User='root';
```

5. Klik **Go** untuk execute
6. Lihat hasilnya - seharusnya muncul 3 user root

### STEP 5: Kembalikan Konfigurasi Normal
1. **SANGAT PENTING!** Kembali ke XAMPP Control Panel
2. Klik **Config** → **my.ini**
3. **HAPUS** atau **COMMENT** baris `skip-grant-tables` yang tadi ditambahkan:
   ```
   # skip-grant-tables
   ```
   ATAU hapus baris tersebut
4. **Save** file
5. **Tutup** Notepad

### STEP 6: Restart MySQL Normal
1. Di XAMPP Control Panel, klik **Stop** MySQL
2. Tunggu benar-benar stop
3. Klik **Start** MySQL lagi
4. MySQL sekarang jalan dengan permission yang benar

### STEP 7: Test Website
1. Buka browser
2. Akses: http://localhost/Kaya%20Tene/index.php
3. Seharusnya **TIDAK ADA ERROR** lagi! ✅

---

## ALTERNATIF: Reinstall MySQL User

Jika cara di atas terlalu ribet, coba ini:

### Via MySQL Command Line:
1. Buka **XAMPP Control Panel**
2. Klik **Shell** (tombol di kanan atas)
3. Ketik command ini:

```bash
mysql -u root --skip-password mysql
```

4. Jika berhasil masuk, ketik:

```sql
DROP USER IF EXISTS 'root'@'localhost';
FLUSH PRIVILEGES;
CREATE USER 'root'@'localhost' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
EXIT;
```

5. Restart MySQL di XAMPP Control Panel
6. Test website

---

## JIKA MASIH ERROR:

Kemungkinan database `kaya_tene` tidak ada. Cek ini:

1. Buka phpMyAdmin (setelah permission fix)
2. Lihat daftar database di kiri
3. Jika tidak ada `kaya_tene`, buat database baru:
   - Klik **New**
   - Nama: `kaya_tene`
   - Collation: `utf8mb4_general_ci`
   - Klik **Create**

4. Import struktur database jika ada file SQL

---

## CATATAN PENTING:

- ⚠️ **JANGAN LUPA** hapus `skip-grant-tables` setelah fix!
- ⚠️ Kalau lupa, semua orang bisa akses database tanpa password!
- ✅ Setelah fix, security kembali normal

---

Dibuat: 14 Maret 2026
Untuk: Yayasan Kaya Tene Website
