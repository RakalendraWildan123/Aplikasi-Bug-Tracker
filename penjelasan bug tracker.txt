# PENJELASAN LENGKAP DOKUMEN SRS
## APLIKASI MONITORING & MANAJEMEN IT DEVELOPER (BUG TRACKER)

Dokumen ini merupakan ringkasan dan penjelasan mendalam dari dokumen **Software Requirements Specification (SRS)** resmi bernomor `SRS-ITDEV-2026-001-REV07` (Versi 2.0, Terbit 16 November 2026).

---

### 1. RINGKASAN EKSEKUTIF & TUJUAN UTAMA
Aplikasi ini dirancang sebagai platform **Manajemen Tugas (Task Management), Pelacak Bug (Bug Tracker), dan Dokumentasi Rilis/Evaluasi** yang ditujukan khusus bagi tim IT Developer. 

Aplikasi mengusung filosofi:
> *"Input Task Bebas Kapan Saja, Pelaporan Fleksibel Multi-Periode, dan Kolaborasi Tanpa Sekat."*

#### 📌 5 Pilar Desain Utama:
1. **Navigasi Sidebar Tunggal (Anti-Slop):** Menghindari navigasi bertingkat yang rumit. Semua menu utama dapat diakses langsung dari sidebar kiri.
2. **Hierarki 3 Tingkat Terstruktur:** `Project (Aplikasi)` ➔ `Modul / Menu / Form` ➔ `Task & Bug`.
3. **Papan Kanban Visual Interaktif:** 4 kolom status alur kerja (*To Do*, *In Progress*, *Review & Test*, *Done*) dengan dukungan *Drag & Drop* native dan tombol navigasi cepat.
4. **Fitur Ambil Alih 1-Klik (*Take Over / Claim*):** Memungkinkan pengalihan tugas antar-developer secara instan dengan pencatatan riwayat serah terima otomatis (*audit trail handover logs*).
5. **Analisis Masalah Mendalam (Root Cause & Solution):** Pencatatan bug tidak hanya status buka/tutup, melainkan mencakup *Keterangan Kendala*, *Akar Masalah (Root Cause)*, dan *Solusi Teknis*.

---

### 2. STRUKTUR HIERARKI & MODEL AKSES
#### A. Struktur Hierarki Sistem
Setiap pekerjaan tidak ditaruh sembarangan, melainkan dikelompokkan secara teratur:
* **Level 1 - Project / Aplikasi:** Sistem atau aplikasi induk (contoh: `INTRA` - Portal Intranet, `HRIS` - Sistem Kepegawaian, `ASSET` - Inventaris IT).
* **Level 2 - Modul / Form / Menu:** Fitur spesifik di dalam project tersebut (contoh: *Modul Autentikasi & SSO*, *Modul Keuangan*, *Modul Cetak Laporan*).
* **Level 3 - Task & Bug:** Pekerjaan konkret atau error yang terjadi pada modul terkait (contoh: *Task: Optimasi query kasir*, *Bug: Error 500 Out of Memory saat cetak mutasi*).

#### B. Model Akses Pengguna (*Flat Access Collaboration*)
* **Akses Setara & Transparan:** Semua developer inti memiliki hak yang sama untuk menginput tugas, memperbarui status Kanban, mencatat bug, dan mengklaim tugas.
* **Personalisasi "Task Saya":** Ketika developer login, sistem memprioritaskan daftar pekerjaan yang menjadi tanggung jawabnya (*Task Saya*), namun tetap menyediakan filter *Semua Tim* untuk transparansi kerja tim.

#### C. Mekanisme Pelaporan Dinamis
Laporan dapat difilter secara fleksibel berdasarkan kebutuhan audit atau rapat:
* **Harian:** Aktivitas harian masuk dan selesai.
* **Mingguan:** Progres 7 hari kalender berjalan.
* **Bulanan:** Rekapitulasi target dan pencapaian bulanan.
* **Custom Rentang Tanggal:** Bebas menentukan *Start Date* dan *End Date*.

---

### 3. KEBUTUHAN FUNGSIONAL UTAMA (10 FITUR INTI / FR)

| Kode FR | Nama Fitur | Deskripsi Singkat & Kegunaan |
| :--- | :--- | :--- |
| **FR-01** | **Master Data Project** | Mengelola daftar aplikasi (nama, kode unik seperti `INTRA`, URL Git repositori, deskripsi, warna tema, status aplikasi). |
| **FR-02** | **Master Data Modul / Menu** | Mengelola modul-modul fungsional di bawah setiap project untuk memetakan histori error per komponen. |
| **FR-03** | **Master Data Developer (Akun)** | Manajemen akun developer, inisial avatar, spesialisasi (*role title*), dan status aktif/nonaktif akun. |
| **FR-04** | **Pencatatan Task Manajemen** | Form pencatatan tugas baru secara bebas (*ad-hoc*) lengkap dengan tipe task, target tanggal (*due date*), spesifikasi, dan catatan kendala (*blocker*). |
| **FR-05** | **Papan Alur Kerja Kanban Board** | Papan kerja visual 4 kolom (*To Do*, *In Progress*, *Review & Test*, *Done*) dengan fitur *Drag & Drop*, tombol geser cepat, dan filter per Project & Developer. |
| **FR-06** | **Monitoring Task Outstanding & Overdue** | Filter otomatis mendeteksi tugas yang melewati batas tanggal (*overdue*) dengan penanda warna visual dan catatan kendala. |
| **FR-07** | **Bug Tracker & History per Modul** | Pencatatan kendala/error lengkap dengan tingkat keparahan (*Severity: Critical, High, Medium, Low*), pelapor, kronologi kendala, **Akar Masalah (Root Cause)**, dan **Solusi Teknis**. |
| **FR-08** | **Log Riwayat Repository & Release** | Dokumentasi rilis kode ke server (*tag* versi seperti `v1.4.2`, *branch*, *commit hash*, dan ringkasan *changelog*). |
| **FR-09** | **Evaluasi Aplikasi & Tindak Lanjut** | Dokumentasi review operasional aplikasi (isu server, keluhan user) serta fitur **1-Klik Konversi** rekomendasi menjadi Task baru. |
| **FR-10** | **Fitur Ambil Alih (*Take Over / Claim*)** | Tombol 1-klik bagi developer lain untuk mengambil alih tugas/bug yang ditinggalkan rekan setimnya dengan pencatatan *handover logs*. |

---

### 4. DETAIL FITUR KHUSUS

#### 1. Mekanisme Ambil Alih (*Take Over / Claim*)
* Tombol **[ ✋ Ambil Alih ]** muncul pada kartu Kanban atau tabel tugas bila PIC tugas tersebut bukan akun yang sedang login.
* Ketika diklik, dialog konfirmasi muncul untuk mencegah ketidaksengajaan.
* Setelah konfirmasi:
  1. Kolom `assigned_to` otomatis berganti ke user yang mengklaim.
  2. Riwayat transfer dicatat ke kolom JSON `handover_logs` (mencatat siapa PIC lama, siapa PIC baru, waktu serah terima, dan catatan).
  3. Tugas otomatis masuk ke tab *Task Saya* milik pengembang baru.

#### 2. Siklus Penanganan Bug (Akar Masalah & Solusi)
* Form Bug menyediakan area khusus:
  * **Akar Masalah (*Root Cause*):** Analisa teknis kenapa error bisa terjadi (misal: kebocoran memori, query tanpa pagination, validasi frontend kurang). Dapat diisi kapan saja tanpa menunggu bug berstatus selesai.
  * **Solusi Teknis / Rencana Tindakan:** Solusi konkret yang diterapkan untuk memperbaiki bug.
* Status penanganan: `Open` ➔ `In Progress` ➔ `Resolved`.

---

### 5. STRUKTUR DATA (DATABASE SCHEMA - 8 TABEL INTI)

Dokumen mendefinisikan 8 tabel relasional yang saling terhubung:
1. **`users`**: Menyimpan akun pengguna, email, role/spesialisasi (*Fullstack Lead, Backend, Frontend*), inisial avatar, warna avatar, dan status `is_active`.
2. **`projects`**: Menyimpan data aplikasi induk, kode unik (`code`), tautan repositori Git (`repo_url`), status (`active`, `maintenance`, `archived`), dan warna badge.
3. **`modules`**: Terhubung ke `projects.id`. Menyimpan modul fungsional, nama menu/form, dan deskripsinya.
4. **`tasks`**: Terhubung ke `projects`, `modules`, `created_by` (pembuat awal), dan `assigned_to` (PIC aktif). Memuat judul, deskripsi, tipe task, status 4 alur, target tanggal, tanggal tuntas riil (`completed_at`), `blocker_notes`, dan JSON `handover_logs`.
5. **`bugs`**: Terhubung ke `projects`, `modules`, `created_by`, dan `assigned_to`. Memuat judul error, kronologi kendala, pelapor, tanggal lapor, `severity`, `status`, `root_cause`, `solution_notes`, `resolved_at`, dan JSON `handover_logs`.
6. **`repo_histories`**: Terhubung ke `projects`. Memuat tanggal rilis, `release_tag` (versi), `branch_name`, `commit_hash`, dan rangkuman perubahan (`summary_changes`).
7. **`app_evaluations`**: Terhubung ke `projects`. Memuat tanggal evaluasi, periode review, kendala operasional server/traffic, masukan user, dan kesimpulan review.
8. **`action_items`**: Terhubung ke `app_evaluations`. Berisi butir-butir rencana tindak lanjut perbaikan, PIC, target tanggal, status, dan `converted_task_id` (ID task bila telah dikonversi ke tabel tasks).

---

### 6. KEBUTUHAN NON-FUNGSIONAL (NFR)
1. **Kecepatan & Performa:** Penyaringan data tanggal dan modul ditargetkan **< 0.8 detik** dengan bantuan pengindeksan database pada foreign keys dan kolom tanggal.
2. **Responsivitas Antarmuka:** Desain rapi berbasis Tailwind CSS, navigasi sidebar tetap di sebelah kiri, ramah layar tablet dan desktop.
3. **Interaktivitas:** Perpindahan status kartu Kanban berlangsung instan tanpa reload halaman penuh (*Drag & Drop native*).
4. **Audit Trail & Keandalan:** Tidak ada tugas yang berpindah tanpa tercatat siapa yang memindahkan dan kapan dipindahkan.
5. **Kemudahan Cetak / Ekspor:** Cetak PDF rapi dengan aturan `@media print` yang otomatis menyembunyikan sidebar dan tombol-tombol aksi.

---

### 7. RENCANA TAHAPAN PENGEMBANGAN (SPRINT ROADMAP)
* **Sprint 1:** Finalisasi Master Data (*Project*, *Modul*, *Developer*) dan skema database relasional 8 tabel.
* **Sprint 2:** Implementasi Papan Kanban Drag & Drop interaktif dan fitur Ambil Alih (*Take Over*).
* **Sprint 3:** Bug Tracker per modul dengan dokumentasi *Root Cause* dan modul evaluasi aplikasi.
* **Sprint 4:** Mesin pelaporan multi-periode dinamis, ekspor PDF/Excel, dan *User Acceptance Testing (UAT)*.