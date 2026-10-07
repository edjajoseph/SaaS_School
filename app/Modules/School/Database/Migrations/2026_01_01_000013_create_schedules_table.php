<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void 
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('school_class_id')->constrained('school_classes')->cascadeOnDelete();
            
            // Relation vers school_subjects
            $table->foreignId('school_subject_id')->constrained('school_subjects')->cascadeOnDelete();
            
            $table->foreignId('staff_id')->nullable()->constrained('staff')->nullOnDelete();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();
            
            $table->foreignId('academic_period_id')
                  ->nullable()
                  ->constrained('academic_periods')
                  ->nullOnDelete();
            
            // Type de séance
            $table->enum('session_type', ['CM', 'TD', 'TP', 'CC', 'EXAM'])->default('CM');
            
            // Jour de la semaine au format numérique ISO-8601 (1 = Lundi, 7 = Dimanche)
            $table->unsignedTinyInteger('day_of_week')->comment('1=Lundi, ..., 7=Dimanche'); 
            
            $table->time('start_time');
            $table->time('end_time');
            
            $table->string('moodle_event_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_completed')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};