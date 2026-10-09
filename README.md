# SIKAT PTK - MAN 2 BANTUL
### Sistem Informasi Peningkatan Kompetensi Pendidik dan Tenaga Kependidikan

Aplikasi sistem informasi pelaporan kegiatan dan peningkatan kompetensi Pendidik & Tenaga Kependidikan (PTK) MAN 2 Bantul berbasis **Laravel 11, Tailwind CSS, Alpine.js, dan MySQL**.

---

## ✨ Fitur Utama

1. **Open Form (Formulir Terbuka Tanpa Ribet Login)**
   - Guru dan staf tidak perlu repot membuat akun untuk melapor.
   - Dilengkapi **Searchable Autocomplete PTK**: Terhubung dengan database kepegawaian 67 GTK MAN 2 Bantul. Cukup ketik beberapa huruf nama atau NIP, identitas (Nama, NIP, Jenis Kelamin, No HP) otomatis terisi.
   - Pilihan fleksibel bagi GTK baru yang belum tercatat untuk input manual.

2. **Multi-Item Upload (1 s/d Maksimal 10 Kegiatan per Pengiriman)**
   - Setiap pengiriman form dapat menampung hingga **10 kegiatan** sekaligus.
   - Dynamic item controller (Tambah Kegiatan / Hapus Kegiatan).
   - Validasi berkas bukti sertifikat mandiri per kegiatan (Format PDF/JPG/PNG, batas ukuran file hingga 10 MB per berkas).
   - Indikator ukuran file dan preview berkas terpilih secara langsung.

3. **Tanda Terima Digital & Bukti Pengajuan (Printable Receipt)**
   - Setiap pengiriman mendapatkan kode pengajuan unik (contoh: `SIKAT-20261009-XXXXX`).
   - Tampilan tanda terima rapi dan otomatis diformat untuk cetak (tombol *Cetak Bukti Tanda Terima*).

4. **Rekapitulasi Terbuka (Public Recap)**
   - Akses publik ke data kegiatan yang telah dilaporkan (`/data-kegiatan`).
   - Statistik total kegiatan tercatat, akumulasi jam pelajaran (JPL), dan jumlah guru/pegawai yang berpartisipasi.
   - Filter berdasarkan tahun dan pencarian nama.

5. **Panel Pengelola / Administrator (`/admin`)**
   - Dashboard statistik dan ringkasan status verifikasi.
   - **Tinjau & Verifikasi Berkas**: Setujui atau minta revisi dengan catatan pengelola.
   - **Download Sertifikat**: Unduh langsung berkas sertifikat yang diunggah.
   - **Export Excel / CSV**: Unduh seluruh rekapitulasi data kegiatan dengan encoding UTF-8 BOM yang langsung rapi dan kompatibel dibuka di Microsoft Excel.
   - **Manajemen Master PTK**: Tambah guru baru, edit, atau import database pegawai dari file `.CSV`.

---

## 🚀 Cara Menjalankan Proyek

### 1. Masuk ke direktori proyek
```bash
cd /home/menara/Project-Dev/sikat-ptk
```

### 2. Konfigurasi Database (.env)
Database MySQL default yang sudah dibuat:
- **Database:** `db_sikat_ptk`
- **User:** `menara`
- **Password:** `123`

### 3. Migrasi & Seed Data PTK
```bash
php artisan migrate --seed
```
*Data 67 GTK dari file kepegawaian otomatis terimpor ke database.*

### 4. Menjalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui browser:
- **Formulir Terbuka PTK:** `http://localhost:8000/`
- **Rekap Publik Kegiatan:** `http://localhost:8000/data-kegiatan`
- **Panel Admin:** `http://localhost:8000/admin`

---

## 🔐 Akun Administrator Default

- **URL Login:** `http://localhost:8000/admin/login`
- **Email:** `admin@man2bantul.sch.id`
- **Password:** `admin123`

---

## 📂 Struktur Data PTK
File sumber data kepegawaian tersimpan di `database/data/gtk_data.csv` yang dapat diperbarui kapan saja atau diimpor ulang melalui menu **Master PTK** di panel admin.
