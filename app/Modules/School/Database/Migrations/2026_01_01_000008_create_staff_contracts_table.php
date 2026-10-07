<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Contrats du personnel
        Schema::create('staff_contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('staff_role_id')->constrained('staff_roles')->cascadeOnDelete();
            
            $table->string('job_title');
            $table->enum('contract_type', ['CDI', 'CDD', 'VACATAIRE', 'PRESTATAIRE', 'STAGE']);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            
            $table->enum('pay_type', ['monthly', 'hourly', 'forfait']);
            $table->decimal('base_salary_or_rate', 12, 2);
            
            $table->string('contract_document_path')->nullable();
            $table->enum('status', ['active', 'expired', 'suspended', 'terminated'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Définition des rubriques (Primes, Retenues, Cotisations)
        Schema::create('pay_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('code');
            $table->string('name');
            $table->enum('type', ['gain', 'deduction']);
            $table->enum('calculation_method', ['fixed', 'percentage']);
            $table->decimal('value', 12, 4)->default(0); // Précision ajustée pour supporter montants et pourcentages
            $table->enum('applies_to', ['all', 'permanent', 'vacant'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['school_id', 'code']);
        });

        // 3. Paramètres Fiscaux & Sociaux par Établissement
        Schema::create('payroll_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->unique()->constrained('schools')->cascadeOnDelete();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->boolean('apply_taxes_to_vacants')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payroll_tax_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->string('code')->index(); // ex: CNPS_RETRAITE, IGR, CNAM, MUGEFCI, ITS, CN
            $table->string('name'); // ex: "CNPS Part Salariale", "Couverture Maladie Universelle (CNAM)"
            $table->enum('category', ['social_salarial', 'social_patronal', 'tax_salarial', 'tax_patronal', 'mutual_salarial']);
            $table->enum('calculation_type', ['percentage', 'fixed_amount'])->default('percentage');
            $table->decimal('rate', 8, 4)->default(0); // ex: 0.0630 pour 6.3%, 0.0100 pour 1%
            $table->decimal('fixed_amount', 12, 2)->nullable(); // Pour les cotisations forfaitaires
            $table->decimal('ceiling', 12, 2)->nullable(); // Plafond mensuel
            $table->decimal('min_base', 12, 2)->nullable(); // Plancher d'imposition si applicable
            $table->boolean('is_active')->default(true);
            $table->boolean('applies_to_vacants')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. En-tête des Bulletins de Paie
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->cascadeOnDelete();
            $table->foreignId('staff_id')->constrained('staff')->cascadeOnDelete();
            $table->foreignId('staff_contract_id')->nullable()->constrained('staff_contracts')->nullOnDelete();
            $table->string('payroll_number')->unique();
            $table->date('period_start');
            $table->date('period_end');
            
            $table->decimal('base_salary', 12, 2)->default(0);
            $table->decimal('total_hours', 8, 2)->default(0);
            $table->decimal('gross_amount', 12, 2)->default(0);
            $table->decimal('bonuses_amount', 12, 2)->default(0);
            $table->decimal('penalties_amount', 12, 2)->default(0);
            $table->decimal('advances_amount', 12, 2)->default(0);
            $table->decimal('net_amount', 12, 2)->default(0);
            
            $table->enum('status', ['draft', 'validated', 'paid', 'cancelled'])->default('draft');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
            $table->softDeletes();

            // Index pour accélérer les recherches de bulletins sur une période
            $table->index(['staff_id', 'period_start', 'period_end']);
        });

        // 5. Lignes de Détails du Bulletin
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->cascadeOnDelete();
            $table->foreignId('pay_component_id')->nullable()->constrained('pay_components')->nullOnDelete();
            $table->string('label');
            $table->enum('type', ['gain', 'deduction']);
            $table->decimal('base_amount', 12, 2)->default(0);
            $table->decimal('rate_or_value', 8, 4)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payrolls');
        Schema::dropIfExists('payroll_settings');
        Schema::dropIfExists('pay_components');
        Schema::dropIfExists('staff_contracts');
    }
};