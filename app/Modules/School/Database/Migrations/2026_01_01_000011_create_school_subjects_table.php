<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_subjects', function (Blueprint $table) {
            $table->id();
            
            // Clés étrangères
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teaching_unit_id')->nullable()->constrained()->nullOnDelete();

            // Surcharges / Configurations spécifiques
            $table->string('custom_name');              // ex: Développement Web Laravel, Algorithmique
            $table->string('code')->nullable();  // ex: ECUE-LARAVEL
            $table->string('color_code', 7)->default('#3B82F6'); // Couleur pour l'emploi du temps

            // Spécificités Universitaires / LMD
            $table->integer('credits')->nullable();              // Crédits de l'ECUE (si sous-découpé)
            $table->decimal('coefficient', 5, 2)->default(1.00); // Coefficient de la matière
            $table->integer('hours_cm')->default(0);             // Volume horaire Cours Magistral
            $table->integer('hours_td')->default(0);             // Volume horaire Travaux Dirigés
            $table->integer('hours_tp')->default(0);             // Volume horaire Travaux Pratiques

            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Unicité pour éviter qu'une école n'ajoute plusieurs fois la même matière dans une même UE
            $table->unique(['school_id', 'subject_id', 'teaching_unit_id'], 'school_subject_unit_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_subjects');
    }
};