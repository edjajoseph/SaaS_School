<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();

            // Pointeur vers l'année académique active (Contexte de travail global)
            $table->foreignId('current_academic_year_id')
                  ->nullable()
                  ->comment('Référence vers l\'année scolaire en cours pour cet établissement');

            // Identification de l'établissement
            $table->string('name');                                 // Ex: Groupe Scolaire Saint-Antoine
            $table->string('code', 50)->unique();                   // Ex: GSSA-ABJ
            $table->string('official_approval_number')->nullable(); // N° d'agrément / Décision d'ouverture
            $table->string('logo_path')->nullable();
            $table->string('stamp_path')->nullable();               // Cachet officiel scanné

            // Contacts & Localisation
            $table->string('email')->nullable();
            $table->string('phone_1', 30)->nullable();
            $table->string('phone_2', 30)->nullable();
            $table->string('po_box')->nullable();                   // Boîte postale (Ex: BP 128 Abidjan 01)
            $table->string('country')->default('CI');               // Code pays ISO (Ex: CI)
            $table->string('city')->nullable();                     // Ex: Abidjan
            $table->string('municipality')->nullable();               // Ex: Cocody, Plateau
            $table->text('address')->nullable();

            // Statut juridique / administratif
            $table->enum('status', ['private', 'public', 'confessional'])->default('private');

            // Communication & Documents
            $table->string('website')->nullable();
            $table->string('document_header')->nullable(); // Entête personnalisé pour impressions
            $table->text('document_footer')->nullable();   // Pied de page légal (Bulletins, reçus)

            // État de l'établissement
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('schools');

        Schema::enableForeignKeyConstraints();
    }
};