<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Exercices comptables
        Schema::create('fiscal_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')
                  ->constrained('academic_years')
                  ->onDelete('cascade');
            $table->string('name')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['open', 'closed'])->default('open');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Plan comptable général (SYSCOHADA / PCG)
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // ex: 411100, 706100, 571100
            $table->string('label'); // ex: "Clients - Scolarités", "Caisse Principale"
            $table->enum('type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Journaux comptables (Consolidé et complet)
        Schema::create('accounting_journals', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique(); // Ex: BQ, CA, VT, AC, OD
            $table->string('name');              // Ex: Journal Banque Principal, Journal Caisse
            $table->enum('type', ['bank', 'cash', 'sales', 'purchase', 'general']);
            
            // Compte de contrepartie / trésorerie par défaut (ex: 571100 pour Caisse, 521100 pour Banque)
            $table->foreignId('default_account_id')
                  ->nullable()
                  ->constrained('chart_of_accounts')
                  ->nullOnDelete();
                  
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Pièce / En-tête d'écriture comptable
        Schema::create('accounting_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiscal_year_id')->constrained('fiscal_years')->cascadeOnDelete();
            
            // Pointe correctement vers 'accounting_journals'
            $table->foreignId('journal_id')->constrained('accounting_journals')->cascadeOnDelete();
            
            $table->string('entry_number'); // ex: VT-2026-00001
            $table->date('entry_date');
            $table->string('label'); // Libellé général de la pièce (ex: "Règlement scolarité Élève X")
            $table->nullableMorphs('source'); // Relation polymorphe (Payment, Invoice, etc.)
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Lignes de débit/crédit (Écritures comptables)
        Schema::create('accounting_entry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accounting_entry_id')->constrained('accounting_entries')->cascadeOnDelete();
            $table->foreignId('chart_of_account_id')->constrained('chart_of_accounts')->cascadeOnDelete();
            $table->string('label')->nullable(); // Libellé propre à la ligne
            $table->decimal('debit', 15, 2)->default(0);
            $table->decimal('credit', 15, 2)->default(0);
            $table->string('lettering_code', 10)->nullable(); // Lettrage (ex: A1, A2)
            
            // Rapprochement bancaire
            $table->boolean('is_reconciled')->default(false);
            $table->dateTime('reconciled_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        // Ordre strict pour respecter l'intégrité référentielle
        Schema::dropIfExists('accounting_entry_items');
        Schema::dropIfExists('accounting_entries');
        Schema::dropIfExists('accounting_journals');
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('fiscal_years');
    }
};