# Factura - Sistem Manajemen Invoice

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
[![Laravel v11.x](https://img.shields.io/badge/Laravel-v12.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com/)
[![Filament v3.x](https://img.shields.io/badge/Filament-v3.x-228B22?style=flat-square&logo=filament)](https://filamentphp.com/)
[![Livewire v3.x](https://img.shields.io/badge/Livewire-v3.x-4B4B4B?style=flat-square&logo=livewire)](https://livewire.laravel.com/)

**Factura** adalah aplikasi manajemen invoice berbasis Laravel dan Filament v3, dengan fitur multi-perusahaan, penomoran otomatis, dan pembuatan PDF..

---

## Fitur Utama

-   **Dashboard Admin Profesional**: Dibuat sepenuhnya dengan [Filament v3](https://filamentphp.com/), memberikan pengalaman pengguna yang cepat dan reaktif.
-   **Manajemen Multi-Perusahaan**: Memungkinkan satu pengguna untuk mengelola beberapa profil perusahaan dan menerbitkan invoice atas nama perusahaan yang berbeda.
-   **Manajemen Invoice**: CRUD lengkap untuk membuat, melihat, mengubah, dan menghapus invoice.
-   **Penomoran Invoice Otomatis**: Nomor invoice dibuat secara otomatis dengan format `INV/TAHUN/BULAN/URUT` berdasarkan tanggal invoice yang dipilih.
-   **Kalkulasi Pajak & Total Otomatis**: Perhitungan PPN (11%) dan Grand Total dilakukan secara otomatis menggunakan Accessor pada Model.
-   **Status Invoice & Watermark**: Status invoice (Lunas, Belum Lunas, dll.) yang dapat diubah dan akan secara otomatis menampilkan watermark pada dokumen PDF.
-   **Preview & Download PDF**: Fitur untuk melihat pratinjau invoice dalam format A4 secara langsung di browser atau mengunduhnya.
-   **Template PDF Modern**: Tampilan invoice yang bersih dan profesional dibuat menggunakan Tailwind CSS.
-   **Koneksi ke Database Supabase**: Siap untuk dihubungkan dengan layanan database modern PostgreSQL.

## Teknologi yang Digunakan

-   **Backend**: Laravel 12
-   **Admin Panel**: Filament 3
-   **Frontend**: Tailwind CSS, Alpine.js (via Filament)
-   **Asset Bundling**: Vite
-   **Database**: PostgreSQL (di-hosting di Supabase)
-   **PDF Generation**: barryvdh/laravel-dompdf

---

## Instalasi & Setup Proyek

Berikut adalah langkah-langkah untuk menjalankan proyek ini di lingkungan baru (lokal atau server hosting).

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/NAMA_USER_ANDA/NAMA_REPO_ANDA.git](https://github.com/NAMA_USER_ANDA/NAMA_REPO_ANDA.git)
    cd NAMA_REPO_ANDA
    ```

2.  **Instalasi Dependensi**
    ```bash
    composer install
    npm install
    ```

3.  **Konfigurasi Lingkungan (.env)**
    Salin file `.env.example` menjadi `.env` baru, lalu generate kunci aplikasi.
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4.  **Atur Variabel di `.env`**
    Buka file `.env` dan atur variabel-variabel berikut:

    ```env
    APP_NAME=Factura
    APP_ENV=local
    APP_DEBUG=true
    APP_URL=http://localhost

    # Gunakan format DB_URL dari Connection Pooler Supabase
    DB_CONNECTION=pgsql
    DATABASE_URL="postgres://postgres.[PROJECT_REF]:[PASSWORD_ANDA]@[AWS_REGION][.pooler.supabase.com:6543/postgres](https://.pooler.supabase.com:6543/postgres)"
    ```

5.  **Build Aset Frontend**
    ```bash
    npm run build
    ```

6.  **Jalankan Migrasi Database**
    ```bash
    php artisan migrate
    ```
    *Untuk mengisi data awal, jalankan: `php artisan migrate --seed`*

7.  **Buat Pengguna Admin Pertama**
    ```bash
    php artisan make:filament-user
    ```
    Ikuti petunjuk di layar untuk memasukkan nama, email, dan password Anda.

9.  **Jalankan Server Development (Lokal)**
    ```bash
    php artisan serve
    ```
    Sekarang Anda bisa mengakses aplikasi di `http://localhost:8000` dan login ke panel admin di `http://localhost:8000/admin`.

## Lisensi

Proyek ini berada di bawah [Lisensi MIT](https://opensource.org/licenses/MIT).
