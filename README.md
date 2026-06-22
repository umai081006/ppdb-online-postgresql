# Sistem Penerimaan Peserta Didik Baru (PPDB) Online

Sistem PPDB Online berbasis web untuk mempermudah proses pendaftaran, verifikasi berkas, dan seleksi siswa baru. Dibangun menggunakan arsitektur MVC standar dengan **Laravel**, **PostgreSQL**, dan **Tailwind CSS**.

## ✨ Fitur Utama

### 👤 Siswa
- **Registrasi & Login** (Autentikasi aman bawaan Laravel Breeze)
- **Form Pendaftaran Lengkap**: Mengisi biodata, asal sekolah, nilai, dan memilih jalur pendaftaran (Reguler, Prestasi, Zonasi).
- **Upload Dokumen Aman**: Upload dokumen wajib (Foto, KK, Ijazah, Rapor, Piagam) yang divalidasi ketat (ekstensi pdf/jpg/png, maks 2MB) dan disimpan secara otomatis ke **Cloudinary** (Cloud Storage).
- **Dashboard Tracking**: Memantau status verifikasi dan melihat hasil akhir kelulusan.

### 👥 Panitia
- **Dashboard Monitoring**: Melihat daftar pendaftar lengkap dengan fitur pencarian dan filter status.
- **Verifikasi Dokumen**: Mengecek berkas siswa. Panitia dapat menerima (*approve*) atau menolak (*reject*) dokumen dengan memberikan catatan perbaikan.
- Jika seluruh dokumen disetujui, status siswa otomatis berubah menjadi "Terverifikasi".

### 👑 Admin
- **Dashboard & Statistik**: Grafik jumlah pendaftar per hari dan ringkasan status pendaftaran.
- **Manajemen Panitia**: Menambah, mengubah, dan menghapus akun panitia.
- **Manajemen Pengumuman**: Membuat pengumuman informasi PPDB yang tampil di halaman utama.
- **Sistem Seleksi Otomatis**:
  - Seleksi cerdas berdasarkan nilai tertinggi, batas kuota per jalur, dan syarat tambahan (Misal: Jalur Zonasi akan mencocokkan kecamatan, Jalur Prestasi mengecek piagam).
  - **Preview Mode**: Admin bisa melihat simulasi daftar yang diterima/ditolak sebelum dipublikasikan.
  - **Publikasi Massal**: Mengubah status siswa secara permanen dengan 1 klik.
  - **Export to Excel**: Mengunduh rekap siswa yang diterima ke format `.xlsx` (semua jalur sekaligus, atau spesifik per jalur) menggunakan `maatwebsite/excel`.

## 🛠️ Teknologi yang Digunakan
- **Framework backend**: Laravel 11
- **Database**: PostgreSQL
- **Styling**: Tailwind CSS
- **File Storage**: Cloudinary (Aman dan tidak membebani server lokal)
- **Library Export**: Laravel Excel (`maatwebsite/excel`)

## 🚀 Panduan Instalasi (Development)

Pastikan komputer Anda telah terinstal **PHP >= 8.2**, **Composer**, **Node.js**, dan **PostgreSQL**.

1. Clone repository ini ke komputer Anda:
```bash
git clone https://github.com/username/sistem-ppdb-online.git
cd sistem-ppdb-online/ppdb-online-laravel
```

2. Install dependency PHP (vendor) dan Node.js (node_modules):
```bash
composer install
npm install
```

3. Salin file `.env.example` menjadi `.env` lalu atur konfigurasi database dan Cloudinary API:
```bash
cp .env.example .env
```
Buka file `.env` dan sesuaikan bagian ini:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nama_database_anda
DB_USERNAME=username_postgres
DB_PASSWORD=password_postgres

# Dapatkan URL ini dari dashboard akun Cloudinary Anda
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
```

4. Generate Application Key:
```bash
php artisan key:generate
```

5. Jalankan migrasi database beserta seeder (untuk membuat Jalur Pendaftaran default):
```bash
php artisan migrate --seed
```

6. Build assets Tailwind CSS:
```bash
npm run dev
```

7. Buka terminal baru dan jalankan server lokal Laravel:
```bash
php artisan serve
```

Aplikasi kini dapat diakses melalui browser di `http://localhost:8000`.

## 📌 Akun Testing (Role Management)

Sistem ini memiliki 3 level Role yang dikelola oleh Middleware (`RoleMiddleware`): `admin`, `panitia`, dan `siswa`.
- **Siswa**: Lakukan registrasi biasa di halaman `/register`.
- **Admin**: Lakukan registrasi, lalu buka database manager (misal pgAdmin) atau Laravel Tinker, dan ubah field `role` akun Anda pada tabel `users` menjadi `admin`. Setelah itu, Anda dapat login dan langsung membuatkan akun untuk Panitia lewat Dashboard Admin.

---
*Project ini dikembangkan sebagai implementasi pembelajaran framework Laravel.*
