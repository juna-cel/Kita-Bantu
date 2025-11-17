-- Gunakan database belajar_db (buat dulu kalau belum ada)
CREATE DATABASE IF NOT EXISTS kita_bantu;
USE kita_bantu;

-- Tabel user
CREATE TABLE user (
    id INT(20) AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(200) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(250) NOT NULL,
    tanggal_lahir DATE,
    bio TEXT,
    file_foto VARCHAR(255), -- untuk menyimpan path/URL foto
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabel kategori
CREATE TABLE kategori (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_kategori VARCHAR(100) NOT NULL,
  icon VARCHAR(100) DEFAULT NULL,
  slug VARCHAR(100) DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel post (relasi ke user)
CREATE TABLE post (
    id INT(20) AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(250) NOT NULL,
    nominal DECIMAL(15,2),
    deskripsi TEXT,
    foto VARCHAR(255),
    tanggal DATE,
    status ENUM('aktif','nonaktif','pending') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT(20) NOT NULL,
    CONSTRAINT fk_post_user FOREIGN KEY (created_by) REFERENCES user(id)
) ENGINE=InnoDB;

-- Tabel donasi (relasi ke user)
CREATE TABLE donasi (
    id INT(20) AUTO_INCREMENT PRIMARY KEY,
    nama_donatur VARCHAR(150) NOT NULL,
    jumlah DECIMAL(15,2) NOT NULL,
    tanggal_donasi DATETIME NOT NULL,
    doa TEXT,
    no_wa VARCHAR(20),
    email VARCHAR(150),
    metode_pembayaran VARCHAR(50),
    status ENUM('pending','success','failed') DEFAULT 'pending',
    id_order VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_by INT(20) NOT NULL,
    CONSTRAINT fk_donasi_user FOREIGN KEY (created_by) REFERENCES user(id)
) ENGINE=InnoDB;

-- Tabel master_bank
CREATE TABLE master_bank (
    id INT(20) AUTO_INCREMENT PRIMARY KEY,
    no_rekening VARCHAR(50) NOT NULL,
    nama VARCHAR(150) NOT NULL,
    status ENUM('aktif','nonaktif') DEFAULT 'aktif',
    keterangan TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO kategori (nama_kategori, icon, slug) VALUES
('Bantuan Pendidikan', 'bi bi-mortarboard', 'pendidikan.php'),
('Bantuan Lingkungan', 'bi bi-flower1', 'lingkungan.php'),
('Kegiatan Sosial', 'bi bi-gift', 'sosial.php'),
('Bantuan Infrastruktur', 'bi bi-building', 'infrastruktur.php'),
('Bantuan Modal Usaha', 'bi bi-cash-stack', 'modal-usaha.php'),
('Menolong Hewan', 'bi bi-heart', 'hewan.php'),
('Bantuan Rumah Ibadah', 'bi bi-bank', 'ibadah.php'),
('Bantuan Kesehatan', 'bi bi-hospital', 'kesehatan.php'),
('Zakat', 'bi bi-coin', 'zakat.php'),
('Bencana Alam', 'bi bi-bag-heart', 'bencana.php');


CREATE TABLE riwayat (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  nama_donasi VARCHAR(100),  
  jumlah_donasi DECIMAL(12,2),
  tanggal_donasi DATETIME DEFAULT CURRENT_TIMESTAMP,
  metode_pembayaran VARCHAR(50),
  status VARCHAR(20) DEFAULT 'Berhasil',
  FOREIGN KEY (user_id) REFERENCES user(id)
);
