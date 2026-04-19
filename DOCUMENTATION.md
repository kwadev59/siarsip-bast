# 📚 DOKUMENTASI TEKNIS: SI ARSIP v2.5

Sistem Manajemen Arsip Digital PT. BIM PPS. Dokumentasi ini berfungsi sebagai panduan pengembangan, pemeliharaan, dan peningkatan skala aplikasi.

---

## 1. Arsitektur Sistem
Aplikasi ini dibangun menggunakan arsitektur **Monolith Modern** yang mengutamakan kecepatan dan kemudahan pemeliharaan tanpa ketergantungan pada alat build frontend yang kompleks.

*   **Backend:** Laravel 12.x (PHP 8.2+)
*   **Database:** MySQL 8.x (Remote Connection)
*   **Frontend:** Bootstrap 5.3 (Vanilla JS, Tanpa Node.js/npm)
*   **Asset Strategy:** Hybrid (CDN dengan Fallback Lokal di `public/assets`)

---

## 2. Struktur Data Utama

### Entitas Pengguna (Users)
*   **Auth Identitas:** Menggunakan `username` unik.
*   **Otomatisasi:** Username dibuat otomatis dengan format `nama_bimpps`.
*   **Role:** `superadmin` (Akses Master Data & User) dan `admin` (Operasional Arsip).

### Manajemen Dokumen (Archives)
*   **UUID:** Setiap arsip memiliki UUID unik untuk keamanan link.
*   **File Storage:** PDF disimpan di `storage/app/public/archives`.
*   **Signed URL:** Download file diproteksi dengan tanda tangan digital sementara (15 menit).

### Relasi Database
*   `archives` belongsTo `divisions`, `categories`, `locations`.
*   `borrowing_logs` belongsTo `archives` dan `users`.
*   `audit_logs` mencatat setiap aksi `CREATE`, `UPDATE`, `DELETE` secara otomatis.

---

## 3. Fitur Unggulan & Logika Bisnis

### A. Digitalisasi PDF (PDF Preview)
Menggunakan `iframe` dengan parameter `#toolbar=0` yang tertanam dalam panel detail arsip. Area pratinjau bersifat dinamis mengikuti tinggi layar (`vh`).

### B. Proteksi Integritas
Data Master (Divisi/Kategori/Lokasi) memiliki proteksi penghapusan. Controller akan mengecek apakah data tersebut masih memiliki keterkaitan dengan tabel `archives` sebelum mengizinkan perintah `DELETE`.

### C. Login Hardening
Implementasi middleware `throttle:5,1` pada rute login untuk mencegah serangan brute-force.

---

## 4. Panduan Pemeliharaan (Maintenance)

### Menambah Modul Master Data Baru
1.  Buat Model & Migrasi.
2.  Gunakan `app/Http/Controllers/DivisionController.php` sebagai template (Single Page Modal CRUD).
3.  Daftarkan rute di `routes/web.php` sebagai `Route::resource`.

### Update Aset Frontend
Jika ingin memperbarui versi Bootstrap atau Icons secara offline:
1.  Ganti file di `public/assets/css/` dan `public/assets/js/`.
2.  Pastikan file font di `public/assets/fonts/` ikut diperbarui jika menggunakan versi ikon yang berbeda.

---

## 5. Strategi Skalabilitas (Scaling)

Jika jumlah dokumen mencapai puluhan ribu:
1.  **Storage:** Pindahkan `public/storage` ke layanan Cloud Storage seperti AWS S3 atau S3-Compatible (MinIO) melalui konfigurasi `filesystems.php`.
2.  **Database:** Tambahkan Index pada kolom `document_number`, `title`, dan `username` untuk mempercepat pencarian.
3.  **Caching:** Aktifkan Redis untuk caching query statistik dashboard yang berat.

---

## 6. Catatan Khusus Pengembang
*   **CSS Variable:** Penyesuaian lebar sidebar dan padding dilakukan melalui variabel CSS di `:root` pada file `app.blade.php`.
*   **Responsivitas:** Aplikasi memiliki "Compact Mode" yang aktif otomatis pada layar laptop resolusi standar untuk mencegah elemen tumpang tindih.

---
*Dibuat oleh: Gemini Engineering Team*
*Update Terakhir: April 2026*
