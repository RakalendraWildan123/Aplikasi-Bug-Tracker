# Planning: Fitur Login, RBAC (Atasan / Admin & Developer), dan Manajemen Akun (Enum Langsung di Database)

## Ringkasan & Tujuan
Membangun sistem autentikasi (**Login & Logout**) serta **Role-Based Access Control (RBAC)** dengan dua peran utama:
- **Atasan Developer (`admin`):** Mengelola akun-akun pengguna & developer (membuat akun baru, mengubah profil/role, mengaktifkan/menonaktifkan akses login, mereset password) serta memantau seluruh sistem.
- **Developer (`developer`):** Login untuk mengakses aplikasi, mengerjakan task/bug, dan memperbarui status pekerjaan.

Arsitektur:
- **Tanpa tabel baru & tanpa file migration tambahan:** Tetap menggunakan tabel `users` bawaan Laravel 12 dengan menambahkan kolom `role` dan `is_active` langsung pada `0001_01_01_000000_create_users_table.php`, serta menambahkan `user_id` (`nullable`, `unique`, FK -> `users.id`, `nullOnDelete()`) langsung pada `2026_11_18_035514_create_developers_table.php`.
- **Enum disatukan langsung di skema database:** Seluruh file PHP Enum terpisah di `app/Enums/` dihapus; nilai enum didefinisikan langsung pada kolom `ENUM(...)` di masing-masing migration dan diakses sebagai string biasa dengan konstanta referensi pada Model terkait.

---

## Perubahan Skema Database

### 1. Tabel `users` (`0001_01_01_000000_create_users_table.php`)
| Nama Kolom | Tipe SQL | Nilai / Default | Constraint | Method Migration Laravel | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `role` | `ENUM` | `'admin'`, `'developer'` | `NOT NULL`, `DEFAULT 'developer'`, `INDEX` | `$table->enum('role', ['admin', 'developer'])->default('developer')->index();` | Peran akses aplikasi |
| `is_active` | `BOOLEAN` | `DEFAULT TRUE` | `NOT NULL` | `$table->boolean('is_active')->default(true);` | Status aktif akses login |

### 2. Tabel `developers` (`2026_11_18_035514_create_developers_table.php`)
| Nama Kolom | Tipe SQL | Referensi | Constraint | Method Migration Laravel | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `user_id` | `BIGINT UNSIGNED` | FK -> `users.id` | `NULLABLE`, `UNIQUE`, `ON DELETE SET NULL` | `$table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();` | Relasi 1-to-1 ke akun login di `users` |

---

## Tahapan Implementasi
1. **Satukan Enum ke Database:** Hapus folder `app/Enums/`, gunakan nilai string literal langsung pada seluruh migration & konstanta pada masing-masing Model (`User`, `Developer`, `Project`, `Task`, `Bug`, `ActionItem`).
2. **Perbarui Migration Existing:** Gabungkan `role` & `is_active` ke `create_users_table` dan `user_id` ke `create_developers_table` (tanpa menambah tabel baru maupun migration tambahan).
3. **Autentikasi & Role Middleware:** Implementasikan `LoginController` (`/login`, `/logout` dengan cek `is_active` dan rate limiting) serta `EnsureUserHasRole` (`role:admin`).
4. **Manajemen Akun oleh Atasan (`admin`):** Implementasikan `AccountController` + FormRequests + halaman Blade Tailwind (`/admin/accounts/*`) dengan transaksi atomik `User` + `Developer`, toggle aktif/non-aktif, dan reset password.
5. **Seeder & Pengujian:** Buat `AccountSeeder` idempoten (1 Admin + 4 akun Developer terhubung) dan pastikan seluruh test suite (`php artisan test`) serta `npm run build` lulus.
