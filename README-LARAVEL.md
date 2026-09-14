# Koperasi Konsumen Yapimu Ahsanu Amala — Versi Laravel

Aplikasi simpan–pinjam koperasi (Laravel + MySQL/MariaDB). Paket ini berisi **kode aplikasi**
(Models, Migrations, Seeder, Controllers, Blade, Middleware, Routes) untuk ditimpakan ke
instalasi Laravel yang baru. Folder `vendor/` **tidak** disertakan — dibuat oleh Composer.

Fitur: login pengurus & anggota, data anggota + akun, simpanan (Pokok/Wajib/Sukarela),
buku kas gabungan otomatis, pinjaman (plafon = sukarela × faktor, jasa %/bln, angsuran),
iuran wajib bulanan + pop-up pengingat, pembagian SHU, laporan bulanan (cetak PDF),
cetak Kartu Anggota & Bukti Setoran, anggota ganti PIN sendiri.

---

## A. Ringkasan cepat (server ada PHP 8.2+ & Composer)

```bash
# 1. Buat proyek Laravel baru
composer create-project laravel/laravel koperasi
cd koperasi

# 2. Timpa dengan file dari paket ini (isi folder koperasi-laravel/)
#    salin: app/  database/  resources/  routes/  public/logo.svg  public/css/  .env.example
#    (timpa file yang sama, mis. routes/web.php & database/seeders/DatabaseSeeder.php)

# 3. Daftarkan helper global -> edit composer.json bagian "autoload" (lihat stubs/composer.autoload-snippet.json)
#    tambahkan:  "files": ["app/helpers.php"]
composer dump-autoload

# 4. Daftarkan alias middleware (lihat langkah B di bawah)

# 5. Konfigurasi .env
cp .env.example .env
php artisan key:generate
#   isi DB_DATABASE / DB_USERNAME / DB_PASSWORD sesuai database Anda
#   pastikan SESSION_DRIVER=file

# 6. Buat tabel + data awal
php artisan migrate --seed
```

Login demo — Pengurus: **admin / admin123** · Anggota: **AGT-001 / 1001**.

---

## B. Daftarkan alias middleware

**Laravel 11 / 12** — edit `bootstrap/app.php`, di dalam `->withMiddleware(...)` tambahkan
blok `$middleware->alias([...])` seperti pada `stubs/bootstrap-app.L11.php`.

**Laravel 10** — edit `app/Http/Kernel.php`, tambahkan 3 baris pada array
`$middlewareAliases` seperti pada `stubs/kernel-alias.L10.txt`.

Alias yang didaftarkan: `admin`, `member`, `kauth`.

---

## C. Pasang di aaPanel (produksi)

1. **Website → Add site**: isi domain, pilih **PHP 8.2** (atau 8.3), buat database MySQL
   sekalian (catat nama/user/password).
2. Upload seluruh isi proyek ke folder situs (mis. `/www/wwwroot/namadomain`).
   Bila belum `composer install`, buka **Terminal** aaPanel di folder itu lalu jalankan:
   ```bash
   composer install --no-dev --optimize-autoloader
   php artisan key:generate
   php artisan migrate --seed
   ```
3. **Set Document Root ke sub-folder `/public`**
   (Website → Settings → Site directory → "Running directory" = `/public`).
4. **URL Rewrite** untuk Laravel (Website → URL Rewrite → pilih **laravel5**,
   atau tempel aturan berikut bila pakai Nginx):
   ```
   location / { try_files $uri $uri/ /index.php?$query_string; }
   ```
5. **Izin folder**: `storage/` dan `bootstrap/cache/` harus dapat ditulis:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www:www storage bootstrap/cache
   ```
6. Ekstensi PHP: aktifkan **pdo_mysql**, **mbstring**, **openssl**, **fileinfo**, **tokenizer** (umumnya sudah default).
7. Buka domain → halaman login muncul.

> Catatan: `composer create-project` menyertakan migrasi bawaan Laravel (users/cache/jobs).
> Itu tidak mengganggu; tabel `sessions` tidak dipakai karena `SESSION_DRIVER=file`.
> Bila ingin bersih, migrasi bawaan `*_create_users_table` boleh dihapus sebelum migrate.

---

## D. Setelah live (keamanan aplikasi keuangan)

- Ganti password pengurus (menu **Pengaturan**) dan PIN anggota demo, atau hapus data demo.
- Set `APP_DEBUG=false` dan `APP_ENV=production` di `.env`.
- Aktifkan **HTTPS** (aaPanel → SSL → Let's Encrypt).
- Jadwalkan **backup database otomatis** (aaPanel → Cron / Backup) ke cloud/FTP.
- Nominal uang memakai `DECIMAL(14,2)` dan tabel InnoDB (mendukung transaksi & foreign key).

---

## E. Struktur berkas penting

```
app/
  helpers.php                 fungsi global (rupiah, terbilang, setting, cu, is_admin, icon, ...)
  Support/                    Ledger.php (buku kas), Laporan.php (rekap bulanan), Icons.php (SVG)
  Models/                     Anggota, Simpanan, Kas, Pinjaman, Angsuran, Iuran, Admin, Pengaturan
  Http/Controllers/           Auth, Dashboard, Anggota, Simpanan, Kas, Pinjaman, Iuran, Shu,
                              Pengaturan, Laporan, Cetak  (+ Member/: Portal, PinjamanSaya, Akun)
  Http/Middleware/            EnsureAdmin, EnsureMember, EnsureLogin
database/migrations/          8 tabel
database/seeders/             DatabaseSeeder.php (admin, pengaturan, 5 anggota demo)
routes/web.php                seluruh rute (bernama)
resources/views/              layouts, partials, admin/, member/, cetak/, login
public/                       logo.svg, css/app.css
stubs/                        contoh composer autoload & pendaftaran middleware
```
