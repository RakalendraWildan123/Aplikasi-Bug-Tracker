# DOKUMEN CHECKLIST PENGUJIAN APLIKASI (QA TEST PLAN & REPORT)
## APLIKASI MONITORING & MANAJEMEN IT DEVELOPER (BUG TRACKER)

---

### 📋 INFORMASI PENGUJIAN
* **Nama Aplikasi:** Aplikasi Monitoring & Manajemen IT Developer (Bug Tracker)
* **Nomor Dokumen SRS:** SRS-ITDEV-2026-001-REV07 (Versi 2.0)
* **Nama Penguji (Tester):** Rakalendra Wildan
* **Tanggal Pengujian:** 23 November
* **Lingkungan Uji (Environment):** Local Development (PHP 8.2+ / Laravel 12 / SQLite / Chrome / Edge)
* **URL Aplikasi:** `http://127.0.0.1:8000`

---

### 🎯 PANDUAN PENGUJIAN
1. **Status Pengujian:**
   * `[PASS]` : Fitur berjalan normal sesuai ekspektasi tanpa ada kendala.
   * `[FAIL]` : Terjadi error, fungsi macet, data salah, atau tampilan rusak.
2. **Kriteria Error:**
   * **Client Error:** Muncul teks merah pada *Browser Console (F12)* atau request berstatus `4xx`/`5xx` di tab *Network*.
   * **Server Error:** Terdapat log exception baru pada file `storage/logs/laravel.log`.
   * **UI/UX Error:** Tampilan berantakan, teks tumpang tindih, atau layout rusak.

---

## 1. MODUL AUTENTIKASI & MANAJEMEN AKUN

| No | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status | Catatan / Temuan |
| :-: | :--- | :--- | :--- | :-: | :--- |
| 1.1 | Login Sukses (Positive Test) | 1. Buka `/login`<br>2. Masukkan email & password benar (`admin@bugtracker.test` / `password`)<br>3. Klik tombol Masuk | Berhasil masuk dan dialihkan ke Dashboard. Sesi login aktif. |PASS|
| 1.2 | Login Password Salah (Negative Test) | 1. Masukkan password sembarangan<br>2. Klik tombol Masuk | Sistem menolak login dan menampilkan pesan error peringatan. Tidak crash. |PASS|
| 1.3 | Login Form Kosong (Negative Test) | 1. Kosongkan email dan password<br>2. Klik tombol Masuk | Muncul pesan validasi form bahwa email & password wajib diisi. |PASS|
| 1.4 | Akun Dinonaktifkan | 1. Login dengan akun yang statusnya nonaktif (`is_active = 0`) | Sistem menolak akses dan memberi tahu bahwa akun dinonaktifkan. |PASS|
| 1.5 | Edit Profil & Ganti Nama | 1. Buka menu Akun Saya (`/account/edit`)<br>2. Ubah Nama Lengkap<br>3. Masukkan password saat ini (`current_password`)<br>4. Klik Simpan | Nama profil berhasil diperbarui, muncul notifikasi sukses. |PASS|
| 1.6 | Edit Profil Tanpa Password Lama | 1. Ubah profil tanpa mengisi password saat ini<br>2. Klik Simpan | Sistem menolak perubahan dan meminta konfirmasi password saat ini. |PASS|
| 1.7 | Logout Sistem | 1. Klik menu / tombol Logout | Sesi keluar, halaman kembali ke form login, tombol back browser tidak membocorkan data. |PASS|

---

## 2. MODUL DASHBOARD & METRIK INTERAKTIF

| No | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status | Catatan / Temuan |
| :-: | :--- | :--- | :--- | :-: | :--- |
| 2.1 | Tampilan Ringkasan Metrik | 1. Buka `/dashboard`<br>2. Amati 4 kartu metrik utama | Kartu menampilkan angka valid: Total Task Aktif, Bug Terbuka, Akun Aktif, dan Proyek Aktif. |PASS|
| 2.2 | Buka Modal Akun Aktif | 1. Klik pada kartu **Akun Aktif** di Dashboard | Muncul popup modal berisi daftar nama seluruh akun tim. |PASS|
| 2.3 | Filter Tab Status pada Modal | 1. Klik tab **Semua**<br>2. Klik tab **Aktif**<br>3. Klik tab **Nonaktif** | Daftar akun terfilter secara instan. Akun nonaktif tampil dengan penanda abu-abu/offline. | PASS|
| 2.4 | Fitur Pencarian Akun pada Modal | 1. Ketik nama tertentu di kolom cari modal (misal: "Raka")<br>2. Ketik nama yang tidak ada | Hasil langsung tersaring real-time. Jika tidak ada, muncul pesan "Tidak ditemukan". |PASS|
| 2.5 | Tutup Modal Akun | 1. Klik tombol [ × ] atau klik di luar area modal | Modal menutup dengan mulus dan mengembalikan fokus layar ke Dashboard. |PASS|

---

## 3. MODUL PAPAN ALUR KERJA (KANBAN BOARD)

| No | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status | Catatan / Temuan |
| :-: | :--- | :--- | :--- | :-: | :--- |
| 3.1 | Tampilan 4 Kolom Alur | 1. Buka menu Papan Kanban (`/kanban`) | Menampilkan 4 kolom lengkap: To Do, In Progress, Review, dan Done. |PASS|
| 3.2 | Drag & Drop Kartu Antar Kolom | 1. Klik & tahan kartu pada To Do<br>2. Geser (drag) dan lepas (drop) ke In Progress | Kartu berpindah kolom seketika tanpa reload halaman. Status di database terupdate. |PASS|
| 3.3 | Tombol Geser Cepat [ ◀ ] dan [ ▶ ] | 1. Klik tombol panah kanan pada kartu To Do<br>2. Klik tombol panah kiri | Kartu berpindah ke kolom berikutnya atau sebelumnya secara instan. |PASS|
| 3.4 | Filter Kanban per Proyek | 1. Pilih proyek tertentu pada dropdown filter atas | Hanya kartu tugas dari proyek terpilih yang ditampilkan pada papan. |PASS|
| 3.5 | Filter Kanban per Developer | 1. Pilih nama developer pada dropdown PIC | Hanya tugas milik developer tersebut yang muncul di papan. |PASS|
| 3.6 | Kartu Berstatus Selesai (Done) | 1. Pindahkan kartu ke kolom Done | Kartu bertanda selesai dan sistem merekam stempel waktu selesai (`completed_at`). |PASS|
| 3.7 | Indikator Overdue | 1. Amati kartu yang target tanggalnya sudah lewat | Muncul badge peringatan Overdue/Terlambat dengan penanda warna merah. |PASS|

---

## 4. MODUL MANAJEMEN TUGAS (TASKS) & AMBIL ALIH

| No | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status | Catatan / Temuan |
| :-: | :--- | :--- | :--- | :-: | :--- |
| 4.1 | Tambah Tugas Baru (Normal) | 1. Buka `/tasks`<br>2. Klik Tambah Task<br>3. Pilih Proyek, Modul, PIC, Judul, Tgl Mulai & Target<br>4. Klik Simpan | Tugas baru berhasil disimpan dan muncul pada tabel serta papan Kanban. |PASS|
| 4.2 | Validasi Form Task Kosong | 1. Buka form Tambah Task<br>2. Langsung klik Simpan tanpa mengisi data | Validasi muncul, field wajib ditandai merah, form tidak submit. |PASS|
| 4.3 | Edit & Update Data Task | 1. Klik tombol Edit pada salah satu task<br>2. Ubah judul atau catatan kendala (blocker)<br>3. Klik Perbarui | Perubahan berhasil tersimpan dan langsung terlihat pada tabel. | PASS|
| 4.4 | Fitur Ambil Alih (Take Over) | 1. Cari task yang PIC-nya bukan akun Anda<br>2. Klik tombol **[ ✋ Ambil Alih ]**<br>3. Konfirmasi pada dialog pop-up | PIC tugas otomatis berganti menjadi akun Anda, tercatat di `handover_logs`. |PASS|
| 4.5 | Hapus Task | 1. Klik tombol Hapus pada task milik sendiri<br>2. Konfirmasi penghapusan | Hanya orang yang mengerjakan task yang dapat menghapusnya. Task terhapus dari daftar dan database secara permanen. |PASS|

---

## 5. MODUL PELACAK BUG (BUG TRACKER)

| No | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status | Catatan / Temuan |
| :-: | :--- | :--- | :--- | :-: | :--- |
| 5.1 | Lapor Bug Baru | 1. Buka menu Pelacak Bug (`/bugs`)<br>2. Klik Laporkan Bug<br>3. Pilih Proyek, Modul, Severity, Judul, Kronologi Kendala<br>4. Klik Simpan | Bug baru tercatat dengan status awal `Open` dan severity sesuai pilihan. |PASS|
| 5.2 | Dropdown Modul Dinamis | 1. Pilih Proyek A ➔ periksa isi dropdown Modul<br>2. Ganti pilihan ke Proyek B | Pilihan dropdown Modul otomatis menyesuaikan hanya modul milik Proyek B. |PASS|
| 5.3 | Input Severity Berbeda | 1. Coba opsi: Critical, High, Medium, Low | Badge severity tampil dengan warna yang sesuai: Kritis (Merah), Tinggi (Oranye), Sedang (Kuning), Rendah (Abu-abu). |PASS|
| 5.4 | Pengisian Akar Masalah (Root Cause) | 1. Buka modal edit/detail bug<br>2. Isi kolom teks **Akar Masalah (Root Cause)**<br>3. Klik Simpan Solusi | Analisis teknis tersimpan permanen dan dapat ditinjau kembali kapan saja. | [ ] PASS<br>[ ] FAIL | |
| 5.5 | Pengisian Solusi Teknis & Resolve | 1. Isi kolom **Solusi Teknis**<br>2. Ubah status menjadi `Resolved`<br>3. Klik Simpan | Status bug berubah menjadi Selesai (Resolved) dan stempel `resolved_at` terisi. | [ ] PASS<br>[ ] FAIL | |
| 5.6 | Filter Bug per Severity & Status | 1. Filter status `Open`<br>2. Filter severity `Critical` | Hanya bug yang memenuhi kriteria filter yang tampil pada tabel. | [ ] PASS<br>[ ] FAIL | |

---

## 6. MODUL EVALUASI APLIKASI & TINDAK LANJUT

| No | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status | Catatan / Temuan |
| :-: | :--- | :--- | :--- | :-: | :--- |
| 6.1 | Tampilan Daftar Evaluasi | 1. Buka menu Evaluasi Aplikasi (`/evaluations`) | Tabel menampilkan daftar evaluasi sistem berkala per proyek. Layout rapi. | [ ] PASS<br>[ ] FAIL | |
| 6.2 | Tambah Evaluasi Baru | 1. Klik Tambah Evaluasi<br>2. Isi Proyek, Periode, Kendala Operasional, Feedback Pengguna<br>3. Tambahkan Action Item rencana tindakan<br>4. Simpan | Data evaluasi dan action items tersimpan dengan benar. | [ ] PASS<br>[ ] FAIL | |
| 6.3 | Konversi 1-Klik Action Item ke Task | 1. Pada baris Action Item, klik tombol **Konversi ke Task** | Action item langsung berubah menjadi Task baru di daftar tugas dan Kanban. | [ ] PASS<br>[ ] FAIL | |

---

## 7. MODUL RIWAYAT RILIS & REPOSITORI

| No | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status | Catatan / Temuan |
| :-: | :--- | :--- | :--- | :-: | :--- |
| 7.1 | Catat Rilis Versi Baru | 1. Buka menu Riwayat Rilis (`/releases`)<br>2. Klik Catat Rilis<br>3. Isi Tag Versi (misal: `v1.5.0`), Branch (`main`), Commit Hash, Changelog<br>4. Simpan | Data rilis baru tampil pada timeline riwayat deployment sistem. | [ ] PASS<br>[ ] FAIL | |
| 7.2 | Filter Rilis per Proyek | 1. Pilih filter proyek tertentu | Riwayat rilis tersaring khusus untuk aplikasi yang dipilih. | [ ] PASS<br>[ ] FAIL | |

---

## 8. MODUL LAPORAN PERIODIK & EKSPOR CETAK

| No | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status | Catatan / Temuan |
| :-: | :--- | :--- | :--- | :-: | :--- |
| 8.1 | Filter Preset Harian | 1. Buka menu Laporan Periodik (`/reports`)<br>2. Pilih Preset: **Harian** | Sistem menampilkan ringkasan data aktivitas hari ini. | [ ] PASS<br>[ ] FAIL | |
| 8.2 | Filter Preset Mingguan | 1. Pilih Preset: **Mingguan** | Data merangkum progress 7 hari berjalan. | [ ] PASS<br>[ ] FAIL | |
| 8.3 | Filter Preset Bulanan | 1. Pilih Preset: **Bulanan** | Data merangkum metrik capaian bulan ini. | [ ] PASS<br>[ ] FAIL | |
| 8.4 | Filter Custom Rentang Tanggal | 1. Masukkan Start Date dan End Date khusus<br>2. Klik Terapkan | Sistem menampilkan agregasi data sesuai rentang tanggal yang dipilih. | [ ] PASS<br>[ ] FAIL | |
| 8.5 | Uji Mode Cetak PDF / Print | 1. Klik tombol **Cetak / PDF** atau tekan `Ctrl + P` | Preview cetak rapi, **sidebar kiri dan tombol aksi otomatis hilang** (@media print). | [ ] PASS<br>[ ] FAIL | |

---

## 9. MODUL MASTER DATA (ADMINISTRATOR)

| No | Skenario Uji | Langkah Pengujian | Hasil yang Diharapkan | Status | Catatan / Temuan |
| :-: | :--- | :--- | :--- | :-: | :--- |
| 9.1 | Tambah Master Proyek | 1. Buka `/projects`<br>2. Tambah proyek baru dengan kode unik (`DEMO1`) | Proyek baru tersimpan dan tersedia pada seluruh dropdown proyek. | [ ] PASS<br>[ ] FAIL | |
| 9.2 | Validasi Duplikat Kode Proyek | 1. Coba tambah proyek lain dengan kode yang sama (`DEMO1`) | Ditolak oleh sistem dengan pesan error bahwa kode proyek sudah digunakan. | [ ] PASS<br>[ ] FAIL | |
| 9.3 | Tambah Master Modul | 1. Buka `/modules`<br>2. Tambah modul baru di bawah Proyek tertentu | Modul baru tersimpan dan terhubung dengan relasi project_id yang benar. | [ ] PASS<br>[ ] FAIL | |
| 9.4 | Kelola Master Akun Pengguna | 1. Buka `/admin/accounts`<br>2. Tambah akun developer baru atau aktifkan/nonaktifkan akun | Status akun diperbarui, akun yang dinonaktifkan tidak dapat login. | [ ] PASS<br>[ ] FAIL | |

---

## 10. NON-FUNCTIONAL REQUIREMENTS (PERFORMA & RESPONSIF)

| No | Skenario Uji | Parameter Uji | Standar Target | Hasil Pengujian | Status |
| :-: | :--- | :--- | :-: | :--- | :-: |
| 10.1 | Kecepatan Navigasi Halaman | Pindah antar menu (Dashboard ➔ Kanban ➔ Tasks ➔ Bugs) | < 0.8 detik | Prefetch & Speculation Rules aktif | [ ] PASS<br>[ ] FAIL |
| 10.2 | Tampilan Tablet (Responsif) | Buka pada mode tablet / perkecil ukuran window browser | Tampilan adaptif | Sidebar rapi, kartu Kanban tetap mudah digeser | [ ] PASS<br>[ ] FAIL |
| 10.3 | Keamanan Input (Anti-XSS) | Masukkan karakter `<script>alert(1)</script>` pada nama task | Teks diescape | Tidak memicu pop-up script eksternal | [ ] PASS<br>[ ] FAIL |
| 10.4 | Konsistensi Browser Console | Cek Console F12 di seluruh 11 halaman utama | 0 Error Merah | Bebas dari Uncaught TypeError / SyntaxError | [ ] PASS<br>[ ] FAIL |

---

### 📊 REKAPITULASI HASIL AKHIR PENGUJIAN

* **Total Skenario Uji:** 40 Butir Uji
* **Jumlah Berhasil (PASS):** _____ Butir
* **Jumlah Gagal (FAIL):** _____ Butir
* **Persentase Keberhasilan:** _____%

**Kesimpulan Penguji:**
[ ] Aplikasi **LAYAK (READY)** untuk deployment / presentasi sidang PKL.  
[ ] Aplikasi **MEMERLUKAN PERBAIKAN** pada butir catatan di atas.

**Tanda Tangan Penguji:**

( ________________________________ )