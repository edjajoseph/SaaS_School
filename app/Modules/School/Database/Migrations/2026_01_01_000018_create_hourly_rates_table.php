<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Grille tarifaire de référence (Diplôme x Niveau x Filière)
        Schema::create('hourly_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            
            $table->foreignId('degree_id')->constrained('degrees')->cascadeOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('levels')->nullOnDelete();
            $table->foreignId('series_id')->nullable()->constrained('series')->nullOnDelete();
        
            $table->decimal('rate_cm', 10, 2)->default(0.00);
            $table->decimal('rate_td', 10, 2)->default(0.00);
            $table->decimal('rate_tp', 10, 2)->default(0.00);
            $table->decimal('rate_examen', 10, 2)->default(0.00);
        
            $table->timestamps();
            $table->softDeletes();
        
            $table->unique(['school_id', 'degree_id', 'level_id', 'series_id'], 'hourly_rate_grid_unique');
        });

        // 1. Attribution des cours, tarifs et volumes horaires
        Schema::create('teacher_subject_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('school_subject_id')->constrained('school_subjects')->cascadeOnDelete();
            $table->foreignId('academic_period_id')->nullable()->constrained('academic_periods')->nullOnDelete();

            // Volumes horaires prévus (Heures)
            $table->integer('volume_cm')->default(0);
            $table->integer('volume_td')->default(0);
            $table->integer('volume_tp')->default(0);
            $table->integer('volume_examen')->default(0);

            // Taux horaires négociés
            $table->decimal('rate_cm', 10, 2)->default(0.00);
            $table->decimal('rate_td', 10, 2)->default(0.00);
            $table->decimal('rate_tp', 10, 2)->default(0.00);
            $table->decimal('rate_examen', 10, 2)->default(0.00);

            // Volumes horaires exécutés par type d'enseignement
            $table->decimal('executed_volume_cm', 8, 2)->default(0.00)->after('volume_examen');
            $table->decimal('executed_volume_td', 8, 2)->default(0.00)->after('executed_volume_cm');
            $table->decimal('executed_volume_tp', 8, 2)->default(0.00)->after('executed_volume_td');
            $table->decimal('executed_volume_examen', 8, 2)->default(0.00)->after('executed_volume_tp');

            // Total cumulé exécuté
            $table->decimal('total_executed_hours', 8, 2)->default(0.00)->after('executed_volume_examen');

            // Indicateur d'achèvement et date de fin effective
            $table->boolean('is_completed')->default(false)->after('is_customized');
            $table->timestamp('completed_at')->nullable()->after('is_completed');

            $table->boolean('is_customized')->default(false);
            
            $table->timestamps();
            $table->softDeletes();

            $table->unique(
                ['staff_id', 'school_class_id', 'school_subject_id', 'academic_period_id'], 
                'staff_course_unique'
            );
        });

        // 2. Table pour les supports de cours multiples
        Schema::create('course_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_subject_rate_id')
                  ->constrained('teacher_subject_rates')
                  ->cascadeOnDelete();

            $table->string('title'); // Ex: "Syllabus du cours", "TP 1 - Modélisation UML", "Slides Chapitre 2"
            $table->enum('type', ['syllabus', 'course_note', 'td', 'tp', 'exam', 'other'])->default('course_note');
            $table->string('file_path'); // Chemin du fichier stocké sur le serveur/S3
            $table->string('original_filename'); // Nom original du fichier uploadé
            $table->string('mime_type')->nullable(); // Ex: application/pdf
            $table->integer('file_size')->nullable(); // Taille en Octets / Ko

            $table->timestamp('uploaded_at')->useCurrent(); // Date et heure précises du dépôt
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        // Suppression propre dans l'ordre inverse de création
        Schema::dropIfExists('course_materials');
        Schema::dropIfExists('teacher_subject_rates');
        Schema::dropIfExists('hourly_rates');
    }
};