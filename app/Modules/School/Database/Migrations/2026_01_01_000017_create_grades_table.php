<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            
            $table->decimal('score', 5, 2)->nullable(); // Note (ex: 14.50), NULL si absent
            $table->boolean('is_absent')->default(false);
            $table->boolean('is_justified')->default(false); // Absence justifiée ou non
            $table->text('remarks')->nullable(); // Appréciation (ex: "Très bon travail", "Fraude")

            $table->timestamps();
            $table->softDeletes();

            // Une seule note par étudiant pour une évaluation donnée
            $table->unique(['evaluation_id', 'registration_id']);
        });

        // 1. Statistique globale de la classe pour un ECUE / Matière
        Schema::create('subject_class_averages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained();
            $table->foreignId('school_class_id')->constrained();
            $table->foreignId('academic_period_id')->constrained();
            $table->foreignId('school_subject_id')->nullable()->constrained(); // ou ecue_id
            $table->decimal('class_average', 5, 2)->default(0); // Moyenne générale de la classe
            $table->decimal('max_average', 5, 2)->default(0);   // Note / moyenne max
            $table->decimal('min_average', 5, 2)->default(0);   // Note / moyenne min
            $table->integer('total_students')->default(0);      // Nombre d'étudiants évalués
            $table->integer('passed_count')->default(0);        // Nb ayant la moyenne (>= 10)
            $table->timestamp('calculated_at');
            $table->timestamps();

            $table->unique(['school_class_id', 'academic_period_id', 'school_subject_id'], 'unique_class_subject_period');
        });

        // 2. Moyenne et rang individuels par Étudiant pour cet ECUE / Matière
        Schema::create('student_subject_averages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_class_average_id')->constrained()->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained()->cascadeOnDelete();
            $table->decimal('average', 5, 2)->nullable(); // Moyenne sur 20
            $table->integer('rank')->nullable();            // Rang de l'étudiant
            $table->string('rank_formatted')->nullable();  // Ex: "1er", "2ème ex", "10ème"
            $table->integer('absences_count')->default(0);
            $table->boolean('is_exempted')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->unique(['subject_class_average_id', 'registration_id'], 'unique_student_subject_avg');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
        Schema::dropIfExists('student_student_averages');
        Schema::dropIfExists('subject_class_averages');
    }
};