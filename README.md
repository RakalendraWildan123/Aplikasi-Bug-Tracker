# Aplikasi Monitoring & Manajemen IT Developer

Aplikasi web terpadu untuk memonitor siklus pengembangan perangkat lunak, memfasilitasi kolaborasi tugas harian tim pengembang, melacak temuan bug, mengelola repositori rilis, mengevaluasi performa sistem, serta menyajikan laporan periodik kinerja tim.

Antarmuka pengguna (*User Interface*) dibangun dengan prinsip:
- **Tegas dan Bersih**: Hierarki visual terstruktur tanpa dekorasi berlebihan.
- **Tanpa Tab Bersarang (*No Nested Tabs*)**: Menggunakan modal interaktif mandiri untuk seluruh aksi formulir dan manipulasi data.
- **Ramah Desktop & Tablet**: Tata letak fleksibel dengan dukungan *collapsible sidebar drawer* dan tabel responsif.
- **Keamanan Sesi Terpusat**: Tombol **Logout** dilindungi dan hanya dapat diakses melalui kartu profil pada halaman **Akun Saya**, menjaga sidebar tetap bersih dan mencegah keluar sesi secara tidak sengaja.

---

## 1. Peran Pengguna & Kredensial Demo

Sistem menyediakan 2 tingkatan peran (*roles*) dengan akun bawaan seeder:

| Peran (*Role*) | Email | Kata Sandi | Deskripsi Hak Akses |
| :--- | :--- | :--- | :--- |
| **Administrator (Atasan IT / PM)** | `admin@bugtracker.test` | `password` | Akses penuh: manajemen akun seluruh pengguna, master data, seluruh fitur operasional, serta hak *takeover* tugas/bug. |
| **Developer (Fullstack Lead)** | `raka.pratama@bugtracker.test` | `password` | Mengelola tugas, melacak bug & solusi, mencatat rilis repositori, menginput evaluasi aplikasi, dan memfilter laporan periodik. |
| **Developer (Backend)** | `dina.kusuma@bugtracker.test` | `password` | Menangani tugas backend, analisis akar masalah bug (*root cause*), dan tindak lanjut evaluasi sistem. |

---

## 2. Struktur Menu & Hierarki Navigasi

Navigasi sidebar dikelompokkan secara terstruktur ke dalam 3 kategori:

```
[Sidebar Navigasi]
|-- 1. FITUR OPERASIONAL
|   |-- Dashboard             (/dashboard atau /)
|   |-- Papan Kanban          (/kanban)
|   |-- Daftar Tugas          (/tasks)
|   |-- Pelacak Bug           (/bugs)
|   |-- Riwayat Rilis         (/releases)
|   |-- Evaluasi Aplikasi     (/evaluations)
|   |-- Laporan Periodik      (/reports)
|   `-- Pop-up Notifikasi     (Header / Topbar Lonceng)
|
|-- 2. FITUR MASTER DATA
|   |-- Master Proyek         (/projects)
|   |-- Master Modul          (/modules)
|   `-- Master Akun           (/admin/accounts) -> Khusus Administrator
|
`-- 3. PENGATURAN
    `-- Akun Saya             (/account) -> Pusat Profil, Password, & Logout
```

---

## 3. Cara Kerja & Alur Operasional dari Setiap Fitur

### 3.1. Fitur Master Data

#### A. Master Proyek (`/projects`)
- **Tujuan**: Mengelola data induk payung proyek sistem yang dikembangkan oleh tim IT.
- **Cara Kerja**:
  1. Klik tombol **Tambah Proyek** pada header halaman untuk membuka modal form.
  2. Masukkan **Nama Proyek**, **Kode Proyek** (otomatis diubah menjadi huruf kapital, misal: `HRIS`, `KEU`), warna badge identitas, status siklus proyek (*Aktif*, *Pemeliharaan*, atau *Diarsipkan*), serta URL repositori Git.
  3. Tabel utama menampilkan rekapitulasi jumlah modul yang terhubung, tugas aktif, dan temuan bug per proyek secara otomatis.
  4. Pengguna dapat memperbarui rincian proyek melalui tombol **Edit** atau menghapus proyek melalui tombol **Hapus** dengan dialog konfirmasi aman.

#### B. Master Modul (`/modules`)
- **Tujuan**: Memecah proyek menjadi komponen fitur fungsional yang spesifik (misal: *Autentikasi & SSO*, *Laporan Keuangan*, *Presensi GPS*).
- **Cara Kerja**:
  1. Klik tombol **Tambah Modul**.
  2. Pilih proyek induk terkait, beri nama modul, dan tulis deskripsi cakupan teknis modul.
  3. Modul yang terdaftar menjadi referensi wajib saat tim developer membuat tugas pengembangan baru maupun saat tim QA melaporkan bug.
  4. Halaman dilengkapi filter instan berdasarkan **Proyek Induk** dan bilah pencarian untuk memudahkan inventarisasi modul per aplikasi.

#### C. Master Akun (`/admin/accounts` — Khusus Administrator)
- **Tujuan**: Pengelolaan identitas pengguna aplikasi, penetapan peran, dan kontrol akses secara terpusat oleh Atasan IT.
- **Cara Kerja**:
  1. Admin dapat mendaftarkan akun baru (Developer atau Admin) melalui tombol **Tambah Pengguna Baru**.
  2. **Ubah Status Aktif (*Toggle Active*)**: Admin dapat mengaktifkan atau menonaktifkan akun dengan satu klik. Akun yang dinonaktifkan akan langsung ditolak saat mencoba masuk ke sistem.
  3. **Reset Kata Sandi (*Reset Password*)**: Admin dapat mengatur ulang kata sandi pengguna langsung melalui modal konfirmasi tanpa perlu mengetahui kata sandi lama pengguna.
  4. **Proteksi Akun Sendiri**: Sistem secara otomatis mencegah Admin untuk menonaktifkan atau menghapus akun miliknya sendiri guna menghindari insiden penguncian akses (*lockout*).

---

### 3.2. Fitur Operasional

#### A. Dashboard Utama Terpadu (`/dashboard` atau `/`)
- **Tujuan**: Menyajikan ringkasan metrik kesehatan proyek, status tugas berjalan, temuan bug mendesak, serta versi rilis terkini dalam satu layar komprehensif tanpa tab bersarang.
- **Cara Kerja**:
  1. **Ringkasan 4 Kartu Metrik Utama**:
     - *Proyek Aktif*: Jumlah proyek yang sedang berjalan dari total proyek yang terdaftar.
     - *Tugas Berjalan*: Total tugas berstatus *In Progress* dengan rincian jumlah tugas *To Do* dan *Review*.
     - *Bug Perlu Ditangani*: Total bug berstatus *Open* atau *In Progress*, disertai penanda merah jika terdapat bug dengan tingkat keparahan kritis (*Critical*).
     - *Rilis Kode Terkini*: Versi tag rilis terbaru yang telah dipublikasikan beserta nama proyek dan tanggal rilisnya.
  2. **Banner Peringatan Kendala (*Blocker Alert*)**: Menampilkan daftar tugas yang mengalami kendala teknis secara langsung dengan kutipan catatan blocker dan tautan instan ke Papan Kanban.
  3. **Aktivitas Tugas Terkini**: Daftar 5 tugas aktif terbaru lengkap dengan kode proyek, tingkat prioritas, status tahapan, dan pengembang yang ditugaskan (*PIC*).
  4. **Pintasan Cepat Operasional**: Akses satu klik langsung ke Papan Kanban, Daftar Tugas, Pelacak Bug, dan Laporan Periodik.
  5. **Temuan Bug Mendesak & Evaluasi**: Pemetaan bug kritis/tinggi teratas dan rencana tindak lanjut perbaikan (*action items*) yang masih aktif.

#### B. Papan Kanban (`/kanban`)
- **Tujuan**: Visualisasi alur pengerjaan tugas tim pengembang secara interaktif dan real-time.
- **Cara Kerja**:
  1. Kartu tugas terdistribusi ke dalam 4 kolom tahapan:
     - **To Do**: Tugas baru yang telah didefinisikan dan siap dikerjakan.
     - **In Progress**: Tugas yang sedang aktif dikerjakan oleh developer penanggung jawab.
     - **Review**: Tugas yang menunggu evaluasi kode (*code review*) atau pengujian QA.
     - **Done**: Tugas yang telah selesai dan memenuhi kriteria penerimaan.
  2. **Pemindahan Status**: Perubahan status tugas dapat dilakukan melalui drag-and-drop antar kolom atau menggunakan dropdown aksi cepat pada kartu tugas.
  3. **Pemberitahuan Kendala (*Blocker Alert*)**: Jika tugas memiliki catatan kendala teknis, kartu akan menampilkan lencana peringatan merah mencolok (*Blocker Notes*).
  4. **Ambil Alih (*Takeover*)**: Anggota tim dapat mengambil alih tugas dari rekan lain melalui modal konfirmasi, yang secara otomatis mencatat riwayat serah terima (*handover logs*).
  5. **Filter Cepat**: Pengguna dapat menyaring kartu berdasarkan **Proyek** atau **Developer** tanpa memuat ulang seluruh halaman.

#### B. Daftar Tugas (`/tasks`)
- **Tujuan**: Penyajian seluruh inventaris tugas pengembangan dalam bentuk tabel tabular untuk monitoring kuantitatif.
- **Cara Kerja**:
  1. Menampilkan ringkasan metrik: Total Tugas, Tugas Selesai, Dalam Pengerjaan, dan Tugas Tertunda.
  2. Bilah pencarian dan filter multi-kriteria: pencarian judul tugas, filter proyek, filter status, dan filter tipe tugas (*Feature*, *Bugfix*, *Refactor*, *Documentation*).
  3. Form modal tambah dan edit tugas memungkinkan penentuan target tanggal penyelesaian (*deadline*) dan deskripsi tugas secara komprehensif.

#### C. Pelacak Bug (`/bugs`)
- **Tujuan**: Dokumentasi temuan masalah sistem, pemetaan tingkat bahaya, dan pencatatan riwayat penyelesaian bug.
- **Cara Kerja**:
  1. **Pemisahan Aksi Modal yang Tegas**:
     - Tombol **Detail**: Meninjau deskripsi temuan, pelapor, tanggal pelaporan, modul terkait, dan tingkat keparahan (*Low*, *Medium*, *High*, *Critical*).
     - Tombol **Solusi**: Modal khusus untuk mencatat analisis akar masalah teknis (*root cause*) serta langkah perbaikan yang diterapkan (*solution notes*).
     - Tombol **Ambil Alih (*Takeover*)**: Mengalihkan penanganan bug ke developer lain yang sedang aktif melakukan debugging.
  2. **Indikator Solusi Visual**: Baris data bug secara otomatis menampilkan badge status solusi (*Sudah Ada Solusi* atau *Menunggu Analisis*) agar tim mengetahui prioritas penanganan.

#### D. Riwayat Rilis & Repo Modul (`/releases`)
- **Tujuan**: Buku log audit (*audit trail*) deployment kode sistem dan tag repositori proyek.
- **Cara Kerja**:
  1. Tim mencatat rilis baru dengan mengisi proyek terkait, tag versi (misal: `v1.2.0`), branch repositori (misal: `main` atau `release/1.2`), commit hash Git (7-40 karakter), dan rangkuman perubahan (*summary changes*).
  2. Jika proyek memiliki tautan repositori Git, commit hash akan otomatis dapat diklik untuk membuka tautan commit langsung pada remote repository (GitHub/GitLab).
  3. **Prinsip Imutabilitas Data (*Immutable / Append-Only*)**:
     - Sesuai tata kelola software audit trail, riwayat rilis **tidak menyediakan tombol edit maupun hapus**.
     - Controller memproteksi aksi `PUT` dan `DELETE` dengan `abort(403)` guna memastikan riwayat rilis bersifat permanen dan tidak dapat dimanipulasi setelah dicatat.

#### E. Evaluasi Aplikasi (`/evaluations`)
- **Tujuan**: Evaluasi performa sistem pasca-rilis secara berkala untuk memetakan kendala operasional dan rencana tindak lanjut perbaikan.
- **Cara Kerja**:
  1. **Pencatatan Evaluasi**: Pengguna mencatat evaluasi berkala dengan memilih proyek, periode review (misal: `Q4 2026` atau `Sprint 24`), tanggal evaluasi, kendala operasional pasca-rilis, dan masukan pengguna/klien.
  2. **Rencana Tindak Lanjut (*Action Items*)**:
     - Setiap evaluasi dapat memiliki beberapa rencana perbaikan (*action items*).
     - Pengguna dapat membuka modal **Detail & Tindak Lanjut** untuk meninjau isu dan menambahkan *action plan* baru lengkap dengan PIC pengembang dan target tanggal selesai.
     - Status tindak lanjut dipantau melalui 3 tahapan: *Direncanakan (Planned)*, *Dikonversi ke Tugas (Converted to Task)*, dan *Selesai (Completed)*.

#### F. Laporan Periodik (`/reports`)
- **Tujuan**: Laporan rekapitulasi komprehensif atas seluruh aktivitas pengembangan dalam rentang waktu tertentu.
- **Cara Kerja**:
  1. **Preset Pilihan Periode Cepat**: Pengguna dapat memilih preset instan: *Semua Data*, *7 Hari Terakhir*, *30 Hari Terakhir*, *Bulan Ini*, *Kuartal Ini*, atau *Tahun Ini*.
  2. **Filter Rentang Tanggal Kustom**: Menyediakan input tanggal mulai dan tanggal selesai, serta opsi filter per proyek dan per developer.
  3. **Kartu Metrik Kinerja Periode**:
     - Rasio penyelesaian tugas (% *Task Completion Rate*).
     - Rasio resolusi bug (% *Bug Resolution Rate*).
     - Jumlah rilis kode pada periode terpilih.
     - Progres tindak lanjut evaluasi aplikasi yang telah selesai.
  4. **Tabel Rekapitulasi Proyek**: Membandingkan produktivitas tugas, tingkat penyelesaian bug, dan rilis antar proyek dalam bentuk visual progress bar.
  5. **Ekspor Data & Cetak**:
     - **Ekspor CSV**: Mengunduh rekapitulasi data periodik instan dalam format CSV (UTF-8 BOM).
     - **Cetak / Simpan PDF**: Dilengkapi stylesheet `@media print` khusus yang secara otomatis menyembunyikan sidebar dan kontrol navigasi, menghasilkan tampilan laporan bersih siap cetak atau simpan ke PDF.

#### G. Pop-up Notifikasi Terpadu (`/notifications`)
- **Tujuan**: Memberikan pembaruan instan atas aktivitas operasional penting (tugas baru/kendala, temuan bug kritis/tinggi, serta publikasi rilis) secara ringkas tanpa mengganggu alur kerja.
- **Cara Kerja**:
  1. **Akses Cepat (Lonceng Notifikasi)**: Tombol pemicu notifikasi tersedia di header desktop maupun topbar tablet/mobile dilengkapi badge jumlah notifikasi yang belum dibaca (*unread counter badge*).
  2. **Tampilan Bersih & Tanpa Tab Bersarang**: Modal pop-up dirancang ringkas satu layar tanpa tab bersarang, hanya menggunakan tombol filter datar (*Semua* dan *Belum Dibaca*) serta opsi *Hapus Semua*.
  3. **Aksi Cepat & Navigasi**:
     - **Tandai Semua Dibaca**: Mengubah seluruh status notifikasi menjadi terbaca secara instan.
     - **Tandai Dibaca Satuan**: Tombol centang pada kartu notifikasi untuk menandai telah ditinjau.
     - **Hapus Satuan**: Menghapus item notifikasi tertentu dari daftar tampilan.
     - **Tautan Langsung**: Tautan langsung pada setiap item untuk langsung menuju fitur terkait (Papan Kanban, Pelacak Bug, atau Riwayat Rilis).

---

### 3.3. Pengaturan & Keamanan Akun

#### Akun Saya (`/account`)
- **Tujuan**: Manajemen profil pribadi dan autentikasi bagi pengguna yang sedang aktif.
- **Cara Kerja**:
  1. **Tampilan Aman Read-Only**: Informasi profil (nama, email, peran, dan foto profil) disajikan secara bersih untuk menghindari pengeditan yang tidak disengaja.
  2. **Modal Edit Profil Terverifikasi**: Untuk mengubah nama, email, atau foto profil, pengguna wajib memasukkan kata sandi saat ini (*current password*) sebagai lapisan verifikasi keamanan.
  3. **Form Ubah Kata Sandi Mandiri**: Formulir pergantian password terpisah dengan validasi minimal 8 karakter dan konfirmasi kata sandi baru.
  4. **Pusat Tombol Logout Aman**: Tombol keluar sesi (*Logout*) terintegrasi khusus pada kartu ringkasan profil sisi kiri halaman ini (dilindungi token CSRF). Tombol logout tidak diletakkan pada sidebar global untuk mencegah insiden sesi keluar tidak sengaja.

---

## 4. Panduan Menjalankan Aplikasi di Lokal

### Prasyarat:
- PHP >= 8.2 & Composer
- Node.js >= 20 & npm
- MySQL / MariaDB Server

### Langkah Menjalankan:
1. **Instal dependensi backend & frontend**:
   ```bash
   composer install
   npm install
   ```
2. **Setup Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
3. **Migrasi Database & Seeder**:
   ```bash
   php artisan migrate:fresh --seed
   ```
4. **Build Aset Frontend**:
   ```bash
   npm run build
   ```
5. **Jalankan Server Lokal**:
   ```bash
   php artisan serve --port=8000
   ```
6. Buka peramban di [http://localhost:8000/login](http://localhost:8000/login).

---

## 5. Pengujian Otomatis (*Automated Testing*)

Aplikasi dilengkapi suite pengujian otomatis komprehensif menggunakan PHPUnit:

```bash
php artisan test
```

### Cakupan Pengujian Fitur UI Utama:
- `DashboardUiTest.php` (Dashboard utama terpadu, 4 metrik ringkas, blocker alert, bebas tab bersarang)
- `PeriodicReportUiTest.php` (Laporan Periodik, filter waktu, rekap proyek, ekspor CSV)
- `AppEvaluationUiTest.php` (Evaluasi Aplikasi, kendala operasional, action items)
- `ReleaseHistoryUiTest.php` (Riwayat Rilis, audit trail, imutabilitas data tanpa edit/delete)
- `ModuleUiTest.php` (Master Modul & relasi proyek)
- `ProjectUiTest.php` (Master Proyek, status siklus, kode unik)
- `AdminAccountUiTest.php` (Master Akun Admin, toggle status, reset password)
- `BugUiTest.php` (Pelacakan bug, solusi terpisah, takeover)
- `TaskUiTest.php` & `KanbanBoardTest.php` (Manajemen tugas, Papan Kanban, handover logs)
- `NotificationUiTest.php` (Pop-up notifikasi, filter flat Semua/Belum Dibaca, aksi mark read/delete)
- `UiComprehensiveScenariosTest.php` & `UiFeaturesUnitTest.php` (Validasi UI, navigasi bebas tab bersarang, proteksi tombol logout)
