<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Enregistrement des paiements (Encaissements & Reçus)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('student_fee_account_id')->constrained('student_fee_accounts')->cascadeOnDelete();
            
            // Ajout de la référence à la tranche payée
            $table->foreignId('student_fee_schedule_id')->nullable()->constrained('student_fee_schedules')->nullOnDelete();
            
            // Ajout de la clé étrangère vers le journal de trésorerie (Caisse / Banque)
            $table->foreignId('journal_id')->nullable()->constrained('accounting_journals')->nullOnDelete();
            
            $table->string('receipt_number')->unique(); // ex: REC-2026-08-00042
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['cash', 'mobile_money', 'bank_transfer', 'cheque'])->default('cash');
            $table->string('transaction_reference')->nullable(); // N° de transaction MoMo / Virement / Chèque
            
            $table->dateTime('paid_at');
            $table->foreignId('received_by_id')->nullable()->constrained('users')->nullOnDelete(); // Caissier(ère)
            
            $table->enum('status', ['completed', 'cancelled'])->default('completed');
            $table->string('cancellation_reason')->nullable();
            $table->foreignId('cancelled_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('cancelled_at')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Ventilation du paiement sur les différentes échéances
        Schema::create('payment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('payments')->cascadeOnDelete();
            $table->foreignId('student_fee_schedule_id')->constrained('student_fee_schedules')->cascadeOnDelete();
            $table->decimal('amount_allocated', 12, 2); // Montant affecté à cette échéance
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_items');
        Schema::dropIfExists('payments');
    }
};