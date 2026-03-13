CREATE DATABASE kaya_tene;
ALTER DATABASE kaya_tene CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE TABLE Berita(
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    isi TEXT NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP
);
CREATE TABLE Admin(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Galeri (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Pendidikan(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Ekonomi(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Lingkungan_Sosial(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Pesan(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subjek VARCHAR(255) DEFAULT NULL,
    isi_pesan TEXT NOT NULL,
    status ENUM('belum dibaca', 'sudah dibaca') DEFAULT 'belum dibaca',
    tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Carousel(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    btn_text VARCHAR(100) DEFAULT 'Profil kami',
    btn_link VARCHAR(255) DEFAULT '#tentang',
    gambar VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE Programs(
      id INT(11) AUTO_INCREMENT PRIMARY KEY,
      judul VARCHAR(255) NOT NULL,
      deskripsi TEXT,
      gambar VARCHAR(255) NOT NULL,
      tanggal DATE NOT NULL,
      kategori ENUM('Pendidikan', 'Ekonomi', 'Sosial, Budaya & Publikasi', 'Pertanian, Peternakan & Perikanan') NOT NULL,     
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  );

CREATE TABLE Struktur_Organisasi(
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(255) NOT NULL,
    jabatan VARCHAR(255) NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    tanggal DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
