# 🚀 PANDUAN DEPLOY: SI ARSIP v2.5

Manual resmi untuk proses deployment aplikasi **SI ARSIP** ke lingkungan produksi (Server PT. BIM PPS).

---

## 1. Persiapan Lingkungan (Server Side)
Pastikan server Anda memenuhi persyaratan minimum:
*   **PHP 8.2** atau lebih tinggi.
*   **MySQL 8.0** (Database Host: `100.105.24.141`, Port: `3307`).
*   **Web Server:** Nginx (Direkomendasikan) atau Apache.
*   **Composer** (v2.x).

---

## 2. Langkah-Langkah Instalasi

### A. Clone & Install Dependencies
1. Upload folder project ke server.
2. Jalankan perintah instalasi tanpa menyertakan library development:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

### B. Konfigurasi Environment (`.env`)
Pastikan file `.env` sudah disetel ke mode produksi:
```env
APP_NAME="SI ARSIP"
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain-or-ip

DB_CONNECTION=mysql
DB_HOST=100.105.24.141
DB_PORT=3307
DB_DATABASE=arsip_db
DB_USERNAME=db_admin
DB_PASSWORD="?passw0rdA"
```

### C. Keamanan & Database
1. Generate App Key baru:
   ```bash
   php artisan key:generate
   ```
2. Jalankan migrasi database:
   ```bash
   php artisan migrate --force
   ```
3. Hubungkan folder storage untuk file PDF:
   ```bash
   php artisan storage:link
   ```

---

## 3. Optimasi Kecepatan (Wajib)
Jalankan perintah berikut untuk mengunci konfigurasi agar aplikasi berjalan lebih cepat:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 4. Izin Akses Folder (Permissions)
Berikan izin akses kepada user web server (biasanya `www-data`) untuk menulis file:
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## 5. Fitur Kritis untuk Dicek (Post-Deploy)
*   **Login:** Menggunakan **Username** (Contoh: `admin`, `it_bimpps`).
*   **PDF Viewer:** Pastikan dokumen tampil di halaman detail.
*   **Offline Fallback:** Cek apakah aset Bootstrap & Icons tetap tampil jika internet lambat.
*   **Rate Limiting:** Pastikan login tidak bisa dilakukan secara brute-force (max 5x/menit).

---

## 6. Troubleshooting
*   **Icons Hilang:** Cek apakah file font di `public/assets/fonts/` sudah lengkap.
*   **PDF 404:** Hapus folder `public/storage` lalu jalankan ulang `php artisan storage:link`.
*   **Change Password:** Pastikan user mengganti password default `123456` segera setelah login pertama kali.

---
*Dibuat oleh: Gemini Engineering Team*
*Tahun: 2026*
