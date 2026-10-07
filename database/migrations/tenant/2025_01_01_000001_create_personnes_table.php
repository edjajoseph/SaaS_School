<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table des personnes de l'équipe SaaS Centrale
        Schema::create('personnes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->enum('civility', ['M', 'Mme', 'Mlle'])->nullable();
            $table->string('sit_mat')->nullable();

            // Nouveaux champs d'état civil
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->foreignId('country_id')->nullable()->constrained('countries')->nullOnDelete(); // Nationalité (Pays)

            $table->string('telephone')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            
            // Photo de profil
            $table->string('photo')->nullable(); 

            $table->timestamps();
            $table->softDeletes();
        });

        // Modification de la table users centrale (Admin SaaS)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('personne_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('personnes')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['personne_id']);
            $table->dropColumn('personne_id');
        });
        
        Schema::dropIfExists('personnes');
    }
};