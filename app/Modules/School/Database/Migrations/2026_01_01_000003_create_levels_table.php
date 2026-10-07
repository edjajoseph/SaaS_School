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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();

            // Rattachement au Cycle d'enseignement
            $table->foreignId('cycle_id')
                  ->constrained('cycles')
                  ->cascadeOnDelete();

            $table->string('code', 30)->unique();  // Ex: CP1, 6EME, TLE_GEN, L3, BT_1
            $table->string('name');                // Ex: Cours Préparatoire 1ère année, Licence 3
            
            // Rattachement à un Examen / Diplôme
            $table->boolean('has_exam')->default(false); // Vrai si ce niveau prépare à un examen officiel
            $table->string('exam_name')->nullable();    // Ex: CEPE, BEPC, BAC, CAP, BTS, Licence

            // Frais de scolarité par défaut pour le niveau
            $table->decimal('tuition_fee', 12, 2)->default(0);

            $table->integer('sequence_order')->default(1); // Ordre d'avancement dans le cursus
            $table->boolean('is_active')->default(false);   // Permet au tenant d'activer/désactiver ce niveau

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};