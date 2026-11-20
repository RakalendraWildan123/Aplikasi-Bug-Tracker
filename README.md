# Aplikasi Monitoring & Manajemen IT Developer

Aplikasi web terpadu untuk memonitor siklus hidup pengembangan perangkat lunak, memfasilitasi kolaborasi tugas harian tim pengembang, melacak temuan bug, mencatat repositori rilis kode, mengevaluasi kesehatan sistem pasca-rilis, serta menyajikan laporan periodik kinerja tim.

Aplikasi ini dibangun dengan prinsip antarmuka:
- **Tegas dan Bersih**: Hierarki visual terstruktur, warna status informatif, serta tipografi proporsional tanpa dekorasi berlebihan.
- **Tanpa Tab Bersarang (*No Nested Tabs*)**: Seluruh aksi formulir, peninjauan rincian, dan konfirmasi menggunakan pop-up modal mandiri sehingga alur pengguna tetap lugas dan fokus.
- **Ramah Desktop & Tablet**: Tata letak fleksibel dengan drawer sidebar responsif, panel grid adaptif, dan tabel data yang ramah sentuhan.
- **Keamanan Sesi Terpusat**: Tombol **Keluar Sesi (Logout)** dilindungi token CSRF dan hanya ditempatkan di dalam kartu profil pada halaman **Akun Saya**, menjaga sidebar tetap bersih dan mencegah keluar sesi secara tidak sengaja.

---

## 1. Tools & Teknologi yang Digunakan

Aplikasi ini memanfaatkan tumpukan teknologi modern untuk menghasilkan performa tinggi, keamanan andal, dan pengalaman pengguna yang responsif:

### A. Backend & Server
- **PHP 8.2+**: Bahasa pemrograman utama yang cepat, bertipe kuat (*strict types*), dan efisien.
- **Laravel 12.x Framework**: Kerangka kerja PHP modern berbasis MVC (*Model-View-Controller*):
  - *Routing & Middleware*: Pengaturan rute terisolasi dengan filter autentikasi sesi dan otorisasi peran (`EnsureUserHasRole`).
  - *Eloquent ORM*: Pengelolaan entitas database relasional dengan relasi integritas tinggi (*Project*, *Module*, *Task*, *Bug*, *RepoHistory*, *AppEvaluation*, *ActionItem*, *Notification*, *User*).
  - *Blade Templating Engine*: Mesin template komponen server-side yang ringan dan aman dari serangan XSS.
  - *Form Validation & CSRF Protection*: Validasi input ketat di sisi server serta proteksi token anti-CSRF pada setiap permintaan pengubahan data.
- **Laravel Snappy / Wkhtmltopdf**: Pustaka ekspor dokumen untuk konversi laporan ke format cetak PDF berpresisi tinggi.

### B. Frontend & Tampilan
- **Tailwind CSS 4.x**: Kerangka kerja styling berbasis utility-first modern dengan `@tailwindcss/vite` untuk kompilasi CSS instan dan ukuran aset produksi yang sangat optimal.
- **Vite 6.x**: Alat bundling aset modern generasi terbaru untuk kompilasi frontend kilat dan *Hot Module Replacement* (HMR).
- **Vanilla JavaScript (ES6+)**:
  - Menggunakan API peramban modern (*Fetch API*) untuk interaksi asinkron tanpa memuat ulang seluruh halaman (*Optimistic UI update* di bawah 0.8 detik).
  - *Native HTML5 Drag and Drop API*: Digunakan pada Papan Kanban untuk memindahkan kartu tugas antar kolom status secara natural.
  - Bebas dari ketergantungan library monolitik berat, menjaga halaman tetap ringan dan cepat dimuat.
- **Heroicons / SVG Icons**: Ikonografi vektor yang tajam pada semua resolusi layar tanpa membebani bandwidth.

### C. Basis Data & Penyimpanan
- **MySQL / MariaDB / SQLite**: Sistem manajemen basis data relasional dengan integritas foreign key cascade/set-null yang terjamin.
- **Laravel Migrations & Seeders**: Pengelolaan skema database terkontrol versi (*version-controlled*) dan penyedia data demo siap pakai.
- **Laravel File Storage**: Penyimpanan berkas foto profil pengguna di direktori publik terlindungi.

### D. Pengujian & Kualitas Kode (*Quality Assurance*)
- **PHPUnit 11.x**: Suite pengujian otomatis komprehensif mencakup *Feature Tests* dan *Unit Tests* (lebih dari 600 skenario pengujian sukses).
- **Artisan CLI**: Manajemen perintah pengujian (`php artisan test`), migrasi, dan pengelolaan antrean background.
- **Git & GitHub**: Sistem kontrol versi untuk pelacakan cabang (*branching*), peninjauan kode, dan riwayat commit.

---

## 2. Gambaran Umum Cara Kerja Aplikasi

Aplikasi menghubungkan seluruh siklus operasional tim pengembang teknologi informasi dalam satu alur yang terintegrasi:

1. **Inisialisasi Data Master (Admin Only)**:
   - Administrator mendaftarkan proyek baru dan kode uniknya (misal: `PAYROLL`, `HRIS`).
   - Administrator memecah proyek ke dalam modul-modul fungsional (misal: *Modul Presensi*, *Modul Autentikasi*).
   - Administrator mengelola akun pengembang, memberikan kredensial, dan mengatur status keaktifan akun.
2. **Perencanaan & Distribusi Tugas**:
   - Tim mendefinisikan tugas pengembangan (fitur, peningkatan, refaktor, atau maintenance) yang dikaitkan ke proyek dan modul terkait.
   - Tugas ditugaskan ke salah satu pengembang penanggung jawab (*Assignee*).
3. **Pelaksanaan Kerja pada Papan Kanban**:
   - Pengembang memantau dan menggeser tugas dari *To Do*, *In Progress*, *Review*, hingga *Done*.
   - **Aturan Otorisasi Kanban**: Hanya pengembang yang ditugaskan pada tugas tersebut yang berhak memindahkan kartu tugas. Pengembang lain dapat menggunakan tombol *Ambil Alih Tugas* jika ingin mengambil tanggung jawab pengerjaan tugas tersebut.
4. **Pelacakan Bug & Dokumentasi Solusi**:
   - Masalah atau cacat sistem dilaporkan dengan modul dan tingkat keparahan (*Low*, *Medium*, *High*, *Critical*).
   - Pengembang yang menangani bug mendokumentasikan analisis akar masalah teknis (*root cause*) dan langkah solusi pada modal khusus.
5. **Pencatatan Riwayat Rilis Kode**:
   - Setiap publikasi versi aplikasi ke lingkungan staging atau production dicatat lengkap dengan tag versi, nama branch, dan hash commit Git.
   - Riwayat rilis bersifat *immutable* (tidak dapat diubah atau dihapus) guna menjamin keaslian audit trail rekayasa perangkat lunak.
6. **Evaluasi Pasca-Rilis & Rencana Tindak Lanjut**:
   - Tim melakukan tinjauan berkala atas stabilitas sistem pasca-rilis.
   - Rencana aksi perbaikan (*action items*) dibuat dan dipantau hingga selesai atau dikonversi kembali menjadi tugas Kanban baru.
7. **Monitoring Terpadu & Pelaporan Kinerja**:
   - **Dashboard**: Menyajikan 4 metrik ringkasan tercepat, peringatan kendala blocker, temuan bug kritis, dan rilis terbaru.
   - **Laporan Periodik**: Menghasilkan metrik rasio penyelesaian tugas, resolusi bug, rekapitulasi performa per proyek, serta ekspor data ke format CSV dan cetak PDF.
   - **Pop-up Notifikasi**: Menampilkan pembaruan terkini secara instan saat terdapat tugas baru, kendala blocker, atau bug kritis.

---

## 3. Peran Pengguna & Kredensial Demo

| Peran (*Role*) | Email | Kata Sandi | Deskripsi Hak Akses |
| :--- | :--- | :--- | :--- |
| **Administrator (Atasan IT / PM)** | `admin@bugtracker.test` | `password` | Akses penuh ke seluruh fitur: manajemen akun pengguna, master proyek, master modul, seluruh fitur operasional, dan hak ambil alih tugas/bug. |
| **Developer (Fullstack Lead)** | `raka.pratama@bugtracker.test` | `password` | Akses operasional: mengelola tugas yang ditugaskan, memindahkan kartu tugas Kanban miliknya, menangani bug & solusi, mencatat rilis, input evaluasi, dan melihat laporan. |
| **Developer (Backend)** | `dina.kusuma@bugtracker.test` | `password` | Akses operasional developer: menangani tugas backend, debugging isu teknis, dan memperbarui rencana tindak lanjut evaluasi. |

---

## 4. Struktur Menu Navigasi

Navigasi sidebar dirancang bersih, tegas, dan adaptif terhadap peran pengguna:

```
[Sidebar Navigasi Aplikasi]
|-- 1. FITUR OPERASIONAL (Dapat diakses oleh Admin & Developer)
|   |-- Dashboard             (/dashboard atau /)
|   |-- Papan Kanban          (/kanban)
|   |-- Daftar Tugas          (/tasks)
|   |-- Pelacak Bug           (/bugs)
|   |-- Riwayat Rilis         (/releases)
|   |-- Evaluasi Aplikasi     (/evaluations)
|   |-- Laporan Periodik      (/reports)
|   `-- Pop-up Notifikasi     (Ikon Lonceng di Topbar / Header)
|
|-- 2. FITUR MASTER DATA (Khusus Administrator - Tersembunyi untuk Developer)
|   |-- Master Proyek         (/projects)
|   |-- Master Modul          (/modules)
|   `-- Master Akun           (/admin/accounts)
|
`-- 3. PENGATURAN
    `-- Akun Saya             (/account - Pusat Profil, Password, & Tombol Logout)
```

---

## 5. Cara Kerja Rinci dari Setiap Fitur

### 5.1. Fitur Master Data (Khusus Administrator)

#### A. Master Proyek (`/projects`)
- **Tujuan**: Mengelola payung aplikasi atau sistem yang sedang dibangun dan dipelihara.
- **Cara Kerja**:
  1. Halaman hanya dapat diakses oleh peran Admin. Pengembang yang mencoba mengakses URL ini akan menerima status HTTP 403 Forbidden.
  2. Tombol **Tambah Proyek** membuka modal form untuk menginput nama proyek, kode unik (otomatis huruf kapital, misal: `PAYROLL`), warna identitas, repositori Git, dan status (*Aktif*, *Pemeliharaan*, atau *Diarsipkan*).
  3. Tabel utama menampilkan rekapitulasi jumlah modul yang terhubung, tugas aktif, dan temuan bug per proyek.
  4. Pengeditan dan penghapusan proyek dilakukan melalui modal pop-up konfirmasi tanpa berpindah halaman.

#### B. Master Modul (`/modules`)
- **Tujuan**: Membagi proyek menjadi unit-unit fitur spesifik (misal: *Modul Pembayaran*, *Modul Notifikasi Push*).
- **Cara Kerja**:
  1. Halaman dilindungi middleware khusus Admin.
  2. Tombol **Tambah Modul** membuka modal untuk memilih proyek induk terkait, mengisi nama modul, serta deskripsi ruang lingkup fungsional.
  3. Menyediakan bilah pencarian dan filter cepat per proyek induk untuk memudahkan pengecekan modul tertentu.
  4. Modul yang terdaftar menjadi pilihan wajib saat pengembang membuat tugas atau melaporkan temuan bug.

#### C. Master Akun Pengguna (`/admin/accounts`)
- **Tujuan**: Manajemen akun tim pengembang dan kontrol hak akses terpusat oleh Atasan IT.
- **Cara Kerja**:
  1. Admin dapat mendaftarkan akun baru (Admin atau Developer) melalui tombol **Tambah Pengguna Baru**.
  2. **Ubah Status Aktif (*Toggle Active*)**: Admin dapat menonaktifkan akun dengan satu klik tombol toggle. Akun yang nonaktif langsung ditolak saat mencoba masuk ke sistem.
  3. **Reset Kata Sandi (*Reset Password*)**: Admin dapat mengatur ulang password pengguna langsung melalui modal konfirmasi tanpa perlu mengetahui password lama.
  4. **Proteksi Akun Sendiri**: Sistem secara otomatis mencegah Admin untuk menonaktifkan atau menghapus akun yang sedang digunakannya sendiri (*anti-lockout guard*).

---

### 5.2. Fitur Operasional (Admin & Developer)

#### A. Dashboard Utama Terpadu (`/dashboard` atau `/`)
- **Tujuan**: Menyajikan ringkasan visual kesehatan proyek, status tugas, temuan bug mendesak, dan versi rilis terbaru dalam satu pandangan tanpa tab bersarang.
- **Cara Kerja**:
  1. **4 Kartu Metrik Ringkas**:
     - *Proyek Aktif*: Jumlah proyek aktif dari seluruh proyek terdaftar.
     - *Tugas Berjalan*: Jumlah tugas yang sedang berada di tahap *In Progress*, *To Do*, dan *Review*.
     - *Bug Perlu Ditangani*: Total bug berstatus aktif dengan penanda khusus jika ada tingkat keparahan *Critical*.
     - *Rilis Kode Terkini*: Tag versi rilis terbaru beserta nama proyek dan tanggal deployment.
  2. **Peringatan Kendala (*Blocker Alert*)**: Menampilkan daftar tugas yang mengalami hambatan teknis secara mencolok dengan kutipan kendala dan tombol pintas ke Papan Kanban.
  3. **Daftar Tugas Terkini**: Tabel 5 tugas aktif terbaru lengkap dengan prioritas, penanggung jawab, dan status pengerjaan.
  4. **Pintasan Cepat Operasional**: Tombol akses satu klik ke fitur Kanban, Tugas, Bug, dan Laporan.
  5. **Tautan Khusus Admin**: Tautan *Kelola Proyek* pada kartu metrik hanya ditampilkan jika pengguna yang masuk berstatus Admin.

#### B. Papan Kanban Interaktif (`/kanban`)
- **Tujuan**: Mengelola alur pengerjaan tugas secara visual dan real-time melalui 4 kolom tahapan (*To Do*, *In Progress*, *Review*, *Done*).
- **Cara Kerja & Aturan Otorisasi**:
  1. **Pembatasan Hak Pemindahan Kartu**:
     - **Tugas yang Sedang Dikerjakan Sendiri**: Kartu memiliki kursor grab, drag-and-drop aktif, tombol panah navigasi kiri/kanan aktif, dan dropdown status cepat aktif.
     - **Tugas Milik Rekan Lain / Belum Ditugaskan**: Drag-and-drop dinonaktifkan (`draggable="false"`), tombol panah navigasi dinonaktifkan (`disabled`), dan dropdown status dinonaktifkan dengan tooltip keterangan.
     - **Validasi Backend (403 Forbidden)**: Endpoint `/kanban/tasks/{task}/status` memvalidasi otorisasi pengguna secara ketat di sisi server. Jika pengguna yang bukan pemilik tugas mencoba mengubah status, server menolak permintaan dengan status 403.
  2. **Tombol Ambil Alih Tugas (*Takeover*)**:
     - Pada kartu tugas milik orang lain atau tugas yang belum ditugaskan, tersedia tombol **Ambil Alih Tugas**.
     - Setelah konfirmasi pada modal pop-up, penugasan tugas berpindah ke pengguna tersebut dan kontrol pemindahan kartu langsung aktif seketika tanpa perlu reload halaman.
  3. **Garis Indikator Warna Status**: Sisi kiri setiap kartu tugas memiliki garis warna tegas sesuai kolom statusnya (Abu-abu untuk To Do, Biru untuk In Progress, Kuning untuk Review, Hijau untuk Done).
  4. **Navigasi Cepat Kolom**: Tombol navigasi cepat di bagian atas memungkinkan pengguna melompat atau menyorot kolom tertentu (sangat membantu pada layar tablet dan desktop).
  5. **Filter Instan Client-side**: Filter instan berdasarkan Proyek dan Developer menyaring kartu dalam waktu kurang dari 10 milidetik.
  6. **Modal Edit Tugas Tanpa Reload**: Klik tombol pensil pada kartu membuka modal pop-up untuk mengedit judul, deskripsi, deadline, penugasan, dan catatan kendala blocker.

#### C. Daftar Tugas Tabular (`/tasks`)
- **Tujuan**: Inventarisasi seluruh tugas pengembangan dalam bentuk tabel data untuk monitoring kuantitatif.
- **Cara Kerja**:
  1. Menyajikan ringkasan metrik total tugas, tugas selesai, dalam pengerjaan, dan tugas tertunda.
  2. Filter pencarian multi-kriteria: pencarian nama tugas, filter proyek, filter status, dan filter tipe tugas (*Feature*, *Enhancement*, *Refactor*, *Maintenance*).
  3. Form tambah dan edit tugas memungkinkan penentuan target tanggal penyelesaian (*deadline*) dan deskripsi secara mendalam.

#### D. Pelacak Bug (`/bugs`)
- **Tujuan**: Mencatat temuan cacat sistem, mengukur tingkat keparahan, dan mendokumentasikan analisis perbaikan.
- **Cara Kerja**:
  1. **Modal Terpisah yang Tegas**:
     - **Detail**: Memeriksa deskripsi bug, modul terkait, pelapor, dan tingkat keparahan (*Low*, *Medium*, *High*, *Critical*).
     - **Solusi**: Modal khusus untuk mencatat analisis akar penyebab (*root cause*) serta langkah perbaikan yang diterapkan (*solution notes*).
     - **Ambil Alih Bug (*Takeover*)**: Mengalihkan penanganan bug ke pengembang yang sedang aktif melakukan perbaikan.
  2. **Lencana Status Solusi**: Kolom solusi secara otomatis menampilkan badge informatif (*Sudah Ada Solusi* atau *Menunggu Analisis*).

#### E. Riwayat Rilis & Modul Repo (`/releases`)
- **Tujuan**: Buku log audit trail atas setiap deployment kode sistem dan penandaan tag repositori Git.
- **Cara Kerja**:
  1. Pengembang mencatat rilis baru dengan mengisi proyek, tag versi (misal: `v2.1.0`), branch repositori (misal: `main`), commit hash Git (7-40 karakter), dan rangkuman perubahan.
  2. Commit hash otomatis menjadi tautan langsung menuju halaman commit di repositori Git terkait (GitHub/GitLab).
  3. **Prinsip Imutabilitas Data (*Immutable / Append-Only*)**:
     - Untuk memenuhi standar audit kepatuhan IT, data rilis yang sudah tercatat **tidak dapat diedit maupun dihapus**.
     - Rute controller memproteksi aksi `PUT` dan `DELETE` dengan status `403 Forbidden` sehingga catatan rilis bersifat permanen.

#### F. Evaluasi Aplikasi & Rencana Tindak Lanjut (`/evaluations`)
- **Tujuan**: Evaluasi berkala atas performa dan kestabilan sistem pasca-rilis untuk mendeteksi kendala operasional secara proaktif.
- **Cara Kerja**:
  1. Pengguna mencatat evaluasi dengan memilih proyek, periode review (misal: `Sprint 18` atau `Q1 2026`), tanggal evaluasi, ringkasan kendala operasional, dan masukan pengguna.
  2. **Rencana Tindak Lanjut (*Action Items*)**:
     - Setiap tinjauan evaluasi dapat memiliki beberapa item rencana tindak lanjut.
     - Melalui modal *Detail & Tindak Lanjut*, tim dapat menambahkan rencana aksi baru lengkap dengan PIC pengembang dan target selesai.
     - Status rencana aksi dipantau melalui 3 tahapan: *Planned*, *Converted to Task*, dan *Completed*.

#### G. Laporan Periodik Komprehensif (`/reports`)
- **Tujuan**: Laporan rekapitulasi produktivitas dan kinerja pengembangan dalam rentang waktu yang ditentukan.
- **Cara Kerja**:
  1. **Pilihan Periode Instan**: Tombol cepat untuk rentang waktu *Semua Data*, *7 Hari Terakhir*, *30 Hari Terakhir*, *Bulan Ini*, *Kuartal Ini*, dan *Tahun Ini*.
  2. **Filter Rentang Kustom**: Menyediakan input tanggal mulai, tanggal selesai, filter proyek, dan filter pengembang penanggung jawab.
  3. **Kartu Metrik Kinerja Periode**:
     - Persentase rasio penyelesaian tugas (*Task Completion Rate*).
     - Persentase rasio resolusi bug (*Bug Resolution Rate*).
     - Total rilis kode yang dipublikasikan pada periode terkait.
     - Progres penyelesaian rencana tindak lanjut evaluasi sistem.
  4. **Tabel Rekapitulasi Produktivitas**: Menampilkan progres tugas, bug, dan rilis per proyek secara komparatif.
  5. **Ekspor CSV & Format Siap Cetak**:
     - Tombol *Ekspor CSV* mengunduh rekapitulasi data tabel secara instan.
     - Tombol *Cetak / Simpan PDF* memanfaatkan stylesheet cetak khusus `@media print` yang menyembunyikan sidebar dan tombol navigasi untuk menghasilkan lembar laporan bersih siap cetak atau simpan ke PDF.

#### H. Pop-up Notifikasi Terpadu (`/notifications`)
- **Tujuan**: Memberikan pembaruan cepat atas kejadian penting (tugas baru, kendala blocker, temuan bug kritis, dan rilis kode baru).
- **Cara Kerja**:
  1. Diakses melalui ikon lonceng pada topbar/header dengan badge counter yang menampilkan jumlah notifikasi yang belum dibaca.
  2. Pop-up modal dirancang datar satu layar tanpa tab bersarang, dilengkapi tombol filter cepat (*Semua* dan *Belum Dibaca*).
  3. Fitur interaksi instan:
     - *Tandai Semua Dibaca*: Mengubah seluruh notifikasi menjadi terbaca dalam satu klik.
     - *Tandai Satuan*: Menandai notifikasi tertentu telah ditinjau.
     - *Hapus Satuan / Bersihkan Semua*: Menghapus notifikasi dari daftar tampilan.
     - *Tautan Langsung*: Mengklik kartu notifikasi mengarahkan pengguna langsung ke modul terkait (Kanban, Bug, atau Rilis).

---

### 5.3. Pengaturan Profil & Keamanan Akun

#### Akun Saya (`/account`)
- **Tujuan**: Manajemen identitas diri, foto profil, dan kata sandi bagi pengguna yang sedang aktif.
- **Cara Kerja**:
  1. **Tampilan Utama Read-Only**: Informasi profil (nama, email, peran, status akun, dan foto profil) disajikan dalam format kartu ringkasan bersih untuk mencegah salah klik edit.
  2. **Modal Ubah Profil Terverifikasi**:
     - Mengubah nama, email, atau mengunggah foto profil baru wajib memasukkan kata sandi saat ini (*current password*) sebagai lapis verifikasi keamanan.
     - Modal pop-up tidak akan tertutup saat mengklik area luar modal (backdrop), mencegah kehilangan input yang belum tersimpan secara tidak sengaja.
  3. **Modal Ubah Kata Sandi**:
     - Formulir pergantian password mandiri dengan validasi kecocokan konfirmasi dan minimal 8 karakter.
  4. **Pusat Tombol Logout Terproteksi**:
     - Tombol **Keluar Sesi (Logout)** ditempatkan secara eksklusif pada kartu ringkasan profil di halaman ini dan dilindungi token CSRF.
     - Tombol logout sengaja tidak diletakkan di sidebar global guna mematuhi standar desain minimalis dan menghindari insiden sesi terputus secara tidak disengaja.

---

## 6. Panduan Instalasi & Menjalankan Aplikasi di Lokal

### Prasyarat Sistem:
- PHP >= 8.2 & Composer
- Node.js >= 20 & npm
- MySQL / MariaDB Server

### Langkah Menjalankan:
1. **Clone repositori dan masuk ke direktori proyek**:
   ```bash
   cd "c:/PKL/Aplikasi Monitoring & Manajemen IT Developer"
   ```
2. **Instal dependensi backend PHP**:
   ```bash
   composer install
   ```
3. **Instal dependensi frontend Node.js**:
   ```bash
   npm install
   ```
4. **Konfigurasi Environment**:
   Salin file konfigurasi environment dan buat encryption key aplikasi:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   *Pastikan pengaturan database pada file `.env` telah disesuaikan dengan konfigurasi MySQL/MariaDB lokal Anda.*
5. **Jalankan Migrasi Database & Seeder Data Demo**:
   ```bash
   php artisan migrate:fresh --seed
   ```
6. **Kompilasi Aset Frontend dengan Vite**:
   ```bash
   npm run build
   ```
7. **Jalankan Server Pengembangan Lokal**:
   ```bash
   php artisan serve --port=8000
   ```
8. **Buka Aplikasi pada Peramban Web**:
   Kunjungi [http://localhost:8000/login](http://localhost:8000/login) dan masuk menggunakan kredensial demo (misal: `admin@bugtracker.test` atau `raka.pratama@bugtracker.test` dengan password `password`).

---

## 7. Pengujian Otomatis (*Automated Testing*)

Aplikasi dilengkapi suite pengujian otomatis komprehensif menggunakan PHPUnit untuk memastikan seluruh fungsi bisnis, otorisasi peran, dan antarmuka berjalan sempurna:

```bash
php artisan test
```

### Rangkuman File Pengujian Fitur Utama:
- `KanbanBoardTest.php` (Pengujian Papan Kanban, otorisasi pemindahan kartu khusus pemilik tugas, penolakan 403 untuk non-assignee, navigasi panah, dan alur ambil alih tugas).
- `ProjectUiTest.php` (Pengujian Master Proyek, proteksi 403 untuk peran pengembang, dan CRUD penuh untuk administrator).
- `ModuleUiTest.php` (Pengujian Master Modul, proteksi 403 untuk peran pengembang, dan pemetaan relasi proyek).
- `DashboardUiTest.php` (Pengujian metrik utama, peringatan kendala blocker, temuan bug kritis, dan rilis terbaru).
- `PeriodicReportUiTest.php` (Pengujian Laporan Periodik, filter rentang waktu instan, rekap proyek, dan ekspor CSV).
- `AppEvaluationUiTest.php` (Pengujian evaluasi aplikasi berkala dan rencana tindak lanjut action items).
- `ReleaseHistoryUiTest.php` (Pengujian riwayat rilis, audit trail deployment kode, dan imutabilitas tanpa edit/delete).
- `AdminAccountUiTest.php` (Pengujian Master Akun Admin, aktivasi toggle akun, dan reset password).
- `BugUiTest.php` (Pengujian pelacak bug, pemisahan modal solusi teknis, dan serah terima bug).
- `TaskUiTest.php` (Pengujian inventaris tugas tabular dan filter multi-kriteria).
- `NotificationUiTest.php` (Pengujian pop-up notifikasi terpadu, aksi tandai dibaca, dan penghapusan).
- `UiComprehensiveScenariosTest.php` (Pengujian terpadu 110 skenario antarmuka, tata letak bebas tab bersarang, dan lokasi tombol logout).
