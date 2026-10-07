<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_attendances', function (Blueprint $table) {
            $table->id();
            
            // Contextes multi-tenant et académique
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('school_subject_id')->constrained('school_subjects')->cascadeOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete();
            $table->foreignId('academic_period_id')->nullable()->constrained('academic_periods')->nullOnDelete();

            // Horaires et volume réalisé
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('hours_done', 5, 2)->default(0); // Ex: 2.50 heures

            // Contenu du Cahier de Textes
            $table->enum('session_type', ['CM', 'TD', 'TP', 'EXAMEN'])->default('CM');
            $table->string('chapter_title')->nullable();
            $table->text('topic_covered')->nullable(); // Résumé de la séance
            $table->text('objectives')->nullable();    // Objectifs pédagogiques
            $table->text('homework')->nullable();      // Devoirs / Travail à faire
            $table->date('homework_due_date')->nullable();

            // Validation administrative (Visa de la Direction / Chef de Dépt)
            $table->boolean('is_validated')->default(false);
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->text('validation_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser le suivi des taux d'exécution et les recherches
            $table->index(['school_id', 'date']);
            $table->index(['school_class_id', 'school_subject_id']);
            $table->index(['staff_id', 'date']);
        });

        // Consolidation du volume horaire prévisionnel et exécuté
        Schema::create('school_subject_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            $table->foreignId('school_subject_id')->constrained('school_subjects')->cascadeOnDelete();
            $table->foreignId('academic_period_id')->nullable()->constrained('academic_periods')->nullOnDelete();

            $table->decimal('planned_hours', 6, 2)->default(0);    // Volume horaire prévisionnel (ex: 30h)
            $table->decimal('executed_hours', 6, 2)->default(0);   // Volume horaire réalisé (ex: 20h)
            $table->decimal('completion_rate', 5, 2)->default(0); // Taux en % (ex: 66.67%)

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_class_id', 'school_subject_id', 'academic_period_id'], 'class_subject_period_unique');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_attendances');
        Schema::dropIfExists('course_logbooks');
    }
};