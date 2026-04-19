# 📘 E-ARSIP (Electronic Archive System)

## Full PRD + ERD + Technical Blueprint

------------------------------------------------------------------------

# 1. OVERVIEW

E-Arsip adalah sistem manajemen arsip hybrid yang mengintegrasikan
dokumen fisik dan digital.

Tujuan: - Mempermudah pencarian dokumen - Mengurangi kehilangan arsip -
Menyediakan tracking peminjaman - Meningkatkan keamanan akses dokumen

Konsep utama: Hybrid Archiving = Digital + Fisik

------------------------------------------------------------------------

# 2. CORE FEATURES

## 2.1 Archive Management

-   Upload file (PDF/JPG/PNG)
-   UUID secure access
-   Metadata lengkap:
    -   nomor dokumen
    -   tanggal
    -   pihak terkait
    -   deskripsi
-   Status:
    -   active
    -   borrowed
    -   archived

## 2.2 Physical Location Mapping

Struktur: Gedung → Ruangan → Lemari → Rak → Box → Map

## 2.3 Advanced Search

-   nomor dokumen
-   kategori
-   tanggal
-   divisi
-   lokasi
-   status

## 2.4 Borrowing System

-   Pinjam / Return dokumen
-   Tracking lengkap

## 2.5 Security & Audit

-   Log semua aktivitas user
-   Tracking IP

## 2.6 Dashboard

-   Statistik arsip
-   Monitoring aktivitas

------------------------------------------------------------------------

# 3. USER ROLES (RBAC)

-   Superadmin → Full akses
-   Manager → Divisi sendiri
-   Staff → Terbatas

Rule: - Semua data difilter berdasarkan division_id

------------------------------------------------------------------------

# 4. USER FLOW

Upload: Scan → Upload → Metadata → Lokasi → Save

Search: Cari → Filter → Detail → Pinjam

Borrow: Klik pinjam → Status berubah

------------------------------------------------------------------------

# 5. TECH STACK

-   Laravel + Filament
-   PostgreSQL
-   Storage: S3 / MinIO

------------------------------------------------------------------------

# 6. DATABASE DESIGN (ERD)

## divisions

-   id
-   name
-   code

## users

-   id
-   name
-   email
-   password
-   role
-   division_id

## categories

-   id
-   name
-   description

## locations

-   id
-   name
-   type
-   parent_id

## archives

-   id
-   uuid
-   document_number
-   title
-   file_path
-   category_id
-   division_id
-   location_id
-   document_date
-   description
-   status
-   created_by

## borrowing_logs

-   id
-   archive_id
-   borrower_name
-   borrowed_at
-   returned_at

## audit_logs

-   id
-   user_id
-   action
-   table_name
-   record_id

------------------------------------------------------------------------

# 7. MIGRATIONS

(see previous section, included)

------------------------------------------------------------------------

# 8. MODELS

Archive Model: - UUID auto generate - Relationship ready

------------------------------------------------------------------------

# 9. RBAC & GLOBAL SCOPE

-   Filter otomatis division_id
-   Superadmin bypass

------------------------------------------------------------------------

# 10. POLICY

-   View: same division / superadmin
-   Update: same division
-   Delete: superadmin only

------------------------------------------------------------------------

# 11. FILAMENT RESOURCE

-   Form input
-   Table + filter
-   Upload file support

------------------------------------------------------------------------

# 12. BEST PRACTICES

-   Gunakan UUID
-   Gunakan SoftDelete
-   Indexing penting
-   Gunakan cloud storage

------------------------------------------------------------------------

# 13. NEXT DEVELOPMENT

-   Seeder
-   Dashboard analytics
-   API integration
-   Mobile support

------------------------------------------------------------------------

END OF DOCUMENT
