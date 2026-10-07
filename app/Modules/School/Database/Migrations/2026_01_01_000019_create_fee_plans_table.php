<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Entête du Plan Tarifaire (ex: Tarif L3 Computer Science 2026-2027)
        Schema::create('fee_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->cascadeOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('levels')->nullOnDelete();
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->nullOnDelete();
            
            $table->string('name'); // ex: Scolarité Standard L3
            $table->decimal('total_amount', 12, 2); // Montant total de la scolarité
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
        });

        // Découpage en tranches d'échéances
        Schema::create('fee_plan_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fee_plan_id')->constrained('fee_plans')->cascadeOnDelete();
            
            $table->string('label'); // ex: Droit d'inscription, Tranche 1, Tranche 2
            $table->decimal('amount', 12, 2); // Montant de la tranche
            $table->date('due_date'); // Date limite de paiement
            $table->integer('position')->default(1); // Ordre d'exigibilité
            $table->boolean('is_blocking')->default(true); // Déclenche le verrouillage pédagogique si non payé à l'échéance
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_plan_items');
        Schema::dropIfExists('fee_plans');
    }
};