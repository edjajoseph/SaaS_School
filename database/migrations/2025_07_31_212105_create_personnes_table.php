<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Création de la table personnes centrale
        Schema::create('personnes', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenoms');
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->string('telephone')->unique()->nullable();
            $table->string('email')->unique();
            $table->string('address')->nullable();
            
            // Photo de profil
            $table->string('photo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 2. Mise à jour de la table users centrale
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('personne_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('personnes')
                  ->nullOnDelete();

            $table->boolean('pwd_change')->default(false)->after('password');
            $table->boolean('isactive')->default(true)->after('pwd_change');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['personne_id']);
            $table->dropColumn(['personne_id', 'pwd_change', 'isactive']);
        });

        Schema::dropIfExists('personnes');
    }
};