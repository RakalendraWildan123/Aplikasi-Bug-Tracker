# Planning: Implementasi Tabel `app_evaluations` (Migration, Model, Relasi, Factory, Seeder & Test)

## Ringkasan & Tujuan
Membuat tabel **`app_evaluations`** untuk mencatat hasil evaluasi berkala aplikasi (tanggal evaluasi, periode review, kendala operasional, dan masukan pengguna) yang terhubung ke tabel **`projects`** pada aplikasi **Laravel 12 + MySQL**, lengkap dengan Migration + Foreign Key + Index, Eloquent Model `AppEvaluation` + Relasi dua arah dengan `Project`, Factory, Seeder idempoten, dan pengujian otomatis.

Dokumen ini disusun langkah demi langkah agar mudah diimplementasikan oleh **junior programmer** atau **model AI yang lebih kecil** secara konsisten dengan pola tabel lainnya (`developers`, `projects`, `modules`, `tasks`, `bugs`, `repo_histories`).

> **Prasyarat Wajib:** Tabel & Model `projects` beserta `ProjectSeeder` (sudah tersedia di branch `main`) menjadi referensi Foreign Key untuk tabel `app_evaluations`.

---

## Spesifikasi Skema Tabel

> **Catatan Konvensi Nama:** Gunakan nama tabel plural **`app_evaluations`** (Model: `App\Models\AppEvaluation`) agar konsisten dengan `developers`, `projects`, `modules`, `tasks`, `bugs`, dan `repo_histories` serta otomatis dikenali Laravel Eloquent. Seluruh nama kolom **wajib `snake_case` huruf kecil**.  
> *(Jika kebutuhan ERD mewajibkan nama singular `app_evaluation`, ubah nama tabel di migrasi menjadi `app_evaluation` dan tambahkan `protected $table = 'app_evaluation';` pada Model `AppEvaluation`.)*

| Nama Kolom | Tipe SQL | Panjang / Referensi | Constraint / Default | Method Migration Laravel | Keterangan |
| :--- | :--- | :--- | :--- | :--- | :--- |
| `id` | `BIGINT UNSIGNED` | Auto Increment | `PRIMARY KEY` | `$table->id();` | ID unik evaluasi |
| `project_id` | `BIGINT UNSIGNED` | FK -> `projects.id` | `NOT NULL`, `ON DELETE CASCADE` | `$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();` | Project yang dievaluasi |
| `evaluation_date` | `DATE` | - | `NOT NULL` | `$table->date('evaluation_date');` | Tanggal pelaksanaan evaluasi |
| `period_review` | `VARCHAR` | `100` | `NOT NULL` | `$table->string('period_review', 100);` | Label periode review (mis. `Q3 2026`, `Q4 2026`, `Oktober 2026`) |
| `operational_issues` | `TEXT` | - | `NULLABLE` | `$table->text('operational_issues')->nullable();` | Catatan kendala operasional/downtime/performa selama periode |
| `user_feedback` | `TEXT` | - | `NULLABLE` | `$table->text('user_feedback')->nullable();` | Ringkasan masukan, keluhan, dan saran pengguna |
| `created_at` & `updated_at` | `TIMESTAMP` | - | `NULLABLE` | `$table->timestamps();` | Audit timestamp standar Laravel |

**Constraint & Index Tambahan:**
- `$table->unique(['project_id', 'period_review']);` — mencegah duplikasi evaluasi untuk periode review yang sama pada project yang sama (sementara project berbeda tetap boleh punya periode sama seperti `Q4 2026`).
- `$table->index(['project_id', 'evaluation_date']);` — mempercepat query daftar evaluasi terbaru per project.

---

## Tahapan Implementasi (Step-by-Step)

### Tahap 1: Buat File Migration Tabel `app_evaluations`
1. Pastikan file migrasi `create_projects_table` sudah ada dan timestamp migrasi baru ini **lebih baru**.
2. Jalankan perintah artisan:
   ```bash
   php artisan make:migration create_app_evaluations_table
   ```
3. Pada method `up()`, buat tabel `app_evaluations` sesuai tabel spesifikasi di atas:
   - Gunakan `$table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();`.
   - Tentukan panjang `VARCHAR` secara eksplisit: `$table->string('period_review', 100);`.
   - Jadikan `operational_issues` dan `user_feedback` bersifat `->nullable()`.
   - Tambahkan `$table->unique(['project_id', 'period_review']);` dan `$table->index(['project_id', 'evaluation_date']);`.
   - Sertakan `$table->timestamps();`.
4. Pada method `down()`, pastikan berisi `Schema::dropIfExists('app_evaluations');`.

### Tahap 2: Buat Eloquent Model `AppEvaluation` & Lengkapi Relasi di `Project`
1. Jalankan perintah artisan:
   ```bash
   php artisan make:model AppEvaluation
   ```
2. Di `app/Models/AppEvaluation.php`:
   - Gunakan trait `HasFactory`.
   - Definisikan `$fillable`: `['project_id', 'evaluation_date', 'period_review', 'operational_issues', 'user_feedback']`.
   - Definisikan method `casts(): array` (konvensi Laravel 12):
     - `'evaluation_date' => 'date'`
   - Tambahkan relasi `BelongsTo`:
     - `project(): BelongsTo` -> `$this->belongsTo(Project::class)`
   - Tambahkan query scope umum:
     - `scopeForProject(Builder $query, int $projectId): Builder`
     - `scopeLatestEvaluation(Builder $query): Builder` -> `orderByDesc('evaluation_date')->orderByDesc('id')`
     - `scopePeriod(Builder $query, string $periodReview): Builder`
3. Di `app/Models/Project.php`:
   - Tambahkan relasi kebalikan (`HasMany`):
     ```php
     public function appEvaluations(): HasMany
     {
         return $this->hasMany(AppEvaluation::class);
     }
     ```

### Tahap 3: Buat Factory (`AppEvaluationFactory`) dan Seeder (`AppEvaluationSeeder`)
1. **Factory (`database/factories/AppEvaluationFactory.php`):**
   - Generate via `php artisan make:factory AppEvaluationFactory --model=AppEvaluation`.
   - Pastikan `period_review` dihasilkan secara **unik** (mis. `sprintf('Q%d %d - %s', fake()->numberBetween(1, 4), fake()->numberBetween(2024, 2026), fake()->unique()->numerify('###'))` atau kombinasi bulan-tahun unik) agar tidak terjadi tabrakan constraint `unique(['project_id', 'period_review'])` saat membuat banyak record untuk satu project di dalam test (`count(3)->for($project)->create()`).
   - Definisikan atribut default valid sesuai panjang kolom:
     - `'project_id' => Project::factory()`
     - `'evaluation_date' => now()->subDays(fake()->numberBetween(0, 60))->toDateString()`
     - `'period_review'` (maks 100 karakter, unik)
     - `'operational_issues' => fake()->paragraph()`
     - `'user_feedback' => fake()->paragraph()`
   - Tambahkan state helper `withoutIssues()` (`operational_issues => null`).
2. **Seeder (`database/seeders/AppEvaluationSeeder.php`):**
   - Generate via `php artisan make:seeder AppEvaluationSeeder`.
   - Pastikan data `projects` (`INTRA`, `KEU`, `HRIS`) sudah tersedia (panggil `$this->call(ProjectSeeder::class);` bila belum ada).
   - Isi minimal 2 periode evaluasi realistis per project (`Q3 2026` dan `Q4 2026`, total 6 record), termasuk minimal 1 evaluasi dengan `operational_issues => null` (tanpa kendala operasional).
   - Gunakan `AppEvaluation::updateOrCreate(['project_id' => $project->id, 'period_review' => $entry['period_review']], $entry)` agar seeder bersifat **idempoten**.
   - Daftarkan `AppEvaluationSeeder::class` pada `DatabaseSeeder.php` setelah `ProjectSeeder::class`.

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
   php artisan db:seed --class=AppEvaluationSeeder
   php artisan db:seed --class=AppEvaluationSeeder
   ```
4. Verifikasi struktur tabel, composite unique, index, dan Foreign Key di MySQL (`SHOW CREATE TABLE app_evaluations;`).

### Tahap 5: Buat Pengujian Otomatis (Feature Test)
1. Buat file `tests/Feature/AppEvaluationTableTest.php` menggunakan `RefreshDatabase`.
2. Tulis test case minimal berikut:
   - **Verifikasi Skema:** Tabel `app_evaluations` ada dan memiliki seluruh 8 kolom (`id`, `project_id`, `evaluation_date`, `period_review`, `operational_issues`, `user_feedback`, `created_at`, `updated_at`).
   - **Kolom Nullable:** Membuat `AppEvaluation` tanpa `operational_issues` dan `user_feedback` berhasil dengan nilai `null`.
   - **Foreign Key Constraint:** Insert dengan `project_id` tidak valid melempar `QueryException`.
   - **Cascade Delete:** Menghapus `Project` otomatis menghapus seluruh `AppEvaluation` miliknya.
   - **Composite Unique (`project_id`, `period_review`):** Duplikat `period_review` pada project yang sama ditolak (`QueryException`), sedangkan `period_review` yang sama pada project berbeda diperbolehkan.
   - **Casting, Relasi Dua Arah & Scope:** `evaluation_date` ter-cast menjadi `Carbon`, `$evaluation->project` & `$project->appEvaluations` bekerja, serta scope `forProject`, `period`, dan `latestEvaluation` mengembalikan hasil yang tepat.
   - **Seeder Idempoten:** Menjalankan `AppEvaluationSeeder` dua kali menghasilkan 6 record konsisten (masing-masing 2 per project `INTRA`, `KEU`, `HRIS`).
3. Jalankan seluruh test suite:
   ```bash
   php artisan test
   ```
   Pastikan seluruh test (lama maupun baru) lulus tanpa error.

---

## Hal yang Harus Dihindari (Guardrails)
- **Jangan pakai huruf kapital pada nama kolom SQL** (`Id`, `Project_id`, `Period_review`, `Operational_issues`, `User_feedback`). Wajib `snake_case` huruf kecil.
- **Jangan lupa batas panjang `VARCHAR(100)`** pada kolom `period_review`.
- **Jangan buat `period_review` sebagai `unique()` global tanpa `project_id`** — beberapa project harus bisa memiliki evaluasi untuk periode yang sama (mis. `Q4 2026`).
- **Pastikan `period_review` di Factory bersifat unik** agar pemanggilan `AppEvaluation::factory()->count(N)->for($project)->create()` tidak flaky karena tabrakan unique index.
- **Jangan lupa menambahkan relasi `appEvaluations()` pada `Project.php`** selain `project()` pada `AppEvaluation.php`.
- **Jangan jalankan `AppEvaluationSeeder` sebelum `ProjectSeeder`** di `DatabaseSeeder.php`.
- **Jangan commit file `.env`** ke Git.

---

## Kriteria Selesai (Definition of Done)
- [ ] Migration `create_app_evaluations_table` dibuat dengan seluruh 6 kolom + `timestamps()`, FK `project_id -> projects.id` (`ON DELETE CASCADE`), composite unique `['project_id', 'period_review']`, index `['project_id', 'evaluation_date']`, dan rollback bersih.
- [ ] Model `app/Models/AppEvaluation.php` (`$fillable`, `casts()`, relasi `project()`, scopes) serta relasi `appEvaluations()` di `app/Models/Project.php` sudah lengkap.
- [ ] `AppEvaluationFactory` (dengan `period_review` unik & state `withoutIssues()`) dan `AppEvaluationSeeder` (idempoten) tersedia serta terdaftar di `DatabaseSeeder.php`.
- [ ] `php artisan migrate`, `migrate:rollback --step=1`, dan `db:seed --class=AppEvaluationSeeder` berjalan tanpa error.
- [ ] Seluruh pengujian otomatis di `php artisan test` lulus (termasuk uji FK, cascade delete, composite unique, casting tanggal, relasi dua arah, scope, dan seeder idempoten).
