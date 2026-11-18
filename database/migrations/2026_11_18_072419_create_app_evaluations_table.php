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
        Schema::create('app_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')
                ->constrained('projects')
                ->cascadeOnDelete();
            $table->date('evaluation_date');
            $table->string('period_review', 100);
            $table->text('operational_issues')->nullable();
            $table->text('user_feedback')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'period_review']);
            $table->index(['project_id', 'evaluation_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_evaluations');
    }
};
