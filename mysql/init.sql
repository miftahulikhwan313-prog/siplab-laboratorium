-- =====================================================
-- SIPLAB - Sistem Inventaris dan Peminjaman Barang Lab
-- Skrip inisialisasi database
-- =====================================================

-- Buat database jika belum ada.
-- Docker Compose sebenarnya sudah membuat database lewat MARIADB_DATABASE,
-- tetapi perintah ini membuat skrip tetap aman dijalankan ulang.
CREATE DATABASE IF NOT EXISTS LabInventoryDB
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE LabInventoryDB;

-- =====================================================
-- Tabel items
-- Menyimpan data barang laboratorium
-- =====================================================
CREATE TABLE IF NOT EXISTS items (
    ItemCode VARCHAR(20) PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Category VARCHAR(100),
    Location VARCHAR(100),
    ItemCondition VARCHAR(50),
    Quantity INT NOT NULL DEFAULT 0,
    CHECK (Quantity >= 0)
) ENGINE = InnoDB;

-- =====================================================
-- Tabel borrowers
-- Menyimpan data peminjam
-- =====================================================
CREATE TABLE IF NOT EXISTS borrowers (
    BorrowerID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Email VARCHAR(255) UNIQUE,
    Phone VARCHAR(20)
) ENGINE = InnoDB;

-- =====================================================
-- Tabel loans
-- Menyimpan transaksi peminjaman barang
-- =====================================================
CREATE TABLE IF NOT EXISTS loans (
    LoanID INT AUTO_INCREMENT PRIMARY KEY,
    BorrowerID INT NOT NULL,
    ItemCode VARCHAR(20) NOT NULL,
    LoanDate DATE NOT NULL,
    ReturnDate DATE NULL,
    FOREIGN KEY (BorrowerID) REFERENCES borrowers(BorrowerID),
    FOREIGN KEY (ItemCode) REFERENCES items(ItemCode)
) ENGINE = InnoDB;

-- =====================================================
-- Data contoh barang
-- =====================================================
INSERT INTO items (ItemCode, Name, Category, Location, ItemCondition, Quantity) VALUES
('LAB-001', 'Mikroskop Digital', 'Elektronik', 'Lab Komputer', 'Baik', 5),
('LAB-002', 'Proyektor', 'Elektronik', 'Lab Multimedia', 'Baik', 3),
('LAB-003', 'Keyboard Mekanik', 'Aksesori', 'Lab Komputer', 'Baik', 10),
('LAB-004', 'Mouse Wireless', 'Aksesori', 'Lab Komputer', 'Baik', 8),
('LAB-005', 'Headset Lab', 'Aksesori', 'Lab Multimedia', 'Baik', 6);

-- =====================================================
-- Data contoh peminjam
-- =====================================================
INSERT INTO borrowers (Name, Email, Phone) VALUES
('Budi Santoso', 'budi@example.com', '081234567890'),
('Siti Aminah', 'siti@example.com', '081298765432'),
('Rahmat Hidayat', 'rahmat@example.com', '081345678901');

-- =====================================================
-- Data contoh peminjaman
-- ReturnDate NULL berarti barang belum dikembalikan
-- =====================================================
INSERT INTO loans (BorrowerID, ItemCode, LoanDate, ReturnDate) VALUES
(1, 'LAB-001', '2026-02-01', '2026-02-10'),
(1, 'LAB-002', '2026-02-15', NULL),
(2, 'LAB-003', '2026-02-18', NULL),
(3, 'LAB-005', '2026-02-20', NULL);
