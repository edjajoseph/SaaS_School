<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Compte financier individuel lié à une inscription (Registration)
        Schema::create('student_fee_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('registration_id')->constrained('registrations')->cascadeOnDelete();
            $table->foreignId('fee_plan_id')->nullable()->constrained('fee_plans')->nullOnDelete();
            
            $table->decimal('total_due', 12, 2)->default(0.00);      // Montant total réclamé
            $table->decimal('discount_amount', 12, 2)->default(0.00); // Remise / Bourse / Réduction
            $table->string('discount_reason')->nullable();           // Ex: Bourse d'excellence 20%
            $table->decimal('total_paid', 12, 2)->default(0.00);     // Total encaissé
            $table->decimal('balance', 12, 2)->default(0.00);        // Solde restant (total_due - discount - total_paid)
            
            $table->enum('status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');
            $table->timestamps();
            $table->softDeletes();
        });

        // Échéancier personnalisé par étudiant
        Schema::create('student_fee_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_fee_account_id')->constrained('student_fee_accounts')->cascadeOnDelete();
            $table->foreignId('fee_plan_item_id')->nullable()->constrained('fee_plan_items')->nullOnDelete();
            $table->string('label');
            $table->decimal('original_amount', 12, 2)->nullable();
            $table->decimal('discount_amount', 12, 2)->default(0.00); // <--- Valeur par défaut 0.00
            $table->decimal('amount', 12, 2);
            $table->decimal('paid_amount', 12, 2)->default(0.00);
            $table->date('due_date');
            $table->boolean('is_paid')->default(false);
            $table->boolean('is_blocking')->default(true);
            
            $table->timestamps();
            $table->softDeletes();
        });

        
        // Remplir original_amount avec la valeur initiale de amount
        DB::statement('UPDATE student_fee_schedules SET original_amount = amount WHERE original_amount IS NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fee_schedules');
        Schema::dropIfExists('student_fee_accounts');
    }
};