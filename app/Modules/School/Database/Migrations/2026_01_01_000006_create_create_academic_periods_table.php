<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('academic_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_type_item_id')->constrained()->cascadeOnDelete();
            
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_current')->default(false);
            $table->boolean('is_closed')->default(false);
            
            $table->timestamps();
            $table->softDeletes();

            // Garantit l'unicité du découpage par année et par école
            $table->unique(['school_id', 'academic_year_id', 'period_type_item_id'], 'unique_school_academic_period');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_periods');
    }
};