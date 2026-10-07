<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Table des terminaux/bornes de pointage autorisés
        Schema::create('attendance_terminals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('name'); // Ex: Borne Hall Principal, PC Salle des Profs
            $table->string('location_description')->nullable(); // Ex: Hall B - Rez-de-chaussée
            $table->enum('type', ['biometric', 'rfid_card', 'pc_station', 'qr_scanner']);
            $table->string('mac_address')->nullable(); // Ex: 00:1A:2B:3C:4D:5E
            $table->string('ip_address')->nullable();  // Ex: 192.168.1.50
            $table->timestamp('last_ping_at')->nullable();
            $table->string('api_token', 80)->nullable()->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Table des pointages du personnel
        Schema::create('staff_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('schedule_id')->nullable()->constrained('schedules')->nullOnDelete(); // Lié si c'est un cours
            $table->foreignId('terminal_id')->nullable()->constrained('attendance_terminals')->nullOnDelete();
            
            $table->date('date');
            $table->timestamp('check_in');          // Heure exacte d'arrivée (scan/pointage)
            $table->timestamp('check_out')->nullable(); // Heure exacte de départ
            
            $table->integer('late_minutes')->default(0);    // Retard calculé par rapport à l'horaire prévu
            $table->integer('early_leave_minutes')->default(0); // Départ anticipé
            $table->decimal('effective_hours', 5, 2)->default(0.00); // Temps réel réalisé (ex: 3.50 h)

            $table->enum('verification_method', ['biometric', 'badge', 'mac_address', 'manual'])->default('manual');
            $table->string('mac_address_used')->nullable();
            
            $table->boolean('is_deducted')->default(false); // Indique si une ponction a été appliquée en paie
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['staff_id', 'date', 'schedule_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_attendances');
        Schema::dropIfExists('attendance_terminals');
    }
};