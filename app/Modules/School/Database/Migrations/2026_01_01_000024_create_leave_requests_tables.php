<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            
            // Rattachement à l'école spécifique au sein du tenant
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Type d'autorisation
            $table->enum('type', [
                'permission',      // Permission ponctuelle / Absence de courte durée
                'late_arrival',    // Retard justifié
                'annual_leave',    // Congé annuel
                'sick_leave',      // Congé maladie
                'maternity_leave', // Congé maternité/paternité
                'special_leave'    // Événement familial / Autre
            ]);

            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('reason');
            $table->string('document_path')->nullable(); // Pièce justificative

            // Circuit de validation
            $table->enum('status', ['pending', 'approved', 'rejected', 'cancelled'])->default('pending');
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->text('rejection_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();
            
            // Index pour optimiser les requêtes filtrées par école
            $table->index(['school_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};