<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        // 1. Table des Applications / Modules (Solutions)
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Ex: 'hotel', 'school', 'hr', 'restaurant'
            $table->string('nom');           // Ex: 'SaaS Hôtel & Hébergement'
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Table des Clients (Tenants - Stancl/Tenancy)
        Schema::create('tenants', function (Blueprint $table) {
            $table->string('id')->primary(); // ID String (ex: 'hotel-lagon')
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->enum('status', ['active', 'suspended', 'cancelled'])->default('active');
            
            $table->foreignId('solution_id')
                  ->nullable()
                  ->constrained('solutions')
                  ->nullOnDelete();

            $table->json('data')->nullable(); // Configuration Stancl/Tenancy
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Table des Offres / Tarifs (Plans)
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solution_id')->constrained('solutions')->cascadeOnDelete();
            $table->string('name');
            $table->string('code')->unique();
            $table->decimal('price', 12, 2)->default(0.00);
            $table->string('currency', 3)->default('XOF');
            $table->integer('invoice_period')->default(1);
            $table->enum('invoice_interval', ['month', 'year'])->default('month');
            $table->integer('trial_period_days')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // 4. Table des Souscriptions / Abonnements (Subscriptions)
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();

            $table->foreignId('solution_id')->constrained('solutions')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('plans')->cascadeOnDelete();

            $table->enum('status', ['trialing', 'active', 'past_due', 'canceled', 'expired'])->default('trialing');
            
            $table->timestamp('starts_at')->useCurrent();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_starts_at')->nullable();
            $table->timestamp('current_period_ends_at')->nullable();
            $table->timestamp('cancels_at')->nullable();
            $table->timestamp('canceled_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // 5. Table des Factures (Invoices)
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            
            $table->string('tenant_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->cascadeOnDelete();

            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->nullOnDelete();

            $table->decimal('amount_ht', 12, 2)->default(0.00);
            $table->decimal('tax_amount', 12, 2)->default(0.00);
            $table->decimal('amount_ttc', 12, 2)->default(0.00);
            $table->string('currency', 3)->default('XOF');
            
            $table->enum('status', ['draft', 'pending', 'paid', 'failed', 'refunded'])->default('pending');
            $table->timestamp('due_date')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // 6. Table des Transactions / Paiements (Payments)
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            
            $table->string('gateway');
            $table->string('transaction_reference')->nullable();
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('XOF');
            $table->enum('status', ['successful', 'failed', 'pending'])->default('pending');
            
            $table->json('raw_response')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('solutions');

        Schema::enableForeignKeyConstraints();
    }
}; // <-- Le point-virgule ici règle l'erreur !