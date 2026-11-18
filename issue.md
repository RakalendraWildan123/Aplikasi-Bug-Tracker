# Planning: Implementasi Tabel `repo_histories` (Migration, Model, Relasi, Factory, Seeder & Test)

## Ringkasan & Tujuan
Membuat tabel **`repo_histories`** untuk mencatat riwayat rilis, tag versi, branch, commit hash, dan ringkasan perubahan (*changelog*) pada setiap **Project** di aplikasi **Laravel 12 + MySQL**, lengkap dengan Migration + Foreign Key + Index, Eloquent Model `RepoHistory` + Relasi dua arah dengan `Project`, Factory, Seeder idempoten, dan pengujian otomatis.

Dokumen ini disusun langkah demi langkah agar mudah diimplementasikan oleh **junior programmer** atau **model AI yang lebih kecil** secara konsisten dengan pola tabel lainnya (`developers`, `projects`, `modules`, `tasks`, `bugs`).

> **Prasyarat Wajib:** Tabel & Model `projects` beserta `ProjectSeeder` (sudah tersedia di branch `main`) menjadi referensi Foreign Key untuk tabel `repo_histories`.

---

## Spesifikasi Skema Tabel

> **Catatan Konvensi Nama:** Nama tabel **`repo_histories`** sudah sesuai standar plural `snake_case` Laravel sehingga otomatis terhubung ke Model **`App\Models\RepoHistory`**. Seluruh nama kolom **wajib `snake_case` huruf kecil**.

| Nama Kolom | Tipe SQL | Panjang / Referensi | Constraint / Default | Method Migration Laravel | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Auto Increment | `PRIMARY KEY` | `$table->id();` | ID unik riwayat repo |
| `project_id` | `BIGINT UNSIGNED` | FK -> `projects.id` | `NOT NULL`, `ON DELETE CASCADE` | `$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();` | Project pemilik riwayat rilis |
| `release_date` | `DATE` | - | `NOT NULL` | `$table->date('release_date');` | Tanggal rilis / catatan commit |
| `release_tags` | `VARCHAR` | `50` | `NULLABLE` | `$table->string('release_tags', 50)->nullable();` | Tag versi rilis (mis. `v1.0.0`, `v1.2.1-rc1`, boleh kosong untuk non-tag commit) |
| `branch_name` | `VARCHAR` | `100` | `NOT NULL` | `$table->string('branch_name', 100);` | Nama branch Git (mis. `main`, `release/1.2`, `hotfix/auth`) |
| `commit_hash` | `VARCHAR` | `40` | `NOT NULL` | `$table->string('commit_hash', 40);` | SHA-1 Git commit hash (maksimal 40 karakter hex) |
| `summary_changes` | `TEXT` | - | `NULLABLE` | `$table->text('summary_changes')->nullable();` | Ringkasan perubahan (*changelog*) pada rilis tersebut |
| `created_at` & `updated_at` | `TIMESTAMP` | - | `NULLABLE` | `$table->timestamps();` | Audit timestamp standar Laravel |

**Constraint & Index Tambahan:**
- `$table->unique(['project_id', 'commit_hash']);` — mencegah pencatatan commit hash yang sama dua kali dalam satu project.
- `$table->index(['project_id', 'release_date']);` — mempercepat query timeline/changelog rilis terbaru per project.

---

## Tahapan Implementasi (Step-by-Step)

### Tahap 1: Buat File Migration Tabel `repo_histories`
1. Pastikan file migrasi `create_projects_table` sudah ada dan timestamp migrasi baru ini **lebih baru**.
2. Jalankan perintah artisan:
   ```bash
   php artisan make:migration create_repo_histories_table
   ```
3. Pada method `up()`, buat tabel `repo_histories` sesuai tabel spesifikasi di atas:
   - Gunakan `$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();`.
   - Tentukan panjang `VARCHAR` secara eksplisit: `release_tags` (`50`, nullable), `branch_name` (`100`), `commit_hash` (`40`).
   - Jadikan `summary_changes` bersifat `->nullable()`.
   - Tambahkan `$table->unique(['project_id', 'commit_hash']);` dan `$table->index(['project_id', 'release_date']);`.
   - Sertakan `$table->timestamps();`.
4. Pada method `down()`, pastikan berisi `Schema::dropIfExists('repo_histories');`.

### Tahap 2: Buat Eloquent Model `RepoHistory` & Lengkapi Relasi di `Project`
1. Jalankan perintah artisan:
   ```bash
   php artisan make:model RepoHistory
   ```
2. Di `app/Models/RepoHistory.php`:
   - Gunakan trait `HasFactory`.
   - Definisikan `$fillable`: `['project_id', 'release_date', 'release_tags', 'branch_name', 'commit_hash', 'summary_changes']`.
   - Definisikan method `casts(): array` (konvensi Laravel 12):
     - `'release_date' => 'date'`
   - Tambahkan relasi `BelongsTo`:
     - `project(): BelongsTo` -> `$this->belongsTo(Project::class)`
   - Tambahkan query scope umum:
     - `scopeForProject(Builder $query, int $projectId): Builder`
     - `scopeLatestRelease(Builder $query): Builder` -> `orderByDesc('release_date')->orderByDesc('id')`
     - `scopeBranch(Builder $query, string $branchName): Builder`
   - *(Opsional namun sangat berguna untuk UI)* Tambahkan helper/accessor `getShortHashAttribute()` atau Attribute `shortHash()` yang mengembalikan 7 karakter pertama dari `commit_hash` (format standar Git).
3. Di `app/Models/Project.php`:
   - Tambahkan relasi kebalikan (`HasMany`):
     ```php
     public function repoHistories(): HasMany
     {
         return $this->hasMany(RepoHistory::class);
     }
     ```

### Tahap 3: Buat Factory (`RepoHistoryFactory`) dan Seeder (`RepoHistorySeeder`)
1. **Factory (`database/factories/RepoHistoryFactory.php`):**
   - Generate via `php artisan make:factory RepoHistoryFactory --model=RepoHistory`.
   - Gunakan helper multibyte-safe (`Illuminate\Support\Str`).
   - Definisikan atribut default valid sesuai batas panjang kolom:
     - `'project_id' => Project::factory()`
     - `'release_date' => now()->subDays(fake()->numberBetween(0, 30))->toDateString()`
     - `'release_tags' => sprintf('v%d.%d.%d', fake()->numberBetween(1, 3), fake()->numberBetween(0, 9), fake()->numberBetween(0, 9))`
     - `'branch_name' => fake()->randomElement(['main', 'develop', 'release/1.0', 'hotfix/security'])`
     - `'commit_hash' => fake()->unique()->sha1()` *(persis 40 karakter hex)*
     - `'summary_changes' => fake()->paragraph()`
   - Tambahkan state helper `untagged()` (`release_tags => null`) untuk menguji commit tanpa release tag.
2. **Seeder (`database/seeders/RepoHistorySeeder.php`):**
   - Generate via `php artisan make:seeder RepoHistorySeeder`.
   - Pastikan data `projects` (`INTRA`, `KEU`, `HRIS`) sudah tersedia (panggil `$this->call(ProjectSeeder::class);` bila belum ada).
   - Isi minimal 2 catatan riwayat rilis realistis per project (total 6 record), dengan `commit_hash` 40 karakter hex unik, tag semver (`v1.0.0`, `v1.1.0`, dll.), serta minimal 1 catatan dengan `release_tags => null`.
   - Gunakan `RepoHistory::updateOrCreate(['project_id' => $project->id, 'commit_hash' => $entry['commit_hash']], $entry)` agar seeder bersifat **idempoten**.
   - Daftarkan `RepoHistorySeeder::class` pada `DatabaseSeeder.php` setelah `ProjectSeeder::class`.

### Tahap 4: Jalankan & Verifikasi Migrasi di Database
1. Pastikan service MySQL aktif dan kredensial `.env` valid.
2. Jalankan migrasi dan uji rollback:
   ```bash
   php artisan migrate
   php artisan migrate:rollback --step=1
   php artisan migrate
   ```
3. Jalankan seeder 2 kali untuk memastikan idempotensi:
   ```bash
   php artisan db:seed --class=RepoHistorySeeder
   php artisan db:seed --class=RepoHistorySeeder
   ```
4. Verifikasi struktur tabel, composite unique, index, dan Foreign Key di MySQL (`SHOW CREATE TABLE repo_histories;`).

### Tahap 5: Buat Pengujian Otomatis (Feature Test)
1. Buat file `tests/Feature/RepoHistoryTableTest.php` menggunakan `RefreshDatabase`.
2. Tulis test case minimal berikut:
   - **Verifikasi Skema:** Tabel `repo_histories` ada dan memiliki seluruh 9 kolom (`id`, `project_id`, `release_date`, `release_tags`, `branch_name`, `commit_hash`, `summary_changes`, `created_at`, `updated_at`).
   - **Kolom Nullable:** Membuat `RepoHistory` tanpa `release_tags` dan `summary_changes` berhasil dengan nilai `null`.
   - **Foreign Key Constraint:** Insert dengan `project_id` yang tidak ada di `projects` melempar `QueryException`.
   - **Cascade Delete:** Menghapus `Project` otomatis menghapus seluruh `RepoHistory` miliknya.
   - **Composite Unique (`project_id`, `commit_hash`):** Duplikat `commit_hash` pada project yang sama ditolak (`QueryException`), sedangkan `commit_hash` yang sama pada project berbeda diperbolehkan.
   - **Casting, Relasi Dua Arah & Scope:** `release_date` ter-cast menjadi `Carbon`, `$history->project` & `$project->repoHistories` bekerja, serta scope `forProject`, `branch`, dan `latestRelease` mengembalikan urutan dan filter yang tepat.
   - **Seeder Idempoten:** Menjalankan `RepoHistorySeeder` dua kali menghasilkan jumlah record konsisten (6 record, masing-masing 2 per project `INTRA`, `KEU`, `HRIS`).
3. Jalankan seluruh test suite:
   ```bash
   php artisan test
   ```
   Pastikan seluruh test (lama maupun baru) lulus tanpa error.

---

## Hal yang Harus Dihindari (Guardrails)
- **Jangan pakai huruf kapital pada nama kolom SQL** (`Id`, `Project_id`, `Commit_hash`). Wajib `snake_case` huruf kecil.
- **Jangan lupa batas panjang `VARCHAR`**: `release_tags` (`50`), `branch_name` (`100`), dan `commit_hash` (`40`).
- **Jangan buat `commit_hash` sebagai `unique()` global tanpa `project_id`** — gunakan composite unique `['project_id', 'commit_hash']` agar terikat per project.
- **Jangan lupa menambahkan relasi `repoHistories()` pada `Project.php`** selain `project()` pada `RepoHistory.php`.
- **Jangan jalankan `RepoHistorySeeder` sebelum `ProjectSeeder`** di `DatabaseSeeder.php`.
- **Jangan commit file `.env`** ke Git.

---

## Kriteria Selesai (Definition of Done)
- [ ] Migration `create_repo_histories_table` dibuat dengan seluruh 7 kolom + `timestamps()`, FK `project_id -> projects.id` (`ON DELETE CASCADE`), composite unique `['project_id', 'commit_hash']`, index `['project_id', 'release_date']`, dan rollback bersih.
- [ ] Model `app/Models/RepoHistory.php` (`$fillable`, `casts()`, relasi `project()`, scopes) serta relasi `repoHistories()` di `app/Models/Project.php` sudah lengkap.
- [ ] `RepoHistoryFactory` (dengan state `untagged()`) dan `RepoHistorySeeder` (idempoten) tersedia serta terdaftar di `DatabaseSeeder.php`.
- [ ] `php artisan migrate`, `migrate:rollback --step=1`, dan `db:seed --class=RepoHistorySeeder` berjalan tanpa error.
- [ ] Seluruh pengujian otomatis di `php artisan test` lulus (termasuk uji FK, cascade delete, composite unique, casting tanggal, relasi dua arah, scope, dan seeder idempoten).
