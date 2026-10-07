<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('registrations', function (Blueprint $table) {
            $table->id();

            // Clés étrangères principales
            $table->foreignId('school_id')
                  ->constrained('schools')
                  ->cascadeOnDelete();

            $table->foreignId('academic_year_id')
                  ->constrained('academic_years')
                  ->cascadeOnDelete();

            $table->foreignId('student_id')
                  ->constrained('students')
                  ->cascadeOnDelete();

            $table->foreignId('school_class_id')
                  ->constrained('school_classes')
                  ->cascadeOnDelete();

            // Référence d'inscription unique (ex: REG-2026-0001)
            $table->string('registration_number', 50)->unique();

            // Type d'opération
            $table->enum('type', ['inscription', 'reinscription'])->default('inscription');

            // Statut de l'inscription administrative
            $table->enum('status', [
                'pending',
                'confirmed',
                'canceled',
                'transferred'
            ])->default('confirmed');

            // Fichiers et métadonnées administratives
            $table->string('payment_receipt', 255)->nullable();
            $table->date('registration_date');            
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_id', 'academic_year_id', 'student_id'], 'unique_student_registration_per_year');
        });

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('registrations');
        Schema::enableForeignKeyConstraints();
    }
};