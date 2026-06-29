-- =========================================================
--  RENTAL KENDARAAN - Database Schema + Seed Data
--  Jalankan file ini di phpMyAdmin / mysql client untuk
--  membuat database beserta data contoh.
-- =========================================================

CREATE DATABASE IF NOT EXISTS rental_kendaraan
    CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE rental_kendaraan;

-- ---------------------------------------------------------
-- Tabel: users  (akun login Admin & Petugas)
-- ---------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'petugas') NOT NULL DEFAULT 'petugas',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- ---------------------------------------------------------
-- Tabel: kategori_kendaraan
-- ---------------------------------------------------------
CREATE TABLE kategori_kendaraan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL,
    deskripsi VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- ---------------------------------------------------------
-- Tabel: kendaraan
-- Catatan: Mobil & Motor disimpan dalam satu tabel (single table
-- inheritance), dibedakan oleh kolom "jenis". Kolom kapasitas_*
-- bersifat khusus salah satu jenis sehingga dibuat nullable.
-- ---------------------------------------------------------
CREATE TABLE kendaraan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kategori_id INT DEFAULT NULL,
    jenis ENUM('mobil', 'motor') NOT NULL DEFAULT 'mobil',
    kode_kendaraan VARCHAR(20) NOT NULL UNIQUE,
    merk VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    tahun YEAR DEFAULT NULL,
    plat_nomor VARCHAR(20) NOT NULL UNIQUE,
    harga_sewa_harian DECIMAL(12, 2) NOT NULL DEFAULT 0,
    status ENUM('tersedia', 'disewa', 'maintenance') NOT NULL DEFAULT 'tersedia',
    kapasitas_penumpang INT DEFAULT NULL,
    transmisi VARCHAR(20) DEFAULT NULL,
    kapasitas_cc INT DEFAULT NULL,
    tipe_motor VARCHAR(20) DEFAULT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    deskripsi TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_kendaraan_kategori FOREIGN KEY (kategori_id)
        REFERENCES kategori_kendaraan (id) ON DELETE SET NULL
) ENGINE = InnoDB;

-- ---------------------------------------------------------
-- Tabel: customers (pelanggan/penyewa)
-- ---------------------------------------------------------
CREATE TABLE customers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    no_ktp VARCHAR(16) NOT NULL UNIQUE,
    no_hp VARCHAR(20) NOT NULL,
    email VARCHAR(100) DEFAULT NULL,
    alamat TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- ---------------------------------------------------------
-- Tabel: transaksi_sewa
-- Relasi: customer_id -> customers, kendaraan_id -> kendaraan,
--         user_id -> users (petugas yang melayani transaksi)
-- ---------------------------------------------------------
CREATE TABLE transaksi_sewa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_transaksi VARCHAR(30) NOT NULL UNIQUE,
    customer_id INT NOT NULL,
    kendaraan_id INT NOT NULL,
    user_id INT DEFAULT NULL,
    tanggal_sewa DATE NOT NULL,
    tanggal_kembali_rencana DATE NOT NULL,
    tanggal_kembali_aktual DATE DEFAULT NULL,
    lama_sewa INT NOT NULL DEFAULT 1,
    total_biaya DECIMAL(12, 2) NOT NULL DEFAULT 0,
    denda DECIMAL(12, 2) NOT NULL DEFAULT 0,
    status ENUM('booking', 'berjalan', 'selesai', 'batal') NOT NULL DEFAULT 'booking',
    catatan TEXT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_transaksi_customer FOREIGN KEY (customer_id)
        REFERENCES customers (id) ON DELETE RESTRICT,
    CONSTRAINT fk_transaksi_kendaraan FOREIGN KEY (kendaraan_id)
        REFERENCES kendaraan (id) ON DELETE RESTRICT,
    CONSTRAINT fk_transaksi_user FOREIGN KEY (user_id)
        REFERENCES users (id) ON DELETE SET NULL
) ENGINE = InnoDB;

-- =========================================================
--  SEED DATA
-- =========================================================

-- Akun login (password sudah di-hash dengan bcrypt, valid untuk password_verify())
--   Admin    -> username: admin   | password: admin123
--   Petugas  -> username: budi    | password: petugas123
INSERT INTO users (nama, username, password, role) VALUES
('Administrator', 'admin', '$2b$12$YXbXbiUbJsvyuYxj4yBdXOz0.Ed5hyaZqIsgOakHiaIX8OymPIplW', 'admin'),
('Budi Setiawan', 'budi', '$2b$12$evuqYvfqv2mloqB6e2Oob.3Kh0APsigW5eCVJBv8Dcv9i1i/YUWbS', 'petugas');

INSERT INTO kategori_kendaraan (nama_kategori, deskripsi) VALUES
('City Car', 'Mobil kecil lincah untuk perkotaan'),
('MPV', 'Mobil keluarga dengan kapasitas penumpang besar'),
('SUV', 'Mobil tangguh untuk segala medan'),
('Matic', 'Motor matic harian'),
('Sport', 'Motor sport / manual untuk performa tinggi');

INSERT INTO kendaraan
    (kategori_id, jenis, kode_kendaraan, merk, model, tahun, plat_nomor, harga_sewa_harian, status, kapasitas_penumpang, transmisi, kapasitas_cc, tipe_motor, deskripsi)
VALUES
    (1, 'mobil', 'KND20260001', 'Toyota', 'Agya', 2022, 'B 1001 ABC', 250000, 'tersedia', 4, 'manual', NULL, NULL, 'City car hemat BBM, cocok untuk dalam kota.'),
    (2, 'mobil', 'KND20260002', 'Toyota', 'Avanza', 2023, 'B 1002 ABC', 350000, 'tersedia', 7, 'manual', NULL, NULL, 'MPV keluarga, kabin luas, irit.'),
    (2, 'mobil', 'KND20260003', 'Daihatsu', 'Xenia', 2021, 'B 1003 ABC', 320000, 'disewa', 7, 'otomatis', NULL, NULL, 'MPV nyaman dengan transmisi otomatis.'),
    (3, 'mobil', 'KND20260004', 'Honda', 'CR-V', 2023, 'B 1004 ABC', 650000, 'tersedia', 5, 'otomatis', NULL, NULL, 'SUV tangguh dengan fitur lengkap.'),
    (3, 'mobil', 'KND20260005', 'Mitsubishi', 'Pajero Sport', 2022, 'B 1005 ABC', 750000, 'maintenance', 7, 'otomatis', NULL, NULL, 'SUV besar, sedang perawatan rutin.'),
    (4, 'motor', 'KND20260006', 'Honda', 'Vario 125', 2023, 'B 2001 XYZ', 80000, 'tersedia', NULL, NULL, 125, 'matic', 'Motor matic harian, irit dan ringan.'),
    (4, 'motor', 'KND20260007', 'Yamaha', 'NMAX', 2023, 'B 2002 XYZ', 110000, 'tersedia', NULL, NULL, 155, 'matic', 'Motor matic premium, nyaman untuk jarak jauh.'),
    (5, 'motor', 'KND20260008', 'Kawasaki', 'Ninja 250', 2022, 'B 2003 XYZ', 220000, 'disewa', NULL, NULL, 250, 'manual', 'Motor sport untuk pengalaman berkendara maksimal.'),
    (4, 'motor', 'KND20260009', 'Honda', 'Beat', 2021, 'B 2004 XYZ', 70000, 'tersedia', NULL, NULL, 110, 'matic', 'Motor matic ringan dan lincah.');

INSERT INTO customers (nama, no_ktp, no_hp, email, alamat) VALUES
('Andi Pratama', '3171012001990001', '081234567890', 'andi.pratama@email.com', 'Jl. Merdeka No. 10, Jakarta'),
('Siti Rahmawati', '3171012505920002', '081298765432', 'siti.rahma@email.com', 'Jl. Sudirman No. 25, Jakarta'),
('Dedi Kurniawan', '3171011207880003', '081356789012', 'dedi.k@email.com', 'Jl. Gatot Subroto No. 5, Jakarta');

-- Contoh transaksi (kendaraan dengan status 'disewa' di atas dipasangkan dengan transaksi 'berjalan')
INSERT INTO transaksi_sewa
    (kode_transaksi, customer_id, kendaraan_id, user_id, tanggal_sewa, tanggal_kembali_rencana, lama_sewa, total_biaya, denda, status, catatan)
VALUES
    ('TRX202606200001', 1, 3, 2, '2026-06-20', '2026-06-25', 5, 1600000, 0, 'berjalan', 'Sewa untuk acara keluarga.'),
    ('TRX202606210002', 2, 8, 2, '2026-06-21', '2026-06-23', 2, 440000, 0, 'berjalan', NULL),
    ('TRX202606150003', 3, 6, 2, '2026-06-15', '2026-06-17', 2, 160000, 0, 'selesai', 'Sudah dikembalikan tepat waktu.');

UPDATE transaksi_sewa SET tanggal_kembali_aktual = '2026-06-17' WHERE kode_transaksi = 'TRX202606150003';
