# Planning: Implementasi Tabel `developers` (Migration, Model, Factory, Seeder & Test)

## Ringkasan & Tujuan
Membuat tabel database untuk menyimpan data IT Developer pada project **Laravel 12 + MySQL**, lengkap dengan Migration, PHP Enum, Eloquent Model, Factory, Seeder, dan pengujian otomatis.

Dokumen ini dirancang langkah demi langkah agar dapat langsung diimplementasikan oleh **junior programmer** atau **model AI yang lebih kecil** dengan risiko kesalahan minimal.

---

## Spesifikasi Skema Tabel

> **Catatan Konvensi Nama:** Gunakan nama tabel plural **`developers`** (standar konvensi Laravel Eloquent agar otomatis terhubung dengan model `Developer` dan konsisten dengan tabel `users`, `jobs`, `cache`). Seluruh nama kolom **wajib menggunakan `snake_case` huruf kecil**.  
> *(Jika kebutuhan eksternal/ERD mewajibkan nama tabel singular `developer`, ganti nama tabel di migrasi menjadi `developer` dan tambahkan `protected $table = 'developer';` pada Model `Developer`.)*

| Nama Kolom | Tipe SQL | Panjang / Nilai | Constraint / Default | Method Migration Laravel | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Auto Increment | `PRIMARY KEY` | `$table->id();` | ID unik developer |
| `name` | `VARCHAR` | `150` | `NOT NULL` | `$table->string('name', 150);` | Nama lengkap developer |
| `email` | `VARCHAR` | `150` | `UNIQUE`, `NOT NULL` | `$table->string('email', 150)->unique();` | Email unik developer |
| `role_title` | `ENUM` | `'Fullstack Lead'`, `'Backend'`, `'Frontend'`, `'Mobile'` | `NOT NULL` | `$table->enum('role_title', DeveloperRole::values());` | Peran/posisi developer |
| `avatar` | `VARCHAR` | `10` | `NOT NULL` | `$table->string('avatar', 10);` | Inisial avatar (mis. `RW`, `JD`) |
| `avatar_bg` | `VARCHAR` | `50` | `NOT NULL` | `$table->string('avatar_bg', 50);` | Warna/class background avatar (mis. `#4F46E5` atau `bg-indigo-600`) |
| `is_active` | `BOOLEAN` (`TINYINT(1)`) | - | `DEFAULT TRUE` (`1`) | `$table->boolean('is_active')->default(true);` | Status aktif developer |
| `created_at` & `updated_at` | `TIMESTAMP` | - | `NULLABLE` | `$table->timestamps();` | Audit timestamp standar Laravel |

---

## Tahapan Implementasi (Step-by-Step)

### Tahap 1: Buat PHP Backed Enum untuk `role_title`
1. Buat file baru `app/Enums/DeveloperRole.php` dengan tipe `enum DeveloperRole: string`.
2. Definisikan 4 case dengan nilai string **persis** sesuai spesifikasi:
   - `FullstackLead = 'Fullstack Lead'`
   - `Backend = 'Backend'`
   - `Frontend = 'Frontend'`
   - `Mobile = 'Mobile'`
3. Tambahkan helper static method `values(): array` yang mengembalikan daftar string value (misal `array_column(self::cases(), 'value')`) agar bisa dipakai ulang di migration, factory, dan validasi tanpa duplikasi string.

### Tahap 2: Buat File Migration Tabel `developers`
1. Jalankan perintah artisan:
   ```bash
   php artisan make:migration create_developers_table
   ```
2. Pada method `up()`, buat tabel `developers` dengan seluruh kolom pada tabel spesifikasi di atas:
   - Pastikan panjang `VARCHAR` ditentukan eksplisit (`150`, `150`, `10`, `50`), **jangan** membiarkan default 255.
   - Pastikan `email` diberi constraint `->unique()`.
   - Pastikan `role_title` menggunakan nilai enum dari `DeveloperRole::values()`.
   - Pastikan `is_active` memiliki `->default(true)`.
   - Sertakan `$table->timestamps();`.
3. Pada method `down()`, pastikan berisi `Schema::dropIfExists('developers');` agar rollback bersih.

### Tahap 3: Buat Eloquent Model `Developer`
1. Jalankan perintah artisan:
   ```bash
   php artisan make:model Developer
   ```
2. Di `app/Models/Developer.php`:
   - Gunakan trait `HasFactory`.
   - Definisikan `$fillable`: `['name', 'email', 'role_title', 'avatar', 'avatar_bg', 'is_active']`.
   - Definisikan method `casts(): array` (konvensi Laravel 12):
     - `'role_title' => DeveloperRole::class`
     - `'is_active' => 'boolean'`
   - *(Opsional namun direkomendasikan)* Tambahkan local scope `scopeActive($query)` untuk mempermudah query developer yang aktif (`where('is_active', true)`).

### Tahap 4: Buat Factory dan Seeder
1. **Factory (`database/factories/DeveloperFactory.php`):**
   - Generate via `php artisan make:factory DeveloperFactory --model=Developer`.
   - Pastikan nilai dummy selalu memenuhi batas panjang kolom:
     - `name`: nama acak (maks 150 karakter).
     - `email`: `fake()->unique()->safeEmail()` (maks 150 karakter).
     - `role_title`: acak dari `DeveloperRole::cases()`.
     - `avatar`: inisial 2 huruf kapital dari nama (maks 10 karakter).
     - `avatar_bg`: pilih acak dari daftar warna/class Tailwind (maks 50 karakter, mis. `bg-indigo-600`, `bg-emerald-600`, `bg-amber-600`, `bg-rose-600`).
     - `is_active`: `true`.
   - Tambahkan state helper `inactive()` pada factory untuk kebutuhan testing developer non-aktif.
2. **Seeder (`database/seeders/DeveloperSeeder.php`):**
   - Generate via `php artisan make:seeder DeveloperSeeder`.
   - Isi minimal 4 data contoh yang mewakili seluruh role (`Fullstack Lead`, `Backend`, `Frontend`, `Mobile`).
   - Gunakan `Developer::updateOrCreate(['email' => ...], [...])` agar seeder bersifat **idempoten** (aman dijalankan berulang kali tanpa error duplicate email).
   - Panggil `DeveloperSeeder::class` dari `DatabaseSeeder.php` bila sesuai kebutuhan project.

### Tahap 5: Jalankan & Verifikasi Migrasi di MySQL
1. Pastikan service MySQL lokal aktif dan kredensial di `.env` sudah benar.
2. Jalankan migrasi:
   ```bash
   php artisan migrate
   ```
3. Uji rollback dan migrasi ulang untuk memastikan method `down()` bekerja bersih:
   ```bash
   php artisan migrate:rollback --step=1
   php artisan migrate
   ```
4. Jalankan seeder sebanyak 2 kali untuk memastikan idempotensi:
   ```bash
   php artisan db:seed --class=DeveloperSeeder
   php artisan db:seed --class=DeveloperSeeder
   ```
5. Cek struktur tabel di MySQL (via HeidiSQL / CLI `DESCRIBE developers;`) untuk memastikan tipe data, panjang kolom, nilai `ENUM`, index `UNIQUE` pada `email`, dan default `is_active = 1` sesuai spesifikasi.

### Tahap 6: Buat Pengujian Otomatis (Automated Test)
1. Buat file test `tests/Feature/DeveloperTableTest.php` menggunakan `RefreshDatabase`.
2. Tulis test case minimal berikut:
   - **Verifikasi Skema:** Tabel `developers` ada dan memiliki seluruh kolom (`id`, `name`, `email`, `role_title`, `avatar`, `avatar_bg`, `is_active`, `created_at`, `updated_at`).
   - **Default Value `is_active`:** Membuat record tanpa menyertakan `is_active` menghasilkan `is_active === true` di database.
   - **Constraint Unique `email`:** Mencoba menyimpan 2 developer dengan `email` sama melempar `QueryException`.
   - **Casting Model:** `role_title` ter-cast menjadi instance `DeveloperRole` dan `is_active` bertipe `bool`.
   - **Seeder Idempoten:** Menjalankan `DeveloperSeeder` dua kali menghasilkan jumlah record yang konsisten dan mencakup keempat role.
3. Jalankan seluruh test suite:
   ```bash
   php artisan test
   ```
   Pastikan seluruh test (lama maupun baru) lulus tanpa error.

---

## Hal yang Harus Dihindari (Guardrails)
- **Jangan gunakan huruf kapital pada nama kolom SQL** (`Id`, `Name`, `Role_title`, `Is_active`). Selalu gunakan `snake_case` huruf kecil.
- **Jangan lupa batas panjang `VARCHAR`** (`150`, `10`, `50`). Method `$table->string('col')` tanpa argumen kedua membuat `VARCHAR(255)`.
- **Jangan typo pada nilai `ENUM`**: pastikan persis `'Fullstack Lead'`, `'Backend'`, `'Frontend'`, `'Mobile'`.
- **Jangan memodifikasi file migrasi lama** yang sudah ada; buat file migrasi baru.
- **Jangan commit file `.env`** ke Git.

---

## Kriteria Selesai (Definition of Done)
- [ ] Enum `app/Enums/DeveloperRole.php` dibuat dengan 4 nilai role sesuai spesifikasi.
- [ ] Migration `create_developers_table` dibuat dengan seluruh kolom, panjang tipe data, `UNIQUE`, `ENUM`, dan `default(true)` yang tepat.
- [ ] Model `app/Models/Developer.php` memiliki `$fillable` dan `casts()` lengkap.
- [ ] `DeveloperFactory` dan `DeveloperSeeder` (idempoten) tersedia dan berfungsi.
- [ ] `php artisan migrate`, `migrate:rollback --step=1`, dan `db:seed` berjalan sukses tanpa error.
- [ ] Seluruh pengujian otomatis di `php artisan test` lulus (termasuk test baru untuk tabel `developers`).
