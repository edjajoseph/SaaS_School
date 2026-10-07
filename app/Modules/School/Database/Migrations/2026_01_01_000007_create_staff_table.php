<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            
            // Référence explicite vers 'personnes'
            $table->foreignId('personne_id')
                  ->unique()
                  ->constrained('personnes')
                  ->cascadeOnDelete();

            $table->foreignId('school_id')
                  ->nullable()
                  ->constrained('schools')
                  ->nullOnDelete();

            $table->string('staff_code')->unique();

            $table->foreignId('speciality_id')
                  ->nullable()
                  ->constrained('specialities')
                  ->nullOnDelete();

            $table->foreignId('degree_id')
                  ->nullable()
                  ->constrained('degrees')
                  ->nullOnDelete();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('staff');
        Schema::enableForeignKeyConstraints();
    }
};