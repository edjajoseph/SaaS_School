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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            // Raccordement à l'établissement
            $table->foreignId('school_id')
                  ->constrained('schools')
                  ->cascadeOnDelete();

            // Informations sur la salle
            $table->string('name');                      // Ex: Salle 101, Labo Physique, Amphithéâtre A
            $table->string('code', 50)->nullable();      // Ex: S101, LAB-PHYS, AMPHI-A
            $table->unsignedInteger('capacity')->nullable(); // Capacité en places assises
            $table->string('building')->nullable();      // Bâtiment / Pavillon (Ex: Bâtiment B)
            $table->unsignedInteger('floor')->nullable();  // Étage (Ex: 0 pour RDC, 1 pour 1er étage)

            // État de la salle
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Le nom ou code de la salle doit être unique par établissement
            $table->unique(['school_id', 'name'], 'unique_room_per_school');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};