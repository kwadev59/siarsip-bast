# 📘 E-ARSIP SYSTEM BREAKDOWN

## Menu, Fitur, dan Fungsi

------------------------------------------------------------------------

# 🧭 1. DASHBOARD

## Tujuan

Memberikan overview kondisi arsip

## Fitur

-   Statistik arsip
-   Statistik peminjaman
-   Aktivitas terbaru
-   Reminder overdue

## Fungsi

-   Total arsip per divisi
-   Status:
    -   Active
    -   Borrowed
    -   Archived
-   Aktivitas terakhir
-   Alert keterlambatan

------------------------------------------------------------------------

# 📂 2. DATA ARSIP

## Fitur 1: List Arsip

### Fungsi

-   Tabel arsip
-   Search:
    -   nomor dokumen
    -   judul
-   Filter:
    -   kategori
    -   tanggal
    -   status
    -   lokasi

------------------------------------------------------------------------

## Fitur 2: Upload Arsip

### Fungsi

-   Upload file
-   Input metadata
-   Generate UUID
-   Validasi data

------------------------------------------------------------------------

## Fitur 3: Detail Arsip

### Fungsi

-   Preview file
-   Metadata lengkap
-   Lokasi fisik
-   Riwayat peminjaman

------------------------------------------------------------------------

## Fitur 4: Edit Arsip

### Fungsi

-   Update metadata
-   Ganti file
-   Audit log

------------------------------------------------------------------------

## Fitur 5: Delete Arsip

### Fungsi

-   Soft delete
-   Superadmin only

------------------------------------------------------------------------

## Fitur 6: Download

### Fungsi

-   Akses file aman via UUID

------------------------------------------------------------------------

# 🗄️ 3. MASTER DATA

## Divisions

-   CRUD divisi

## Categories

-   CRUD kategori

## Locations

-   Struktur hirarki lokasi

------------------------------------------------------------------------

# 🔄 4. LOG PEMINJAMAN

## Pinjam

-   Input peminjam
-   Update status

## Return

-   Input tanggal kembali
-   Update status

## History

-   List log
-   Filter data

------------------------------------------------------------------------

# 👥 5. USER MANAGEMENT

## Fitur

-   CRUD user
-   Assign role
-   Assign division

------------------------------------------------------------------------

# 🔐 6. AUDIT LOG

## Fungsi

-   Tracking aktivitas user
-   Filter berdasarkan user/action

------------------------------------------------------------------------

# 🔍 7. GLOBAL FEATURES

## RBAC

-   Filter berdasarkan divisi

## Search

-   Full-text (optional)

## Security

-   UUID file
-   Signed URL

## Soft Delete

-   Restore data

------------------------------------------------------------------------

# ⚡ 8. OPTIONAL FEATURES

## OCR

-   Scan ke teks

## Bulk Upload

-   Upload banyak file

## Notification

-   Reminder & alert

## Reporting

-   Export PDF/Excel

------------------------------------------------------------------------

# 🚀 ROADMAP

## Versi 1

-   Arsip
-   Lokasi
-   Borrow
-   RBAC

## Versi 2

-   Audit log
-   Dashboard
-   Reporting

## Versi 3

-   OCR
-   Notification
-   API

------------------------------------------------------------------------

END OF DOCUMENT
