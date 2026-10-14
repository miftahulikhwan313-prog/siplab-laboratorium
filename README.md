# SIPLAB — Sistem Inventaris dan Peminjaman Barang Laboratorium

SIPLAB adalah aplikasi web sederhana untuk mengelola inventaris dan peminjaman barang laboratorium. Aplikasi ini dibangun sebagai proyek praktikum untuk mempelajari kolaborasi tim menggunakan Git, GitHub, Docker, PHP, dan MariaDB.

Proyek ini memiliki pola yang mirip dengan sistem perpustakaan, tetapi konteksnya diganti menjadi laboratorium:

| Konsep Perpustakaan | Konsep SIPLAB |
|---|---|
| Buku | Barang Laboratorium |
| ISBN | Kode Barang |
| Judul Buku | Nama Barang |
| Anggota | Peminjam |
| Peminjaman Buku | Peminjaman Barang |
| Pengembalian Buku | Pengembalian Barang |
| Stok Buku | Stok Barang |
| Katalog Publik | Katalog Barang Tersedia |

---

## Tujuan Proyek

Proyek ini dibuat untuk memenuhi tugas praktikum dengan fokus pada:

1. Kolaborasi tim menggunakan Git dan GitHub.
2. Penggunaan feature branch.
3. Penggunaan pull request.
4. Code review oleh QA.
5. Penyelesaian merge conflict.
6. Penggunaan Docker Compose untuk menyeragamkan lingkungan pengembangan.
7. Penggunaan GitHub Actions sebagai Continuous Integration sederhana.

---

## Tim Pengembang

| No | Nama | Peran | GitHub |
|---|---|---|---|
| 1 | Miftahul Ihwan | Project Manager | [miftahulikhwan313-prog-pm] |
| 2 | Muhammad Zaky Rahman | Backend Developer | [muzarahmanDev-backend] |
| 3 | Fawwaz Muhammad Widiyanto | Frontend Developer | [fawas29-frontend] |
| 4 | Muhammad Huwaiza Rafi | QA / Reviewer | [HuwaizaRafi-qa] |

---

## Teknologi yang Digunakan

| Lapisan | Teknologi |
|---|---|
| Web Server | Apache |
| Bahasa Pemrograman | PHP 8.2 |
| Database | MariaDB 10.6 |
| Containerization | Docker & Docker Compose |
| Version Control | Git |
| Hosting Repository | GitHub |
| Continuous Integration | GitHub Actions |
| Database GUI | phpMyAdmin |

---

## Fitur MVP

| No | Fitur | Deskripsi |
|---|---|---|
| 1 | CRUD Barang | Tambah, lihat, ubah, dan hapus data barang laboratorium |
| 2 | CRUD Peminjam | Tambah, lihat, ubah, dan hapus data peminjam |
| 3 | Peminjaman | Peminjam meminjam barang dan stok barang berkurang |
| 4 | Pengembalian | Peminjam mengembalikan barang dan stok barang bertambah |
| 5 | Katalog Publik | Menampilkan barang yang masih tersedia |
| 6 | CI GitHub Actions | Pemeriksaan sintaks PHP otomatis |

---

## Gambaran Database

Nama database:

```text
LabInventoryDB
```

Proyek ini sudah menyelesaikan fitur backend dan frontend MVP.
proyek ini selesai 