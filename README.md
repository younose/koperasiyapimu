# Koperasi Konsumen Yapimu Ahsanu Amala

Aplikasi simpan–pinjam koperasi berbasis **Laravel + MySQL/MariaDB**.

README ini memandu **alur kerja end-to-end**:
**VSCode (ngoding) → Test lokal → Push ke GitHub → Deploy ke aaPanel**, sekaligus cara update rutin.

> Panduan pemasangan detail per-fitur ada di `README-LARAVEL.md`.

---

## 0. Gambaran Alur

```
┌───────────┐  git push   ┌──────────┐  git pull / webhook   ┌────────────┐
│  VSCode   │ ──────────► │  GitHub  │ ───────────────────► │  aaPanel   │
│ (laptop)  │             │  (repo)  │                       │  (server)  │
└───────────┘             └──────────┘                       └────────────┘
   ngoding                  simpan &                            live untuk
   + test lokal             riwayat versi                       pengurus/anggota
```

Prinsip penting: **kode** disimpan di Git, tetapi **rahasia** (`.env`) dan **hasil build** (`vendor/`, `storage/`)
TIDAK ikut ke Git. Server mengambil kode dari GitHub lalu menjalankan `composer install` & `migrate` sendiri.

---

## 1. Prasyarat di Laptop (sekali saja)

Install:

- **PHP 8.2+** dan ekstensi: `mbstring, pdo_mysql, openssl, tokenizer, xml, curl, fileinfo`
- **Composer** (https://getcomposer.org)
- **Git** (https://git-scm.com)
- **MySQL/MariaDB** lokal (atau XAMPP/Laragon/Herd)
- **VSCode** + ekstensi yang disarankan:
  - *PHP Intelephense* (autocomplete PHP)
  - *Laravel Blade Snippets* / *Laravel Blade formatter*
  - *DotENV*
  - *GitLens* (opsional, riwayat Git)

Cek instalasi:

```bash
php -v
composer -V
git --version
```

---

## 2. Menyiapkan Proyek di VSCode

Karena paket ini berisi **kode aplikasi** (tanpa folder `vendor/`), buat kerangka Laravel dulu lalu timpakan file dari paket.

```bash
# a. Buat proyek Laravel baru
composer create-project laravel/laravel koperasi
cd koperasi

# b. Salin isi paket koperasi-laravel/ ke dalam folder ini (timpa file yg sama):
#    app/  database/  resources/  routes/  public/logo.svg  public/css/  stubs/
#    README.md  README-LARAVEL.md  .env.example
```

Lalu daftarkan helper global & middleware (WAJIB, sekali saja):

1. Edit `composer.json` bagian `autoload`, tambahkan baris `files` (contoh: `stubs/composer.autoload-snippet.json`):

   ```json
   "autoload": {
       "psr-4": { "App\\": "app/", "Database\\Factories\\": "database/factories/", "Database\\Seeders\\": "database/seeders/" },
       "files": [ "app/helpers.php" ]
   }
   ```

   ```bash
   composer dump-autoload
   ```

2. Daftarkan alias middleware `admin`, `member`, `kauth`:
   - **Laravel 11/12** → `bootstrap/app.php` (lihat `stubs/bootstrap-app.L11.php`)
   - **Laravel 10** → `app/Http/Kernel.php` (lihat `stubs/kernel-alias.L10.txt`)

Buka folder di VSCode:

```bash
code .
```

---

## 3. Test di Lokal (sebelum push)

```bash
# a. Siapkan .env
cp .env.example .env
php artisan key:generate

# b. Isi koneksi database lokal di .env
#    DB_DATABASE=koperasi
#    DB_USERNAME=root
#    DB_PASSWORD=            (sesuai MySQL lokal Anda)
#    SESSION_DRIVER=file

# c. Buat database kosong "koperasi" (via phpMyAdmin / CLI), lalu:
php artisan migrate --seed

# d. Jalankan server pengembangan
php artisan serve
```

Buka **http://127.0.0.1:8000**.

Checklist uji cepat (manual):

- [ ] Login pengurus `admin / admin123` → dashboard tampil
- [ ] Tambah anggota, catat simpanan, cek buku kas otomatis
- [ ] Beri/pengajuan pinjaman, bayar angsuran
- [ ] Generate iuran → muncul pop-up pengingat
- [ ] Cetak Kartu Anggota & Bukti Setoran (Save as PDF)
- [ ] Laporan bulanan tampil & bisa dicetak
- [ ] Login anggota `AGT-001 / 1001` → portal, ajukan pinjaman, ganti PIN

Bila memakai test otomatis Laravel:

```bash
php artisan test
```

> Perbaiki dulu semua error di lokal. **Jangan push kode yang belum jalan.**

---

## 4. Menyiapkan Git & Push ke GitHub

### 4.1 Pastikan `.gitignore` benar

Laravel sudah menyertakan `.gitignore` bawaan yang mengecualikan `vendor/`, `.env`,
`storage/*`, dll. Pastikan minimal berisi:

```gitignore
/vendor
/node_modules
.env
.env.backup
/storage/*.key
/public/storage
/.phpunit.cache
```

> **PENTING:** `.env` berisi password database — jangan pernah masuk ke Git.
> Yang di-commit adalah `.env.example` (tanpa nilai rahasia).

### 4.2 Inisialisasi & commit pertama

```bash
git init
git add .
git commit -m "Initial commit: aplikasi koperasi Laravel"
git branch -M main
```

### 4.3 Buat repo di GitHub

1. Buka https://github.com/new → beri nama, pilih **Private** (disarankan untuk aplikasi keuangan) → **Create repository**.
2. Hubungkan & push:

```bash
git remote add origin https://github.com/USERNAME/koperasi.git
git push -u origin main
```

> Untuk push berikutnya, GitHub meminta **Personal Access Token** (bukan password).
> Buat di: GitHub → Settings → Developer settings → Personal access tokens.
> Alternatif lebih praktis: pakai **SSH key** (Settings → SSH and GPG keys).

### 4.4 Siklus kerja harian

```bash
# setelah mengubah kode & lolos test lokal:
git add .
git commit -m "Deskripsi singkat perubahan"
git push
```

---

## 5. Deploy ke aaPanel (pertama kali)

### 5.1 Siapkan situs & database

1. **aaPanel → Website → Add site**: isi domain, pilih **PHP 8.2** (atau 8.3),
   centang buat **MySQL database** sekaligus (catat: nama DB, user, password).
2. Aktifkan ekstensi PHP (App Store → PHP 8.2 → Settings → Install): `pdo_mysql`, `mbstring`,
   `openssl`, `fileinfo`, `tokenizer`, `curl`, `xml` (umumnya sudah aktif).

### 5.2 Ambil kode dari GitHub

**Opsi A — Git di Terminal aaPanel (disarankan):**

```bash
cd /www/wwwroot
rm -rf namadomain           # kosongkan folder situs bila perlu (hati-hati)
git clone https://github.com/USERNAME/koperasi.git namadomain
cd namadomain
```

> Repo private: gunakan Personal Access Token pada URL
> `https://USERNAME:TOKEN@github.com/USERNAME/koperasi.git`, atau pasang **Deploy Key** (SSH).

**Opsi B — Fitur Git aaPanel:** menu **Git** (App Store) → clone repo ke folder situs.

### 5.3 Build & konfigurasi di server

```bash
cd /www/wwwroot/namadomain

# a. Install dependency (mode produksi)
composer install --no-dev --optimize-autoloader

# b. Siapkan .env di SERVER (jangan dari Git)
cp .env.example .env
php artisan key:generate

# c. Edit .env → isi DB dari langkah 5.1, dan:
#    APP_ENV=production
#    APP_DEBUG=false
#    APP_URL=https://namadomain
#    SESSION_DRIVER=file
nano .env

# d. Migrasi + data awal
php artisan migrate --seed

# e. Optimasi cache produksi
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5.4 Arahkan web root & rewrite

1. **Website → Settings → Site directory** → set **Running directory = `/public`**.
2. **Website → URL Rewrite** → pilih template **laravel5**
   (atau tempel manual untuk Nginx):

   ```
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```

### 5.5 Izin folder

```bash
cd /www/wwwroot/namadomain
chmod -R 775 storage bootstrap/cache
chown -R www:www storage bootstrap/cache
```

### 5.6 Uji

Buka `https://namadomain` → halaman login muncul. Login `admin / admin123`.

---

## 6. Update Aplikasi (deploy berikutnya)

Setiap kali ada perubahan yang sudah di-push ke GitHub:

```bash
cd /www/wwwroot/namadomain
git pull

composer install --no-dev --optimize-autoloader   # bila dependency berubah
php artisan migrate                                 # bila ada migration baru

# refresh cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Agar ringkas, buat skrip `deploy.sh` di server:

```bash
#!/usr/bin/env bash
set -e
cd /www/wwwroot/namadomain
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan up
echo "Deploy selesai."
```

Jalankan dengan `bash deploy.sh`.

**Otomatis (opsional):** di GitHub → repo → Settings → **Webhooks**, arahkan ke endpoint
deploy di aaPanel (menu **Git → Webhook**) agar `git pull` jalan otomatis tiap push.

---

## 7. Keamanan (wajib untuk aplikasi uang)

- Ganti password pengurus (menu **Pengaturan**) & PIN anggota demo, atau hapus data demo.
- `APP_DEBUG=false` + `APP_ENV=production` di server.
- Aktifkan **HTTPS**: aaPanel → situs → **SSL** → Let's Encrypt → **Force HTTPS**.
- Jadwalkan **backup database otomatis**: aaPanel → **Cron** atau **Backup** → kirim ke cloud/FTP.
- Repo GitHub sebaiknya **Private**; pastikan `.env` tidak pernah ter-commit.
- Nominal uang memakai `DECIMAL(14,2)` & tabel **InnoDB** (transaksi + foreign key).

---

## 8. Troubleshooting singkat

| Gejala | Penyebab umum | Solusi |
|---|---|---|
| Halaman 500 blank | izin `storage`/`bootstrap/cache` | jalankan langkah 5.5; cek `storage/logs/laravel.log` |
| CSS/logo tidak muncul | web root belum `/public` | set Running directory `/public` (5.4) |
| 404 di semua halaman selain "/" | rewrite belum aktif | pasang URL Rewrite laravel5 (5.4) |
| `Class ... not found` (helper) | belum daftar autoload | `composer dump-autoload` (langkah 2) |
| Route `admin`/`member` error | alias middleware belum didaftar | daftarkan alias (langkah 2) |
| Perubahan tak muncul | cache lama | `php artisan optimize:clear` lalu cache ulang |
| Login gagal terus | belum `migrate --seed` | jalankan `php artisan migrate --seed` |

---

## 9. Perintah yang sering dipakai

```bash
php artisan serve            # server lokal
php artisan migrate --seed   # buat tabel + data awal
php artisan migrate:fresh --seed   # reset total (HATI-HATI: hapus semua data)
php artisan optimize:clear   # bersihkan semua cache
php artisan tinker           # REPL untuk coba query/model
git status                   # lihat perubahan
```

---

**Akun demo** — Pengurus: `admin / admin123` · Anggota: `AGT-001 / 1001`.
