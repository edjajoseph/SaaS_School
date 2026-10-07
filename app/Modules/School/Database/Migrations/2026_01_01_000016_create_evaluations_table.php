<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



return new class extends Migration
{
    public function up(): void
    {
        
        Schema::create('evaluation_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('code')->unique(); // ex: CC, TP, EXAM_SESSION_1, EXAM_SESSION_2
            $table->string('name'); // ex: Contrôle Continu, Travaux Pratiques, Examen Session 1, Rattrapage
            $table->decimal('default_weight', 5, 2)->default(1.00); // Coefficient par défaut
            $table->boolean('is_catch_up')->default(false); // Flag pour identifier les rattrapages
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            
            // Unique clé pour la Matière (Classique) OU l'ECUE (LMD)
            $table->foreignId('school_subject_id')->constrained('school_subjects')->cascadeOnDelete();
            
            // Type d'évaluation (CC, TP, Exam, Rattrapage)
            $table->foreignId('evaluation_type_id')->constrained('evaluation_types')->cascadeOnDelete();
            
            // Période académique (Semestre / Trimestre)
            $table->foreignId('academic_period_id')
                  ->nullable()
                  ->constrained('academic_periods')
                  ->nullOnDelete();
            
            $table->string('title'); // ex: CC1, Examen écrit, TP Microcontrôleurs
            $table->decimal('coefficient', 5, 2)->default(1.00); // Poids dans la matière / ECUE
            $table->decimal('max_score', 5, 2)->default(20.00);  // Note maximale (ex: /20)
            $table->date('evaluated_at')->nullable();
            
            $table->boolean('is_published')->default(false); // Publication
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_types');
        Schema::dropIfExists('evaluations');
    }
};