<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->date('date');
            
            $table->enum('status', ['present', 'absent', 'late', 'excused'])->default('present');
            $table->integer('late_minutes')->default(0); // Durée du retard en minutes
            $table->text('reason')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['schedule_id', 'registration_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_attendances');
    }
};