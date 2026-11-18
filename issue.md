# Planning: Inisialisasi Project Laravel 12 & Integrasi Stack Utama

## Ringkasan & Tujuan
Membuat fondasi project baru menggunakan **Laravel 12** di direktori kerja ini dengan integrasi frontend ringan (**Vue 3 Standalone**, **Tailwind CSS**, **HTML5 Drag & Drop API**), database **MySQL**, **Redis** untuk caching ekspor, serta **Snappy** sebagai engine ekspor dokumen.

Dokumen ini disusun secara *high-level* sebagai panduan implementasi awal agar seluruh dependensi terpasang, terkonfigurasi, dan terverifikasi siap pakai sebelum pengembangan fitur bisnis dimulai.

---

## Tech Stack & Dependensi Utama
- **Backend Framework:** Laravel 12
- **Frontend:**
  - Vue 3 (Standalone — tanpa arsitektur SPA penuh / Inertia)
  - Tailwind CSS
  - HTML5 Drag & Drop API (native browser API, tanpa library pihak ketiga)
- **Database:** MySQL
- **Cache (Caching Ekspor):** Redis
- **Export Engine:** Laravel Snappy (`wkhtmltopdf` / `wkhtmltoimage`)

---

## Tahapan Implementasi (High-Level)

### 1. Inisialisasi Project Laravel 12
- Buat project Laravel 12 langsung di folder workspace ini.
- Pastikan direktori `.git` yang sudah ada tidak terhapus atau tertimpa selama proses instalasi.
- Lakukan setup dasar (`APP_KEY`, struktur direktori default, dan konfigurasi environment dasar).

### 2. Konfigurasi Database (MySQL)
- Ubah konfigurasi koneksi database default ke **MySQL** melalui `.env` dan `config/database.php`.
- Pastikan database target tersedia dan migrasi bawaan Laravel dapat dijalankan tanpa error.

### 3. Setup Frontend (Vue 3 Standalone + Tailwind CSS + HTML5 Drag & Drop API)
- Pastikan **Tailwind CSS** terkonfigurasi dan dapat di-build dengan baik bersama asset bundler bawaan Laravel 12 (Vite).
- Integrasikan **Vue 3** dalam mode *standalone* sehingga dapat di-mount langsung pada halaman Blade secara modular.
- Sediakan satu kerangka/komponen uji sederhana yang menggabungkan Vue 3, styling Tailwind CSS, dan **HTML5 Drag & Drop API** secara native (tanpa library eksternal seperti SortableJS/vuedraggable) untuk memastikan interaksi berjalan mulus.

### 4. Konfigurasi Redis untuk Caching Ekspor
- Pasang dan konfigurasi driver/client **Redis** yang kompatibel dengan environment PHP yang digunakan (`predis/predis` atau ekstensi `phpredis`).
- Atur koneksi Redis pada `.env` dan konfigurasi cache Laravel.
- Rancang struktur/service caching sederhana khusus untuk kebutuhan **caching hasil/data ekspor** (mencakup penamaan cache key yang jelas dan pengaturan TTL/kedaluwarsa).

### 5. Integrasi Engine Ekspor (Snappy)
- Pasang package Laravel Snappy (`barryvdh/laravel-snappy`) dan publikasikan file konfigurasinya.
- Pastikan binary `wkhtmltopdf` (dan `wkhtmltoimage` bila dibutuhkan) tersedia serta path binary dikonfigurasi secara fleksibel melalui `.env` agar mendukung environment Windows maupun Linux.
- Hubungkan proses ekspor Snappy dengan layer caching Redis (jika hasil ekspor tersedia di cache Redis, sajikan dari cache; jika belum, generate via Snappy lalu simpan ke Redis).

### 6. Dokumentasi Environment & Kebersihan Repository
- Perbarui `.env.example` dengan seluruh variabel tambahan yang dibutuhkan (MySQL, Redis, path binary Snappy).
- Pastikan file sensitif/lokal (`.env`, `vendor/`, `node_modules/`) tetap diabaikan oleh `.gitignore`.
- Tambahkan catatan setup singkat di `README.md` terkait prasyarat environment (versi PHP, MySQL, Redis server, dan instalasi binary Snappy).

---

## Batasan & Catatan untuk Implementor
- **Fokus pada fondasi:** Tidak perlu membangun fitur bisnis penuh pada tahap ini; cukup sediakan halaman dan endpoint uji (*smoke test*) untuk memvalidasi integrasi setiap dependensi.
- **Native Drag & Drop:** Wajib menggunakan HTML5 Drag & Drop API bawaan browser, bukan library eksternal.
- **Konfigurasi Portabel:** Hindari *hardcode* path absolut (terutama untuk binary Snappy); gunakan environment variable.

---

## Kriteria Selesai (Definition of Done)
- [ ] Project Laravel 12 terpasang rapi di root folder tanpa mengganggu `.git`.
- [ ] Koneksi MySQL berhasil dan migrasi default berjalan lancar.
- [ ] Halaman uji frontend berhasil me-render Vue 3 standalone + Tailwind CSS dengan fungsi HTML5 Drag & Drop API yang bekerja.
- [ ] Koneksi Redis aktif dan mekanisme caching ekspor (cache miss -> generate -> cache hit) terverifikasi.
- [ ] Engine Snappy berhasil menghasilkan file ekspor PDF tanpa error path/binary.
- [ ] `.env.example` dan instruksi setup singkat telah diperbarui.
