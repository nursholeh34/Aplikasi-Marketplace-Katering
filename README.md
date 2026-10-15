# Marketplace Katering (Laravel 11)

Platform yang mempertemukan **katering (merchant)** dengan **kantor (customer)** untuk pesanan makan siang karyawan.

Folder ini adalah **lapisan kode aplikasi** (overlay) di atas skeleton Laravel 11 yang bersih.

## Kebutuhan
PHP 8.2+ (diuji sintaksnya di 8.3), Composer 2, MySQL/MariaDB.
Ekstensi PHP aktif: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `ctype`, `tokenizer`, `xml`.
Node/npm **tidak diperlukan** (Bootstrap dimuat dari CDN, jadi butuh internet saat membuka halaman).

## Instalasi (Windows / CMD)

```bat
:: 1. Buat proyek Laravel 11 baru
composer create-project laravel/laravel:^11.0 marketplace-katering
cd marketplace-katering

:: 2. Salin isi zip ini ke folder proyek, TIMPA file yang sudah ada (ya untuk semua)

:: 3. Siapkan .env
copy /Y .env.example .env
php artisan key:generate

:: 4. Buat database kosong bernama marketplace_katering (phpMyAdmin / HeidiSQL / CLI),
::    lalu sesuaikan DB_USERNAME dan DB_PASSWORD di file .env

:: 5a. Cara A - lewat migrasi + data contoh
php artisan migrate --seed

:: 5b. Cara B - lewat file SQL (pilih salah satu, jangan keduanya)
::     mysql -u root -p marketplace_katering < database/catering.sql

:: 6. Link folder upload foto menu
php artisan storage:link

:: 7. Jalankan
php artisan serve
```
Buka http://localhost:8000

Tanpa MySQL? Ubah `.env` menjadi `DB_CONNECTION=sqlite`, hapus baris DB_HOST/PORT/DATABASE/USERNAME/PASSWORD, lalu `php artisan migrate --seed` (jangan pakai file SQL).

## Akun contoh (kata sandi: `password`)
| Peran | Email |
|---|---|
| Merchant | merchant1@example.com (Dapur Bu Sari), merchant2@example.com (Pempek & Co) |
| Customer | customer@example.com (PT Maju Bersama) |

## Fitur sesuai soal
**Portal Merchant** — registrasi & login, kelola profil (nama perusahaan, alamat, kontak, deskripsi), CRUD menu (deskripsi, foto, harga), daftar pesanan + invoice yang masuk, dan ubah status pesanan (menunggu → dikonfirmasi → terkirim / batal).

**Portal Customer** — registrasi & login, cari katering (kata kunci nama/menu, kota, jenis makanan), pilih menu + jumlah porsi + tanggal pengiriman, invoice otomatis, riwayat pesanan, batalkan pesanan yang masih menunggu.

**Invoice** — satu halaman `/invoices/{id}` yang dapat dibuka merchant maupun customer yang terlibat (tombol cetak / simpan PDF dari browser).

## Poin teknis
- **Keamanan login**: password di-hash (bcrypt), CSRF di semua form, regenerasi session saat login (anti session fixation), rate limit login 6x/menit, middleware `role:merchant` / `role:customer`.
- **Otorisasi data**: merchant hanya bisa mengubah menu/order miliknya; invoice hanya bisa dibuka pihak terkait (selain itu 403).
- **Integritas harga**: harga dan total dihitung ulang di server dari database, bukan dari input form; harga/nama menu disalin ke `order_items` sehingga invoice lama tidak berubah bila menu diedit/dihapus.
- **Transaksi DB** untuk registrasi dan pembuatan order (semua berhasil atau tidak sama sekali).
- **Manajemen error**: validasi berbahasa Indonesia, `try/catch` + `report()` di operasi tulis, halaman error kustom (403/404/419/429/500/503).
- **Upload foto**: validasi `image|mimes:jpg,jpeg,png,webp|max:2048`, foto lama dihapus saat diganti.

## Struktur database
`users` (role: merchant/customer) → `merchants` / `customers` (1-1) → `menus` (milik merchant) → `orders` (customer + merchant) → `order_items`.
