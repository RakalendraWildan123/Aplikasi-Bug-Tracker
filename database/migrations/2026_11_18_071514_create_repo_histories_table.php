<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repo_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();
            $table->date('release_date');
            $table->string('release_tags', 50)->nullable();
            $table->string('branch_name', 100);
            $table->string('commit_hash', 40);
            $table->text('summary_changes')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'commit_hash']);
            $table->index(['project_id', 'release_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repo_histories');
    }
};
