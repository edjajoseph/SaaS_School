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
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();

            // Clés étrangères
            $table->foreignId('school_id')
                  ->constrained('schools')
                  ->cascadeOnDelete();

            $table->foreignId('level_id')
                  ->constrained('levels')
                  ->cascadeOnDelete();

            $table->foreignId('serie_id')
                  ->constrained('series')
                  ->cascadeOnDelete();

            // Informations de la classe
            $table->string('name');                      // Ex: 6ème A, Terminale C1
            $table->string('code', 50)->nullable();      // Ex: 6A, TC1
            $table->unsignedInteger('capacity')->nullable(); // Capacité maximale d'élèves
            $table->decimal('tuition_fee', 12, 2)->default(0.00); // Frais de scolarité de base

            // État de la classe
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Un nom de classe doit être unique au sein d'un même établissement
            $table->unique(['school_id', 'name'], 'unique_class_per_school');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Désactiver la vérification des clés étrangères temporairement
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('school_classes');

        // Réactiver la vérification des clés étrangères
        Schema::enableForeignKeyConstraints();
    }
};